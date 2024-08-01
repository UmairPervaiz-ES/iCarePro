<?php

namespace App\Http\Requests\Doctor;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateContactInformation extends FormRequest
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
            'primary_phone_number'  => 'required|max:14',
        ];
    }

    public function messages()
    {
        return [
            'primary_phone_number.required'  => 'Please enter primary phone number',
            'secondary_phone_number.required'  => 'Please enter secondary phone number',
            'secondary_email.required'  => 'Please enter secondary email',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success'   => false,
            'error'      => $validator->errors()
        ], 422));
    }
}
