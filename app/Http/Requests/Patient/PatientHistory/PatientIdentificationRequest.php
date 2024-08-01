<?php

namespace App\Http\Requests\Patient\PatientHistory;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule;

class PatientIdentificationRequest extends FormRequest
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
            'legal_first_name' => 'required|string|max:29',
            'legal_last_name' => 'nullable|max:29|string',
            'legal_middle_name' => 'nullable|max:29|string',
            'suffix' => [Rule::in([null, 'Miss', 'Mrs', 'Ms', 'Mr'])],
            'legal_sex' => [Rule::in([null, 'Male', 'Female', 'Other'])],
            'previous_name' => 'nullable|string|max:29',
            'dob' => 'nullable|date',
            'emirates_id' => 'nullable|string|max:16',
            'mother_name' => 'nullable|string|max:49',
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
