@extends('layouts.register.index')
@inject('subject', 'App\Models\Subject')

<!-- inject('schoolMaster', 'App\Models\SchoolMaster') -->

@section('custom_css')
<link rel="stylesheet" href="{{ asset('register/css/slick/slick.css') }} ">
<link rel="stylesheet" href="{{ asset('register/css/slick/slick-theme.css') }} ">
<style>

.regist-info figure div{
    text-align: center !important;
    width: 135px !important;
    height: 135px !important;
    border-radius: 50% !important;
    overflow: hidden !important;
    margin: 0 auto !important;
    display: flex;
    align-items: center;
    justify-content: center;
}

.select2-selection--single{
    background-color: #fbfbfb !important;
    border: none !important;
  }

/* .regist-info figure img{
    width: 100%;
    height: 100%;
    object-fit: fill;
} */

</style>
@endsection

@section('class_main', 'regist regist-type2')
@section('class_section' , 'regist regist-type2')
@section('class_body' , 'regist regist-type2')


@section('content')

<div class="login-bottom">
  <div class="login-bottom-content">
    @include('component.register.alert')
    <div class="loginbox_c full-width screen-back">
      <a class="btn-back" onClick="$('.slick-slider').slick('slickPrev')"><img src=" {{ asset('register/images/back-icon.svg') }}" alt=""></a>

      <input name="card_id" type="file" id="file-upload" value="{{ old('card_id') }}" style="display: none;" />

      <div class="regist-slide regist-slide-edit">
        <div class="item">
          <div class="opt-tit">
            <h2 class="text_center title">ニックネーム登録</h2>
          </div>
          <!-- slide 1  -->
          <form method="post" onsubmit="return nextFrom(event)">
            <table class="table-style table-sp">
              <tbody>
                <tr>
                  <td colspan="2" class="addicon">
                    <span class="clgray">アイコンがついているものがedutoss上で公開されます。</span>
                  </td>
                </tr>
                <tr>
                  <th class="addicon"><span>ユーザー名</span></th>
                  <td>
                    <input class="form-control" type="text" pattern="^\S+$" name="name" data-label="ユーザー名" value="{{ old('name') }}" placeholder="お名前太郎" required maxlength="20">
                  </td>
                </tr>
              </tbody>
            </table>
            <div class="btn-wrapper">
              <button type="submit" class="btn btn-lightgreen">次へ</button>
            </div>
          </form>
        </div>
        <div class="item regist-info" style="width: 100%; display: inline-block;">
          <div class="opt-tit">
            <h2 class="text_center title">基本情報登録</h2>
          </div>
          <input style="display:none" name="avatar" type="file" id="file-upload-avatar">

          <figure onclick='$("#file-upload-avatar").trigger("click");' class="avatar-img-up">

            <div>
              <img src="{{ asset('register/images/user.png') }}" alt="画像選択">
            </div>
            <figcaption style="margin-top: 20px">画像選択</figcaption>
          </figure>



          <!-- slide 2  -->
          <form method="post" onsubmit="return nextFrom(event)">
            <table class="table-style">
              <tbody>
                <tr>
                  <td colspan="2" class="addicon">
                    <span class="clgray">アイコンがついているものがedutoss上で公開されます。</span>
                  </td>
                </tr>
                <tr>
                  <th>氏名</th>
                  <td>
                    <input maxlength="20" class="form-control" type="text" pattern="^\S+$" name="last_name" data-label="氏名" value="{{ old('last_name') }}" placeholder="お名前太郎" required>
                  </td>
                </tr>
                <tr>
                  <th>ふりがな</th>
                  <td>
                    <input maxlength="20" class="form-control" type="text" pattern="^[ぁ-んァ-ン　]+$" name="kana" data-label="ふりがな" value="{{ old('kana') }}" placeholder="おなまえたろう" required>
                  </td>
                </tr>
                <tr>
                  <th>生年月日</th>
                  <td>
                    <div class="block3 grid3item">
                      <div class="block3-ins">

                        <!-- <div> -->
                        <!-- class="calendar-input" -->
                        <!-- <input class="form-control" name="birthday['year']" data-label="生年月日(年)" type="text" placeholder=""  required > -->
                        <!-- </div><span>年</span> -->

                        <select class="form-control" name="birthday['year']" required>
                          <option value="0">年を選択</option>
                          @for( $i = date('Y') - 18; $i >= 1950; $i-- )
                          <option value="{{ $i }}" {{ old("birthday['year']") == $i ? 'checked' : null }}>{{ $i }}</option>
                          @endfor
                        </select>

                        <span>年</span>
                      </div>

                      <div class="block3-ins">
                        <!-- <div>
                                        <input class="form-control" name="birthday['month']" type="text"  data-label="生年月日(月)" placeholder="" >
                                      </div> -->
                        <select class="form-control" name="birthday['month']" required>
                          <option value="0">月を選択</option>
                          @for( $i = 1; $i <= 12; $i++ ) <option value="{{ $i }}" {{ old("birthday['month']") == $i ? 'checked' :  null }}>{{ $i }}</option>
                            @endfor
                        </select>

                        <span>月</span>
                      </div>

                      <div class="block3-ins">
                        <!-- <div>
                                        <input class="form-control" name="birthday['day']" type="text"  data-label="生年月日(日)" placeholder="" >
                                      </div> -->
                        <select class="form-control" name="birthday['day']" required>
                          <option value="0">日を選択</option>
                          @for( $i = 1; $i <= 31; $i++ ) <option value="{{ $i }}" {{ old("birthday['day']") == $i ? 'checked' :  null }}>{{ $i }}</option>
                            @endfor
                        </select>
                        <span>日</span>
                      </div>
                    </div>
                  </td>
                </tr>
                <tr>
                  <th>性別</th>
                  <td>
                    <select class="select form-control" name="sex" data-label="性別">

                      @foreach( EUser::GENDER as $key => $va )
                      <option value="{{ $key }}" {{ old('sex') == $key ? 'checked' : null }}>{{ $va }}</option>
                      @endforeach
                      <!-- <option value="male" {{ old('sex') == 'male' ? 'checked' : null }} >男性</option>
                                    <option value="female" {{ old('sex') == 'female' ? 'checked' : null }}  >女性</option>
                                    <option value="others" {{ old('sex') == 'others' ? 'checked' : null }}  >その他</option> -->
                    </select>
                  </td>
                </tr>

                <tr>
                  <th class="addicon">最終学歴</th>
                  <td id="school_plan">
                    <!-- <select class="select" name="university_Code"  data-label="最終学歴"  require>
                                      <option value="">最終学歴を選択</option>
                                  </select>  -->
                  </td>
                </tr>

                <tr>
                  <th>学部</th>
                  <td>
                    <select class="select" name="faculty_code" data-label="学部" require>
                      <option value="">学部を選択</option>
                    </select>
                  </td>
                </tr>
                <!-- <tr>
                                <th>学部</th>
                                <td>
                                  <select class="select" name="undergraduate" data-label="学部" >
                                    <option value="1" {{ old('undergraduate') == 1 ? 'checked' : null }} >選択してください</option>
                                    <option value="2" {{ old('undergraduate') == 2 ? 'checked' : null }}>選択してください</option>
                                  </select>
                                </td>
                              </tr> -->

                <tr>
                  <th>在学/卒業</th>
                  <td>

                    @foreach( EUser::EDU_STATUS as $_key => $_value )
                    <label class="mr50 customck">
                      <input type="radio" id="men" name="edu_status" value="{{ $_value }}" {{( old('edu_status') == $_value ) ||  ( ! old('edu_status') && $_key == 0  ) ? 'checked' : null  }} require>
                      <span for="men" class="checkmark">{{ $_value }}</span>
                    </label>
                    @endforeach

                    <!-- <label class="customck">
                                    <input type="radio" id="women" name="edu_status" value="graduated" require>
                                    <span for="women" class="checkmark">卒業</span>
                                  </label> -->

                  </td>
                </tr>
                <!-- <tr>
                                <th>学科</th>
                                <td>
                                  <select class="select" name="subject_study" data-label="学部" >
                                    <option value="1" {{ old('subject_study') == 1 ? 'checked' : null }} >選択してください</option>
                                    <option value="2" {{ old('subject_study') == 2 ? 'checked' : null }} >選択してください</option>
                                  </select>
                                </td>
                              </tr> -->
                <tr>
                  <th>電話番号</th>
                  <td>
                    <input class="form-control" type="text" name="tel" value="{{ old('tel') }}" pattern="([0-9]{10,11})|([0-9]{3}-[0-9]{3}-[0-9]{4})" placeholder="09012345678" maxlength="11" minlength="10" required>
                  </td>
                </tr>
                <tr>
                  <th class="addicon">自己紹介</th>
                  <td>
                    <textarea class="form-control" name="introduction" maxlength="255" cols="30" rows="5" required>{{ old('introduction') }}</textarea>
                  </td>
                </tr>
                <tr>
                  <th>学生証</th>
                  <td>
                    <div class="box-upload">
                      <figure><img src=" {{ asset('register/common_img/icon-card.png') }} " alt="アップロード"></figure>
                      <span onclick='$("#file-upload").trigger("click");' class="btn-upload"><span>アップロード</span></span>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>

            <div class="btn-wrapper">
              <button type="submit" class="btn btn-lightgreen">次へ</button>
            </div>
          </form>
        </div>
        <!-- slick 3 -->
        <div class="item select-courses">
          <form method="post" onsubmit="return nextFrom(event)">
            <div class="opt-tit">
              <h2 class="text_center title">受諾する科目選択</h2><span>※複数選択可能</span>
            </div>
            <ul class="list-courses">
              @foreach($subject::all() as $key => $row)
              <li>
                <input type="checkbox" class="form-control" id="check{{ $key }}" value="{{ $row->id }}" name="subject[]" {{  old('subject') && in_array( $row->id, old('subject')) ? 'checked' : null }}>
                <div><img src=" {{ asset($row->icon) }} " alt=""><span>{!! $row->name !!}</span></div>
              </li>
              @endforeach
            </ul>
            <div class="btn-wrapper">
              <button type="submit" class="btn btn-lightgreen">次へ</button>
            </div>
          </form>
        </div>
        <!-- slick 4 -->
        <div class="item info-setting">
          <div class="opt-tit">
            <h2 class="text_center title">口座情報設定</h2>
          </div>
          <form method="post" id="form-submit">
            <div class="info-setting-ins">
              <div class="grid2item">
                <dl>
                  <dt>金融機関コード</dt>
                  <dd>
                    <input class="form-control" type="text" name="bank_code" pattern="[0-9]{4}" value="{{ old('bank_code') }}" required>
                  </dd>
                </dl>
                <dl>
                  <dt>支店コード</dt>
                  <dd>
                    <input class="form-control" type="text" name="branch_code" pattern="[0-9]{3}" value="{{ old('branch_code') }}" required>
                  </dd>
                </dl>
              </div>
              <div class="info-block">
                <dl>
                  <dt>口座番号</dt>
                  <dd>
                    <input class="form-control" type="text" name="bank_account_number" value="{{ old('bank_account_number') }}" pattern="[0-9]{7}" required>
                  </dd>
                </dl>
                <dl>
                  <dt>口座名義人</dt>
                  <dd>
                    <input class="form-control" pattern="^([ァ-ン（）]|ー)+$" type="text" name="bank_account_name" value="{{ old('bank_account_name') }}" required>
                  </dd>
                </dl>
                <dl>
                  <dt>預金種目</dt>
                  <dd>
                    <ul class="list-courses">
                      <li class="active">
                        <input type="radio" name="bank_account_type" id="check0" value="1" {{ old('bank_account_type') == 1 ? 'checked' : null }} required>
                        <div><img src=" {{ asset('register/images/icon-notbook.png') }} " alt=""><span>普通</span></div>
                      </li>
                      <li>
                        <input type="radio" name="bank_account_type" id="check1" value="2" {{ old('bank_account_type') == 2 ? 'checked' : null }} required>
                        <div><img src=" {{ asset('register/images/icon-notbook.png') }} " alt=""><span>当座</span></div>
                      </li>
                    </ul>
                  </dd>
                </dl>
              </div>
              <div class="btn-wrapper">
                <button type="submit" class="btn btn-lightgreen">次へ</button>
              </div>
            </div>
            <form>
        </div>
      </div>

    </div>
  </div>
