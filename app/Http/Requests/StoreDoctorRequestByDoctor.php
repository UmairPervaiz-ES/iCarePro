<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StoreDoctorRequestByDoctor extends FormRequest
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

            'license_number' => ['sometimes',
                Rule::when(($request->has('doctor_id') && $this->license_number != ""), Rule::unique('doctor_legal_information')->where(function ($query) {
                        $query->where('license_number', $this->license_number);
                    })->whereNot('doctor_id', $this->doctor_id) ),

            //     Rule::when(!$request->has('doctor_id'), Rule::unique('doctor_legal_information')->where(function ($query) {
            //             $query->where('license_number', $this->license_number);
            //         }) ),
                 ],

            'emirate_id' => ['sometimes',
                Rule::when(($request->has('doctor_id') && $this->emirate_id != ""), Rule::unique('doctor_legal_information')->where(function ($query) {
                        $query->where('emirate_id', $this->emirate_id);
                    })->whereNot('doctor_id', $this->doctor_id) ),


            //     Rule::when(!$request->has('doctor_id'), Rule::unique('doctor_legal_information')->where(function ($query) {
            //             $query->where('emirate_id', $this->emirate_id);
            //         }) ),
                 ],

            'passport_number' => ['sometimes',
                Rule::when(($request->has('doctor_id') && $this->passport_number != ""), Rule::unique('doctor_legal_information')->where(function ($query) {
                        $query->where('passport_number', $this->passport_number);
                    })->whereNot('doctor_id', $this->doctor_id) ),

            //     Rule::when(!$request->has('doctor_id'), Rule::unique('doctor_legal_information')->where(function ($query) {
            //             $query->where('passport_number', $this->passport_number);
            //         }) ),
                 ],

        ];
    }

    public function messages()
    {

        $return = [
            'license_number.required' => 'Please enter license number',
            'license_number.unique' => 'Entered license number is already in use',
            'passport_number.required' => 'Please enter passport number',
            'passport_number.unique' => 'Entered passport number is already in use',
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
