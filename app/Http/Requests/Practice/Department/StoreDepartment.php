<?php

namespace App\Http\Requests\Practice\Department;

use App\Traits\RespondsWithHttpStatus;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StoreDepartment extends FormRequest
{
    use RespondsWithHttpStatus;
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
            'name' => ['required',
                        Rule::when($request->has('department_id'), Rule::unique('departments')
                        ->where('practice_id', $this->practice_id())->ignore($this->department_id)),

                        Rule::when(!$request->has('department_id'), Rule::unique('departments')
                        ->where('practice_id', $this->practice_id())),
                    ]
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Please enter a name',
            'name.unique' => 'Entered department name is already present',
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
