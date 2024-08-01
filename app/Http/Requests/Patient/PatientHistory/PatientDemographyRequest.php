<?php

namespace App\Http\Requests\Patient\PatientHistory;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule;

class PatientDemographyRequest extends FormRequest
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
            'language_id' => 'nullable|numeric',
            'race_id' => 'nullable|numeric',
            'ethnicity_id' => 'nullable|numeric',
            'marital_status' => [Rule::in([null, 'Unknown', 'Married', 'Single', 'Divorced',  'Separated', 'Widowed', 'Partner'])],
            'sexual_orientation' => [Rule::in([null, 'Lesbian, gay or homosexual', 'Straight or heterosexual', 'Bisexual', 'Something else, please describe',  'Do not know', 'Choose not to disclose'])],
            'gender_identity' => [Rule::in([null, 'Identifies as Male', 'Identifies as Female', 'Transgender Male/Female-to-Male (FTM)', 'Transgender Female/Male-to-Female (MTF)',  'Gender non-conforming (neither exclusively male nor female)', 'Additional gender category / other, please specify', 'Choose not to disclose'])],
            'assigned_sex_at_birth' => [Rule::in([null, 'Male', 'Female', 'Choose not to disclose', 'unknown'])],
            'pronoun' => [Rule::in([null, 'he/him', 'she/her', 'they/them', 'Choose not to disclose'])],
            'home_bound' => [Rule::in([null, 'Yes', 'No'])],
            'family_size' => 'nullable|integer',
            'income' => 'nullable|numeric',
            'income_define_per' => [Rule::in([null, 'Year', 'Month', '2 Weeks', 'Week', 'Hourly', 'Choose not to disclose'])],
            'agricultural_worker' => 'nullable|string|max:19',
            'homeless_status' => 'nullable|string|max:19',
            'school_based_health_center_patient' => 'nullable|string|max:19',
            'veteran_status' => 'nullable|string|max:19',
            'public_housing_patient' => 'nullable|string',
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
