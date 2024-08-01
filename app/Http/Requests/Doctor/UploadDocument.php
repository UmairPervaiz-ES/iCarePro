<?php

namespace App\Http\Requests\Doctor;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UploadDocument extends FormRequest
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
            'file_paths' => ['required'],
            'file_paths.*' => ['mimes:doc,pdf,docx,jpeg,jpg,png'],  // 10240 = max 10 MB
        ];
    }

    public function messages()
    {
        return [
            'file_paths.required' => 'Please select document(s) to upload',
            'file_paths.*.mimes' => 'Document type should be of type doc,pdf,docx,jpeg,jpg,png',
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
