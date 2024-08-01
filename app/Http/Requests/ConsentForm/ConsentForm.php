<?php

namespace App\Http\Requests\ConsentForm;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;


class ConsentForm extends FormRequest
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
    public function rules(Request $request)
    {
        return [
            'consent_form_type_id' => 'required|numeric',
            'version' => ['required', 'max:19', Rule::unique('consent_forms')->where(function ($query) use ($request) {
                return $query->where('consent_form_type_id', $request->consent_form_type_id);
            })->ignore($request->id, 'id')],
            'content' => 'required|string',
            'content_arabic' => 'string',
            'content_status' => ['required', Rule::in(['DRAFT', 'SAVE'])],
            'publish_status' => ['required', Rule::in(['PENDING','ACTIVE','DEACTIVATE'])],
            'published_at' => 'required_if:publish_status,==,ACTIVE|date',
            'deactivated_at' => 'required_if:publish_status,==,DEACTIVATE|date',
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
