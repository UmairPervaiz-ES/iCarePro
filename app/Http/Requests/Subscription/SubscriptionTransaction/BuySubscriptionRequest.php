<?php

namespace App\Http\Requests\Subscription\SubscriptionTransaction;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class BuySubscriptionRequest extends FormRequest
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

    public function rules()
    {
        return [
            'subscription_id' => 'required',
            // 'practice_id' => 'required',
            'card_no' => 'required|string|max:49',
            'ccExpiryMonth' => 'required|string|max:10',
            'ccExpiryYear' => 'required|string|max:4',
            'cvvNumber' => 'required|string|max:5',
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
