@inject('subject', 'App\Models\Subject')

@extends('layouts.student.index')

@section('body_class','homepage navstate_show chrome')
@section('class_main','main_body p-home')

@php
    $_user =  Auth::guard('student')->check()  ? Auth::guard('student')->user() : null;
    $subject = $subject::all();
@endphp

@section('custom_css')
    <script src="{{ asset('plugin/js/vue/vue.js') }}"></script>

    <link rel="stylesheet" type="text/css" href="{{ asset('student/css/slick/slick.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('student/css/slick/slick-theme.css') }}"/>
@endsection('custom_css')

@php
    $_teacher = session('teacher_infor');
@endphp

@section('content')

    <div class="container-fluid">
        @include('component.register.alert')

        <input type="file" name="path[]" data-index="0" data-chose="0" style="display:none" accept="image/*">
        <input type="file" name="path[]" data-index="1" data-chose="0" style="display:none" accept="image/*">
        <input type="file" name="path[]" data-index="2" data-chose="0" style="display:none" accept="image/*">
        <input type="file" name="path[]" data-index="3" data-chose="0" style="display:none" accept="image/*">
        <input type="file" name="path[]" data-index="4" data-chose="0" style="display:none" accept="image/*">

        <section class="section_request_detail">

            <a class="back-link fsc" href="{{ url()->previous() }}">
                <img src="{{ asset('student/images/back-icon.svg') }}"
                     alt="{{ trans("student.request_create")  }}"><span>{{ trans("student.request_create") }}</span>
            </a>

            <div class="box-shawdow">
                <h2 class="pp-request-tit">リクエスト内容</h2>

                <div class="block-users">
                    <figure class="img-circle"><img src="{{ $_teacher->avatar_img }}" alt="お名前太郎さん">
                    </figure>
                    <div class="users-info">
                        <h3 class="tit">{{ $_teacher->name }}</h3>
                        <p class="text">大学 : <span
                                    id="university">{{ $_teacher->university->university_name }}</span></p>
                        <div class="list-tag">
                            @foreach( $_teacher->subject as $_key => $_val )
                                <span class="tag tag-dark-green">{!! str_replace( '<br />', '', $_val->name) !!}</span>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="block-top">
                    <div class="item">

                        <ul class="listimg listimg-sp non-file" id="file_append"></ul>

                        <button class="btn btn-image btn-outline-primary " onclick="appendFile()">
                            <!-- btn-disable -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 26 26"
                                 fill="none">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                      d="M13 0C11.8954 0 11 0.89543 11 2V11H2C0.895431 11 0 11.8954 0 13C0 14.1046 0.89543 15 2 15H11V24C11 25.1046 11.8954 26 13 26C14.1046 26 15 25.1046 15 24V15H24C25.1046 15 26 14.1046 26 13C26 11.8954 25.1046 11 24 11H15V2C15 0.895431 14.1046 0 13 0Z"
                                      fill="#00A29A"></path>
                            </svg>
                            <span>画像を追加する（任意）</span>
                        </button>

                    </div>

                    <!-- form submit -->

                    <div class="item fiximgafter">

                        <form id="form">
                            <input class="form-control" type="hidden" name="user_receive_id"
                                   value="{{ $_teacher->id }}">
                            <dl>
                                <dt>タイトル</dt>
                                <dd>
                                    <input placeholder="{{ trans('common.placeholder.title') }}" class="form-control" type="text" name="title" value="{{ old('title') }}"
                                           maxlength="40" pattern="^\S+$" required>
                                </dd>
                            </dl>
                            <dl>
                                <dt>説明文</dt>
                                <dd>
                                    <textarea placeholder="{{ trans('common.placeholder.explanatory') }}" class="form-control" name="content" pattern="^\S+$"
                                              required>{{ old('content') }}</textarea>
                                </dd>
                            </dl>
                            <dl>
                                <dt>科目選択</dt>
                                <dd>
                                    <select class="form-control" name="subject_id" required data-embed="false">
                                        <option value="">-------------</option>
                                        @foreach($subject as $key => $_sub)
                                            <option value="{{ $_sub->id }}" {{ old('subject_id') && old('subject_id') == $_sub->id ? 'selected' : null }} >{!! $_sub->name !!}</option>
                                        @endforeach
                                    </select>
                                </dd>
                            </dl>
                            <dl>
                                <dt>おすすめの先生</dt>
                                <dd>
                                    <ul class="list-courses">
                                        <li>
                                            <input type="radio" name="sugges" value="1" checked>
                                            <div><span>表示</span></div>
                                        </li>
                                        <li>
                                            <input type="radio" name="sugges" value="0">
                                            <div><span>非表示</span></div>
                                        </li>
                                    </ul>
                                </dd>
                            </dl>
                        </form>
                    </div>
                </div>

                <div class="block-bottom">

                        <button class="btn btn-image btn-primary btn-pink" data-bs-toggle="modal"
                        onclick="show_poup(event)" >
                            <img src="{{ asset('student/images/icon-check.svg') }}" alt="チケットを消費してリクエストする"><span>チケットを消費してリクエストする</span>
                        </button>

                    @include('component.student.request.teacher_list')
                </div>
            </div>

        </section>

        <!-- <div class="modal fade popup-style popup-bottom-sp" id="cvcPopup" tabindex="-1" role="dialog" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <button class="close" data-bs-dismiss="modal" aria-hidden="true"></button>
              <div class="modal-body">
                <h2 class="cvc-title">CVCとは</h2>
                <p>CVCとは、セキュリティコードと呼ばれる番号のことです。クレジットカードやデビットカード裏面のサインパネルに記載されている数字の末尾3~4桁のこと指していて、本人確認をするためや不正利用を防ぐ役割があります。その他にも、インターネットショッピングでカード決済をする際に、カードが利用者自身お手元にあることを確認するために使われることが多いです。</p>
              </div>
            </div>
          </div>
        </div> -->
        <!-- modal- submit -->
        <div class="modal fade popup-style popup-style1 popup-bottom-sp" id="requestTicket" tabindex="-1" role="dialog"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <button class="close" data-bs-dismiss="modal" aria-hidden="true"></button>
                    <div class="modal-body">
                        <h2 class="pp-tit">確認</h2>
                        <p class="text-center">チケットを一枚消費して、<br>リクエストを完了します。</p>
                        <figure class="avatar-box"><img src="{{ asset('student/images/icon_request_001.svg') }}"
                                                        alt="お名前太郎さん"></figure>
                        <p class="number-ticket">チケット数<span class="point">{!! is_numeric( $_user->ticket ) ? $_user->ticket.'枚' : $_user->ticket  !!} </span></p>
                        @if( $_user->can('buyPoint', App\Model\User::class) )
                        <button class="btn btn-image btn-outline-primary btn-cart">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="23" viewBox="0 0 18 23"
                                 fill="none">
                                <g clip-path="url(#clip0_1095_6374)">
                                    <path d="M3.66381 6.13333C3.53001 4.24222 4.04611 2.60667 5.47337 1.33528C6.48647 0.434444 7.68434 0 9.03514 0C10.0737 0 11.0295 0.2875 11.9024 0.856111C12.7817 1.42472 13.4316 2.19139 13.8776 3.14333C14.3173 4.08889 14.4001 5.08556 14.3491 6.13333C14.8397 6.13333 15.2985 6.13333 15.7573 6.13333C16.3307 6.13333 16.6047 6.37611 16.662 6.95111C16.9169 9.50667 17.1718 12.0622 17.4266 14.6242C17.6178 16.5728 17.8281 18.515 18.0001 20.4636C18.1212 21.8564 17.0698 22.9936 15.6744 23C11.2334 23.0064 6.78593 23.0064 2.34487 23C0.955846 23 -0.101853 21.8564 0.0192087 20.47C0.153014 18.8728 0.331421 17.2756 0.484342 15.6783C0.63089 14.1833 0.783811 12.6947 0.930359 11.1997C1.07691 9.74944 1.21708 8.29278 1.36363 6.8425C1.40824 6.42722 1.71408 6.14611 2.13461 6.13972C2.62523 6.12694 3.12859 6.13333 3.66381 6.13333ZM15.1966 7.67944C11.0486 7.67944 6.93886 7.67944 2.81638 7.67944C2.81001 7.73694 2.79726 7.78806 2.79089 7.83278C2.62523 9.50028 2.46593 11.1678 2.30027 12.8353C2.0454 15.3972 1.79054 17.9656 1.53567 20.5275C1.4847 21.0706 1.82877 21.4667 2.37036 21.4667C6.79868 21.4667 11.2206 21.4667 15.6489 21.4667C16.1842 21.4667 16.5346 21.0578 16.4773 20.5275C16.439 20.1378 16.4008 19.7481 16.3626 19.3583C16.2097 17.8506 16.0631 16.3428 15.9102 14.835C15.7445 13.1547 15.5725 11.4681 15.4068 9.78778C15.3367 9.085 15.2666 8.38861 15.1966 7.67944ZM5.19301 6.11417C7.74169 6.11417 10.2776 6.11417 12.8072 6.11417C13.113 3.0475 10.8957 1.54611 9.03514 1.52694C7.20009 1.50139 4.88717 2.97083 5.19301 6.11417Z"
                                          fill="#00A29A"></path>
                                    <path d="M9.7805 10.0882C10.9465 10.5226 11.6601 11.4618 11.6729 12.5735C11.6793 13.0399 11.3734 13.3976 10.9401 13.4104C10.5069 13.4232 10.1755 13.0974 10.1501 12.631C10.1118 11.9665 9.61483 11.481 8.97767 11.4938C8.36599 11.5065 7.86262 12.0176 7.86262 12.6374C7.86262 13.2763 8.35961 13.7682 9.0159 13.7938C10.3539 13.8385 11.4244 14.7329 11.6347 15.9788C11.8577 17.2949 11.1886 18.4768 9.92705 18.9752C9.81873 19.0199 9.77413 19.0646 9.76776 19.1924C9.7359 19.6204 9.41094 19.9271 9.00315 19.9207C8.60811 19.9143 8.27678 19.614 8.2513 19.1988C8.24492 19.0454 8.17484 19.0071 8.05377 18.956C7.05342 18.579 6.35891 17.6015 6.33342 16.5538C6.32068 16.081 6.62015 15.7296 7.05342 15.704C7.49944 15.6785 7.83077 16.0043 7.86262 16.4963C7.90085 17.1671 8.4297 17.6527 9.07324 17.6207C9.69767 17.5888 10.1819 17.0457 10.1564 16.4196C10.1309 15.8126 9.64032 15.3463 9.00953 15.3271C8.01554 15.2951 7.23183 14.8735 6.70935 14.011C5.85555 12.5926 6.50546 10.7335 8.05377 10.1457C8.13023 10.1138 8.22581 10.0243 8.23855 9.95403C8.30227 9.48125 8.60174 9.18097 9.02864 9.19375C9.44917 9.20653 9.74227 9.51959 9.77413 9.99875C9.76775 10.0243 9.77413 10.0499 9.7805 10.0882Z"
                                          fill="#00A29A"></path>
                                </g>
                                <defs>
                                    <clipPath id="clip0_1095_6374">
                                        <rect width="18" height="23" fill="white"></rect>
                                    </clipPath>
                                </defs>
                            </svg><span onclick="$('#buyticket').modal('show');">購入する</span>
                        </button>
                        @endif
                        <div class="btn-ticket text_center">
                            <button onclick="sendRequest()"
                                    class="btn btn-primary btn-pink" {{ ! $_user->ticket ? 'disabled' : null }} >
                                リクエストを完了する
                            </button>
                            <button class="btn btn-primary btn-gray">閉じる</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection

@section('custom_js')

    <script src="{{ asset('student/js/slick.min.js') }}"></script>
    <script src="{{ asset('js/student/file_upload.js') }}"></script>

    <script>
        const _CHECK_PLAN = parseInt( "{{ $_user->can('viewStudent', App\Model\User::class) }}" );

        $('select[name="subject_id"]').select2({
            minimumResultsForSearch: Infinity,
            width: 'element',
            // allowClear: true ,
            placeholder: '選択してください。'
        });

        var subjects = <?= json_encode($subject) ?>;

        var URL_SUBMIT = @JSON( route('student.request.handle') );
        var URL_LOAD = @JSON( route('student.teacher.list') );

        var URL_OLD_TEACHER = @JSON( route('student.teacher.old_request') );

    </script>

    <script src="{{ asset('js/student/sugges_teacher.js') }}"></script>
@endsection
