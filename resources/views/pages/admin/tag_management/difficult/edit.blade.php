@inject('subject', 'App\Models\Subject')

@extends('layouts.admin.index')

@section('body_class','p-admin')

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
                      <th>タグ名</th>
                      <td>
                        <input class="form-control" type="text" maxlength="20" name="name" value="{{ $item->name }}" required>
                      </td>
                    </tr>
                    <tr>
                      <th>ステータス</th>
                      <td>
                        <div class="choose-subject" id="active" >
                          <div data-id="1" class="item {{ $item->active == 1  ? 'active' : null  }}">表示</div>
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

 var form = document.getElementById('form-submit');

form.addEventListener('submit', function(event){

  event.preventDefault();
  var active = $('#active div.active').data('id');

  $('#form-submit').append($('<input>',{name:'active', type:'hidden',value: active  }));
  $('#form-submit').submit();

})

</script>
@endsection