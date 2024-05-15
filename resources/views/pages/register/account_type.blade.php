@extends('layouts.register.index')

@section('custom_css')
<!-- <script src="//cdn.jsdelivr.net/npm/jquery-validation@1.19.1/dist/jquery.validate.min.js"></script> -->
<link rel="stylesheet" href="{{ asset('register/css/slick/slick.css') }}">
<link rel="stylesheet" href="{{ asset('register/css/slick/slick-theme.css') }}">
@endsection

@section('class_section','account_type')
@section('class_body','account_type chrome')

@section('content')
<div class="login-bottom">
  <div class="login-bottom-content">
      @include("component.register.alert")
      <form class="loginbox_c full-width" method="post" id="from_submit" >
      @csrf
      @method('PATCH')
                          <div class="opt-tit">
                        <h2 class="text_center title">アカウントタイプをご選択<br class="dissp">ください</h2>
                      </div>
                    <div class="block-img">
                      <div class="item"  onclick="submitRole('student')" >
                        <figure><img src="{{ asset('register/images/account_001.png') }}" alt="生徒"></figure>
                        <div class="text_center">
                        <!--  -->
                          <button type="button" class="figcaption">生徒</button>
                        </div>
                      </div>
                      <div class="item" onclick="submitRole('teacher')" >
                        <figure><img src="{{ asset('register/images/account_002.png') }}" alt="先生"></figure>
                        <div class="text_center">

                          <button type="button"  class="figcaption">先生</button>
                        </div>
                      </div>
                    </div>
      </form>
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
      $(document).on('ready', function () {
        if($(window).width() < 639){
          console.log('abc');
          $(".block-img").slick({
            dots: false,
            infinite: true,
            centerMode: false,
            slidesToShow: 1,
            slidesToScroll: 1,
            autoplay: true,
            autoplaySpeed: 3000,
            arrows: true
          });
        }
      });
        function submitRole(role){
            $('#from_submit').append($('<input>',{type: 'hidden', name:'role',value:role}))
            $('#from_submit').submit();
        }

    </script>
@endsection