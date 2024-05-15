@extends('layouts.admin.index')

@section('body_class','p-admin')

@section('custom_title')
<a class="btn btn-success" href="{{ route('admin.notificationDelivery.add') }}" >新規作成</a>
@endsection

@section('content')
<div class="table-style mt00 tableheadgreen">
    <table>
      <thead>
      <form method="post" id="form-submit" action="{{ URL::current() }}">
        @csrf
        <tr>
          <th>送信日時</th>
          <th>お知らせタイトル</th>
          <th class="nowrap">
            <div class="select-box">
              <select name="eSign_destination" class="form-control">
                <option value="">お知らせ先</option>
                <option value="all" {{ isset($eSign_destination) && $eSign_destination == 'all' ? 'selected' : null }} >すべての会員に公開</option>
                <option value="teacher" {{ isset($eSign_destination) && $eSign_destination == 'teacher' ? 'selected' : null }} >講師のみ</option>
                <option value="student" {{ isset($eSign_destination) && $eSign_destination == 'student' ? 'selected' : null }} >生徒のみ</option>
              </select>
            </div>
          </th>
          <th>ステータス</th>
          <th>アクション</th>
        </tr>
      </form>
      </thead>
      <tbody>
      @foreach($item as $key => $row)
        <tr>
          <td>{{ $row->start_at->format('Y年m月d日 H:i') }} </td>
          <td>{{ $row->title }}</td>
          <td>{{ $row->desti }}</td>
          <td><span class="linkpage clmain" href="#">{{ $row->status == 'sent' ? '送信済み' : '未送信'  }} </span></td>
          <td>
            <div class="fcc"><a class="btn btn-primary btn-custom mr10" href="{{ route('admin.notificationDelivery.edit',["id" => $row->id]) }}">編集</a>
              <button class="btn btn-danger btn-custom" onClick="showPoupDelete({{ $row->id }})" >削除</button>
            </div>
          </td>
        </tr>
      @endforeach
      </tbody>
    </table>
</div>
{!! $item->appends(request()->input())->links('component.admin.pager') !!}

@endsection

@section('custom_js')
<script src="{{ asset('js/form.js') }}"></script>
<script>
  let URI_DELETE = @JSON(route('admin.notificationDelivery.delete'));
  let token = "{{ csrf_token() }}";
</script>
@endsection