@inject('subject', 'App\Models\Subject')

@extends('layouts.teacher.index')

@section('body_class','main_body p-accepted_request pdbottom')
@section('class_main','p-content-accepted_request pdbottom')
@section('custom_css')
<link href="{{ asset('js/slick/slick-lightbox.css') }}" rel="stylesheet"/>
<script src="{{ asset('js/slick/slick.min.js') }}"></script>
<script src="{{ asset('js/slick/slick-lightbox.min.js') }}"></script>
@endsection

<!-- ->whereNull('video_id') -->

@php
    $_user =  Auth::guard('teacher')->check()  ? Auth::guard('teacher')->user() : null;

    $_section_01 = $_user->orderRequest()->whereIn('status',[ EStatus::ACCEPTED,EStatus::DELAYED])->whereNull('video_id');
    $_section_02 = $_user->orderRequest()->where('status',EStatus::APPROVED);
    $_section_03 = $_user->orderRequest()->whereIn('status',[ EStatus::ACCEPTED, EStatus::DENIED,EStatus::DELAYED])->whereNotNull('video_id');
    $_section_04 = $_user->orderRequest()->where('status','complete');
@endphp

@section('content')

    <section>
        @include('component.register.alert')
    </section>

    @if( $_check_01 = $_section_01->count() )

        <section class="section_01">
            <h2 class="tit-border-bottom tit-main"><span>未回答のリクエスト</span><img
                        src="{{ asset('teacher/common_img/icon-check.svg') }}" alt="未回答のリクエスト"></h2>


                <div class="list-teacher list-request">
                    @foreach( $_section_01->get() as $_key => $_item )

                        <div class="request-item item">

                            <ul class="listimgs">
                                @foreach($_item->image as $_key => $_img)
                                    <li><img src="{{ $_img }}" alt=""></li>
                                @endforeach
                            </ul>

                            <h3 class="request-tit">{{ $_item->title }}</h3>
                            <p class="answer-deadline">{{ trans('common.deadline_upload_video') }} : {{ $_item->expires_at }}</p>
                            <ul class="list-courses">
                                <li>
                                    <div>
                                        <img src="{{ asset($_item->subject->icon) }}"><span>{!! $_item->subject->name !!}</span>
                                    </div>
                                </li>
                            </ul>

                            <a class="btn btn-image btn-pdf"
                            href="{{ route('teacher.request.accepted_request',['id' => $_item->id ]) }}">
                                <img src="{{ asset('teacher/common_img/icon_upload.svg') }}"
                                    alt="アップロードする"><span>アップロードする</span>
                            </a>

                            <button class="btn btn-primary" onclick="review( {{ $_item->id }} )">内容を確認する</button>
                        </div>
                    @endforeach
                </div>


        </section>

    @endif

    @if( $_check_02 = $_section_02->count() )
    <section class="section_02 padding-sec">
        <h2 class="tit-border-bottom tit-main"><span>承認待ちのリクエスト</span><img
                    src="{{ asset('teacher/common_img/icon-check.svg') }}" alt="承認待ちのリクエスト"></h2>
        <div class="list-teacher list-request">
            @foreach( $_section_02->get() as $_key => $_item )
                <div class="request-item item">
                    <div class="status-order"><img src="{{ asset('teacher/common_img/icon-time.svg') }}"
                                                   alt="承認待ち"><span>承認待ち</span></div>
                    <ul class="listimgs">
                        @foreach($_item->image as $_key => $_img)
                            <li><img src="{{ $_img }}" alt=""></li>
                        @endforeach
                    </ul>
                    <h3 class="request-tit">{{ $_item->title }}</h3>
                    <p class="answer-deadline">{{ trans('common.request_end_date') }} {{ $_item->updated_at->format('Y年m月d日 H:i') }}</p>
                    <ul class="list-courses">
                        <li>
                            <div>
                                <img src="{{ asset($_item->subject->icon) }}"><span>{!! $_item->subject->name !!}</span>
                            </div>
                        </li>
                    </ul>

                    <button class="btn btn-primary" onclick="review( {{ $_item->id }} )">内容を確認する</button>
                </div>
            @endforeach
        </div>
    </section>
    @endif
    <!-- request deined -->
    @if( $_check_03 = $_section_03->count() )
    <section class="section_03 padding-sec">
        <h2 class="tit-border-bottom tit-main"><span>再アップロード要求</span><img
                    src="{{ asset('teacher/common_img/icon-check.svg') }}" alt="承認待ちのリクエスト"></h2>
        <div class="list-teacher list-request">
            @foreach( $_section_03->get() as $_key => $_item )
                <div class="request-item item">
                    <!-- <div class="status-order"><img src="{{ asset('teacher/common_img/icon-time.svg') }}" alt="承認待ち"><span>承認待ち</span></div> -->
                    <ul class="listimgs">
                        @foreach($_item->image as $_key => $_img)
                            <li><img src="{{ $_img }}" alt=""></li>
                        @endforeach
                    </ul>
                    <h3 class="request-tit">{{ $_item->title }}</h3>
                    <p class="answer-deadline">{{ trans('common.deadline_upload_video') }} : {{ $_item->expires_at }}</p>
                    <ul class="list-courses">
                        <li>
                            <div>
                                <img src="{{ asset($_item->subject->icon) }}"><span>{!! $_item->subject->name !!}</span>
                            </div>
                        </li>
                    </ul>

                    <a class="btn btn-image btn-pdf" href="{{ route('teacher.request.edit',['id' => $_item->id ]) }}">
                        <img src="{{ asset('teacher/common_img/icon_upload.svg') }}"
                             alt="アップロードする"><span>アップロードする</span>
                    </a>

                    <button class="btn btn-primary" onclick="review( {{ $_item->id }} )">内容を確認する</button>

                </div>
            @endforeach
        </div>
    </section>
    @endif

    @if( $_check_04 = $_section_04->count() )
    <section class="section_04 padding-sec" id="complete">
        <h2 class="tit-border-bottom tit-main"><span>完了したリクエスト</span><img
                    src="{{ asset('teacher/common_img/icon-tick.svg') }}" alt="完了したリクエスト"></h2>
        <div class="list-teacher list-request">
            @foreach( $_section_04->get() as $_key => $_item )
                <div class="request-item item">
                    <ul class="listimgs">
                        @foreach($_item->image as $_key => $_img)
                            <li><img src="{{ $_img }}" alt=""></li>
                        @endforeach
                    </ul>
                    <h3 class="request-tit">{{ $_item->title }}</h3>
                    <p class="answer-deadline">{{ trans('common.request_end_date') }} {{ $_item->updated_at->format('Y年m月d日 H:i') }}</p>
                    <ul class="list-courses">
                        <li>
                            <div>
                                <img src="{{ asset($_item->subject->icon) }}"><span>{!! $_item->subject->name !!}</span>
                            </div>
                        </li>
                    </ul>

                    <button class="btn btn-primary" onclick="review( {{ $_item->id }} )">内容を確認する</button>

                </div>
            @endforeach
        </div>
    </section>
    @endif

    <div id="result_poup"></div>

    @if( !$_check_01 && !$_check_02 && !$_check_03 && !$_check_04  )
    <div class="empty" >
        <img src="{{ asset('teacher/common_img/Group292.svg') }}" alt="">
        <p>
            受注済みリクエストがありません。
        </p>
    </div>
    @endif

