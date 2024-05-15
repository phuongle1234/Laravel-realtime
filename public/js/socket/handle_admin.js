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
        showNoticationBroadcast(e);
  });
  // socket.listen('MessageSentEvent', (e) => {
  //     //count_message
  //     $('.count_message').addClass('show');
  //     $('.count_message').text(e.count);
  // });

function showNoticationBroadcast(obj){

  // onclick="redirectToRequestManagement('${obj.id}')"
  //count noti
      $('div.blockheader ul.dropdown-menu').prepend(
                `<li class="active">
                    <a href="${window.location.origin}/admin/video_management/list_approval" >
                      <figure><img src="${window.location.origin}/student/common_img/logo-infobox.png" alt="${obj.title} ${obj.created_at}"></figure>
                      <div class="text">
                          ${obj.title}<span class="time">${obj.created_at}</span>
                      </div>
                    </a>
                  </li>`
      );

    $('.count_noti').show();
    $('.count_noti').addClass('show');
    $('.count_noti').text(obj.count_unread);
}