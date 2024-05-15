以下URLのフォームより、お客様からお問い合わせをいただきました。
<br><br>
●お名前:<br>
&nbsp; {{ $details['name'] }} <br><br>

●メールアドレス:<br>
&nbsp; {{ $details['email'] }} <br><br>

●カテゴリ<br>
&nbsp; {{ EInquiry::getTextByOptions($details['options']) }} <br><br>

@if($details['options'] === 'request_cancel')
●リクエスト名<br>
    &nbsp; {{ $details['request_complete_name'] }} <br><br>
@endif

●詳細<br>
&nbsp; {{ $details['content'] }} <br><br>

<br><br>
========================================================
<!-- include("component.mail.footer") -->