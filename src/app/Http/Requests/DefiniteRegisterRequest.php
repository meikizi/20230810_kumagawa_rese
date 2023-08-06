<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DefiniteRegisterRequest extends FormRequest
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
            'phone_number1' => ['required', 'numeric'],
            'phone_number2' => ['required', 'numeric'],
            'phone_number3' => ['required', 'numeric'],
            'phone_number' => ['required', 'numeric', 'digits_between:10,11'],
            'year' => ['required', 'numeric', 'digits:4'],
            'month' => ['required', 'numeric', 'digits_between:1,2'],
            'day' => ['required', 'numeric', 'digits_between:1,2'],
            'birthday' => ['required', 'date'],
            'postcode' => ['required', 'max:8', 'regex:/^[0-9-]+$/'],
            'address' => ['required', 'string', 'max:191'],
            'building_name' => ['max:191'],
            'full_address' => ['required', 'string', 'max:191'],
        ];
    }

    public function messages()
    {
        return [
            'phone_number1.required' => '電話番号を入力してください',
            'phone_number1.numeric' => '電話番号を数値で入力してください',
            'phone_number2.required' => '電話番号を入力してください',
            'phone_number2.numeric' => '電話番号を数値で入力してください',
            'phone_number3.required' => '電話番号を入力してください',
            'phone_number3.numeric' => '電話番号を数値で入力してください',
            'phone_number.required' => '電話番号を入力してください',
            'phone_number.numeric' => '電話番号を数値で入力してください',
            'phone_number.digits_between' => '電話番号を10桁または11桁で入力してください',
            'year.required' => '生年月日を選択してください',
            'year.numeric' => '生年月日を数値で入力してください',
            'year.digits' => '4桁の数値で入力してください',
            'month.required' => '生年月日を選択してください',
            'month.numeric' => '生年月日を数値で入力してください',
            'month.digits_between' => '1桁または2桁で入力してください',
            'day.required' => '生年月日を選択してください',
            'day.numeric' => '生年月日を数値で入力してください',
            'day.digits_between' => '1桁または2桁で入力してください',
            'birthday.required' => '生年月日を入力してください',
            'birthday.date' => '生年月日を2000-01-01のような形式で入力してください',
            'postcode.required' => '郵便番号を入力してください',
            'postcode.max' => '郵便番号をハイフンを含む8桁以下で入力してください',
            'postcode.regex' => 'ハイフンを含む半角数字で入力してください',
            'address.required' => '住所を入力してください',
            'address.string' => '住所を文字列で入力してください',
            'address.max' => '住所を191文字以下で入力してください',
            'building_name.max' => '建物名を191文字以下で入力してください',
            'full_address.required' => '住所を入力してください',
            'full_address.string' => '住所を文字列で入力してください',
            'full_address.max' => '住所を191文字以下で入力してください',
        ];
    }

    public function prepareForValidation()
    {
        $this->merge(['postcode' => mb_convert_kana($this->postcode, 'a')]);
    }
}
