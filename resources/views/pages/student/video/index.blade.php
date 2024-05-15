@inject('subject', 'App\Models\Subject')
@inject('Tag', 'App\Models\Tag')
@inject('schoolMaster', 'App\Models\SchoolMaster')
@extends('layouts.student.index')

@section('body_class','main_body p-video_list pdbottom')
@section('class_main','p-content-video_list pdbottom')

@php
    $_user = Auth::guard('student')->user();

    $_filed = [];

    if( $_user->can('viewStudent', App\Model\User::class) )
    $_filed = $Tag::where([ 'tag_type' => 'field' , 'active' => 1 ])->get()

@endphp


@section('custom_css')

    @if( ! $_user->can('viewStudent', App\Model\User::class)  )
        <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-1471529931314203" crossorigin="anonymous"></script>
    @endif

    <script src="{{ asset('plugin/js/vue/vue.js') }}"></script>
@endsection('custom_css')

@section('content')
    <div id="app">
        <div class="search-block section_01--ins">
            <div class="item">
                <div class="text">
                    <h3 class="teacher-title">フリーワード検索</h3>
                    <div class="search-btn-wrapper">
                        <input class="form-control" type="text" v-model="key_work">
                        <button type="button"><img src="{{ asset('student/images/icon_search.svg') }}" alt=""></button>
                    </div>
                </div>
            </div>
            <div class="item">
                <div class="text" id="school_plant">
                    <h3 class="teacher-title">学校検索</h3>
                    <!-- <select name="schools">
                      <option value="">学校検索</option>
                    </select> -->
                </div>
            </div>
        </div>
        <div class="subject-search section_01--ins">
            <h2 class="vl-tit">科目検索</h2>
            <ul class="list-courses">

                @foreach( $subject::all() as $_key => $_row )
                    <li>
                        <input type="checkbox" name="subject" @click="showTag" value="{{ $_row->id }}">
                        <div><img src="{{ asset($_row->icon) }}" alt=""><span>{!! $_row->name !!}</span></div>
                    </li>
                @endforeach
            </ul>
        </div>

        @if( $_user->can('viewStudent', App\Model\User::class) )
            <div class="field-search section_01--ins">
                <h2 class="vl-tit">分野検索</h2>
                <ul class="list-courses">

                    <li v-for=" _row in tags ">
                        <input type="checkbox" name="tag" @click="handleTag" :value="_row.id">
                        <div>@{{ _row.name }}</div>
                    </li>

                </ul>
            </div>

            <div class="degree-difficulty section_01--ins" v-show=" tags.length != 0  ">
                <h2 class="vl-tit">難易度</h2>
                <ul class="list-courses">
                    @foreach( $Tag::where(['tag_type' => 'difficult', 'active' => 1 ])->get() as $_key => $_row )
                        <li>
                            <input type="checkbox" id="check0" name="degree" @click="handleDiff"
                                   value="{{ $_row->id }}">
                            <div>{{ $_row->name }}</div>
                        </li>
                    @endforeach
                </ul>
            </div>
            <!-- </section> -->
        @endif

        <section class="section_02" style="overflow-y: scroll;overflow-x: hidden;height: 500px;" @scroll="handleScroll">

            @if( $_user->can('viewStudent', App\Model\User::class) )
                <div class="vl-tit-block">
                    <h2 class="tit-main"><span>動画一覧</span><img
                                src="{{ asset('student/images/video_list/video-chat.svg') }}" alt="動画一覧"></h2>

                    <select name="sort" data-embed="false" style="width: 200px" v-model="sort">
                        <option value="deadline">更新日順</option>
                        <option value="watch">視聴回数が多い順</option>
                        <option value="rating">高評価順</option>
                    </select>

                </div>
            @endif

            <div class="list-teacher list-request" v-show="list.length != 0">

                <div class="request-item item" v-for=" item in list ">

                    <ul class="listimgs">
                        <li><img :src="item.thumbnail" alt=""></li>
                    </ul>
                    <h3 class="request-tit">@{{ item.title }}</h3>
                    <p class="answer-deadline">完了日: @{{ item.expires_at }}</p>
                    <ul class="list-courses list2-item">
                        <li>
                            <div><img :src="'../'+item.icon"><span v-html=" item.subject_name"></span></div>
                        </li>
                        <li>
                            <div class="item-bg"><span>@{{ item.tag_name }}</span></div>
                        </li>
                    </ul>
                    <button class="btn btn-primary" @click="review( item.id )">視聴する</button>
                </div>
            </div>
        </section>


        <div id="result"></div>

    </div>
@endsection

