<?php

namespace App\Http\Requests\ConsentForm;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;

class ConsentLog extends FormRequest
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
            'row.*.consent_form_type_id' => 'required|numeric',
            'row.*.consent_form_id' => 'required|numeric',
            'row.*.consent_status' =>  ['required', Rule::in(['AGREE', 'DISAGREE'])],
            'row.*.category' =>  ['required', Rule::in(['DOCTOR', 'PATIENT'])],
        ];
    }

    public function response(array $errors)
    {
        if ($this->expectsJson()) {
            return new JsonResponse($errors, 422);
        }
    }
}
