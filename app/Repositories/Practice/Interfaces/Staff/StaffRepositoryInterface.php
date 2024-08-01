<?php

namespace App\Repositories\Practice\Interfaces\Staff;

interface StaffRepositoryInterface
{
    public function viewDetailsByStaffID($request);

    public function listOfStaff($request);

    public function store($request);

    public function emailWithCredentials($request);

    public function statusUpdate($request);
}
