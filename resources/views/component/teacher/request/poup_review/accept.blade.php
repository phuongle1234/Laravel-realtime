<div class="modal fade requestPopup requestSmPopup popup-style2 popup-bottom-sp" id="requestUnanswer" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <button class="close" data-bs-dismiss="modal" aria-hidden="true"></button>
            <div class="modal-body">
              <h2 class="pp-request-tit">未回答のリクエスト</h2>
              <div class="block-content">
                <div class="img">
                  <ul class="listimgs_opup listimgs-pp">
                    @foreach($item->image as $_key => $_img)
                    <li style="width: 100%;height: 300px;background: #efefef;" ><img style="width: 100%; height: 100%; object-fit: contain" src="{{ $_img }}" alt=""></li>
                    @endforeach
                  </ul>
                </div>
                <div class="content">
                  <h3 class="tit">{{ $item->title }}</h3>
                  <ul class="list-courses">
                    <li>
                      <div ><img  src="{{ asset($item->subject->icon) }}" ><span>{!! $item->subject->name !!}</span></div>
                    </li>
                  </ul>
                  <p class="text">{!!  nl2br($item->content) !!}</p>
                </div>
              </div>
              <div class="btn-wrapper">

              <a class="btn btn-image btn-pdf" href="{{ route( !$item->video_id ? 'teacher.request.accepted_request' : 'teacher.request.edit' ,['id' => $item->id ]) }}">
               <img src="{{ asset('teacher/common_img/icon-pdf.svg') }}" alt="アップロードする"><span>アップロードする</span>
              </a>

              <a class="btn btn-image btn-message" href="{{ route('teacher.message') }}">
                <img src="{{ asset('teacher/common_img/icon-comment.svg') }}" alt="メッセージを送る"><span>メッセージを送る</span>
              </a>
              </div>
            </div>
          </div>
        </div>
  </div>

<script>
  setTimeout(function() {
      $(".listimgs_opup").slick({
            dots: false,
            infinite: true,
            centerMode: false,
            slidesToShow: 1,
            slidesToScroll: 1,
            arrows: true,
      });
      var sLightbox = $(".listimgs_opup");
        sLightbox.slickLightbox({
          src: 'src',
          itemSelector: 'li img'
        });
  }, 200);
</script>