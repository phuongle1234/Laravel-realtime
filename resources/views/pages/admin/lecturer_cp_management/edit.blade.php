@extends('layouts.admin.index')
@section('body_class','p-admin')
@section('content')
  <h2 class="tit-page"><a href="{{ route('admin.lecturerCPManagement.list') }}"><img src="{{ asset('../images/back-icon.svg') }}" alt="iconback"></a>講師報酬管理-編集</h2>
  <form method="post">
    @csrf
    @method('PUT')
  <div class="table-style mt00 table-shawdow">
    <table>
      <tbody>
      <tr>
        <th>講師ユーザー名</th>
        <td>
          <input class="disable form-control" type="text" value="{{ $lecturer->name ?? "" }}" maxlength="30" placeholder="お名前太郎">
        </td>
      </tr>
      <tr>
        <th>適用</th>
        <td>
          <div class="select-box custom-select sources" id="sources">
            <select name="reward_action" class="form-control">
              <option value="" {{ (old('reward_action') == '' ? 'selected' : "") }}>---------------</option>
              <option value="bonus" {{ (old('reward_action') == 'bonus' ? 'selected' : "") }}>追加</option>
              <option value="minus" {{ (old('reward_action') == 'minus' ? 'selected' : "") }}>削減</option>
            </select>
          </div>
        </td>
      </tr>
      <tr>
        <th>金額操作</th>
        <td>
          <input class="numbertype form-control" type="text" placeholder="" maxlength="7" name="amount" inputmode="numeric" pattern="[0-9]{1,2,3,4,5,6,7}">
        </td>
      </tr>
      </tbody>
    </table>
    <div class="fec mt10">
      <button class="btn btn-primary btn-custom mr10">実行</button>
{{--      <a class="btn btn-danger btn-custom" href="{{ route('admin.lecturerCPManagement.list') }}">削減</a>--}}
    </div>
  </div>
  </form>
  <h2 class="tit-page mt50">金額操作履歴</h2>
  <div class="table-style mt00 tableheadgreen">
    <table>
      <thead>
      <tr>
        <th width="25%">日時</th>
        <th width="25%">適用</th>
        <th width="25%">金額増減</th>
        <th width="25%">変更後の報酬</th>
      </tr>
      </thead>
      <tbody>

      @if($lecturer->transfer->isNotEmpty())
        @foreach($lecturer->transfer as $transfer)
          <tr>
            <td>{{ Helper::renderDateTime($transfer->created_at,true) }}</td>
            <td>{{ ETransfer::renderAction($transfer->action) }}</td>
            <td> {!! ETransfer::renderTransferByAction($transfer->amount,$transfer->action) !!} </td>
            <td>{{ number_format($transfer->current_reward) ?? 0 }}</td>
          </tr>
        @endforeach
      @endif

      </tbody>
    </table>
  </div>
@endsection

@section('custom_js')
@endsection