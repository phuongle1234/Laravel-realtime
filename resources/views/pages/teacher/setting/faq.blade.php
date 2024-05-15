@extends('layouts.teacher.index')
@section('body_class','p-setting faq pdbottom hasbg')
@section('class_main','p-content-setting faq pdbottom')
@section('content')
<section>
  <div class="setting-wrapper"><div class="back-link fsc"><a href="{{route('teacher.home')}}"><img src="{{asset('images/back-icon.svg')}}" alt="よくある質問"></a><span>よくある質問</span></div>
    <div class="faq-block">
      <dl>
        <dt class="acr_title open">
          <div class="text question">リクエストを受諾しましたが、質問の意図が分からないところがあります。</div>
        </dt>
        <dd class="acr_con">
          <div class="text answer">リクエスト受諾後に生徒とメッセージのやり取りが可能となります。解説授業を制作する上で不明点があれば、メッセージを利用して確認をしてください。</div>
        </dd>
      </dl>
      <dl>
        <dt class="acr_title">
          <div class="text question">リクエストはどこから受諾できますか。</div>
        </dt>
        <dd class="acr_con">
          <div class="text answer">「リクエスト一覧」や「Home画面」より科目を選択して受諾することができます。</div>
        </dd>
      </dl>
      <dl>
        <dt class="acr_title">
          <div class="text question">解説授業はどのように作成すれば良いでしょうか。</div>
        </dt>
        <dd class="acr_con">
          <div class="text answer">解説授業作成にあたっての注意事項は作成方法は先生マニュアルに記載しています。
          </div>
        </dd>
      </dl>
      <dl>
        <dt class="acr_title">
          <div class="text question">海外旅行に行くのでしばらくの間リクエストを受諾できないのですが。</div>
        </dt>
        <dd class="acr_con">
          <div class="text answer">直接リクエストを受注する事ができない場合には、「各種設定」→「リクエスト受付設定」より直接リクエストを受け付けるボタンをオフにしてください。
          </div>
        </dd>
      </dl>
      <dl>
        <dt class="acr_title">
          <div class="text question">報酬を請求してしばらく経ちますが、報酬が振り込まれません。</div>
        </dt>
        <dd class="acr_con">

        <div class="text answer">送金手続き中にエラーが生じている可能性があります。
        </div>
        </dd>
      </dl>
      <dl>
        <dt class="acr_title">
          <div class="text question">報酬の請求をキャンセルすることはできますか。</div>

        </dt>
        <dd class="acr_con">

        <div class="text answer">報酬リクエストより請求された時点で送金手続きを開始するため、請求後のキャンセルはできません。
        </div>
        </dd>
      </dl>
    </div>
  </div>
</section>
@endsection