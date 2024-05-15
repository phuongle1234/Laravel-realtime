@inject('subject', 'App\Models\Subject')
@inject('Tag', 'App\Models\Tag')

@extends('layouts.teacher.index')

@section('body_class','main_body p-accepted_request pdbottom hasbg')
@section('class_main','p-content-accepted_request pdbottom')

@section('custom_title_back')
<a href="{{ route('teacher.request.list') }}">
  <img src="{{ asset('teacher/images/back-icon.svg') }}" alt="リクエスト内容"></a>
@endsection('custom_title_back')

@php
    $_user =  Auth::guard('teacher')->check()  ? Auth::guard('teacher')->user() : null;
@endphp

@section('content')
<a class="back-link fsc" href="{{ route('teacher.request.accepted') }}">
  <img src="{{ asset('teacher/images/back-icon.svg') }}" alt="{{ trans('teacher.request-accepted_request') }}">
  <span>{{ trans('teacher.request-accepted_request') }}</span>
</a>

@include('component.register.alert')

<div class="box-top boxshadow">
                <ul class="item listimgs">
                  @foreach($item->image as $_key => $_img)
                  <li><figure><img src="{{ $_img }}" alt=""></figure></li>
                  @endforeach
                </ul>

                <div class="item">
                  <h3>{{ $item->title }}</h3>

                  <p><?= nl2br($item->content) ?></p>

                </div>
  </div>
  <form method="post" id="form_submit" enctype="multipart/form-data" >
    @csrf
    @method('put')

    <input  name="path" id="add_path"  type="file" style="display: none" accept=".pdf" >
    <input  name="video" id="add_video" type="file" style="display: none" accept="video/*" >


    <input  name="path_id" type="hidden" >
    <input  name="video_id" type="hidden" >

    <div class="box-bottom boxshadow">
                  <div class="item">
                    <h3 class="green-tit">動画アップロード</h3>

                    <iframe style="display: none; margin-top: 10px"  width="478" height="274" frameborder="0" allowfullscreen allow="autoplay; encrypted-media"></iframe>
                    <video id="main-video" controls="" style="display: none; width: 478px; height: 281px;" ><source type="video/mp4"></video>
                    <figure class="video-block"><img src="{{ asset('teacher/images/accepted_request/img_001.png') }}" alt=""></figure>

                    <span class="btn btn-image btn-pdf btnupload" data-bs-toggle="modal" data-bs-target="#videoOptionPopup">
                      <img src="{{ asset('teacher/common_img/icon_upload.svg') }}" alt="動画を選択する​"><span>動画を選択する​</span>
                    </span>

                    <h3 class="green-tit mt30">添付資料アップロード</h3>
                    <span class="btn btn-image btn-pdf" onclick="$('#add_path').trigger('click')" >
                        <img src="{{ asset('teacher/common_img/icon-pdf.svg') }}" alt="20220311explanation.pdf​">
                        <span id="file-pdf">20220311explanation.pdf​</span>
                    </span>
                  </div>
                  <div class="item">
                    <div class="request-detail">
                      <dl class="noinline">
                        <dt>動画タイトル</dt>
                        <dd>
                          <input class="form-control" type="text" name="video_title" pattern="{40}" maxlength="40"  required>
                        </dd>
                      </dl>
                      <dl class="noinline">
                        <dt>動画説明文</dt>
                        <dd>
                          <textarea class="form-control" name="description" cols="30" maxlength="1000" rows="5" required></textarea>
                        </dd>
                      </dl>
                      <dl class="noinline">
                        <dt>科目</dt>
                        <dd>
                          <select name="subject_id">
                            <option value="">科目</option>
                            @foreach($subject::all() as $_key => $_row)
                              <option value="{{ $_row->id }}" {{ $_row->id == $item->subject_id ? 'selected' : null }}>{{ $_row->text_name }}</option>
                            @endforeach
                          </select>
                        </dd>
                      </dl>
                      <dl class="noinline">
                        <dt>
                          <div class="vl-tit-block">
                            <h2 class="tit-main">分野選択</h2>
                            <div class="list-tag"><span class="tag tag-dark-green">{{ $item->subject->text_name }}</span></div>
                          </div>
                        </dt>
                        <dd>
                          <ul class="list-courses" id="tag_id">
                          @foreach( $item->subject->tags as $_key => $_val )
                            <li class="fixagain">
                              <input type="radio" name="tag_id" value="{{ $_val->id }}" required>
                              <div><span class="ellipsis">{{ $_val->name }}</span></div>
                              <div class="tooltipfix"><span>{{ $_val->name }}</span></div>
                            </li>
                          @endforeach
                          </ul>
                        </dd>
                      </dl>
                      <dl class="noinline">
                        <dt>難易度選択</dt>
                        <dd>
                          <ul class="list-courses list-courses-red">
                            @foreach( $Tag::where('tag_type','difficult')->get() as $_row )
                              <li>
                                <input type="radio" name="field_id" value="{{ $_row->id }}" required>
                                <div><span>{{ $_row->name }}</span></div>
                              </li>
                            @endforeach
                          </ul>
                        </dd>
                      </dl>
                    </div>
                  </div>

                  <div class="item">
                    <button type="submit" class="btn-primary btn btn-image btn-complete-upload"  >
                      <img src="{{ asset('teacher/common_img/icon-complete.svg') }}" alt="アップロードを完了する"><span>アップロードを完了する</span>
                    </button>
                  </div>
    </div>
  </form>

  <div class="modal fade popup-style popup-bottom-sp" id="videoOptionPopup" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <button class="close" data-bs-dismiss="modal" aria-hidden="true"></button>
                    <div class="modal-body">
                      <p class="content-txt">
                         動画を読み込む場所を<br>選択してください。</p>
                      <div class="fce btn-pp">
                        <button class="btn btn-dark" data-bs-dismiss="modal" aria-hidden="true" onclick="$('#add_video').trigger('click')" >この端末から</button>
                        <button class="btn btn-primary" data-bs-dismiss="modal" aria-hidden="true" data-bs-toggle="modal" data-bs-target="#videoConfirm">動画一覧から</button>
                      </div>
                    </div>
                  </div>
                </div>
  </div>

    <div class="modal fade requestPopup popup-style2 popup-bottom-sp" id="videoConfirm" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <button class="close" data-bs-dismiss="modal" aria-hidden="true"></button>

                        <div class="modal-body">
                          <div class="fsc select-box">
                            <h2 class="pp-request-tit">動画管理-確認</h2>
                            <button onclick="getMyVideo()" class="btn btn-primary">選択する</button>
                          </div>

                          <div class="list-teacher list-request select-video">
                                  @foreach($_user->videos()->withCount('views')->orderBy('created_at','DESC')->get() as $_key => $_row)
                                    <div class="request-item item">
                                        <ul class="listimgs">
                                          <li><img src="{{ $_row->thumbnail }}" alt></li>
                                        </ul>
                                        <h3 class="request-tit">{{ $_row->title }}</h3>

                                        <input type="hidden" name="vimeo_id" value="{{ $_row->id }}">
                                        <input type="hidden" name="description" value="{{ $_row->description }}" >
                                        <input type="hidden" name="path" value="{{ $_row->path }}" >
                                        <input type="hidden" name="player_embed_url" value="{{ $_row->player_embed_url }}" >

                                        <p class="answer-deadline">{{ $_row->created_at->format('Y年m月d日 H:i') }}<br>再生回数：{{ $_row->views_count }}回</p>

                                        <div class="icon-check">
                                          <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 25 25" fill="none">
                                            <rect width="25" height="25" rx="12.5" fill="#4197E5"></rect>
                                            <path d="M6 11.1034L11.5417 17L20 8" stroke="white" stroke-width="3" stroke-linecap="round"></path>
                                          </svg>
                                        </div>

                                    </div>
                                  @endforeach
                          </div>

                      </div>
                    </div>
                  </div>
                </div>
       </div>
