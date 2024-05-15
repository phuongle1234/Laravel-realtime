@inject('subject', 'App\Models\Subject')

@extends('layouts.admin.index')

@section('body_class','p-admin')

@section('custom_title')
<button class="btn btn-success">新規作成</button>
@endsection

@section('content')
<div class="table-style mt00 table-shawdow">
    <form method="post" id="form-submit">
    @method('PUT');
    @csrf
          <table>
                  <tbody>
                    <tr>
                      <th>テンプレート名</th>
                      <td>
                        <input class="form-control" type="text" name="name" maxlength="100" value="{{ $item->name }}">
                      </td>
                    </tr>
                    <tr>
                      <th>タイトル</th>
                      <td>
                        <input class="form-control" type="text" name="title" maxlength="100" value="{{ $item->title }}">
                      </td>
                    </tr>
                    <tr>
                      <th>詳細</th>
                      <td>
                        <textarea class="form-control" name="content" cols="30" rows="7" maxlength="1000">{{ $item->content }}</textarea>
                      </td>
                    </tr>
                    <tr>
                      <th>お知らせ先</th>
                      <td>
                        <div class="choose-subject" id="destination">
                          <div data-id="all" class="item {{ $item->destination == 'all' ? 'active' : null }}">すべての会員に公開</div>
                          <div data-id="teacher" class="item {{ $item->destination == 'teacher' ? 'active' : null }}">講師のみ</div>
                          <div data-id="student" class="item {{ $item->destination == 'student' ? 'active' : null }}">生徒のみ</div>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <th>表示ステータス</th>
                      <td>
                        <div class="choose-subject" id="display">
                          <div data-id="1" class="item {{ $item->display == 1 ? 'active' : null }}">表示</div>
                          <div data-id="0" class="item {{ $item->display == 0 ? 'active' : null }}">非表示</div>
                        </div>
                      </td>
                    </tr>
                  </tbody>
            </table>
            <div class="fec">
              <button class="btn btn-primary btn-custom mr10" href="#">登録</button>
              <a class="btn btn-danger btn-custom" href="{{ route('admin.notificationManagement.list') }}">戻る</a></div>
      </form>
      </div>
@endsection

@section('custom_js')
<script>
 var form = document.getElementById('form-submit');

form.addEventListener('submit', function(event){

  event.preventDefault();
  var destination = $('#destination div.active').data('id');
  var display = $('#display div.active').data('id');

  $('#form-submit').append($('<input>',{name:'destination', type:'hidden',value: destination  }));
  $('#form-submit').append($('<input>',{name:'display', type:'hidden',value: display  }));

  $('#form-submit').submit();

})

</script>
@endsection