const iframe = document.querySelector('iframe');
const player = new Vimeo.Player(iframe);



$(document).ready( (e) => {

// startVideo();

    let _seconds = 0;
    let _interva = null;
    let _flag_timetanp = false;


    player.on('play', function(e) {

        if( _flag_timetanp )
            _clear_intervel_watch();

       _interva = setInterval((el) => {
                                _seconds ++;
                                _flag_timetanp = true;
                            }, 1000);
    });

    player.on('pause', function(e) {
        _clear_intervel_watch();
    });

    const _clear_intervel_watch = () =>{
                                            clearInterval(_interva);
                                            _flag_timetanp = false;
                                    };

    $( '#requestVideo' ).on('hide.bs.modal', function(e) {

        e.preventDefault();

        clearInterval(_interva);
        player.pause();

        userView(_seconds);

        $('div.modal-backdrop').remove();
        $(e.target).remove();

    })


    window.addEventListener('beforeunload', (event) => {
        event.preventDefault();
        // Chrome requires returnValue to be set.
        userView(_seconds);
    });

})



function userView( _seconds ){

    if(_seconds > 0){
        axios.post( window.location.origin + '/userView', { _method: 'PUT', video_id:_id_vd, request_id: _id_rs, seconds: _seconds })
    }

}

function likeVideo(f, _id){
    console.log(f);
    _svg = $(f).children('svg');
    _is_active = $(_svg).attr('class');
    _status = '';

    if(_is_active == 'active'){
      _status = 'inactive';
      $(_svg).removeClass('active');
    }else{
      _status = 'active';
      $(_svg).addClass('active');
    }

    axios.post( window.location.origin + '/userLike', { _method: 'PUT', id:_id, status: _status }).then((response) => {
        if(response.status==200)
          $('p.total-liked').text(response.data); //console.log(response);
    })
}