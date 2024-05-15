@extends('layouts.teacher.index')

@section('body_class','p-setting notification pdbottom hasbg')

@section('class_main','p-content-setting notification pdbottom')

@section('content')
<section><a class="back-link fsc" href="{{ route('teacher.setting.index') }}"><img src="{{asset('images/back-icon.svg')}}" alt="リクエスト受付設定"><span>リクエスト受付設定</span></a>
  @include('component.register.alert')

  <form class="setting-content" action URL="{{ URL::current() }}" method="post" id="form-request-reception" >
    @csrf

  <ul class="list-links list-noti">
      <li><span>直接リクエストを受け付ける</span>
        <label class="switch">
          <input  type="checkbox" name="accept_directly" {{ isset( $item ) && $item->accept_directly  == 0   ? null : 'checked' }} ><span class="slider"></span>
        </label>
      </li>
    </ul>
  </form>
  </section>
  @endsection
@section('custom_js')

<script>
$('input[name="accept_directly"]').click(function(){
      $('#form-request-reception').submit();
})
</script>
@endsection