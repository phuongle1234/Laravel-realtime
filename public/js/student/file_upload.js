function appendFile(f){

  // let li =  $('#file_append li:first');
  //     li.find(':input[type=file]').trigger('click');
    $('input[data-chose=0]:first').trigger('click');
}

function clearFile(f){

    //_slick_index = $('.listimg .slick-track li').length-1;




    destroyCarousel();

    $('ul.slick-dots li.slick-active').remove();

    let _index_file = $( f.closest('li') ).attr('data-index');

    $(`input[data-index=${_index_file}][type=file]`).val('');
    $(`input[data-index=${_index_file}][type=file]`).attr('data-chose',0);

    f.closest('li').remove();

    //$('.listimg').removeClass("slick-initialized slick-slider");

    slickCarousel();

    // if( _slick_index )
    // $('.listimg').slick( 'slickGoTo', ( _slick_index -1  ) );

    //$('.listimg').slick('slickPrev');

    //destroyCarousel();

    //slickCarousel();

    // destroyCarousel();
    // slickCarousel();
    // setTimeout(function (){
    //     destroyCarousel();
    // }, 200);
    // slickCarousel();
  // let figure = $(f).parent();
  // clear file
  // $(figure).find(':input[type=file]').val('');
  // clear img
  // $(figure).find('img').attr('src','');
  // $(figure).parent().attr('class','hide');
  // check file
  // if( $('ul li').length == 6 )
  //   $(figure).parent().parent().addClass('non-file');

   if(  $('input[data-chose=0]').length )
      $('#file_append~button.btn-image').attr('disabled', false);
}

// submit form
async function sendRequest(){

  $('#loading').show();

  let _form_control = $('#form .form-control, #form input, input[name="path[]"]');

    // try {

    //     let _checking = await _check();

  var form = $("<form>",{ action: URL_SUBMIT, method:'post', name:'submit_form', enctype:'multipart/form-data', style: 'display: none;' })
              .append($('<input>',{name:'_token',value:_token}))
              .append($('<input>',{type: 'hidden', name:'_method',value:'PUT' }))
              .append($(_form_control))
              .append($('section.section_request_detail'));
        form.appendTo('div.container-fluid').submit();

    // }catch(e) {
    //    alert(e.mext);  return false;
    // }

}

function _check (){

  // return new Promise((resolve, reject) => {

    _validate = '';

      if(!$('input[name="title"]').val())
        _validate += 'タイトル​は必須です。\n';

      if(!$('textarea[name="content"]').val())
      _validate += '説明文​は必須です。\n';

      if(!$('select[name="subject_id"]').val())
        _validate += '科目選択​は必須です。\n';

    if(_validate == '')
        return {'ist' : true};

      return {'ist' : false, 'mext': _validate};

  // })

}

// async function convertHeicToJpg(input , i = 0)
// {
//       $('#loading').show();

//         input = input.target;
//         var blobfile = $(input)[0].files[i]; //ev.target.files[0];

//         let blobURL = URL.createObjectURL(blobfile);

//         // convert "fetch" the new blob url
//         let blobRes = await fetch(blobURL)

//         // convert response to blob
//         let blob = await blobRes.blob()

//         // convert to PNG - response is blob
//         let resultBlob = await heic2any({ blob })


//         var url = URL.createObjectURL(resultBlob);

//         //let fileInputElement = $(input)[0];

//         let container = new DataTransfer();
//         let file = new File([resultBlob], "heic"+".png",{type:"image/png", lastModified:new Date().getTime()});
//         container.items.add(file);


//         $(input)[0].files = container.files;

//         $('#loading').hide();
//         return true;

// }

function initSlick(){

    $(".listimg").slick({
        dots: false,
        infinite: false,
        slidesToShow: 1,
        autoplay: false,
        arrows: false,
        draggable: false,
        swipe: false
    });
}

