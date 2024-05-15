@extends('layouts.student.index')

@section('body_class','main_body p-setting payment hasbg')
@section('class_main','p-content-setting payment')

@section('content')
    @include('component.register.alert')
    <a class="back-link fsc" href="{{ route('student.setting.plan_change') }}"><img
                src="{{ asset('../images/back-icon.svg') }}"
                alt="プラン変更"><span>{{ trans('student.setting-plan_change') }}</a></span>
    <div class="setting-bg">
        <form class="setting-content">
            <ul class="select-list clmain-select payment-select">
                @include('component.student.setting.show_current_plan_payment',['plan_id' => $_user->stripe()->first()->plan_id])
            </ul>
            <div class="text_center">
                <button class="btn btn-primary mt00" type="button" data-bs-toggle="modal"
                        onclick="checkPlan({{$_user->stripe()->first()->plan_id}})" data-bs-target="">プランを変更する
                </button>
            </div>
        </form>
    </div>
    <div class="modal fade popup-style popup-style1 popup-bottom-sp popup-top" id="paymentMethodSetting" tabindex="-1"
         role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <button class="close" data-bs-dismiss="modal" aria-hidden="true"></button>
                <div class="modal-body">
                    <h2 class="pp-tit">現在お使いのカード</h2>
                    <form class="setting-content setting-card-box">
                        <ul>
                            @if(!empty($_card))
                                <li class="active">
                                    <svg class="icon-check" xmlns="http://www.w3.org/2000/svg" width="40" height="40"
                                         viewBox="0 0 40 40" fill="none">
                                        <circle cx="20" cy="20" r="20" fill="#8FC31F"></circle>
                                        <path d="M11 18.1379L18.5208 26L30 14" stroke="white" stroke-width="3"
                                              stroke-linecap="round"></path>
                                    </svg>
                                    <img src="{{ asset('../student/images/setting/debit_card.svg') }}" alt=""><span>**** **** **** {{ $_card[0]->last4 }}</span>
                                </li>
                            @endif
                        </ul>
                        <p class="text_center"><a class="change-card" href="{{ route('student.setting.payment') }}">別のカードに変更する</a>
                        </p>
                        <p class="text_center">
                            <button class="btn btn-primary btn-fullw" type="button" data-bs-dismiss="modal"
                                    aria-hidden="true" data-bs-toggle="modal" data-bs-target="#confirmChangePlan">次へ
                            </button>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade popup-style popup-style1 popup-bottom-sp confirmDlt" id="confirmChangePlan" tabindex="-1"
         role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <button class="close" data-bs-dismiss="modal" aria-hidden="true"></button>
                <div class="modal-body">
                    <h3 class="title">確認</h3>
                    <p class="content-txt">{!! nl2br($_confirm_plan_text) !!}</p>
                </div>
                <div class="fcc">
                    <button class="btn btn-primary" data-bs-dismiss="modal" onclick="updatePlan()" aria-hidden="true">
                        変更を完了する
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom_js')
    <script>
        $(document).ready(function () {
        });

        function updatePlan() {
            $('#loading').show();
            var updatePlanURL = @JSON(route('student.setting.update_plan'));
            var selected_plan_id = $('ul.payment-select li.active').attr('data-id');
            axios.post(updatePlanURL, {selected_plan_id: selected_plan_id}).then((response) => {
                $('#loading').hide();
                $('#error_mess').remove();
                html_erorr = `<div class="alert alert-dark" id='error_mess'><span>${response.data.message}</span>
                                  <button class="btn-close" type="button" onclick="$(this).parent().remove()">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="29" height="29" viewBox="0 0 29 29" fill="none">
                                      <rect width="2.5" height="20" rx="1.25" transform="matrix(-0.707107 -0.707107 -0.707107 0.707107 22.0972 7.95496)" fill="black"></rect>
                                      <rect width="2.5" height="20" rx="1.25" transform="matrix(-0.707107 -0.707107 -0.707107 0.707107 22.0972 7.95496)" fill="white"></rect>
                                      <rect width="2.5" height="20" rx="1.25" transform="matrix(-0.707107 0.707107 0.707107 0.707107 7.95526 6.18689)" fill="black"></rect>
                                      <rect width="2.5" height="20" rx="1.25" transform="matrix(-0.707107 0.707107 0.707107 0.707107 7.95526 6.18689)" fill="white"></rect>
                                    </svg>
                                  </button>
                              </div>`;
                $(html_erorr).insertAfter("a.back-link");
            });
        }

        function checkPlan(current_plan_id) {
            var value = $('ul.payment-select li.active').attr('data-id');

            if (current_plan_id == value) {
                return false;
            } else {
                $('#paymentMethodSetting').modal('show');
            }
        }
    </script>
@endsection
