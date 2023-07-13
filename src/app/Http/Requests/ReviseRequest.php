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
            'name' => ['required', 'string', 'max:191'],
            'area' => ['required', 'string', 'max:191'],
            'genre' => ['required', 'string', 'max:191'],
            'overview' => ['required', 'string', 'max:500'],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => '店舗名を入力してください',
            'name.string' => '文字列を入力してください',
            'name.max' => '191文字以内で入力してください',
            'area.required' => '地域を入力してください',
            'area.string' => '文字列を入力してください',
            'area.max' => '191文字以内で入力してください',
            'genre.required' => 'ジャンルを入力してください',
            'genre.string' => '文字列を入力してください',
            'genre.max' => '191文字以内で入力してください',
            'overview.required' => '概要を入力してください',
            'overview.string' => '文字列を入力してください',
            'overview.max' => '500文字以内で入力してください',
        ];
    }
}
