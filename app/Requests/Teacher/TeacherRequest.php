<?php

namespace App\Requests\Teacher;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

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
        $rules['name'] = "required|string|max:40";
        $rules['last_name'] = "required|string";
        $rules['kana'] = "required|regex:/^[ｧ-ﾝﾞﾟ]*/";
        $rules['birthday'] = ["required"];
        $rules['sex'] = ["required"];
        $rules['education'] = ["required"];
        $rules['undergraduate'] = ["required"];
        $rules['subject_study'] = ["required"];
        $rules['subject'] = ["required"];
        $rules['tel'] = ["required"];
        $rules['introduction'] = ["required"];
        $rules['card_id'] = ["required","mimes:pdf,jpg,png"];
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
        $attributes['education'] = '最終学歴';
        $attributes['undergraduate'] = '最終学歴';
        $attributes['sex'] = '性別';
        $attributes['subject_study'] = '学部';
        $attributes['tel'] = '電話番号';
        $attributes['introduction'] = 'パスワード';
        $attributes['card_id'] = 'パスワード';
        $attributes['token_stripe'] = '口座情報設定';
        $attributes['subject'] = '受諾する科目選択';

        return $attributes;
    }
}
