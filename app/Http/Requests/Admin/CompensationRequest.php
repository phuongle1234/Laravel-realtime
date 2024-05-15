<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CompensationRequest extends FormRequest
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
        if ($this->isMethod("POST")) {
            return [
                "from_date" => 'required',
                "to_date"=> 'required_with:from_date',
            ];
        }
        return [
            //
        ];
    }

    public function attributes()
    {
        return [
            'from_date' => '適用',
            "to_date" => "金額操作",
        ];
    }

    public function messages()
    {
        return [
            'from_date.required' => '検索項目をご選択ください。',
            'to_date.required_with' => '検索項目をご選択ください。',
        ];
    }
}
