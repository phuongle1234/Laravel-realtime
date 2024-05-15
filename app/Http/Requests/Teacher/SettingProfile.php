<?php

namespace App\Http\Requests\Teacher;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class SettingProfile extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if ($this->isMethod("POST")) {

            $rules = [];

            $rules['name'] = [ "required" ,"max:20",  Rule::unique('users','name')->ignore(auth()->guard('teacher')->user()->name,'name') ];

            $rules['email'] = [ "required" ,"max:50",  Rule::unique('users','email')->ignore(auth()->guard('teacher')->user()->email,'email') ];

            $rules['tel'] = ['required'];

            $rules['introduction'] = ['required','max:1000','string'];

            if($this->password)
            $rules['password'] = [ "required", "string", Password::min(6)->letters()->numbers(), "not_regex:/\p{Z}|\p{S}|\p{P}/u" ];

            return $rules;
        }
        return [
            //
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'ユーザー名',
            'last_name' => '氏名',
            "kana" => "ふりがな",
            "year" => "生年月日",
            "month" => "生年月日",
            "day" => "生年月日",
            "sex" => "性別",
            "email" => "e-mail",
            "tel" => "電話番号",
            "introduction" => "自己紹介",
            "password" => "パスワード"
        ];
    }
}