@endsection

@section('custom_js')
<script src="{{ asset('js/vimeo-upload/vimeo-upload.js') }}"></script>
<script src="{{ asset('js/video_upload/handle.js') }}"></script>

<script>
  let access_token = "{{ env('VIMEO_ACCESS') }}";
  const _REQUEST_ID = {{ request()->id }};

  let _tag = @JSON( $Tag::where('tag_type','field')->get() );

  $(document).ready(function(){

    _li_select = $('select[name=subject_id]').parent().children('ul.select-options').children('li') ;

    $(".listimgs").slick({
          dots: false,
          infinite: true,
          centerMode: false,
          slidesToShow: 1,
          slidesToScroll: 1,
          autoplay: false,
          arrows: true,
    });

        $(_li_select).on( "click", (e) => {
            _id = e.target.getAttribute('rel');
            if(_id)
              showTag(_id,e.target);
        });

        $('input[type=file]').change((e)=>{

            _name = e.target.getAttribute('name');

            if(_name == 'video'){
                reviewVideo(e.target);
            }else{

              _button = _div_review = $(e.target).parent().children('div.boxshadow').find('#file-pdf');
              _file = e.target.files[0];

              upfilePdf(_file,_button);

            }
        })

  });
  //form_submit
var form = document.getElementById('form_submit');

