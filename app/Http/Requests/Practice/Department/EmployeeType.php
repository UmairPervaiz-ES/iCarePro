<?php

namespace App\Http\Requests\Practice\Department;

use App\Traits\RespondsWithHttpStatus;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EmployeeType extends FormRequest
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
            'name' => ['required', Rule::unique('department_employee_types')->where(function ($query) use($request) {
                    return $query->where('department_id', $request->department_id)
                        ->where('practice_id', $this->practice_id());
            }),]
        ];
    }

    public function messages()
    {
        return [
            'name' => 'Please enter a name for employee type',
            'name.unique' => 'Entered employee type is present already',
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
