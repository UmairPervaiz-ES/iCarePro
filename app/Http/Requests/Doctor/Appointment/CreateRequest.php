<?php

namespace App\Http\Requests\Doctor\Appointment;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class CreateRequest extends FormRequest
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
        if ($this->id) {
            return [
                'id' => 'nullable|numeric',
//                'medical_problem_id' => 'required',
            ];
        } else {
            return [
                'patient_id' => 'required|numeric',
                'doctor_slot_id' => "required|numeric",
                'patient_instructions' => 'nullable|string',
                'date' => "required|date",
                'start_time' => "required",
                'end_time' => "required",
            ];
        }
    }
    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success'   => false,
            'data'      => $validator->errors()
        ], 422));
    }
}