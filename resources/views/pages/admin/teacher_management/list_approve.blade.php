@inject('subject', 'App\Models\Subject')
@extends('layouts.admin.index')
@section('body_class','p-admin')
@section('content')

<div class="table-style mt00 tableheadgreen table-scroll">
              <table>
              <thead>
              <form method="post" id="form-submit">
                @csrf
                <tr>
                        <th>NO</th>
                        <th class="nowrap">申請日</th>
                        <th class="nowrap">
                          <div class="search-input">
                            <input class="form-control" type="text" name="name" value="{{ $name ?? '' }}" placeholder="申請者名" maxlength="10">
                            <button class="search-icon"><img src="{{ asset("../common_img/icon-search.svg") }}" alt="search"></button>
                          </div>
                        </th>
                        <th class="nowrap">
                          <div class="search-input">
                            <input class="form-control" type="text" name="email" value="{{ $email ?? '' }}" placeholder="メールアドレス" maxlength="20">
                            <button class="search-icon"><img src="{{ asset("../common_img/icon-search.svg") }}" alt="search"></button>
                          </div>
                        </th>
                        <th class="nowrap">
                          <div class="select-box">
                            <select class="form-control subject" name="approved_admin">
                              <option value="">書類確認ステータス</option>
                              <option value="1" {{ isset($approved_admin) && $approved_admin == 1 ? 'selected' : null }}  >承認済み</option>
                              <option value="0" {{ isset($approved_admin) &&  $approved_admin == 0 ? 'selected' : null }} >未確認</option>
                              <option value="2" {{ isset($approved_admin) &&  $approved_admin == 2 ? 'selected' : null }} >再提出要請</option>
                            </select>
                          </div>
                        </th>
                        <th class="nowrap">
                          <div class="select-box">
                            <select class="form-control subject" name="percent_training">
                              <option value="">動画視聴ステータス</option>
                              <option value="1" {{ isset($percent_training) && $percent_training == 1 ? 'selected' : null }} >視聴済み</option>
                              <option value="0" {{ isset($percent_training) && $percent_training == 0 ? 'selected' : null }} >未完了</option>
                            </select>
                          </div>
                        </th>
                        <th class="nowrap">制限解除</th>
                        <th class="nowrap">アクション</th>
                  </tr>
                </form>
              </thead>
                <tbody>
        @foreach($item as $_key => $_val )
                  <tr>
                      <td>{{ ($_key+1) }}</td>
                      <td>{{ $_val->created_at->format('Y年m月d日 H:i') }} </td>
                      <td>{{  $_val->name }}</td>
                      <td>{{  $_val->email }}</td>
                      <td><span class="text_bold {{  $_val->approve_status->_class  }} "> {{  $_val->approve_status->_text  }} </span></td>
                      <td><span class="text_bold {{  $_val->train_status->_class  }}">{{  $_val->train_status->_text  }}</span>
                      </td>
                      <td>

                      <form method="post" action="">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="id" value="{{ $_val->id }}">
                        <button class="tag black-tag" {{  ($_val->approved_admin != 1) || ($_val->percent_training	 != 100) ? 'disabled' : null   }} >解除</button>
                      </form>

                    </td>
                      <td>
                        <div class="fcc">
                          <a class="btn btn-primary btn-custom" href="{{ route('admin.teacherManagement.edit_approve',['id' => $_val->id ] ) }}">確認</a>
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
  // let URI_DELETE = @JSON(route('admin.teacherManagement.delete'));
  let token = "{{ csrf_token() }}";

  function updateStatus(f,id){

    let active = $(f).attr('data-value');

    //有効 無効
    if(active == 'active'){
        status = 'denied';
          $(f).removeClass('red-tag'); $(f).addClass('black-tag');
          $(f).attr('data-value',status);
          $(f).text('有効');
    }else{
          status = 'active';
          $(f).removeClass('black-tag'); $(f).addClass('red-tag');
          $(f).attr('data-value',status);
          $(f).text('無効')
    }

      return onUpdate({id:id, status:status});
  }

</script>
<script src="{{ asset('js/form.js') }}"></script>
@endsection