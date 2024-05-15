<?php

namespace App\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class NotificationDeliveryRequest extends FormRequest
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
        // $rules['name'] = ["required", "string", "max:255"];
        $rules['title'] = [ "required", "string", "max:255" ];
        $rules['content'] = [ "required", "string" ];
        $rules['destination'] = [ "required", "in:all,teacher,student" ];
        $rules['start_at'] = [ "required", "date_format:Y-m-d H:i" ];

        return $rules;
    }

    // public function messages()
    // {

    // }

    public function attributes()
    {
        $attributes = [];
        // $attributes['name'] = 'テンプレート名';
        $attributes['title'] = 'タイトル';
        $attributes['content'] = '詳細';
        $attributes['destination'] = 'お知らせ先';
        $attributes['start_at'] = '送信日時';

        return $attributes;
    }
}
