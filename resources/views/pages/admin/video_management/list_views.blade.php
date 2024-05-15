@extends('layouts.admin.index')
@section('body_class','p-video list-views')
@section('class_main','p-video list-views')

@section('custom_css')
<script src="{{ asset('../js/datepicker-last/js/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('../js/datepicker-last/css/bootstrap-datepicker.min.css') }}"></script>
<link rel="stylesheet" href="{{ asset('../js/datepicker-last/css/bootstrap-datepicker3.min.css') }}">
<script src="{{ asset('../js/datepicker-last/locales/bootstrap-datepicker.ja.min.js') }}"></script>
@endsection

@section('custom_title')
&nbsp;&nbsp;
<button class="btn btn-img btn-dark" onclick="exportCsv()"><img src="{{ asset('common_img/csv.svg') }}" alt="CSV エクスポート" >
  <span>
    <font style="vertical-align: inherit;">
      <font style="vertical-align: inherit;">CSV エクスポート</font>
    </font>
  </span>
</button>
@endsection

@section('content')

<form method="post" id="form-submit" action="{{ route('admin.video_management.list_views') }}" >
@csrf
<div class="bg-green csv-box">

    <div class="search-input">
      <input class="form-control" type="text" name="key_work" placeholder="フリーワード検索" maxlength="20" value="{{ isset($key_work) ? $key_work : null }}">
      <button class="search-icon"><img src="{{ asset('common_img/icon-search.svg') }}" alt="search"></button>
    </div>

    <div class="calendar-group">
      <div class="calendar-input">
        <input class="form-control" name="from_date" type="text" value="{{ $from_date }}">
      </div>
      <div class="calendar-input">
        <input class="form-control" name="to_date" type="text" value="{{ $to_date }}">
      </div>
    </div>
    <button type="submit" class="btn btn-dark" type="button">検索</button>

    <div class="select-box" >
      <select class="form-control" name="order_by" >
        <option value="" >-------------------</option>
        <option value="views|DESC" {{ isset($order_by) && $order_by == 'views|DESC' ? 'selected' : null }}>視聴回数が多い順</option>
        <option value="views|ASC" {{ isset($order_by) && $order_by == 'views|ASC' ? 'selected' : null }} >視聴回数が少ない順</option>
        <option value="likes|DESC" {{ isset($order_by) && $order_by == 'likes|DESC' ? 'selected' : null }} >高評価数が多い順</option>
        <option value="likes|ASC" {{ isset($order_by) && $order_by == 'likes|ASC' ? 'selected' : null }} >高評価数が少ない順</option>
      </select>
    </div>

</div>
</form>

<h2 class="tit-page">再生回数一覧</h2>
<div class="table-style mt00 tableheadgreen table-scroll table-scroll-md">
  <table>
    <thead>
      <tr>
        <th class="nowrap">投稿日時</th>
        <th class="nowrap">講師ユーザーネーム</th>
        <th class="nowrap">動画サムネイル</th>
        <th>動画タイトル</th>
        <th>視聴回数</th>
        <th>視聴時間</th>
        <th>高評価数</th>
      </tr>
    </thead>
    <tbody>
      @foreach($items as $item)

      <tr></tr>
        <td class="nowrap">{{ $item->created_at->format('Y年m月d日 H:i') }}</td>
        <td><a class="linkpage clmain" href="{{ route('admin.teacherManagement.edit',[ 'id' => $item->owner_id ]) }}">{{ $item->name }}</a></td>
        <td>
          <figure class="thumbnail"><img src="{{ $item->thumbnail }}" alt="動画サムネイル"></figure>
        </td>
        <td>{{ $item->title }}</td>
        <td>{{ $item->views }}回</td>
        <td>{{ Helper::renderTotalPlayTime($item->watches) }}</td>
        <td>{{ $item->likes }}</td>
      </tr>
      @endforeach

    </tbody>
  </table>
</div>
{!! $items->appends(request()->input())->links('component.admin.pager') !!}
@endsection

@section('custom_js')
<script src="{{ asset('js/form.js') }}"></script>
<script>
  $('.calendar-input input').datepicker({
      language: "ja",
      autoclose: true,
      todayHighlight: true,
  });

  const exportCsv = () => {
    const _from_date = $('input[name=from_date]').val();
    const _to_date = $('input[name=to_date]').val();
    const _token = $('input[name=_token][type=hidden]').val();

    var form = $("<form>",{method:'post'})
              .append($('<input>',{name:'_token',value: _token }))
              .append($('<input>',{name:'_method',value:'PUT'}))
              .append($('<input>',{name:'from_date',value:_from_date})).append($('<input>',{name:'_to_date',value:_to_date}));
        form.appendTo('body').submit();
        form.remove();
  }
</script>
@endsection