@extends('layouts.student.index')

@section('body_class','main_body p-setting faq hasbg')
@section('class_main','p-content-setting faq')

@section('class_main','p-content-setting')

@section('content')
<div class="setting-wrapper">

        <span class="back-link fsc" >
                <a href="{{ route('student.home') }}"><img src="{{ asset('student/images/back-icon.svg') }}" alt="{{ trans("common.setting.payment") }}"></a>
                <span>{{ trans("common.faq.question") }}</span>
        </span>

        @include('component.register.alert')
        <div class="faq-block">
            <dl>
              <dt class="acr_title open">
                <div class="text question">無料会員でリクエストチケットを購入することはできますか。</div>
              </dt>
              <dd class="acr_con">
                <div class="text answer">できません。チケットを購入できるのは有料会員（edutossフル利用プラン、チケット付きプラン）のみとなります。</div>
              </dd>
            </dl>
            <dl>
              <dt class="acr_title">
                <div class="text question">リクエストをしましたが、受諾されずにキャンセルとなりました。
                </div>
              </dt>
              <dd class="acr_con">
                <div class="text answer">edutossには多数の有名大学在学中・出身の先生が在籍していますが、以下のような場合にはリクエストが受諾されない可能性があります。<br>
                  ・質問の解答が容易に説明できるものではない。
<br>
・１回のリクエストに複数の質問が含まれている。
<br>
なお、難易度の高い問題で解答・解説があるものは、それを併せてリクエストして頂けると先生側が安心して授業を制作できます。
                </div>
              </dd>
            </dl>
            <dl>
              <dt class="acr_title">
                <div class="text question">リクエストが受諾されましたが、解説授業が送られてきません。</div>
              </dt>
              <dd class="acr_con">
                <div class="text answer">
                  先生は、リクエストを受諾してから48時間以内に動画を制作するルールとなっています。<br>
                  動画の作成の状況は運営事務局において定期的に確認を行っており、遅延が確認された時点でご連絡を差し上げています。運営事務局から連絡が無い場合にはお手数ですが、「各種設定」→「お問い合わせ」よりお問い合わせください。
                </div>
              </dd>
            </dl>
            <dl>
              <dt class="acr_title">
                <div class="text question">先生が制作した解説授業について質問をしようとしたら、メッセージが送れませんでした。</div>
              </dt>
              <dd class="acr_con">
                <div class="text answer">
                  先生に適正な報酬をお支払いするために、生徒側でリクエストを完了する動作をしなくても、リクエストをしたときから10日経過後に自動でリクエストが完了される仕様となっています。<br>
                  リクエスト完了後に先生とメッセージのやり取りはできませんのでご注意ください。
                </div>
              </dd>
            </dl>
            <dl>
              <dt class="acr_title">
                <div class="text question">大学検索をする際に、特定の大学が出てきません。</div>
              </dt>
              <dd class="acr_con">
                <div class="text answer">
                  一部の大学に関しては、正式名称ではなく略称で登録されています。<br>
                  大学名に含まれる文字を１文字～２文字入力した上で、候補の中から探すようにしてください。
                </div>
              </dd>
            </dl>
            <dl>
              <dt class="acr_title">
                <div class="text question">解説動画に対して質問はできますか。</div>
              </dt>
              <dd class="acr_con">
                <div class="text answer">
                  ご自身がリクエストした解説授業については、リクエストが完了するまでの間はメッセージから質問ができます（リクエスト完了後の質問はできません）。動画一覧にある解説動画に対して質問することはできません。
                </div>
              </dd>
            </dl>
            <dl>
              <dt class="acr_title">
                <div class="text question">先生のプロフィールにある緑色のマークは何を意味しますか。</div>
              </dt>
              <dd class="acr_con">
                <div class="text answer">
                  先生が24時間以内にログイン状態となっている場合に、緑色のマークが表示されます。
                </div>
              </dd>
            </dl>
            <dl>
              <dt class="acr_title">
                <div class="text question">有料会員を解約した場合には、いつからedutossの機能が利用できなくなりますか。</div>
              </dt>
              <dd class="acr_con">
                <div class="text answer">
                  解約をした場合には、次回の引き落とし日に機能が制限されます。
                </div>
              </dd>
            </dl>
            <dl>
              <dt class="acr_title">
                <div class="text question">有料会員を解約した場合には、未使用のチケットは返金されますか。</div>
              </dt>
              <dd class="acr_con">
                <div class="text answer">
                  いかなる理由でもお客様都合でのキャンセルはできません。また、無料会員となることでチケットを使用することはできなくなるので解約日までにチケットを消化するようにしてください。
                </div>
              </dd>
            </dl>
      </div>
@endsection

@section('custom_js')

<script>

</script>
@endsection
