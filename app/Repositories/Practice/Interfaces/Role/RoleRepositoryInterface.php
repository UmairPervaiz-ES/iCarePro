<?php

namespace App\Repositories\Practice\Interfaces\Role;

interface RoleRepositoryInterface
{
    public function list();

    public function rolesPagination($noOfRecords);

    public function addRole($request);

    public function permissions();
}
