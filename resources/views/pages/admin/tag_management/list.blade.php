@inject('subject', 'App\Models\Subject')
@extends('layouts.admin.index')

@section('body_class','main_body p-tag list')
@section('class_main','p-content-tag list')

@section('custom_title')
<a class="btn btn-success" data-bs-toggle="modal" data-bs-target="#tagPopup" >新規作成</a>
@endsection

@section('content')
<div class="table-style mt00 tableheadgreen">
                <table>
                <form method="post" id="form-submit" action="{{ URL::current() }}">
                @csrf
                  <thead>
                    <tr>
                      <th>No</th>
                      <th>
                        <div class="select-box">
                          <select name="eSign_tag_type" id="tag_type" class="form-control">
                            <option value="">属性</option>
                            <option value="field" {{ isset($eSign_tag_type) && $eSign_tag_type == 'field' ? 'selected' : null }} >分野</option>
                            <option value="difficult" {{ isset($eSign_tag_type) && $eSign_tag_type == 'difficult' ? 'selected' : null }} >難易度</option>
                          </select>
                        </div>
                      </th>
                      <th>
                        <div class="select-box">
                          <select name="eSign_subject_id" id="subject_id" class="form-control">
                            <option value="">項目名</option>
                            @foreach($subject::all() as $_key => $_row)
                            <option value="{{ $_row->id }}" {{ isset($eSign_subject_id) && $eSign_subject_id == $_row->id ? 'selected' : null }} >{!! $_row->name !!}</option>
                            @endforeach
                          </select>
                        </div>
                      </th>
                      <th>
                        <div class="search-input">
                          <input class="form-control" type="text" name="name" value="{{ isset($name) ? $name : null }}" placeholder="タグ名" maxlength="20">
                            <button type="submit" class="search-icon"><img src="{{ asset("../common_img/icon-search.svg") }}" alt="search"></button>
                        </div>
                      </th>
                      <th>
                        <div class="select-box">
                          <select name="eSign_active" id="status" class="form-control">
                            <option value="">ステータス</option>
                            <option value="1" {{ isset($eSign_active) && $eSign_active == '1' ? 'selected' : null }} >表示</option>
                            <option value="0" {{ isset($eSign_active) && $eSign_active == '0' ? 'selected' : null }} >非表示</option>
                          </select>
                        </div>
                      </th>
                      <th>アクション</th>
                    </tr>
                  </thead>
                  </form>
                  <tbody>

                    @foreach($item as $key => $row)
                      <tr>
                          <td>{{ $key+1 }}</td>
                          <td>{{ $row->type }}</td>
                          <td>{!! $row->subject ? $row->subject->name : null !!}</td>
                          <td>{{  $row->name }}</td>

                          <td><span class="tag {{ $row->active == 0 ? 'black-tag' : null }}" data-value="{{  $row->active }}" onClick="updateStatus( this, {{ $row->id }} )"> {{ $row->status }}</span>
                          </td>
                          <td>
                            <div class="fcc">
                              <a class="btn btn-primary btn-custom mr05" href='{{ route("admin.tagManagement.{$row->tag_type}.edit",[ "id" => $row->id  ]) }}'>編集</a>
                              <a class="btn btn-danger btn-custom" onClick="showPoupDelete({{ $row->id }})">削除</a>
                            </div>
                          </td>
                      </tr>
                    @endforeach

                  </tbody>
                </table>
              </div>
              <div class="modal fade popup-style" id="tagPopup" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <button class="close" data-bs-dismiss="modal" aria-hidden="true"></button>
                    </div>
                    <div class="modal-body">
                      <p class="content-txt">作成するタグの属性を選択してください。</p>
                    </div>
                    <div class="modal-footer">
                      <div class="fce btn-pp">
                        <a class="btn btn-primary" href="{{ route('admin.tagManagement.field.add' ) }}" >分野</a>
                        <a class="btn btn-dark" href="{{ route('admin.tagManagement.difficult.add' ) }}" >難易度</a>
                      </div>
                    </div>
              </div>
          </div>
      </div>
      {!! $item->appends(request()->input())->links('component.admin.pager') !!}
@endsection

@section('custom_js')
<script src="{{ asset('js/form.js') }}"></script>
<script>
  let URI_DELETE = @JSON(route('admin.tagManagement.delete'));
  let token = "{{ csrf_token() }}";

  function updateStatus(f, id){
    //0 表示
    //1 非表示

    active = $(f).attr('data-value');

    if(active == 1){
        $(f).addClass('black-tag');
        $(f).text('非表示');
        status = 0;
        $(f).attr('data-value',status);
    }else{
        $(f).removeClass('black-tag');
        $(f).text('表示');
        status = 1;
        $(f).attr('data-value',status);
    }

      return onUpdate({id:id, active:status});
  }
</script>
@endsection