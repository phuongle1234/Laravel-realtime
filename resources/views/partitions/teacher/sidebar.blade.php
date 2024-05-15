<div id="layoutSidenav_nav">
          <nav class="sb-sidenav accordion sb-sidenav-light" id="sidenavAccordion">
            <div class="sb-sidenav-menu">
              <ul class="nav navbar-nav scroll-beauty">
                <li class="text_center menu1 {{ $_name_page == 'home' ? 'active' : null }}"><a class="btn btn-img home-btn" href="{{ route("{$_MY_PAGE}home") }}"><span>HOME</span></a></li>

              @if( $_user->can('viewTeacher', App\Model\User::class) )

                <li class="nav-item dissp active menu1"><a class="nav-link info-icon" href="{{ route('teacher.notification.index') }}"><img src="{{ asset('teacher/common_img/menu1.svg') }}">お知らせ<span class="numinfo {{ $_noti_count ? 'show' : null }}">{{ $_noti_count }}</span></a></li>
                <li class="nav-item dissp"><a class="nav-link info-icon" href="{{ route('teacher.message') }}"><img src="{{ asset('teacher/common_img/menu2.svg') }}">メッセージ<span class="numinfo {{ $_contact_count ? 'show' : null }}">{{ $_contact_count }}</span></a></li>
                <li class="nav-item {{ $_name_page == 'request.list' ? 'active' : null }}"><a class="nav-link" href="{{ route('teacher.request.list') }}"><img src="{{ asset('teacher/common_img/menu3.svg') }}">リクエスト一覧</a></li>
                <li class="nav-item {{ $_name_page == 'request.accepted' ? 'active' : null }}"><a class="nav-link" href="{{ route('teacher.request.accepted') }}"><img src="{{ asset('teacher/common_img/menu4.svg') }}">受注済みリクエスト</a></li>

                <li class="nav-item {{ $_name_page == 'video.list' ? 'active' : null }} "><a class="nav-link" href="{{ route('teacher.video.list') }}"><img src="{{ asset('teacher/common_img/menu5.svg') }}">動画管理</a></li>

                <li class="nav-item menu-down"><a class="nav-link" href="javascript:void(0)"><img src="{{ asset('teacher/common_img/menu6.svg') }}">報酬管理</a>
                  <ul class="menu-child">
                    <li><a class="menu-child-item" href="{{ route('teacher.reward.index') }}">報酬リクエスト</a></li>
                    <li><a class="menu-child-item" href="{{ route('teacher.reward.history',['id' => auth()->guard('teacher')->id()]) }}">報酬履歴</a></li>
                  </ul>
                </li>
                <li class="nav-item"><a class="nav-link" href="{{ route("{$_MY_PAGE}setting.index") }} "><img src="{{ asset('teacher/common_img/menu8.svg') }}">各種設定</a></li>

              @endif
                <li class="nav-item"><a class="nav-link" href="{{ route('logout',[ "role" => 'teacher' ]) }}"><img src="{{ asset('teacher/common_img/menu9.svg') }}">ログアウト</a></li>
              </ul>
            </div>
          </nav>
        </div>