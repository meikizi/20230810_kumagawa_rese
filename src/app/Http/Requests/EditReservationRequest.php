<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditReservationRequest extends FormRequest
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
                'date' => ['required', 'date'],
                'time' => ['required', 'date_format:H:i'],
                'number' => ['required', 'integer', 'max:10'],
                'date_time' => ['after:now'],
            ];
        }

        if ($this->has('delete')) {
            return [
            ];
        }
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
            'date.required' => '日付を入力してください',
            'date.date' => '正しい日付形式ではありません。2023-01-01のような形式で入力してください。',
            'time.required' => '時間を入力してください',
            'time.date_format' => '正しい時間形式ではありません。00:00のような形式で入力してください。',
            'number.required' => '人数を入力してください',
            'number.integer' => '数値を入力してください',
            'number.max' => '10以下の数値を入力してください',
            'date_time.after' => '予約日は今の時間以降の日時を選択してください。',
        ];
    }
}
