<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Doctor\UpdateAboutMe;
use App\Http\Requests\Doctor\AddDoctorFee;
use App\Http\Requests\Doctor\OffDate;
use App\Http\Requests\Doctor\RequestOtpToUpdatePrimaryEmail;
use App\Http\Requests\Doctor\SlotStoreRequest;
use App\Http\Requests\Doctor\UpdateContactInformation;
use App\Http\Requests\Doctor\UpdateCurrentAddressInformation;
use App\Http\Requests\Doctor\UpdatePersonalInformation;
use App\Http\Requests\Doctor\UpdatePrimaryEmail;
use App\Http\Requests\Doctor\UploadDocument;
use App\Repositories\Doctor\Interfaces\DoctorRepositoryInterface;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    private DoctorRepositoryInterface $doctorRepository;

    public function __construct(DoctorRepositoryInterface $doctorRepository)
    {
        $this->doctorRepository = $doctorRepository;
    }

    /**
     * @OA\Get(
     *      path="/backend/api/doctor/{doctor_id}",
     *      operationId="doctorDetailsByID",
     *      tags={"Doctor"},
     *      security={
     *         {"passport": {}},
     *      },
     *      summary="Details by ID",
     *      description="Retrieving details by doctor ID",
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
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *           @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *      ),
     *  )
     */

    public function getDetailsByID($id)
    {
        return $this->doctorRepository->getDetailsByID($id);
    }

    /**
     * @OA\Post(
     *      path="/backend/api/doctor/list-of-slots/{doctor_id}",
     *      operationId="doctorListOfSlots",
     *      tags={"Doctor"},
     *      security={
     *         {"passport": {}},
     *      },
     *      summary="List of slots",
     *      description="Retrieving list of slots",
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
     *      @OA\Parameter(
     *      name="pagination",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *     ),
     *
     *      @OA\Parameter(
     *      name="page",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *     ),
     *
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *           @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *      ),
     *  )
     */

    public function listOfSlots(Request $request, $id)
    {
        return $this->doctorRepository->listOfSlots($request, $id);
    }

    /**
     * @OA\Post(
     *      path="/backend/api/doctor/request-otp-to-update-doctor-primary-email",
     *      operationId="requestOTPToUpdatePrimaryEmail",
     *      tags={"Doctor"},
     *      summary="Request for OTP",
     *      description="OTP is requested by entering new primary email ID and emailed to doctor's entered new primary email ID
     *          but email id is not updated unless valid OTP is verified",
     *      security={{"passport":{}}},
     *      @OA\Parameter(
     *      name="update_primary_email",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *           @OA\MediaType(
     *           mediaType="application/json",
     *          )
     *      ),
     *  )
     */

    public function requestOtpToUpdatePrimaryEmail(RequestOtpToUpdatePrimaryEmail $request)
    {
        return $this->doctorRepository->requestOtpToUpdatePrimaryEmail($request);
    }

    /**
     * @OA\Post(
     *      path="/backend/api/doctor/update-doctor-primary-email",
     *      operationId="updateDoctorPrimaryEmail",
     *      tags={"Doctor"},
     *      summary="Update Primary Email ID",
     *      description="Updating primary email ID by entering OTP sent to requested new primary email ID",
     *      security={{"passport":{}}},
     *      @OA\Parameter(
     *      name="otp",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *           @OA\MediaType(
     *           mediaType="application/json",
     *          )
     *      ),
     *  )
     */

    public function updatePrimaryEmail(UpdatePrimaryEmail $request)
    {
        return $this->doctorRepository->updatePrimaryEmail($request);
    }

    /**
     * @OA\Get (
     *      path="/backend/api/doctor/doctor-fee-list",
     *      operationId="doctorFeeList",
     *      tags={"Doctor"},
     *      summary="Fees list",
     *      description="Retrieving list of fees",
     *      security={{"passport":{}}},
     *      @OA\Parameter(
     *      name="id",
     *      in="path",
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

    public function doctorFee(Request $request, $id)
    {
        return $this->doctorRepository->doctorFee($request, $id);
    }

    /**
     * @OA\Post(
     *      path="/backend/api/doctor/add-doctor-fee",
     *      operationId="addDoctorFee",
     *      tags={"Doctor"},
     *      summary="Adding Fee",
     *      description="Entering fee by sending amount",
     *      security={{"passport":{}}},
     *      @OA\Parameter(
     *      name="doctor_id",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *     ),
     *
     *      @OA\Parameter(
     *      name="amount",
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

    public function addDoctorFee(AddDoctorFee $request)
    {
        return $this->doctorRepository->addDoctorFee($request);
    }

    /**
     * @OA\Post(
     *      path="/backend/api/doctor/update-doctor-fee-status/",
     *      operationId="updateDoctorFeeStatus",
     *      tags={"Doctor"},
     *      summary="Update fee status",
     *      description="Updating fee status by sending that doctor fee's ID",
     *      security={{"passport":{}}},
     *
     *      @OA\Parameter(
     *      name="doctor_id",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *     ),
     *
     *      @OA\Parameter(
     *      name="id",
     *      in="path",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *           @OA\MediaType(
     *           mediaType="application/json",
     *          )
     *      ),
     *  )
     */

    public function updateDoctorFeeStatus(Request $request, $id)
    {
        return $this->doctorRepository->updateDoctorFeeStatus($request, $id);
    }

    /**
     * @OA\Post(
     *      path="/backend/api/doctor/add-slot/",
     *      operationId="addDoctorSlot",
     *      tags={"Doctor"},
     *      summary="Add slot",
     *      description="Adding slot",
     *      security={{"passport":{}}},
     *      @OA\Parameter(
     *      name="doctor_id",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *     ),
     *
     *      @OA\Parameter(
     *      name="date_from",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *     ),
     *
     *     @OA\Parameter(
     *      name="date_to",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *     ),
     *
     *     @OA\Parameter(
     *      name="time_from",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *     ),
     *
     *     @OA\Parameter(
     *      name="time_to",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *     ),
     *
     *     @OA\Parameter(
     *      name="slot_time",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *     ),
     *
     *     @OA\Parameter(
     *      name="days[0]",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *     ),
     *
     *     @OA\Parameter(
     *      name="days[1]",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *           @OA\MediaType(
     *           mediaType="application/json",
     *          )
     *      ),
     *  )
     */

    public function addSlot(SlotStoreRequest $request)
    {
       return $this->doctorRepository->addSlot($request);
    }

    /**
     * @OA\Post(
     *      path="/backend/api/doctor/publish-slot/",
     *      operationId="publishSlot",
     *      tags={"Doctor"},
     *      summary="Publishing slot",
     *      description="Publishing slot by sending slot ID, can send more than one IDs as an array",
     *      security={{"passport":{}}},
     *
     *      @OA\Parameter(
     *      name="doctor_id",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *     ),
     *
     *     @OA\Parameter(
     *      name="ids[0]",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *     ),
     *
     *     @OA\Parameter(
     *      name="ids[1]",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *           @OA\MediaType(
     *           mediaType="application/json",
     *          )
     *      ),
     *  )
     */

    public function publishSlot(Request $request)
    {
        return $this->doctorRepository->publishSlot($request);
    }

    /**
     * @OA\Post(
     *      path="/backend/api/doctor/deactivate-slot",
     *      operationId="deactivateSlot",
     *      tags={"Doctor"},
     *      summary="Deactivating slot",
     *      description="Deactivating slot by passing slot ID and checking whether there are any appointments too, if found will be canceled",
     *      security={{"passport":{}}},
     *
     *      @OA\Parameter(
     *      name="doctor_id",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *     ),
     *
     *
     *     @OA\Parameter(
     *      name="id",
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

    public function deactivateSlot(Request $request)
    {
        return $this->doctorRepository->deactivateSlot($request);
    }

    /**
     * @OA\Get  (
     *      path="/backend/api/doctor/list-of-off-dates/",
     *      operationId="listOfOffDates",
     *      tags={"Doctor"},
     *      summary="Listing off dates",
     *      description="Retreiving list of off dates",
     *      security={{"passport":{}}},
     *
     *      @OA\Parameter(
     *      name="doctor_id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *     ),
     *
     *      @OA\Parameter(
     *      name="pagination",
     *      in="path",
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

    public function listOfOffDates($doctor_id, $pagination)
    {
       return $this->doctorRepository->listOfOffDates($doctor_id, $pagination);
    }

    /**
     * @OA\Post(
     *      path="/backend/api/doctor/add-off-dates/",
     *      operationId="addDoctorOffDates",
     *      tags={"Doctor"},
     *      summary="Adding off dates",
     *      description="Adding off dates",
     *      security={{"passport":{}}},
     *
     *      @OA\Parameter(
     *      name="doctor_id",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *     ),
     *
     *     @OA\Parameter(
     *      name="dates[0]",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *     ),
     *
     *     @OA\Parameter(
     *      name="dates[1]",
     *      in="query",
     *      required=false,
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

    public function addOffDates(OffDate $request)
    {
       return $this->doctorRepository->addOffDates($request);
    }

    /**
     * @OA\Post(
     *      path="/backend/api/doctor/delete-off-dates/",
     *      operationId="deleteDoctorOffDates",
     *      tags={"Doctor"},
     *      summary="Deleting Off dates",
     *      description="Deleting Off dates",
     *      security={{"passport":{}}},
     *
     *      @OA\Parameter(
     *      name="doctor_id",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *     ),
     *
     *     @OA\Parameter(
     *      name="dates[0]",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *     ),
     *
     *     @OA\Parameter(
     *      name="dates[1]",
     *      in="query",
     *      required=false,
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

    public function deleteOffDates(Request $request)
    {
        return $this->doctorRepository->deleteOffDates($request);
    }

    /**
     * @OA\Post(
     *      path="/backend/api/doctor/delete-document",
     *      operationId="deleteDoctorDocument",
     *      tags={"Doctor"},
     *      summary="Deleting document",
     *      description="Deleting document by send document's ID",
     *      security={{"passport":{}}},
     *
     *      @OA\Parameter(
     *      name="doctor_id",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *     ),
     *
     *     @OA\Parameter(
     *      name="id",
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

    public function deleteDocument(Request $request)
    {
        return $this->doctorRepository->deleteDocument($request);
    }

    /**
     * @OA\Post(
     *      path="/backend/api/doctor/upload-document",
     *      operationId="uploadDoctorDocument",
     *      tags={"Doctor"},
     *      summary="Uploading document",
     *      description="Uploading document",
     *      security={{"passport":{}}},
     *
     *      @OA\Parameter(
     *      name="doctor_id",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *     ),
     *
     *      @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  type="object",
     *                  @OA\Property(
     *                      property="file_paths[]",
     *                      type="array",
     *                      @OA\Items(
     *                          type="file",
     *                          format="binary",
     *                      ),
     *                  )
     *              )
     *          )
     *      ),
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

    public function uploadDocument(UploadDocument $request)
    {
        return $this->doctorRepository->uploadDocument($request);
    }

    /**
     * @OA\Post(
     *      path="/backend/api/doctor/update-specialization",
     *      operationId="updateDoctorSpecialization",
     *      tags={"Doctor"},
     *      summary="Updating specializations",
     *      description="Updating specializations by sending selected array of specialization IDs",
     *      security={{"passport":{}}},
     *
     *      @OA\Parameter(
     *      name="doctor_id",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *     ),
     *
     *     @OA\Parameter(
     *      name="specialization_ids[0]",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *     ),
     *
     *     @OA\Parameter(
     *      name="specialization_ids[1]",
     *      in="query",
     *      required=false,
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

    public function updateSpecialization(Request $request)
    {
        return $this->doctorRepository->updateSpecialization($request);
    }

    /**
     * @OA\Post(
     *      path="/backend/api/doctor/update-personal-information",
     *      operationId="updateDoctorPersonalInformation",
     *      tags={"Doctor"},
     *      summary="Updating personal information",
     *      description="Updating personal information along with his specializations",
     *      security={{"passport":{}}},
     *
     *      @OA\Parameter(
     *      name="doctor_id",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *     ),
     *
     *     @OA\Parameter(
     *      name="first_name",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *     ),
     *
     *     @OA\Parameter(
     *      name="middle_name",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *     ),
     *
     *     @OA\Parameter(
     *      name="last_name",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *     ),
     *
     *     @OA\Parameter(
     *      name="gender",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string",
     *           enum={"Male", "Female", "Transgender", "Prefer not to say", "Other"}
     *      )
     *     ),
     *
     *     @OA\Parameter(
     *      name="marital_status",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string",
     *           enum={"Unknown","Married","Single","Divorced","Separated", "Widowed", "Partner"}
     *      )
     *     ),
     *
     *     @OA\Parameter(
     *      name="specializationIDs[0]",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *     ),
     *
     *     @OA\Parameter(
     *      name="specializationIDs[1]",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *     ),
     *
     *     @OA\Parameter(
     *      name="specializationIDs[2]",
     *      in="query",
     *      required=false,
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

    public function updatePersonalInformation(UpdatePersonalInformation $request)
    {
        return $this->doctorRepository->updatePersonalInformation($request);
    }

    /**
     * @OA\Post(
     *      path="/backend/api/doctor/about-me",
     *      operationId="updateAboutMe",
     *      tags={"Doctor"},
     *      summary="Update about me",
     *      description="Updating about me",
     *      security={{"passport":{}}},
     *
     *      @OA\Parameter(
     *      name="doctor_id",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *     ),
     *
     *      @OA\Parameter(
     *      name="about_me",
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

    public function updateAboutMe(UpdateAboutMe $request)
    {
        return $this->doctorRepository->updateAboutMe($request);
    }

    /**
     * @OA\Post(
     *      path="/backend/api/doctor/update-contact-information",
     *      operationId="updateContactInformation",
     *      tags={"Doctor"},
     *      summary="Update contact information",
     *      description="Updating contact information",
     *      security={{"passport":{}}},
     *
     *      @OA\Parameter(
     *      name="doctor_id",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *     ),
     *
     *      @OA\Parameter(
     *      name="primary_phone_number",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *     ),
     *
     *      @OA\Parameter(
     *      name="secondary_phone_number",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *     ),
     *
     *      @OA\Parameter(
     *      name="secondary_email`",
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

    public function updateContactInformation(UpdateContactInformation $request)
    {
        return $this->doctorRepository->updateContactInformation($request);
    }

    /**
     * @OA\Post(
     *      path="/backend/api/doctor/updateCurrentAddress",
     *      operationId="updateCurrentAddress",
     *      tags={"Doctor"},
     *      summary="Update current address",
     *      description="Updating current address",
     *      security={{"passport":{}}},
     *
     *      @OA\Parameter(
     *      name="doctor_id",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *     ),
     *
     *      @OA\Parameter(
     *      name="current_address_1",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *     ),
     *      @OA\Parameter(
     *      name="current_address_2",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *     ),
     *
     *      @OA\Parameter(
     *      name="current_country_id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="interger"
     *      )
     *     ),
     *
     *     @OA\Parameter(
     *      name="current_state_id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *     ),
     *
     *      @OA\Parameter(
     *      name="current_city_id`",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *     ),
     *
     *      @OA\Parameter(
     *      name="current_zip_code`",
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

    public function updateCurrentAddress(UpdateCurrentAddressInformation $request)
    {
        return $this->doctorRepository->updateCurrentAddress($request);
    }

    /**
     * @OA\Post(
     *      path="/backend/api/doctor/doctor-patient-list",
     *      operationId="doctorPatientList",
     *      tags={"Doctor"},
     *
     *      summary="Doctor patient list",
     *      description="Retrieving doctor's patients list",
     *      security={{"passport": {}},},
     *      @OA\Parameter(
     *          name="pagination",
     *          in="query",
     *          required=false,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="first_name",
     *          in="query",
     *          required=false,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="middle_name",
     *          in="query",
     *          required=false,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="last_name",
     *          in="query",
     *          required=false,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="patient_key",
     *          in="query",
     *          required=false,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="status",
     *          in="query",
     *          required=false,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="phone_number",
     *          in="query",
     *          required=false,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *           @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *      ),
     *  )
     */

    public function doctorPatientList(Request $request)
    {
        return $this->doctorRepository->doctorPatientList($request);
    }

    /**
     * @OA\Get(
     *      path="/backend/api/doctor/stats",
     *      operationId="doctorDashboardStats",
     *      tags={"Doctor"},
     *
     *      summary="Dashboard Stats",
     *      description="Showing all stats for doctor in dashboard getting count of
     *      total doctor patients, doctor Appointment, today appointment, Upcoming Appointment, cancelled Appointment, completed Appointment.",
     *      security={{"passport": {}},},
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *           @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *      ),
     *  )
     */

    public function doctorDashboardStats()
    {
        return $this->doctorRepository->doctorDashboardStats();
    }

    /**
     * @OA\Get(
     *      path="/backend/api/doctor/appointment-chart",
     *      operationId="doctorDashboardAppointmentPiChart",
     *      tags={"Doctor"},
     *
     *      summary="Dashboard Appointment Pi Chart",
     *      description="Dashboard Appointment Pi Chart return count of total, upcoming, cancelled and  completed appointments.",
     *      security={{"passport": {}},},
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *           @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *      ),
     *  )
     */

    public function doctorAppointmentPiChart()
    {
        return $this->doctorRepository->doctorAppointmentPiChart();
    }

    /**
     * @OA\Get(
     *      path="/backend/api/doctor/list-of-registration-requests",
     *      operationId="listOfDoctorRegistrationRequests",
     *      tags={"Doctor"},
     *      summary="Practice registration requests",
     *      description="List of requests sent to doctor by practice to get resgistered as a doctor to his practice",
     *      security={{"passport":{}}},
     *
     *      @OA\Parameter(
     *      name="pagination",
     *      in="path",
     *      required=true,
     *      @OA\Schema(
     *           type="integer",
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

    public function listOfDoctorRegistrationRequests($pagination)
    {
        return $this->doctorRepository->listOfDoctorRegistrationRequests($pagination);
    }

    /**
     * @OA\Post(
     *      path="/backend/api/doctor/update-registration-request-status",
     *      operationId="updateDoctorRegistrationRequestStatus",
     *      tags={"Doctor"},
     *      summary="Update request status sent by practice",
     *      description="Updating request status sent by practicet to doctor to register in his practice",
     *      security={{"passport":{}}},
     *
     *      @OA\Parameter(
     *      name="request_id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer",
     *      )
     *     ),
     *
     *     @OA\Parameter(
     *      name="status",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string",
     *           enum={ "Accepted","Rejected"}
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

    public function updateDoctorRegistrationRequestStatus(Request $request)
    {
        return $this->doctorRepository->updateDoctorRegistrationRequestStatus($request);
    }

    /**
     * @OA\Post(
     *      path="/backend/api/doctor/dated-appointments",
     *      operationId="calendarViewForAppintments",
     *      tags={"Doctor"},
     *      summary="Doctors dated appointments list for calendar view",
     *      security={
     *         {"passport": {}},
     *      },
     *      description="Dated appointments list without appointments status rescheduled and cancelled are retreived and display in a calendar",
     *
     *      @OA\Parameter(
     *          name="start_date",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *
     *     @OA\Parameter(
     *          name="end_date",
     *          in="query",
     *          required=false,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *           @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *      ),
     *  )
     */

    public function calendarAppointmentsViewDates(Request $request)
    {
        return $this->doctorRepository->calendarAppointmentsViewDates($request);
    }

    /**
     * @OA\Post (
     *      path="/backend/api/doctor/notifications",
     *      operationId="notifications",
     *      tags={"Doctor"},
     *      summary="Retreving all notifications",
     *      description="Retreiving all notifications",
     *      security={{"passport":{}}},
     *
     *      @OA\Parameter(
     *      name="pagination",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer",
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

    public function allNotifications(Request $request)
    {
        return $this->doctorRepository->allNotifications($request);
    }

    /**
     * @OA\Post(
     *      path="/backend/api/doctor/markNotificationAsRead",
     *      operationId="markDoctorNotificationAsRead",
     *      tags={"Doctor"},
     *      summary="Marking notification as read",
     *      description="Marking notifiaction as read",
     *      security={{"passport":{}}},
     *
     *      @OA\Parameter(
     *      name="notification_id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string",
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

    public function markNotificationAsRead(Request $request)
    {
        return $this->doctorRepository->markNotificationAsRead($request);
    }

    /**
     * @OA\Post(
     *      path="/backend/api/doctor/markAllNotificationsAsRead",
     *      operationId="markDoctorAllNotificationsAsRead",
     *      tags={"Doctor"},
     *      summary="Marking all notifications as read",
     *      description="Marking all notifiactions as read",
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

    public function markAllNotificationsAsRead(Request $request)
    {
        return $this->doctorRepository->markAllNotificationsAsRead($request);
    }
}
