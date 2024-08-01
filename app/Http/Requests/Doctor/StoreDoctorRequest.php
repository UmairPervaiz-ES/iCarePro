<?php

namespace App\Http\Requests\Doctor;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StoreDoctorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(Request $request)
    {
        return [
            'suffix' => ['required' , Rule::in(['Miss', 'Mrs', 'Ms', 'Mr'])],
            'first_name' => ['required' , 'string', 'max:29'],
            'last_name' => ['required' , 'string', 'max:29'],

            'primary_email' => ['required', 'string', 'email', 'max:254',
                Rule::when($request->has('doctor_id'), Rule::unique('doctors')->ignore(auth()->id())),
                Rule::when(!$request->has('doctor_id'), 'unique:doctors'),
            ],

            'secondary_email' => ['nullable','string', 'email', 'max:254'],
            'gender' => ['required' , Rule::in(['Male', 'Female', 'Transgender', 'Prefer not to say', 'Other'])],
            'dob' => ['required', 'date'],
            'country_code_primary_phone_number' => ['required' , 'max:9'],
            'primary_phone_number' => ['required' , 'max:14'],
            'country_code_secondary_phone_number' => ['max:9'],
            'secondary_phone_number' => ['max:14'],

            'license_number' => ['sometimes', 'max:49' ,'required',
                Rule::when($request->has('doctor_id'), Rule::unique('doctor_legal_information')->where(function ($query) {
                    $query->where('license_number', $this->license_number);
                })->whereNot('doctor_id', $this->doctor_id) ),

                Rule::when(!$request->has('doctor_id'), Rule::unique('doctor_legal_information')->where(function ($query) {
                    $query->where('license_number', $this->license_number);
                }) ),
            ],

            'emirate_id' => ['sometimes', 'required', 'max:19',
                Rule::when($request->has('doctor_id'), Rule::unique('doctor_legal_information')->where(function ($query) {
                    $query->where('emirate_id', $this->emirate_id);
                })->whereNot('doctor_id', $this->doctor_id) ),

                Rule::when(!$request->has('doctor_id'), Rule::unique('doctor_legal_information')->where(function ($query) {
                    $query->where('emirate_id', $this->emirate_id);
                }) ),
            ],

            'passport_number' => ['sometimes', 'max:34',
                Rule::when($request->has('doctor_id'), Rule::unique('doctor_legal_information')->where(function ($query) {
                    $query->where('passport_number', $this->passport_number);
                })->whereNot('doctor_id', $this->doctor_id) ),

                Rule::when(!$request->has('doctor_id'), Rule::unique('doctor_legal_information')->where(function ($query) {
                    $query->where('passport_number', $this->passport_number);
                }) ),
            ],

            'current_country_id' => ['required' , 'numeric'],
            'current_state_id' => ['required' , 'numeric'],
            'current_city_id' => ['required' , 'numeric'],
            'home_town_country_id' => ['required' , 'numeric'],
            'home_town_state_id' => ['required' , 'numeric'],
            'home_town_city_id' => ['required' , 'numeric'],
            'current_address_1' => ['required' , 'string' , 'max:254'],
            'current_zip_code' => ['required' , 'string','max:14'],
            'home_town_address_1' => ['required' , 'string','max:254'],
            'home_town_zip_code' => ['required' , 'string','max:14'],
            'specialization_id' => ['required'],
//            'license_photo_url' => ['required', 'mimes:doc,pdf,docx,jpeg,jpg,png'],
//            'passport_photo_url' => ['required', 'mimes:doc,pdf,docx,jpeg,jpg,png'],
//            'emirate_photo_url' => ['required', 'mimes:doc,pdf,docx,jpeg,jpg,png'],
//            'profile_photo_url' => ['sometimes', 'mimes:jpeg,jpg,png'],
            // COMMENTED FOR NOW
//            'file_path' => ['required'],
//            'file_path.*' => ['mimes:doc,pdf,docx,jpeg,jpg,png'],  // 10240 = max 10 MB
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        $return = [
            'suffix.required' => 'Please select suffix',
            'first_name.required' => 'Please enter first name',
            'middle_name.required' => 'Please enter middle name',
            'last_name.required' => 'Please your last name',
            'primary_email.required' => 'Please enter primary email',
            'primary_email.unique' => 'Primary email ID is already taken',
            'secondary_email.required' => 'Please enter secondary email',
            'secondary_email.unique' => 'Secondary email ID is already taken',
            'gender.required' => 'Please select gender',
            'dob.required' => 'Please select date of birth',
            'country_code_primary_phone_number.required' => 'Please select a country code for primary phone number',
            'primary_phone_number.required' => 'Please enter your primary number',
            'license_number.required' => 'Please enter license number',
            'license_number.unique' => 'Entered license number is already in use',
            // 'emirate_id.required' => 'Please enter CNIC number',
            // 'emirate_id.unique' => 'Entered CNIC number is already in use',
            'passport_number.required' => 'Please enter passport number',
            'passport_number.unique' => 'Entered passport number is already in use',
            'marital_status.required' => 'Please select marital status',
            'current_country_id.required' => 'Please select your current country',
            'current_state_id.required' => 'Please select your current state',
            'current_city_id.required' => 'Please select your current city',
            'home_town_country_id.required' => 'Please select your home town country',
            'home_town_state_id.required' => 'Please select your home town country',
            'home_town_city_id.required' => 'Please select your home town city',
            'current_address_1.required' => 'Please enter your current address 1',
            'current_zip_code.required' => 'Please enter your current zip code',
            'home_town_address_1.required' => 'Please enter your home town address 1',
            'home_town_zip_code.required' => 'Please enter your home town zip code',
            'specialization_id.required' => 'Please select specializations',
//            'license_photo_url.required' => 'Please upload your license.',
//            'license_photo_url.mimes' => 'license should be of type doc,pdf,docx,jpeg,jpg,png',
//            'passport_photo_url.required' => 'Please upload your passport.',
//            'passport_photo_url.mimes' => 'Passport should be of type doc,pdf,docx,jpeg,jpg,png',
//            'emirate_photo_url.required' => 'Please upload your emirate id.',
//            'emirate_photo_url.mimes' => 'Emirate ID should be of type doc,pdf,docx,jpeg,jpg,png',
//            'profile_photo_url.mimes' => 'Profile photo should be of type jpeg,jpg,png',
//            'file_path.required' => 'Please select document(s) to upload',
//            'file_path.*.mimes' => 'Document type should be of type doc,pdf,docx,jpeg,jpg,png',
        ];

        if($this->practice_country == 'Pakistan'){
            $return['emirate_id.required'] = 'Please enter CNIC Number';
            $return['emirate_id.unique'] = 'Entered CNIC Number is already in use';
        }
        else if($this->practice_country == 'United Arab Emirates'){
            $return['emirate_id.required'] = 'Please enter emirate id';
            $return['emirate_id.unique'] = 'Entered emirate id is already in use';
        }
        else if($this->practice_country == 'United States'){
            $return['emirate_id.required'] = 'Please enter SSN Number';
            $return['emirate_id.unique'] = 'Entered SSN Number is already in use';
        }
        
        return $return;
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success'   => false,
            'error'      => $validator->errors()
        ], 422));
    }
}
