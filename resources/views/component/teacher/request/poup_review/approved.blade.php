 <!-- modal fade requestPopup requestSmPopup popup-style2 popup-bottom-s -->

 <div class="modal fade requestPopup popup-style2 popup-bottom-sp" id="poup-review" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <button class="close" data-bs-dismiss="modal" aria-hidden="true"></button>
                    <div class="modal-body">
                      <h2 class="pp-request-tit">{{ trans('student.request_approved') }}</h2>
                      <div class="block-reverse">
                        <div class="block-content">

                          <div class="img" >

                          <ul class="listimgs_poup listimgs-pp">
                            @foreach( $item->image as $img )
                              <li> <img src="{{ $img }}" alt="見出しが入ります見出しが入ります"> </li>
                            @endforeach
                          </ul>

                                <!-- <ul  class="img_review" >
                                    foreach($item->image as $key => $_row_img)
                                            <li><img src=" $_row_img " alt=""></li>
                                    endforeach
                                </ul> -->

                          </div>

                          <!-- title content -->
                          <div class="content">
                            <h3 class="tit">{{ $item->title }}</h3>

                            <ul class="list-courses">
                                <li>
                                <div><img src="{{ asset($item->subject->icon) }}" alt="数学Ⅰ"><span>{!! $item->subject->name !!}</span></div>
                                </li>
                            </ul>

                            <p class="text">{{ $item->content }}</p>

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
                      <div class="block-artical">
                        <div class="col">
                          <h3 class="green-tit">動画</h3>
                          <iframe src="{{ $_video->player_embed_url }}"  width="478" height="281" frameborder="0" allowfullscreen allow="autoplay; encrypted-media"></iframe>
                          <!-- <figure class="block-video"><img src="../images/your_request/popup_img2.png" alt="動画"></figure> -->
                        </div>
                        <div class="col">
                          <h3 class="green-tit">動画説明文</h3>
                          <h4 class="tit">{{ $item->video_title }}</h4>
                          <p class="text">{!!  nl2br($item->description) !!}</p>
                        </div>

                        <div class="col">
                          <h3 class="green-tit">添付資料</h3>

                        @if( $item->path )
                        @php $_file_name = explode('/',$item->path);   $_file_name = $_file_name[count($_file_name)-1];  @endphp
                          <a class="btn-primary btn-image btn-pdf" href="{{ route( 'link_dowload' , ['name' => $_file_name ] ) }}" download>
                            <img src="{{ asset('teacher/common_img/icon-pdf.svg') }}" alt="20220311explanation.pdf​">
                            <span> {{ $_file_name }} ​</span>
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

                        <a class="btn btn-image btn-message" href="../message">
                            <img src="{{ asset('student/common_img/icon-comment.svg') }}" alt="メッセージを送る"><span>メッセージを送る</span>
                        </a>

                        <!-- <button class="btn btn-primary btn-pink" data-bs-dismiss="modal" aria-hidden="true" data-bs-toggle="modal" data-bs-target="#requestConfirmPp">依頼を終了する</button> -->
                      </div>
                    </div>

                  </div>
                </div>
              </div>

<script>
 setTimeout( (e) => {
    $('.listimgs_poup').slick({
              dots: false,
              infinite: false,
              centerMode: false,
              slidesToShow: 1,
              slidesToScroll: 1,
              autoplay: false,
              arrows: true,
      });
      var sLightbox = $(".listimgs_poup");
        sLightbox.slickLightbox({
          src: 'src',
          itemSelector: 'li img'
        });
  },200)
//   $("iframe").on("load", (e) => {

//     _iframe  = e.target;

//   if( _iframe.getAttribute('src').search( 'transcode.svg' ) < 0)
//     return false;

//   _height = _iframe.getAttribute('height');
//   _width = _iframe.getAttribute('width');

//   _parent = $(_iframe).parent();

//   // object-fit: contain;

//    $(`<figure width="${_width}" height="${_height}" ><img src='${ _iframe.getAttribute('src') }' style=" width: 100%; height: 100%;" /> </figure>`).insertAfter( $(_parent).find('h3.green-tit') );

//   $(_iframe).remove();

// });

</script>
    <!-- $(document).ready(function(){
        $(".img_review").slick({
          dots: false,
          infinite: true,
          centerMode: false,
          slidesToShow: 1,
          slidesToScroll: 1,
          autoplay: false,
          arrows: true,
        });
    }); -->
