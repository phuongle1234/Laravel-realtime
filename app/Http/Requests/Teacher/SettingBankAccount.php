<?php

namespace App\Http\Requests\Teacher;

use Illuminate\Foundation\Http\FormRequest;

class SettingBankAccount extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if ($this->isMethod("POST")) {
           
            return [
                "bank_code" => ['required'],
                "branch_code"=> ['required'],
                "bank_account_number" =>  ['required'],
                "bank_account_name" => ['required'],
            ];
        }
        return [
            //
        ];
    }

    public function attributes()
    {
        return [
            'bank_code' => '金融機関コード',
            "branch_code" => "支店コード",
            "bank_account_number" => "口座番号",
            'bank_account_name' => '口座名義人',
        ];
    }
}
