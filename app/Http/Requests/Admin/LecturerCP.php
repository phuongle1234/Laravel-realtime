<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class LecturerCP extends FormRequest
{
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
        if ($this->isMethod("PUT")) {
            return [
                "reward_action" => 'required',
                "amount"=> 'numeric|required|min:1',
            ];
        }
        return [
            //
        ];
    }

    public function attributes()
    {
        return [
            'reward_action' => '適用',
            "amount" => "金額操作",
        ];
    }

    public function messages()
    {
        return [
            'reward_action.required' => '適用をご選択ください。',
            'amount.required' => '変更したい金額をご入力ください。',
            'amount.numeric' => '変更したい金額をご入力ください。',
            'amount.min' => '金額は少なくとも 1 でなければなりません'
        ];
    }
}
