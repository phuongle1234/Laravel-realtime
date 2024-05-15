<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used during authentication for various
    | messages that we need to display to the user. You are free to modify
    | these language lines according to your application's requirements.
    |
    */
    'before' => '18歳未満は登録できません。',
    'same' => 'パスワードが一致しません。',
    'numeric' => 'The :attribute must be a number.',
    'regex' => ':attribute が正しくありません。',
    'required' => ':attributeは必須です',
    'max' => [
        'numeric' => ':attribute may not be greater than :max.',
        'file' => ':attribute may not be greater than :max kilobytes.',
        'string' => ':attributeを半角英数字:max文字以下で入力してください。.',
        'array' => ':attribute may not have more than :max items.',
    ],

    'image' => ':attributeを正しく入力してください。',
    'mimes' => ':attributeを正しく入力してください。',
    'email' => ':attribute は有効なメールアドレスでなければなりません。',
    'not_regex' => ':attribute は英数字のみでご入力ください',
    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'unique' => 'この:attributeは既に存在しています。',

];