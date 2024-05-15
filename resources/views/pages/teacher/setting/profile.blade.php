@php
  $_item_subject = collect( $item->userSubject->toArray() )->map(function($val,$index){ return $val['subject_id'];  })->toArray();
@endphp

@extends('layouts.teacher.index')
@section('custom_css')
<style>
  ul.select-options {
    display: none!important
  }
  .select-styled:after{
    content: none!important
  }
</style>
@endsection
@inject('schoolMaster', 'App\Models\SchoolMaster')
@inject('subjects', 'App\Models\Subject')

@section('body_class','p-setting profile pdbottom hasbg')

@section('class_main','p-content-setting profile pdbottom')

@section('content')
<div class="setting-wrapper"><a class="back-link fsc" href="{{route('teacher.setting.index')}}"><img src="{{asset('images/back-icon.svg')}}" alt="プロフィール編集"><span>プロフィール編集</span></a>  @include('component.register.alert')

  <div class="setting-bg" action="">
      <form class="setting-content" method="post" id="form-submit" enctype="multipart/form-data">
        @csrf
        <input style="display:none" name="avatar" type="file" id="file-upload">
      <div onclick='$("#file-upload").trigger("click");' style="cursor: pointer;">
        <figure   class="avatar-img img-circle"><img src="{{ $item->avatar_img }}" alt="{{ $item->name }}"></figure>
        <figcaption class="avatar-text">画像を選択</figcaption>
      </div>
        <div class="addiconfix mb30">
          <div class="addicon">
            <span class="clgray">アイコンがついているものがedutoss上で公開されます。</span>
          </div>
        </div>
        <div class="setting-body">
          <dl class="inline-box">
            <dt class="addicon">ユーザー名</dt>
            <dd>
              <input class="form-control" type="text" name="name" required maxlength="20" value="{{old('name',@$item->name)}}">
            </dd>
          </dl>
          <dl class="inline-box">
            <dt>氏名</dt>
            <dd>
              <input class="form-control" type="text" name="last_name" disabled maxlength="20" value="{{old('last_name',@$item->last_name)}}">
            </dd>
          </dl>
          <dl class="inline-box">
            <dt>ふりがな</dt>
            <dd>
              <input class="form-control" type="text" name="kana" disabled maxlength="20" value="{{old('kana',@$item->kana)}}">
            </dd>
          </dl>
          <dl class="inline-box">
            <dt>生年月日</dt>
            <dd>
              <div class="column3">
                <div class="item">
                  <div class="calendar-input">
                    <input class="form-control" type="number"  disabled value="{{ old('year',\Carbon\Carbon::parse($item->birthday)->format('Y'))}}" name="year">
                  </div><span>年</span>
                </div>
                <div class="item">
                  <div class="calendar-input">
                    <input class="form-control" type="number" disabled value="{{ old('month',\Carbon\Carbon::parse($item->birthday)->format('m'))}}" name="month">
                  </div><span>月</span>
                </div>
                <div class="item">
                  <div class="calendar-input">
                    <input class="form-control" type="number" disabled value="{{ old('day',\Carbon\Carbon::parse($item->birthday)->format('d'))}}" name="day">
                  </div><span>日</span>
                </div>
              </div>
            </dd>
          </dl>
          <dl class="inline-box">
            <dt>性別</dt>
            <dd>
              <select name="sex" disabled>
                <option value="{{$item->sex}}" >{{  EUser::GENDER[ $item->sex ] }}</option>
              </select>
            </dd>
          </dl>
          <dl class="inline-box">
            <dt class="addicon">最終学歴</dt>
            <dd>
              <select class="select" name="university_Code"  data-label="最終学歴" disabled>
             <option value="{{$item->university_Code}}">{{$schoolMaster->university_name($item->university_Code)->university_name}}</option>
            </select>
            </dd>
          </dl>
          <dl class="inline-box">
            <dt>学部</dt>
            <dd>

              <select class="select" name="faculty_code"  data-label="学部"  disabled>
                @if($item->faculty_code)
                <option id='' value="{{$item->faculty_code}}" selected >{{$item->faculty($item->faculty_code)->first()->faculty_name}}</option>
