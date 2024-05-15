@extends('layouts.student.index')

@section('body_class','main_body p-setting faq hasbg')
@section('class_main','p-content-setting faq')

@section('class_main','p-content-setting')

@section('content')
<div class="setting-wrapper">

        <span class="back-link fsc" >
                <a href="{{ route('student.home') }}"><img src="{{ asset('student/images/back-icon.svg') }}" alt="{{ trans("common.setting.payment") }}"></a>
                <span>{{ trans("common.faq.law") }}</span>
        </span>


        @include('component.register.alert')
        <div class="setting-bg" action="">
          <div class="docx">
              @include('component.law.index')
          </div>
        </div>
@endsection

@section('custom_js')

<script>

</script>
@endsection
