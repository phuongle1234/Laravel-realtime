var id = $('meta[name="csrf_id"]').attr('content');
var _token = $('meta[name="csrf-token"]').attr('content');
var _token_acsset = $('meta[name="token_acsset"]').attr('content');

let echo = new Echo({
    broadcaster: "socket.io",
    host: window.location.hostname+SCOKET_PORT,
    encrypted: true,
    auth:{
      headers : {
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

function showNotificationBroadcast(obj){

  //count noti
      $('ul#notifications').prepend(
                `<li class="active">
                    <a href="${window.location.origin}/teacher_mypage/notification/${obj.id}">
                      <figure><img src="${window.location.origin}/student/common_img/logo-infobox.png" alt="${obj.title} ${obj.created_at}"></figure>
                      <div class="text">
                          ${obj.title}<span class="time">${obj.created_at}</span>
                      </div>
                    </a>
                  </li>`
      );

    $('.count_noti').addClass('show');
    $('.count_noti').text(obj.count_unread);


}

// const getNumberMessage =  async () => {

//     const _count_message = await axios.post(`${window.location.origin}/getNumberMessage`,{ _method:"PUT", role: 'student'  });

//     if( _count_message.status == 200 && _count_message.data  )
//     {
//       $('.count_message').addClass('show');
//       $('.count_message').text(_count_message.data);
//     }

// }

