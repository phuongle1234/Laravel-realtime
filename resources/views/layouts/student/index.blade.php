@php

    $_route = request()->route();
    $_MY_PAGE = 'student.';
    $_prefix =  str_replace("/","",$_route->getPrefix());
    $_name_page = str_replace($_MY_PAGE,'',$_route->getName());

    $_active = explode(".",$_name_page);
    $_active = count($_active)>1?$_active[1]:$_active[0];

    $_class_body = app()->view->getSections()['body_class'];
    $_class_main = isset(app()->view->getSections()['class_main']) ? app()->view->getSections()['class_main'] : 'p-content-admin';

    $_user =  Auth::guard('student')->check()  ? Auth::guard('student')->user() : null;

    $_noti_count = $_user->unreadNotifications()->where('via','broadcast')->count();
    $_contact_count = $_user->contact()->count();

    $_token_acsset = $_user->access_token;

@endphp

        <!DOCTYPE html>
<html lang="ja">
<head>
    @inject('seo','App\Services\SeoService')
    @include("partitions.{$_MY_PAGE}head")
    @yield("custom_css")
</head>

<body class="homepage navstate_show">
<!-- loading -->
@include("component.loading.index")
<!-- HEADER -->
@include("partitions.{$_MY_PAGE}header")
<!-- /END HEADER -->
<!-- MAIN BODY -->
<section class="main_body {{ $_class_body }}">
    <div id="layoutSidenav">
        <div class="btn_sidebar">
            <div class="btn_sidebar_e"><span></span><span></span><span></span></div>
        </div>
        @include("partitions.{$_MY_PAGE}sidebar")
        <div class="scroll-nonedefault" id="layoutSidenav_content">
            <main class="{{ $_class_main }}">

                <div class="container-fluid">
                    <section class="section1">

                        @if( (Lang::has("student.".$_name_page)) )
                            <h2 class="tit-main">
                                <span>{{ trans("student.".$_name_page) }}</span>
                                @yield('custom_title')
                            </h2>
                        @endif

                        @yield('content')
                    </section>
                </div>
            </main>
        </div>
    </div>
</section>
<!-- component buy point -->
@include("component.pop_up.ticket")
<!-- /END MAIN BODY -->
<footer class="footer">
    <div class="footer-info container"></div>
    <div class="backtop">
        <div class="backtop-icon"><a class="lh00" href="javascript:void(0)"><img
                        src="{{ asset('student/common_img/backtop.png') }}" alt="PAGE UP"></a></div>
    </div>
    <div class="footer-bottom">
        <p>©2022</p>
    </div>
</footer>
<!-- FOOTER -->

<!-- /END FOOTER -->
<!-- SCRIPT -->
<script>
    const _STRIPE_KEY = '{{ env('STRIPE_KEY') }}';

    let TOKEN_ACSSET = '{{ $_token_acsset }}';

    let SCOKET_PORT = '{{ env('SCOKECT_URL') }}';

    let URL_PAYMENT = @JSON( route('student.setting.payment') );

    let URL_ADD_PAYMENT = @JSON( route('student.setting.handle_payment_info') );

    let URL_DEFAULT_PAYMENT = @JSON( route('student.setting.update_default') );

    let URL_INFOR_PAYMENT = @JSON( route('student.setting.card_info') );

    let URL_UPDATE_PAYMENT = @JSON( route('student.setting.update_card_info') );

    let URL_BUY_POINT = @JSON( route('student.setting.Payment_intent') );

    var _tickets = null;
</script>
<script src="{{ asset('js/socket/handle.js') }}"></script>

<script src="{{ asset('student/js/bootstrap.bundle.min.js') }}" crossorigin="anonymous"></script>
<script src="{{ asset('student/js/scripts.js') }}"></script>
<script src="{{ asset('student/js/common.js') }}"></script>
<script src="{{ asset('student/js/datepicker/moment.min.js') }}"></script>
<!-- Structured data settings-->
@yield("custom_js")
<!-- /END SCRIPT -->
</body>

</html>