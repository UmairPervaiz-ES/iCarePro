<?php

namespace App\Repositories\ConsentForm\Eloquent;

use App\libs\Messages\DoctorGlobalMessageBook as DGMBook;
use App\Models\ConsentForm\ConsentFormLog;
use App\Models\ConsentForm\ConsentFormType;
use App\Repositories\ConsentForm\Interfaces\ConsentFormRepositoryInterface;
use App\Traits\CreateOrUpdate;
use App\Traits\RespondsWithHttpStatus;

class ConsentFormRepository implements ConsentFormRepositoryInterface
{
    use RespondsWithHttpStatus;
    use CreateOrUpdate;

    /**
     * Description: Create/Update Consent Form Type
     * 1) If consent form type exist. It will be updated
     * 2) If consent form type not exist. It will be created
     * 3) Activity is logged
     * 4) Consent form types and success message is return
     *
     * @param  mixed $request
     * @return void
     */
    public function setConsentFormType($request)
    {
        $key = ['id' => $request['id']];

        // create/update consent form type
        $consentForm = $this->createOrUpdate('ConsentForm\ConsentFormType', $request->validated(), $key);

        if ($consentForm->wasRecentlyCreated) {
            $responseMessage = DGMBook::SUCCESS['CONSENT_FORM_TYPE_CREATED'];
            $status = 201;
        } else {
            $responseMessage = DGMBook::SUCCESS['CONSENT_FORM_TYPE_UPDATED'];
            $status = 200;
        }
        // Store Activity log. Return response
        return $this->response($request, $consentForm, $responseMessage, $status);
    }

    /**
     * Description: Create/Update Doctor Consent Form
     * 1) If consent form exist. It will be updated
     * 2) If consent form not exist. It will be created
     * 3) Activity is logged
     * 4) Consent forms and success message is return
     *
     * @param  mixed $request
     * @return void
     */
    public function setConsentForm($request)
    {
        $key = ['id' => $request['id']];

        // create/update consent form
        $consent_form = $this->createOrUpdate('ConsentForm\ConsentForm', $request->validated(), $key);

        if ($consent_form->wasRecentlyCreated) {
            $response_message = DGMBook::SUCCESS['CONSENT_FORM_CREATED'];
            $status = 201;
        } else {
            $response_message = DGMBook::SUCCESS['CONSENT_FORM_UPDATED'];
            $status = 200;
        }
        // Store Activity log. Return response
        return $this->response($request, $consent_form, $response_message, $status);
    }

    /**
     * Description: Get all Consent Forms
     * 1) Create method for get consent forms
     * 2) Activity is logged
     * 3) Consent forms and success message is return
     *
     * @return void
     */
    public function consentForms()
    {
        $concent_forms = $this->getConsentForms(null);

        // Store Activity log. Return response
        return $this->response(true, $concent_forms, DGMBook::SUCCESS['CONSENT_FORM_RECEIVED'], 200);
    }

    /**
     * Description: Get All Published Consent Forms 
     * 1) Create method for get published consent forms
     * 2) Activity is logged
     * 3) Consent forms and success message is return
     *
     * @return void
     */
    public function publishedConsentForms()
    {
        $concent_forms = $this->getPublishedConsentForms(null);

        // Store Activity log. Return response
        return $this->response(true, $concent_forms, DGMBook::SUCCESS['PUBLISHED_CONSENT_FORM_RECEIVED'], 200);
    }

    /**
     * 
     *  Description: Store Consent Form Response 
     * 1) Store validated response
     * 2) Activity is logged
     * 3) Consent form response and success message is return
     *
     * @param  mixed $request
     * @return void
     */
    public function addConsentLog($request)
    {
        foreach ($request->request as $value) {
            $key = [
                ['consent_form_type_id', $value['consent_form_type_id']],
                ['consent_form_id', $value['consent_form_id']],
                ['category_id', $value['category_id']]
            ];

            $data = [
                'consent_form_type_id' => $value['consent_form_type_id'],
                'consent_form_id' => $value['consent_form_id'],
                'consent_status' => $value['consent_status'],
                'category' => $value['category'],
                'category_id' => $value['category_id'],
            ];
            $consent_log = $this->createOrUpdate('ConsentForm\ConsentFormLog', $data, $key);
            $consent_response[] = $consent_log;
        }
        if ($consent_log->wasRecentlyCreated) {
            $response_message = DGMBook::SUCCESS['DOCTOR_CONSENT_FORM_CREATED'];
            $status = 201;
        } else {
            $response_message = DGMBook::SUCCESS['DOCTOR_CONSENT_FORM_UPDATED'];
            $status = 200;
        }

        // Store Activity log. Return response
        return $this->response($request, $consent_response, $response_message, $status);
    }

