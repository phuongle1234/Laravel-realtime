@extends('layouts.student.index')

@section('body_class','main_body p-setting hasbg')
@section('class_main','p-content-setting')

@section('content')
<div class="setting-wrapper">

        <a class="back-link fsc" href="{{ route('student.setting.index') }}">
                <img src="{{ asset('student/images/back-icon.svg') }}" alt="{{ trans("common.setting.payment") }}"><span>{{ trans("common.setting.payment") }}</span>
        </a>
        @include('component.register.alert')
        <div class="setting-bg" action="">
        <form class="setting-content setting-card-box">
                      <ul>
                          @foreach($_card as $_row)
                            <li  {{ $_row->id == $_default ? 'class=active' : null }} >
                                <svg onclick="changeDefault('{{ $_row->id }}')" class="icon-check" xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 40 40" fill="none">
                                  <circle cx="20" cy="20" r="20" fill="#8FC31F"></circle>
                                  <path d="M11 18.1379L18.5208 26L30 14" stroke="white" stroke-width="3" stroke-linecap="round"></path>
                                </svg><img src="{{ asset('student/images/setting/debit_card.svg') }}" alt=""><span>**** **** **** {{ $_row->last4 }}</span>
                                  <a href="{{ route('student.setting.payment_edit',['card_id' => $_row->id ]) }}" class="btn btn-outline-primary">編集する</a>
                            </li>
                          @endforeach
                      </ul>
                      <div class="text_center"><a class="btn btn-outline-primary btn-image" href="{{ route('student.setting.payment_info') }}">
                          <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 26 26" fill="none">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M13 0C11.8954 0 11 0.89543 11 2V11H2C0.895431 11 0 11.8954 0 13C0 14.1046 0.89543 15 2 15H11V24C11 25.1046 11.8954 26 13 26C14.1046 26 15 25.1046 15 24V15H24C25.1046 15 26 14.1046 26 13C26 11.8954 25.1046 11 24 11H15V2C15 0.895431 14.1046 0 13 0Z" fill="#00A29A"></path>
                          </svg><span>お支払方法を追加する</span></a></div>
          </form>
        </div>
@endsection

@section('custom_js')
<script>
  let URL = @JSON(route('student.setting.update_default'));

  function changeDefault(card_id){
    var form = $("<form>",{ method:'post', action:URL })
            .append($('<input>',{name:'_token',value:_token}))
            .append($('<input>',{type: 'hidden', name:'_method',value:'PATCH' }))
            .append($('<input>',{name:'card_id',value:card_id}));
      form.appendTo('body').submit();
      form.remove();
  }
</script>
@endsection
