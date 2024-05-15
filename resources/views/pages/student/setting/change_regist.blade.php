@extends('layouts.student.index')

@section('body_class','main_body p-setting hasbg')
@section('class_main','p-content-setting')
@php
  $_user = Auth::guard('student')->user();
@endphp
@section('content')
<div class="setting-wrapper">

        <a class="back-link fsc" href="{{ route('student.setting.index') }}">
                <img src="{{ asset('student/images/back-icon.svg') }}" alt="{{ trans("common.setting.regist") }}"><span>{{ trans("common.setting.regist") }}</span>
        </a>
        @include('component.register.alert')
                <div class="setting-bg" action="">
                <form class="setting-content" method="post" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                      <input style="display:none" name="avatar" type="file" id="file-upload">
                      <!-- width: 280px;height: 180px; margin-left: 250px;margin-bottom: 20px;  -->
                      <div onclick='$("#file-upload").trigger("click");' style="cursor: pointer;">
                      <figure  class="avatar-img">
                      <img src="{{ $_user->avatar_img }}" alt="{{ $_user->name }}"></figure>

                      <figcaption class="avatar-text">画像を選択</figcaption>
                      </div>
                      <div class="setting-body">
                        <dl class="inline-box">
                          <dt>ユーザー名</dt>
                          <dd>
                            <input class="form-control" type="text" name="name" value="{{ $_user->name }}" maxlength="20" required>
                          </dd>
                        </dl>

                        <dl class="inline-box">
                          <dt>ログインID</dt>
                          <dd>
                            <input class="form-control" type="email" name="email" value="{{ $_user->email }}" maxlength=50 required>
                          </dd>
                        </dl>

                        <dl class="inline-box">
                            <dt>パスワード</dt>
                            <dd>
                              <input class="form-control" type="password" name="password" minlength="6" maxlength="15" value="{{ old('password') }}"  placeholder="**************" require>
                            </dd>
                          </dl>

                      </div>
                      <div class="text_center">
                        <button type="submit" class="btn btn-primary">登録する</button>
                      </div>
                    </form>
                </div>
        </div>
@endsection

@section('custom_js')
<script>
// let URI_CHAT = @JSON(route('ajax.chat'));

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
              console.log( ext, extension );
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

</script>
@endsection
