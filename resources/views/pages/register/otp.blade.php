@extends('layouts.register.index')

@section('custom_css')
<script src="//cdn.jsdelivr.net/npm/jquery-validation@1.19.1/dist/jquery.validate.min.js"></script>
@endsection

@section('content')
<div class="login-bottom">
<div class="login-bottom-content">
        @include('component.register.alert')
                  <form class="loginbox_c resetpass otp-block validation" id="otpform" method="post">
                  @csrf
                    <div class="opt-tit">
                      <h2 class="text_center title">認証コードをご入力ください。</h2>
                      <p class="text_center text_note">ご登録いただいたe-mailアドレス宛に、<br class="dissp">認証コードを送信しました。</p>
                    </div>
                    <div class="number">
                      <div class="number-box">
                        <div class="item">
                          <input class="otp-input pass_otp" name="otpInput" min="0" max="9" maxlength="1" pattern="[0-9]" type="text" aria-invalid="false">
                        </div>
                        <div class="item">
                          <input class="otp-input" name="otpInput" min="0" max="9" maxlength="1" pattern="[0-9]" type="text" aria-invalid="false">
                        </div>
                        <div class="item">
                          <input class="otp-input" name="otpInput" min="0" max="9" maxlength="1" pattern="[0-9]" type="text" aria-invalid="false">
                        </div>
                        <div class="item">
                          <input class="otp-input" name="otpInput" min="0" max="9" maxlength="1" pattern="[0-9]" type="text" aria-invalid="false">
                        </div>
                        <div class="item">
                          <input class="otp-input" name="otpInput" min="0" max="9" maxlength="1" pattern="[0-9]" type="text" aria-invalid="false">
                        </div>
                        <div class="item">
                          <input class="otp-input" name="otpInput" min="0" max="9" maxlength="1" pattern="[0-9]" type="text" aria-invalid="false">
                        </div>
                      </div>
                    </div>
                    <div class="text_center resendmail">

                      <p>メールが届かない場合はこちら</p><a class="textcl text_bold" href="{{ route('register.resend_otp',["token" => request()->token]) }}">メールを再送する</a>
                    </div>
                  </form>
                </div>
  </div>
@endsection

@section('custom_js')
<script>
      $("#otpform").validate(
        {
          rules:
          {
            otpInput:
            {
              number: true,
            }
          },
          messages: {
            otpInput: "",
          }
      });
      $(".pass_otp").bind("paste", function(e){
          var pastedData = e.originalEvent.clipboardData.getData('text');
          $('.number-box .item').each(function(index){
            $(this).find('.otp-input').val(pastedData[index]);
            $(this).find('.otp-input').focus();
          });
      });

      $("input[name=otpInput]").keyup(function(){
          //check otp
          var _check_input = 0;
          var value = '';
          $("input[name=otpInput]").each(function(index,element){
              if(val = $(element).val()){
                  value += val;
                _check_input ++;
              }
          })

          if( _check_input == $("input[name=otpInput]").length ){
            $('#otpform').append($('<input>',{type: 'hidden', name:'otp',value:value}))
            $('#otpform').submit();
          }
      })
    </script>
@endsection