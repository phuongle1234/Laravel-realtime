@php $_user = Auth::guard('student')->user(); @endphp

<div class="modal fade requestPopup requestSmPopup popup-style2 popup-bottom-sp" id="requestVideo" tabindex="-1" role="dialog" aria-hidden="true">

        <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <button class="close" data-bs-dismiss="modal" aria-hidden="true"></button>
                  <div class="modal-body">
                    <h2 class="pp-request-tit">{{ trans('student.request_complete') }}</h2>
                    <div class="block-content">
                      <div class="img">
                        <!-- <figure class="video-block"><img src="{{ asset('student/images/your_request/video.png') }}" alt="動画タイトルが入ります動画タイトルが入ります動画タイトルが入ります動画タイトルが入ります"></figure> -->
                        <iframe src="{{ $item->video->player_embed_url }}"  width="654" height="362" frameborder="0" allowfullscreen allow="autoplay; encrypted-media"></iframe>

                        <div class="block-top">

                          @if( $_teacher = $item->teacher)

                          <div class="total-like">
                                  <div class="dispc">
                                        <div class="block-rating"><img src="{{ asset('student/common_img/icon_start.svg') }}" alt="">
                                        <span class="point">{{ $_teacher->rating }}</span>
                                        <span class="total-vote">{{ $_teacher->people_rating }}</span></div>
                                  </div>
                                  <div class="block-like">
                                    <div class="like-box">
                                      <figure class="likehover" onClick="likeVideo(this, {{ $item->id }} )" >
                                        <svg  {{ $item->likeVideo() ? 'class=active' : null }}  version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 26 26" style="enable-background:new 0 0 26 26;" xml:space="preserve">
                                          <style type="text/css">
                                            .st0{fill:none;}
                                            .st1{fill:#8790A8;}
                                          </style>
                                          <g>
                                            <path class="st0" d="M16.8,9.5c0.2,0,0.3,0,0.4,0c1.5,0,3.1,0,4.6,0c2.5,0,4.3,1.9,4.3,4.4c0,0.7-0.2,1.4-0.6,2.1
                                              c-1.8,2.6-3.5,5.3-5.3,7.9c-1,1.4-2.4,2.1-4.1,2.1c-1.8,0-3.5,0-5.3,0c-1.3,0-2.5-0.5-3.5-1.4c-0.1-0.1-0.1-0.1-0.2-0.2
                                              c-0.2,0.6-0.6,0.8-1.2,0.8c-1.6,0-3.2,0-4.7,0c-0.7,0-1-0.4-1-1c0-4.8,0-9.6,0-14.4c0-0.7,0.4-1,1-1c1.6,0,3.2,0,4.8,0
                                              c0.4,0,0.8,0.1,1.1,0.5C7,9.1,7.1,9,7.1,8.9c1.8-2.4,3.5-4.9,5-7.6C12.6,0.4,13.4,0,14.5,0c0.2,0,0.4,0,0.6,0.1
                                              c1.1,0.5,2.1,1.1,2.6,2.2C18,3,18,3.8,17.9,4.5c-0.4,1.6-0.7,3.1-1.1,4.7C16.8,9.3,16.8,9.4,16.8,9.5z"/>
                                            <path class="st1" d="M21.7,9.6c-1.5,0-3.1,0-4.6,0c-0.1,0-0.2,0-0.4,0c0-0.1,0-0.2,0-0.3c0.4-1.6,0.7-3.1,1.1-4.7
                                              C18,3.8,18,3,17.6,2.3c-0.6-1.1-1.5-1.8-2.6-2.2C14.9,0,14.7,0,14.5,0c-1,0-1.8,0.4-2.3,1.4C10.7,4,9,6.5,7.1,8.9
                                              C7.1,9,7,9.1,6.9,9.2C6.7,8.8,6.3,8.7,5.9,8.7c-1.6,0-3.2,0-4.8,0c-0.7,0-1,0.4-1,1c0,4.8,0,9.6,0,14.4c0,0.7,0.4,1,1,1
                                              c1.6,0,3.2,0,4.7,0c0.6,0,1-0.1,1.2-0.8c0.1,0.1,0.2,0.2,0.2,0.2c1,0.9,2.1,1.4,3.5,1.4c1.8,0,3.5,0,5.3,0c1.7,0,3.1-0.7,4.1-2.1
                                              c1.8-2.6,3.5-5.2,5.3-7.9c0.4-0.6,0.6-1.3,0.6-2.1C26.1,11.5,24.2,9.6,21.7,9.6z M5.2,23.2c-1.1,0-2.2,0-3.3,0c0-4.2,0-8.4,0-12.7
                                              c1.1,0,2.2,0,3.3,0C5.2,14.8,5.2,19,5.2,23.2z M23.7,15.2c-1.6,2.4-3.3,4.9-4.9,7.3c-0.7,1.1-1.7,1.6-3.1,1.6c-1.6,0-3.2,0-4.8,0
                                              c-1.2,0-2.1-0.5-2.8-1.4c-0.1-0.1-0.1-0.2-0.2-0.3c-0.3-0.4-0.7-0.8-0.8-1.2c-0.1-0.4,0-1,0-1.5c0-1.1,0-2.2,0-3.3
                                              c0-1.5,0-2.9,0-4.4c0-0.3,0.1-0.4,0.3-0.6c1-1,1.9-2.1,2.7-3.3c1.3-2,2.5-3.9,3.7-5.9c0.4-0.6,0.7-0.7,1.2-0.2
                                              c0.2,0.2,0.4,0.3,0.6,0.5C16,3,16.2,3.5,16.1,4.1c-0.4,2-0.9,4-1.3,6c-0.2,0.8,0.2,1.3,1.1,1.3c1.9,0,3.9,0,5.8,0
                                              c1,0,1.8,0.4,2.2,1.3C24.3,13.5,24.2,14.4,23.7,15.2z"/>
                                          </g>
                                        </svg>

                                        <!-- <img src="{{ asset('student/common_img/icon-like.svg') }}" alt="高評価"> -->
                                      </figure>
                                      <figcaption>高評価</figcaption>
                                    </div>
                                    <!-- $item->video -->
                                    <p class="total-liked">{{ $item->like_count }}</p>
                                  </div>
                            </div>

                              <div class="block-users">
                                <a href="{{ route('student.teacher.infor',[ 'id' => $_teacher->id ] ) }}" style="z-index: 9999999999; cursor: pointer;" >
                                    <figure><img src="{{ $_teacher->avatar_img }}" alt="{{ $_teacher->name }}"></figure>
                                </a>
                                    <div class="users-info">
                                      <h3 class="tit">{{ $_teacher->name }}</h3>
                                      <p class="text">大学 : {{ $_teacher->university->university_name }}</p>

                                        <div class="list-tag">
                                            @foreach( $item->teacher->subject as $_key => $_row)
                                                <span class="tag tag-dark-green">{!! $_row->text_name !!}</span>
                                            @endforeach
                                        </div>

                                    </div>

                              </div>


                        @endif
                        </div>
                        <!-- <div class="list-tag"><span class="tag tag-dark-green">数学Ⅰ</span><span class="tag tag-dark-green">数学A</span><span class="tag tag-dark-green">数学Ⅱ</span><span class="tag tag-dark-green">数B</span><span class="tag tag-dark-green">英語</span></div> -->
                        <div class="box-gray">
                            <div class="item">
                              <div class="fcc box-gray-tit">
                                <p>分野</p>
                                <ul class="list-courses">
                                  <li>
                                    <div><img src="{{ asset($item->subject->icon) }}" alt="数学Ⅰ"><span>{!! $item->subject->name !!}</span></div>
                                  </li>
                                </ul>
                              </div>
                              <div class="btn btn-primary btn-orange no-btn">{{ $item->tags->name }}</div>
                            </div>
                            <div class="item">
                              <div class="box-gray-tit">難易度</div>
                              <div class="btn btn-outline-success btn-outline-org no-btn">{{ $item->fields->name }}</div>
                            </div>
                          </div>

                      @if( isset( $item->teacher->settings->accept_directly ) && ( $item->teacher->settings->accept_directly == 1 ) && $_user->can('viewStudent', App\Model\User::class)  )
                        <a href="{{ route('student.request.create_direct',['id' => $item->user_receive_id ]) }}" class="btn btn-primary btn-pink">この講師に直接リクエストする</a>
                      @endif

                      </div>
                      <div class="content">
                        <h3 class="tit">{{ $item->video_title }}</h3>
                        <p class="date">{{ $item->created_at->format('Y年m月d日') }}</p>
                        <ul class="list-courses">
                          <li>
                              <div><img src="{{ asset($item->subject->icon) }}" alt="数学Ⅰ"><span>{!! $item->subject->name !!}</span></div>
                          </li>
                        </ul>
                        <p class="text"> {{ $item->description }} </p>

                      @if( $item->path && $_user->can('viewStudent', App\Model\User::class) )
                      @php $_file_name = explode('/',$item->path);   $_file_name = $_file_name[count($_file_name)-1];  @endphp
                        <h3 class="green-tit">添付資料</h3>

                        <a href="{{ route( 'link_dowload' , ['name' => $_file_name ] ) }}" download >
                        <div class="orange-box">

                            <div class="orange-box-ins">
                            <img src="../student/common_img/icon-pdf.svg" alt="20220311explanation.pdf​">

                              <!-- download -->

                                <span>{{ $_file_name }}</span>

                            </div>

                        </div>
                        </a>

                      @endif

                      </div>
                    </div>
                  </div>
                </div>
        </div>
  </div>
  <script src="https://player.vimeo.com/api/player.js"></script>
<script>
    var _id_rs = {{ $item->id }};
    var _id_vd  = {{ $item->video->id }};
</script>
  <script src="{{ asset('student/js/video/player.js') }}"></script>