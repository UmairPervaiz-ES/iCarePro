<?php

namespace App\Repositories\Practice\Eloquent\Staff;

use App\Helper\Helper;
use App\Filters\Staff\DepartmentName;
use App\Filters\Staff\DepartmentTypeName;
use App\Filters\Staff\FirstName;
use App\Filters\Staff\LastName;
use App\Filters\Staff\MiddleName;
use App\Filters\Staff\Role as StaffRole;
use App\Filters\Staff\Search;
use App\Http\Resources\Staff\StaffCollection;
use App\Http\Resources\Staff\StaffResource;
use App\Jobs\Staff\SendCredentialsToStaff;
use App\libs\Messages\PracticeGlobalMessageBook as PGMBook;
use App\Models\User\User;
use App\Repositories\Practice\Interfaces\Staff\StaffRepositoryInterface;
use App\Traits\RespondsWithHttpStatus;
use Carbon\Carbon;
use http\Client;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\Passport;
use Spatie\Permission\Models\Role;
use Illuminate\Pipeline\Pipeline;

class StaffRepository implements StaffRepositoryInterface
{
    use RespondsWithHttpStatus;

    public function __construct(){}

    /**
     *  Description: Staff ID is passed to this function to get staff details by ID and returns StaffResource
     *  1) This method is used to get staff details by ID
     *  2) Staff ID is passed in request by user_id key name
     *  3) Activity is logged, and a success message is return
     *
     * @param $request
     * @return Response|StaffResource
     */
    public function viewDetailsByStaffID($request): Response|StaffResource
    {
        $staff = User::with('department','department_employee_type', 'currentCountry:id,name',
            'currentState:id,name', 'currentCity:id,name', 'homeTownCountry:id,name', 'homeTownState:id,name', 'homeTownCity:id,name')
            ->where(['id' => $request->user_id, 'practice_id' => $this->practice_id()])
            ->first();

        if (!$staff)
        {
            $response = $this->response($request->all(), null, PGMBook::FAILED['USER_NOT_FOUND'], 400, false);
        }
        else
        {
            $response = $this->response($request->all(), new StaffResource($staff), PGMBook::SUCCESS['STAFF_DETAILS'], 200);
        }
        return $response;
    }

    /**
     *  Description: Search function to get list of staffs. Search is done on first name, department name,
     *  role name or department employee type name basis
     *  1) This method is used to get staffs
     *  2) Activity is logged, and a success message is return with StaffResourceCollection
     *
     * @param $request
     * @return StaffCollection
     */
    public function listOfStaff($request): StaffCollection
    {
        $staffs = app(Pipeline::class)
            ->send(User::query())
            ->through([
                FirstName::class,
                DepartmentName::class,
                StaffRole::class,
                DepartmentTypeName::class,
                Search::class,
                MiddleName::class,
                LastName::class
            ])
            ->thenReturn()
            ->with('department_employee_type')
            ->where('practice_id', $this->practice_id())
            ->where('user_key', '!=', $this->uniqueKey())
            ->latest('id')
            ->paginate($request['pagination']);

        // Get user key. Generate message for log
        $message = $this->uniqueKey() . '-' . PGMBook::SUCCESS['STAFF_LIST'];
        Helper::activityLog($message, json_encode($request->all()), json_encode($staffs));

        return new StaffCollection($staffs);
    }

    /**
     *  Description: Function to store staff. Department id, role id and department employee type (not required)
     *  is added along with staff information
     *  1) Department and role IDs are required
     *  2) Role is assigned along with its permissions
     *  3) If user_id key is present in the request it means that incoming request is for update that user if not present
     *     than new user will be created
     *  4) Unique email property for secondary email is not required and implemented
     *
     * @param $request
     * @return Response
     */
    public function store($request): Response
    {
        $role = Role::with('permissions')->where('id', $request->role_id)->first();

        if (!$role){
            $response = $this->response($request->all(), null, PGMBook::FAILED['ROLE_NOT_FOUND'], 400, false);
        }

        else
        {

            if ($request->user_id)
            {
                $user = User::where(['id' => $request->user_id, 'practice_id' => $this->practice_id()])->first();
                if (!$user)
                {
                    $response = $this->response($request->all(), null, PGMBook::FAILED['USER_NOT_FOUND'], 400, false);
                }
                else
                {
                    $data = $this->storeUser($request, $role);
                    $data['updated_by'] = $data['created_by'];
                    unset($data['created_by']);
                    $user->update($data);

                    $response = $this->response($request->all(), $user, PGMBook::SUCCESS['STAFF_UPDATED'], 200);
                }
            }
            else
            {
                $user = User::create($this->storeUser($request, $role));
                $user->update(['user_key' => 'staff-'.$user->id]);

                dispatch(new SendCredentialsToStaff($user->password, $user))->onQueue(config('constants.SEND_CREDENTIALS_TO_STAFF'));

                $response = $this->response($request->all(), $user, PGMBook::SUCCESS['STAFF_ADDED'], 201);
            }
            $user->syncRoles($role)->syncPermissions($role->permissions);
        }

        return $response;
    }

