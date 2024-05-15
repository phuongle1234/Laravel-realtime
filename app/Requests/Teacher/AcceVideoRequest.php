<?php

namespace App\Requests\Teacher;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class AcceVideoRequest extends FormRequest
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

        if( ! $this->video &&  ! $this->video_id )
            $rules['video'] = "required";

        $rules['video_title'] = "required|string|max:60";
        $rules['description'] = "required|string";


        $rules['tag_id'] = "required";
        $rules['field_id'] = "required";

        return $rules;
    }

    // public function messages()
    // {

    // }

    public function attributes()
    {
        $attributes = [];
        $attributes['title'] = '動画タイトル';
        $attributes['video'] = '動画';
        $attributes['video_id'] = '動画';
        $attributes['video_title'] = '動画タイトル';
        $attributes['description'] = '動画説明文';
        $attributes['tag_id'] = '分野選択';
        $attributes['field_id'] = '難易度選択';

        return $attributes;
    }
}