    /**
     * Description: Get Register Doctor Consent Forms 
     * 1) Set condition for get register doctor consent forms
     * 2) Use method for get doctor register consent forms
     * 3) Activity is logged
     * 4) Register doctor consent forms and success message is return
     *
     * @return void
     */
    public function registerDoctorConsentForms()
    {
        // condition for concent forms
        $condition = [
            ['category', 'DOCTOR'],
            ['sub_category', 'REGISTRATION']
        ];

        // get concent forms
        $concent_forms = $this->getConsentForms($condition);

        // Store Activity log. Return response
        return $this->response(true, $concent_forms, DGMBook::SUCCESS['REGISTER_DOCTOR_CONSENT_FORM_RECEIVED'], 200);
    }

    /**
     * Description: Get Register Doctor Published Consent Forms  
     * 1) Set condition for get register doctor published consent forms
     * 2) Use method for get register doctor published register consent forms
     * 3) Activity is logged
     * 4) Register doctor published consent forms and success message is return
     *
     * @return void
     */
    public function registerDoctorPublishedConsentForms()
    {
        // condition for concent forms
        $condition = [
            ['category', 'DOCTOR'],
            ['sub_category', 'REGISTRATION']
        ];

        // get published concent forms
        $concent_forms = $this->getPublishedConsentForms($condition);

        // Store Activity log. Return response
        return $this->response(true, $concent_forms, DGMBook::SUCCESS['REGISTER_DOCTOR_CONSENT_FORM_RECEIVED'], 200);
    }

    /**
     * Description:Get Register Patient Consent Forms  
     * 1) Set condition for get register patient consent forms
     * 2) Use method for get register patient consent forms
     * 3) Activity is logged
     * 4) Register patient consent forms and success message is return
     *
     * @return void
     */
    public function registerPatientConsentForms()
    {
        // condition for concent forms
        $condition = [
            ['category', 'PATIENT'],
            ['sub_category', 'REGISTRATION']
        ];

        // get concent forms
        $concent_forms = $this->getConsentForms($condition);

        // Store Activity log. Return response
        return $this->response(true, $concent_forms, DGMBook::SUCCESS['REGISTER_PATIENT_CONSENT_FORM_RECEIVED'], 200);
    }

    /**
     * Description: Get Register Patient Published Consent Forms  
     * 1) Set condition for get register patient published consent forms
     * 2) Use method for get register patient published consent forms
     * 3) Activity is logged
     * 4) Register patient published consent forms and success message is return
     *
     * @return void
     */
    public function registerPatientPublishedConsentForms()
    {
        // condition for concent forms
        $condition = [
            ['category', 'PATIENT'],
            ['sub_category', 'REGISTRATION']
        ];

        // get published concent forms
        $concent_forms = $this->getPublishedConsentForms($condition);

        // Store Activity log. Return response
        return $this->response(true, $concent_forms, DGMBook::SUCCESS['REGISTER_PATIENT_PUBLISHED_CONSENT_FORM_RECEIVED'], 200);
    }

    /**
     * Description: Get Consent Forms Response 
     * 1) Get authenticated doctor consent response
     * 3) Activity is logged
     * 4) Consent forms and success message is return
     *
     * @return void
     */
    public function consentLogResponse()
    {
        // get concent forms response
        $consent_response = ConsentFormLog::where('category_id', auth()->user()->id)->where('category', 'DOCTOR')->get();

        // Store Activity log. Return response
        return $this->response(true, $consent_response, DGMBook::SUCCESS['CONSENT_FORM_RESPONSE_RECEIVED'], 200);
    }

    /**
     * Description: Get Consent Forms Query 
     * 1) Generic Method to get consent form type depending on condition
     *
     * @param  mixed $condition
     * @return void
     */
    public function getConsentForms($condition)
    {
        return ConsentFormType::where($condition)
            ->with('consentForms')->has('consentForms')->get();
    }

    /**
     * 
     * Description: Get Published Consent Forms Query 
     * 1) Generic Method to get published consent form depending on condition
     *
     * @param  mixed $condition
     * @return void
     */
    public function getPublishedConsentForms($condition)
    {
        return ConsentFormType::where($condition)
            ->with(['publishConsentForm' => function ($query) {
                return $query->where('publish_status', 'ACTIVE')->latest('version');
            }])->has('publishConsentForm')->get();
    }
}
