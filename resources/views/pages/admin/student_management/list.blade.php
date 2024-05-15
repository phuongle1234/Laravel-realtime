@extends('layouts.admin.index')
@section('custom_css')
<style>
  .select-options li:first-child{
    display: none !important
  }
</style>
@endsection
@section('body_class','p-student')
@section('class_main','p-content-student')

@section('content')
<div class="table-style mt00 tableheadgreen table-scroll table-scroll-md">
  <table>
    <thead>
      <form method="post" id="form-submit">
        @csrf
          <tr>
            <th>
              <div class="search-input" >
                <input class="form-control" type="text" name="code" placeholder="生徒ID" maxlength="10" value="{{ isset($code) ? $code : null }}" >
                <button type="submit" class="search-icon"><img src="{{ asset("../common_img/icon-search.svg") }}" alt="search"></button>
              </div>
            </th>
            <th>
              <div class="search-input" >
                <input class="form-control" type="text" name="name" placeholder="ユーザー名" value="{{ isset($name) ? $name : null }}" maxlength="20">
                <button type="submit" class="search-icon"><img src="{{ asset("../common_img/icon-search.svg") }}" alt="search"></button>
              </div>
            </th>
            <th>
              <div class="search-input">
                <input class="form-control"  type="text" name="email" placeholder="メールアドレス" value="{{ isset($email) ? $email : null }}" maxlength="50">
                <button type="submit" class="search-icon"><img src="{{ asset("../common_img/icon-search.svg") }}" alt="search"></button>
              </div>
            </th>
            <th>
              <div class="select-box">
                <select name="plan_id" class="form-control" >
                  <option value="">会員ステータス</option>
                  @foreach( EStripe::getAllPlan() as $key => $_val )
                  <option {{ isset($plan_id) && ($plan_id == $_val['id']) ? "selected" : "" }}  value="{{ $_val['id'] }}">{{ $_val['name'] }}</option>
                  @endforeach
                </select>
              </div>
            </th>
            <th>チケット数</th>
            <th>合計リクエスト数</th>
            <th>アクション</th>
          </tr>
      </form>
    </thead>
    <tbody>
      @foreach($item as $key => $row)
        <tr>
          <td>{{ $row->code }}</td>
          <td>{{ $row->name }}</td>
          <td>{{ $row->email }}</td>
          <td>{{ EStripe::getPlanNameByPlanID($row->stripe->plan_id) }}</td>
          <td>{{ $row->tickets->sum('amount') ? $row->tickets->sum('amount')."枚" : null }} </td>
          <td>{{ $row->request_count ? $row->request_count."回" : null }}</td>
          <td>
            <div class="fcc">
              <a class="btn btn-primary btn-custom mr05" href="{{ route('admin.studentManagement.edit',[ "id" => $row->id ]) }}">詳細</a>
              <a onClick="showPoupDelete({{ $row->id }})" class="btn btn-danger btn-custom" >削除</a></div>
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
</div>
{!! $item->appends(request()->input())->links('component.admin.pager') !!}
@endsection

@section('custom_js')
<script>
  let URI_DELETE = @JSON(route('admin.studentManagement.delete'));
  let token = "{{ csrf_token() }}";
</script>
<script src="{{ asset('js/form.js') }}"></script>
@endsection