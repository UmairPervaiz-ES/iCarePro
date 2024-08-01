<?php

namespace App\Http\Requests\Patient\PatientHistory;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class PatientEmploymentRequest extends FormRequest
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
            'patient_id' => 'required|numeric',
            'occupation' => 'required|string|max:49',
            'employer_name' => 'required|string|max:49',
            'employer_address' => 'nullable|string|max:254',
            'industry' => 'nullable|string|max:254',
            'country_id' => 'nullable|numeric',
            'city_id' => 'nullable|numeric',
            'state_id' => 'nullable|numeric',
            'zip_code' => 'nullable|string|max:14',
            'country_code' => 'nullable|string|max:9',
            'phone' => 'nullable|string|max:14',
            'email' => 'nullable|string|max:254',

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
