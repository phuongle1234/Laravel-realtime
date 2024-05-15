@extends('layouts.teacher.index')

@section('body_class','main_body p-message type1 pdbottom-page')
@section('class_main','p-content-message type1 pdbottom-page notification')
@php
  $_notifications = Auth::guard('teacher')->user()->notifications()->where('via','broadcast')->get();

  $id = isset($id) ? $id : null;
  $_noti_select = $_notifications->where('id',$id)->first();
  if($_noti_select)
    $_noti_select->markAsRead();

@endphp

@section('content')
<div class="message-wrapper">
        <div class="message-sidebar">
          <ul class="scroll-nonedefault">
              @foreach($_notifications as $_noti)
                    <li class="{{ ! $_noti->read_at ? 'noread' : null }} {{ $_noti->id == $id ? 'class=active' : null }} " >
                        <a href="{{ route('teacher.notification.show',['id' => $_noti->id]) }}">
                            <div class="mess-ins">
                                  <div class="title">
                                    <div class="text">{{ $_noti->title }}</div>
                                    <div class="time">{{ $_noti->created_at->format('Y年m月d日 H:i') }}</div>
                                  </div>

                                  <div class="content">{!! $_noti->content !!}</div>
                              </div>
                          </a>
                    </li>
              @endforeach
          </ul>
        </div>
                <div class="dissp">
                  <div class="back-link fsc"> <a href="{{ route('teacher.notification.index') }}"> <img src="{{ asset('student/images/back-icon.svg') }}" alt="メッセージ"><span>メッセージ</span> </a></div>
                </div>

                <div class="message-content">

                  @if(  $_noti_select )
                    <div class="green-title">
                      <h3>{{ $_noti_select->title }}</h3>
                      <p>{{ $_noti_select->created_at->format('Y年m月d日 H:i') }}</p>
                    </div>
                    <div class="box">{!! nl2br($_noti_select->content) !!}</div>
                  @endif

                </div>

              </div>
@endsection

@section('custom_js')
<script>
// using sp

if( $(window).width() < 960 ){
  let _id = '{{ $id }}';

  if(_id){
      $('div.message-sidebar').hide();
  }else{
      $('.message-content').hide();
  }

}
</script>
@endsection
