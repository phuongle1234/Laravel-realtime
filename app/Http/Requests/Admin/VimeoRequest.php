<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class VimeoRequest extends FormRequest
{
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
        return [
            'client_id' => 'required',
            'client_secrets' => 'required',
            'personal_access_token' => 'required'
        ];
    }

//    public function attributes()
//    {
//        return [
//            'client_id' => '適用',
//            "client_secrets" => "金額操作",
//        ];
//    }

    public function messages()
    {
        return [
            'client_id.required' => 'VimeoクライアントIDが入力してください。',
            'client_secrets.required' => 'Vimeoシークレットキーが入力してください。',
            'personal_access_token.required' => 'Vimeoアクセストークンが入力してください。',
        ];
    }
}
