<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReservationRequest extends FormRequest
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
            'date' => ['required','date'],
            'time' => ['required', 'date_format:H:i'],
            'number' => ['required', 'integer','max:100'],
            'date_time' => ['after:now'],
        ];
    }

    protected function prepareForValidation()
    {
        $date_time = ($this->filled(['date', 'time'])) ? $this->date . ' ' . $this->time : '';
        $this->merge([
            'date_time' => $date_time
        ]);
    }

    public function messages()
    {
        return [
            'date.required' => '予約日を選択してください',
            'date.date' => '正しい日付形式ではありません。2023-01-01のような形式で入力してください。',
            'time.required' => '予約時間を選択してください',
            'time.date_format:H:i' => '正しい時間形式ではありません。00:00のような形式で入力してください。',
            'number.required' => '予約人数を選択してください',
            'number.integer' => '数値を入力してください',
            'number.max' => '100以下の数値を入力してください',
            'date_time.after' => '予約日は今の時間以降の日時を選択してください。',
        ];
    }
}
