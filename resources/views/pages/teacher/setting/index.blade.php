@extends('layouts.teacher.index')

@section('body_class','main_body p-setting pdbottom hasbg')
@section('class_main','p-content-setting pdbottom')

<!-- section('custom_title')
<img src="{{ asset('student/common_img/icon_title1.svg') }}" alt="プロフィール">
endsection -->

@section('content')
@include('component.register.alert')
                <ul class="list-links">
                  <li><a href="{{route('teacher.setting.profile')}}">プロフィール編集</a></li>
                  <li><a href="{{route('teacher.setting.notification')}}">通知設定</a></li>
                  <li><a href="{{route('teacher.setting.request_reception')}}">リクエスト受付設定</a></li>
                  <li><a href="{{route('teacher.setting.account_info')}}">口座情報設定</a></li>
                  <li><a href="{{route('teacher.setting.inquiry')}}">お問い合わせ</a></li>
                </ul>
                @endsection

                @section('custom_js')
                <script>
              </script>
              @endsection
              