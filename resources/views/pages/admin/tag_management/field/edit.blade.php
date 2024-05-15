@inject('subject', 'App\Models\Subject')

@extends('layouts.admin.index')

@section('body_class','p-tag detail')
@section('class_main','p-content-tag detail')

<!-- section('custom_title')
<button class="btn btn-success">新規作成</button>
endsection -->

@section('content')
<div class="table-style mt00 table-shawdow">
  <form method="post" id="form-submit">
      @csrf
                <table>
                  <tbody>
                    <tr>
                      <th>科目</th>
                      <td>

                        <div class="select-box">
                          <select name="subject_id" class="form-control">
                              <option>---------------</option>
                            @foreach($subject::all() as $key => $row)
                              <option value="{{ $row->id }}" {{ $item->subject_id == $row->id ? 'selected' : null  }} >{!! $row->text_name !!}</option>
                            @endforeach
                          </select>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <th>タグ名</th>
                      <td>
                        <input name="name" class="form-control" type="text" maxlength="20" value="{{ $item->name }}" required>
                        <div class="choose-subject mt30" id="active">
                          <div data-id="1" class="item {{ $item->active == 1  ? 'active' : null  }}" >表示</div>
                          <div data-id="0" class="item {{ $item->active == 0  ? 'active' : null  }}">非表示</div>
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
                <div class="fec mt20">
                  <button class="btn btn-primary btn-custom mr10" >登録</button>
                  <a class="btn btn-danger btn-custom" href="{{ route('admin.tagManagement.list') }}">戻る</a>
                </div>
    </form>
  </div>
@endsection

@section('custom_js')
<script>
$( document ).ready(function(){
    $('div.select-styled').text($('select[name="subject_id"] option[selected]').text())
})
//select-styled
 var form = document.getElementById('form-submit');

form.addEventListener('submit', function(event){

  event.preventDefault();
  var active = $('#active div.active').data('id');

  $('#form-submit').append($('<input>',{name:'active', type:'hidden',value: active  }));
  $('#form-submit').submit();

})

</script>
@endsection