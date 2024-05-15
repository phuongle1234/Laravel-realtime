 <!-- modal fade requestPopup requestSmPopup popup-style2 popup-bottom-s -->
 @php $_user = Auth::guard('student')->user(); @endphp

 <div class="modal fade requestPopup requestSmPopup popup-style2 popup-bottom-sp show" id="poup-review" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <button class="close" data-bs-dismiss="modal" aria-hidden="true"></button>
                    <div class="modal-body">
                      <h2 class="pp-request-tit">{{ trans('teacher.request-poup-complete') }}</h2>
                      <div class="block-reverse">
                        <div class="block-content">

                          <div class="img" >
                            <!-- <figure >
                                    <img src="{{ $item->image[0] }}" alt="見出しが入ります見出しが入ります">
                            </figure> -->

                                <ul  class="img_review" >
                                    @foreach($item->image as $key => $_row_img)
                                            <li><img src="{{ $_row_img }}" alt=""></li>
                                    @endforeach
                                </ul>

                          </div>

                          <!-- title content -->
                          <div class="content">
                            <h3 class="tit">{{ $item->title }}</h3>

                            <ul class="list-courses">
                                <li>
                                  <div><img src="{{ asset($item->subject->icon) }}" alt="数学Ⅰ"><span>{!! $item->subject->name !!}</span></div>
                                </li>
                            </ul>

                            <p class="text">{!! nl2br($item->content) !!}</p>

                        </div>
                        </div>

                    @if( $_teacher = $item->teacher)
                        <div class="block-users">
                          <figure><img src="{{ $_teacher->avatar_img }}" alt="{{ $_teacher->name }}"></figure>
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

                    @if( $_video = $item->video)
                      <div class="block-content block-content2">
                        <div class="img">
                          <h3 class="green-tit">動画</h3>
                          <iframe src="{{ $_video->player_embed_url }}"  width="478" height="281" frameborder="0" allowfullscreen allow="autoplay; encrypted-media"></iframe>
                          <!-- <figure class="block-video"><img src="../images/your_request/popup_img2.png" alt="動画"></figure> -->

                          <!-- <h3 class="green-tit">動画説明文</h3> -->
                          <h4 class="tit">{{ $item->video_title }}</h4>
                          <p class="text">{!!  nl2br($item->description) !!}</p>
                        </div>
                        <div class="content">
                          <h3 class="green-tit">添付資料</h3>

                        @if( $item->path  )
                          @php $_file_name = explode('/',$item->path);   $_file_name = $_file_name[count($_file_name)-1];  @endphp
                          <a class="btn-primary btn-image btn-pdf" href="{{ route( 'link_dowload' , ['name' => $_file_name ] ) }}"  download >
                              <img src="{{ asset('teacher/common_img/icon-pdf.svg') }}" alt="20220311explanation.pdf​">
                            <span><?=  strlen($_file_name) > 20 ? substr( $_file_name, 0,20 )."..." : null ?>​</span>
                          </a>
                        @endif

                          <div class="box-gray">
                            <div class="item">
                              <div class="fcc box-gray-tit">
                                <p>分野</p>
                                <ul class="list-courses">
                                  <li>
                                    <div><img src="{{ asset($item->subject->icon) }}" alt="数学Ⅰ"><span>{{ $item->subject->text_name }}</span></div>
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
                        </div>
                      </div>
                    @endif

                    </div>

                    <div class="modal-footer">
                      <div class="fce btn-pp">

                        <!-- <a class="btn btn-image btn-message" href="../message">
                            <img src="{{ asset('student/common_img/icon-comment.svg') }}" alt="メッセージを送る"><span>メッセージを送る</span>
                        </a> -->
                        <button class="btn btn-primary btn-dark" data-bs-dismiss="modal" aria-hidden="true" data-bs-toggle="modal" data-bs-target="#requestConfirmPp" >閉じる</button>
                        <!-- <button class="btn btn-primary btn-pink" data-bs-dismiss="modal" aria-hidden="true" data-bs-toggle="modal" data-bs-target="#requestConfirmPp">依頼を終了する</button> -->
                      </div>
                    </div>

                  </div>
                </div>
              </div>

<script>
    $(document).ready(function(){

      setTimeout( (e) => {
        $(".img_review").slick({
          dots: false,
          infinite: true,
          centerMode: false,
          slidesToShow: 1,
          slidesToScroll: 1,
          autoplay: false,
          arrows: true,
        });
        var sLightbox = $(".img_review");
        sLightbox.slickLightbox({
          src: 'src',
          itemSelector: 'li img'
        });
      },200)

    });
</script>