<?php

namespace App\Http\Requests\EPrescription\Vital;

use Illuminate\Validation\Rule as ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rules\RequiredIf;
class SetPulseVitalRequest extends FormRequest
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
            'rate' => 'numeric',
            'type' => ValidationRule::in([ 'regular','irregular','regularly irregular','irregularly irregular',]),
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
