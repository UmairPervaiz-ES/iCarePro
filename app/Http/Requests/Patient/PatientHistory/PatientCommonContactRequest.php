<?php

namespace App\Http\Requests\Patient\PatientHistory;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule;

class PatientCommonContactRequest extends FormRequest
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
            'patient_relationship' => ['required', Rule::in(['Spouse', 'Parent', 'Child', 'Sibling', 'Friend', 'Cousin', 'Guardian', 'Other'])],
            'first_name' => 'required|string|max:29',
            'middle_name' => 'nullable|string|max:29',
            'last_name' => 'required|max:29|string',
            'email' => 'nullable|string|max:254',
            'dob' => 'nullable|date',
            'suffix' =>  Rule::in([null, 'Miss', 'Mrs', 'Ms', 'Mr']),
            'address' => 'nullable|string|max:254',
            'country_id' => 'nullable|numeric',
            'city_id' => 'nullable|numeric',
            'state_id' => 'nullable|numeric',
            'zip_code' => 'nullable|string|max:14',
            'emirates_id' => 'nullable|string|max:16',
            'country_code' => 'nullable|string|max:9',
            'phone' => 'nullable|string|max:14',
            'contact_reference' => ['required', Rule::in(['guarantor', 'guardian', 'next to kin', 'emergency contact'])],
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
