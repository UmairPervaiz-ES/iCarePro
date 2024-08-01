<?php

namespace App\Http\Requests\Practice\Initial;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
class InitialRequest extends FormRequest
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
            'practice_name' => 'required|max:29|string',
            'country_code' => 'required|max:9|string',
            'phone_number' => 'required|max:14|string',
            'first_name' => 'required|max:29|string',
            'middle_name' => 'nullable|max:29|string',
            'last_name' => 'required|max:29|string',
            'designation' => 'required|max:49|string',
            'email' => 'required|email|max:254|unique:practice_registration_requests',
            'about_us' => 'required|string',
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
