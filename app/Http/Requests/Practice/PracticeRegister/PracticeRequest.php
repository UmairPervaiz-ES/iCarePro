<?php

namespace App\Http\Requests\Practice\PracticeRegister;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule;


class PracticeRequest extends FormRequest
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
    public function rules()
    {
        return [
            'practice_registration_request_id' => 'nullable|numeric',
            'subscription_id' => 'nullable|numeric',
            'practice_id' => 'nullable|numeric',
            'logo_url' => 'nullable|string',
            'password' => 'nullable|string',
            'tax_id' => 'required|max:39',
            'practice_npi' => 'required|max:14',
            'practice_taxonomy' => 'required|max:29',
            'facility_id' => 'required|max:39',
            'oid' => 'required|max:29',
            'clia_number' => 'required|max:29',
            'privacy_policy' => 'required|string',
            'address_line_1' => 'required|max:254|string',
            'address_line_2' => 'nullable|max:254|string',
            'country_id'=>'nullable|numeric',
            'city_id'=>'nullable|numeric',
            'state_id'=>'nullable|numeric',
            'zip_code'=>'nullable|max:14|string',
            'billing_address_line_1' => 'required|max:254|string',
            'billing_address_line_2' => 'nullable|max:254|string',
            'billing_country_id'=>'nullable|numeric',
            'billing_city_id'=>'nullable|numeric',
            'billing_state_id'=>'nullable|numeric',
            'billing_zip_code'=>'nullable|max:14||string', 
           

         ];
    }
    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success'   => false,
            'data'      => $validator->errors()
        ], 422));
    }
}
