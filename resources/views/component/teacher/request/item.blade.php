@foreach($_result as $_key => $item)
    <div class="request-item item">

       @if($item->is_urgent)
                    <div class="status-order is-urgent"><img src="{{ asset('teacher/common_img/urgent.svg') }}" alt="緊急"><span>緊急</span></div>
        @endif

        <ul class="listimgs" >
            @foreach($item->image as $_img)
            <li ><img src="{{ $_img }}" alt=""></li>
            @endforeach
        </ul>

        <h3 class="request-tit">{{ $item->title }}</h3>
        <p class="answer-deadline">{{ trans('common.Request_order_deadline') }} : {{ $item->expires_at }}</p>

        <ul class="list-courses">
            <li>
            <div><img src="{{ asset($item->subject->icon) }}"><span>{!! $item->subject->name !!}</span></div>
            </li>
        </ul>
        <input type="hidden" name="content" value="{{ $item->content }}">
        <input type="hidden" name="id" value="{{ $item->id }}" >
        <button class="btn btn-primary" onclick="review($(this).parent())" >内容を確認する</button>

    </div>
@endforeach

<script>
    // $(document).ready(function(){
    //         $(".listimgs").slick({
    //             dots: false,
    //             infinite: true,
    //             centerMode: false,
    //             slidesToShow: 1,
    //             slidesToScroll: 1,
    //             autoplay: false,
    //             arrows: true,
    //         });
    // });
</script>