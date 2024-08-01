<?php

namespace App\Http\Requests\Doctor;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
class UpdatePersonalInformation extends FormRequest
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
            'first_name' => ['required' , 'max:29'],
            'last_name' => ['required' , 'max:29'],
            'gender' => ['required' , Rule::in(['Male', 'Female', 'Transgender', 'Prefer not to say', 'Other'])],
            'specializationIDs' => ['required'],
        ];
    }

    public function messages()
    {
        return [
            'first_name.required'  => 'Please enter first name',
            'last_name.required'  => 'Please enter last name',
            'gender.required'  => 'Please select gender',
            'specializationIDs.required'  => 'Please select specialization(s)',
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