// $(".listimg").slick({
//     dots: false,
//     infinite: false,
//     slidesToShow: 1,
//     autoplay: false,
//     arrows: false,
// });

// function unslick(){
//     $('.listimg').slick('unslick');
// }

function slickCarousel() {
    $('.listimg').slick({
        dots: true,
        infinite: false,
        slidesToShow: 1,
        autoplay: false,
        arrows: false,
        // draggable: false,
        // swipe: false
    });
}

function destroyCarousel() {
    if ($('.listimg').hasClass('slick-initialized')) {
        $('.listimg').slick('destroy');
    }
}

// load teacher
$('input[type=file]').change( async function(value){



  var ext = $(this).val().split('.').pop();

  if( ext == 'HEIC' )
      await convertHeicToJpg(value);
  // if(!(jQuery.inArray(ext,['pdf','jpg','png']) !== -1)){
  //     $(this).val('');
  //     alert('PDF, PNG, JPEG, のみ添付可能です');
  //     return false;
  // }

  let file = $(this)[0].files[0];

  let val = $(this).val()

    if(file){

      $(this).attr('data-chose',1);
      let _index_file =  $(this).attr('data-index');

      if( file.size>10000000 ){
        alert('・10MB以内の画像をアップロードしてください。');
        $(this).val('');
        return false;
      }

      if( file.type.split('/')[0] != 'image'){
        alert('イメージのみ添付可能です。');
        return false;
      }

      let figure = $(this).parent();

      let reader = new FileReader();


      reader.readAsDataURL(file);
           reader.onload = function(reader){
               let srcimg = reader.srcElement.result;
               $('.listimg').append(`<li data-index="${ _index_file }">
                    <figure>
                          <img alt="" src="${srcimg}">
                            <div class="remove-img" onClick="clearFile(this)">
                              <svg xmlns="http://www.w3.org/2000/svg" width="29" height="29" viewBox="0 0 29 29" fill="none">
                                <rect width="2.5" height="20" rx="1.25" transform="matrix(-0.707107 -0.707107 -0.707107 0.707107 22.0972 7.95496)" fill="black"></rect>
                                <rect width="2.5" height="20" rx="1.25" transform="matrix(-0.707107 -0.707107 -0.707107 0.707107 22.0972 7.95496)" fill="white"></rect>
                                <rect width="2.5" height="20" rx="1.25" transform="matrix(-0.707107 0.707107 0.707107 0.707107 7.95526 6.18689)" fill="black"></rect>
                                <rect width="2.5" height="20" rx="1.25" transform="matrix(-0.707107 0.707107 0.707107 0.707107 7.95526 6.18689)" fill="white"></rect>
                              </svg>
                            </div>
                    </figure>
                </li>`);
             // $(figure.find('img')).attr('src',reader.srcElement.result);
               destroyCarousel();
               slickCarousel();
           }

      // $(figure).parent().removeClass('hide');
      // $(figure).parent().parent().removeClass('non-file');

        if( ! $('input[data-chose=0]').length )
          $('#file_append~button.btn-image').attr('disabled', true);
    }

    // setInterval(function () {
    //     initSlick();
    // }, 3000 )

})




const show_poup = async (event) => {

    if( ! _CHECK_PLAN )
    {
      alert('有料会員のみがリクエストできます。'); return false;
    }

    let _return = await _check();

    if( ! _return.ist )
    {
      alert(_return.mext); return false;
    }

    $('#requestTicket').modal('show');
      return true;

      // _check().then( (env) => {
      //             _poup =  event.target.getattribute('data-poup');
      //             console.log(_poup);
      //             $(event.target).unbind('show.bs.modal');
      //             $(event.target).modal('show'); return;
      //           })
      //         .catch((erre)=> { if(erre){ alert(erre.mext); return; } })

}

// $('#requestTicket').on('show.bs.modal', function (e) {
//   e.preventDefault();
//   console.log(e);
//   // do something...
// })