
// const check_name = async (e) => {

// }

async function nextFrom(e){

    e.preventDefault();

    $('div.alert-dark').remove();

    let _index = $('div.slick-active').data('slick-index');

    if( _index == 0 )
    {
        _result = await fetch( window.location.origin + '/checkName', { method: 'POST', headers: { 'X-CSRF-TOKEN': _token, 'Content-Type': 'application/json' }, body: JSON.stringify({ check_name: true , name: e.target[0].value })  });
        //$.post( window.location.origin + '/checkName', { _token:_token, check_name: true , name: e.target[0].value   } );
        if( _result.status > 200 )
        {
            repont = await _result.text();
            handleShowError( JSON.parse(repont).error.name );
            return false
        }
    }


    // check card id
    if(_index == 1 && !$('#file-upload').val() ){
        handleShowError('学生証をアップロードしてください。');
        return false;
    }
    // check  birthday
    if(_index == 1 && ( $(`select[name="birthday['year']"]`).val() == 0 || $(`select[name="birthday['month']"]`).val() == 0 || $(`select[name="birthday['day']"]`).val() == 0 ) ){
        handleShowError('生年月日を入力してください。');
        return false;
    }
    //
    if(_index == 1 && !$('select[name=university_Code').val()  ){
        handleShowError('最終学歴を入力してください。');
        return false;
    }
    //
    if(_index == 1 && !$('select[name=faculty_code').val()  ){
        handleShowError('学部を入力してください。');
        return false;
    }
    //
    if(_index == 1 && !$('input[name=edu_status').val()  ){
        handleShowError('学科を入力してください。');
        return false;
    }

    if(_index == 2){
        let _check_box = false;
       $('#slick-slide02 input[name="subject[]"]').each(function(ind,val){
            if($(val).prop('checked')){
                _check_box = true;
                return false;
            }
       });

        if(!_check_box){
         handleShowError('受諾する科目選択を入力してください。'); return false;
        }
    }


    $('.regist-slide').slick('slickNext');
        $('a.btn-back').show();
     return false;
}


var form = document.getElementById('form-submit');

form.addEventListener('submit', function(event){

    event.preventDefault();
    $('#loading').show();

    var val = $('input[name="bank_account_name"]').val();
    val = val.replaceAll('　','');
    if(val.length == 0){
        handleShowError('口座名義人は必須です');
        return false;
    }

    stripe.createToken('bank_account',
                                {
                                  country: 'JP',
                                  currency: 'jpy',
                                  routing_number: $('input[name="bank_code"]').val()+$('input[name="branch_code"]').val(),
                                  account_number: $('input[name="bank_account_number"]').val(),
                                  account_holder_name: $('input[name="bank_account_name"]').val()
                                  // account_holder_type: $('select[name="business_type"]').val(),
                                }
            ).then(function(result){

                if(result.error){
                    code_error = result.error.param;
                    handleShowError(error_code[code_error]);
                }

                if(result.token){
                    handleSubmit(result.token.id);
                    // $('#form-submit').append($('<input>',{name:'token_stripe', type:'hidden',value:`${result.token.id}`}))
                    // $('#form-submit').submit();
                }

            });



})

function handleSubmit(token){

        $('div.login-bottom-content').hide();
    //enctype="multipart/form-data"
    var form = $("<form>",{method:'post', name:'submit_form', enctype:'multipart/form-data' }).append($('<input>',{name:'_token',value:_token}))
        .append($('#file-upload'))
        .append($('#file-upload-avatar'))
        .append($('<input>',{name:'token_stripe', type:'hidden',value:token }) );
    //console.log($('div#slick-slide00 form','div#slick-slide01 form','div#slick-slide02 form','div#slick-slide03 form')); return false;

        // form.append($('div#slick-slide00 form').html())
        //     .append($('div#slick-slide01 form').html())
        //     .append($('div#slick-slide03 form').html());
            $('div#slick-slide00 form').find(':input').each(function(e,ide){
                form.append($(ide));
            })

            $('div#slick-slide01 form').find(':input',':select').each(function(e,ide){
                form.append($(ide));
            })

            $('div#slick-slide02 form').find(':input').each(function(e,ide){
                    form.append($(ide));
            })

            $('div#slick-slide03 form').find(':input').each(function(e,ide){
                form.append($(ide));
            })

        form.appendTo('body').submit();
        form.remove();
}

function handleShowError(messege_errors) {

$('#loading').hide();



          html_erorr =    `<div class="alert alert-dark"><span>${messege_errors}</span>
                                <button class="btn-close" type="button" onclick="$(this).parent().remove()" >
                                  <svg xmlns="http://www.w3.org/2000/svg" width="29" height="29" viewBox="0 0 29 29" fill="none">
                                    <rect width="2.5" height="20" rx="1.25" transform="matrix(-0.707107 -0.707107 -0.707107 0.707107 22.0972 7.95496)" fill="black"></rect>
                                    <rect width="2.5" height="20" rx="1.25" transform="matrix(-0.707107 -0.707107 -0.707107 0.707107 22.0972 7.95496)" fill="white"></rect>
                                    <rect width="2.5" height="20" rx="1.25" transform="matrix(-0.707107 0.707107 0.707107 0.707107 7.95526 6.18689)" fill="black"></rect>
                                    <rect width="2.5" height="20" rx="1.25" transform="matrix(-0.707107 0.707107 0.707107 0.707107 7.95526 6.18689)" fill="white"></rect>
                                  </svg>
                                </button>
                            </div>`;

$( "div.login-bottom-content" ).prepend(html_erorr);


    $('html, body').animate({ scrollTop: $('body').offset().top}, 1000);
}