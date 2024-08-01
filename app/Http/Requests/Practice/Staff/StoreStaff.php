<?php

namespace App\Http\Requests\Practice\Staff;

use App\Helper\Helper;
use App\Traits\RespondsWithHttpStatus;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class StoreStaff extends FormRequest
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
    public function rules()
    {
        return [
            'department_id' => ['required'],
            'first_name' => ['required'],
            'last_name' => ['required'],
            'email' => ['required', 'email',
                Rule::when(!$this->has('user_id'),
                    Rule::unique('users')->where('practice_id', $this->practice_id())),

                Rule::when($this->has('user_id'), Rule::unique('users')->where(function ($query) {
                    $query->where('email', $this->email);
                })->whereNot('id', $this->user_id) ),
            ],
            'secondary_email' => ['sometimes', Rule::when($this->secondary_email == null,'nullable'),
                Rule::when($this->secondary_email != null,
                'email')
            ],
            'country_code_phone_number' => ['required'],
            'phone_number' => ['required'],
            'gender' => ['required'],
            'dob' => ['required', 'date'],
            'profile_photo_url' => ['sometimes', Rule::when($this->profile_photo_url != null,'mimes:jpeg,jpg,png')],
            'home_address_1' => ['required' , 'max:254' ,'string' ],
            'home_address_2' => [ 'max:254' , 'string' ,'nullable'],
            'home_town_country_id' => ['required','numeric'],
            'home_town_state_id' => ['required','numeric'],
            'home_town_city_id' => ['required','numeric'],
            'current_zip_code' => ['required' ,'max:14' , 'string'],
            'current_address_1' => ['required' ,'max:254' , 'string'],
            'current_address_2' => ['max:254' , 'string' ,'nullable'],
            'current_country_id' => ['required' , 'numeric'],
            'current_state_id' => ['required' , 'numeric'],
            'current_city_id' => ['required' , 'numeric'],
            'home_zip_code' => ['required' ,'max:14' , 'string'],
        ];
    }

    public function messages()
    {
        return [
            'department_id.required' => 'Please select a department',
            'first_name.required' => 'First name is required',
            'middle_name.required' => 'Middle name is required',
            'last_name.required' => 'Last name is required',
            'email.required' => 'Email is required',
            'email.unique' => 'Entered email is already present',
            'country_code_phone_number.required' => 'Please select country code for primary phone number.',
            'phone_number.required' => 'Phone number is required',
            'gender.required' => 'Gender is required',
            'dob.required' => 'Date of brith if required',
            'profile_photo_url.mimes' => 'Profile photo should be of type jpeg,jpg,png',
            'home_address_1.required' => 'Please enter home address 1',
            'home_address_2.required' => 'Please enter home address 2',
            'home_town_country_id.required' => 'Select home country',
            'home_town_state_id.required' => 'Select home state',
            'home_town_city_id.required' => 'Select home city',
            'current_zip_code.required' => 'Current zip code is required',
            'current_address_1.required' => 'Please enter current address 1',
            'current_address_2.required' => 'Please enter current address 2',
            'current_country_id.required' => 'Select current country',
            'current_state_id.required' => 'Select current state',
            'current_city_id.required' => 'Select current city',
            'home_zip_code.required' => 'Home zip code is required',
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
