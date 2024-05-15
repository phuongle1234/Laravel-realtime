@extends('layouts.student.index')

@section('body_class','main_body p-setting faq hasbg')
@section('class_main','p-content-setting faq')

@section('class_main','p-content-setting')

@section('content')
<div class="setting-wrapper">

        <span class="back-link fsc" >
                <a onclick="javascript:history.back();"><img style="cursor: pointer;" src="{{ asset('student/images/back-icon.svg') }}" alt="{{ trans("common.setting.payment") }}"></a>
                <span>{{ trans("common.faq.policy") }}</span>
        </span>

        @include('component.register.alert')
        <div class="setting-bg">
                  <div class="docx">
                       @include('component.policy.index')
                  </div>
        </div>
</div>

@endsection

@section('custom_js')

<script>

</script>
@endsection
