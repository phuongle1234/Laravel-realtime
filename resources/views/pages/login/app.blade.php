@extends('layouts.register.index')
<style>
    .toggleEye {
        display: flex;
        justify-content: center;
        align-items: center;
        width: 30px;
        height: 40px;
        position: absolute;
        right: 5px;
        top: 0;
        bottom: 0;
        margin: auto;
    }
    .toggleEye svg {
        width: auto !important;
        max-width: 100% !important;
        height: auto;
        position: relative !important;
        left: 0;
    }
</style>
@section('custom_title')
<a class="btn btn-lightgreen" href="{{ route('register.index') }}">新規会員登録</a>
@endsection

@php
  $_email_input = request()->cookie('_email_input');
  $_email_input = $_email_input ? \Illuminate\Support\Facades\Crypt::decryptString( $_email_input ) : null;
@endphp

@section('content')


<div class="loginbox-wrapper">
    <div class="loginbox fz16">
    @include('component.register.alert')
        <div class="loginbox_c">

            <figure class="logobox"><img src="{{ asset('register/images/login/logo3.png') }}" alt="edutoss"></figure>
            <form method="post" id="submitLogin">
                @csrf
                    <div class="formlogin">
                      <div class="formbox_e">
                        <input class="form-control" type="email" name="email" value="{{ old('email') ? old('email') : $_email_input }}" placeholder="ログインID（e-mail）" maxlength="50" required>
                      </div>
                      <div class="formbox_e">
                        <div class="fsc boxpassword">
                          <input class="form-control pass_log_id" type="password" name="password" value="{{ old('password') }}" minlength="6" maxlength="12" placeholder="パスワード（6文字以上の半角英数字）" required>
                            <div class="toggleEye">
                                <i class="fas fa-eye toggle-password"></i>
                            </div>
                        </div>
                      </div>
                      <div class="formbox_e form-check">
                        <input class="form-check-input" id="flexCheckDefault" name="remember" value="1" type="checkbox" {{ old('password') ? "checked" : null }}  >
                        <label class="form-check-label" for="flexCheckDefault">ログイン情報を保存する</label>
                      </div>
                      <div class="formbox_e boxbtn">
                        <button type="submit" class="btn btn-lightgreen">ログイン</button>
                      </div>
                    </div>
              </form>
                    <div class="forgetpass"><a class="center textcl" href="{{ route('forgotPass') }}">パスワードをお忘れの方 </a></div>
                    <p class="createnew text_center">アカウントをお持ちでない方<a class="clgreen" href="{{ route('register.index') }}">新規登録</a></p>
    </div>
  </div>
</div>
@endsection

@section('custom_js')
<script>


       $(document).ready(function(){
        $("body").on('click', '.toggle-password', function() {
          $(this).toggleClass("fa-eye fa-eye-slash");
          var input = $(".pass_log_id");
          if (input.attr("type") === "password") {
            input.attr("type", "text");
          } else {
            input.attr("type", "password");
          }
        });
      });



</script>
@endsection