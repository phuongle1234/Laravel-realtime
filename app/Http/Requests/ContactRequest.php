<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
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
                "name" => 'required|string|max:12',
                "email" => 'required|email:rfc,dns',
                "content" => 'required',
                "agreement" => 'required',
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
            'name.required' => 'お名前をご入力ください',
            'email.required' => 'メールアドレスをご入力ください',
            'email.email' => 'メールアドレスは有効なメールアドレスでなければなりません。',
            'content.required' => '詳細をご入力ください',
            'agreement.required' => '「個人情報保護方針」の内容に同意いただいておりません。',
        ];
    }
}
