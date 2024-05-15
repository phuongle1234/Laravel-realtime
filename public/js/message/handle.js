var id = $('meta[name="csrf_id"]').attr('content');
var user = { name :  $('li.user-icon span').text(), avatar: $('li.user-icon img').attr('src'), id : id };

console.log( VuePureLightbox  );

let vue=  new Vue({
      el: "#app",
      components: { 'vue-pure-lightbox': VuePureLightbox },
      data() {
        return {
          toggler: false,
          imgs: [ ],
          sidebar: true,
          content: true,
          is_sp: false,
          user: user,
          message_id: null,
          request_name: null,
          contact: [],
          path: null,
          messages: [],
          message: "",
          status: "ready"
        }
      },
methods: {
    scrollBottom(){
            let messages = document.getElementById('messagge_content');

            messages.scrollTop =  this.is_sp ? messages.scrollHeight + 200 : messages.scrollHeight;

            //messages.scrollTop = messages.scrollHeight;
            //$('div#messagge_content').animate({ scrollTop: messages.scrollHeight  }, -1);
    },
    redirect(_option = 1){
      // _option 1: show siderbar else show content
      if(_option == 1){
          this.sidebar = true; this.content = false;
      }else{
          this.sidebar = false; this.content = true;
      }
      return true;
    },
    cleaData(){
      $('#choose-img').val(null);
      this.path = null;
    },
    setValueMessage(val){
      //$('#container').html() +
      this.message =  val;
    },
    async sendMessage(event){

        if(event.shiftKey === true || ( ! $('#container').find('img.emojioneemoji').length && ! document.getElementById('container').textContent && !this.path  ) )
            return;

        file = $('#choose-img')[0].files[0];

        const _message = new Promise((resolve, reject) => {

            let _mess = "";

            $.each( document.getElementById('container').childNodes, (ind,val) => {

                switch( $(val)[0].nodeName ){
                    case 'DIV': _mess += $(val).html().replace(/(<([^>]+)>)/gi, "");  break;
                    case '#text': _mess += $(val).text();  break;
                    case 'IMG': _mess += `<img class="emojioneemoji"  src="${val.getAttribute('src')}">`;   break;
                    case 'BR': _mess +=  '</br>'; break;
                    default: _mess +=  $(val).text();
                }

            });
            return resolve( _mess );

        })

        message = await _message;

        let formData = new FormData();
        formData.append('user_to', this.contact.id);
        formData.append('user_from', this.user.id);
        formData.append('message_id', this.message_id);
        formData.append('message', message ?? '');
        formData.append('path', file != undefined  ? file : null );

        this.cleaData();

          axios.post(URL_CHAT, formData,
          {
            headers: {
              "Content-Type": "multipart/form-data;boundary=----WebKitFormBoundaryyrV7KO0BoCBuDbTL",
            },
          }
          ).then(output => {
                if(output.status == 200){
                  this.messages.splice(0, 0,output.data);

                  $(`ul.scroll-nonedefault li[data-id=${this.message_id}] div.text`).html( message );

                  //this.messages.push(output.data);
                  //this.scrollBottom();

                }
          });

          //message = { user_id:  this.user.id, 'message' : this.message, 'created_at': moment().format('YYYY-MM-DD HH:mm') };
          //this.messages.push(message);
          // this.message = '';

            $('#container').html('');

    },
    setMessage(event){
      $('#container').empty();
      if(this.is_sp)
        this.redirect(2);


      $(event.currentTarget).children('div').attr('class','mess-ins');

      this.message_id = event.currentTarget.getAttribute('data-id');

      this.request_name = event.currentTarget.getAttribute('data-request');

      this.contact = {
                        id: event.currentTarget.getAttribute('data-user-id'),
                        name : event.currentTarget.getAttribute('data-user') ,
                        avatar : event.currentTarget.getAttribute('data-avatar'),
                        _request_status : event.currentTarget.getAttribute('data-request-status'),
                        status : event.currentTarget.getAttribute('data-status'),
                        contact_id: event.currentTarget.getAttribute('data-contat-id')
                      };

      axios.post(URL_LOAD, { 'id': this.message_id, 'user_id': this.user.id  }).then((response) => {
          //console.log(response);
          if(response.status == 200){

            this.messages.splice(0, this.messages.length);
            this.messages = response.data.message.reverse();

            $('.count_message').text( response.data.count );
            // have seen
            $(`ul.scroll-nonedefault li[data-id=${this.message_id}] div.text`).attr('style','font-weight: unset');
            //this.scrollBottom();
          }

      });

    },
    sendEvent(){

      if(this.status != 'typing')
          return false;

        axios.post(URL_EVENT_SEEN, { 'user_id': this.user.id, 'message_id': this.message_id }).then((response) => {

            this.status = 'ready';
            $('.count_message').text( response.data );

        });
    }
  },
  computed: {
      // setValueMessage(val){
      //   this.message = 'abc';
      //   console.log(this.message);
      // }
   },
  // watch: {
  //   messages: function(e) {
  //     //imgs
  //     //this.imgs.splice(0, 0);

  //     this.imgs = e.reduce( (acc, act) => {
  //                   console.log( act.path != null, acc );
  //                   if( act.path != null )
  //                     return [ ...acc, act.path ];
  //                 }, [] );

  //   }
  // },
  created(){
    socket.listen('MessageSentEvent', (e) => {

        $(`ul.scroll-nonedefault li[data-id=${e.message_id}] div.text`).html( e.message );

        if(e.message_id == this.message_id){

          this.messages.splice(0, 0, e);
          this.status = 'typing';

          setTimeout( () => this.scrollBottom(), 200 )

        }else{

          $(`ul.scroll-nonedefault li[data-id=${e.message_id}] div.mess-ins`).attr('class','mess-ins unread');
          $(`ul.scroll-nonedefault li[data-id=${e.message_id}] div.text`).attr('style','font-weight: bold');
          this.status = 'miss_mesaage';

        }
        // update message new
    });

    if( $(window).width() < 960 ){
            this.is_sp = true;
            this.content = false;
    }

  },
  mounted(){

    $("#emoji").emojioneArea({
          standalone: true,

          //hideSource: false,
          // autoHideFilters: true,
          // pickerPosition: "top",
          // spellcheck: false,
          //container: "#container",

          pickerPosition:  "top",
          filtersPosition: "bottom",
          tones: false,
          autocomplete: true,
          shortnames: true,
          saveEmojisAs: "unicode",

          events: {

              emojibtn_click: function (button, event) {
                  $('#container').focus();
                  let img = $(button).html();

                  pasteHtmlAtCaret(img);

                //$("#container").html( _value );

              },
              change: function (editor, event) {
                //console.log(editor);

              },

            }
      });

      $('#choose-img').change( (e) => {

        let file = $('#choose-img')[0].files[0];

          if(!(jQuery.inArray(file.type,['image/jpeg','image/jpg','image/png']) !== -1)){
              $(this).val('');
              alert('PNG, JPEG, のみ添付可能です');
              return false;
          }

          if(file){
              if( file.size>10000000 ){
                  alert('・10MB以内の画像をアップロードしてください。');
                  return false;
              }

            let reader = new FileReader();
            reader.readAsDataURL(file);

            reader.onload = function(reader){
                vue.path =  reader.srcElement.result;
                vue.sendMessage(e);
            }


          }
      })

  },

     //path
})


pasteHtmlAtCaret = (html) => {
  let sel, range;
  if (window.getSelection) {
    // IE9 and non-IE
    sel = window.getSelection();
    if (sel.getRangeAt && sel.rangeCount) {
      range = sel.getRangeAt(0);
      range.deleteContents();

      // Range.createContextualFragment() would be useful here but is
      // non-standard and not supported in all browsers (IE9, for one)
      const el = document.createElement("div");
      el.innerHTML = html;
      let frag = document.createDocumentFragment(),
        node,
        lastNode;
      while ((node = el.firstChild)) {
        lastNode = frag.appendChild(node);
      }
      range.insertNode(frag);

      // Preserve the selection
      if (lastNode) {
        range = range.cloneRange();
        range.setStartAfter(lastNode);
        range.collapse(true);
        sel.removeAllRanges();
        sel.addRange(range);
      }
    }
  } else if (document.selection && document.selection.type != "Control") {
    // IE < 9
    document.selection.createRange().pasteHTML(html);
  }
}

// vue.use( VueEasyLightbox )
// vue.mount('#app')