<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
        if ($this->has('send')) {
            return [
                'name' => ['required', 'string', 'max:191'],
                'email' => ['required', 'email', 'max:191'],
                'title' => ['required', 'string', 'max:191'],
                'message' => ['required', 'string', 'max:191'],
            ];
        }

        if ($this->has('upload')) {
            return [
                'image' => ['required', 'file', 'image',  'max:10000', 'mimes:jpg,jpeg,png'],
            ];
        }

        if ($this->has('delete')) {
            return [
                'image_path' => ['required'],
            ];
        }

        if ($this->has('import')) {
            return [
            ];
        }
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
            'image.required' => 'ファイルを選択してください',
            'image.file' => 'ファイルを選択してください',
            'image.image' => '画像ファイルを選択してください',
            'image.max' => 'ファイルのサイズが大き過ぎます',
            'image.mimes' => 'jpg,jpeg,pngのいずれかを含む画像ファイルを選択してください',
            'image_path.required' => '画像を選択してください'
        ];
    }
}
