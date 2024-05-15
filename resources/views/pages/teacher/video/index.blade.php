@inject('subject', 'App\Models\Subject')

@extends('layouts.teacher.index')

@section('body_class','main_body p-video_management pdbottom')
@section('class_main','p-content-video_management pdbottom')


@php
    $_user =  Auth::guard('teacher')->check()  ? Auth::guard('teacher')->user() : null;
@endphp



@section('content')

  <div class="fsc tvideo-tit">
      <h2>{{ trans('teacher.video-list') }}</h2>
      <button class="btn-image btn-pdf btn-upload" data-bs-toggle="modal" data-bs-target="#videoAdd"  ><span>動画アップロード</span></button>
  </div>

<div class="list-teacher list-request">
        @foreach($item as $key => $_row)
            <div class="request-item item">
              <ul class="listimgs">
                <li><img src="{{ $_row->thumbnail }}" alt></li>
              </ul>
              <h3 class="request-tit">{{ $_row->title }}</h3>
              <p class="answer-deadline">{{ $_row->created_at->format('Y年m月d日 H:i') }}
                <br>再生回数：{{ $_row->views_count }}回</p>

              <input type="hidden" name="description" value="{{ $_row->description }}" >
              <input type="hidden" name="vimeo_id" value="{{ $_row->vimeo_id }}" >
              <input type="hidden" name="path" value="{{ $_row->name_path }}" >
              <input type="hidden" name="id" value="{{ $_row->id }}" >
              <input type="hidden" name="player_embed_url" value="{{ $_row->player_embed_url }}" >
              <input type="hidden" name="request" value="{{ $_row->request_count }}">

              <div class="tvideo-btn-group">

              @if( ! $_row->request_count )
                <button class="btn btn-image btn-pencil" onclick="reviewEdit(this)"><span>編集する</span></button>
              @endif

                <button class="btn btn-primary" onclick="review(this)"><span>内容を確認する</span></button>

              </div>

            </div>
        @endforeach
  </div>
            @include('component.teacher.pagination.index')

      <!-- modal fade requestPopup requestSmPopup video-pp popup-style2 -->

      <div class="modal fade requestPopup requestSmPopup video-pp popup-style2 popup-bottom-sp" id="videoAdd" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <button class="close" data-bs-dismiss="modal" aria-hidden="true"></button>
                    <div class="modal-body">
                          <form method="post" enctype="multipart/form-data" id="form_add">
                          @csrf
                          @method('put')
                              <h2 class="pp-request-tit">{{ trans('teacher.video-add') }}</h2>
                              <div class="block-content">
                                <input  name="path" id="add_path"  type="file" style="display: none" accept=".pdf" >
                                <input  name="video" id="add_video" type="file" style="display: none" accept="video/*" >
                                <div class="img">
                                    <div class="video-block video-review" >
                                        <img src="{{ asset('teacher/images/accepted_request/img_001.png') }}" alt="">
                                        <video id="main-video" controls style="display: none; width: 359px; height: 206px;"> <source type='video/mp4' > </video>
                                    </div>

                                    <span class="btn btn-image btn-pdf btnupload" id="app_button_upload" onclick="$('#add_video').trigger('click')">
                                      <img src="{{ asset('teacher/common_img/icon_upload.svg') }}" alt="動画を選択する​​">
                                      <span>動画を選択する​​</span>
                                    </span>

                                    <span class="btn btn-image btn-pdf" onclick="$('#add_path').trigger('click')">
                                      <img src="{{ asset('teacher/common_img/icon-pdf.svg') }}" alt="20220311explanation.pdf​" >
                                      <span>20220311explanation.pdf​</span>
                                    </span>

                                </div>
                                <div class="content">
                                  <div class="video-edit-block">
                                    <dl>
                                      <dt>動画タイトル</dt>
                                      <dd>
                                        <input name="title" class="form-control" type="text"  name="title" >
                                      </dd>
                                    </dl>
                                    <dl>
                                      <dt>動画説明文</dt>
                                      <dd>
                                        <textarea name="description" style="height: 237px;" class="form-control"></textarea>
                                      </dd>
                                    </dl>
                                  </div>
                                </div>

                                <div class="btn-wrapper">
                                  <!-- <span class="btn btn-red" data-bs-dismiss="modal" aria-hidden="true" data-bs-toggle="modal" data-bs-target="#confirmDltVideo">削除する</span> -->
                                  <button class="btn btn-primary">登録する</button>
                                </div>

                              </div>
                          </form>
                    </div>
                  </div>
                </div>
        </div>

              <div class="modal fade requestPopup requestSmPopup video-pp popup-style2 popup-bottom-sp" id="videoEdit" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <button class="close" data-bs-dismiss="modal" aria-hidden="true"></button>
                    <div class="modal-body">
                      <h2 class="pp-request-tit">動画管理-編集</h2>
                      <form action="{{ route('teacher.video.update') }}" id="form-edit" method="post" enctype="multipart/form-data">
                      <div class="block-content">

                          @csrf
                          @method('PATCH')
                            <input  name="path" type="file" data-modal="edit-video" style="display: none" accept=".pdf" >
                            <input  name="video"  type="file" data-modal="edit-video" style="display: none" accept="video/*" >

                            <input type="hidden" name="vimeo_id">
                            <input type="hidden" name="id">

                                <div class="img">
                                  <div class="video-block video-review" >
                                      <iframe  width="359" height="206" frameborder="0" allowfullscreen allow="autoplay; encrypted-media"></iframe>
                                      <video id="main-video" controls="" style="display: none; width: 359px; height: 206px;" ><source type="video/mp4"></video>
                                  </div>

                                    <span class="btn btn-image btn-pdf btnupload" onclick="$('#videoEdit input[name=video]').trigger('click')">
                                      <img src="{{ asset('teacher/common_img/icon_upload.svg') }}" alt="動画を選択する​​" >
                                      <span>動画を選択する​​</span>
                                    </span>

                                    <span class="btn btn-image btn-pdf" onclick="$('#videoEdit input[name=path]').trigger('click')">
                                      <img src="{{ asset('teacher/common_img/icon-pdf.svg') }}" alt="20220311explanation.pdf​">
                                      <span>20220311explanation.pdf​</span></span>
                                </div>
                              <div class="content">
                                <div class="video-edit-block">
                                  <dl>
                                    <dt>動画タイトル</dt>
                                    <dd>
                                      <input class="form-control" type="text" name="title" value="猿でもわかる三角関数">
                                    </dd>
                                  </dl>
                                  <dl>
                                    <dt>動画説明文</dt>
                                    <dd>
                                      <textarea name="description" class="form-control">この動画を見れば、今までわからなかった三角関数があっという間にわかるはず。三角関数は複雑そうに見えてすごく単純です。やり方さえわかれば、すぐにマスターできるでしょう。</textarea>
                                    </dd>
                                  </dl>
                                </div>
                              </div>

                              <div class="btn-wrapper">
                            <span class="btn btn-red" data-bs-dismiss="modal" aria-hidden="true" data-bs-toggle="modal" data-bs-target="#confirmDltVideo">削除する</span>
                            <button class="btn btn-primary">登録する</button>
                          </div>

                      </div>
                      </form>
                    </div>
                  </div>
                </div>
              </div>

              <div class="modal fade popup-style popup-bottom-sp" id="confirmDltVideo" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <button class="close" data-bs-dismiss="modal" aria-hidden="true"></button>
                    <div class="modal-body">
                      <h2 class="cvc-title">確認</h2>
                      <p class="content-txt">
                        この動画を削除します。<br>
                        本当によろしいでしょうか？
                      </p>
                      <div class="fce btn-pp">
                        <button class="btn btn-dark" data-bs-dismiss="modal" aria-hidden="true">キャンセル</button>
                        <button class="btn btn-red" onclick="deleteVideo()">削除する</button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="modal fade requestPopup requestSmPopup video-pp popup-style2 popup-bottom-sp" id="videoAccept" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <button class="close" data-bs-dismiss="modal" aria-hidden="true"></button>
                    <div class="modal-body">
                      <h2 class="pp-request-tit">動画管理-確認</h2>
                      <div class="block-content">
                        <div class="img">
                          <iframe  width="359" height="206" frameborder="0" allowfullscreen allow="autoplay; encrypted-media"></iframe>
                          <a  class="pdf btn-primary btn-image btn-pdf btn-pdf-border" download >
                            <img src="{{ asset('teacher/common_img/icon-pdf-bd.svg') }}"  >
                            <!-- <a href="" target="_blank"> -->
                                <span>20220311explanation.pdf​​</span>
                            <!-- </a> -->
                          </a>
                        </div>
                        <div class="content">
                          <div class="video-edit-block">
                            <dl>
                              <dt>動画タイトル</dt>
                              <dd class="title">猿でもわかる三角関数</dd>
                            </dl>
                            <dl>
                              <dt>動画説明文</dt>
                              <dd class="description">この動画を見れば、今までわからなかった三角関数があっという間にわかるはず。三角関数は複雑そうに見えてすごく単純です。やり方さえわかれば、すぐにマスターできるでしょう。</dd>
                            </dl>
                          </div>
                        </div>
                        <div class="btn-wrapper">
                          <button class="btn btn-primary btn-dark" data-bs-dismiss="modal" aria-hidden="true">閉じる</button>
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
<!-- pending render access token for vimeo -->

