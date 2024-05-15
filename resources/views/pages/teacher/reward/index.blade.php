@extends('layouts.teacher.index')
@section('body_class','p-reward_management pdbottom hasbg')
@section('class_main','p-content-reward_management pdbottom')

@section('content')
    @include('component.erorr.custom')
    <div class="tit-main"><span>報酬リクエスト</span></div>
    <div class="boxshadow">
        <dl>
            <dt><span class="tit">現在の報酬</span><span class="tag">￥{{ number_format($teacher->reward) ?? 0 }}</span></dt>
        </dl>
        <dl>
            <dt><span class="tit">報酬申請</span></dt>
            <dd>
                <form method="POST">
                    @csrf
                    <div class="fsc fixwidth">
                        <input class="form-control form-control-number" autocomplete="off" type="number"
                               name="amount"
                               minlength="4"
                               maxlength="7"
                               inputmode="numeric"
                               oninput="checkChar(this)">
                        <button class="btn btn-primary">リクエストする</button>
                    </div>
                    <p>
                        振込する報酬額を設定することができます。<br>
                        振込の最低金額は1,000円からとなっておりますので、ご了承ください。<br>
                        また、振込手数料はご負担いただきますようお願いいたします。<br><br>
                        ※なお、振り込みまでに数日かかる場合がございます。ご了承ください。
                    </p>
                </form>
            </dd>
        </dl>
    </div>
@endsection
@section('custom_js')
    <script>
        function checkChar(e){
            if(e.value == "") e.value = "";
            if (e.value.length > e.maxLength) e.value = e.value.slice(0, e.maxLength);
        }
    </script>
@endsection
