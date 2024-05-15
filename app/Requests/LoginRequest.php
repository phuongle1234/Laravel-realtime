<?php

namespace App\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class LoginRequest extends FormRequest
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
        $rules['email'] = "required|string|max:40|email:rfc,dns";
        $rules['password'] = [ "required", "string", Password::min(6)->letters()->numbers() ];

        return $rules;
    }

    // public function messages()
    // {

    // }

    public function attributes()
    {
        $attributes = [];
        $attributes['email'] = 'メールアドレス';
        $attributes['password'] = 'パスワード';

        return $attributes;
    }
}
