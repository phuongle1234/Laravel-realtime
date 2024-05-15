@extends('layouts.admin.index')

@section('body_class','p-p-compensation detail')
@section('class_main','p-content-compensation detail')

@section('content')
  <div class="table-style mt00 tableheadgreen tableheadblack">
    <table>
      <thead>
      <tr>
        <th>請求日時</th>
        <th>講師ユーザー名</th>
{{--        <th>合計視聴回数</th>--}}
{{--        <th>合計視聴時間</th>--}}
{{--        <th>合計高評価数</th>--}}
        <th>請求金額</th>
      </tr>
      </thead>
      <tbody>

      @if(!empty($exported_compensations))
        @foreach($exported_compensations as $compensation)
          <tr>
            <td>{{ Helper::renderDateTime($compensation->created_at,true) }}</td>
            <td>{{ $compensation->user->name ?? "" }}</td>
            <td>{{ number_format($compensation->log->total_amount_exported) ?? 0 }}円</td>
          </tr>
        @endforeach
      @endif

      </tbody>
    </table>
  </div>
  <div class="fec mt20"><a class="btn btn-danger btn-custom mr10" href="{{ route('admin.compensation_management.history') }}">戻る</a></div>
  @endsection
