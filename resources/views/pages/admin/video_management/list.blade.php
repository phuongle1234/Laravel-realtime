@inject('_status','App\Enums\EStatus')

@extends('layouts.admin.index')
@section('body_class','p-video video-list')
@section('class_main','p-content-video video-list')



@section('content')
<div class="table-style mt00 tableheadgreen table-scroll table-scroll-md">
    <table>
      <thead>
        <form method="post" id="form-submit"  action="{{ URL::current() }}">
          @csrf
        <tr>
          <th>公開日時</th>
          <th class="nowrap">
            <div class="search-input">
              <input class="form-control" type="text" name="teacher_name" placeholder="講師ユーザー名" maxlength="20" value="{{ isset($teacher_name) ? $teacher_name : null }}">
              <button type="submit" class="search-icon"><img src="{{asset('common_img/icon-search.svg')}}" alt="search"></button>
            </div>
          </th>
          <th class="nowrap">動画サムネイル</th>
          <th class="nowrap">
            <div class="search-input">
              <input class="form-control" type="text" name="title" placeholder="動画タイトル" maxlength="20" value="{{ isset($title) ? $title : null }}">
              <button type="submit" class="search-icon"><img src="{{asset('common_img/icon-search.svg')}}" alt="search"></button>
            </div>
          </th>
          <th class="nowrap">リクエストID</th>
          <th>アクション</th>
        </tr>
      </form>

      </thead>
      <tbody>
        @foreach($items as $item)

        <tr>
          <td class="nowrap">{{ $item->updated_at->format('Y年m月d日 H:i') }}</td>
          <td>{{$item->user->name}}</td>
          <td>
            <figure class="thumbnail"><img src="{{ $item->thumbnail }}" alt="動画サムネイル"></figure>
          </td>
          <td>{{ $item->title }}</td>
          <td>
            @if( $item->request->count() )
              <?=  $item->request->map( function($_request){ return "<a href='". route('admin.request_management.detail',["id" => $_request->id ]) ."'>".  $_request->id ."</a>"; })->implode(", ") ?>
            @endif
          </td>
          <td>
            <div class="fcc">
            <a class="btn btn-warning btn-custom mr05" onclick="active(this)" data-id="{{ $item->id }}" data-value="{{ $item->active }}" >{{ $item->active == 1 ?  $_status::IS_DISPLAYED_TEXT : $_status::IS_NOT_DISPLAYED_TEXT    }}</a>

            <a class="btn btn-primary btn-custom mr05" href="{{route('admin.video_management.detail',$item->id)}}">詳細</a>

            @if( ! $item->request->count() )
            <a onClick="showPoupDelete({{ $item->id }})"  class="btn btn-danger btn-custom" >削除</a>
            @endif

            </div>
          </td>
        </tr>
        @endforeach

      </tbody>
    </table>
  </div>
  <div class="modal fade popup-style" id="confirmInActiveVideo" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <form action="{{ route('admin.video_management.active') }}" method="post">
        @csrf
        @method('PUT')
          <div class="modal-header">
            <span class="close" data-bs-dismiss="modal" aria-hidden="true"></span>
          </div>
          <div class="modal-body">
            <h3 class="popup-tit">この動画の公開を拒否します。</h3>
            <!-- <form> -->
            <!-- <p class="content-txt">拒否する理由</p> -->
            <!-- </form> -->
          </div>
          <div class="modal-footer">
            <div class="fce btn-pp">
              <input type="hidden" name="id" >
              <input type="hidden" name="actNum" >

              <span class="btn btn-dark" data-bs-dismiss="modal" aria-hidden="true">キャンセル</span>
              &nbsp;&nbsp;
              <button type="submit" class="btn btn-success" >OK</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
  {!! $items->appends(request()->input())->links('component.admin.pager') !!}
@endsection
@section('custom_js')
<script>
  let URI_DELETE = @JSON(route('admin.video_management.delete'));
  let token = "{{ csrf_token() }}";

  function active(f){

    id = f.getAttribute('data-id');
    actNum =  parseInt( f.getAttribute('data-value') );

    if(actNum)
      actNum = 0;
    else
      actNum = 1;

        $('#confirmInActiveVideo input[name=id]').val(id);
        $('#confirmInActiveVideo input[name=actNum]').val(actNum);

        $('#confirmInActiveVideo').modal('show');
  }
</script>
<script src="{{ asset('js/form.js') }}"></script>
@endsection