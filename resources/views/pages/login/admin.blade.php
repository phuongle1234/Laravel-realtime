@extends('layouts.admin.index')

@section('body_class','p-login')

@section('content')
<main class="p-content-login">
            <div class="container-fluid">
              <h2 class="tit-login"><img src="../common_img/logo.svg" alt="edutoss"></h2>
              @include("component.erorr.index")
              <div class="loginbox-wrapper">
                <div class="loginbox fz16">

                <form method="post">
                @csrf
                    <div class="loginbox_c">
                        <figure class="logobox"><img src="../common_img/logo-login.svg" alt="edutoss">
                        <figcaption>管理画面</figcaption>
                        </figure>
                        <div class="formlogin">
                        <div class="formbox_e">
                            <input class="form-control" name="email" type="email" placeholder="ログインID" maxlength="50">
                        </div>
                        <div class="formbox_e">
                            <div class="fsc boxpassword">
                            <input class="form-control pass_log_id" name="password" type="password" minlength="8" maxlength="12" placeholder="パスワード" ><i class="fas fa-eye toggle-password"></i>
                            </div>
                        </div>
                        <div class="formbox_e boxbtn">
                            <button type="submit" class="btn btn-primary">ログイン</button>
                        </div>
                        </div>
                        <!-- <div class="forgetpass"><a class="center textcl" href="#">パスワードをお忘れ点方</a></div> -->
                    </div>
                <form>
                </div>
              </div>
            </div>
          </main>
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