@section('custom_js')

    <script>

        let _tag = @JSON( $_filed );

    </script>


    <script>

        let URL_LOAD = @JSON( route('student.video.index') );
        let URL_REVIEW = @JSON( route('student.request.review') );


        const _no_record = "{{ trans('common.no_record') }}";


        const vue = new Vue({
            el: "#app",
            data() {
                return {
                    tag: null,
                    key_work: null,
                    school: null,
                    tags: [],
                    diff: null,
                    subject: null,
                    list: [],
                    sort: 'deadline',
                    current_page: 1,
                    last_page: 0
                }
            },
            methods: {
                review(id) {

                    axios.post(URL_REVIEW, {id: id, review_poup: true}).then((response) => {
                        if (response.status == 200) {
                            $('#result').html(response.data);
                            $('#result div.modal').modal('show');
                        }
                    })
                    //$('#requestVideo').modal('show');
                },
                showTag(e) {

                    if ($(`input[type=checkbox][name=subject]:checked`).prop("checked") != true) {
                        return this.tags = [];
                    }

                    $(`input[type=checkbox][name=subject]:not([value=${e.target.value}])`).attr("checked", false);
                    id = $(`input[type=checkbox][name=subject]:checked`).val();
                    this.tag = null;
                    this.diff = [];
                    if (id) {
                        this.subject = id;
                        this.tags = _tag.filter(item => item.subject_id == id);
                    }

                    // var result = this.subjects.find(item => item.id == this.subject);
                    // if(result)
                    // return result.name.replace('<br />','') ;
                    // return null; requestVideo
                },
                async getUniversity() {

                    const _repose = new Promise((resolve, reject) => {
                        $.post(window.location.origin + '/getUniversity', {_method: 'PUT', _token: _token}, resolve);
                    })
                    const _return = await _repose;

                    _option = '<option value="" ></option>';
                    // _li = '';

                    $.each(_return, (_idx, _val) => {
                        // act = '';
                        _option += `<option value="${_val.code}">${_val.name}</option>`;
                        // _li += `<li rel="${ _val.code }" >${ _val.name }</li>`;
                    });

                    _select = `<select name="school" onChange="check_inst(event)" > ${_option} </select>`;

                    // $(_select).append(_option);
                    // $( $(_select).parent().find('div.select-styled') ).html(_text_defult);
                    // $( $(_select).parent().find('ul.select-options') ).append(_li);

                    $('#school_plant').append(_select);

                    $('select[name="school"]').select2({
                        "language": {
                            "noResults": () => (_no_record)
                        },
                        width: 'element',
                        allowClear: true,
                        placeholder: '選択してください。'
                    });


                },
                showDif(id) {
                    console.log(id);
                },
                loadData() {

                    // _sort = $('select[name=sort]').val();
                    // _sort = _sort ? _sort : 'created_at';
                    // _school = $('select[name=school]').val();

                    axios.post(URL_LOAD, {
                        _method: 'PUT',
                        sort: this.sort,
                        university_Code: this.school,
                        key_work: this.key_work,
                        subject_id: this.subject,
                        tag_id: this.tag,
                        field_id: this.diff
                    }).then((response) => {
                        if (response.status == 200) {
                            this.list = response.data.data;
                            this.current_page = response.data.current_page;
                            this.last_page = response.data.last_page;
                        }

                    })

                },
                handleScroll({
                                 target: {
                                     scrollTop,
                                     clientHeight,
                                     scrollHeight
                                 }
                             }) {

                    let _percentage = Math.round( (scrollTop + clientHeight) / scrollHeight * 100);
                    if ( _percentage == 100 ) {

                        if (this.current_page < this.last_page) {
                            const next_page = this.current_page + 1;
                            axios.post(URL_LOAD + "?page=" + next_page, {
                                _method: 'PUT',
                                sort: this.sort,
                                university_Code: this.school,
                                key_work: this.key_work,
                                subject_id: this.subject,
                                tag_id: this.tag,
                                field_id: this.diff
                            }).then((response) => {
                                if (response.status == 200 && this.current_page != response.data.current_page )
                                {
                                    this.list = [...this.list, ...response.data.data];
                                    this.current_page = response.data.current_page;
                                }

                            })
                        }
                    }
                },
                handleTag(e) {

                    if ($(`input[type=checkbox][name=tag]:checked`).prop("checked") != true)
                        return this.tag = null;

                    $(`input[type=checkbox][name=tag]:not([value=${e.target.value}])`).attr("checked", false);
                    this.tag = e.target.value;
                },
                handleDiff(e) {

                    if ($(`input[type=checkbox][name=degree]:checked`).prop("checked") != true)
                        return this.diff = null;

                    $(`input[type=checkbox][name=degree]:not([value=${e.target.value}])`).attr("checked", false);
                    this.diff = e.target.value;
                }
            },
            watch: {

                subject: function () {
                    this.loadData();
                },
                school: function () {
                    this.loadData();
                },
                key_work: function () {
                    this.loadData();
                },
                tag: function () {
                    this.loadData();
                },
                diff: function () {
                    this.loadData();
                }
                //this.loadData();
                //console.log(this.key_work, this.subject, this.tag, this.diff);

            },
            computed: {},
            created() {
                $('section.section1').attr('class', 'section_01');
                this.getUniversity();
                this.loadData();

            },
            mounted() {

                $('select[name="sort"]').select2({
                    minimumResultsForSearch: Infinity,
                    width: 'element',
                    // allowClear: true ,
                    placeholder: '選択してください。'
                }).on('select2:select', function (e) {
                    // console.log(e.params.data.id);
                    vue.sort = e.params.data.id;
                    this.current_page = 1;
                    vue.loadData();
                })

                // $(document).click(function(e) {

                //     _check = e.target.getAttribute('class') ?? null ;


                //     if( _check && _check.search('select-styled') >=0 ){

                //       $(e.target).toggleClass('active');
                //       $(e.target).parent().find('ul.select-options').toggle();
                //     }

                //     if(e.target.nodeName == 'LI' ){
                //         //vue.schools = e.target.getAttribute('rel');
                //         console.log( $(e.target).parent().parent().find('div.select-styled.active') );
                //         e.stopPropagation();
                //         $('ul.select-options li').removeClass('active');
                //         $(e.target).addClass('active');
                //         $(e.target).parent().parent().find('select').val( e.target.getAttribute('rel') );
                //         $(e.target).parent().toggle();
                //         vue.loadData();
                //     }

                //   })
            }
        })


        check_inst = (e) => {
            vue.school = e.target.value;
        }

    </script>
@endsection