@endsection

@section('custom_js')
    <script>
        // startCheck();


        let _URL = @JSON( route('teacher.request.review') );

        $(document).ready(function () {
            $('.listimgs').each(function(){
                $(this).slick({
                    dots: false,
                    infinite: true,
                    centerMode: false,
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    autoplay: false,
                    arrows: true,
                });

                var sLightbox = $(this);
                sLightbox.slickLightbox({
                    src: 'src',
                    itemSelector: 'li img'
                });
            });
        });

        function review(id) {

            axios.post(_URL, {_method: 'PUT', id: id, status: 'review'}).then((response) => {
                if (response.status == 200) {

                    $('#result_poup').html(response.data);

                    $('#result_poup div.modal').modal('show');

                }
            })
        }

        // function startCheck() {

        //     _section_1 = $('section.section_01');
        //     _section_2 = $('section.section_02');
        //     _section_3 = $('section.section_03');
        //     _section_4 = $('section.section_04');

        //     if ($(_section_3).find('.list-teacher').html().trim() == '')
        //         $(_section_3).hide();

        //     if (
        //         ($(_section_1).find('.list-teacher').html().trim() == '') &&
        //         ($(_section_2).find('.list-teacher').html().trim() == '') &&
        //         ($(_section_3).find('.list-teacher').html().trim() == '') &&
        //         ($(_section_4).find('.list-teacher').html().trim() == '')
        //     ) {
        //         $(_section_1).hide();
        //         $(_section_2).hide();
        //         $(_section_3).hide();
        //         $(_section_4).hide();
        //         $('div.empty').show();
        //     }

        // }

    </script>
@endsection
