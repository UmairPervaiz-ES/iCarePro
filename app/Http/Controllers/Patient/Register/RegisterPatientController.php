<?php

namespace App\Http\Controllers\Patient\Register;

use App\Http\Controllers\Controller;
use App\Http\Requests\Patient\Register\BasicInfoUpdateRequest;
use App\Http\Requests\Patient\Register\RegisterRequest;
use App\Http\Requests\Patient\Register\SetPasswordRequest;
use App\Http\Requests\Patient\Register\UpdatePasswordRequest;
use App\Repositories\Patient\Interfaces\Register\RegisterRepositoryInterface;
use Illuminate\Http\Request;

class RegisterPatientController extends Controller
{
    private RegisterRepositoryInterface $registerRepository;
    public function __construct(RegisterRepositoryInterface $registerRepository)
    {
        $this->registerRepository = $registerRepository;
    }


    /**
     * @OA\Post(
     *      path="/backend/api/patient/patient-register",
     *      operationId="patientRegister",
     *      tags={"Patient"},
     *      summary="Patient registration",
     *      description="Patient registration ",
     *      security={{"passport":{}}},
     *      @OA\Parameter(
     *      name="country_code",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *     ),
     *      @OA\Parameter(
     *      name="phone_number",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *     ),
     *      @OA\Parameter(
     *      name="first_name",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *     ),
     *      @OA\Parameter(
     *      name="last_name",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *     ),
     *      @OA\Parameter(
     *      name="email",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *     ),
     *      @OA\Parameter(
     *      name="gender",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string",
     *           enum={"Male", "Female", "Other"}
     *      )
     *     ),
     *      @OA\Parameter(
     *      name="dob",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *     ),
     *      @OA\Parameter(
     *      name="middle_name",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *     ),
     *         @OA\RequestBody(
     *         required=false,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     description="Profile Photo",
     *                     property="file",
     *                     type="file",
     *                ),
     *             )
     *         )
     *     ),
     *     @OA\Parameter(
     *      name="password",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *     ),
     *      @OA\Parameter(
     *      name="practice_id",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *           @OA\MediaType(
     *           mediaType="application/json",
     *          )
     *      ),
     *  )
     */
    public function registerPatient(RegisterRequest $request)
    {
        return $this->registerRepository->registerPatient($request);
    }
    /**
     * @OA\Post(
     *      path="/backend/api/patient/check-patient",
     *      operationId="checkPatient",
     *      tags={"Patient"},
     *      summary="Check patient exist or not",
     *      description="Check Patient Exist or Not by passing country_code and phone_number of patient ",
     *      security={{"passport":{}}},
     *      @OA\Parameter(
     *      name="country_code",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="phone_number",
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

    public function checkPatientExist(Request $request)
    {
        return $this->registerRepository->checkPatientExist($request);
    }
    /**
     * @OA\Post(
     *      path="/backend/api/patient/check-patient-login",
     *      operationId="checkPatientLogin",
     *      tags={"Patient"},
     *      summary="Check Patient Login ",
     *      description="Check Patient Login also check  mobile number verified or not password set or not ",
     *      security={{"passport":{}}},
     *      @OA\Parameter(
     *      name="country_code",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="phone_number",
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
    public function checkPatientLogin(Request $request)
    {
        return $this->registerRepository->checkPatientLogin($request);
    }

    /**
     * @OA\Post(
     *      path="/backend/api/patient/send-verification-code",
     *      operationId="sendVerificationCode",
     *      tags={"Patient"},
     *      summary="Send verification code ",
     *      description="Patient login first send verification code to patient mobile number for verification",
     *      security={{"passport":{}}},
     *      @OA\Parameter(
     *      name="phone_number",
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


    public function sendMobileVerificationCode(Request $request)
    {
        return $this->registerRepository->sendMobileVerificationCode($request);
    }
    /**
     * @OA\Post(
     *      path="/backend/api/patient/verify-verification-code",
     *      operationId="verifyVerificationCode",
     *      tags={"Patient"},
     *      summary="Verify number by passing verification code",
     *      description="Verify number by passing verification code",

     *      security={{"passport":{}}},
     *      @OA\Parameter(
     *      name="phone_number",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="verified_code",
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

    public function verifyMobileVerificationCode(Request $request)
    {
        return $this->registerRepository->verifyMobileVerificationCode($request);
    }
    /**
     * @OA\Post(
     *      path="/backend/api/patient/set-password",
     *      operationId="setPassword",
     *      tags={"Patient"},
     *      summary="Set password against number account",
     *       description="Set password against number account",

     *      security={{"passport":{}}},
     *      @OA\Parameter(
     *      name="phone_number",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="password",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="confirm_password",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
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

    public function setPassword(SetPasswordRequest $request)
    {
        return $this->registerRepository->setPassword($request);
    }

      /**
     * @OA\Post(
     *      path="/backend/api/patient/update-password",
     *      operationId="updatePassword",
     *      tags={"Patient"},
     *      summary="Patient change password ",
     *      description="Patient change our password ",

     *      security={{"passport":{}}},
     *      @OA\Parameter(
     *      name="old_password",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="new_password",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="confirm_password",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
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

    public function updatePassword(UpdatePasswordRequest $request)
    {
        return $this->registerRepository->updatePassword($request);
    }
       /**
     * @OA\Post(
     *      path="/backend/api/practice/edit-patient-basic-info",
     *      operationId="editPatientBasicInformation",
     *      tags={"Patient"},
     *      summary="Edit patient basic information",
     *      description="Edit patient basic information",
     *      security={{"passport":{}}},
     *      @OA\Parameter(
     *      name="phone_number",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *     ),
     *      @OA\Parameter(
     *      name="first_name",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *     ),
     *      @OA\Parameter(
     *      name="last_name",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *     ),
     *     @OA\Parameter(
     *      name="middle_name",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *     ),
     *      @OA\Parameter(
     *      name="gender",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *     ),
     *      @OA\Parameter(
     *      name="dob",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *           @OA\MediaType(
     *           mediaType="application/json",
     *          )
     *      ),
     *  )
     */