</div>
@endsection

@section('custom_js')
<script src="{{ asset('register/js/slick.min.js') }}"></script>
<script>

      $('a.btn-back').on('click',  (e) => {
         _index = $('div.slick-active').data('slick-index');
         if( !_index )
          $(e.target).hide();
      });

      $('a.btn-back').hide();

      $('.slick-slider').slick({
        centerMode: true,
        focusOnSelect: true,
        accessibility: false,
        centerPadding: '0px',
        slidesToShow: 5,
        arrows: false,
        autoplay: true,
        autoplaySpeed: 0,
        adaptiveHeight: true,
        cssEase: 'linear',
        speed: 5000,
        responsive: [{
          breakpoint: 960,
          settings: {
            centerMode: true,
            centerPadding: '0px',
            slidesToShow: 5
          }
        }, {
          breakpoint: 768,
          settings: {
            centerMode: true,
            centerPadding: '0px',
            slidesToShow: 3
          }
        }]
      });
    </script>

<!-- Structured data settings-->
    <script src="{{ asset('register/js/slick.min.js') }}"></script>

    <script src="https://js.stripe.com/v3/"></script>

    <script>
      const _no_record = "{{ trans('common.no_record') }}";

      let error = "";

      let _token = "{{ csrf_token() }}";

      let stripe = Stripe("{{ env('STRIPE_KEY') }}",{
        locale: 'ja'
      });

    let error_code = {
              'bank_account[routing_number]' : '金融コードまた支店コードが無効です',
              'bank_account[account_holder_name]' : '口座名が無効です',
              'bank_account[account_number]' : '口座番号が無効です',
            };

  $(document).ready(function(){
        getUniversity();

        $(".regist-slide").slick({
          dots: true,
          infinite: true,
          centerMode: false,
          slidesToShow: 1,
          slidesToScroll: 1,
          autoplay: false,
          arrows: false,
          adaptiveHeight: true,
          draggable: false,
          swipe: false

        });

        $('.form-control').change(function(e){
            let nodeName = $(this)[0].nodeName.toLowerCase();
            $(`${nodeName}[name=${$(this).attr('name')}]`).val($(this).val());
        })


         // validate file
         $('#file-upload').change( async function(value){

                var ext = $(this).val().split('.').pop();

                if( jQuery.inArray( ext, _ip_ext ) !== -1 )
                  await convertHeicToJpg(value);

                // if(!(jQuery.inArray(ext,['pdf','jpg','png']) !== -1)){
                //     $(this).val('');
                //     alert('PDF, PNG, JPEG, のみ添付可能です');
                //     return false;
                // }

                let file = $(this)[0].files[0];



                // if( file.size>10000000 )
                //  alert('・10MB以内の画像をアップロードしてください。');

                if(file){

                  if(!(jQuery.inArray(ext, extension ) !== -1)){
                    $(this).val('');
                    alert(`${extension.join(', ')} のみ添付可能です`);
                    return false;
                  }

                  if( file.size>10000000 ){
                      alert('・10MB以内の画像をアップロードしてください。');
                      $(this).val('');
                      return false;
                  }

                  if( file.type.split('/')[0] != 'image'){
                      alert('イメージのみ添付可能です。');
                      return false;
                  }

                  // name = file.name;
                  // name = name.length>15?name.substring(0,15)+'...':name;
                  // $('span.btn-upload').html(`<span>${name}</span>`);

                  let reader = new FileReader();
                  reader.readAsDataURL(file);
                  reader.onload = function(reader){
                      $('div.box-upload figure img').hide();
                      //.attr('src',reader.srcElement.result);
                      $('div.box-upload').css({'background-image':`url(${reader.srcElement.result})`,'background-repeat':'no-repeat','background-position':'center','background-size':'100%'});
                      return false;
                  }

                }

                $('div.box-upload').css({'background':'#F9F9FB','max-width':'423px','padding':'30px 15px'});
        })


        $('.slick-dots li button').on('click', function(e){
                e.stopPropagation();
        });
  });