form.addEventListener('submit', function(event){

    event.preventDefault();

    _tag_id = event.target.querySelector("input[name=tag_id]:checked").value;
    _field_id = event.target.querySelector("input[name=field_id]:checked").value;

    _title = $(event.target).find(':input[name="video_title"]').val();
    _description = $(event.target).find(':input[name="description"]').val();
    _file = $(event.target).find(':input[name="video"]')[0].files[0];

    _path_id = $(event.target).find(':input[name="path_id"]').val();
    _file_pdf = $(event.target).find(':input[name="path"]')[0].files[0];
    _video_id = $(event.target).find(':input[name="video"]')[0].files[0];

    _vimeo_id = $(event.target).find(':input[name="video_id"]').val();

    const _check_upload = new Promise( (resolve, reason) => {

                                if(_video_id){

                                        let _flag_pdf = false;

                                        if( _path_id || _file_pdf )
                                          _flag_pdf = true;

                                        // if( ! _flag_pdf )
                                        //   return reason({ mex: 'not-pdf' });

                                        return resolve({ mex: 'up-video' });

                                }else if(_vimeo_id){
                                  return resolve({ mex: 'submit' });
                                }
                                return reason({ mex: 'not-video' });
                    });

    _check_upload.then( (e) => {

        if(e.mex == 'up-video')
          uploadVideo(event.target, _title, _description, _file, { request_id: _REQUEST_ID, tag_id: _tag_id, field_id: _field_id  } );
        if(e.mex == 'submit')
          $(event.target).submit();

    }).catch( (e) => {

        if(e.mex == 'not-video')
          alert('動画 は必須です');

        if(e.mex == 'not-pdf')
          alert('動画は必須です');
    });

    //$(event.target).submit();
    //uploadVideo(event.target, _title, _description, _file);
    //alert('PDF は必須です');
   //alert('動画は必須です'); return false;

})

  function reviewVideo(f){

    _file = f.files[0];

    if( _file.type.split("/")[0] != 'video' )
    {
      alert('Videoみ添付可能です。');
      $(f).val('');
      return false;
    }

      _div_review = $(f).parent().children('div.boxshadow'); //.find('#main-video');

     $(_div_review).find('figure.video-block').hide();
     $(_div_review).find('iframe').hide();

      _video = $(_div_review).find('video');
      _source = $(_video).find('source');



      _url = URL.createObjectURL(_file);

      // review video
      $(_source).attr('src',_url);
      $(_video).load();
      $(_video).show();
  }

  async function getMyVideo(){

      _active = $('#videoConfirm div.active');

      if(!_active.length)
          alert('動画管理-確認する​​は必須です');
      // clear file
      $('input[name="video_id"]').val('');
      $('input[name="path_id"]').val('');

      $('#main-video').hide();
      $('figure.video-block').hide();

      _title = $(_active).find('.request-tit').text();
      _description = $(_active).find(':input[name=description]').val();
      _video_id = $(_active).find(':input[name=vimeo_id]').val();
      _path = $(_active).find(':input[name=path]').val();
      _player_embed_url = $(_active).find(':input[name=player_embed_url]').val();

      if(_player_embed_url.search( 'transcode.svg' ) >= 0 )
        _player_embed_url = await resizingImg( $('div.boxshadow iframe').height(), $('div.boxshadow iframe').width(),_player_embed_url);

      $('div.boxshadow iframe').attr('src',_player_embed_url);
      $('div.boxshadow iframe').show();

      $('input[name="video_id"]').val(_video_id);
      $('input[name="path_id"]').val(_path);

      $('input[name="video_title"]').val(_title);
      $('textarea[name="description"]').val(_description);

      name_pdf = _path.split('/');
      name_pdf = name_pdf[ name_pdf.length -1 ] ;
      name_pdf = name_pdf.length > 20 ? name_pdf.substring(0, 20) + '...' : name_pdf;

      $('#file-pdf').text(name_pdf);
      $('#videoConfirm').modal('hide');
  }

  function showTag(_id,f){

      let _text = $(f).text();
      let tag_filter = _tag.filter(item => parseInt(item.subject_id) == parseInt(_id) );

        $('div.list-tag span.tag-dark-green').text(_text);
        $('#tag_id').empty();

        $.each(tag_filter , (e,val) => {
            $('#tag_id').append(`
                <li class="fixagain">
                  <input type="radio" name="tag_id" value="${val.id}" required>
                  <div><span class="ellipsis">${val.name}</span></div>
                  <div class="tooltipfix"><span>${val.name}</span></div>
                </li>
            `);
        })

  }
</script>
@endsection
