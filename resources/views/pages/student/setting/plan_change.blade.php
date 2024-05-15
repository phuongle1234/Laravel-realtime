@extends('layouts.student.index')

@section('body_class','main_body p-setting plan_change hasbg')
@section('class_main','p-content-setting plan_change')

@section('content')
    <a class="back-link fsc" href="../setting"><img src="{{ asset('../images/back-icon.svg') }}" alt="プラン変更"><span>{{ trans('student.setting-plan_change') }}</a></span>
    <div class="setting-bg">
        <form class="setting-content">

            @if( $_user_stripe->plan_id !== EStripe::FREE_PLAN_ID )
                <h3 class="setting-title"> {{ $_period_end ? '無料プランへの変更日' : '次回のお支払予定日' }} {{ $_user->current_period_end }}</h3>
            @endif

            <div class="fsc planchange-tit">
                <div class="title">現在のプラン</div><a class="btn btn-primary" href="{{ route('student.setting.plan_payment') }}">プランを変更する</a>
            </div>

            <ul class="select-list none-select clmain-select">
                @include('component.student.setting.show_current_plan',['plan_id' => $_user_stripe->plan_id])
            </ul>

            @if( ! $_period_end )
                <div class="text_center"><a class="linkpage" href="{{ route('student.setting.cancel') }}">無料プランへの変更はこちら</a></div>
            @endif
        </form>
    </div>
@endsection

@section('custom_js')
<script>
    $(document).ready(function(){
    });
</script>
@endsection
