@extends('layouts.admin.index')

@section('body_class','p-admin')

@section('custom_title')
<a class="btn btn-success" href="{{ route('admin.notificationManagement.add') }}" >新規作成</a>
@endsection

@section('content')
<div class="table-style mt00 tableheadgreen">
                <table>
                  <thead>
                    <tr>
                      <th width="70%">テンプレート名</th>
                      <th width="30%">アクション</th>
                    </tr>
                  </thead>
                  <tbody>
                  @foreach($item as $key => $row)
                    <tr>
                      <td>{{ $row->name }}</td>
                      <td>
                        <div class="fcc">
                          <a class="btn btn-primary btn-custom mr10" href="{{ route('admin.notificationManagement.edit',["id" => $row->id]) }}">詳細</a>
                          <a onClick="showPoupDelete({{ $row->id }})" class="btn btn-danger btn-custom" >削除</a>
                        </div>
                      </td>
                    </tr>
                  @endforeach
                  </tbody>
                </table>
              </div>
              {!! $item->appends(request()->input())->links('component.admin.pager') !!}

@endsection

@section('custom_js')
<script src="{{ asset('js/form.js') }}"></script>
<script>
  let URI_DELETE = @JSON(route('admin.notificationManagement.delete'));
  let token = "{{ csrf_token() }}";
</script>
@endsection