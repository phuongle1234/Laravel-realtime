@inject('subject', 'App\Models\Subject')
@inject('Request', 'App\Models\Request')

@extends('layouts.teacher.index')

@section('body_class','main_body p-request_list pdbottom')
@section('class_main','p-content-request_list pdbottom')

@section('custom_css')
<script src="{{ asset('plugin/js/vue/vue.js') }}"></script>

<link href="{{ asset('js/slick/slick-lightbox.css') }}" rel="stylesheet"/>
<script src="{{ asset('js/slick/slick.min.js') }}"></script>
<script src="{{ asset('js/slick/slick-lightbox.min.js') }}"></script>

@endsection('custom_css')

@php
    $_user =  Auth::guard('teacher')->check()  ? Auth::guard('teacher')->user() : null;
    $_arr_subject = [];

@endphp

@section('content')
<section class="section_01">
  @include('component.register.alert')
  <div class="requestL-block">
        <div class="item">
          <h2 class="teacher-title">{{ trans('teacher.request-list') }}</h2>
          <ul class="list-courses">
                @foreach($subject::all() as $key => $_sub)
                  <li>
                      <input onclick="loadData(this)" type="radio" name="check_id" id="check1" value="{{ $_sub->id }}" >
                      <div><img src="{{ asset($_sub->icon) }}" alt=""><span>{!! $_sub->name !!}</span></div>
                  </li>
                @endforeach

          </ul>
    </div>

    <figure class="item"><img src="{{ asset('teacher/images/request_list/img_001.png') }}" alt="科目検索"></figure>

  </div>
</section>

<section class="section_02">


        <div class="vl-tit-block">
          <h2 class="tit-main"><span>あなたへのおすすめ</span><img src="{{ asset('teacher/images/icon-recommend.svg') }}" alt="あなたへのおすすめ"></h2>

          <div class="list-tag">

            @foreach($_user->subject()->get() as $_key => $sub)
              @php array_push( $_arr_subject, $sub->id );  @endphp
              <span class="tag tag-dark-green">{!! $sub->name !!}</span>
            @endforeach
          </div>

        </div>

        <div class="list-teacher">
        <!-- $_arr_subject -->

          @foreach( $Request::where(['status' => EStatus::PENDING, 'is_direct' => 0, 'is_displayed' => EStatus::IS_DISPLAYED ])
                            ->whereNotIn('user_id',$_user->list_blocked_by_user)
                            ->whereIn('subject_id',$_arr_subject)
                            ->orderBy('created_at','DESC')
                            ->get()
          as $_key => $item)

            <div class="request-item item">

                  @if($item->is_urgent)
                        <div class="status-order is-urgent"><img src="{{ asset('teacher/common_img/urgent.svg') }}" alt="緊急"><span>緊急</span></div>
                  @endif

                <ul class="listimgs" >
                  @foreach($item->image as $_img)
                  <li ><img src="{{ $_img }}" alt=""></li>
                  @endforeach
                </ul>

                <h3 class="request-tit">{{ $item->title }}</h3>
                <p class="answer-deadline">{{ trans('common.Request_order_deadline') }} : {{ $item->expires_at }}</p>
                <ul class="list-courses">
                  <li>
                    <div><img src="{{ asset($item->subject->icon) }}"><span>{!! $item->subject->name !!}</span></div>
                  </li>
                </ul>
                <input type="hidden" name="content" value="<?= nl2br($item->content) ?>">
                <input type="hidden" name="id" value="{{ $item->id }}" >
                <button class="btn btn-primary" onclick="review($(this).parent())" >内容を確認する</button>
            </div>
          @endforeach
        </div>

  </section>

    <div class="modal fade requestPopup requestSmPopup popup-style2 popup-bottom-sp" id="requestTextPp" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <button class="close" data-bs-dismiss="modal" aria-hidden="true"></button>
          <div class="modal-body">
            <h2 class="pp-request-tit">リクエスト内容確認</h2>
            <div class="block-content">
                  <div class="img">
                    <!-- //listimgs -->
                        <ul class="listimgs"></ul>
                    </div>
              <div class="content">
                <h3 class="tit">見出しが入ります見出しが入ります</h3>
                <ul class="list-courses">
                  <li>
                    <div><img src="../images/request_list/icon_1.png" alt="数学Ⅰ"><span>数学Ⅰ</span></div>
                  </li>
                </ul>
                <p class="text">テキストが入りますテキストが入りますテキストが入りますテキストが入りますテキストが入りますテキストが入りますテキストが入りますテキストが入りますテキストが入りますテキストが入りますテキストが入りますテキストが入りますテキストが入りますテキストが入りますテキストが入ります</p>
              </div>
            </div>
            <div class="btn-wrapper">
              <input type="hidden" name="id" />
              <!-- <button class="btn btn-primary btn-dark" data-bs-dismiss="modal" aria-hidden="true" data-bs-toggle="modal"  >受諾しない</button> -->
              <button class="btn btn-primary" type="button" data-bs-dismiss="modal" aria-hidden="true" data-bs-toggle="modal" data-bs-target="#confirmDlt">リクエストを受ける</button>
            </div>
          </div>
        </div>
      </div>
    </div>

      <div class="modal fade popup-style popup-bottom-sp" id="confirmDlt" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <button class="close" data-bs-dismiss="modal" aria-hidden="true"></button>
            <div class="modal-body">
              <h2 class="cvc-title">確認</h2>
              <p class="content-txt">
                このリクエストを受注します<br>
                よろしいでしょうか？
              </p>
              <div class="fce btn-pp">
                <button class="btn btn-dark" data-bs-dismiss="modal" aria-hidden="true">キャンセル</button>
                <button class="btn btn-primary" onclick="submit_request()">受注する</button>
              </div>
            </div>
          </div>
        </div>
      </div>

