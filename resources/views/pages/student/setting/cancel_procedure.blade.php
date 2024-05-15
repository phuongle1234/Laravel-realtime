@extends('layouts.student.index')

@section('body_class','main_body p-setting cancel_procedure hasbg')
@section('class_main','p-content-setting cancel_procedure')

@section('content')
    <div class="setting-wrapper"><a class="back-link fsc" href="{{ route('student.setting.plan_change') }}"><img src="{{ asset('../images/back-icon.svg') }}" alt="無料プランへの変更手続き"><span>無料プランへの変更手続き</span></a>
        <div class="setting-bg" action="">
            <h3 class="cancle-tit">無料プランへ変更するとedutossの有料会員専用の機能が{{ $_user->current_period_end }}より使用できなくなります</h3>
            <p class="text_center">
                無料プランへ変更すると、{{ $_user->current_period_end }}時点でのチケットおよび、有料会員専用の機能が使用できなくなります。<br>
                チケットが残っている場合でも、払い戻し等はできませんのであらかじめご了承ください。<br>
                再度、有料プランにお申込みいただくことで、引き続きedutossの有料会員専用の機能をご使用いただけます。
            </p>
            <div class="text_center">
                <button class="btn btn-primary mt00" data-bs-toggle="modal" data-bs-target="#confirmDltProc">上記に同意して無料プランに変更する</button>
            </div>
        </div>
    </div>
    <div class="modal fade popup-style popup-style1 popup-bottom-sp confirmDlt" id="confirmDltProc" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <button class="close" data-bs-dismiss="modal" aria-hidden="true"></button>
                <div class="modal-body">
                    <h3 class="title">確認</h3>
                    <p class="content-txt">本当に解約しますか。</p>
                </div>
                <div class="modal-footer">
                    <div class="fce btn-pp">
                        <button class="btn btn-dark" data-bs-dismiss="modal" aria-hidden="true">戻る</button>
                        <button class="btn btn-primary" data-bs-dismiss="modal" onclick="cancelPlan()" aria-hidden="true">解約する</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom_js')
<script>
    $(document).ready(function(){
    });

    function cancelPlan(){
        $('#loading').show();
        var cancelPlanURL = @JSON(route('student.setting.cancel'));

        axios.post(cancelPlanURL, {_method:"POST"}).then((response) => {
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
</script>
@endsection
