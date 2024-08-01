<?php

namespace App\Http\Requests\Patient\Register;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule;

class BasicInfoUpdateRequest extends FormRequest
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
            'phone_number' => 'required|string|max:15|',
            'first_name' => 'required|string|max:29|',
            'last_name' => 'required|string|max:29|',
            'middle_name' => 'nullable|string|max:29|',
            'email' => 'string|max:254',
            'gender' =>  ['required', Rule::in(['Male', 'Female', 'Transgender', 'Prefer not to say', 'Other'])],
            'dob' => 'required|date',
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
