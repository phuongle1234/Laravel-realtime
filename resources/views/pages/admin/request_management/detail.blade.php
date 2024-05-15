@extends('layouts.admin.index')

@section('body_class', 'p-request detail')
@section('class_main', 'p-content-request detail')

@section('content')
    <div class="table-style mt00 table-shawdow">
        <table>
            <tbody>
            <tr>
                <th>リクエスト日時</th>
                <td>
                    <input class="form-control disable" type="text" maxlength="30"
                           value="{{ Helper::renderDateTime($item->created_at,true )}}">
                </td>
            </tr>
            <tr>
                <th>生徒名</th>
                <td>
                    <input class="form-control disable" type="text" maxlength="30"
                           value="{{ $item->student->name ?? "" }}">
                </td>
            </tr>
            <tr>
                <th>画像</th>
                <td>
                    <div class="list-thumbnail">
                        @foreach($item->image as $key => $value)
                            <figure class="thumbnail">
                                <img src="{{ $value  }}" >
                            </figure>
                        @endforeach
                    </div>
                </td>
            </tr>
            <tr>
                <th>タイトル</th>
                <td>
                    <input class="form-control disable" type="text" maxlength="30" value="{{ $item->title ?? "" }}">
                </td>
            </tr>
            <tr>
                <th>説明文</th>
                <td>
                    <textarea class="form-control disable" name="" cols="30" rows="6" value="">{!! $item->content !!}</textarea>
                </td>
            </tr>
            <tr>
                <th>講師名</th>
                <td>
                    <input class="form-control disable" type="text" maxlength="30"
                           value="{{$item->teacher->name ?? ""}}">
                </td>
            </tr>
            <tr>
                <th>科目</th>
                <td><span class="tag tag-dark-green">{!!$item->subject->text_name!!}</span></td>
            </tr>
            <tr>
                <th>作成期限</th>
                <td>
                    <div class="calendar-group">
                        <div class="calendar-input">
                            <input class="form-control disable-calendar" type="text"
                                   placeholder="{{ Helper::renderDateTime($item->deadline) }}">
                        </div>

                    </div>
                </td>
            </tr>
            <tr>
                <th>ステータス</th>
                <td>
                    <div class="choose-subject">
                        @foreach(EStatus::REQUEST_STATUS_ARRAY as $k => $v)
                            <div class="item {{ ($item->status === $k) ? 'active' : "" }} disable-btn">{{ $v }}</div>
                        @endforeach
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
        <div class="fec mt20">
            {{--            <a class="btn btn-warning btn-custom mr10" href="#">公開</a>--}}
            {{--            <a class="btn btn-primary btn-custom mr10" href="#">登録</a>--}}
            <a class="btn btn-danger btn-custom" href="{{ route('admin.request_management.list') }}">戻る</a></div>
    </div>
@endsection