<?php

namespace App\Http\Requests\Doctor;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateCurrentAddressInformation extends FormRequest
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
            'current_address_1'     => 'required|max:254',
            'current_state_id'     => 'required|numeric',
            'current_country_id'     => 'required|numeric',
            'current_city_id'     => 'required|numeric',
            'current_zip_code'     => 'required|max:14',
        ];
    }

    public function messages()
    {
        return [
            'current_address_1.required'    => 'Please enter current address 1.',
            'current_state_id.required'    => 'Please select current state.',
            'current_country_id.required'    => 'Please select current country.',
            'current_city_id.required'    => 'Please select current city.',
            'current_zip_code.required'    => 'Please enter current zip code.',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success'   => false,
            'error'      => $validator->errors()
        ], 422));
    }
}
