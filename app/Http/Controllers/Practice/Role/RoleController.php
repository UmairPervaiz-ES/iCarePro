<?php

namespace App\Http\Controllers\Practice\Role;

use App\Http\Controllers\Controller;
use App\Http\Requests\Practice\AssignPermissions;
use App\Http\Requests\Practice\StoreRole;
use App\Repositories\Practice\Interfaces\Role\RoleRepositoryInterface;

class RoleController extends Controller
{
    private RoleRepositoryInterface $roleRepository;

    public function __construct(RoleRepositoryInterface $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    /**
     * @OA\Get(
     *      path="/backend/api/practice/roles",
     *      operationId="listOfRoles",
     *      tags={"Practice"},
     *      summary="List of roles",
     *      description="List of roles",
     *      security={{"passport":{}}},
     *
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *           @OA\MediaType(
     *           mediaType="application/json",
     *          )
     *      ),
     *  )
     */

    public function list()
    {
        return $this->roleRepository->list();
    }

    /**
     * @OA\Get(
     *      path="/backend/api/practice/roles-pagination",
     *      operationId="listOfPaginatedRoles",
     *      tags={"Practice"},
     *      summary="List of paginated roles",
     *      description="List of paginated roles",
     *      security={{"passport":{}}},
     *
     *      @OA\Parameter(
     *      name="noOfRecords",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *     ),
     *
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *           @OA\MediaType(
     *           mediaType="application/json",
     *          )
     *      ),
     *  )
     */

    public function rolesPagination($noOfRecords)
    {
        return $this->roleRepository->rolesPagination($noOfRecords);
    }

    /**
     * @OA\Post(
     *      path="/backend/api/practice/add-role",
     *      operationId="addRole",
     *      tags={"Practice"},
     *      summary="Add role",
     *      description="Add role",
     *      security={{"passport":{}}},
     *      @OA\Parameter(
     *      name="name",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *           @OA\MediaType(
     *           mediaType="application/json",
     *          )
     *      ),
     *  )
     */

    public function addRole(StoreRole $request)
    {
        return $this->roleRepository->addRole($request);
    }

    /**
     * @OA\Post(
     *      path="/backend/api/practice/assign-permissions-to-role",
     *      operationId="assignPermissions",
     *      tags={"Practice"},
     *      summary="Assign permissins to a role",
     *      description="Assign permissins to a role",
     *      security={{"passport":{}}},
     *
     *      @OA\Parameter(
     *      name="role_id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *     ),
     *
     *      @OA\Parameter(
     *      name="role_name",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *     ),
     *
     *      @OA\Parameter(
     *      name="permission_ids[0]",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *     ),
     *
     *      @OA\Parameter(
     *      name="permission_ids[1]",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *     ),
     *
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *           @OA\MediaType(
     *           mediaType="application/json",
     *          )
     *      ),
     *  )
     */

    public function assignPermissions(AssignPermissions $request)
    {
        return $this->roleRepository->assignPermissions($request);
    }

    /**
     * @OA\Get(
     *      path="/backend/api/practice/permissions",
     *      operationId="listOfPermissions",
     *      tags={"Practice"},
     *      summary="List of permissions",
     *      description="List of permissions",
     *      security={{"passport":{}}},
     *
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *           @OA\MediaType(
     *           mediaType="application/json",
     *          )
     *      ),
     *  )
     */
    public function permissions()
    {
        return $this->roleRepository->permissions();
    }
}
