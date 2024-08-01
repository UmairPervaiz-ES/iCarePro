<?php

namespace App\Http\Requests\EPrescription\Vital;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule as ValidationRule;
use Illuminate\Validation\Rules\RequiredIf;

class SetBloodPressureVitalRequest extends FormRequest
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
            'systole' => 'numeric',
            'diastole' => 'numeric',
            'type' => [ValidationRule::in([ 'sitting','standing','supine','lying on side','prone',])],
            'site' => [ValidationRule::in([ 'L arm','R arm','L leg','R leg','L wrist','R wrist',])],
            'cuffsize' => [ValidationRule::in([ 'neonatal','infant','small pediatric','pediatric','small adult','adult','large adult','child thigh','adult thigh',])],
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
