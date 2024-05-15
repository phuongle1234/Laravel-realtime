<?php

namespace App\Requests\Teacher;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class VideoRequest extends FormRequest
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
        $rules['title'] = "required|string|max:40";
        $rules['description'] = "required|string";

        $rules['path'] = "required|mimes:pdf";
        $rules['video_id'] = "required";

        return $rules;
    }

    // public function messages()
    // {

    // }

    public function attributes()
    {
        $attributes = [];
        $attributes['title'] = '動画タイトル';
        $attributes['description'] = '動画説明文';
        $attributes['path'] = 'PDF';
        $attributes['video_id'] = '動画を選択する​​';

        return $attributes;
    }
}