<script>

let URL_DETELE = @JSON( route('teacher.video.delete')  );

$('input[type=file]').change((e)=>{

    _name = e.target.getAttribute('name');

    if(_name == 'video'){

        reviewVideo(e.target);
    }else{

      _button = _div_review = $(e.target).parent().children('div.img').children('span').eq(1);
      _file = e.target.files[0];
      upfilePdf(_file,_button);

    }
})



async function review(f){

//_delete_id

   _div = $(f).parent().parent();
   _title = $(_div).children('h3.request-tit').text();

   _path = $(_div).children('input[name="path"]').val();
   _description = $(_div).children('input[name="description"]').val();


  _player_embed_url = $(_div).children('input[name="player_embed_url"]').val();

  if(_player_embed_url.search( 'transcode.svg' ) >= 0 )
  _player_embed_url = await resizingImg( $('#videoEdit iframe').height(), $('#videoEdit iframe').width(),_player_embed_url);

  $('#videoAccept iframe').attr('src', _player_embed_url);

   //$('#videoAccept div.video-review img').attr('src',$(_div).children('ul').children('li').children('img').attr('src') );



  // name_pdf = name_pdf[ name_pdf.length -1 ] ;
  // name_pdf = name_pdf.length > 20 ? name_pdf.substring(0, 20) + '...' : name_pdf; pdf
  $('#videoAccept a.pdf').hide();

  if(_path)
  {
    name_pdf = _path;
    _path = window.location.origin + '/linkDowload/' + name_pdf;
    $('#videoAccept a').attr('href',_path);
    $('#videoAccept a span').text( name_pdf );
    $('#videoAccept a.pdf').show();
  }


   $('#videoAccept dd.title').text(_title);

  // $('#videoAccept input[name=id]').val(_id);
  // $('#videoAccept input[name=vimeo_id]').val(_vimeo_id);
    $('#videoAccept dd.title').text(_title);
   $('#videoAccept dd.description').text(_description);

   //vimeo_id
  $('#videoAccept').modal('show');

}

