@extends('layouts.student.index')

@section('body_class','main_body p-setting hasbg')
@section('class_main','p-content-setting')
@php
  $_notifications = Auth::guard('student')->user()->settings()->first();

  if(!$_notifications){
    $_notifications = (object)[];
    $_notifications->notifications_by_email	 = false;
    $_notifications->notifications_from_admin	= true;
    $_notifications->other_notices	= true;
  }

@endphp
@section('content')
<div class="setting-wrapper">

        <a class="back-link fsc" href="{{ route('student.setting.index') }}">
                <img src="{{ asset('student/images/back-icon.svg') }}" alt="{{ trans("common.setting.regist") }}"><span>{{ trans("common.setting.notification") }}</span>
        </a>
        @include('component.register.alert')
                <div class="setting-bg" action="">
                <form class="setting-content" method="post" id="form-submit">
                @csrf
                @method('PUT')
                        <ul class="list-links list-noti">
                          <li><span>お知らせをメールで受信する</span>
                            <label class="switch">
                              <input id="1" name="notifications_by_email" onChange="$('#form-submit').submit()" type="checkbox" value="true" {{ $_notifications->notifications_by_email ? 'checked' : null }} ><span class="slider"></span>
                            </label>
                          </li>
                          <li><span>運営からのお知らせ</span>
                            <label class="switch">
                              <input id="2" name="notifications_from_admin" onChange="$('#form-submit').submit()"  type="checkbox" value="true" {{ $_notifications->notifications_from_admin ? 'checked' : null }}  ><span class="slider"></span>
                            </label>
                          </li>
                          <li><span>その他のお知らせ</span>
                            <label class="switch">
                              <input id="3" name="other_notices"  onChange="$('#form-submit').submit()"  type="checkbox" value="true" {{ $_notifications->other_notices ? 'checked' : null }} ><span class="slider"></span>
                            </label>
                          </li>
                        </ul>
                    </form>
                </div>
        </div>
@endsection

@section('custom_js')
<script>

</script>
@endsection
