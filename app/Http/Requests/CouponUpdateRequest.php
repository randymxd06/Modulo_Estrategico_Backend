<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CouponUpdateRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'description' => ['required', 'string', 'max:100'],
            'percent' => ['required', 'numeric'],
            'product_category_id' => ['required', 'integer', 'exists:product_categories,id'],
            'number_of_days' => ['required', 'integer'],
        ];
    }

    protected function failedValidation(Validator $validator) {
        throw new HttpResponseException(response()->json($validator->errors()->all(), 422));
    }

}
