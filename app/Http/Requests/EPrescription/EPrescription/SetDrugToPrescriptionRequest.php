<?php

namespace App\Http\Requests\EPrescription\EPrescription;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rules\RequiredIf;
use Illuminate\Validation\Rule as ValidationRule;
class SetDrugToPrescriptionRequest extends FormRequest
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
            'id' => 'numeric',
            'appointment_id' => 'required|numeric',
            'medical_problem_id' => 'numeric',
            'drug_id' => 'numeric',
            'drug_name' => [ new RequiredIf($this->drug_id > 0), 'string', 'max:149'],
            'quantity' => 'numeric',
            'type' => 'string|max:49',
            'strength_id' => 'numeric',
            'strength_value' => 'string|max:19',
            'mg_tab' => ValidationRule::in(['mg', 'tablet(s)', 'gm', 'ml', 'ml/L', 'mg/ml', 'mcg', 'IU/ml']),
            'repetition' => ValidationRule::in(['every day','twice a day', '3 times a day', '4 times a day', '5 times a day', '6 times a day', 'every other day', 'every hour', 'every 2 hours', 'every 3 hours', 'every 3-4 hours', 'every 4 hours', 'every 4-6 hours', 'every 6 hours', 'every 6-8 hours',  'every 8 hours',  'every 12 hours',  'every 24 hours', 'every 72 hours',  'every week',  'twice a week', '3 times a week',  'every 2 weeks',  'every 3 weeks', 'every 4 weeks',  'every month',  'every 2 months', 'every 3 months', 'as needed',]),
            'route' => ValidationRule::in([ 'oral','Inject','Physical',]),
            'when' => ValidationRule::in([ 'before meals','with meals','after meals','in the morning','at noon','in the evening','at dinner','at bedtime','around the clock','as directed','as needed',]),
            'quantity_unit' => ValidationRule::in(['tablet(s)','mg','blist pack(s) of 100','bottle(s) of 100', 'bottle(s) of 1000',]),
            'for_days' => 'integer',
            'quantity_total' => 'numeric',
            'internal_note' => 'string',
            'note_to_patient' => 'string',
            'note_to_pharmacy' => 'string',
            'dispense_as_written' => 'boolean'
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