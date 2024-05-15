<div class="message-sidebar" v-show="sidebar">

        <ul class="scroll-nonedefault">
              <!-- list request contact -->
            @foreach($_contact as $_row)

                <li class=" {{ isset($_row->user->online) && $_row->user->online ? 'hasmess' : null }}"

                    :data-id="{{ $_row->message_id }}"
                    :data-contat-id="{{ $_row->id  }}"
                    :data-user-id="'{{ $_row->user->id  }}'"
                    :data-status="'{{ $_row->status }}'"
                    :data-request-status="'{{ $_row->request->status }}'"
                    :data-user="'{{ $_row->user->name  }}'"
                    :data-avatar="'{{  $_row->user->avatar_img  }}'"
                    :data-request="'{{ $_row->request->title }}'"

                    @click="setMessage($event)">
                    <div class="mess-ins {{ !$_row->seen_at ? 'unread' : "" }}">
                        <figure><img style="object-fit: revert;" src="{{ asset($_row->user->avatar_img) }}" alt=""></figure>
                        <div class="content">
                            <div class="title">
                            <div class="tit-left"><span class="status"></span><span class="text"><?= mb_substr($_row->request->title,0,50,'UTF-8').'...' ?></span></div>
                            <div class="tit-right">{{ $_row->created_at->format('Y年m月d日 H:i') }}</div>
                            </div>
                            <!-- //seen_at -->
                            <div class="text __message" <?= !$_row->seen_at ? 'style="font-weight: bold"' : null ?> >{!! nl2br($_row->message_content) !!}</div>
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>
</div>

