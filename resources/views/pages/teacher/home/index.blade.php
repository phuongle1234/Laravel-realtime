@extends('layouts.teacher.index')

@section('body_class','p-home pdbottom teacher_list_detail')
@section('class_main','p-content-home pdbottom teacher_list_detail')

@php

  $_user =  Auth::guard('teacher')->check()  ? Auth::guard('teacher')->user() : null;

  $_sugges = $_user->suggestRequest()->get();

  $_nomination = $_user->nominationRequests();

  $_row_nomination = $_user->nominationRequests()->where(['status' => EStatus::PENDING,'is_displayed' => EStatus::IS_DISPLAYED])->with('subject')->orderBy('requests.created_at','DESC')->get();


@endphp

@section('header_container')
@include("component.teacher.alert_direct")
@endsection

@section('custom_title')
<img src="{{ asset('teacher/common_img/icon_title1.svg') }}" alt="プロフィール">
@endsection

@section('content')
<section class="section1">

    @if( !$_user->can('viewTeacher', App\Model\User::class) )
        @include('component.pop_up.not_activated')
    @endif

    @include('component.register.alert')
            <div class="block-profile block-profile-student">
                  <div class="item teacher-item">
                    <div class="fsc box-top">
                      <div class="ins">
                        <figure class="img-circle"><img src="{{ $_user->avatar_img }}" alt="{{ $_user->name }}"></figure>
                      </div>
                      <div class="ins">
                        <p class="teacher-name">
                          {{ $_user->name }}

                          @can('viewTeacher', App\Models\User::class)
                          <a href="{{ route('teacher.setting.profile') }}">
                            <img src="{{ asset('teacher/images/icon_pencil.svg') }}" alt="{{ $_user->name }}">
                          </a>
                          @endcan
                        </p>
                        <div class="rating"><img src="./common_img/icon_start.svg" alt="">

                         <span class="point">{{ $_user->rating }}</span>
                         <span class="number">{{ $_user->people_rating }}</span>

                        </div>
                        <p class="college">大学 : {{ $_user->university()->first()->university_name ?? "" }}</p>
                      </div>
                    </div>
                    <div class="fsc box-bottom">
                      <div class="ins">
                        <div class="status-online {{ $_user->online ? EStatus::ACTIVE : null }}"><span>{!! nl2br($_user->offline_text) !!}</span></div>
                      </div>
                      <div class="ins">
                        <div class="list-tag">
                              @foreach( $_user->subject()->get() as $_key => $_sub )
                                <span class="tag tag-dark-green">{!! $_sub->name !!}</span>
                              @endforeach
                          </div>
                      </div>
                    </div>
                  </div>
                  <div class="item">
                    <div class="itemins">
                      <div class="item-ins item-ins-fix red">
                        <div class="box-profiles">
                          <figure><img src="{{ asset('teacher/common_img/menu4.svg') }}" alt="動画投稿数"></figure>
                          <div class="text">
                            <h3>動画投稿数</h3>
                            <p>{{ $_user->videos()->count() }} 本</p>
                          </div>
                        </div>
                      </div>
                      <div class="item-ins item-ins-fix blue">
                        <div class="box-profiles">
                          <figure><img src="{{ asset('teacher/common_img/icon_like_w.svg') }}" alt="高評価数"></figure>
                          <div class="text">
                            <h3>高評価数</h3>
                            <p>{{  $_user->liked()->count() }}件</p>
                          </div>
                        </div>
                      </div>
                      <div class="item-ins item-ins-fix yll">
                        <div class="box-profiles">
                          <figure><img src="{{ asset('teacher/common_img/icon_start_w.svg') }}" alt="指名リクエスト数"></figure>
                          <div class="text">
                            <h3>指名リクエスト数</h3>
                            <p>{{ $_nomination->count() }}本</p>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="itemins itemins-bttom">
                      <div class="item-ins item-ins-fix green">
                        <div class="box-profiles">
                          <figure><img src="{{ asset('teacher/common_img/icon_user.png') }}" alt="自己紹介"></figure>
                          <div class="text">
                            <h3>自己紹介</h3>
                            <p>{!! nl2br($_user->introduction) !!}</p>
                          </div>
                        </div>
                      </div>
                      <div class="item-ins item-ins-fix purple">
                        <div class="box-profiles">
                            <figure><img src="{{ asset('images/blocktop_icon4.svg') }}" alt="利用ガイド"></figure>
                            <div class="text">
                                <h3>利用ガイド</h3>
                            </div>
                        </div>
                          <a download href="{{ asset('student/images/user_guide.pdf') }}">
                            <div class="btn btn-border purple">
                              <svg width="18" height="19" viewBox="0 0 18 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M16.6296 10.0413L15.9953 9.43851L16.6296 10.0413ZM8.52162 17.8108L7.88733 18.4135L8.52162 17.8108ZM9.24653 17.8108L8.61224 17.208L9.24653 17.8108ZM1.13854 10.0413L0.50425 10.644L1.13854 10.0413ZM5.83105 1.875H11.9377V0.125H5.83105V1.875ZM6.20605 8.69683V1.5H4.45605V8.69683H6.20605ZM1.501 10.0718H4.83105V8.32183H1.501V10.0718ZM9.15591 17.208L1.77283 9.43851L0.50425 10.644L7.88733 18.4135L9.15591 17.208ZM15.9953 9.43851L8.61224 17.208L9.88082 18.4135L17.2639 10.644L15.9953 9.43851ZM12.9377 10.0718H16.2672V8.32183H12.9377V10.0718ZM11.5627 1.5V8.69683H13.3127V1.5H11.5627ZM12.9377 8.32183C13.1448 8.32183 13.3127 8.48972 13.3127 8.69683H11.5627C11.5627 9.45622 12.1783 10.0718 12.9377 10.0718V8.32183ZM17.2639 10.644C18.0961 9.7682 17.4753 8.32183 16.2672 8.32183V10.0718C15.9377 10.0718 15.7683 9.67737 15.9953 9.43851L17.2639 10.644ZM7.88733 18.4135C8.4294 18.984 9.33875 18.984 9.88082 18.4135L8.61224 17.208C8.76007 17.0525 9.00807 17.0525 9.15591 17.208L7.88733 18.4135ZM1.501 8.32183C0.29284 8.32183 -0.327995 9.76819 0.50425 10.644L1.77283 9.43851C1.99981 9.67737 1.83049 10.0718 1.501 10.0718V8.32183ZM4.45605 8.69683C4.45605 8.48972 4.62395 8.32183 4.83105 8.32183V10.0718C5.59045 10.0718 6.20605 9.45622 6.20605 8.69683H4.45605ZM11.9377 1.875C11.7306 1.875 11.5627 1.70711 11.5627 1.5H13.3127C13.3127 0.740609 12.6971 0.125 11.9377 0.125V1.875ZM5.83105 0.125C5.07166 0.125 4.45605 0.740608 4.45605 1.5H6.20605C6.20605 1.70711 6.03816 1.875 5.83105 1.875V0.125Z" fill="#8795DE"/>
                                <defs>
                                  <clipPath id="clip0_1092_6245">
                                    <rect width="18" height="19" fill="white"></rect>
                                  </clipPath>
                                </defs>
                              </svg>
                              <span>ダウンロード</span>
                            </div>
                          </a>
                      </div>
                    </div>
                  </div>
                </div>
