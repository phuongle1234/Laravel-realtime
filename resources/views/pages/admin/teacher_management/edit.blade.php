@inject('subject', 'App\Models\Subject')
@inject('schoolMaster', 'App\Models\SchoolMaster')

@extends('layouts.admin.index')

@section('body_class','p-admin')

@section('custom_css')
  <script src="{{ asset('js/datepicker-last/js/bootstrap-datepicker.min.js') }}"></script>
  <script src="{{ asset('js/datepicker-last/css/bootstrap-datepicker.min.css') }}"></script>
  <link rel="stylesheet" href="{{ asset('js/datepicker-last/css/bootstrap-datepicker3.min.css') }}">
  <script src="{{ asset('js/datepicker-last/locales/bootstrap-datepicker.ja.min.js') }}"></script>
@endsection

@section('content')
<form method="post">
  @csrf
  @method('PUT')
  <div class="table-style mt00 table-shawdow">
  <table>
                  <tbody>
                    <tr>
                      <th>登録日時</th>
                      <td>
                        <input class="disable form-control"  type="text" maxlength="30" value="{{ $item->created_at->format('Y年m月d日 h:i') }}" >
                      </td>
                    </tr>
                    <tr>
                      <th>講師ID</th>
                      <td>
                        <input class="disable form-control" type="text" maxlength="30" value="{{ $item->code }}">
                      </td>
                    </tr>
                    <tr>
                      <th>ユーザー名</th>
                      <td>
                        <input class="form-control" type="text" name="name" maxlength="30" value="{{ $item->name }}">
                      </td>
                    </tr>
                    <!-- <tr>
                      <th>氏名</th>
                      <td>
                        <input class="form-control" type="text" name="first_name" require maxlength="30" value="{{ $item->last_name }}{{ $item->first_name }}">
                      </td>
                    </tr> -->
                    <tr>
                      <th>ふりがな</th>
                      <td>
                        <input class="form-control" type="text" name="kana" required maxlength="30" value="{{ $item->kana }}">
                      </td>
                    </tr>
                    <tr>
                      <th>生年月日</th>

                      <td>
                        <input class="form-control" type="text" id="datetime" required  name="birthday" maxlength="30" value="{{  date('Y-m-d',strtotime($item->birthday)) }}">
                      </td>

                    </tr>
                    <tr>
                      <th>性別</th>
                      <td>

                        <!-- <input class="form-control" type="text" name="gender" maxlength="30" value="{{ $item->gender }}"> -->

                        <div class="select-box">
                            <select name="sex" disabled require>
                              <option value="{{ $item->sex }}" selected >{{  EUser::GENDER[ $item->sex ] }}</option>
                            </select>
                        </div>

                      </td>
                    </tr>
                    <tr>
                      <th>大学名</th>
                      <td>
                        <select class="select" name="university_Code"  data-label="最終学歴" data-embed="false" require>
                          <option value="">最終学歴を選択</option>
                        @foreach( $schoolMaster->university() as $_key => $val )
                          <option value="{{ $val->code }}" {{  $item->university_Code == $val->code ? 'selected' : null }} >{{ $val->name }}</option>
                        @endforeach
                      </select>
                      </td>
                    </tr>

                    <tr>
                      <th>学部</th>
                      <td>

                        <select class="select" name="faculty_code"  data-label="学部"  require>
                          <option value="" >学部を選択</option>

                          @if($item->faculty_code)
                          <option id='' value="{{$item->faculty_code}}" selected >{{ @$item->faculty($item->faculty_code)->first()->faculty_name}}</option>
                          @endif
                        </select>
                                           </td>
                    </tr>
                    <tr>
                      <th>在学/卒業</th>
                      <td>
                      @foreach( EUser::EDU_STATUS as $_key => $_value )
                            <label class="mr50 customck">
                              <input type="radio" id="men" name="edu_status" value="{{ $_value }}" {{ ( $_value == $item->edu_status ) || ( ! in_array( $item->edu_status , EUser::EDU_STATUS) &&  $_key == 1 ) ? 'checked' : null }} require>
                              <span for="men" class="checkmark">{{ $_value }}</span>
                            </label>
                      @endforeach
                      </td>
                    </tr>

                    <tr>
                      <th>e-mail</th>
                      <td>
                        <input class="form-control" type="email" required name="email" maxlength="30" value="{{ $item->email }}">
                      </td>
                    </tr>
                    <tr>
                      <th>電話番号</th>
                      <td>
                        <input class="form-control" type="tel" required name="tel" maxlength="30" value="{{ $item->tel }}">
                      </td>
                    </tr>
                    <tr>
                      <th>科目</th>
                      <td>

                        <div class="list-courses">
                        @foreach($subject::all() as $_key => $_row)
                          <li>
                            <input id="check1" name="subject[]"  type="checkbox" {{ in_array($_row->id,$item->subject_array) ? 'checked' : null }} value="{{ $_row->id }}">
                            <div>
                              <img src="{{ asset($_row->icon) }}" >
                              <pan>{!! $_row->name !!}</pan>
                            </div>
                          </li>
                        @endforeach
                        </div>

                      </td>
                    </tr>
                  </tbody>
                </table>
    </div>
    <div class="green-box">
      <div class="green-box-ins">
        <figure><img src="{{ $item->avatar_img }}" alt="画像"></figure>
        <figcaption>画像</figcaption>
      </div>
      <div class="green-box-ins">
        <div class="table-style">

          <table>
            <tbody>
              <tr>
                <th>電話番号</th>
                <td>
                  <textarea class="form-control disable" rows="4" cols="50">{!! $item->introduction !!}</textarea>
                </td>
              </tr>
              <tr>

                <th>動画投稿数</th>
                <td>
                  <input class="form-control disable" type="text" value="{{ $item->videos_count }}">
                </td>
              </tr>
              <tr>
                <th>指名リクエスト数</th>
                <td>
                  <input class="form-control disable" type="text" value="{{ $item->nomination_requests_count }}">
                </td>
              </tr>
              <tr>
                <th>ステータス</th>
                <td>

                    <div class="list-courses list-courses-radio">
                            <li>
                              <input type="radio" name="status" value="active" id="check1" {{ $item->status == EStatus::ACTIVE ? 'checked' : null }}>
                              <div><span>有効</span></div>
                            </li>
                            <li>
                              <input type="radio" name="status" value="denied" id="check2" {{ $item->status == EStatus::DENIED ? 'checked' : null }}>
                              <div><span>無効</span></div>
                            </li>
                      </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="fec">
          <button class="btn btn-primary btn-custom mr10" href="#">登録</button>
          <a class="btn btn-danger btn-custom" href="{{ route('admin.teacherManagement.list') }}">戻る</a></div>
      </div>
    </div>