var form_edit = document.getElementById('form-edit');

form_edit.addEventListener('submit', function(event){

    event.preventDefault();


    _title = $(event.target).find(':input[name="title"]').val();
    _description = $(event.target).find(':input[name="description"]').val();
    _file = $(event.target).find(':input[name="video"]')[0].files[0];

    _video_id = $(event.target).find(':input[name="vimeo_id"]').val();

    _file_pdf = $(event.target).find(':input[name="path"]')[0].files[0];

    if(!_file){
      editVideo(event.target,_title,_description, _video_id);
      return false;
    }

    uploadVideo(event.target, _title, _description, _file);

})

var form = document.getElementById('form_add');

form.addEventListener('submit', function(event){

  event.preventDefault();

  _title = $(event.target).find(':input[name="title"]').val();
  _description = $(event.target).find(':input[name="description"]').val();
  _file = $(event.target).find(':input[name="video"]')[0].files[0];

  _file_pdf = $(event.target).find(':input[name="path"]')[0].files[0];


  if(!_file){
    alert('動画を選択する​​は必須です');
    return false;
  }

  // if(!_file_pdf){
  //   alert('PDF​​は必須です');
  //   return false;
  // }

  uploadVideo(event.target, _title, _description, _file);

})



// async function loadInfoVIdeo(_video_id){