</section>

              <section class="section2">
                <h2 class="tit-border-bottom">先生トレーニング</h2>
                <div class="box-training">
                  <div class="box-training-wrapper">
                    <div class="video-box" id="video_box"></div>
                    <div class="process-box"><span class="process-tit">完了率</span>
                      <div class="process-wrapper">
                        <div class="single-chart">
                          <svg class="circular-chart orange" viewBox="0 0 36 36">
                            <defs>
                              <linearGradient id="paint0_linear_7_105" x1="0%" y1="0%" x2="100%" y2="0%" gradientUnits="userSpaceOnUse">
                                <stop offset="0%" stop-color="#4197E5"></stop>
                                <stop offset="100%" stop-color="#FB7878"></stop>
                              </linearGradient>
                            </defs>
                            <path class="circle-bg" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"></path>
                            <path id="percent_user" class="circle" stroke-dasharray="{{ $_user->percent }},100" stroke="url(#paint0_linear_7_105)" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"></path>
                          </svg>
                          <div class="percentage" id="percent_text" >{{ $_user->percent }}%</div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </section>
@if( $_user->can('viewTeacher', App\Model\User::class) )
      @if( ! empty($_row_nomination->toArray()) )
              <section class="section3" id="warning-block" >
                <div class="vl-tit-block">
                  <h2 class="tit-main"  id="direct"><span>あなたへの指名リクエスト</span></h2>
                  <div class="list-tag">
                    @foreach( $_row_nomination as $_key_sub => $_row_subject )
                      <span class="tag tag-dark-green">{!! $_row_subject->subject->name !!}</span>
                    @endforeach
                  </div>
                </div>

                <div class="list-teacher list-request" >
                @foreach($_row_nomination as $_key => $_row)

                  <div class="request-item item">

                  @if($_row->is_urgent)
                    <div class="status-order is-urgent"><img src="{{ asset('teacher/common_img/urgent.svg') }}" alt="緊急"><span>緊急</span></div>
                  @endif

                      <ul class="listimgs">
                          @foreach($_row->image as $_key_img => $_img)
                          <li><img src="{{ $_img }}" alt=""></li>
                          @endforeach
                        </ul>
                    <h3 class="request-tit">{{ $_row->title }}</h3>
                    <p class="answer-deadline">リクエスト受注期限 : {{ $_row->expires_at }}</p>
                    <ul class="list-courses">
                      <li>
                        <div><img src="{{ $_row->subject->icon }}" alt="数学Ⅰ"><span>{!! $_row->subject->name !!}</span></div>
                      </li>
                    </ul>
                    <input type="hidden" name="content" value="<?= nl2br($_row->content) ?>">
                    <input type="hidden" name="is_direct" value="{{ $_row->is_direct }}">
                    <input type="hidden" name="id" value="{{ $_row->id }}">
                    <button class="btn btn-primary" onclick="review_request( $(this).parent() )">内容を確認する</button>
                  </div>
                @endforeach
                </div>

              </section>
        @endif

        @if( ! empty( $_sugges->toArray() )  )
              <section class="section3">
                <div class="vl-tit-block">
                  <h2 class="tit-main"><span>新着リクエスト</span><img src="{{ asset('teacher/images/icon_home1.svg') }}" alt="新着リクエスト"></h2>
                  <div class="list-tag">
                  @foreach( $_sugges as $_key_sub => $_row_sub )
                    <span class="tag tag-dark-green">{!! $_row_sub->subject->name !!}</span>
                  @endforeach
                  </div>
                </div>

                <div class="list-teacher list-request">
                  @foreach( $_sugges as $_key => $_row )

                      <div class="request-item item">

                      @if($_row->is_urgent)
                        <div class="status-order is-urgent"><img src="{{ asset('teacher/common_img/urgent.svg') }}" alt="緊急"><span>緊急</span></div>
                      @endif

                        <ul class="listimgs">
                          @foreach($_row->image as $_key_img => $_img)
                          <li><img src="{{ $_img }}" alt=""></li>
                          @endforeach
                        </ul>
                        <h3 class="request-tit">{{ $_row->title }}</h3>
                        <p class="answer-deadline">{{ $_row->expires_at }}</p>
                        <ul class="list-courses">
                          <li>
                            <div><img src="{{ $_row->subject->icon }}" alt="{{ $_row->subject->name }}"><span>{!! $_row->subject->name !!}</span></div>
                          </li>
                        </ul>
                        <input type="hidden" name="content" value="<?= nl2br($_row->content) ?>">
                          <input type="hidden" name="is_direct" value="{{ $_row->is_direct }}">
                        <input type="hidden" name="id" value="{{ $_row->id }}">
                        <button  onclick="review_request( $(this).parent() )" class="btn btn-primary" >内容を確認する</button>
                      </div>
                  @endforeach
                </div>

              </section>
        @endif

              <section class="section4">
                <ul class="list-links">
                  <li><a href="{{route('teacher.setting.user_guide')}}">ご利用ガイド</a></li>
                  <li><a href="{{route('teacher.setting.faq')}}">よくある質問</a></li>
                  <li><a href="{{route('teacher.setting.privacy_policy')}}">プライバシーポリシー</a></li>
                  <li><a href="{{route('teacher.setting.law')}}">特定商取引法に基づく表記</a></li>
                </ul>
              </section>

              <div class="modal fade requestPopup requestSmPopup popup-style2 popup-bottom-sp" id="requestTextPp" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <button class="close" data-bs-dismiss="modal" aria-hidden="true"></button>
                    <div class="modal-body">

                        <h2 class="pp-request-tit">リクエスト内容確認</h2>
                        <div class="block-content">
                        <div class="img">
                          <ul class="listimgs"></ul>
                        </div>
                          <div class="content">
                            <h3 class="tit">見出しが入ります見出しが入ります</h3>
                            <ul class="list-courses">
                              <li>
                                <div><img src="./images/request_list/icon_1.png" alt="数学Ⅰ"><span>数学Ⅰ</span></div>
                              </li>
                            </ul>
                            <p class="text">テキストが入りますテキストが入りますテキストが入りますテキストが入りますテキストが入りますテキストが入りますテキストが入りますテキストが入りますテキストが入りますテキストが入りますテキストが入りますテキストが入りますテキストが入りますテキストが入りますテキストが入ります</p>
                          </div>
                        </div>

                        <div class="btn-wrapper">
                          <input type="hidden" name="id" />
                          <input type="hidden" name="is_direct" />
                          <button onclick="submit_request(false)" class="btn btn-primary btn-dark" >受諾しない</button>
                          <button onclick="submit_request(true)" class="btn btn-primary">リクエストを受ける</button>
                        </div>

                    </div>
                  </div>
                </div>
              </div>



            </div>
