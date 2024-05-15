@switch($plan_id)
    @case(EStripe::STANDARD_PLAN_ID)
        <li class="active" data-id="2">
            <div class="tag"><span>現在の<br>プラン</span></div>
            <span class="title">{{ EStripe::STANDARD_PLAN_NAME }}</span><span
                    class="text">{!! nl2br( EStripe::getDescriptByPlan( EStripe::STANDARD_PLAN_ID  ) ) !!}</span><span
                    class="unit">￥{{ number_format(EStripe::STANDARD_PLAN_PRICE) }}<small>（税込）</small></span></li>
        <li data-id="3" class="label" ><span class="title">{{ EStripe::PREMIUM_PLAN_NAME }}</span>
            <span class="text">{!! nl2br( EStripe::getDescriptByPlan( EStripe::PREMIUM_PLAN_ID  ) ) !!}</span>
            <span class="unit">￥{{ number_format(EStripe::PREMIUM_PLAN_PRICE) }}<small>（税込）</small></span></li>
        @break
    @case(EStripe::PREMIUM_PLAN_ID)
        <li data-id="2"><span class="title">{{ EStripe::STANDARD_PLAN_NAME }}</span><span
                    class="text">{!! nl2br( EStripe::getDescriptByPlan( EStripe::STANDARD_PLAN_ID  ) ) !!}</span><span
                    class="unit">￥{{ number_format(EStripe::STANDARD_PLAN_PRICE) }}<small>（税込）</small></span></li>
        <li data-id="3" class="active label">
            <div class="tag"><span>現在の<br>プラン</span></div>
            <span class="title">{{ EStripe::PREMIUM_PLAN_NAME }}</span>
            <span class="text">{!! nl2br( EStripe::getDescriptByPlan( EStripe::PREMIUM_PLAN_ID  ) ) !!}</span>
            <span class="unit">￥{{ number_format(EStripe::PREMIUM_PLAN_PRICE) }}<small>（税込）</small></span></li>
        @break
    @default
        <li data-id="2"><span class="title">{{ EStripe::STANDARD_PLAN_NAME }}</span><span
                    class="text">{!! nl2br( EStripe::getDescriptByPlan( EStripe::STANDARD_PLAN_ID  ) ) !!}</span><span
                    class="unit">￥{{ number_format(EStripe::STANDARD_PLAN_PRICE) }}<small>（税込）</small></span></li>
        <li data-id="3" class="label" >
            <span class="title">{{ EStripe::PREMIUM_PLAN_NAME }}</span>
            <span class="text">{!! nl2br( EStripe::getDescriptByPlan( EStripe::PREMIUM_PLAN_ID  ) ) !!}</span>
            <span class="unit">￥{{ number_format(EStripe::PREMIUM_PLAN_PRICE) }}<small>（税込）</small></span></li>
    @break
@endswitch

