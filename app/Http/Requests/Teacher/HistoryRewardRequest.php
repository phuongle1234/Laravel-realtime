<?php

namespace App\Http\Requests\Teacher;

use Illuminate\Foundation\Http\FormRequest;

class HistoryRewardRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if ($this->isMethod("POST")) {
            return [
                "from_date" => 'required',
            ];
        }
        return [
            //
        ];
    }

//    public function attributes()
//    {
//        return [
//            'from_date' => '報酬履歴',
//        ];
//    }

    public function messages()
    {
        return [
            'from_date.required' => 'カレンダーを選択してください。',
        ];
    }
}
