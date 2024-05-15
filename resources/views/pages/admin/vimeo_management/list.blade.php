@extends('layouts.admin.index')

@section('body_class','p-admin')
@section('class_main','p-content-admin')

@section('content')
<div class="table-style mt00 tableheadgreen">
                <table>
                  <thead>
                    <tr>
                      <th>VimeoユーザーID</th>
                      <th>ストレージ使用率</th>
                      <th>アクション</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>{{ !empty($vimeo) ? $vimeo['body']['name'] : "" }}</td>
                      <td>{{ Helper::renderVimeoStorage($vimeo['body']['upload_quota']['space']['max'],$vimeo['body']['upload_quota']['space']['used']) }}</td>
                      <td><a class="btn btn-primary btn-custom" href="{{ route('admin.vimeo_management.show',['id' => 1]) }}">編集</a></td>
                    </tr>
                  </tbody>
                </table>
              </div>
@endsection