//   const _result = await fetch(`https://api.vimeo.com/videos/${_video_id}`, {
//             method: "GET",
//             headers: {
//               Authorization: `Bearer ${access_token}`,
//               "Content-Type": "application/json",
//             },
//             // body: JSON.stringify({

//             //     }),
//           });

//   const res = await _result.json();
//   console.log(res);
//   //return res;
// }

async function reviewEdit(f){

//_delete_id

   _div = $(f).parent().parent();
   _title = $(_div).children('h3.request-tit').text();
   _create_at = $(_div).children('p.answer-deadline').html();
   _description = $(_div).children('input[name="description"]').val();
   _id = $(_div).children('input[name="id"]').val();
  _vimeo_id =  $(_div).children('input[name="vimeo_id"]').val();
  _request =  parseInt( $(_div).children('input[name="request"]').val() );
  _player_embed_url = $(_div).children('input[name="player_embed_url"]').val();


  if(_player_embed_url.search( 'transcode.svg' ) >= 0 )
  _player_embed_url = await resizingImg( $('#videoEdit iframe').height(), $('#videoEdit iframe').width(),_player_embed_url);

    $('#videoEdit iframe').attr('src', _player_embed_url);

    $('#videoEdit div.video-review img').attr('src',$(_div).children('ul').children('li').children('img').attr('src') );
    $('#videoEdit input[name=title]').val(_title);
    $('#videoEdit input[name=id]').val(_id);
    $('#videoEdit input[name=vimeo_id]').val(_vimeo_id);
    $('#videoEdit textarea[name=description]').val(_description);

  $('#videoEdit span.btn-red').show();

  if(_request)
  $('#videoEdit span.btn-red').hide();

   //vimeo_id
  $('#videoEdit').modal('show');

}

function reviewVideo(f){

  if( f.files[0].type.split("/")[0] != 'video' )
  {
    alert('Videoみ添付可能です。');
    $(f).val('');
    return false;
  }

    _div_review = $(f).parent().children('div.img').children('div.video-review')
    $(_div_review).children('img').hide();

    if( $(f).attr('data-modal') == 'edit-video' ){
        $(_div_review).children('iframe').remove();
    }

    _video = $(_div_review).children('video');
    _source = $(_video).children('source');

    _file = f.files[0];

    _url = URL.createObjectURL(_file);

    $('figure').remove();
    // review video
    $(_source).attr('src',_url);
    $(_video).load();
    $(_video).show();
}



// $("iframe").on("load", (e) => {
//   console.log(e);
//   _iframe  = e.target;

//   if( _iframe.getAttribute('src').search( 'transcode.svg' ) < 0)
//     return false;
//   _height = _iframe.getAttribute('height');
//   _width = _iframe.getAttribute('width');

//   _parent = $(_iframe).parent();

//   $(_parent).prepend( $(`<div width="${_width}" height="${_height}" >
//                             <img src='${ _iframe.getAttribute('src') }' style="object-fit: contain; width: 100%; height: 100%;" />
//                     </div>`) );

//   $(_iframe).remove();

// });

</script>
@endsection
