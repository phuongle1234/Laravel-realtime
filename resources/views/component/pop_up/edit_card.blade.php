
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <button class="close" data-bs-dismiss="modal" aria-hidden="true"></button>
              <div class="modal-body">
                <div class="payment-box">
                  <h2 class="pp-tit">お支払方法選択</h2>
                  <figure><img src="{{ asset('student/images/img-card.png') }}" alt=""></figure>
                  <form id="edit_form_card">
                    <dl>
                      <dt>カード番号</dt>
                      <dd id="edit_card_number" style="height: 40px; background: #fbfbfb;">
                        <!-- <input class="form-control numberonly" type="text" name="" placeholder="0000000000000000" maxlength="12"> -->
                      </dd>
                    </dl>
                    <dl>
                      <dt>有効期限</dt>
                      <dd id="edit_card_expiry" style="height: 40px; background: #fbfbfb;">
                        <!-- <input class="form-control numberonly" type="text" name="" placeholder="mm/yy"> -->
                      </dd>
                    </dl>
                    <dl>
                      <dt>
                        <div class="fsc"><span class="text">セキュリティコード</span><span class="csv">CVCとは</span>
                          <div class="show-btn" data-bs-dismiss="modal" aria-hidden="true" data-bs-toggle="modal" data-bs-target="#cvcPopup"><img src="./images/show-btn.svg" alt=""></div>
                        </div>
                      </dt>
                <dd id="edit_card_csv" style="height: 40px; background: #fbfbfb;">
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

<script>
var _cardNumber = '{{ $_card->last4 }}';
var _card = '{{ $_card->id }}';
var _cardExpiry = '{{ $_card->exp_month }}/{{ substr($_card->exp_year,2) }}'

let stripe_poup_edit = Stripe(_STRIPE_KEY,{
    locale: 'ja'
  });

var elements_edit = stripe_poup_edit.elements();
 cardNumber = elements_edit.create('cardNumber',{placeholder: `**** **** **** ${_cardNumber}` });
 cardNumber.mount('#edit_card_number');

 cardExpiry = elements_edit.create('cardExpiry',{placeholder: `${_cardExpiry}` });
 cardExpiry.mount('#edit_card_expiry');

 cardCvC = elements_edit.create('cardCvc',{placeholder: `****` });
 cardCvC.mount('#edit_card_csv');

var form_edit = document.getElementById('edit_form_card');

form_edit.addEventListener('submit', function(event){

  event.preventDefault();
  $('#loading').show();
  event.preventDefault();

  stripe_poup_edit.createToken(cardNumber,{  }).then(function(result){

      if(result.error){
        $( `<div class="alert alert-dark"><span>${result.error.message}</span></div>`).insertAfter( "#edit_card .pp-tit" );
            $('#loading').hide();
            return false;
      }else{

        axios.post(URL_UPDATE_PAYMENT,{ _method:"PUT", token_stripe: result.token.id, card_id: _card  }).then((response) => {
                  if(response.status == 200){
                      $('#edit_card').modal('hide');
                      $('#edit_card').empty();
                      paymentMethod(_tickets);
                  }
          })
      }

  });

})
</script>