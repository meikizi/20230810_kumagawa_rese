<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReviewRequest extends FormRequest
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
            'rate' => ['required', 'integer', 'max:5'],
            'review' => ['required', 'string', 'max:500'],
        ];
    }

    public function messages()
    {
        return [
            'rate.required' => '評価を選択してください',
            'rate.integer' => '数値を入力してください',
            'rate.max' => '正しい評価を選択してください',
            'review.required' => 'レビュー内容を記入してください',
            'review.string' => '文字列を入力してください',
            'review.max' => '500文字以内で入力してください',
        ];
    }
}
