@extends('layouts.student.index')

@section('body_class','homepage navstate_show chrome')
@section('class_main','main_body p-your_request pdbottom')

@php
  $_user =  Auth::guard('student')->check()  ? Auth::guard('student')->user() : null;
@endphp

@section('custom_title')
  <link rel="stylesheet" href="{{ asset('student/css/slick/slick.css') }}">
  <link rel="stylesheet" href="{{ asset('student/css/slick/slick-theme.css') }}">
  <link href="{{ asset('js/slick/slick-lightbox.css') }}" rel="stylesheet"/>
  <script src="{{ asset('js/slick/slick.min.js') }}"></script>
  <script src="{{ asset('js/slick/slick-lightbox.min.js') }}"></script>
  <link rel="stylesheet" href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css">
  <style>
    .rateGroup{
      display: flex;
      justify-content: center;
      align-items: center;
    }
    .rate {
        display: inline-block;
        border: 0;
    }
    /* Hide radio */
    .rate > input {
        display: none;
    }
    /* Order correctly by floating highest to the right */
    .rate > label {
        float: right;
    }
    /* The star of the show */
    .rate > label:before {
        display: inline-block;
        font-size: 2rem;
        padding: .3rem .2rem;
        margin: 0;
        cursor: pointer;
        font-family: FontAwesome;
        content: "\f005 "; /* full star */
    }

    /* Half star trick */
    .rate .half:before {
        content: "\f089 "; /* half star no outline */
        position: absolute;
        padding-right: 0;
    }
    /* Click + hover color */
    .rate input:checked ~ label, /* color current and previous stars on checked */
    .rate label:hover, label:hover ~ label { color: #73B100;  } /* color previous stars on hover */

    /* Hover highlights */
    .rate input:checked + label:hover, input:checked ~ label:hover, /* highlight current and previous stars */
    .rate input:checked ~ label:hover ~ label, /* highlight previous selected stars for new rating */
    .rate label:hover ~ input:checked ~ label /* highlight previous selected stars */ { color: #A6E72D;  }

    /* div.request-item ul {
      max-height: 300px;
      overflow: hidden;
    }
    div.request-item ul li {
      width: 352px
    } */
  </style>
@endsection

<!-- section('custom_title')
<img src="{{ asset('student/images/your_request/icon_001.svg') }}" alt="依頼中のリクエスト">
endsection -->

@section('content')
<h2 class="tit-main"><span>{{ trans('student.request-pending') }}</span><img src="{{ asset('student/images/your_request/icon_001.svg') }}" alt="依頼中のリクエスト"></h2>
<div class="list-teacher list-request">
    @foreach( $_user->list_request  as $_key => $_row)

        <div class="request-item item">

          @if($_row->status != EStatus::PENDING)
            <div class="status-order">
              <img src="{{ asset('student/images/your_request/icon_check.svg') }}" alt="受注済み">
              <span>受注済み</span></div>
          @endif

          <ul class="listimgs">
            @foreach($_row->image as $key => $_row_img)
              <li><img src="{{ $_row_img }}" alt=""></li>
            @endforeach
          </ul>

          <h3 class="request-tit">{{ $_row->title }}</h3>

          <p class="answer-deadline">回答期限：{{ $_row->expires_at }}</p>

          <ul class="list-courses">
            <li>
              <div><img src="{{ asset($_row->subject->icon) }}" alt="数学Ⅰ"><span>{!! $_row->subject->name !!}</span></div>
            </li>
          </ul>

          @php
            $_button =  (object) $_row->class_status;
          @endphp

          <button onclick="reviewRequest({{ $_row->id }})" class="btn btn-primary {{ $_button->class }}" >{{ $_button->text }}</button>
        </div>
    @endforeach

</div>
        <br><br>
          <!-- section -->
          @if( ! empty( $_user->list_request_complte[0] ) )
              <section class="section_02">
                <h2 class="tit-main"><span>{{ trans('student.request-complete') }}</span><img src="{{ asset('student/images/your_request/icon_002.svg') }}" alt="過去のリクエスト"></h2>
                <div class="list-teacher list-request">

                      @foreach( $_user->list_request_complte  as $_key => $_row)
                            <div class="request-item item">

                              <ul class="listimgs">
                                  @foreach($_row->image as $key => $_row_img)
                                    <li><img src="{{ $_row_img }}" alt=""></li>
                                  @endforeach
                              </ul>

                              <h3 class="request-tit">{{ $_row->title }}</h3>

                              <p class="answer-deadline">回答期限：{{ $_row->end_at }}</p>

                              <ul class="list-courses">
                                <li>
                                  <div><img src="{{ asset($_row->subject->icon) }}" alt="数学Ⅰ"><span>{!! $_row->subject->name !!}</span></div>
                                </li>
                              </ul>

                              @php
                                $_button =  (object) $_row->class_status;
                              @endphp

                              <button onclick="reviewRequest({{ $_row->id }})" class="btn btn-primary {{ $_button->class }}" >{{ $_button->text }}</button>
                            </div>
                      @endforeach

                </div>
              </section>
            @endif

              <a class="btn-request" href="{{ route('student.request.create') }}">
                <img src="{{ asset('student/common_img/request-icon.svg') }}" alt="">
              </a>

         <div id="result_poup"></div>

         <div class="modal fade popup-style popup-style1 popup-bottom-sp" id="requestRating" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog" role="document">
                <form action="{{ route('student.request.confirm') }}" method="post">
                          @csrf
                          @method('put')
                    <div class="modal-content">
                      <span class="close" data-bs-dismiss="modal" aria-hidden="true"></span>
                        <div class="modal-body">
                          <h2 class="pp-tit">講師の評価</h2>
                          <p class="text-center">今回の講師はいかがでしたか。</p>
                          <figure class="avatar-box"><img src="../common_img/avatar.png" alt="お名前太郎さん"></figure>
                          <figcaption>お名前太郎さん</figcaption>
                          <div class="rateGroup">
                            <ul class="rate">
                              <input type="radio" id="rating10" name="rating" value="10" /><label for="rating10" title="5 stars"></label>
                              <input type="radio" id="rating9" name="rating" value="9" /><label class="half" for="rating9" title="4 1/2 stars"></label>
                              <input type="radio" id="rating8" name="rating" value="8" /><label for="rating8" title="4 stars"></label>
                              <input type="radio" id="rating7" name="rating" value="7" /><label class="half" for="rating7" title="3 1/2 stars"></label>
                              <input type="radio" id="rating6" name="rating" value="6" /><label for="rating6" title="3 stars"></label>
                              <input type="radio" id="rating5" name="rating" value="5" /><label class="half" for="rating5" title="2 1/2 stars"></label>
                              <input type="radio" id="rating4" name="rating" value="4" /><label for="rating4" title="2 stars"></label>
                              <input type="radio" id="rating3" name="rating" value="3" /><label class="half" for="rating3" title="1 1/2 stars"></label>
                              <input type="radio" id="rating2" name="rating" value="2" /><label for="rating2" title="1 star"></label>
                              <input type="radio" id="rating1" name="rating" value="1" /><label class="half" for="rating1" title="1/2 star"></label>
                            </ul>
                          </div>

                          <div class="text_center">
                              <input type="hidden" name="request_id">
                              <button type="submit" class="btn btn-primary">リクエストを完了する</button>
                          </div>

                        </div>
                    </div>
                  </form>
                </div>
            </div>

@endsection

@section('custom_js')
<script src="{{ asset('student/js/slick.min.js') }}"></script>
<script>

    $(document).ready(function(){
        innit();
    });

    const innit = () => {
        $(".listimgs").slick({
              dots: false,
              infinite: false,
              centerMode: false,
              slidesToShow: 1,
              slidesToScroll: 1,
              autoplay: false,
              arrows: true,
          });
        $(".listimgs").slickLightbox({
            src: 'src',
            itemSelector: 'li img'
        });
    }

  // () => {
  //     $(".listimgs").slick({
  //         dots: false,
  //         infinite: true,
  //         centerMode: false,
  //         slidesToShow: 1,
  //         slidesToScroll: 1,
  //         autoplay: false,
  //         arrows: true,
  //       });
  // }
    // $('#requestRating ul li').click( (e)=>{
    //     console.log( e.target );
    // })

let URI = @JSON(route('student.request.review'));

// axios.post(URI_CHAT,{
//                   user_to: 1,
//                   user_from: 10,
//                   message_id: 1,
//                   message: 'phuhong _ test abc 456',
//                   path: null,
//                   _token:_token
//     }).then(function(output){
//             console.log(output);
//     });

function rating(f){
    _class = $(f).attr('class');

    if(_class == 'active')
      $(f).removeClass('active');
    else
      $(f).addClass('active');

    _count = $('#requestRating ul.rating-list li.active').length;

    $('#requestRating input[name=rating]').val(_count);
}

function requestConfirmPp(f){

    _div = $(f).parent();
    _div_content = $(_div).parent().parent();

    _id = $(_div).find(':input[name=id_receive]').val();
    _request_id = $(_div).find(':input[name=request_id]').val();

    _avatar = $(_div_content).find('.block-users').find('figure').find('img').attr('src');
    _name = $(_div_content).find('.users-info').find('.tit').text();

    $('#requestRating .avatar-box img').attr('src', _avatar);

    $('#requestRating figcaption').text(_name);
    // $('#requestRating input[name=id]').val(_id);
    $('#requestRating input[name=request_id]').val(_request_id);
    $('#requestRating').modal('show');

}



function reviewRequest(id){

      axios.post(URI,{ id:id }).then( async (output) => {

        if(output.status == 200){

          await $('.listimgs').slick('destroy');


          $('#result_poup').html(output.data);
          $('.listimgs').css({'display' : 'none'});

          setTimeout( () => {
                    $('.listimgs').removeAttr( 'style' );
                    innit();
              }, 200);

          $('#result_poup div.modal').modal('show');

            $('body').addClass('fuc');
        }



      });
        //$('#result_poup')
        //$('#requestVideo').modal('show');
}
$('body').on('click', '.requestPopup .close', function() {
    $('body').removeClass('fuc');
})
</script>
@endsection
