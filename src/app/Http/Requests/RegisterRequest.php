<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'email' => ['required', 'email', 'unique:users', 'max:191'],
            'password' => ['required', 'string', 'min:8', 'max:30'],
            'path' => ['string', 'max:10240', 'mimes:jpg,jpeg,png'],
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
            'email.unique' => 'メールアドレスが既に存在しています',
            'email.max' => 'メールアドレスを191文字以下で入力してください',
            'password.required' => 'パスワードを入力してください',
            'password.string' => 'パスワードを文字列で入力してください',
            'password.min' => 'パスワードを8文字以上で入力してください',
            'password.max' => 'パスワードを20文字以下で入力してください',
            'path.string' => '正しいファイル形式のファイルを選択してください',
            'path.max' => 'ファイルのサイズが大き過ぎます',
            'path.mimes' => 'jpg,jpeg,pngのいずれかを含む画像ファイルを選択してください',
        ];
    }
}
