@extends('layouts.teacher.index')
@section('body_class','p-setting law pdbottom hasbg')
@section('class_main','p-content-setting law pdbottom')
@section('custom_css')
<style>
div.text_polycy{
  padding: 60px 130px;
}
</style>
@endsection
@section('content')
<section>
  <div class="setting-wrapper"><div class="back-link fsc"><a href="{{route('teacher.home')}}"><img src="{{asset('images/back-icon.svg')}}" alt="特定商取引法に基づく表記"></a><span>特定商取引法に基づく表記</span></div>
    <div class="setting-bg" action="">
    <div class="docx">
          @include('component.law.index')
    </div>
  </div>
</section>
@endsection