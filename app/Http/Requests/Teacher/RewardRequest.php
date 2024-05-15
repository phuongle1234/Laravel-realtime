<?php

namespace App\Http\Requests\Teacher;

use Illuminate\Foundation\Http\FormRequest;

class RewardRequest extends FormRequest
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
                "amount" => ['numeric','required','min:1000'],
            ];
        }
        return [
            //
        ];
    }

    public function attributes()
    {
        return [
            'amount' => '報酬申請',
        ];
    }

    public function messages()
    {
        return [
            'amount.required' => '報酬申請したい金額をご入力ください。',
            'amount.numeric' => '報酬申請したい金額をご入力ください。',
            'amount.min' => '金額は 1000 以上である必要があります。'
        ];
    }
}
