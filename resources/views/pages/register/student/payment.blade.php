@extends('layouts.register.index')

@section('custom_css')
<link rel="stylesheet" href="{{ asset('register/css/slick/slick.css') }} ">
<link rel="stylesheet" href="{{ asset('register/css/slick/slick-theme.css') }} ">
@endsection

@section('class_main', 'p-content-login regist')
@section('class_section' , 'main_body p-login regist')
@section('class_body' , 'secondpage navstate_show page-login regist')
@section('content')

<div class="login-bottom">
                <div class="login-bottom-content">
                @include('component.register.alert')
                  <div class="loginbox_c full-width screen-back">
                    <a class="btn-back" onclick="history.back()" ><img src="{{ asset('register/images/back-icon.svg') }}" alt=""></a>
                    <div class="payment-box">
                      <div class="opt-tit">
                        <h2 class="text_center title">お支払情報設定</h2>
                      </div>
                      <figure><img src="{{ asset('register/images/img-card.png') }}" alt=""></figure>
                  <form method="post" action="{{ route('register.complete',['token' => request()->token]) }}" id="form-submit">
                    @csrf
                        <dl>
                          <dt>カード番号</dt>
                          <dd id="cardNumber">
                            <!-- <input class="form-control" type="text" name=""  placeholder="0000000000000000"> -->
                          </dd>
                        </dl>
                        <dl>
                          <dt>有効期限</dt>
                          <dd id="cardExpiry">
                            <!-- <input class="form-control w50" type="text" name="" id="cardExpiry" placeholder="mm/yy"> -->
                          </dd>
                        </dl>
                        <dl>
                          <dt>セキュリティコード</dt>
                          <dd>
                            <div class="box-cvc" >
                              <div id="cardCvc" style="width: 355px"></div>
                              <!-- <input class="form-control w50" type="text" name="" id="cardCvc" placeholder="123"> -->
                              <span>CVCとは</span>
                              <div class="show-btn" data-bs-toggle="modal" data-bs-target="#cvcPopup">
                                <img src="{{ asset('register/images/show-btn.svg') }}" alt="">
                              </div>
                            </div>
                          </dd>
                        </dl>

                      <div class="btn-wrapper">
                        <button type="submit" class="btn btn-lightgreen">次へ</button>
                      </div>

                    </div>
                    </form>

                  </div>
                </div>
              </div>



              <div class="modal fade popup-style" id="cvcPopup" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title">CVCとは</h5>
                      <button class="close" data-bs-dismiss="modal" aria-hidden="true"></button>
                    </div>
                    <div class="modal-body">
                      <p>CVCとは、セキュリティコードと呼ばれる番号のことです。クレジットカードやデビットカード裏面のサインパネルに記載されている数字の末尾3~4桁のこと指していて、本人確認をするためや不正利用を防ぐ役割があります。その他にも、インターネットショッピングでカード決済をする際に、カードが利用者自身お手元にあることを確認するために使われることが多いです。</p>
                    </div>
                  </div>
          </div>
  </div>
@endsection

@section('custom_js')
<script src="{{ asset('register/js/slick.min.js') }}"></script>
<script src="https://js.stripe.com/v3/"></script>
<script>
let stripe = Stripe("{{ env('STRIPE_KEY') }}",{
  locale: 'ja'
});

var elements = stripe.elements();

var cardNumberElement = elements.create('cardNumber');
    cardNumberElement.mount('#cardNumber');

var cardExpiryElement = elements.create('cardExpiry');
    cardExpiryElement.mount('#cardExpiry');

var cardCvCElement = elements.create('cardCvc');
    cardCvCElement.mount('#cardCvc');

var form = document.getElementById('form-submit');

form.addEventListener('submit', function(event){

     $('#loading').show();


        event.preventDefault();
        stripe.createToken(cardNumberElement).then(function(result){

            if(result.error){
                handleShowError(result.error.message);
            }else{

                $('#form-submit').append($('<input>',{name:'token_stripe', type:'hidden',value:result.token.id}));
                $('#form-submit').submit();
                //form.appendTo('body').submit();
                //form.remove();
            }

        });
});

function handleShowError(messege_errors) {

$('#loading').hide();

$('div.errors').remove();

          html_erorr =    `<div class="alert alert-dark"><span>${messege_errors}</span>
                                <button class="btn-close" type="button">
                                  <svg xmlns="http://www.w3.org/2000/svg" width="29" height="29" viewBox="0 0 29 29" fill="none">
                                    <rect width="2.5" height="20" rx="1.25" transform="matrix(-0.707107 -0.707107 -0.707107 0.707107 22.0972 7.95496)" fill="black"></rect>
                                    <rect width="2.5" height="20" rx="1.25" transform="matrix(-0.707107 -0.707107 -0.707107 0.707107 22.0972 7.95496)" fill="white"></rect>
                                    <rect width="2.5" height="20" rx="1.25" transform="matrix(-0.707107 0.707107 0.707107 0.707107 7.95526 6.18689)" fill="black"></rect>
                                    <rect width="2.5" height="20" rx="1.25" transform="matrix(-0.707107 0.707107 0.707107 0.707107 7.95526 6.18689)" fill="white"></rect>
                                  </svg>
                                </button>
                            </div>`;
$( "div.login-bottom-content" ).prepend(html_erorr);
//
// $('button[type="submit"]').prop("disabled",false);
// $('button[type="submit"]').parent().removeClass("disabled");
//
// $('html, body').animate({ scrollTop: $('div.headtitle').offset().top}, 1000);
}
</script>
@endsection