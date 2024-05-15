@inject('nanageNotificationTemplate', 'App\Models\ManageNotificationTemplate')

@extends('layouts.admin.index')

@section('body_class','p-admin')

@section('custom_css')
<link rel="stylesheet" href="{{ asset('js/datetimepicker/jquery.datetimepicker.css') }}">
<link rel="stylesheet" href="{{ asset('js/datetimepicker/jquery.datetimepicker.min.css') }}">
@endsection

@section('content')
<div class="table-style mt00 table-shawdow">
      <form method="post" id="form-submit">
        @csrf
        @method('PUT')
                <table>
                  <tbody>
                    <tr>
                      <th>送信日時</th>
                      <td>
                        <div class="calendar-input calendar-input-right">
                       <!-- old('start_at') -->
                          <input class="form-control" name="start_at" id="datetime" type="text" value="{{ $item->start_at->format('Y-m-d H:i') }}" >
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <th>テンプレート</th>
                      <td>
                        <div class="select-box">
                          <select class="form-control" id="temp">
                            <option value="">-------------</option>
                            @foreach($nanageNotificationTemplate::where('display',1)->get() as $key => $_row)
                              <option value="{{ $_row->id }}"  data-value="{{ json_encode($_row) }}" > {{ $_row->name }} </option>
                            @endforeach
                          </select>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <th>タイトル</th>
                      <td>
                        <input class="form-control" type="text" name="title" value="{{ $item->title }}" placeholder="誰でもわかる三角関数" maxlength="100">
                      </td>
                    </tr>
                    <tr>
                      <th>詳細</th>
                      <td>
                        <textarea class="form-control" name="content" cols="30" rows="6" value="{{ old('content') }}" maxlength="1000">{{ $item->content }}</textarea>
                      </td>
                    </tr>
                    <tr>
                      <th>お知らせ先</th>
                      <td>
                        <div class="choose-subject" id="destination">
                          <div class="item disable-btn {{ $item->destination == 'all' ? 'active' : null  }}" data-id="all" >すべての会員に公開</div>
                          <div class="item disable-btn {{ $item->destination == 'teacher' ? 'active' : null  }}" data-id="teacher" >講師のみ</div>
                          <div class="item disable-btn {{ $item->destination == 'student' ? 'active' : null  }}" data-id="student" >生徒のみ</div>
                        </div>

                      </td>
                    </tr>
                  </tbody>
                </table>
                <div class="fec">
                  <button type="submit" class="btn btn-primary btn-custom mr10" >登録</button>
                  <a class="btn btn-danger btn-custom" href="{{ route('admin.notificationDelivery.list') }}">戻る</a>
                </div>
        </form>
    </div>
@endsection

@section('custom_js')
<script src="{{ asset('js/datetimepicker/jquery.js') }}"></script>
<script src="{{ asset('js/datetimepicker/jquery.datetimepicker.full.js') }}"></script>

<script>


 $(function () {

      $('#datetime').datetimepicker({
          format:'Y-m-d H:i',
          step:1,
          autoclose: false,
      });
      $.datetimepicker.setLocale('ja');

      $('ul.select-options li').click(function(){
          let id = $(this).attr('rel');
          obj = $(`select#temp option[value=${id}]`).data('value');
          $('input[name=title]').val(obj.title);
          $('textarea[name=content]').val(obj.content);
          $('#destination div.item').removeClass('active');
          $(`#destination div.item[data-id=${obj.destination}]`).addClass('active');
          //$('input[name=title]').val(obj.title);
      })
  });

 var form = document.getElementById('form-submit');

form.addEventListener('submit', function(event){

  event.preventDefault();
  var destination = $('#destination div.active').data('id');

  $('#form-submit').append($('<input>',{name:'destination', type:'hidden',value: destination  }));

  $('#form-submit').submit();

})

</script>
@endsection