    /**
     *  Description: Function to mail credentials to staff added previously. Password is generated and emailed to staff
     *  1) Staff id as user_id key is passed in request
     *  2) Password is generated using otpGenerator helper function
     *  3) Email is sent to staff containing his account password
     *
     * @param $request
     * @return Response
     */
    public function emailWithCredentials($request): Response
    {
        $user = User::where('id', $request->user_id)->first();
        if (!$user)
        {
            $response = $this->response($request->all(), null, PGMBook::FAILED['USER_NOT_FOUND'], 400, false);
        }
        else
        {
            $password = Helper::otpGenerator();
            $user->update([
                'password' => \Hash::make($password),
                'credentials_send_at' => Carbon::now(),
                'updated_by' => $this->uniqueKey(),
            ]);
            // Keeping credential logs
            Helper::credentialLog($user->id, Auth::getDefaultDriver(),0, $password);

            dispatch(new SendCredentialsToStaff($password, $user))->onQueue(config('constants.SEND_CREDENTIALS_TO_STAFF'));
            $response = $this->response($request->all(), $user, PGMBook::SUCCESS['STAFF_CREDENTIALS_SENT'], 200);
        }

        return $response;
    }

    /**
     *  Description: Private function used by store function. All required fields related to save staff is returned
     *
     * @param $request
     * @param $role
     * @return array
     */
    private function storeUser($request, $role): array
    {
        return [
            'practice_id' => $this->practice_id(),
            'department_id' => $request->department_id,
            'department_employee_type_id' => $request->department_employee_type_id,
            'role_id' => $role->id,
            'role_name' => str_replace('practice-'. $this->practice_id(). '@','',$role->name),     // Replacing Practice ID along with '@' sign from Role name
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'secondary_email' => $request->secondary_email,
            'country_code_phone_number' => $request->country_code_phone_number,
            'phone_number' => $request->phone_number,
            'country_code_secondary_phone_number' => $request->country_code_secondary_phone_number,
            'secondary_phone_number' => $request->secondary_phone_number,
            'gender' => $request->gender,
            'dob' => $request->dob,
            'profile_photo_url' => $request->profile_photo_url,
            'home_address_1' => $request->home_address_1,
            'home_address_2' => $request->home_address_2,
            'home_town_country_id' => $request->home_town_country_id,
            'home_town_state_id' => $request->home_town_state_id,
            'home_town_city_id' => $request->home_town_city_id,
            'current_zip_code' => $request->current_zip_code,
            'current_address_1' => $request->current_address_1,
            'current_address_2' => $request->current_address_2,
            'current_country_id' => $request->current_country_id,
            'current_state_id' => $request->current_state_id,
            'current_city_id' => $request->current_city_id,
            'home_zip_code' => $request->home_zip_code,
            'created_by' => $this->uniqueKey(),
        ];
    }

    /**
     *  Description: Function to update staff status and staff model instance is returned on success
     *  1) Staff id as user_id key is passed in request
     *  2) Activity is logged and a response is sent
     *
     * @param $request
     * @return Response
     */
    public function statusUpdate($request): Response
    {
        $staff = User::where(['id' => $request->user_id, 'practice_id' => $this->practice_id()])->first();
        if (!$staff)
        {
            $response = $this->response($request->all(), null, PGMBook::FAILED['USER_NOT_FOUND'], 400, false);
        }
        else
        {
            $staff->update(['is_active' => $request->status]);
            $response = $this->response($request->all(), $staff, PGMBook::SUCCESS['STAFF_STATUS_UPDATE'], 200);
        }

        return $response;
    }
}
