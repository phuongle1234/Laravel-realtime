@extends('layouts.student.index')

@section('body_class','main_body p-setting hasbg')

@section('class_main','p-content-setting')

@section('content')
<div class="setting-wrapper">

        <a class="back-link fsc" href="{{ url()->previous() }}">
                <img src="{{ asset('student/images/back-icon.svg') }}" alt="{{ trans("common.setting.payment") }}"><span>{{ trans("common.setting.inquiry") }}</span>
        </a>
        @include('component.register.alert')
        <div class="setting-bg" action="">
          <form class="setting-content" id="form-submit" method="post">
          @csrf

            <div class="setting-body">
                  <dl class="noinline">
                                <dt>お名前</dt>
                                <dd>
                                  <input class="form-control" type="text" name="name" value="{{ old('name') }}" maxlength="12" required>
                                </dd>
                              </dl>
                              <dl class="noinline">
                                <dt>メールアドレス</dt>
                                <dd>
                                  <input class="form-control" type="email" name="email" value="{{ old('email') }}" required>
                                </dd>
                              </dl>
                                <dl class="noinline">
                                    <dt>カテゴリ</dt>
                                    <dd>
                                        <div class="select">
                                            <select name="options" id="options" class="select-hidden">
                                                @foreach(EInquiry::STUDENT_INQUIRY_OPTIONS_ARRAY as $k => $v)
                                                    <option {{ old('options') ? (($k === old('options')) ? "selected" : "") : "" }} value="{{ $k }}">{{ $v }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </dd>
                                </dl>

                                @if(!empty($list_request_complete))
                                    <dl class="noinline d-none">
                                        <dt>リクエスト名</dt>
                                        <dd>
                                            <div class="select">
                                                <select name="request_complete_name" class="select-hidden">
                                                    @foreach($list_request_complete as $k => $v)
                                                        <option value="{{ $v->title }}">{{ $v->title }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </dd>
                                    </dl>
                                @endif
                              <dl class="noinline">
                                <dt>詳細</dt>
                                <dd>
                                  <textarea class="form-control" name="content" cols="30" rows="5" required> {{ old('content') }} </textarea>
                                </dd>
                      </dl>
            </div>
            <div class="text_center">
                        <label class="text-bottom" for="check">
                          <input type="checkbox" name="agreement" id="check" required><span> <a href="{{ route('student.faq.policy') }}"> プライバシーポリシー </a> に同意する<br>※ご同意いただけない場合は送信ができません</span>
                        </label>
                        <button class="btn btn-primary">送信する</button>
              </div>
          </form>
        </div>
@endsection

@section('custom_js')
        <script>
            $( $('select[name="options"]').parent().find('ul.select-options li') ).click( (e) => {
                let selected_option =  $(e.target).attr('rel');

                if(selected_option == "request_cancel"){
                    $('select[name="request_complete_name"]').parent().parent().parent().parent().removeClass('d-none');
                } else {
                    $('select[name="request_complete_name"]').parent().parent().parent().parent().addClass('d-none')
                }
            })
        </script>
@endsection
