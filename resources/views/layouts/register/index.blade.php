<!DOCTYPE html>
<html lang="ja">
  <head>
        @inject('seo','App\Services\SeoService')
        @include('partitions.register.head')
        @yield("custom_css")
        <style>
            #agentRegister select{
              height: 25px;
            }

            #loading div.modal-content {
                border: 2px solid #8FC31F;
                justify-content: center;
                align-items: center;
                flex-direction: column;
                position: absolute;
                display: flex;
                overflow: hidden;
                width: 350px !important;
                height: 350px;
                min-width: 350px !important;
                padding: 0px;
                border-radius: 100%;
                top: 0;
                bottom: 0;
                left: 0;
                right: 0;
                margin: auto;
            }

          #loading {
              display: none;
              position: fixed;
              z-index: 9999;
              padding-top: 100px;
              left: 0;
              top: 0;
              width: 100%;
              height: 100%;
              overflow: auto;

              background-color: rgb(0 0 0 / 68%);
          }


          .rotate {
            animation: rotation 3s infinite linear;
          }

          @keyframes rotation {
            from {
              transform: rotate(0deg);
            }
            to {
              transform: rotate(359deg);
            }
          }

</style>
  </head>
  <body class="secondpage navstate_show page-login @yield('class_body')">
      <!-- loading -->
      <div id="loading" class="modal" >
                  <!-- Modal content -->
            <div class="modal-content" >
                <img class="rotate" id="loading_img" src="{{ asset("register/images/login/logo_login_h.svg") }}">
            </div>
        </div>

    <!-- HEADER -->
    <!-- /END HEADER -->
    <!-- MAIN BODY -->
    <section class="main_body p-login @yield('class_section')">
      <div id="layoutSidenav"><div class="btn_sidebar">
<div class="btn_sidebar_e"><span></span><span></span><span></span></div>
</div>
        <div id="layoutSidenav_content">

          <main class="p-content-login @yield('class_main')">
            <div class="container-fluid">
              <h2 class="tit-login">
                <a> <img src="{{ asset('register/images/login/logo_login_h.svg') }}" alt="edutoss"></a>
                @yield('custom_title')
                <!-- <a class="btn btn-lightgreen" href="../">新規会員登録</a> -->
              </h2>
                @yield('content')
              </div>

            </div>
          </main>
        </div>
      </div>
    </section>
    <!-- /END MAIN BODY -->
    <!-- FOOTER -->
    <!-- /END FOOTER -->
    <!-- SCRIPT -->
    <script src="{{ asset('register/js/bootstrap.bundle.min.js') }}" crossorigin="anonymous"></script>
    <script src="{{ asset('register/js/scripts.js') }}"></script>
    <script src="{{ asset('register/js/common.js') }}"></script>
    <!-- Structured data settings-->
    @yield("custom_js")
    <!-- /END SCRIPT -->
  </body>
</html>