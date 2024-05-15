<div class="modal-dialog" role="document">
          <div class="modal-content">
            <button class="close" data-bs-dismiss="modal" aria-hidden="true"></button>
            <div class="modal-body">
              <div class="block-content">
                <div class="img img_poup">

                  <figure style="overflow: hidden;border-radius: 10px;width: 100%;height: 312px;">
                  <img  style="width: 100%;object-fit: contain;height: 100%;" src="{!! $item->image[0] !!}" ></figure>
                </div>

                <div class="content">
                  <h3 class="tit">{{ $item->title }}</h3>
                  <div class="item-img">
                    <img src="{{ asset($item->subject->icon) }}" ><span>{!! $item->subject->name !!}</span></div>
                  <p class="text">{!! nl2br($item->content) !!}</p>
                </div>
              </div>

              @if( $_teacher = $item->teacher)
                <div class="block-users">
                    <figure >
                        <img  src="{{ $_teacher->avatar_img }}" alt="{{ $_teacher->name }}">
                     </figure>
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
                          <p class="text">{!! nl2br($item->description) !!}</p>
                        </div>
                        <div class="col">
                          <h3 class="green-tit">添付資料</h3>

                        @if($item->path)
                          <button class="btn-img btn-download" >
                            @php $_file_name = explode('/',$item->path);   $_file_name = $_file_name[count($_file_name)-1];  @endphp
                            <a download href="{{ route( 'link_dowload' , ['name' => $_file_name ] ) }}">
                                <img src="{{ asset('common_img/icon-pdf-org.svg') }}" >
                                <span>{{ $_file_name }}​</span>
                            </a>

                            </button>
                          @endif

                          <div class="box-gray">
                            <div class="item">
                              <div class="fcc box-gray-tit">
                                <p>分野</p>
                                <ul class="list-courses">
                                  <li>
                                    <div><img src="{{ asset($item->subject->icon) }}" alt="数学Ⅰ"><span>{!! nl2br($item->subject->name) !!}</span></div>
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

            <div class="modal-footer">
              <div class="fce btn-pp">
                <button class="btn btn-dark" onclick="denied({{ $item->id }})"  data-bs-dismiss="modal" aria-hidden="true" >拒否</button>

                <button class="btn btn-success" onclick="pass({{ $item->id }})" data-bs-dismiss="modal" aria-hidden="true" >許可</button>
              </div>
            </div>

          </div>
        </div>

