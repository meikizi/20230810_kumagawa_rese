<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditReviewRequest extends FormRequest
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
        if ($this->has('update')) {
            return [
                'rate' => ['required', 'integer', 'max:5'],
                'review' => ['required', 'string', 'max:400'],
            ];
        }

        if ($this->has('delete')) {
            return [];
        }
    }

    public function messages()
    {
        return [
            'rate.required' => '評価を選択してください',
            'rate.integer' => '数値を入力してください',
            'rate.max' => '正しい評価を選択してください',
            'review.required' => 'コメントを入力してください',
            'review.string' => '文字列を入力してください',
            'review.max' => '400文字以内で入力してください',
        ];
    }
}
