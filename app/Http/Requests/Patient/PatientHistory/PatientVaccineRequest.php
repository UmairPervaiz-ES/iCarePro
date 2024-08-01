<?php

namespace App\Http\Requests\Patient\PatientHistory;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule;

class PatientVaccineRequest extends FormRequest
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
            'vaccine_id' => 'required|numeric',
            'route_id' => 'nullable|numeric',
            'national_drug_code_id' => 'nullable|numeric',
            'site_id' => 'nullable|numeric',
            'manufacture_id' => 'nullable|numeric',
            'administer_date' => 'required|date',
            'administer_by' => 'nullable|string|max:254',
            'amount' => 'nullable|numeric',
            'unit' => [Rule::in([null, 'ml', 'mcg', 'mg', 'capsule'])],
            'lot_number' => 'nullable|string|max:99',
            'expiry_date' => 'nullable|date',
            'vaccine_given_date' => 'nullable|date',
            'date_on_vaccine' => 'nullable|date',

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
