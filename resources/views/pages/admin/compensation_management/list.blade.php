@extends('layouts.admin.index')

@section('body_class','p-compensation')
@section('class_main','p-content-compensation')

@section('content')
  <div class="fsc lecturer-tit">
    <h2 class="tit-page">報酬請求管理</h2>
      <form method="POST" action="{{ route('admin.compensation_management.export') }}">
        @csrf
        <button class="btn btn-img btn-dark"><img src="{{ asset('../common_img/csv.svg') }}" alt="CSVエクスポート"><span>CSVエクスポート</span></button>
      </form>
  </div>
<div class="table-style mt00 tableheadgreen">
    <table>
      <thead>
        <tr>
          <th>請求日時</th>
          <th>講師ユーザー名</th>
{{--          <th>合計視聴回数</th>--}}
{{--          <th>合計視聴時間</th>--}}
{{--          <th>合計高評価数</th>--}}
          <th>請求金額</th>
        </tr>
      </thead>
      <tbody>

      @if(!empty($compensations))
        @foreach($compensations as $compensation)
          <tr>
            <td>{{ Helper::renderDateTime($compensation->created_at,true) }}</td>
            <td>{{ $compensation->user->name ?? "" }}</td>
            <td>{{ number_format($compensation->amount) ?? 0 }}円</td>
          </tr>
        @endforeach
      @endif

      </tbody>
    </table>
  </div>
  @endsection
