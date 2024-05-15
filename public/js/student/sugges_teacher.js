let vue = new Vue({
    el: "#app",
    data() {
      return {
        subjects: subjects,
        host: window.location.origin,
        subject: null,
        sugges: 1,
        list_new: [],
        list_old: [],
        subject: [],
      }
    },
    methods: {

      chooseTeacher(obj){


          $('div.block-users').show();
          $('div.block-users figure.img-circle img').attr('src',obj.avatar_img);
          $('div.block-users h3.tit').text( obj.name );
          if( obj.university_Code )
          $('div.block-users #university').text( obj.university.university_name );
          $('div.block-users .list-tag').empty();

          $.each( obj.subject, (ind,val) => {
            $('div.block-users .list-tag').append(`<span class="tag tag-dark-green">${val.text_name}</span>`);
          });

          $('input[name="user_receive_id"]').val(obj.id);

          $("div.scroll-nonedefault").animate({ scrollTop: $("div.block-users").offset().top, },1500);

      },
      loadData(_value){

          axios.post(URL_LOAD, { 'subject_id': _value  }).then((response) => {
              if(response.status == 200){
                  this.list_new.slice(0, this.list_new.length )
                  this.list_new =  response.data;
              }
          })
            //URL_OLD_TEACHER
          axios.post(URL_OLD_TEACHER, { 'subject_id': _value  }).then((response) => {
              if(response.status == 200 ){
                    this.list_old.slice(0, this.list_old.length )
                    this.list_old =  response.data;
              }
          })
      },


    },
    computed: {
      showSubjectName() {
          var result = this.subjects.find(item => item.id == parseInt(this.subject) );

          if(result)
            return result.text_name;

          return null;
      }
    },
    created(){
      // console.log(this.user,'abc');
    },
    mounted(){

      $('select[name="subject_id"]').on('select2:select', function (e) {
          vue.subject = e.params.data.id;
          vue.loadData(vue.subject);
      })

        // $('ul.select-options li').click(function(e){
        //     subject = $('select[name="subject_id"]').val();
        //     _sugges = parseInt($('input[name=sugges]:checked').val());
        //     console.log(_sugges);
        //     if(_sugges == 1){
        //       vue.subject = subject;
        //       vue.loadData(subject);
        //     }
        // });

        $('input[name=sugges]').on('change',(e)=>{
            _val = parseInt( e.target.value );

            if(_val){
                subject = $('select[name="subject_id"]').val();
                vue.loadData(subject);
            }else{
                vue.list_new = [];
                vue.list_old = [];
            }

        });
    },
})