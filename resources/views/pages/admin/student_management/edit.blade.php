@extends('layouts.admin.index')

@section('body_class','p-admin')

@section('content')



<div class="table-style mt00 table-shawdow">
  <table>
    <tbody>
      <tr>
        <th>登録日時</th>
        <td>
          <input class="form-control disable" type="text" name="registeredDate" maxlength="30" value="{{ Helper::renderDateTime($item->created_at,true) }}">
        </td>
      </tr>
      <tr>
        <th>生徒ID</th>
        <td>
          <input class="form-control disable" type="text" name="studentID" maxlength="30" value="{{ $item->code }}">
        </td>
      </tr>
      <tr>
        <th>氏名</th>
        <td>
          <input class="form-control disable" type="text" name="studentName" maxlength="30" value="{{ $item->name }}" >
        </td>
      </tr>
      <tr>
        <th>e-mail</th>
        <td>
          <input class="form-control disable" type="email" name="email" maxlength="30" value="{{ $item->email }}" >
        </td>
      </tr>
      <tr>
        <th>チケット数</th>
        <td>
          <input class="form-control disable" type="text" name="numTicket" maxlength="30" value="{{ $item->ticket }}枚">
        </td>
      </tr>
      <tr>
        <th>合計リクエスト数</th>
        <td>
          <input class="form-control disable" type="text" name="numRequest" maxlength="30" placeholder="{{ $item->request_count.'回' }}">
        </td>
      </tr>
      <tr>
        <th>会員ステータス</th>
        <td>
          <div class="choose-subject">

          @foreach( EStripe::getAllPlan() as $key => $_plan  )
            <div class="item disable-btn {{ $_plan['id'] == $item->stripe->plan_id ? 'active' : null }}">{{ $_plan['name'] }}</div>
          @endforeach
          </div>

        </td>
      </tr>
    </tbody>
  </table>
  <div class="fec mt20">
    {{-- <a class="btn btn-primary btn-custom mr10" href="#">登録</a> --}}
    <a class="btn btn-danger btn-custom" href="{{ route('admin.studentManagement.list') }}">戻る</a></div>
</div>
@endsection

@section('custom_js')

@endsection