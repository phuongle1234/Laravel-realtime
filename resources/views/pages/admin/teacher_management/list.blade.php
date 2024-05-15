@inject('subject', 'App\Models\Subject')
@extends('layouts.admin.index')
@section('body_class','p-admin')
@section('content')
<div class="table-style mt00 tableheadgreen table-scroll">
                <table>

              <thead>
              <form method="post" id="form-submit" action="{{ URL::current() }}">
                @csrf
                      <tr>
                            <th class="nowrap">
                              <div class="search-input">
                                <input class="form-control" type="text" name="code" value="{{ isset($code) ? $code : null }}" placeholder="講師ID" maxlength="10">
                                <button type="submit" class="search-icon"><img src="{{ asset("../common_img/icon-search.svg") }}" alt="search"></button>
                              </div>
                            </th>
                            <th class="nowrap">
                              <div class="search-input">
                                <input class="form-control" type="text" name="name" value="{{ isset($name) ? $name : null }}" placeholder="講師ユーザー名" maxlength="20">
                                <button type="submit" class="search-icon"><img src="{{ asset("../common_img/icon-search.svg") }}" alt="search"></button>
                              </div>
                            </th>
                            <th class="nowrap">
                              <div class="search-input">
                                <input class="form-control" type="text" name="email" value="{{ isset($email) ? $email : null }}" placeholder="メールアドレス" maxlength="50">
                                <button type="submit" class="search-icon"><img src="{{ asset("../common_img/icon-search.svg") }}" alt="search"></button>
                              </div>
                            </th>
                            <th class="nowrap">
                              <div class="select-box">
                                <select name="subject_id" class="form-control subject">
                                <option value="">------</option>
                                @foreach($subject::all() as $key => $value)
                                  <option value="{{ $value->id }}" {{ isset($subject_id) && $subject_id == $value->id ? 'selected' : null }} >{!! $value->name !!}</option>
                                @endforeach
                                </select>
                              </div>
                            </th>
                            <th class="nowrap">動画投稿数</th>
                            <th class="nowrap">指名リクエスト数</th>
                            <th class="nowrap">最終ログイン日時</th>
                            <th class="nowrap">
                              <div class="select-box">
                                <select name="eSign_status" class="form-control status">
                                  <option value="">ステータス</option>
                                  <option value="active" {{ isset($eSign_status) && $eSign_status == 'active' ? 'selected' : null }} >有効</option>
                                  <option value="denine" {{ isset($eSign_status) && $eSign_status == 'denine' ? 'selected' : null }} >無効</option>
                                </select>
                              </div>
                            </th>
                            <th class="nowrap">アクション</th>
                      </tr>

                </form>
                </thead>
                  <tbody>

            @foreach($item as $key => $row)
                    <tr>
                      <td>{{ $row->code }}</td>
                      <td>{{ $row->name }}</td>
                      <td>{{ $row->email }}</td>
                      <td>{{ $row->subject_text }}</td>
                      <td>{{ $row->videos_count }}本</td>
                      <td>{{ $row->order_request_count ? $row->order_request_count.'回' : null }}</td>
                      <td>{{ $row->seen_at->format('Y年m月d日 h:i') }}</td>
                      <td>
                          <span  class="tag {{ $row->status == EStatus::ACTIVE ?'black-tag' : 'red-tag'   }}" data-value='{{ $row->status }}' onClick="updateStatus( this, {{ $row->id }} )" > {{ $row->status == EStatus::ACTIVE ? EStatus::EFFECTIVENESS : EStatus::INVALID  }} </span>
                      </td>
                      <td>
                        <div class="fcc">
                          <a class="btn btn-primary btn-custom mr05" href="{{ route('admin.teacherManagement.edit',[ "id" => $row->id ]) }}">編集</a>
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

<script>
  let URI_DELETE = @JSON(route('admin.teacherManagement.delete'));
  let token = "{{ csrf_token() }}";

  function updateStatus(f,id){
    let active = $(f).attr('data-value');

    const _ACTIVE = "{{ EStatus::ACTIVE }}";
    const _DELETE = "{{ EStatus::DELETE }}";

    const _EFFECTIVENESS = "{{ EStatus::EFFECTIVENESS }}";
    const _INVALID = "{{ EStatus::INVALID }}";

    //有効 無効
    if(active == 'active'){
          status = _DELETE;
          $(f).removeClass('black-tag'); $(f).addClass('red-tag');
          $(f).attr('data-value',status);
          $(f).text(_INVALID);
    }else{
          status = _ACTIVE;

          $(f).removeClass('red-tag'); $(f).addClass('black-tag');
          $(f).attr('data-value',status);
          $(f).text(_EFFECTIVENESS)
    }

      return onUpdate({id:id, status:status});
  }

</script>
<script src="{{ asset('js/form.js') }}"></script>
@endsection