<?php

namespace App\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Auth;

class ChangeRegistRequest extends FormRequest
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

        $rules['email'] = [ "required", "email", "max:50",
                            Rule::unique('users','email')->ignore(auth()->guard('student')->user()->email,'email')
                          ];

        $rules['name'] = [ "required", "string" , "max:20",
                            Rule::unique('users','name')->ignore(auth()->guard('student')->user()->name,'name')
                         ];

        if($this->password)
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
        $attributes['email'] = 'e-mail';
        $attributes['name'] = 'ユーザー名';
        $attributes['password'] = 'パスワード';

        return $attributes;
    }
}
