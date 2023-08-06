<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'amount' => ['required', 'max:8', 'regex:/^[0-9]+$/'],
            'name' => ['required', 'string', 'max:191'],
        ];
    }

    public function messages()
    {
        return [
            'amount.required' => 'お支払い金額を入力してください',
            'amount.max' => 'お支払い金額を8桁以下で入力してください',
            'amount.regex' => '半角数字で入力してください',
            'name.required' => 'カード名義人を入力してください',
            'name.string' => 'カード名義人を文字列で入力してください',
            'name.max' => 'カード名義人を191桁以下で入力してください',
        ];
    }
}
