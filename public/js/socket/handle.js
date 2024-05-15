var id = $('meta[name="csrf_id"]').attr('content');
var _token = $('meta[name="csrf-token"]').attr('content');
var _token_acsset = $('meta[name="token_acsset"]').attr('content');


// 'Accept' => 'application/json',
// 'Authorization' => 'Bearer '.$accessToken,
 // headers : {
//   'X-CSRF-TOKEN': _token
// }

let echo = new Echo({
    broadcaster: "socket.io",
    host: window.location.hostname+SCOKET_PORT,
    encrypted: true,
    auth:{
      headers:{
        'Accept' : 'application/json',
        'Authorization' : 'Bearer '+_token_acsset
      }
    }
});


var socket = echo.private('App.Models.User.'+id);
  socket.notification((e) => {
        showNotificationBroadcast(e);
  });
  socket.listen('MessageSentEvent', (e) => {
      //count_message
      $('.count_message').addClass('show');
      $('.count_message').text(e.count);
  });

const showNotificationBroadcast =  async (obj) => {


  //count noti
      $('ul#notifications').prepend(
                `<li class="active">
                    <a href="${window.location.origin}/student_mypage/notification/${obj.id}">
                      <figure><img src="${window.location.origin}/student/common_img/logo-infobox.png" alt="${obj.title} ${obj.created_at}"></figure>
                      <div class="text">
                          ${obj.title}<span class="time">${obj.created_at}</span>
                      </div>
                    </a>
                  </li>`
      );

    $('.count_noti').addClass('show');
    $('.count_noti').text(obj.count_unread);

    const _count_message = await axios.post(`${window.location.origin}/getNumberMessage`,{ _method:"PUT", role: 'student'  });

    if( _count_message.status == 200 && _count_message.data  )
    {
      $('.count_message').addClass('show');
      $('.count_message').text(_count_message.data);
    }

}

function paymentMethod(tickets){

      _tickets = tickets;
      $('#loading').show();

    axios.post(URL_PAYMENT).then( async (response) => {
        if(response.status==200){

            ///$(document).ready(function() {

                $.getScript("https://js.stripe.com/v3/",() => {

                    $('#loading').hide();
                    $('#paymentMethod').html(response.data);
                    $('#paymentMethod').modal('show');

                })

        }
    })

}

//

function charges(){
  //URL_BUY_POINT
  $('#loading').show();
  axios.post(URL_BUY_POINT,{ _method:'PATCH', tickets: _tickets }).then((response) => {

        if(response.status == 200){
          $('#loading').hide();
          $('#point div.modal').modal('hide')
          $('span.point').text(response.data.tickets);
          $('div.btn-ticket button:disabled').prop("disabled",false);
        }
  })
}

function updateDefaultCard( _card ){

  $('#loading').show();
  axios.post(URL_DEFAULT_PAYMENT,{ _method:'PATCH', card_id: _card }).then((response) => {

      if(response.status==200){
        $('#loading').hide();
          paymentMethod(_tickets);
      }
  })
  //URL_DEFAULT_PAYMENT
}

function updateEditCard( _card ){
//URL_INFOR_PAYMENT
$('#loading').show();
  axios.post(URL_INFOR_PAYMENT,{ card_id: _card }).then((response) => {

    if(response.status==200){
      $('#loading').hide();
      $('#edit_card').html(response.data);
      $('#edit_card').modal('show');
      return true;
    }
  })

}