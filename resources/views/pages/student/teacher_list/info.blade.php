@extends('layouts.student.index')

@section('body_class','main_body p-video_list pdbottom')
@section('class_main','p-content-video_list pdbottom teacher_list_detail')

@section('content')
<section class="section1">

    <h2 class="tit-main back-link fsc">
        <a  href="{{ url()->previous() }}"><img src="{{ asset('student/images/back-icon.svg') }}" alt="登録内容の変更"> </a>
        <span>プロフィール</span>
        <img src="{{ asset('student/common_img/icon_title1.svg') }}" alt="プロフィール">
    </h2>

    <div class="block-profile">
        <div class="item teacher-item">
        <div class="fsc box-top">
                      <div class="ins">
                        <figure class="img-circle"><img src="{{ $_info->avatar_img }}" alt="{{ $_info->name }}"></figure>
                      </div>
                      <div class="ins">
                        <p class="teacher-name">
                          {{ $_info->name }}
                        </p>
                        <div class="rating"><img src="{{ asset('student/common_img/icon_start.svg') }}" alt="">

                         <span class="point">{{ $_info->rating }}</span>
                         <span class="number">{{ $_info->people_rating }}</span>

                        </div>
                        <p class="college">大学 : {{ $_info->university->university_name ?? "" }}</p>
                      </div>
                    </div>
                <div class="fsc box-bottom">
                    <div class="ins">
                    <div class="status-online {{ $_info->online ? EStatus::ACTIVE : null }}"><span>{!! nl2br($_info->offline_text) !!}</span></div>
                    </div>
                    <div class="ins">
                    <div class="list-tag">
                            @foreach( $_info->subject as $_key => $_sub )
                            <span class="tag tag-dark-green">{{ $_sub->text_name }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>
        </div>
        <div class="item">
                    <div class="item-ins red">
                      <div class="box-profiles">
                        <figure><img src="{{ asset('student/common_img/menu4.svg') }}" alt="動画投稿数"></figure>
                        <div class="text">
                          <h3>動画投稿数</h3>
                          <p>{{ $_info->videos_count }} 本</p>
                        </div>
                      </div>
                    </div>
                    <div class="item-ins blue">
                      <div class="box-profiles">
                        <figure><img src="{{ asset('student/common_img/icon_like_w.svg') }}" alt="高評価数"></figure>
                        <div class="text">
                          <h3>高評価数</h3>
                          <p>{{  $_info->liked_count }}件</p>
                        </div>
                      </div>
                    </div>
                    <div class="item-ins yll">
                      <div class="box-profiles">
                        <figure><img src="{{ asset('student/common_img/icon_start_w.svg') }}" alt="指名リクエスト数"></figure>
                        <div class="text">
                          <h3>指名リクエスト数</h3>
                          <p>{{ $_info->nomination_requests_count }}本</p>
                        </div>
                      </div>
                    </div>
                    <div class="item-ins green">
                      <div class="box-profiles">
                        <figure><img src="{{ asset('student/common_img/icon_user.png') }}" alt="自己紹介"></figure>
                        <div class="text">
                          <h3>自己紹介</h3>
                          <p>{!! nl2br($_info->introduction) !!}</p>
                        </div>
                      </div>
                    </div>
                  </div>
    </div>
</section>

@if( $_video->count() )
    <section class="section_02">
        <div class="vl-tit-block">
            <h2 class="tit-main"><span>動画一覧</span><img src="{{ asset('student/images/video_list/video-chat.svg') }}" alt="動画一覧"></h2>
            <div class="blocktag">

                @foreach($_subject as $_tag)
                            <span class="tag tag-dark-green">{{ str_replace('<br />', '', $_tag) }}</span>
                @endforeach
            </div>
            <!-- <select>
                        <option value="視聴回数が多い順">視聴回数が多い順</option>
                        <option value="高評価順">高評価順</option>
                        <option value="更新日順">更新日順</option>
                    </select> -->
        </div>


        <div class="list-teacher list-request">

        @foreach( $_video as $_key => $_value )
            <div class="request-item item">

                <ul class="listimgs">
                    <li><img src="{{ $_value->thumbnail }}" alt=""></li>
                </ul>

                <h3 class="request-tit">{{ $_value->vd_title }}</h3>
                <p class="answer-deadline">完了日: {{ $_value->expires_at }}</p>
                <ul class="list-courses list2-item">

                    <li>
                        <div><img src="{{ asset($_value->icon) }}" alt="{{ $_value->subject_name }}"><span>{!! $_value->subject_name !!}</span></div>
                    </li>

                    <li>
                        <div class="item-bg"><span>{{ $_value->tag_name }}</span></div>
                    </li>
                </ul>
                <button class="btn btn-primary" onclick="review( {{ $_value->id }} )">視聴する</button>
            </div>
        @endforeach

        </div>

    </section>
@endif
    <div id="result"></div>
@endsection

@section('custom_js')

<script>

const URL_REVIEW = @JSON( route('student.request.review') );

const review = (id) => {

    axios.post(URL_REVIEW,{ id:id, review_poup:true }).then((response) => {
        if(response.status == 200){
            $('#result').html(response.data);
            $('#result div.modal').modal('show');
        }
    })

}
</script>