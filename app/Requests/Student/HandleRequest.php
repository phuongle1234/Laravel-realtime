<?php

namespace App\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class HandleRequest extends FormRequest
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
        $rules['title'] = "required|string|max:40";
        $rules['content'] = "required|string";
        $rules['subject_id'] = "required|regex:/^[ｧ-ﾝﾞﾟ]*/";
        // $rules['private'] = ["required"];
        // $rules['path'] = ["required"];

        return $rules;
    }

    // public function messages()
    // {

    // }

    public function attributes()
    {
        $attributes = [];
        $attributes['title'] = 'タイトル';
        $attributes['content'] = '説明文';
        $attributes['subject_id'] = '科目選択';
        // $attributes['private'] = 'おすすめの先生';
        $attributes['path'] = 'リクエスト内容';

        return $attributes;
    }
}
