<div id="layoutSidenav_nav">
  <nav class="sb-sidenav accordion sb-sidenav-light" id="sidenavAccordion">
    <div class="sb-sidenav-menu">
      <ul class="nav navbar-nav scroll-beauty">
        <li class="text_center menu1 {{ $_active == 'home' ? 'active' : null }}">
          <a class="btn btn-img home-btn" href=' {{ route("{$_MY_PAGE}home") }} '><span>HOME</span></a>
        </li>

        <li class="nav-item dissp"><a class="nav-link info-icon" href="{{ route('student.notification.index') }}"><img src="{{ asset('student/common_img/menu7.svg') }}">お知らせ<span class="count_noti numinfo {{ $_noti_count ? 'show' : null }}">{{ $_noti_count }}</span></a></li>
        <li class="nav-item dissp"><a class="nav-link info-icon" href="{{ route('student.message') }}"><img src="{{ asset('student/common_img/menu8.svg') }}">メッセージ<span class="count_message numinfo {{ $_contact_count ? 'show' : null }}">{{ $_contact_count }}</span></a></li>

        <li class="nav-item {{ $_name_page == 'teacher.index' ? 'active' : null }}"><a class="nav-link" href="{{ route("{$_MY_PAGE}teacher.index") }}"><img src="{{ asset('student/common_img/menu2.svg') }}">先生一覧</a></li>
        <li class="nav-item {{ $_name_page == 'request.list' ? 'active' : null }} "><a class="nav-link" href=" {{ route("{$_MY_PAGE}request.list") }} "><img src="{{ asset('student/common_img/menu3.svg') }}">あなたのリクエスト</a></li>
        <li class="nav-item {{ $_name_page == 'video.list' ? 'active' : null }} "><a class="nav-link" href="{{ route("{$_MY_PAGE}video.list") }}">
                  <img src="{{ asset('student/common_img/menu4.svg') }}">動画一覧</a>
        </li>

        @if( $_user->can('viewStudent', App\Model\User::class) )
          <li class="nav-item {{ $_name_page == 'study.index' ? 'active' : null }}"><a class="nav-link" href="{{ route("{$_MY_PAGE}study.index") }}"><img src="{{ asset('student/common_img/menu5.svg') }}">学習管理</a></li>
        @endif

        <li class="nav-item"><a class="nav-link" href="{{ route("{$_MY_PAGE}setting.index") }} "><img src="{{ asset('student/common_img/menu6.svg') }}">各種設定</a></li>

        <li class="nav-item"><a class="nav-link " href="{{ route('logout',[ "role" => 'student' ]) }}"><img src="{{ asset('common_img/menu9.svg') }}">ログアウト</a></li>
      </ul>
    </div>
  </nav>
</div>