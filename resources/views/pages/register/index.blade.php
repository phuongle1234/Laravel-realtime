@extends('layouts.register.index')

@section('class_body','p-newcreate')
@section('class_main','p-newcreate')
@section('class_section','p-newcreate')

@section('custom_title')
<!-- <a class="btn btn-lightgreen" href="../">新規会員登録</a> -->
<a class="btn btn-lightgreen" href="{{ route('loginMyApp') }}">ログイン</a>
@endsection

@section('content')

@include('component.register.alert')
<div class="loginbox-wrapper">
    <div class="loginbox fz16">

        <div class="loginbox_c">
                    <figure class="logobox"><img src="{{ asset('register/images/login/logo4.png') }}" alt="edutoss"></figure>
            <form method="post">
                @csrf
                    <div class="formlogin">
                      <div class="formbox_e">
                        <input class="form-control" type="email" name="email" value="{{ old('email') }}" placeholder="ログインID（e-mail）" maxlength="50" required>
                      </div>
                      <div class="formbox_e">
                        <div class="fsc boxpassword">
                          <input class="form-control pass_log_id" type="password" name="password" value="{{ old('password') }}" minlength="6" maxlength="12" placeholder="パスワード（6文字以上の半角英数字）" required><i class="fas fa-eye toggle-password"></i>
                        </div>
                      </div>

                      <!-- <div class="formbox_e form-check">
                        <input class="form-check-input" id="flexCheckDefault" type="checkbox" {{ old('password') ? "checked" : null }}  required>
                        <label class="form-check-label" for="flexCheckDefault">ログイン情報を保存する</label>
                      </div> -->
                      <div class="formbox_e boxbtn">
                        <button type="submit" class="btn btn-lightgreen">新規登録</button>
                      </div>
                    </div>
                    <p class="createnew text_center mt20">すでにアカウントをお持ちの方<a class="clgreen" href="{{ route('loginMyApp') }}">ログイン</a></p>
              </form>
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