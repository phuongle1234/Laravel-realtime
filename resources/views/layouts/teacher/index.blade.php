@php

  $_route = request()->route();
  $_MY_PAGE = 'teacher.';

  $_prefix =  str_replace("/","",$_route->getPrefix());
  $_name_page = str_replace($_MY_PAGE,'',$_route->getName());

  $_active = explode(".",$_name_page);
  $_active = count($_active)>1?$_active[1]:$_active[0];

  $_class_body = app()->view->getSections()['body_class'];
  $_class_main = isset(app()->view->getSections()['class_main']) ? app()->view->getSections()['class_main'] : 'p-content-admin';

  $_user =  Auth::guard('teacher')->check()  ? Auth::guard('teacher')->user() : null;

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
    <!-- HEADER -->
    @include("component.loading.progress")

    @include("partitions.{$_MY_PAGE}header")
    <!-- /END HEADER -->

    <!-- MAIN BODY -->
    <section class="main_body {{ $_class_body }}">

      <div id="layoutSidenav"><div class="btn_sidebar">
        <div class="btn_sidebar_e"><span></span><span></span><span></span></div>
      </div>

        @include("partitions.{$_MY_PAGE}sidebar")

        <div class="scroll-nonedefault" id="layoutSidenav_content">
          <main class="{{ $_class_main }}">
            <div class="container-fluid">

                <!-- include('component.register.alert') -->

                @yield('header_container')

                @if( (Lang::has("teacher.".$_name_page)) )
                      <h2 class="tit-main"><span>{{ trans("teacher.".$_name_page) }}</span>  @yield('custom_title') </h2>
                @endif

                @yield('content')
              </div>
          </main>
        </div>
      </div>
    </section>
    <!-- /END MAIN BODY -->
    <!-- FOOTER -->
    <!-- /END FOOTER -->
    <!-- SCRIPT -->

    <script>
        let SCOKET_PORT = '{{ env('SCOKECT_URL') }}';
    </script>

    <script src="{{ asset('js/socket/handle_teacher.js') }}"></script>
    <script src="{{ asset('teacher/js/bootstrap.bundle.min.js') }}" crossorigin="anonymous"></script>
    <script src="{{ asset('teacher/js/scripts.js') }}"></script>
    <script src="{{ asset('teacher/js/common.js') }}"></script>
    <script src="{{ asset('teacher/js/slick.min.js') }}"></script>
  <!-- Structured data settings-->
       @yield("custom_js")
  <!-- /END SCRIPT -->

    <!-- /END SCRIPT -->
  </body>
</html>