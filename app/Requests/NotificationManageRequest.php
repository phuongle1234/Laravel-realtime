<?php

namespace App\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class NotificationManageRequest extends FormRequest
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
        $rules['name'] = ["required", "string", "max:255"];
        $rules['title'] = [ "required", "string", "max:255" ];
        $rules['content'] = [ "required", "string" ];
        $rules['destination'] = [ "required", "in:all,teacher,student" ];
        $rules['display'] = [ "required", "in:0,1" ];
                                //, "email","max:40", Password::min(10)->letters()->symbols()->numbers()
        return $rules;
    }

    // public function messages()
    // {

    // }

    public function attributes()
    {
        $attributes = [];
        $attributes['name'] = 'テンプレート名';
        $attributes['title'] = 'タイトル';
        $attributes['content'] = '詳細';
        $attributes['destination'] = 'お知らせ先';
        $attributes['display'] = '表示ステータス';

        return $attributes;
    }
}
