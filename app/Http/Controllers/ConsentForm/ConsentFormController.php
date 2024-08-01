<?php

namespace App\Http\Controllers\ConsentForm;

use App\Http\Controllers\Controller;
use App\Http\Requests\ConsentForm\ConsentForm;
use App\Http\Requests\ConsentForm\ConsentFormType;
use App\Http\Requests\ConsentForm\ConsentLog;
use App\Repositories\ConsentForm\Interfaces\ConsentFormRepositoryInterface;

class ConsentFormController extends Controller
{
    private ConsentFormRepositoryInterface $consentRepository;
    public function __construct(ConsentFormRepositoryInterface $consentRepository)
    {
        $this->consentRepository = $consentRepository;
    }

    /**
     * Create/Update Consent Form Type
     *
     * @param  mixed $request
     * @return void
     */

    /**
     * @OA\Post(
     *      path="/backend/api/practice/set-consent-form-type",
     *      operationId="setConsentFormType",
     *      tags={"Practice"},
     *      summary="Set consent form type",
     *      description="set consent form type",
     *      security={{"passport":{}}},
     *      @OA\Parameter(
     *      name="category",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="sub_category",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="type",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *      ),
     *     @OA\Parameter(
     *      name="is_required",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="practice_id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *          )
     *      ),
     *     @OA\Parameter(
     *      name="created_by",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *           @OA\MediaType(
     *           mediaType="application/json",
     *          )
     *      ),
     *  )
     */
    public function setConsentFormType(ConsentFormType $request)
    {

        return $this->consentRepository->setConsentFormType($request);
    }

    /**
     * Create/Update Consent Form
     *
     * @param  mixed $request
     * @return void
     */

    /**
     * @OA\Post(
     *      path="/backend/api/practice/set-consent-form",
     *      operationId="setConsentForm",
     *      tags={"Practice"},
     *      summary="Set consent form",
     *      description="set consent form",
     *      security={{"passport":{}}},
     *      @OA\Parameter(
     *      name="type_id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="version",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="content",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="content_arabic",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *      ),
     *     @OA\Parameter(
     *      name="content_status",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="publish_status",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *          )
     *      ),
     *     @OA\Parameter(
     *      name="created_by",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *      name="updated_by",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *      name="published_at",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *      name="deactivated_at",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *           @OA\MediaType(
     *           mediaType="application/json",
     *          )
     *      ),
     *  )
     */
    public function setConsentForm(ConsentForm $request)
    {

        return $this->consentRepository->setConsentForm($request);
    }
    /**
     * Get all Consent Forms
     *
     * @return void
     */


    /**
     * @OA\Get(
     *      path="/backend/api/practice/consent-forms",
     *      operationId="doctorRegistrationConsentForm",
     *      tags={"Practice"},
     *
     *      summary="all consent forms",
     *      description="all consent forms",
     *     security={
     *         {"passport": {}},
     *   },

     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *           @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *      ),
     *  )
     */

    public function consentForms()
    {

        return $this->consentRepository->consentForms();
    }
    /**
     * Get Published Consent Forms
     *
     * @param  mixed $request
     * @return void
     */

    /**
     * @OA\Get(
     *      path="/backend/api/practice/publish-consent-forms",
     *      operationId="publishConsentForms",
     *      tags={"Practice"},
     *
     *      summary="published consent forms",
     *      description="get all published consent forms",
     *     security={
     *         {"passport": {}},
     *   },

     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *           @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *      ),
     *  )
     */

    public function publishedConsentForms()
    {

        return $this->consentRepository->publishedConsentForms();
    }

    /**
     * Store Consent Response.
     *
     * @param  mixed $request
     * @return void
     */

    /**
     * @OA\Post(
     *      path="/backend/api/practice/add-consent-log",
     *      operationId="addConsentLog",
     *      tags={"Practice"},
     *      summary="Add Consent Response",
     *      description="Add Consent Response",
     *      security={{"passport":{}}},
     *      @OA\Parameter(
     *      name="consent_form_type_id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="consent_form_id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="consent_status",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="category",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="category_id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *           @OA\MediaType(
     *           mediaType="application/json",
     *          )
     *      ),
     *  )
     */
    public function addConsentLog(ConsentLog $request)
    {
        return $this->consentRepository->addConsentLog($request);
    }

    /**
     * Get Register Doctor Consent Forms
     *
     * @param  mixed $request
     * @return void
     */

    /**
     * @OA\Get(
     *      path="/backend/api/practice/register-doctor-consent-forms",
     *      operationId="registerDoctorConsentForms",
     *      tags={"Practice"},
     *
     *      summary="registration doctor consent forms",
     *      description="registration doctor consent forms",
     *     security={
     *         {"passport": {}},
     *   },

     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *           @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *      ),
     *  )
     */
    public function registerDoctorConsentForms()
    {

        return $this->consentRepository->registerDoctorConsentForms();
    }

    /**
     * Get Register Doctor Published Consent Forms
     *
     * @param  mixed $request
     * @return void
     */

    /**
     * @OA\Get(
     *      path="/backend/api/practice/register-doctor-publish-consent-forms",
     *      operationId="registerDoctorPublishedConsentForms",
     *      tags={"Practice"},
     *
     *      summary="register doctor publish consent forms",
     *      description="register doctor  publish consent forms",
     *     security={
     *         {"passport": {}},
     *   },

     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *           @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *      ),
     *  )
     */
    public function registerDoctorPublishedConsentForms()
    {

        return $this->consentRepository->registerDoctorPublishedConsentForms();
    }

    /**
     * Get Register Patient Consent Forms
     *
     * @param  mixed $request
     * @return void
     */

    /**
     * @OA\Get(
     *      path="/backend/api/practice/register-patient-consent-forms",
     *      operationId="registerPatientConsentForms",
     *      tags={"Practice"},
     *
     *      summary="registration patient consent forms",
     *      description="registration patient consent forms",
     *     security={
     *         {"passport": {}},
     *   },

     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *           @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *      ),
     *  )
     */
    public function registerPatientConsentForms()
    {

        return $this->consentRepository->registerPatientConsentForms();
    }

    /**
     * Get Register Patient Published Consent Forms
     *
     * @param  mixed $request
     * @return void
     */

    /**
     * @OA\Get(
     *      path="/backend/api/practice/register-patient-publish-consent-forms",
     *      operationId="registerPatientPublishedConsentForms",
     *      tags={"Practice"},
     *
     *      summary="register patient publish consent forms",
     *      description="register patient  publish consent forms",
     *     security={
     *         {"passport": {}},
     *   },

     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *           @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *      ),
     *  )
     */
    public function registerPatientPublishedConsentForms()
    {

        return $this->consentRepository->registerPatientPublishedConsentForms();
    }

    /**
     * @OA\Get(
     *      path="/backend/api/doctors/consent-log-response",
     *      operationId="consentLogResponse",
     *      tags={"Doctor"},
     *
     *      summary="consent log response",
     *      description="consent log response",
     *     security={
     *         {"passport": {}},
     *   },

     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *           @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *      ),
     *  )
     */
    public function consentLogResponse()
    {
        return $this->consentRepository->consentLogResponse();
    }
}
