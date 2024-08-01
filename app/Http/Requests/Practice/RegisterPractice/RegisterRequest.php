<?php

namespace App\Http\Requests\Practice\RegisterPractice;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class RegisterRequest extends FormRequest
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
            'practice_registeration_request_id' => 'nullable|numeric',
            'subscription_id' => 'nullable|numeric',
            'practice_id' => 'nullable|numeric',
            'logo_url' => 'nullable',
            'password' => 'nullable|string|max:254',
            'tax_id' => 'required|numeric',
            'practice_npi' => 'required|numeric',
            'practice_taxonomy' => 'required|numeric',
            'facility_id' => 'required|numeric',
            'oid' => 'required|numeric',
            'clia_number' => 'required|numeric',
            'privacy_policy' => 'required|string',
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
