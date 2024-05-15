@extends('layouts.student.index')

@section('body_class','main_body p-setting hasbg')

@section('class_main','p-content-setting payment_info_setting')

@section('content')
<div class="setting-wrapper">

        <a class="back-link fsc" href="{{ route('student.setting.payment') }}">
          <img src="{{ asset('student/images/back-icon.svg') }}" alt="{{ trans("common.setting.payment") }}"><span>{{ trans("common.setting.payment_add") }}</span>
        </a>

        @include('component.register.alert')
        <div class="setting-bg" action="">
          <form class="setting-content" id="form-submit" method="post">
          @csrf
          @method('PUT')
          <div class="setting-body">
                        <dl class="noinline">
                          <dt>カード番号</dt>
                          <dd id="cardNumber">
                            <!-- <input class="form-control" type="text" name="name" maxlength="12"> -->
                          </dd>
                        </dl>
                        <dl class="noinline">
                          <dt>名義</dt>
                          <dd >
                              <input id="cardName" class="form-control" type="text" name="cardName">
                          </dd>
                        </dl>
                        <div class="box-csv">

                          <dl class="noinline">
                            <dt>有効期限</dt>
                            <dd id="cardExpiry">
                              <!-- <input class="form-control" type="text" name="category" placeholder="mm/yy"> -->
                            </dd>
                          </dl>

                          <dl class="noinline">
                            <dt>セキュリティコード</dt>
                            <dd id="cardCvc">
                            </dd>
                          </dl>
                          <div class="csv-pp"><span>CVCとは</span><img src="{{ asset('student/images/show-btn.svg') }}" style="cursor: pointer;" alt="CVCとは" data-bs-toggle="modal" data-bs-target="#cvcPopup"></div>
              </div>
              </div>
              <div class="text_center">
                <button class="btn btn-outline-primary">設定する</button>
              </div>
          </form>
        </div>
    <div class="modal fade popup-style popup-bottom-sp" id="cvcPopup" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <button class="close" data-bs-dismiss="modal" aria-hidden="true"></button>
                <div class="modal-body">
                    <h2 class="cvc-title">CVCとは</h2>
                    <p>CVCとは、セキュリティコードと呼ばれる番号のことです。クレジットカードやデビットカード裏面のサインパネルに記載されている数字の末尾3~4桁のこと指していて、本人確認をするためや不正利用を防ぐ役割があります。その他にも、インターネットショッピングでカード決済をする際に、カードが利用者自身お手元にあることを確認するために使われることが多いです。</p>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom_js')
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

    event.preventDefault();
    $('#loading').show();
    event.preventDefault();

    stripe.createToken(cardNumberElement,{ name: $('#cardName').val() }).then(function(result){

        if(result.error){
            handleShowError(result.error.message);
        }else{
            $('#form-submit').append($('<input>',{name:'token_stripe', type:'hidden',value:result.token.id}));
            $('#form-submit').submit();
        }

    });

})


function handleShowError(messege_errors) {

  $('#loading').hide();

  $('div.alert-dark').remove();

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

  //$( "div.setting-wrapper" ).insertAfter(html_erorr);
  $( html_erorr ).insertAfter("a.back-link");

  $('html, body').animate({ scrollTop: $('section.main_body').offset().top}, 1000);
}
</script>
@endsection