@endif
@endsection

@section('custom_js')
<script>
  const _percent = {{ $_user->percent }};

  let URL_REQUEST = @JSON( route('teacher.request.handle') );

  let URL_UPDATE_PERCENT = @JSON( route('teacher.update_percent') );

  let VIDEO_TRAIN = "{{ env('VIDEO_TRAIN') }}";

  let URL_NUMBER_REQUEST_DIRECT = "{{ route('ajax.get_number_request_direct')  }}";

  const player = new Vimeo.Player('video_box', { id: VIDEO_TRAIN, width: 592, height: 358, playsinline: 0 });

  player.on('pause', (e) => {
     _percent_new = Math.round(e.percent*100);

     if(_percent_new>_percent){

        axios.post(URL_UPDATE_PERCENT,{ _method: 'PUT', percent: _percent_new, playsinline: false }).then( (output)=>{
            if(output.status == 200){
              $('#percent_user').attr('stroke-dasharray',`${_percent_new},100`);
              $('#percent_text').text(`${_percent_new}%`)
            }
        });

     }

  });




  socket.notification( async (e) => {

      if(e.request_id){

        await $('.listimgs').slick('destroy');

        _is_show = $('div.fsc.warning-block').is( ":visible" );
        _count = parseInt( $('span.count_direct').text() );

        $('#warning-block').remove();

        const _request = await axios.post( window.location.origin + '/getWarningBlock', { _method: 'PUT' } );

        $( _request.data ).insertAfter( $('section.section2') );



          // $('.listimgs').slick('reinit');
          innit();

          _number = await getNumberRequestDirect();

          $('div.fsc.warning-block').show();
          $('span.count_direct').text( _number.data );
      }

  });

  $(document).ready(function(){
        innit();
       // renderSlick();
   });

  innit = () => {
    $(".listimgs").slick({
          dots: false,
          infinite: false,
          centerMode: false,
          slidesToShow: 1,
          slidesToScroll: 1,
          autoplay: false,
          arrows: true,
        });
    $(".listitempop").slick({
          dots: true,
          infinite: false,
          centerMode: false,
          slidesToShow: 1,
          slidesToScroll: 1,
          autoplay: false,
          arrows: true,
        });
  }
  // async function renderSlick(){

  //     await $(".listimgs").slick('unslick');
  //     //$('.listimgs').removeClass("slick-initialized slick-slider");

  //     await $(".listimgs").slick({
  //             dots: false,
  //             infinite: true,
  //             centerMode: false,
  //             slidesToShow: 1,
  //             slidesToScroll: 1,
  //             autoplay: false,
  //             arrows: true,
  //           });

  // }

  async function review_request(f){


    //await $('#requestTextPp .listimgs_poup').empty();
    //await $('#requestTextPp .listimgs_poup').append( $(f).find('.listimgs .slick-slide:not(.slick-cloned) li') );
    await $('.listimgs').slick('destroy');


      $('#requestTextPp .tit').html( $(f).find('.request-tit').text() );
      $('#requestTextPp .list-courses').html( $(f).find('.list-courses').html() );


      $('#requestTextPp .listimgs').html( $(f).find('.listimgs').html() ); //`<li><img src="${ $(f).find('.listimgs img')[0].currentSrc }" alt=""></li>`

      $('#requestTextPp p.text').html(  $(f).find(':input[name=content]').val()  );
      $('#requestTextPp input[name=id]').val( $(f).find(':input[name=id]').val() );
      $('#requestTextPp input[name=is_direct]').val( $(f).find(':input[name=is_direct]').val() );

      $('#requestTextPp button.btn-dark').hide();

      if( $(f).find(':input[name=is_direct]').val() )
        $('#requestTextPp button.btn-dark').show();

      innit();
      await  setTimeout( () => {  $('#requestTextPp').modal('show');  }, 200);

      //$('#requestTextPp').modal('show');
  }

  function submit_request(flag){
    let _is_direct = $('#requestTextPp input[name=is_direct]').val();

    if(_is_direct != '1' && flag == 1){
        $('#requestTextPp').modal('hide');
    }
    let _id = $('#requestTextPp input[name=id]').val();
    let _satus = flag ? 'accept' : 'cancel';

    var form = $("<form>",{ method:'post', action:URL_REQUEST })
            .append($('<input>',{name:'_token',value:_token}))
            .append($('<input>',{type: 'hidden', name:'_method',value:'PUT' }))
            .append($('<input>',{name:'id',value:_id}))
            .append($('<input>',{name:'status',value:_satus}));
      form.appendTo('body').submit();
      form.remove();
  }

  function scrollDirect(){

      $("div.scroll-nonedefault").animate({ scrollTop: $("#warning-block").offset().top-100, },1500);
  }


  const getNumberRequestDirect = async () => ( await axios.post(URL_NUMBER_REQUEST_DIRECT,{ _method:"PUT", role: 'teacher'  }) );

  $('.btn_close').click(function (e) {
      $('#loaded').removeClass('showthis');
  });

</script>
@endsection
