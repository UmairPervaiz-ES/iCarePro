<?php

namespace App\Http\Requests\Patient\PatientHistory;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule as ValidationRule;

class PatientAllergyRequest extends FormRequest
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
            'patient_allergy_id' => 'nullable|numeric',
            'patient_id' => 'required|numeric',
            'allergy_id' => 'required|numeric',
            'criticality' => [ValidationRule::in([null, 'Low', 'High', 'Unable to assess'])],
            'onset_date' => 'required|date',
            'note' => 'nullable|string',
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