@endif
              </select>
            </dd>
          </dl>
          <dl class="inline-box">
            <dt>在学/卒業</dt>
            <dd>

          @foreach( EUser::EDU_STATUS as $_key => $_value )
            <label class="mr50 customck">
              <input type="radio" id="men" name="edu_status" value="{{ $_value }}" {{ ( $_value == $item->edu_status ) || ( ! in_array( $item->edu_status , EUser::EDU_STATUS) &&  $_key == 1 ) ? 'checked' : null }} require>
              <span for="men" class="checkmark">{{ $_value }}</span>
            </label>
          @endforeach

            </dd>
          </dl>

          <dl class="inline-box">
            <dt>e-mail</dt>
            <dd>
              <input class="form-control" type="email" name="email" maxlength="50" value="{{old('email',@$item->email)}}">
            </dd>
          </dl>

          <!-- パスワード -->

          <dl class="inline-box">
            <dt>パスワード</dt>
            <dd>
              <input class="form-control" type="password" name="password" minlength="6" maxlength="15" value="{{ old('password') }}"  placeholder="**************" require>
            </dd>
          </dl>

          <dl class="inline-box">
            <dt>電話番号</dt>
            <dd>
              <input class="form-control" type="text" name="tel" value="{{old('tel',@$item->tel)}}"  pattern="([0-9]{10,11})|([0-9]{3}-[0-9]{3}-[0-9]{4})" >
            </dd>
          </dl>

          <dl class="inline-box">
            <dt>科目</dt>
            <dd>
              <ul class="list-courses">

                @foreach( $subjects::all() as $subject)
                <li>
                  <input type="checkbox" id="check{{$subject->id}}" value="{{ $subject->id }}" name="subject[]" {{ in_array( $subject->id, $_item_subject) ? 'checked' : null }} >
                  <div><img src="{{asset($subject->icon)}}" alt="{!! $subject->name !!}"><span>{!! $subject->name !!}</span></div>
                </li>
                @endforeach

              </ul>
            </dd>
          </dl>
          <dl class="inline-box">
            <dt class="addicon">自己紹介</dt>
            <dd>
              <textarea class="self-introduction form-control" maxlength="1000" name="introduction" cols="30" rows="4"  required="required">{{$item->introduction}}</textarea>
            </dd>
          </dl>
        </div>
        <div class="text_center">
          <button class="btn btn-primary">登録する</button>
        </div>
      </form>
    </div>
  </div>
  @endsection
  @section('custom_js')
  <script>
$('#file-upload').change( async function(value){

var ext = $(this).val().split('.').pop();

if( jQuery.inArray( ext, _ip_ext ) !== -1 )
      await convertHeicToJpg(value);

// if(!(jQuery.inArray(ext,['pdf','jpg','png']) !== -1)){
//     $(this).val('');
//     alert('PDF, PNG, JPEG, のみ添付可能です');
//     return false;
// }

let file = $(this)[0].files[0];

if(file){

  if(!(jQuery.inArray(ext, extension ) !== -1)){
      $(this).val('');
      alert(`${extension.join(', ')} のみ添付可能です`);
      return false;
  }

  if( file.size>10000000 ){
      alert('・10MB以内の画像をアップロードしてください。');
      $(this).val('');
      return false;
  }

  if( file.type.split('/')[0] != 'image'){
      alert('イメージのみ添付可能です。');
      return false;
  }

  let reader = new FileReader();
  reader.readAsDataURL(file);
  reader.onload = function(reader){
        $('figure.avatar-img img').attr('src',reader.srcElement.result);
  }

}

})

//$styledSelect.text($(this).text()).removeClass('active');
// $this.val($(this).attr('rel'));
// $list.hide();
// $(this).parent().find('li').removeClass('active');
// $(this).addClass('active');
</script>
@endsection