</form>
@endsection

@section('custom_js')
<script>
        // let _token = "{{ csrf_token() }}";
  $('#datetime').datepicker({
      language: "ja",
      autoclose: true,
      format: 'yyyy-mm-dd',
      todayHighlight: true,
  });

      $(document).ready(function(){
        $("body").on('click', '.toggle-password', function() {
          $(this).toggleClass("fa-eye fa-eye-slash");
          var input = $(".pass_log_id");
          if (input.attr("type") === "password") {
            input.attr("type", "text");
          } else {
            input.attr("type", "password");
          }
        });
      });

  $('select[name="university_Code"]').select2({
      "language": {
          "noResults": () => (_no_record)
      },
      width: 'element',
      // allowClear: true,
      placeholder: '選択してください。'
  }).on('select2:select', function (e) {
      getFaculty(e.target.value);
  });

      // $( $('select[name="university_Code"]').parent().find('ul.select-options li') ).click( (e) => {
      //         _id =  $(e.target).attr('rel');

      //         console.log( $('select[name="university_Code"]').val(), _id );

      //         getFaculty(_id);
      //   })

function getFaculty(_id){

      $.post( window.location.origin + '/getFaculty', { _method: 'PUT', _token: _token, id:_id  }, (e)=>{

          _option = '<option value="">学部を選択</option>';
          _li = '';

          $.each( e, (_idx,_val) => {

            act = '';

            _option += `<option value="${ _val.faculty_code }">${ _val.faculty_name }</option>`;

            if(_idx == 0){
              act = 'class="active"';
              _li_act = _val.faculty_name;
            }
              _li += `<li onClick="clickActive(event)" rel="${ _val.faculty_code }" ${act} >${ _val.faculty_name }</li>`;
          });

          _select = $('select[name="faculty_code"]');
          $(_select).html(_option);
          $( $(_select).parent().find('div.select-styled') ).html(_li_act);
          $( $(_select).parent().find('ul.select-options') ).html(_li);

      });

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
}

</script>
@endsection