@extends('layouts.teacher.index')
@section('body_class','p-setting privacy_policy pdbottom hasbg')
@section('class_main','p-content-setting privacy_policy pdbottom')

@section('content')
<section>
  <div class="setting-wrapper"><div class="back-link fsc"><a onclick="javascript:history.back()"><img style="cursor:pointer;" src="{{asset('images/back-icon.svg')}}" alt="プライバシーポリシー"></a><span>プライバシーポリシー</span></div>
    <div class="setting-bg">
        <div class="docx">
              @include('component.policy.index')
        </div>
    </div>
  </div>
</section>
@endsection