//form-submit  school_plan

async function getUniversity(){

    const _repose = new Promise((resolve, reject) => { $.post( window.location.origin + '/getUniversity', { _method: 'PUT', _token: _token }, resolve); })
    const _return = await _repose;

            _option = '<option value="" ></option>';

              $.each( _return, (_idx,_val) => {
                // act = '';
                _option += `<option value="${ _val.code }">${ _val.name }</option>`;
                  // _li += `<li rel="${ _val.code }" >${ _val.name }</li>`;
              });

            _select = `<select name="university_Code" onChange="check_inst(event)"> ${_option} </select>`;

            $('#school_plan').append( _select );

            // $('select[name="university_Code"]').select2({ formatNoMatches: _no_record, width: 'element'  } );

            $('select[name="university_Code"]').select2({
                "language": {
                      "noResults": () => (_no_record)
                },
                width: 'element',
                allowClear: true ,
                placeholder: '選択してください。'
            });

            // $( $('select[name="university_Code"]').parent().find('ul.select-options li') ).click( (e) => {

            //           _id =  $(e.target).attr('rel');
            //           if( !_id )
            //             return false;

            //           $('select[name="university_Code"]').val(_id);

            //           $('select[name="university_Code"]').parent().find('ul.select-options li').removeClass('active');
            //           $(e.target).addClass('active');
            //           $(e.target).parent().parent().children('div.select-styled').text( $(e.target).text() );
            //           $('ul.select-options').toggle()

            //           getFaculty(_id);
            //   })
}

