<?php

namespace App\Http\Requests\Patient\PatientHistory;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule;

class PatientContactRequest extends FormRequest
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
            'address_line_1' => 'nullable|string|max:254',
            'address_line_2' => 'nullable|string|max:254',
            'zip_code' => 'nullable|string|max:14',
            'country_id' => 'nullable|numeric',
            'city_id' => 'nullable|numeric',
            'state_id' => 'nullable|numeric',
            'home_country_code' => 'nullable|string|max:9',
            'home_phone_number' => 'nullable|string|max:14',
            'work_country_code' => 'nullable|string|max:9',
            'work_phone_number' => 'nullable|string|max:14',
            'consent_to_text' => [Rule::in([null, 'Yes', 'No'])],
            'contact_preference' => [Rule::in([null, 'Home phone', 'Work phone',  'Mail', 'Portal'])],
            'patient_email' => 'nullable|string|max:254',
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
