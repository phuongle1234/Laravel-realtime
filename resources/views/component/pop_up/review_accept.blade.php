<div class="modal fade requestPopup requestSmPopup popup-style2 popup-bottom-sp" id="requestNotUpload" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <button class="close" data-bs-dismiss="modal" aria-hidden="true"></button>
      <div class="modal-body">
        <h2 class="pp-request-tit"> {{ trans('student.request_active') }} </h2>
        <div class="block-reverse">
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
              <h3 class="tit">{{ $item->title }}</h3>
              <ul class="list-courses">
                <li>
                  <div><img src="{{ asset($item->subject->icon) }}" alt="数学Ⅰ"><span> {!! $item->subject->name !!} </span></div>
                </li>
              </ul>
              <p class="text"> {!! nl2br($item->content) !!} </p>
            </div>


            <!-- <div class="block-users">
                      <figure><img src="../images/teacher_list/avatar.png" alt="$item->teacher->name "></figure>
                      <div class="users-info">
                        <h3 class="tit">お名前太郎さん</h3>
                        <p class="text">大学 : 早稲田大学 経済学部</p>
                        <div class="list-tag"><span class="tag tag-dark-green">数学Ⅰ</span><span class="tag tag-dark-green">数学A</span><span class="tag tag-dark-green">数学Ⅱ</span><span class="tag tag-dark-green">数B</span><span class="tag tag-dark-green">英語</span></div>
                      </div>
                    </div> -->

          </div>

            @if( $_teacher = $item->teacher)
              <div class="block-users" style="display: inline-flex;">
              <a href="{{ route('student.teacher.infor',[ 'id' => $_teacher->id ] ) }}" style="z-index: 9999999999; cursor: pointer;" >
                <figure><img src="{{ $_teacher->avatar_img }}" alt="{{ $_teacher->name }}"></figure>
              </a>
                <div class="users-info">
                  <h3 class="tit">{{ $_teacher->name }}</h3>
                  <p class="text">大学 : {{ $_teacher->university->university_name }}</p>

                  <div class="list-tag">
                    @foreach( $item->teacher->subject as $_key => $_row)
                    <span class="tag tag-dark-green">{{ $_row->text_name }}</span>
                    @endforeach
                  </div>

                </div>
              </div>
            @endif
        </div>

          <div class="block-info">
            <img src="{{ asset('student/common_img/icon-warning.svg') }}" alt="">
            <span>{{ $item->status == EStatus::ACCEPTED ? 'まだ講師がアップロードを完了していません。' : '運営側にて動画の内容を確認しております。' }}</span>
          </div>

      </div>
      <div class="modal-footer">
        <div class="fce btn-pp"><a class="btn btn-image btn-message" href="{{ route('student.message') }}">
            <img src="{{ asset('student/common_img/icon-comment.svg') }}" alt="メッセージを送る"><span>メッセージを送る</span></a></div>
      </div>
    </div>
  </div>
</div>