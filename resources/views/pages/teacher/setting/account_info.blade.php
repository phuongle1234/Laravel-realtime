@extends('layouts.teacher.index')

@section('body_class','p-setting account_info pdbottom hasbg')

@section('class_main','p-content-setting account_info pdbottom')

@section('content')
<section>
    <div class="setting-wrapper">
      {{-- <div class="alert alert-dark"><span>登録が完了しました</span>
        <button class="btn-close" type="button">
          <svg xmlns="http://www.w3.org/2000/svg" width="29" height="29" viewBox="0 0 29 29" fill="none">
            <rect width="2.5" height="20" rx="1.25" transform="matrix(-0.707107 -0.707107 -0.707107 0.707107 22.0972 7.95496)" fill="black"></rect>
            <rect width="2.5" height="20" rx="1.25" transform="matrix(-0.707107 -0.707107 -0.707107 0.707107 22.0972 7.95496)" fill="white"></rect>
            <rect width="2.5" height="20" rx="1.25" transform="matrix(-0.707107 0.707107 0.707107 0.707107 7.95526 6.18689)" fill="black"></rect>
            <rect width="2.5" height="20" rx="1.25" transform="matrix(-0.707107 0.707107 0.707107 0.707107 7.95526 6.18689)" fill="white"></rect>
          </svg>
        </button>
      </div> --}}
      <a class="back-link fsc" href="../setting"><img src="{{asset('images/back-icon.svg')}}" alt="口座情報設定"><span>口座情報設定</span></a>
      @include('component.register.alert')
      <div class="setting-bg" action="">
        <form class="setting-content" action URL="{{ URL::current() }}" method="post" id="form-setting">
          @csrf
          <div class="setting-body">
            <div class="box-csv">
              <dl class="noinline">
                <dt>金融機関コード</dt>
                <dd>
                  <input class="form-control" type="text" name="bank_code" required value="{{old('bank_code',@$item->bank_code)}}">
                </dd>
              </dl>
              <dl class="noinline">
                <dt>支店コード</dt>
                <dd>
                  <input class="form-control" type="text" name="branch_code" required value="{{old('branch_code',@$item->branch_code)}}">
                </dd>
              </dl>
            </div>
            <dl class="noinline">
              <dt>口座番号</dt>
              <dd>
                <input class="form-control" type="text" name="bank_account_number" required value="{{old('bank_account_number',@$item->bank_account_number)}}" maxlength="12">
              </dd>
            </dl>
            <dl class="noinline">
              <dt>口座名義人</dt>
              <dd>
                <input class="form-control" type="text" pattern="^([ァ-ン（）]|ー)+$" name="bank_account_name" required value="{{old('bank_account_name',@$item->bank_account_name)}}">
              </dd>
            </dl>
            <dl class="noinline">
              <dt>預金種目</dt>
              <dd>
                <ul class="list-courses">
                  <li>
                    <input type="radio" required value="1" name="bank_account_type" @if(@$item->bank_account_type == 1) checked @endif>
                    <div><span>普通</span></div>
                  </li>
                  <li>
                    <input type="radio" required value="2" name="bank_account_type"  @if(@$item->bank_account_type == 2) checked @endif>
                    <div><span>当座</span></div>
                  </li>
                </ul>
              </dd>
            </dl>
          </div>
          <div class="text_center">
            <button class="btn btn-primary">登録する</button>
          </div>
        </form>
      </div>
    </div>
  </section>
  @endsection

@section('custom_js')
  <script src="https://js.stripe.com/v3/"></script>

  <script>

      let error = "";
      // let _token = "{{ csrf_token() }}";
      let stripe = Stripe("{{ env('STRIPE_KEY') }}",{
        locale: 'ja'
      });

      let error_code = {
                'bank_account[routing_number]' : '金融コードまた支店コードが無効です',
                'bank_account[account_holder_name]' : '口座名が無効です',
                'bank_account[account_number]' : '口座番号が無効です',
      };

      var form = document.getElementById('form-setting');

      form.addEventListener('submit', async (event) => {

          event.preventDefault();
          var val = $('input[name="bank_account_name"]').val();
          val = val.replaceAll('　','');
          if(val.length == 0){
              handleShowError('口座名義人は必須です');
              return false;
          }

          $('#progess .btn-wrapper').hide();
          $('#progess').show();

        const _result = await  stripe.createToken('bank_account',
                                      {
                                        country: 'JP',
                                        currency: 'jpy',
                                        routing_number: $('input[name="bank_code"]').val()+$('input[name="branch_code"]').val(),
                                        account_number: $('input[name="bank_account_number"]').val(),
                                        account_holder_name: $('input[name="bank_account_name"]').val()
                                        // account_holder_type: $('select[name="business_type"]').val(),
                                      }
                               );

         if( _result.error )
         {
            code_error = _result.error.param;
            handleShowError(error_code[code_error]);
            return false;
         }

         if(_result.token){
          // handleSubmit(result.token.id);
          // $('#form-setting').append($('<input>',{name:'token_stripe', type:'hidden',value:`${result.token.id}`}))
            $('#form-setting').submit();
          }

      })

    //   .then(function(result){

    //   if(result.error){
    //       code_error = result.error.param;
    //       handleShowError(error_code[code_error]);
    //   }

    //   if(result.token){
    //       // handleSubmit(result.token.id);
    //       // $('#form-setting').append($('<input>',{name:'token_stripe', type:'hidden',value:`${result.token.id}`}))
    //       $('#form-setting').submit();
    //   }

    // })

    function handleShowError(messege_errors) {

        $('#progess').hide();

          html_erorr =    `<div class="alert alert-dark"><span>${messege_errors}</span>
                                <button class="btn-close" type="button" onclick="$(this).parent().remove()">
                                  <svg xmlns="http://www.w3.org/2000/svg" width="29" height="29" viewBox="0 0 29 29" fill="none">
                                    <rect width="2.5" height="20" rx="1.25" transform="matrix(-0.707107 -0.707107 -0.707107 0.707107 22.0972 7.95496)" fill="black"></rect>
                                    <rect width="2.5" height="20" rx="1.25" transform="matrix(-0.707107 -0.707107 -0.707107 0.707107 22.0972 7.95496)" fill="white"></rect>
                                    <rect width="2.5" height="20" rx="1.25" transform="matrix(-0.707107 0.707107 0.707107 0.707107 7.95526 6.18689)" fill="black"></rect>
                                    <rect width="2.5" height="20" rx="1.25" transform="matrix(-0.707107 0.707107 0.707107 0.707107 7.95526 6.18689)" fill="white"></rect>
                                  </svg>
                                </button>
                            </div>`;
        var alert_block = $('.alert-dark');
        if(alert_block.length > 0){
            alert_block.remove();
        }
      $( "div.setting-wrapper" ).prepend(html_erorr);
//
// $('button[type="submit"]').prop("disabled",false);
// $('button[type="submit"]').parent().removeClass("disabled");
//
    $('div.scroll-nonedefault').animate({ scrollTop: $('body').offset().top}, 1000);
  }

</script>
@endsection