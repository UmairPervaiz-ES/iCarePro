<?php

namespace App\Repositories\Practice\Interfaces\Department;

interface DepartmentRepositoryInterface
{
    public function list();

    public function store($request);

    public function edit($request);

    public function departmentEmployeeType($request);

    public function departmentEmployeeTypeUpdateStatus($request, $id);
}
