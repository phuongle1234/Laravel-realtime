@extends('layouts.student.index')

@section('body_class','main_body p-setting hasbg')
@section('class_main','p-content-setting')

<!-- section('custom_title')
<img src="{{ asset('student/common_img/icon_title1.svg') }}" alt="プロフィール">
endsection -->

@section('content')
@include('component.register.alert')
<ul class="list-links">
        <li><a href="{{ route('student.setting.regist') }}">登録内容の変更</a></li>
        <li><a href="{{ route('student.setting.notification') }}">通知設定</a></li>
        <li><a href="{{ route('student.setting.plan_change') }}">プラン変更</a></li>
        <li><a href="{{ route('student.setting.payment') }}">お支払情報設定</a></li>
        <li><a href="{{ route('student.setting.inquiry') }}">お問い合わせ</a></li>
</ul>
@endsection

@section('custom_js')
<script>
// let URI_CHAT = @JSON(route('ajax.chat'));

// axios.post(URI_CHAT,{
//                   user_to: 1,
//                   user_from: 10,
//                   message_id: 1,
//                   message: 'phuhong _ test abc 456',
//                   path: null,
//                   _token:_token
//     }).then(function(output){
//             console.log(output);
//     });

</script>
@endsection
