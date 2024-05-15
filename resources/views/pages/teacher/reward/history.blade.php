@extends('layouts.teacher.index')
@section('body_class','p-reward_management pdbottom hasbg')
@section('class_main','p-content-reward_management pdbottom')

@section('content')
    @include('component.erorr.custom')
    <form method="POST">
        @csrf
        <div class="main-tit reward-tit">
            <span class="text">報酬履歴</span>
            <div class="calendar-input">
                <input name="from_date" value="{{ isset($from_date) ? $from_date : "" }}" class="form_date_search form-control" type="text" placeholder="2022年4月">
            </div>
            <button class="btn btn-primary">検索</button>
            <div style="text-align: right;">
                <span class="text">現在の報酬: ￥{{ number_format($teacher->reward) ?? 0 }}</span>
            </div>
        </div>
    </form>
    <div class="table-style">
        <table>
            <thead>
            <tr>
                <th class="nowrap">集計期間</th>
                <th class="nowrap">リクエスト受注数</th>
                <th class="nowrap">動画再生回数</th>
                <th>動画再生時間</th>
                <th>高評価数</th>
                <th>報酬</th>
            </tr>
            </thead>
            <tbody>

            @if(!empty($list_statistical))
                @foreach($list_statistical as $statistical)
                    <tr>
                        <td class="nowrap">
                            {!! Helper::renderStatisticalDate($statistical->year,$statistical->month) !!}
                        </td>
                        <td>{{ $statistical->requests ?? 0 }}本</td>
                        <td>{{ number_format($statistical->views) ?? 0 }}回</td>
                        <td>{{ Helper::renderTotalPlayTime($statistical->seconds) }}</td>
                        <td>{{ number_format($statistical->likes) }}件</td>
                        <td>{{ number_format($statistical->amount) }}円</td>
                    </tr>
                @endforeach
            @endif

            </tbody>
        </table>
    </div>
@endsection
@section('custom_js')
    <script>
        $(".form_date_search").flatpickr({
            minDate: new Date(new Date().getFullYear() - 20, new Date().getMonth(), 1),
            locale: 'ja',
            maxDate: 'today',
            disableMobile: true,
            plugins: [new monthSelectPlugin({shorthand: false, dateFormat: "Y-m", altFormat: "M Y"})]
        });

    </script>
@endsection
