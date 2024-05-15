<?php

namespace App\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
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

        //preg_match('/\p{Z}|\p{S}|\p{P}/u', $value)
        $rules = [];
        $rules['email'] = "required|string|email|max:40|unique:users,email";
        $rules['password'] = [ "required", "string", Password::min(6)->letters()->numbers(), "not_regex:/\p{Z}|\p{S}|\p{P}/u" ];
                                //, "email","max:40", Password::min(10)->letters()->symbols()->numbers()
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
