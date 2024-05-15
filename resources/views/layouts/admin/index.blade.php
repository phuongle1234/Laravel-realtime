@php

  $_route = request()->route();

  $_prefix =  str_replace("admin/","",$_route->getPrefix());
  $_name_page = $_route->getName();
  $_name_page = str_replace('admin.','',$_name_page);

  $_name_page_detail = str_replace('.','-',$_name_page);

  $_active = explode(".",$_name_page);
  $_active = count($_active)>1?$_active[1]:$_active[0];

  $_class_body = app()->view->getSections()['body_class'];

  $_user =  auth()->guard('admin')->check()  ? auth()->guard('admin')->user() : null;

  if(!empty($_user))
    $_noti_count = $_user->unreadNotifications()->where('via','broadcast')->count();

  $_class_main = isset(app()->view->getSections()['class_main']) ? app()->view->getSections()['class_main'] : 'p-content-admin';
@endphp

<!DOCTYPE html>
<html lang="ja">
  <head>
    @include("partitions.admin.head")
    @yield("custom_css")
  </head>
  <body class="secondpage navstate_show page-admin">
    <!-- HEADER -->
    <!-- /END HEADER -->
    <!-- MAIN TITLE PAGE -->
    <div class="mainmv _sechead _sechead-admin">
      <div class="container flexbox">
        <h1 class="fontmin _sechead-title"></h1>
      </div>
    </div>
    <!-- /END MAIN TITLE PAGE -->
    <!-- MAIN BODY -->
<section class="main_body @yield('body_class')">

      <div id="layoutSidenav">
        <div class="btn_sidebar">

              <div class="btn_sidebar_e">
                  <span></span>
                  <span> </span>
                  <span></span>
              </div>
        </div>

    @if( $_class_body != "p-login" )
      @include("partitions.admin.sidebar")
      <div id="layoutSidenav_content">
          <div class="blockheader">
            @include("component.erorr.index")
            @include('partitions.admin.header')
          </div>
          <main class="{{ $_class_main }}">

              @include("component.pop_up.comfrim_delete")
              @include("component.pop_up.notification")
            <div class="container-fluid">

                      <!-- <div class="container-fluid"> -->

                        @if( (Lang::has("common.".$_name_page)) )
                          <h2 class="tit-page">
                              {{ trans("common.".$_name_page) }}

                              @yield('custom_title')
                          </h2>
                        @endif

                        <!-- $_name_page_detail -->
                        @if( (Lang::has("common.".$_name_page_detail)) )
                        <h2 class="tit-page">
                          <a href="{{ url()->previous() }}"> <img src="{{ asset('../images/back-icon.svg') }}" alt="iconback"></a>
                          {{ trans("common.".$_name_page_detail) }}

                            @yield('custom_title')
                        </h2>
                        @endif

                      @yield('content')
                <!-- </div> -->
            </div>
          </main>
        </div>
      </div>
    @else
      <div id="layoutSidenav_content">
        @yield('content')
      </div>
    @endif

    </section>
    <!-- /END MAIN BODY -->
    <!-- FOOTER -->
    <!-- /END FOOTER -->
    <!-- SCRIPT -->
    <!-- Declare variable for js -->
    <script>
      let _confirm_delete = '{{ trans("common.confirm.delete") }}' ;
      let SCOKET_PORT = '{{ env("SCOKECT_URL") }}';
    </script>
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}" crossorigin="anonymous"></script>
    <script src="{{ asset('js/scripts.js') }}"></script>
    <script src="{{ asset('js/common.js') }}"></script>

    <script src="{{ asset('js/socket/handle_admin.js') }}"></script>

    <script>
      $(document).ready(function(){
        setTimeout(() => {
          $('.alert-wrapper').fadeOut();
        }, 2000)
      });
    </script>
    <!-- Structured data settings-->
    @yield("custom_js")
    <!-- /END SCRIPT -->
  </body>
</html>