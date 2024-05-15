@extends('layouts.admin.index')

@section('body_class','p-admin')
@section('class_main','p-content-admin')

@section('content')
<div class="table-style mt00 tableheadgreen">
                <table>
                  <thead>
                    <tr>
                      <th>アドミン名</th>
                      <th> ログインID</th>
                      <th>アクション</th>
                    </tr>
                  </thead>
                  <tbody>
                  @foreach( $item as $key => $row )
                    <tr>
                      <td>{{ $row->name }}</td>
                      <td>{{ $row->email }}</td>
                      <td><a class="btn btn-primary btn-custom" href="{{ route('admin.adminManagement.edit',[ "id" => $row->id ]) }}">編集</a></td>
                    </tr>
                  @endforeach
                  </tbody>
                </table>
              </div>
              {!! $item->appends(request()->input())->links('component.admin.pager') !!}
@endsection

@section('custom_js')

@endsection