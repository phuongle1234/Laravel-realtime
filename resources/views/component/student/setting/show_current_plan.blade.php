@if($plan_id === EStripe::STANDARD_PLAN_ID)
    <li><span class="title">{{ EStripe::STANDARD_PLAN_NAME }}</span><span
                class="text">{!! nl2br( EStripe::getDescriptByPlan( $plan_id  ) ) !!}</span><span
                class="unit">￥{{ number_format(EStripe::STANDARD_PLAN_PRICE) }}<small>（税込）</small></span></li>

@elseif($plan_id === EStripe::PREMIUM_PLAN_ID)
    <li><span class="title">{{ EStripe::PREMIUM_PLAN_NAME }}</span>
        <span class="text">{!! nl2br( EStripe::getDescriptByPlan( $plan_id  ) ) !!}</span>
        <span class="unit">￥{{ number_format(EStripe::PREMIUM_PLAN_PRICE) }}<small>（税込）</small></span></li>
@else
    <li>
        <span class="title">{{ EStripe::FREE_PLAN_NAME }}</span>
        <span class="text">{!! nl2br( EStripe::getDescriptByPlan( $plan_id  ) ) !!}</span>
    </li>
@endif