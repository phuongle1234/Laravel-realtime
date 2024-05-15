@extends('layouts.register.index')
@inject('subject', 'App\Models\Subject')

<!-- inject('schoolMaster', 'App\Models\SchoolMaster') -->

@section('custom_css')
<link rel="stylesheet" href="{{ asset('register/css/slick/slick.css') }} ">
<link rel="stylesheet" href="{{ asset('register/css/slick/slick-theme.css') }} ">

<style>
  .regist-info figure div {
    text-align: center !important;
    width: 135px !important;
    height: 135px !important;
    border-radius: 50% !important;
    overflow: hidden !important;
    margin: 0 auto !important;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .select2-selection--single {
    background-color: #fbfbfb !important;
    border: none !important;
  }

  /* .regist-info figure img{
    width: 100%;
    height: 100%;
    object-fit: fill;
} */
</style>
@endsection

@section('class_main', 'regist regist-type2')
@section('class_section' , 'regist regist-type2')
@section('class_body' , 'regist regist-type2')


@section('content')

<div class="login-bottom">
  <div class="login-bottom-content">
    @include('component.register.alert')
    <div class="loginbox_c full-width screen-back">

          <div class="completedSection">
            <div class="opt-tit checkicon">
              <h2 class="text_center title">会員登録ありがとうございます！</h2>
            </div>
            <div class="text_center">
              <p>早速edutossを利用してみましょう。</p>
            </div>
            <div class="btn-wrapper">
              <a href="{{ route('student.home') }}" class="btn btn-lightgreen"><span>マイページへ</span></a>
            </div>
          </div>

    </div>
  </div>
</div>
@endsection

@section('custom_js')
<script async src="https://s.yimg.jp/images/listing/tool/cv/ytag.js"></script>
<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-1471529931314203" crossorigin="anonymous"></script>
<script>
  window.yjDataLayer = window.yjDataLayer || [];
  function ytag() { yjDataLayer.push(arguments); }
  ytag({"type":"ycl_cookie"});
</script>
<script async>
ytag({
  "type": "yss_conversion",
  "config": {
    "yahoo_conversion_id": "1001265339",
    "yahoo_conversion_label": "F9QbCPb94eQDEPDro_4o",
    "yahoo_conversion_value": "400"
  }
});
</script>

@endsection