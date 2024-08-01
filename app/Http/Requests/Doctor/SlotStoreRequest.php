<?php

namespace App\Http\Requests\Doctor;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class SlotStoreRequest extends FormRequest
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
            'date_from' => ['required', 'date', 'after_or_equal:'.Carbon::now()->format('Y-m-d')],
            'date_to' => ['required', 'date', 'after_or_equal:date_from'],
            'time_from' => ['required', 'date_format:h:i A'],
            'time_to' => ['required', 'date_format:h:i A', 'after:'.Carbon::parse($this->time_from)->format('h:i A')],
            'slot_time' => ['required'],
            'days' => ['required'],
        ];
    }

    public function messages()
    {
        return [
            'date_from.required' => 'Please select from date',
            'date_from.date' => 'Please select a valid from date',
            'date_to.required' => 'Please select to date',
            'date_to.date' => 'Please select a valid to date',
            'date_to.after' => 'To date must be greater than from date',
            'time_from.required' => 'Please select from time',
            'time_to.required' => 'Please select to time',
            'time_to.after' => 'To time must be greater than from time ',
            'slot_time.required' => 'Please enter a slot interval',
            'days.required' => 'Please select days of weeks',
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
