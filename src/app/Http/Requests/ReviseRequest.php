<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReviseRequest extends FormRequest
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
        $rules = [];

        if ($this->has('delete')) {
            return $rules;
        }

        return [
            'name' => ['required', 'string', 'max:50'],
            'area' => ['required', 'string', 'max:50'],
            'genre' => ['required', 'string', 'max:50'],
            'overview' => ['required', 'string', 'max:400'],
            'path' => ['string', 'regex:/.jpeg$|.png$/']
        ];
    }

    public function messages()
    {
        return [
            'name.required' => '店舗名を入力してください',
            'name.string' => '文字列を入力してください',
            'name.max' => '50文字以内で入力してください',
            'area.required' => '地域を入力してください',
            'area.string' => '文字列を入力してください',
            'area.max' => '50文字以内で入力してください',
            'genre.required' => 'ジャンルを入力してください',
            'genre.string' => '文字列を入力してください',
            'genre.max' => '50文字以内で入力してください',
            'overview.required' => '概要を入力してください',
            'overview.string' => '文字列を入力してください',
            'overview.max' => '400文字以内で入力してください',
            'path.string' => '画像URLを文字列で入力してください',
            'path.regex' => '画像URLに拡張子がjpeg、pngのみ入力してください',
        ];
    }
}
