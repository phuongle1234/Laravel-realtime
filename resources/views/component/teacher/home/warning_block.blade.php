<section class="section3" id="warning-block" >
                <div class="vl-tit-block">
                  <h2 class="tit-main"  id="direct"><span>あなたへの指名リクエスト</span></h2>
                  <div class="list-tag">
                    @foreach( $_item as $_key_sub => $_row_subject )
                      <span class="tag tag-dark-green">{!! $_row_subject->subject->name !!}</span>
                    @endforeach
                  </div>
                </div>

                <div class="list-teacher list-request" >
                @foreach($_item as $_key => $_row)

                  <div class="request-item item">

                  @if($_row->is_urgent)
                    <div class="status-order is-urgent"><img src="{{ asset('teacher/common_img/urgent.svg') }}" alt="緊急"><span>緊急</span></div>
                  @endif

                      <ul class="listimgs">
                          @foreach($_row->image as $_key_img => $_img)
                          <li><img src="{{ $_img }}" alt=""></li>
                          @endforeach
                      </ul>
                    <h3 class="request-tit">{{ $_row->title }}</h3>
                    <p class="answer-deadline">{{ $_row->expires_at }}</p>
                    <ul class="list-courses">
                      <li>
                        <div><img src="{{ $_row->subject->icon }}" alt="数学Ⅰ"><span>{!! $_row->subject->name !!}</span></div>
                      </li>
                    </ul>
                    <input type="hidden" name="content" value="<?= nl2br($_row->content) ?>">
                    <input type="hidden" name="is_direct" value="{{ $_row->is_direct }}">
                    <input type="hidden" name="id" value="{{ $_row->id }}">
                    <button class="btn btn-primary" onclick="review_request( $(this).parent() )">内容を確認する</button>
                  </div>

                @endforeach
                </div>

</section>