check_inst = (e) => {
  getFaculty( e.target.value );
}

</script>
<script>

// Validate file

  $('#file-upload-avatar').change( async function(value){

  var ext = $(this).val().split('.').pop();

  if( jQuery.inArray( ext, _ip_ext ) !== -1 )
      await convertHeicToJpg(value);

  // if(!(jQuery.inArray(ext,['pdf','jpg','png']) !== -1)){
  //     $(this).val('');
  //     alert('PDF, PNG, JPEG, のみ添付可能です');
  //     return false;
  // }

  let file = $(this)[0].files[0];



    if(file){

      if(!(jQuery.inArray(ext, extension ) !== -1)){
                    $(this).val('');
                    alert(`${extension.join(', ')} のみ添付可能です`);
                    return false;
      }

      if( file.size>10000000 )
      {
        alert('・10MB以内の画像をアップロードしてください。');
        return false;
      }

      if( file.type.split('/')[0] != 'image'){
          alert('イメージのみ添付可能です。');
          return false;
      }

      let reader = new FileReader();
      reader.readAsDataURL(file);
      reader.onload = function(reader){
            $('figure.avatar-img-up img').attr('src',reader.srcElement.result);
            return false;
      }
      //.regist-info .box-upload
    }

    $('figure.avatar-img-up img').attr('src', window.location.origin + '/register/images/user.png');

})

  async function getFaculty(_id){

    const _repose = new Promise((resolve, reject) => { $.post( window.location.origin + '/getFaculty', { _method: 'PUT', _token: _token, id:_id  }, resolve); })
    const _return = await _repose;

          _text_defult = '学部を選択';
          _option = `<option value="">${_text_defult}</option>`;
          _li = `<li class="active" onClick="clickActive(event)" rel="">${_text_defult}</li>`;

          $.each( _return, (_idx,_val) => {
            act = '';
            _option += `<option value="${ _val.faculty_code }">${ _val.faculty_name }</option>`;
              _li += `<li onClick="clickActive(event)" rel="${ _val.faculty_code }" >${ _val.faculty_name }</li>`;
          });

          _select = $('select[name="faculty_code"]');
          $(_select).html(_option);
          $( $(_select).parent().find('div.select-styled') ).html(_text_defult);
          $( $(_select).parent().find('ul.select-options') ).html(_li);



}

function clickActive(e){

      e.stopPropagation();

      _e =  e.target;
      _rel = $(_e).attr('rel');
      _div = $(_e).parent().parent();


      $( $(_div).find('div.select-styled') ).text( $(_e).text() );

      $( $(_div).find('select') ).val( _rel ) ;

      $( $(_div).find('ul.select-options li') ).removeClass('active');

      $( $(_div).find(`ul.select-options li[rel=${_rel}]`) ).addClass('active');

      $( $(_div).find('ul.select-options') ).hide();

      //$styledSelect.text($(this).text()).removeClass('active');
      // $this.val($(this).attr('rel'));
      // $list.hide();
      // $(this).parent().find('li').removeClass('active');
      // $(this).addClass('active');
};

</script>

<script src="{{ asset('register/teacher/validation.js') }}"></script>
@endsection