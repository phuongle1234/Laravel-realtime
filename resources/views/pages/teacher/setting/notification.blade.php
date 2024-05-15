@extends('layouts.teacher.index')

@section('body_class','main_body p-setting notification pdbottom hasbg')

@section('class_main','p-content-setting notification pdbottom')
@section('content')
<section><a class="back-link fsc" href="{{route('teacher.setting.index')}}"><img src="{{asset('images/back-icon.svg')}}" alt="通知設定"><span>通知設定</span></a>
  @include('component.register.alert')

  <form class="setting-content" action URL="{{ URL::current() }}" method="post" id="form-setting" >
    @csrf
    <ul class="list-links list-noti">

                  <li><span>お知らせをメールで受信する</span>
                    <label class="switch">
                      <input id="1" type="checkbox" class="setting-checkbox" name="notifications_by_email" @if(@$item->notifications_by_email == 1) checked @endif><span class="slider"></span>
                    </label>
                  </li>
                  <li><span>運営からのお知らせ</span>
                    <label class="switch">
                      <input id="2" type="checkbox" class="setting-checkbox" name="notifications_from_admin" @if(@$item->notifications_from_admin == 1) checked @endif><span class="slider"></span>
                    </label>
                  </li>
                  <li><span>その他のお知らせ</span>
                    <label class="switch">
                      <input id="3" type="checkbox" class="setting-checkbox" name="other_notices" @if(@$item->other_notices ==1 ) checked @endif><span class="slider"></span>
                    </label>
                  </li>
                </ul>
  </form>
              </section>
@endsection
@section('custom_js')
<script>
$('.setting-checkbox').on('change',function(){
      $('#form-setting').submit();
})
</script>
@endsection