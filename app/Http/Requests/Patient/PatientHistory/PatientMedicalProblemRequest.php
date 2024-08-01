<?php

namespace App\Http\Requests\Patient\PatientHistory;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule;

class PatientMedicalProblemRequest extends FormRequest
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
            'medical_problem_id' => 'required|numeric',
            'status' => [Rule::in([null, 'Active', 'Historical'])],
            'removal_reason' => 'nullable|string|max:254',
            'type' => [Rule::in([null, 'Chronic', 'Acute'])],
            'onset_date' => 'required|date',
            'last_occurrence' => 'nullable|date',
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
