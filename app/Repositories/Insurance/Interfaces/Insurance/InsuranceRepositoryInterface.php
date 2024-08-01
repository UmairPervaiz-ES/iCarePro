<?php

namespace App\Repositories\Insurance\Interfaces\Insurance;

interface InsuranceRepositoryInterface 
{
    public function addInsurance($request);
    public function insuranceList($id);
    public function insuranceCompanyList($id);


}