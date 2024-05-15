@extends('layouts.register.index')

@section('custom_css')
<link rel="stylesheet" href="{{ asset('register/css/slick/slick.css') }} ">
<link rel="stylesheet" href="{{ asset('register/css/slick/slick-theme.css') }} ">
@endsection

@section('class_main', 'regist')
@section('class_section' , 'regist')
@section('class_body' , 'regist')

@section('content')
<div class="login-bottom">
  <div class="login-bottom-content">
    @include('component.register.alert')
    <div class="loginbox_c full-width fixheight">

      <a class="btn-back" onClick="$('.slick-slider').slick('slickPrev')">
        <img src=" {{ asset('register/images/back-icon.svg') }}" alt="">
      </a>

      <form id="form-submit" method="post">
        @csrf
        <div class="regist-slide">
          <div class="item">
            <div class="opt-tit">
              <h2 class="text_center title">基本情報登録</h2>
            </div>
            <table class="table-style table-sp">
              <tbody>
                <tr>
                  <th>ユーザー名</th>
                  <td>
                    <input pattern="^\S+$" class="form-control" name="name" maxlength="20" onChange="$('input[name=name]').val($(this).val())" value="{{ old('name') }}" type="text" placeholder="お名前太郎" required>
                  </td>
                </tr>
              </tbody>
            </table>
            <div class="btn-wrapper">
              <button type="submit" class="btn btn-lightgreen next-slide">次へ</button>
            </div>
          </div>
          <div class="item">
            <div class="opt-tit">
              <h2 class="text_center title">プラン選択</h2>
            </div>
            <ul class="select-list">
              <li data-plan="{{ EStripe::FREE_PLAN_ID }}" class="active"><span class="title">{{ EStripe::FREE_PLAN_NAME }}</span>
                <span class="text">{!! nl2br( EStripe::getDescriptByPlan( EStripe::FREE_PLAN_ID ) ) !!}</span><span class="unit">￥無料</span>
              </li>

              <li data-plan="{{ EStripe::STANDARD_PLAN_ID }}"><span class="title">{{ EStripe::STANDARD_PLAN_NAME }}</span>
                <span class="text">{!! nl2br( EStripe::getDescriptByPlan( EStripe::STANDARD_PLAN_ID ) ) !!}</span><span class="unit">
                  ￥{{ number_format(EStripe::STANDARD_PLAN_PRICE) }}<small>（税込）</small></span>
              </li>

              <li data-plan="{{ EStripe::PREMIUM_PLAN_ID }}" class="label">
                <span class="title">{{ EStripe::PREMIUM_PLAN_NAME }}</span>
                <span class="text">{!! nl2br( EStripe::getDescriptByPlan( EStripe::PREMIUM_PLAN_ID ) ) !!}</span>
                <span class="unit">￥{{ number_format(EStripe::PREMIUM_PLAN_PRICE) }}<small>（税込）</small></span>
              </li>
            </ul>
            <div class="btn-wrapper">
              <button type="submit" class="btn btn-lightgreen">次へ</button>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@section('custom_js')
<script src="{{ asset('register/js/slick.min.js') }}"></script>
<script>
  $('.slick-slider').slick({
    centerMode: true,
    focusOnSelect: true,
    accessibility: false,
    centerPadding: '0px',
    slidesToShow: 5,
    arrows: false,
    autoplay: true,
    autoplaySpeed: 0,
    adaptiveHeight: true,
    cssEase: 'linear',
    speed: 5000,
    responsive: [{
      breakpoint: 960,
      settings: {
        centerMode: true,
        centerPadding: '0px',
        slidesToShow: 5
      }
    }, {
      breakpoint: 768,
      settings: {
        centerMode: true,
        centerPadding: '0px',
        slidesToShow: 3
      }
    }]
  });
</script>
<!-- Structured data settings-->
<script>
  $(document).ready(function() {

    $('a.btn-back').hide();

    $('a.btn-back').on('click', (e) => {
      _index = $('div.slick-active').data('slick-index');
      if (!_index)
        $(e.target).hide();
    });



    $(".regist-slide").slick({
      dots: true,
      infinite: true,
      centerMode: false,
      slidesToShow: 1,
      slidesToScroll: 1,
      autoplay: false,
      arrows: false,
      adaptiveHeight: true,
      draggable: false,
      swipe: false
    });

    // $('button.next-slide').click(function(e) {
    //   e.preventDefault();
    //   //var slideno = $(this).data('slide');
    //   $('.regist-slide').slick('slickGoTo',3);
    // });

    $('.slick-dots li button').on('click', function(e) {
      e.stopPropagation();
    });

  });

  var form = document.getElementById('form-submit');

  form.addEventListener('submit', async (event) => {

    event.preventDefault();
    const _input = event.target;
    $('div.alert-dark').remove();
    // console.log( $('div.slick-active').data('slick-index')  );
    if ($('div.slick-active').data('slick-index') == 0) {

        _result = await fetch( window.location.origin + '/checkName', { method: 'POST', headers: { 'X-CSRF-TOKEN': _input.querySelector('input[name=_token]').value, 'Content-Type': 'application/json' }, body: JSON.stringify({ check_name: true , name: _input.querySelector('input[name=name]').value })  });

        if( _result.status > 200 )
        {
            repont = await _result.text();
            handleShowError( JSON.parse(repont).error.name );
            return false
        }

      $('a.btn-back').show();
      $('a.btn-back img').show();
      $('.regist-slide').slick('slickGoTo', 3);
      return false;
    }



    var plan = $('div.slick-active ul.select-list li.active').data('plan');

    $('#form-submit').append($('<input>', {
      name: 'plan',
      type: 'hidden',
      value: plan
    }));
    $('#form-submit').submit();

  })

const handleShowError = (messege_errors) => {

  $('#loading').hide();

  html_erorr =    `<div class="alert alert-dark"><span>${messege_errors}</span>
                        <button class="btn-close" type="button" onclick="$(this).parent().remove()" >
                          <svg xmlns="http://www.w3.org/2000/svg" width="29" height="29" viewBox="0 0 29 29" fill="none">
                            <rect width="2.5" height="20" rx="1.25" transform="matrix(-0.707107 -0.707107 -0.707107 0.707107 22.0972 7.95496)" fill="black"></rect>
                            <rect width="2.5" height="20" rx="1.25" transform="matrix(-0.707107 -0.707107 -0.707107 0.707107 22.0972 7.95496)" fill="white"></rect>
                            <rect width="2.5" height="20" rx="1.25" transform="matrix(-0.707107 0.707107 0.707107 0.707107 7.95526 6.18689)" fill="black"></rect>
                            <rect width="2.5" height="20" rx="1.25" transform="matrix(-0.707107 0.707107 0.707107 0.707107 7.95526 6.18689)" fill="white"></rect>
                          </svg>
                        </button>
                    </div>`;

  $( "div.login-bottom-content" ).prepend(html_erorr);
  $('html, body').animate({ scrollTop: $('body').offset().top}, 1000);
}

</script>
@endsection