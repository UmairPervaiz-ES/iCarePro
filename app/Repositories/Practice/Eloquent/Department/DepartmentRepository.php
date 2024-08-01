<?php

namespace App\Repositories\Practice\Eloquent\Department;

use App\Http\Resources\Practice\DepartmentCollection;
use App\libs\Messages\PracticeGlobalMessageBook as PGMBook;
use App\Models\Department\Department;
use App\Models\Department\DepartmentEmployeeType;
use App\Repositories\Practice\Interfaces\Department\DepartmentRepositoryInterface;
use App\Traits\RespondsWithHttpStatus;
use Illuminate\Http\Response;

class DepartmentRepository implements DepartmentRepositoryInterface
{
    use RespondsWithHttpStatus;

    public function __construct(){}

    /**
     *  Description: Returns list of departments of a practice.
     *
     *  1) This method returns empty data array if no department is found against a practice.
     * @return DepartmentCollection
     */
    public function list(): DepartmentCollection
    {
        $departments = Department::with('departmentEmployeeTypes:department_id,id,name,status')->where('practice_id', $this->practice_id())->orderByDesc('created_at')->get();

        $this->response('List of departments', $departments, PGMBook::SUCCESS['DEPARTMENT_LIST'], 200);
        return new DepartmentCollection($departments);
    }

    /**
     *  Description: Stores department
     *
     *  1) Department name is unique for each practice.
     *  2) Returns department model instance.
     * @param $request
     * @return Response
     */
    public function store($request): Response
    {
        $department = Department::create([
            'practice_id' => $this->practice_id(),
            'name' => $request->name,
            'created_by' => $this->uniqueKey()
        ]);

        return $this->response($request->all(), $department, PGMBook::SUCCESS['DEPARTMENT_CREATED'], 201);
    }

    /**
     *  Description: Edit department name
     *
     *  1) Department name is unique for each practice.
     *  2) Returns department model instance.
     * @param $request
     * @return Response
     */
    public function edit($request): Response
    {
        $department = Department::where('id', $request->department_id)->first();
        if (!$department)
        {
            $response = $this->response($request->all(),null, PGMBook::FAILED['DEPARTMENT_NOT_FOUND'],400, false);
        }
        else
        {
            $department->update(['name' => $request->name]);
            $response = $this->response($request->all(), $department, PGMBook::FAILED['DEPARTMENT_UPDATED'], 200);
        }
        return $response;
    }

    /**
     *  Description: Stores department employee type against a department
     *
     *  1) Department employee type name field is unique for each practice.
     *  2) Returns DepartmentEmployeeType model instance
     * @param $request
     * @return Response
     */
    public function departmentEmployeeType($request): Response
    {
        $employeeType = array();
        foreach ($request->name as $name){
            $employeeType[] = DepartmentEmployeeType::create([
                'department_id' =>  $request->department_id,
                'practice_id' =>  $this->practice_id(),
                'name' =>  $name,
                'created_by' =>  $this->uniqueKey(),
                'status' =>  1,             // Adding status column inorder to show in API response
            ]);
        }

        return $this->response($request->all(), $employeeType, PGMBook::SUCCESS['DEPARTMENT_EMPLOYEE_TYPE'], 201);
    }

    /**
     *  Description: Updates department employee type status and name.
     *
     *  1) Department employee type name field is unique for each practice it is handled in front-end while updating.
     *  2) Incoming status value will be updated 0 or 1.
     *  3) Returns DepartmentEmployeeType model instance
     * @param $request
     * @param $id
     * @return Response
     */
    public function departmentEmployeeTypeUpdateStatus($request, $id): Response
    {
        $departmentEmployeeType = DepartmentEmployeeType::where('id', $id)->first();
        if (! $departmentEmployeeType)
        {
            $response = $this->response($request->all(), null, PGMBook::FAILED['DEPARTMENT_EMPLOYEE_TYPE_NOT_FOUND'], 400, false);
        }
        else
        {
            $departmentEmployeeType->update([
                'name' => $request->name,
                'status' => $request->status,
                'updated_by' => $this->uniqueKey()
            ]);
            $response = $this->response($request->all(), $departmentEmployeeType, PGMBook::SUCCESS['DEPARTMENT_EMPLOYEE_TYPE_UPDATED'], 200);
        }

        return $response;
    }
}
