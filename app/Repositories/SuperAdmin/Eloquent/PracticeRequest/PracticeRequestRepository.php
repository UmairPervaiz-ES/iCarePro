<?php

namespace App\Repositories\SuperAdmin\Eloquent\PracticeRequest;

use App\Jobs\Practice\PracticeDetailReject;
use App\Jobs\Practice\PracticePasswordOneTime;
use App\Jobs\Practice\PracticeRegister as PracticePracticeRegister;
use App\Jobs\Practice\PracticeRegisterReject as PracticePracticeRegisterReject;
use App\libs\Messages\SuperAdminGlobalMessageBook;
use App\Models\Practice\InitialPractice;
use App\Models\Practice\Practice;
use App\Models\Subscription\SubscriptionPermission;
use App\Repositories\SuperAdmin\Interfaces\PracticeRequest\PracticeRequestRepositoryInterface;
use App\Traits\RespondsWithHttpStatus;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
class PracticeRequestRepository implements PracticeRequestRepositoryInterface
{
    use RespondsWithHttpStatus;

    /**
     *  Description: Function for retrieving initial practice pending requests list
     *  1) This method is used to list
     *  2) Activity is logged, and a success message is return
     * @param $request
     * @return Response
     */
    public function initialPracticeRequest($request): Response
    {
        $initialPractice = InitialPractice::where('status', 'Pending')->latest('id')->paginate($request['pagination']);
        return $this->response(true, $initialPractice, SuperAdminGlobalMessageBook::SUCCESS['INITIAL_PENDING'], 200);
    }

    /**
     *  Description: Function for super admin to update status of initial practice request
     *  1) This method is used for response
     *  2) If id is not exist ,error message will return
     *  3) If id exist then status is updated as Accepted or Rejected
     *  4) If response is Accepted then confirmation email is send with link to practice to add further details
     *  5) If response is Rejected then rejection email is send to practice
     *  2) Activity is logged, and a success message is return
     * @param mixed $request
     * @return Response
     */
    public function initialPracticeRequestResponse($request): Response
    {
        // find data by id
        $initialPractice = InitialPractice::find($request['id']);
        if (!$initialPractice) {
            $message = SuperAdminGlobalMessageBook::FAILED['PRACTICE_REQUEST_NOT_FOUND'];
            $status = 400;
            $initialPractice = false;
            $success = false;
        }
        else {
            // status is update
            $initialPractice->status = $request->status;
            $initialPractice->save();
            //if status is accepted then send email to practice register
            if ($request->status == "Accepted") {
                dispatch(new PracticePracticeRegister($initialPractice))->onQueue(config('constants.INITIAL_REQUEST_ACCEPT'));
            }
            //if status is rejected then send email
            elseif ($request->status == "Rejected") {
                dispatch(new PracticePracticeRegisterReject($initialPractice))->onQueue(config('constants.INITIAL_REQUEST_REJECT'));
            }
            $message = SuperAdminGlobalMessageBook::SUCCESS['SEND_EMAIL'];
            $status = 201;
            $success = true;
        }
        return $this->response($request, $initialPractice, $message, $status, $success);
    }

    /**
     *  Description: Function to retrieve practice pending request list
     *  1) This method is used to get list of pending request
     *  2) Activity is logged, and a success message is return
     * @param $request
     * @return Response|array
     */
    public function practiceRequestGet($request): Response|array
    {
        $practices = InitialPractice::
            with('practiceRequest','practiceRequest.practiceAddress', 'practiceRequest.practiceBillingAddress')
            ->where('status', 'Accepted')
            ->orderByDesc('created_at')
            ->paginate($request['records']);

        return $this->response(true, $practices, SuperAdminGlobalMessageBook::SUCCESS['PRACTICE_LIST'], 200);
    }

    /**
     * Description: Function for super admin to update pending practice request status
     *  1) This method is used to update practice request response
     *  2) If id does not exist ,error message will be returned
     *  3) If id exist then status is updated as Accepted or  Rejected
     *  4) If response is Accepted then confirmation email with credentials is sent to practice
     *  5) If response is Rejected then rejection email is sent to practice
     *  2) Activity is logged, and a success message is return
     * @param mixed $request
     * @return Response
     */
    public function practiceRequestResponse($request): Response
    {
        // find data by id
        $practice = Practice::with('initialPractice')->where('practice_registration_request_id', $request['id'])->first();

        if (!$practice) {
            $message = SuperAdminGlobalMessageBook::FAILED['PRACTICE_REQUEST_NOT_FOUND'];
            $status = 400;
            $practice = false;
            $success = false;
        }
        else {
            $password = Str::random(10);
            // status is update
            $practice->status = $request->status;
            if ($request->status == "Accepted") {
                $practice->password = bcrypt($password);
                $permissions = SubscriptionPermission::where('subscription_id', 1)->pluck('permission_id')->toArray();
                $role = Role::create(['guard_name' => 'practice-api', 'name' => 'practice-' . $practice->id . '@Admin']);
                $practice->assignRole($role);
                foreach ($permissions as $permission) {
                    DB::table('role_has_permissions')->insert([
                        'role_id' => $role->id,
                        'permission_id' => $permission,
                    ]);
                }
            } elseif ($request->status == "Rejected") {
                $practice->password = null;
            }
            $practice->save();


            //if status is accepted then send password by email
            if ($request->status == "Accepted") {

                dispatch(new PracticePasswordOneTime($practice, $password))->onQueue(config('constants.PRACTICE_REQUEST_ACCEPT'));
                $message = SuperAdminGlobalMessageBook::SUCCESS['PRACTICE_REGISTER_EMAIL'];
                $status = 201;
                $success = true;
            }
            //if status is rejected then send email
            elseif ($request->status == "Rejected") {
                dispatch(new PracticeDetailReject($practice))->onQueue(config('constants.PRACTICE_REQUEST_REJECT'));
                $message = SuperAdminGlobalMessageBook::SUCCESS['SEND_EMAIL'];
                $status = 201;
                $success = true;
            }
        }
        return $this->response($request, $practice, $message, $status, $success);
    }
}
