async function editVideo(f, _title, _description, _video_id){

    //progess_pre
    $('#progess').show();
    $('#progess span').hide();

    // await (new VimeoUpload({
    //             name: _title,
    //             description: _description,
    //             video_id: _video_id,
    //             token: access_token,

    //             onError: function(data) {
    //               $('#progess').hide();
    //               console.log(data);
    //             },
    //             onComplete: function(e) {
    //                 $(f).find(':input[name="video"]').remove();
    //                 $(f).append($('<input>',{type: 'hidden', name:'thumbnail',value: e.pictures.base_link }))
    //                 $(f).append($('<input>',{type: 'hidden', name:'player_embed_url',value: e.player_embed_url }))
    //                 $(f).append($('<input>',{type: 'hidden', name:'transcode',value: e.transcode.status }))
    //                 $(f).submit();
    //             }

    //         })).edit();

          $(f).find(':input[name="video"]').remove();
          $(f).submit();

    }
    //video-review

    async function uploadVideo(f, _title, _description, _file, request_value = {} ){

        //progess_pre
        $('#progess').show();

        await (new VimeoUpload({
                    name: _title,
                    description: _description,
                    file: _file,
                    request_value: request_value,
                    onError: function(data) {
                      $('#progess').hide();
                      $('#videoAdd').modal('hide');
                        handleShowError();
                    },
                    onProgress: function(data) {
                      //progess_pre
                        _progress = Math.round( (data.loaded / data.total) * 100 );
                        $('#progess_pre').text(_progress);

                    },
                    onComplete: function(videoId) {

                        //$('#progess').hide();

                        $(f).find(':input[name="video"]').remove();

                        $(f).append($('<input>',{type: 'hidden', name:'video_id',value: videoId }))
                        $(f).append($('<input>',{type: 'hidden', name:'status_upload',value: 'create_new' }))
                        $(f).submit();
                        //var url = 'https://vimeo.com/' + videoId

                    }
                })).upload();



    }

    function deleteVideo(){
        let id = $('#videoEdit input[name=id]').val();
        //let _vimeo_id = $('#videoEdit input[name=vimeo_id]').val();
        //let path = $('#videoEdit div.video-review img').attr('src');
      console.log(id);
        var form = $("<form>",{ method:'post', action:URL_DETELE })
                .append($('<input>',{name:'_token',value:_token }))
                .append($('<input>',{type: 'hidden', name:'_method',value:'PATCH' }))
                .append($('<input>',{name:'id',value: id }));
                // .append($('<input>',{name:'path',value: path }))
                // .append($('<input>',{name:'vimeo_id',value: _vimeo_id }));

          form.appendTo('body').submit();
          form.remove();
    }

function upfilePdf(_file, _button){

    if(_file){
        name = _file.name;
        name = name.length>15?name.substring(0,15)+'...':name;
        $(_button).html(`<span>${name}</span>`);
    }
}


function handleShowError() {

  // $('#loading').hide();
    let _text_error = 'アップロード中にエラーが発生しました。インターネット接続に問題がある可能性があります。もう一度確認してください';


  html_erorr =    `<div class="alert alert-dark"><span>${_text_error}</span>
                        <button class="btn-close" type="button" onclick="$(this).parent().remove()" >
                          <svg xmlns="http://www.w3.org/2000/svg" width="29" height="29" viewBox="0 0 29 29" fill="none">
                            <rect width="2.5" height="20" rx="1.25" transform="matrix(-0.707107 -0.707107 -0.707107 0.707107 22.0972 7.95496)" fill="black"></rect>
                            <rect width="2.5" height="20" rx="1.25" transform="matrix(-0.707107 -0.707107 -0.707107 0.707107 22.0972 7.95496)" fill="white"></rect>
                            <rect width="2.5" height="20" rx="1.25" transform="matrix(-0.707107 0.707107 0.707107 0.707107 7.95526 6.18689)" fill="black"></rect>
                            <rect width="2.5" height="20" rx="1.25" transform="matrix(-0.707107 0.707107 0.707107 0.707107 7.95526 6.18689)" fill="white"></rect>
                          </svg>
                        </button>
                    </div>`;

  $( "div.container-fluid" ).prepend(html_erorr);


      $('div.scroll-nonedefault').animate({ scrollTop: $('body').offset().top}, 1000);
  }