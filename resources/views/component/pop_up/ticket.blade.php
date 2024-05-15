<div id="point">
    <!-- poup buy point -->
    <div class="modal fade popup-style popup-style1 popup-bottom-sp popup-top" id="buyticket" tabindex="-1"
         aria-modal="true" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <button class="close" data-bs-dismiss="modal" aria-hidden="true"></button>
                <div class="modal-body">
                    <h2 class="pp-tit">チケット購入</h2>
                    <div class="block-buyticket">
                        <dl>
                            <dt><img src="{{ asset('student/images/icon-ticket.svg') }}" alt="1枚"><span>1枚</span></dt>
                            <dd>
                                <button class="btn btn-primary btn-org-radius" onclick="paymentMethod(1)">￥{{ number_format(EStripe::TICKETS_PRICE_UNIT) }}</button>
                            </dd>
                        </dl>
                        <dl>
                            <dt><img src="{{ asset('student/images/icon-ticket.svg') }}" alt="3枚"><span>3枚</span></dt>
                            <dd>
                                <button class="btn btn-primary btn-org-radius" onclick="paymentMethod(3)">￥{{ number_format(EStripe::TICKETS_PRICE_UNIT * 3) }}
                                </button>
                            </dd>
                        </dl>
                        <dl>
                            <dt><img src="{{ asset('student/images/icon-ticket.svg') }}" alt="5枚"><span>5枚</span></dt>
                            <dd>
                                <button class="btn btn-primary btn-org-radius" onclick="paymentMethod(5)">￥{{ number_format(EStripe::TICKETS_PRICE_UNIT * 5) }}
                                </button>
                            </dd>
                        </dl>
                        <dl>
                            <dt><img src="{{ asset('student/images/icon-ticket.svg') }}" alt="10枚"><span>10枚</span></dt>
                            <dd>
                                <button class="btn btn-primary btn-org-radius" onclick="paymentMethod(10)">￥{{ number_format(EStripe::TICKETS_PRICE_UNIT * 10) }}
                                </button>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade popup-style popup-style1 popup-bottom-sp popup-top" id="paymentMethod" tabindex="-1"
         style="display: none;" aria-hidden="true"></div>


    <div class="modal fade popup-style popup-style1 popup-bottom-sp popup-top" id="card" tabindex="-1"
         style="display: none;" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <button class="close" data-bs-dismiss="modal" aria-hidden="true"></button>
                <div class="modal-body">
                    <div class="payment-box">
                        <h2 class="pp-tit">お支払方法選択</h2>
                        <figure><img src="{{ asset('student/images/img-card.png') }}" alt=""></figure>
                        <form id="submit_form">
                            <dl>
                                <dt>カード番号</dt>
                                <dd id="poup_card_number" style="height: 40px; background: #fbfbfb;">
                                    <!-- <input class="form-control numberonly" type="text" name="" placeholder="0000000000000000" maxlength="12"> -->
                                </dd>
                            </dl>
                            <dl>
                                <dt>有効期限</dt>
                                <dd id="poup_card_expiry" style="height: 40px; background: #fbfbfb;">
                                    <!-- <input class="form-control numberonly" type="text" name="" placeholder="mm/yy"> -->
                                </dd>
                            </dl>
                            <dl>
                                <dt>
                                    <div class="fsc"><span class="text">セキュリティコード</span><span class="csv">CVCとは</span>
                                        <div class="show-btn" data-bs-dismiss="modal" aria-hidden="true"
                                             data-bs-toggle="modal" data-bs-target="#cvcPopup"><img
                                                    src="./images/show-btn.svg" alt=""></div>
                                    </div>
                                </dt>
                                <dd id="poup_card_csv" style="height: 40px; background: #fbfbfb;">
                                    <!-- <div class="box-cvc" > -->
                                    <!-- <input class="form-control numberonly" type="text" name="" placeholder="123" maxlength="4"> -->
                                    <!-- </div> -->
                                </dd>
                            </dl>
                            <div class="btn-wrapper">
                                <button class="btn btn-primary btn-fullw">登録する</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade popup-style popup-style1 popup-bottom-sp popup-top" id="edit_card" tabindex="-1"
         style="display: none;" aria-hidden="true">
    </div>

    <div class="modal fade popup-style popup-bottom-sp" id="cvcPopup" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <button class="close" data-bs-dismiss="modal" aria-hidden="true"></button>
                <div class="modal-body">
                    <h2 class="cvc-title">CVCとは</h2>
                    <p>
                        CVCとは、セキュリティコードと呼ばれる番号のことです。クレジットカードやデビットカード裏面のサインパネルに記載されている数字の末尾3~4桁のこと指していて、本人確認をするためや不正利用を防ぐ役割があります。その他にも、インターネットショッピングでカード決済をする際に、カードが利用者自身お手元にあることを確認するために使われることが多いです。</p>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- <script src="https://js.stripe.com/v3/"></script> -->

<script>

$('#paymentMethod').on('shown.bs.modal', function (e) {

    var stripe_poup = Stripe( _STRIPE_KEY, {
                locale: 'ja'
    });

    var elements = stripe_poup.elements();

    var cardNumberElement = elements.create('cardNumber');
    cardNumberElement.mount('#poup_card_number');

    var cardExpiryElement = elements.create('cardExpiry');
    cardExpiryElement.mount('#poup_card_expiry');

    var cardCvCElement = elements.create('cardCvc');
    cardCvCElement.mount('#poup_card_csv');

    var form = document.getElementById('submit_form');

    form.addEventListener('submit', function (event) {

        event.preventDefault();
        $('#loading').show();
        event.preventDefault();

        stripe_poup.createToken(cardNumberElement, {}).then(function (result) {

            if (result.error) {
                //$('div.payment-box').append
                $(`<div class="alert alert-dark"><span>${result.error.message}</span></div>`).insertAfter(".pp-tit");
                $('#loading').hide();
                return false;
                //handleShowError(result.error.message);
            } else {

                axios.post(URL_ADD_PAYMENT, {_method: "PUT", token_stripe: result.token.id}).then((response) => {

                    if (response.status == 200) {
                        $('#card').modal('hide');
                        paymentMethod(_tickets);
                    }

                })
            }

        });

    })

})
</script>