<?php

namespace App\Http\Requests\EPrescription\EPrescription;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule as ValidationRule;


class AppointmentStatusChangeRequest extends FormRequest
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
            'appointment_id' => 'required|numeric',
            'reason' => 'nullable|max:254|required_if:status,==,Cancelled',
            'comments' => 'nullable|max:254', 
            'status' => ValidationRule::in(['Pending', 'Checked in' ,'Confirmed', 'Cancelled', 'Completed', 'Rescheduled']),
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
