<?php

namespace App\Repositories\Insurance\Eloquent\Insurance;

use App\libs\Messages\PracticeGlobalMessageBook as PGMBook;
use App\Models\Insurance\Insurance;
use App\Repositories\Insurance\Interfaces\Insurance\InsuranceRepositoryInterface;
use App\Traits\RespondsWithHttpStatus;

class InsuranceRepository implements InsuranceRepositoryInterface
{
    use RespondsWithHttpStatus;

    /** 
     *  Description: This function create insurance
     *  1) This method is used to create
     *  2) If fields not validated,field is required message will return
     *  3) In case of fields validated , create insurance
     *  4) Activity is logged, and a success message is return
     * @param  mixed $request
     * @return Response
     */
    public function  addInsurance($request)
    {
        $insurance = Insurance::create([
            'patient_id' => $request['patient_id'],
            'insurance_name' => $request['insurance_name'],
            'company' => $request['company'],
            'insurance_id' => $request['insurance_id'],
            'percentage' => $request['percentage'],
            'amount' => $request['amount'],
            'created_by' => auth()->id(),
        ]);
        return $this->response($request,  $insurance, PGMBook::SUCCESS['INSURANCE'], 201);
    }

    /**
     *  Description: This function list insurance relate to patient
     *  1) This method is used to create
     *  2) Get id (patient_id ) form param
     *  3) Show insurance list related to patient
     *  4) Activity is logged, and a success message is return
     * @param  mixed $id
     * @return void
     */
    public function insuranceList($id)
    {

        $insurances = Insurance::where('patient_id', $id)->latest('id')->get();
        return $this->response(true,  $insurances, PGMBook::SUCCESS['INSURANCE_LIST'], 200);
    }


    /**
     *  Description: This function list insurance (id , company) relate to patient
     *  1) This method is used to create
     *  2) Get id (patient_id ) form param
     *  3) Show insurance list related to patient
     *  4) Activity is logged, and a success message is return
     * @param  mixed $id
     * @return void
     */

    public function insuranceCompanyList($id)
    {
        $insurances = Insurance::where('patient_id', $id)->select('id', 'company')->distinct('company')->get();
        return $this->response(true,  $insurances, PGMBook::SUCCESS['INSURANCE_COMPANY_LIST'], 200);
    }
}
