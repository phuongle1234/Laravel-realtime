@inject('subject', 'App\Models\Subject')
@inject('schoolMaster', 'App\Models\SchoolMaster')

@extends('layouts.student.index')

@section('body_class','main_body p-teacher_list pdbottom')
@section('class_main','p-content-teacher_list pdbottom')

@php

$_user = Auth::guard('student')->user();
$subject = $subject::all();

@endphp

@section('custom_css')
  <script src="{{ asset('plugin/js/vue/vue.js') }}"></script>
@endsection('custom_css')


@section('content')
<div id="app">

              <div class="teacher-block">
                    <div class="item">
                        <ul class="list-courses"  >
                              <li v-for="sub in subjects" >
                                <input type="checkbox" id="check1" :value="sub.id" @click="hanlde" v-bind:checked="subject == sub.id" >
                                <div ><img :src="sub.image" alt=""><span v-html="sub.name"></span></div>
                              </li>
                        </ul>
                    </div>

                    <div class="item" >
                      <div class="text" id="school_plant">
                          <h3 class="teacher-title">学校検索</h3>

                          <!-- <select name="school">
                            <option value="">学校検索</option>
                          </select> -->

                      </div>
                  </div>
              </div>


          <section class="section_02 distance" v-show="list.length != 0">

                <h2 class="teacher-title2">先生一覧 <span v-show=" subject != null " > @{{ showSubjectName }} </span></h2>
                <div class="list-teacher"  >
                    <!-- show list account -->
                    <div class="teacher-item item" v-for="_row in list" >
                      <figure>
                      <!-- +'/teacher_list/infor/'+_row.id -->
                        <a :href="host+'/student_mypage/teacher_list/infor/'+_row.id" >
                          <img :src="_row.avatar_img" alt="">
                        </a>
                      </figure>

                      <div class="status-online" :class="{ active:_row.online }"><span>@{{ _row.name }}</span></div>
                      <div class="rating">

                        <!-- <img :src="_row.avatar_img" alt=""> -->
                        <span class="point">@{{ _row.rating }}</span>
                        <span class="number">@{{ _row.people_rating }}</span></div>

                        <p class="college" >大学 : @{{ _row.university_Code ? _row.university.university_name : null }}</p>
                      <ul class="list-courses list-courses-3item" >
                        <li v-for="subject in _row.subject.slice(0, 9)" >
                          <div>
                            <!-- <img :src="subject.icon" alt=""> -->
                            <span v-html="subject.name.replace('<br />','')" ></span></div>
                        </li>
                      </ul>
                      @if( $_user->can('viewStudent', App\Model\User::class) )
                        <a :href=" host+'/student_mypage/request/create_directly/'+_row.id" class="btn btn-primary" type="button" >直接リクエストする</a>
                      @endif
                    </div>

                </div>

          </section>
</div>

@endsection

@section('custom_js')
<script>

var subjects = <?= json_encode($subject) ?>;
var user = { name :  $('li.user-icon span').text(), avatar: $('li.user-icon img').attr('src'), id : $('meta[name="csrf_id"]').attr('content') };
var URL_LOAD = @JSON( route('student.teacher.list') );
// css change class
$('h2.tit-main').attr('class','teacher-title');

const _no_record = "{{ trans('common.no_record') }}";

const vue = new Vue({
      el: "#app",
      data() {
        return {
          user: user,
          schools: null,
          host: window.location.origin,
          subjects: subjects,
          list: [],
          subject: null,
        }
      },
      methods: {

        loadData(){
          if(! this.subject && ! this.schools)
          {
            return this.list = [];
          }
                axios.post(URL_LOAD, { 'subject_id': this.subject, 'university_Code': this.schools  }).then((response) => {
                    if(response.status == 200)
                      this.list = response.data;

                })
        },

        hanlde(e){

          _checked = $(e.target).prop('checked');

          this.subject = null;
          if( _checked )
          this.subject = e.target.value;
          this.loadData(e);
        },
        // filter(params, data){
        //   console.log(params, data);
        // },

        async getUniversity(){

            const _repose = new Promise((resolve, reject) => { $.post( window.location.origin + '/getUniversity', { _method: 'PUT', _token: _token }, resolve); })
            const _return = await _repose;

                  _option = '<option value="" ></option>';
                  // _li = '';

                  $.each( _return, (_idx,_val) => {
                    // act = '';
                    _option += `<option value="${ _val.code }">${ _val.name }</option>`;
                      // _li += `<li rel="${ _val.code }" >${ _val.name }</li>`;
                  });

            _select = `<select name="school" onChange="check_inst(event)"> ${_option} </select>`;

            // $(_select).append(_option);
            // $( $(_select).parent().find('div.select-styled') ).html(_text_defult);
            // $( $(_select).parent().find('ul.select-options') ).append(_li);

            $('#school_plant').append( _select );

            $('select[name="school"]').select2({
              "language": {
                    "noResults": () => (_no_record)
              },
              // noResults:_no_record,
              width: 'element' ,
              allowClear: true ,
              placeholder : '選択してください。'  } );

            // $('select[name="school"]').select2({
            //   ...
            //   "language": {
            //       "noMatches": function(){
            //           return "No Results Found <a href='#' class='btn btn-danger'>Use it anyway</a>";
            //       }
            //   }
            //   });
       }

      //  language: $.extend({},
      //   $.fn.select2.defaults.defaults.language, {
      //     noResults: function() {
      //       var term = id_categoria
      //         .data('select2')
      //         .$dropdown.find("input[type='search']")
      //         .val();

      //       return $("<span>Nenhum resultado. <span class='add'>Adicionar <b>" + term + "</b></span>?</span>");
      //     }
      //   })

      },
      watch: {
        schools: function(e) {
            // console.log(e);
            this.loadData(e);
        }

        // subject: function(e){
        //   this.loadData();
        // }
        //loadData
      },
      computed: {
        showSubjectName() {
            var result = this.subjects.find(item => item.id == this.subject);
            if(result)
            return result.name.replace('<br />','') ;
            return null;
        }
      },
      created(){
         this.getUniversity();
      },
      mounted(){

        // $('select[name="school"]').on('change', function() {
        //     console.log(e);
        // })

         //$(document).click(function(e) {

        //       _check = e.target.getAttribute('class') ?? null ;


        //       if( _check && _check.search('select-styled') >=0 ){
        //         $(e.target).toggleClass('active').next('ul.select-options').toggle();
        //       }

        //       if(e.target.nodeName == 'LI'){
        //           vue.schools = e.target.getAttribute('rel');
        //           e.stopPropagation();
        //           $('ul.select-options li').removeClass('active');
        //           $(e.target).addClass('active');
        //           $('select.select-hidden').val( e.target.getAttribute('rel') );
        //           $('ul.select-options').toggle()

        //       }

        //})



      },
  })

  check_inst = (e) => {
      vue.schools = e.target.value;
  }
</script>
@endsection