@endsection

@section('custom_js')
<script>
    let URL_REQUEST = @JSON( route('teacher.request.handle') );

    let URL_FILTER = @JSON( route('teacher.request.filter') );

    $(document).ready(function(){
        innit();
    });

    const innit = () => {

        // $(".listimgs").slick({
        //       dots: false,
        //       infinite: false,
        //       centerMode: false,
        //       slidesToShow: 1,
        //       slidesToScroll: 1,
        //       autoplay: false,
        //       arrows: true,
        //   });
        //   $(".listimgs").slickLightbox({
        //             src: 'src',
        //             itemSelector: 'li img'
        //   });

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
    }

    async function review(f){
        _id =  $(f).find(':input[name=id]').val();

        _check_status = await axios.post( window.location.origin + '/checkRequest', { _method: 'PUT', id: _id  } );

        if( _check_status.status != 200 )
        {
          alert( _check_status.data ); return false;
        }

        await $('.listimgs').slick('destroy');

        $('#requestTextPp .listimgs').empty();

        $('#requestTextPp .tit').html( $(f).find('.request-tit').text() );
        $('#requestTextPp .list-courses').html( $(f).find('.list-courses').html() );

        $('#requestTextPp .listimgs').html( $(f).find('.listimgs').html() );
        //$('#requestTextPp .listimgs').html(`<li><img src="${ $(f).find('.listimgs img')[0].currentSrc }" alt=""></li>`);

        $('#requestTextPp p.text').html( $(f).find(':input[name=content]').val() );
        $('#requestTextPp input[name=id]').val( _id );
        $('#requestTextPp input[name=is_direct]').val( $(f).find(':input[name=is_direct]').val() );
        innit();

        await  setTimeout( () => {  $('#requestTextPp').modal('show');  }, 200);


    }

    function loadData(f){
        _subject = $(f).parent().children('div').children('span').text();
        _id = $(f).val();
        $('div.list-tag').empty();
        $('div.list-tag').html(`<span class="tag tag-dark-green">${_subject}</span>`);

        axios.post(URL_FILTER,{ _method:'PUT', id: _id }).then((response) => {
            if(response.status==200){
              $('div.list-teacher').empty();
              $('div.list-teacher').html(response.data);

              if ($('.listimg').hasClass('slick-initialized'))
               $('.listimg').slick('destroy');

              innit();

            }
        })
    }



    function submit_request(flag){

      let _id = $('#requestTextPp input[name=id]').val();
      let _satus = 'accept';

      var form = $("<form>",{ method:'post', action:URL_REQUEST })
              .append($('<input>',{name:'_token',value:_token}))
              .append($('<input>',{type: 'hidden', name:'_method',value:'PUT' }))
              .append($('<input>',{name:'id',value:_id}))
              .append($('<input>',{name:'status',value:_satus}));
        form.appendTo('body').submit();
        form.remove();
  }



  // new Vue({
  //     el: "#app",
  //     data() {
  //       return {
  //         list: [],
  //         subject: [],
  //       }
  //     },
  //     methods: {

  //       construct(){

  //         axios.post(URL_REQUEST,{ _method:'PUT' }).then((response) => {
  //             if(response.status == 200){
  //                 this.list.splice(0, this.list.length);
  //                 this.subject.splice(0, this.subject.length);
  //                 this.list = response.data.list;
  //                 this.subject = response.data.subject_data;
  //             }
  //         });

  //       },
  //       showSubjectName(id) {
  //           var result = this.subjects.find(item => item.id == id);
  //           return result.name;
  //       }

  //     },
  //     computed: {

  //     },
  //     created(){
  //         this.construct();
  //     },
  //     beforeCreate(){



  //     mounted(){

  //     }
  // })

</script>
@endsection
