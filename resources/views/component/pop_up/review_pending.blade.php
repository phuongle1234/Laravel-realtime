 <!-- modal fade requestPopup requestSmPopup popup-style2 popup-bottom-s -->
 <div class="modal fade requestPopup requestSmPopup popup-style2 popup-bottom-sp show" id="poup-review" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog" role="document">
          <div class="modal-content">
            <button class="close" data-bs-dismiss="modal" aria-hidden="true"></button>
            <div class="modal-body">
              <h2 class="pp-request-tit"> {{ trans('student.request_pending') }} </h2>
              <div class="block-content">
                <div class="img">
                    <ul class="listimgs">
                        @foreach( $item->image as $img )
                          <li>
                            <figure>
                                <img src="{{ $img }}" alt="{{ $item->title }}">
                            </figure>
                          </li>
                        @endforeach
                      </ul>
                </div>
                <div class="content">
                  <h3 class="tit"> {{ $item->title }} </h3>
                  <ul class="list-courses">
                    <li>
                      <div><img src="{{ asset($item->subject->icon) }}" alt="数学Ⅰ"><span>  {!! $item->subject->name !!} </span></div>
                    </li>
                  </ul>
                   <p class="text"> {!! nl2br($item->content) !!} </p>
                </div>
              </div>
            </div>
          </div>
        </div>
</div>

<!-- <script>
    $(document).ready(function(){
        $(".img_review").slick({
          dots: false,
          infinite: true,
          centerMode: false,
          slidesToShow: 1,
          slidesToScroll: 1,
          autoplay: false,
          arrows: true,
        });
    });
</script> -->