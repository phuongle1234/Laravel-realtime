var form = document.getElementById('form-submit');

$('ul.select-options li').click(function(e) {

      $('table thead').find('select').each(function( index,val ){
        if(val.value)
          $('#form-submit').append($('<input>',{name: val.name, type:'hidden',value: val.value  }));
    })
  //$('#form-submit').append($('<input>',{name:'eSign_destination',value:$(this).attr('rel') }));
    $('#form-submit').submit();
});

form.addEventListener('submit', function(event){
  console.log(event);
  event.preventDefault();

   $('table thead').find('select').each(function( index,val ){
      if(val.value)
        $('#form-submit').append($('<input>',{name: val.name, type:'hidden',value: val.value  }));
   })

   $('#form-submit').submit();

})

function showPoupDelete(id){
    $('#confirmDlt #onDelete').attr('data-id',id);
    $('#confirmDlt').modal('show');
}

function onDelete(f){

  var id = $(f).data('id');

  var form = $("<form>",{ method:'post', action:URI_DELETE })
            .append($('<input>',{name:'_token',value:token}))
            .append($('<input>',{type: 'hidden', name:'_method',value:'DELETE' }))
            .append($('<input>',{name:'id',value:id}));
      form.appendTo('body').submit();
      form.remove();

}

function onUpdate(obj){
  let _token = $('meta[name=csrf-token]').attr('content');
  let _URL = window.location.href;
  let _data = obj;
      _data._method = 'PATCH';
      _data._token = _token;

  $.post(_URL,_data,(output) => { });

}

