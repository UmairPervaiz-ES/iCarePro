<?php

namespace App\Http\Requests\EPrescription\Vital;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rules\RequiredIf;
use Illuminate\Validation\Rule as ValidationRule;
class SetTemperatureVitalRequest extends FormRequest
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
            'id' => 'numeric',
            'patient_id' => 'numeric',
            'appointment_id' => 'numeric',
            'temperature_f' => 'numeric',
            'examine_location' =>  ValidationRule::in([ 'oral','ear','axillary','rectal','temporal artery',]),
            'not_performed' => 'required|boolean',
            'reason' => [ new RequiredIf($this->not_performed == TRUE), ValidationRule::in([ 'Not indicated','Not tolerated','Patient refused',])]
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