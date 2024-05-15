<?php

namespace App\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class TagManageRequest extends FormRequest
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
        $_tag_type = explode('/',request()->route()->getPrefix())[1];

        $rules = [];
        $rules['name'] = ["required", "string", "max:255"];

        if( $_tag_type == 'field' )
            $rules['subject_id'] = [ "required", "numeric" ];

        $rules['active'] = [ "required", "numeric", "in:0,1" ];

        return $rules;
    }

    // public function messages()
    // {
    // }

    public function attributes()
    {
        $attributes = [];
        $attributes['name'] = '科目';
        $attributes['subject_id'] = 'タグ名';
        $attributes['active'] = 'ステータス';

        return $attributes;
    }
}
