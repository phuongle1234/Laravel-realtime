<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<body>
<table>
    <thead>
      <tr>
        <th>集計期間</th>
        <th>講師ユーザーネーム</th>
        <th>合計視聴回数</th>
        <th>合計視聴時間</th>
        <th>合計高評価数</th>
      </tr>
    </thead>
    <tbody>
    @foreach( $_data as $_key => $row )
        <tr>
            <td>{{ $_date_time }}</td>
            <td>{{ $row->name }}</td>
            <td>{{ number_format( $row->views ) }}</td>
            <td>{!! Helper::renderTotalPlayTime( $row->watchs ) !!}</td>
            <td>{{ $row->likes }}</td>
        </tr>
    @endforeach
    </tbody>
  </table>
</body>
</html>

