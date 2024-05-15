@php
  $_count_direct = $_user->nominationRequests()->where(['status' => EStatus::PENDING, 'is_displayed' => EStatus::IS_DISPLAYED])->count();
@endphp

<div class="fsc warning-block" {{ !$_count_direct ? 'style=display:none' : null }} onclick="scrollDirect()">
    <img src="{{ asset('teacher/common_img/icon-warning-w.svg') }}" alt="">
    <p><span class="count_direct">{{ $_count_direct }}</span>件のリクエスト受注の依頼が来ています。<br>内容を確認し、受注を確定させてください。</p>
</div>
