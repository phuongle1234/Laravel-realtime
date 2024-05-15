<?php

namespace App\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class AdmitManageRequest extends FormRequest
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
        // $rules['email'] = [ "required",
        //                     "string",
        //                     "email",
        //                     "max:40"];

        // if( $this->request->get("email_old") != $this->request->get("email") ){
        //     array_push($rules['email'],"unique:users");
        // }

        $rules['password'] = [ "required", "string" ,"max:12" ];
        $rules['name'] = [ "required", "string" ,"max:50" ];
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
        $attributes['name'] = 'アドミン名';
        $attributes['password'] = 'パスワード';

        return $attributes;
    }
}
