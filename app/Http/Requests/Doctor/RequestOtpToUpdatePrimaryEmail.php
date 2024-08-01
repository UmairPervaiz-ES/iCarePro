<?php

namespace App\Http\Requests\Doctor;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class RequestOtpToUpdatePrimaryEmail extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
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
            'update_primary_email' => ['required', 'max:254' , 'email', Rule::unique('doctors','primary_email')],
        ];
    }

    public function messages()
    {
        return [
            'update_primary_email.required' => 'Please enter a valid email ID',
            'update_primary_email.unique' => 'Entered Email ID is already taken',
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
