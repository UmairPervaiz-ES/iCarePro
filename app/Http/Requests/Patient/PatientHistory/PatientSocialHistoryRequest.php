<?php

namespace App\Http\Requests\Patient\PatientHistory;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule;

class PatientSocialHistoryRequest extends FormRequest
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
            'gender_identity' => [Rule::in([null, 'Identifies as Male', 'Identifies as Female', 'Transgender Male/Female-to-Male (FTM)', 'Transgender Female/Male-to-Female (MTF)', 'Gender non-conforming (neither exclusively male nor female)', 'Additional gender category / other, please specify', 'Choose not to disclose'])],
            'sex_at_birth' => [Rule::in([null, 'Male', 'Female', 'Choose not to disclose', 'unknown'])],
            'pronoun' => [Rule::in([null, 'he/him', 'she/her', 'they/them'])],
            'first_name' => 'nullable|string|max:29|',
            'sexual_orientation' => [Rule::in([null, 'Lesbian, gay or homosexual', 'Straight or heterosexual', 'Bisexual', 'Something else, please describe', 'Do not know', 'Choose not to disclose'])],
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
