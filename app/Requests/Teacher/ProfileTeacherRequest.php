<?php

namespace App\Requests\Teacher;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
use Log;
// use Illuminate\Validation\Rules\File;

class ProfileTeacherRequest extends FormRequest
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

        $rules['name'] = "required|string|max:40|unique:users,name";

        // if(  $this->check_name )
        //     return $rules;

        $rules['last_name'] = "required|string";
        $rules['kana'] = "required|regex:/^[ぁ-んァ-ン　]+$/";
        $rules['birthday'] = ["required"];
        $rules['sex'] = ["required"];

        $rules['university_Code'] = ["required"];
        $rules['faculty_code'] = ["required"];
        $rules['edu_status'] = ["required"];

        $rules['subject'] = ["required"];
        $rules['tel'] = ["required"];
        $rules['introduction'] = ["required","max:255"];

        $rules['card_id'] = ["required","mimes:pdf,jpg,png", "max:". 10*1024  ];

        $rules['avatar'] = ["mimes:pdf,jpg,png", "max:". 10*1024  ];

        $rules['token_stripe'] = ["required"];

        //, "email","max:40", Password::min(10)->letters()->symbols()->numbers()
        return $rules;
    }

    // public function messages()
    // {

    // }

    public function attributes()
    {
        $attributes = [];
        $attributes['name'] = 'ユーザー名';
        $attributes['last_name'] = '氏名';
        $attributes['kana'] = 'ふりがな';
        $attributes['birthday'] = '生年月日';
        $attributes['university_Code'] = '最終学歴';
        $attributes['faculty_code'] = '最終学歴';
        $attributes['sex'] = '性別';
        $attributes['edu_status'] = '在学/卒業';
        $attributes['tel'] = '電話番号';
        $attributes['introduction'] = 'パスワード';
        $attributes['card_id'] = 'パスワード';
        $attributes['avatar'] = '画像選択';
        $attributes['token_stripe'] = '口座情報設定';
        $attributes['subject'] = '受諾する科目選択';

        return $attributes;
    }
}
