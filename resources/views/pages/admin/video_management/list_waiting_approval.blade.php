@extends('layouts.admin.index')
@section('custom_css')
<link rel="stylesheet" href="{{ asset('css/admincus.css') }}">

@endsection
@section('body_class','p-video video-list')
@section('class_main','p-content-video video-list')

@section('content')
<div class="table-style mt00 tableheadgreen">
    <table>
      <thead>
        <tr>
          <th>申請日時</th>
          <th class="nowrap">講師ユーザー名</th>
          <th class="nowrap">動画サムネイル</th>
          <th class="nowrap">動画タイトル</th>
          <th>アクション</th>
        </tr>
      </thead>
      <tbody>
      @foreach( $item as $_key => $_row )

        <tr>
          <td class="nowrap"> {{ $_row->created_at->format('Y年m月d日 H:i') }}</td>
          <td>{{ $_row->teacher->name }}</td>
          <td>
            <figure class="thumbnail"><img src="{{ $_row->thumbnail }}" ></figure>
          </td>
          <td>{{ $_row->video_title }}</td>
          <td>
            <div class="fcc">
              <button class="btn btn-outline-success btn-custom mr05" onclick="review( {{ $_row->id }}  )" >確認</button> &nbsp;
              <a class="btn btn-dark btn-custom mr05" href="{{ route('admin.video_management.detail', [ 'id' => $_row->video_id ]) }}">編集</a>
            </div>
          </td>
        </tr>
      @endforeach
      </tbody>

    </table>
  </div>

  <div class="modal fade popup-style2" id="contentPopup" tabindex="-1" role="dialog" aria-hidden="true">

  </div>

      <div class="modal fade popup-style" id="videoPopup" tabindex="-1" role="dialog" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
            <form action="{{ route('admin.video_management.pass') }}" method="post">
              @csrf
              @method('PUT')
              <div class="modal-header">
                <span class="close" data-bs-dismiss="modal" aria-hidden="true"></span>
              </div>
              <div class="modal-body">
                <p class="content-txt">
                    この動画の公開を許可します。<br>
                  よろしいでしょうか。
                </p>
              </div>
              <div class="modal-footer">
                <div class="fce btn-pp">
                  <input type="hidden" name="id">
                  <span class="btn btn-dark" data-bs-dismiss="modal" aria-hidden="true">キャンセル</span>
                  &nbsp;&nbsp;
                  <button type="submit" class="btn btn-success">許可する</button>
                </div>
              </div>
            </form>
            </div>
          </div>
        </div>

        <div class="modal fade popup-style" id="confirmRefuseVideo" tabindex="-1" role="dialog" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <form action="{{ route('admin.video_management.denied') }}" method="post">
              @csrf
              @method('PUT')
                <div class="modal-header">
                  <span class="close" data-bs-dismiss="modal" aria-hidden="true"></span>
                </div>
                <div class="modal-body">
                  <h3 class="popup-tit">この動画の公開を拒否します。</h3>
                  <!-- <form> -->
                    <label>拒否する理由</label>
                    <textarea class="form-control" name="content" cols="30" rows="4" require required> </textarea>
                  <!-- </form> -->
                </div>
                <div class="modal-footer">
                  <div class="fce btn-pp">
                    <input type="hidden" name="id" >
                    <span class="btn btn-dark" data-bs-dismiss="modal" style="margin-right:20px" aria-hidden="true">キャンセル</span>
                    &nbsp;&nbsp;
                    <button type="submit" class="btn btn-success" >OK</button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
        {!! $item->appends(request()->input())->links('component.admin.pager') !!}
  @endsection

  @section('custom_js')
  <script>
    let URL = @JSON( route('admin.video_management.review') )

    function review(id){

        let token = $('meta[name="csrf-token"]').attr('content');
        $.post(URL,{ _token: token, _method: 'PUT', id: id }, (e)=>{
            $('#contentPopup').html(e);
            $('#contentPopup').modal('show');
        });


    }
    //confirmRefuseVideo
    function denied(id){
        $('#confirmRefuseVideo textarea[name=content]').val('');
        $('#confirmRefuseVideo input[name=id]').val(id);
        $('#confirmRefuseVideo').modal('show');
    }
    //videoPopup
    function pass(id){
        $('#videoPopup input[name=id]').val(id);
        $('#videoPopup').modal('show');
    }

  </script>
  @endsection