<?php

namespace App\Http\Controllers\Practice\Department;

use App\Http\Controllers\Controller;
use App\Http\Requests\Practice\Department\EmployeeType;
use App\Http\Requests\Practice\Department\StoreDepartment;
use App\Repositories\Practice\Interfaces\Department\DepartmentRepositoryInterface;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    private DepartmentRepositoryInterface $departmentRepository;

    public function __construct(DepartmentRepositoryInterface $departmentRepository)
    {
        $this->departmentRepository = $departmentRepository;
    }

    /**
     * @OA\Get(
     *      path="/backend/api/practice/departments",
     *      operationId="listOfDepartments",
     *      tags={"Practice"},
     *      summary="List of departments",
     *      description="List of departments",
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
        return $this->departmentRepository->list();
    }

    /**
     * @OA\Post(
     *      path="/backend/api/practice/add-department",
     *      operationId="addDepartment",
     *      tags={"Practice"},
     *      summary="Add department",
     *      description="Adding department",
     *      security={{"passport":{}}},
     *
     *      @OA\Parameter(
     *      name="name",
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
    public function store(StoreDepartment $request)
    {
      return $this->departmentRepository->store($request);
    }

    /**
     * @OA\Post(
     *      path="/backend/api/practice/edit-department",
     *      operationId="editDepartment",
     *      tags={"Practice"},
     *      summary="Edit department",
     *      description="Editing department name",
     *      security={{"passport":{}}},
     *
     *      @OA\Parameter(
     *      name="department_id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *     ),
     *
     *     @OA\Parameter(
     *      name="name",
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
    public function edit(StoreDepartment $request)
    {
      return $this->departmentRepository->edit($request);
    }

    /**
     * @OA\Post(
     *      path="/backend/api/practice/add-department-employee-type",
     *      operationId="addDepartmentEmployeeType",
     *      tags={"Practice"},
     *      summary="Add Employee type in a department",
     *      description="Adding employee types in a department",
     *      security={{"passport":{}}},
     *
     *      @OA\Parameter(
     *      name="department_id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *     ),
     *
     *     @OA\Parameter(
     *      name="name[0]",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *     ),
     *
     *     @OA\Parameter(
     *      name="name[1]",
     *      in="query",
     *      required=false,
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
    public function departmentEmployeeType(EmployeeType $request)
    {
        return $this->departmentRepository->departmentEmployeeType($request);
    }

    /**
     * @OA\Post(
     *      path="/backend/api/practice/department-employee-type-status-update",
     *      operationId="updateStatusForEmployeType",
     *      tags={"Practice"},
     *      summary="Update employee type status",
     *      description="Updating employee type status in a department",
     *      security={{"passport":{}}},
     *
     *      @OA\Parameter(
     *      name="id",
     *      in="path",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *     ),
     *
     *     @OA\Parameter(
     *      name="name",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *     ),
     *
     *     @OA\Parameter(
     *      name="status",
     *      in="query",
     *      required=true,
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
    public function departmentEmployeeTypeUpdateStatus(Request $request,$id)
    {
        return $this->departmentRepository->departmentEmployeeTypeUpdateStatus($request, $id);
    }
}
