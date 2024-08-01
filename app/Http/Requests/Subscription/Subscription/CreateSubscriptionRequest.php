<?php

namespace App\Http\Requests\Subscription\Subscription;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class CreateSubscriptionRequest extends FormRequest
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
            'name' => 'required|max:39',
            'description' => 'required|string',
            'duration_days' => 'required|integer|min:1',
            'price' => 'required|numeric',
            'allowed_doctors' => 'required|min:0|numeric',
            'allowed_patients' => 'required|min:0|numeric',
            'allowed_appointments' => 'required|min:0|numeric',
            'allowed_staff' => 'required|min:0|numeric',
            'is_trial' => 'required|boolean',
            'status' => 'required|boolean',
            'permissions' => 'required',
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
