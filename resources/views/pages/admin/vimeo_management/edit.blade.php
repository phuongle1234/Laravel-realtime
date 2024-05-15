@extends('layouts.admin.index')

@section('body_class','p-admin')
@section('class_main','p-content-admin')

@section('content')

<div class="table-style mt00 table-shawdow">
    <form method="post" action="{{ route('admin.vimeo_management.update',['id' => $vimeo_model->id]) }}">
      @method('PUT')
      @csrf
                <table>
                  <tbody>
                    <tr>
                      <th>VimeoクライアントID</th>
                      <td>
                        <div class="fsc">
                          <input class="w300px form-control" name="client_id" type="text" value="{{ !empty($vimeo_model) ? $vimeo_model->client_id : "" }}">
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <th>Vimeoシークレットキー</th>
                      <td>
                        <div class="fsc">
                          <input class="w300px form-control" name="client_secrets" type="text" value="{{ !empty($vimeo_model) ? $vimeo_model->client_secrets : "" }}" >
                        </div>
                      </td>
                    </tr>
                    <tr>
                        <th>Vimeoアクセストークン</th>
                        <td>
                            <div class="fsc">
                                <input class="w300px form-control" name="personal_access_token" type="text" value="{{ !empty($vimeo_model) ? $vimeo_model->personal_access_token : "" }}">
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th>VimeoユーザーID</th>
                        <td>
                            <div class="fsc">
                                <input class="w300px form-control" disabled type="text" value="{{ !empty($vimeo_account) ? $vimeo_account['body']['name'] : "" }}"  maxlength="50">
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th>ストレージ使用率</th>
                        <td>
                            <div class="fsc">
                                <input class="w300px form-control" disabled type="text" value="{{ Helper::renderVimeoStorage($vimeo_account['body']['upload_quota']['space']['max'],$vimeo_account['body']['upload_quota']['space']['used']) }}"  maxlength="50">
                            </div>
                        </td>
                    </tr>
                  </tbody>
                </table>
          <div class="fec mt20">
                <button type="submit" class="btn btn-primary btn-custom mr10" >登録</button>
                <a class="btn btn-danger btn-custom" href="{{ route('admin.vimeo_management.list') }}">戻る</a>
          </div>
    </form>
</div>
@endsection

@section('custom_js')
<script>

    </script>
@endsection