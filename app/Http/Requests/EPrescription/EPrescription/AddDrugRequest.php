<?php

namespace App\Http\Requests\EPrescription\EPrescription;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rules\RequiredIf;
use Illuminate\Validation\Rule as ValidationRule;
class AddDrugRequest extends FormRequest
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
            'name' => ['string', 'required', 'max:149'],
            'type' => ValidationRule::in([ 'Liquid','Tablet', 'Capsule', 'Plasma/Topical/Serum', 'Suppositories', 'Drops', 'Inhalers', 'Injections', 'Implants and patches', 'Lozenges']),
            'unit' => ValidationRule::in([ '','mg', 'ml', 'mcg', 'mg/ml', 'gm', 'IU/ml', 'ml/L']),
            'intake' =>  ValidationRule::in([ 'Oral','Inhalation', 'Injection', 'Topical', 'Spray']),
            'salt_name' => ['nullable', 'string', 'max:149'],
            "drugStrengths"    => ['required','array', 'min:1'],
            "drugStrengths.*"  => ['required','numeric','distinct']
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