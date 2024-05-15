@extends('layouts.admin.index')

@section('body_class', 'p-request')
@section('class_main', 'p-content-request')

@section('content')
    <div class="table-style mt00 tableheadgreen table-scroll">
        <table>
            <thead>
            <tr>
                <th>リクエスト日時</th>
                <th>生徒名</th>
                <th>画像</th>
                <th>タイトル</th>
                <th>講師名</th>
                <th>作成期限</th>
                <th class="nowrap">
                    <form method="POST" id="form-submit">
                        @csrf
                        <div class="select-box">
                            <select name="eSign_status" class="form-control">
                                <option value="" selected>ステータス</option>
                                @foreach(EStatus::REQUEST_STATUS_ARRAY as $k => $v)
                                    <option {{ isset($eSign_status) ? (($k === $eSign_status) ? "selected" : "") : "" }} value="{{ $k }}">{{ $v }}</option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                </th>
                <th>アクション</th>
            </tr>
            </thead>
            <tbody>

            @foreach ($items as $item)

                <tr>
                    <td>{{ Helper::renderDateTime($item->created_at,true) }}</td>
                    <td>{{ $item->student->name }}</td>
                    <td>

                        <figure class="thumbnail">
                            <img src="{{ $item->image[0] }}" alt="画像">
                        </figure>
                    </td>
                    <td>{{ $item->title }}</td>
                    <td>{{ $item->teacher->name ?? '' }}</td>

                    <td>{{ Helper::renderDateTime($item->deadline) }}</td>
                    <td style="{{ Helper::renderColorRequestStatus($item->status) }}">{{ EStatus::getTextByStatus($item->status) }}</td>
                    <td>
                        <div class="fcc">

                        @if($item->status === EStatus::COMPLETE)
                            <button  onclick="navigator.clipboard.writeText('{{ env("URL_CLIENT_SIDE")."?id=".$item->video_id  }}'); " style="padding: 2px 12px" class="btn btn-dark btn-custom" ><img style="filter: invert(100%) sepia(100%) saturate(0%) hue-rotate(288deg) brightness(102%) contrast(102%); height: 26px;" src="{{ asset('images/copy.svg') }}" alt="コピー"> コピー</button> &nbsp;
                        @endif

                        @if($item->status === EStatus::PENDING)
                                <form method="POST" action="{{ route('admin.request_management.update',['id' => $item->id]) }}">
                                    @csrf
                                    <input type="hidden" name="is_displayed" value="{{ $item->is_displayed === EStatus::IS_DISPLAYED ?
                                    EStatus::IS_NOT_DISPLAYED : EStatus::IS_DISPLAYED  }}">
                                    <button class="btn btn-warning btn-custom mr05">{{ EStatus::renderPublicStatus($item->is_displayed) }}</button>
                                </form>
                        @endif

                            <a class="btn btn-primary btn-custom mr05" href="{{ route('admin.request_management.detail',["id" => $item->id]) }}">詳細</a>

                            <!-- if($item->status !== EStatus::COMPLETE) -->
                            <button class="btn btn-danger btn-custom" onClick="showPoupDelete({{ $item->id }})">削除 </button>

                        </div>
                    </td>
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
        let URI_DELETE = @JSON(route('admin.request_management.delete'));
        let token = "{{ csrf_token() }}";
    </script>
@endsection