    public function editPatientBasicInformation(BasicInfoUpdateRequest $request)
    {
        return $this->registerRepository->editPatientBasicInformation($request);
    }

      /**
     * @OA\Post(
     *      path="/backend/api/patient/change-patient-phone-number",
     *      operationId="changePatientPhoneNumber",
     *      tags={"Patient"},
     *      summary="Change patient phone number",
     *      description="Change patient phone number",
     *      security={{"passport":{}}},
     *      @OA\Parameter(
     *      name="phone_number",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *     ),
     *      @OA\Parameter(
     *      name="new_phone_number",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *     ),
     *      @OA\Parameter(
     *      name="country_code",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *           @OA\MediaType(
     *           mediaType="application/json",
     *          )
     *      ),
     *  )
     */



    public function changePhoneNumber(Request $request)
    {
        return $this->registerRepository->changePhoneNumber($request);
    }

      /**
     * @OA\Post(
     *      path="/backend/api/patient/verify-otp",
     *      operationId="verifyPatientPhoneNumber",
     *      tags={"Patient"},
     *      summary="Verify patient phone number",
     *      description="Verify patient phone number",
     *      security={{"passport":{}}},
     *      @OA\Parameter(
     *      name="phone_no_code",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *     ),
     *      @OA\Parameter(
     *      name="phone_no",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *     ),
     *      @OA\Parameter(
     *      name="OTP",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *           @OA\MediaType(
     *           mediaType="application/json",
     *          )
     *      ),
     *  )
     */

    public function verifyOtp(Request $request)
    {
        return $this->registerRepository->verifyOtp($request);
    }


    /**
     * @OA\Post(
     *      path="/backend/api/patient/send-OTP",
     *      operationId="sendOTPToPatientPhoneNumber",
     *      tags={"Patient"},
     *      summary="Send OTP to patient phone number",
     *      description="Send OTP to patient phone number",
     *      security={{"passport":{}}},
     *      @OA\Parameter(
     *      name="phone_no_code",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *     ),
     *      @OA\Parameter(
     *      name="phone_no",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *           @OA\MediaType(
     *           mediaType="application/json",
     *          )
     *      ),
     *  )
     */

    public function sendOTP()
    {
        return $this->registerRepository->sendOTP();
    }



}

