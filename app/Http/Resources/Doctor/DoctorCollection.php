<?php

namespace App\Http\Resources\Doctor;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\ResourceCollection;

class DoctorCollection extends ResourceCollection
{
    public static $wrap = 'doctors';

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return $this->collection->transform(function ($data) {
            return [
                'id'         => $data->id,
                'doctor_key'         => $data->doctor_key,
                'first_name'       => $data->first_name,
                'middle_name'       => $data->middle_name,
                'last_name'       => $data->last_name,
                'primary_email'       => $data->primary_email,
                'secondary_email'       => $data->secondary_email,
                'gender'       => $data->gender,
                'dob'       => $data->dob,
                'about_me'       => $data->about_me,
                'profile_photo_url'       => $data->profile_photo_url,
                'country_code_primary_phone_number' => $data->country_code_primary_phone_number,
                'primary_phone_number'       => $data->primary_phone_number,
                'country_code_secondary_phone_number' => $data->country_code_secondary_phone_number,
                'secondary_phone_number'       => $data->secondary_phone_number,
                'address'       => new DoctorAddressResource($data->doctorAddress),
                'legal_information'       => new DoctorLegalInformationResource($data->doctorLegalInformation),
                'doctor_specializations'       =>  DoctorSpecializationResource::collection($data->doctorSpecializations),
                'license_photo_url'       => $data->license_photo_url,
                'passport_photo_url'       => $data->passport_photo_url,
                'emirate_photo_url'       => $data->emirate_photo_url,
                'marital_status'       => $data->marital_status,
                'kyc_status'       => $data->kyc_status,
                'is_active'       => $data->is_active,
                'created_at'       => Carbon::parse($data->created_at)->format('d-m-Y'),
            ];
        });
    }
}
