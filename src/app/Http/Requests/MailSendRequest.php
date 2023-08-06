<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MailSendRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:191'],
            'email' => ['required', 'email', 'max:191'],
            'title' => ['required', 'string', 'max:191'],
            'message' => ['required', 'string', 'max:191'],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => '名前を入力してください',
            'name.string' => '名前を文字列で入力してください',
            'name.max' => '名前を191文字以下で入力してください',
            'email.required' => 'メールアドレスを入力してください',
            'email.email' => 'メールアドレスをメール形式で入力してください',
            'email.max' => 'メールアドレスを191文字以下で入力してください',
            'title.required' => 'タイトルを入力してください',
            'title.string' => 'タイトルを文字列で入力してください',
            'title.max' => 'タイトルを20文字以下で入力してください',
            'message.required' => 'メッセージを入力してください',
            'message.string' => 'メッセージを文字列で入力してください',
            'message.max' => 'メッセージを20文字以下で入力してください',
        ];
    }
}
