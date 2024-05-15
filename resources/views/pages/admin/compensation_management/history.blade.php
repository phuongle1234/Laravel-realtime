@extends('layouts.admin.index')

@section('body_class','p-compensation history')
@section('class_main','p-content-compensation history')
@section('custom_css')
    <script src="{{ asset('../js/datepicker-last/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('../js/datepicker-last/css/bootstrap-datepicker.min.css') }}"></script>
    <link rel="stylesheet" href="{{ asset('../js/datepicker-last/css/bootstrap-datepicker3.min.css') }}">
    <script src="{{ asset('../js/datepicker-last/locales/bootstrap-datepicker.ja.min.js') }}"></script>
@endsection
@section('content')
    <h2 class="tit-page">検索項目</h2>
    <div class="bg-green">
        <form class="dflex formfix" action="{{ URL::current() }}" method="POST">
            @csrf
            <div class="group">
                <div class="calendar-input">
                    <input class="form-control" name="from_date" value="{{ isset($from_date) ? $from_date : null }}" type="text" placeholder="2022年4月9日">
                </div>
                <span>~</span>
                <div class="calendar-input">
                    <input class="form-control" name="to_date" value="{{ isset($to_date) ? $to_date : null }}" type="text" placeholder="2022年4月12日">
                </div>
            </div>
            <button class="btn btn-dark" type="submit">検索</button>
        </form>
    </div>
    <h2 class="tit-page mt50">報酬支払履歴</h2>
    <div class="table-style mt00 tableheadgreen">
        <table>
            <thead>
            <tr>
                <th>ファイル生成日</th>
                <th>合計支払額</th>
                <th>ファイル名</th>
                <th>データ数</th>
                <th></th>
            </tr>
            </thead>
            <tbody>

            @if(!empty($histories))
                @foreach($histories as $history)
                    <tr>
                        <td>{{ Helper::renderDateTime($history->created_at,true) }}</td>
                        <td>{{ number_format($history->total_amount_exported) ?? 0 }}</td>
                        <td>{{ $history->file_path_name ?? "" }}</td>
                        <td><a class="link-page"
                               href="{{ route('admin.compensation_management.detail',['id' => $history->id]) }}">{{ $history->total_records ?? 0 }}</a></td>
                        <td>
                            <a style="text-decoration: none;" href="{{ Storage::disk('transfer')->exists($history->file_path_name) ?
                                    Storage::disk('transfer')->url($history->file_path_name) : "#" }}"
                                    class="btn-img btn-download">
                                    <img src="{{ asset('../common_img/download.svg') }}" alt="ダウンロード"><span>ダウンロード</span></a>
                        </td>
                    </tr>
                @endforeach
            @endif

            </tbody>
        </table>
    </div>
    {!! $histories->appends(request()->input())->links('component.admin.pager') !!}

@endsection
@section('custom_js')
    <script>
        $('.calendar-input input').datepicker({
            language: "ja",
            autoclose: true,
            todayHighlight: true,
        });
    </script>
@endsection