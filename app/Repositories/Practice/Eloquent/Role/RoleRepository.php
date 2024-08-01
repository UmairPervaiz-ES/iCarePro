<?php

namespace App\Repositories\Practice\Eloquent\Role;

use App\Helper\Helper;
use App\Http\Resources\Practice\RoleCollection;
use App\Http\Resources\Practice\RolePaginationCollection;
use App\libs\Messages\PracticeGlobalMessageBook as PGMBook;
use App\Models\User\User;
use App\Repositories\Practice\Interfaces\Role\RoleRepositoryInterface;
use App\Traits\RespondsWithHttpStatus;
use Illuminate\Http\Response;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleRepository implements RoleRepositoryInterface
{
    use RespondsWithHttpStatus;

    public function __construct(){}

    /**
     *  Description: Function to get roles of practice. Returns list of roles of practice
     *  1) This method returns empty data array if no role is found against a practice
     *
     * @return RoleCollection
     */
    public function list(): RoleCollection
    {
        $roles = Role::where('name', 'iLike', 'practice-'.$this->practice_id().'@'.'%')
            ->whereNot('name', 'iLike', 'practice-'.$this->practice_id().'@'.'Admin')
            ->has('permissions')
            ->get();

        $this->response($roles, $roles, PGMBook::SUCCESS['ROLE_LIST'], 200);
        return new RoleCollection($roles);
    }

    /**
     *  Description: Function to get paginated roles of practice
     *  1) This method returns empty data array if not role is found against a practice
     *  2) Paginated roles of practice are returned as a response
     *
     * @param $noOfRecords
     * @return RolePaginationCollection
     */
    public function rolesPagination($noOfRecords): RolePaginationCollection
    {
        $roles = Role::with('permissions:id,name')->where('name', 'iLike','practice-'.$this->practice_id().'@'.'%')
            ->where('name', '!=', 'practice-'.$this->practice_id().'@'.'Admin')
            ->orderByDesc('created_at')
            ->paginate($noOfRecords);

        $this->response($noOfRecords, $roles, PGMBook::SUCCESS['ROLE_LIST'], 200);
        return new RolePaginationCollection($roles);
    }

    /**
     *  Description: Function to store role. Stores role by concatenating practice ID with role name in order to prevent
     *  unique role name constraint
     *  1) Role name is passed to this function and api guard is assigned to it
     *  2) If role name is already present then status code of 409 with success value false is return in response
     *  3) Activity is logged and a response is sent
     *
     * @param $request
     * @return Response
     */
    public function addRole($request): Response
    {
        $roleName = 'practice-'.$this->practice_id() . '@' . $request->name;        // Adding @ sign inorder to create unique role name
        $role = Role::where(['name' => $roleName, 'guard_name' => 'api'])->first();

        if ($role)
        {
            $role['name'] = str_replace('practice-'. $this->practice_id(). '@','',$role->name);
            $response = $this->response($request->all(), $role, PGMBook::FAILED['ROLE_EXIST'], 422, false);
        }
        else
        {
            $role = Role::create([
                'name' => $roleName,
                'guard_name' => 'api',
            ]);
            $role['name'] = str_replace('practice-'. $this->practice_id(). '@','',$role->name);
            $response = $this->response($request->all(), $role, PGMBook::SUCCESS['ROLE_ADDED'], 201);
        }

        return $response;
    }

    /**
     *  Description: Function to assign permissions to role and role model instance in returned
     *  1) Role id is sent in request.
     *  2) If role is present than permissions ids sent in request are assigned to that role.
     *  3) All user's permissions are updated having that role.
     *  4) Activity is logged and a response is sent
     *
     * @param $request
     * @return Response
     */
    public function assignPermissions($request): Response
    {
        $roles = Role::where('name', 'iLike', 'practice-'.$this->practice_id().'@'.'%')
            ->where('name', '!=', 'practice-'.$this->practice_id().'@Admin')
            ->get();

        if (!$roles->contains('id', $request->role_id)) {
            $response = $this->response($request->all(), null, PGMBook::FAILED['ROLE_NOT_FOUND'], 400, false);
        }elseif ($roles->except(['id' => $request->role_id])->contains('name', $request->role_name)) {
            $response = $this->response($request->all(), $roles->only($request->role_id), PGMBook::FAILED['ROLE_EXIST'], 409, false);
        }else {
            $permissions = Permission::whereIn('id', $request->permission_ids)->get();

            $role = $roles->where('id', $request->role_id)->first();
            $role->syncPermissions($permissions);
            $role->update(['name' => 'practice-'.$this->practice_id().'@'.$request->role_name]);
            $role['name'] = str_replace('practice-'. $this->practice_id(). '@', '', $role->name);
            $response = $this->response($request->all(), $role, PGMBook::SUCCESS['PERMISSIONS_ASSIGNED'], 200);
        }

        return $response;
    }

    /**
     *  Description: Function to get list of permissions and authenticate practice permissions with guard name 'api' are returned
     *  1) Permissions are categorized.
     *  2) Activity is logged and a response is sent
     *  3) Returns categorized permissions
     */
    public function permissions(): Response
    {
        $role = Role::with(['permissions' => function ($query) {
            return $query->where('guard_name', 'api')
                ->whereIn('id', [89,90,91,92,93,94,95,96,101,102,103,104,121,122,123,124,125,126,127,128,129,130,131,132]);
        }])->where('name', '=', auth()->user()->getRoleNames()[0])->first();

        if (!$role) {
            $response = $this->response(null, null, PGMBook::FAILED['ROLE_NOT_FOUND'], 400, false);
        }else {
            $permissions = $role->permissions->groupBy(function ($val) {
                return substr($val->name, 0, strpos($val->name, "-"));
            });
            $response = $this->response(null, $permissions, PGMBook::SUCCESS['PERMISSIONS_LIST'], 200);
        }

        return $response;
    }

}
