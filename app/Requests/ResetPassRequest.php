<?php

namespace App\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class ResetPassRequest extends FormRequest
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

        $rules['password'] = [ "required", "string" , "max:12", "min:6" , Password::min(6)->letters()->numbers() , "same:c_password"];
        $rules['c_password'] = [ "required", "string" ];

        return $rules;
    }

    // public function messages()
    // {

    // }

    public function attributes()
    {
        $attributes = [];
        $attributes['c_password'] = 'パスワード（確認用）';
        $attributes['password'] = 'パスワード';

        return $attributes;
    }
}
