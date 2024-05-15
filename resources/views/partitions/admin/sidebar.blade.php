@inject('VideoService', 'App\Services\VideoService')
@inject('teacheraproved', 'App\Http\Controllers\Admin\TeacherController')
@inject('compensationRepo','App\Repositories\UserCompensationRepository')

@php
    $_video_aproved_count = $VideoService->countAproved();
    $_teacher_count = $teacheraproved->countApprove();
    $_icon = (object)[
                        'active' => asset('common_img/menu-icon-active.svg'),
                        'nonActive' => asset('common_img/menu-icon.svg')
                      ];
    $_noti = $compensationRepo->isShowNotification();
    $_noti_html = "";
    if($_noti) $_noti_html = "<span class='noti-number'>！</span>";
@endphp

<div id="layoutSidenav_nav">
    <div class="logoSide"><img src="{{ asset('common_img/logo.svg') }}" alt="edutoss"></div>
    <nav class="sb-sidenav accordion sb-sidenav-light" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
            <ul class="nav navbar-nav scroll-beauty">

                <li class="nav-item menu1 {{ $_prefix == 'admin_management' ? 'active' : null }}">
                    <a class="nav-link" href="{{ route('admin.adminManagement.list') }}"><img
                                src="{{ $_prefix == 'admin_management' ? $_icon->active : $_icon->nonActive }}">アドミン管理</a>
                </li>

                <li class="nav-item {{ $_prefix == 'student_management' ? 'active' : null }}">
                    <a class="nav-link" href="{{ route('admin.studentManagement.list') }}"><img
                                src="{{ $_prefix == 'student_management' ? $_icon->active : $_icon->nonActive }}">生徒管理</a>
                </li>

                <li class="nav-item {{ $_prefix == 'lecturer_management' ? 'active' : null }} dropdown">
                    <a class="nav-link dropdown-toggle" href="javascript:void(0)"><img
                                src="{{ $_prefix == 'lecturer_management' ? $_icon->active : $_icon->nonActive }}">講師管理
                        @if($_teacher_count)
                            <span class="noti-number">{{ $_teacher_count }}</span>
                        @endif
                </a>
                    <div class="menu-child dropdown-menu menu-child">
                        <a class="dropdown-item" href="{{ route('admin.teacherManagement.list') }}">・講師一覧</a>
                        <a class="dropdown-item"
                           href="{{ route('admin.teacherManagement.list_approve_status') }}">・申請一覧
                        @if($_teacher_count)
                            <span class="noti-number">{{ $_teacher_count }}</span>
                        @endif
                        </a>
                    </div>
                </li>

                <li class="nav-item {{ $_prefix == 'lecturer_cp_management' ? 'active' : null }} ">
                    <a class="nav-link" href="{{ route('admin.lecturerCPManagement.list') }}"><img
                                src="{{ asset('common_img/menu-icon.svg') }}">講師報酬管理</a></li>

                <li class="nav-item dropdown {{ $_prefix == 'video_management' ? 'active' : null }}">
                    <a class="nav-link dropdown-toggle" href="javascript:void(0)">
                        <img src="{{ asset('common_img/menu-icon.svg') }}">動画管理

                        @if($_video_aproved_count)
                            <span class="noti-number">{{ $_video_aproved_count }}</span>
                        @endif
                    </a>

                    <div class="dropdown-menu menu-child" aria-labelledby="navbarDropdownMenuLink">
                            <a class="dropdown-item" href="{{route('admin.video_management.list')}}">・動画一覧 </a>
                                <a class="dropdown-item" href="{{ route('admin.video_management.list_approval') }}">・承認待ち動画一覧
                                    @if($_video_aproved_count)
                                        <span class="noti-number">{{ $_video_aproved_count }}</span>
                                    @endif
                                </a>
                                <a class="dropdown-item" href="{{route('admin.video_management.list_views')}}">・再生回数一覧</a>
                    </div>

                 </li>

                <li class="nav-item {{ $_prefix == 'tag_management' ? 'active' : null }}">
                    <a class="nav-link" href="{{ route('admin.tagManagement.list') }}"><img
                                src="{{ $_prefix == 'tag_management' ? $_icon->active : $_icon->nonActive }}">タグ管理マスタ</a>
                </li>

                <li class="nav-item {{ $_prefix == 'request_management' ? 'active' : null }}"><a class="nav-link" href="{{route('admin.request_management.list')}}"><img
                                src="{{ asset('common_img/menu-icon.svg') }}">リクエスト管理</a>
                </li>
                <li class="nav-item dropdown {{ $_prefix == 'compensation_management' ? 'active' : null }}"><a class="nav-link dropdown-toggle" href="javascript:void(0)"><img
                                src="{{ asset('common_img/menu-icon.svg') }}">報酬管理{!! $_noti_html !!}
                    </a>
                    <div class="menu-child dropdown-menu menu-child"><a class="dropdown-item" href="{{route('admin.compensation_management.list')}}">・報酬請求管理{!! $_noti_html !!}</a><a
                                class="dropdown-item" href="{{route('admin.compensation_management.history')}}">・報酬支払履歴</a></div>
                </li>

                <li class="nav-item {{ $_prefix == 'notification_delivery' ? 'active' : null }}">
                    <a class="nav-link" href="{{ route('admin.notificationDelivery.list') }}">
                        <img src="{{ $_prefix == 'notification_delivery' ? $_icon->active : $_icon->nonActive }}">お知らせ配信
                    </a>
                </li>

                <li class="nav-item {{ $_prefix == 'notification_management' ? 'active' : null }}">
                    <a class="nav-link" href="{{ route('admin.notificationManagement.list') }}"><img
                                src="{{ $_prefix == 'notification_management' ? $_icon->active : $_icon->nonActive }}">お知らせのテンプレート管理</a>
                </li>

                <li class="nav-item {{ $_prefix == 'vimeo_management' ? 'active' : null }}">
                    <a class="nav-link" href="{{ route('admin.vimeo_management.list') }}"><img
                                src="{{ $_prefix == 'vimeo_management' ? $_icon->active : $_icon->nonActive }}">Vimeoアカウント管理</a>
                </li>
            </ul>
        </div>
    </nav>

    <a class="btn btn-outline-secondary btn-logout" href="{{ route('logout',[ "role" => 'admin' ]) }}">ログアウト</a>

</div>