<?php

namespace App\Http\Resources\Practice;

use App\Http\Resources\Doctor\DoctorAddressResource;
use App\Http\Resources\Doctor\DoctorLegalInformationResource;
use App\Http\Resources\Doctor\DoctorSpecializationResource;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use JsonSerializable;

class DoctorCollection extends ResourceCollection
{
    public static $wrap = 'doctors';
    /**
     * Transform the resource collection into an array.
     *
     * @param  Request  $request
     * @return array|Arrayable|JsonSerializable
     */
    public function toArray($request): array|JsonSerializable|Arrayable
    {
        return $this->collection->transform(function ($data) {
            return [
                'id'         => $data->doctor->id,
                'doctor_key'         => $data->doctor->doctor_key,
                'first_name'       => $data->doctor->first_name,
                'middle_name'       => $data->doctor->middle_name,
                'last_name'       => $data->doctor->last_name,
                'primary_email'       => $data->doctor->primary_email,
                'secondary_email'       => $data->doctor->secondary_email,
                'gender'       => $data->doctor->gender,
                'dob'       => $data->doctor->dob,
                'about_me'       => $data->doctor->about_me,
                'profile_photo_url'       => $data->doctor->profile_photo_url,
                'primary_phone_number'       => $data->doctor->country_code_primary_phone_number . $data->doctor->primary_phone_number,
                'secondary_phone_number'       => $data->doctor->country_code_secondary_phone_number . $data->doctor->secondary_phone_number,
                'address'       => new DoctorAddressResource($data->doctor->doctorAddress),
                'legal_information'       => new DoctorLegalInformationResource($data->doctor->doctorLegalInformation),
                'doctor_specializations'       =>  DoctorSpecializationResource::collection($data->doctor->doctorSpecializations),
                'license_photo_url'       => $data->doctor->license_photo_url,
                'passport_photo_url'       => $data->doctor->passport_photo_url,
                'emirate_photo_url'       => $data->doctor->emirate_photo_url,
                'marital_status'       => $data->doctor->marital_status,
                'kyc_status'       => $data->doctor->kyc_status,
                'is_active'       => $data->doctor->is_active,
                'created_at'       => Carbon::parse($data->doctor->created_at)->format('Y-m-d'),
            ];
        });
    }
}
