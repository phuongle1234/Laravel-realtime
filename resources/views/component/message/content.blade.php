@php
    $_role = str_replace( "auth:", "", request()->route()->getAction()['middleware'][1] );
@endphp

<div class="message-content" @click="sendEvent" v-show="content">
    <div class="message-content-ins">
        <div class="dissp">
            <div class="back-link fsc" @click="redirect(1)"><img src="{{ asset('student/images/back-icon.svg') }}"
                                                                 alt="メッセージ"><span>メッセージ</span></div>
        </div>
        <div class="block-top">
            @php
                $url = route('teacher.request.accepted');
                if($_role == EUser::STUDENT) $url = route('student.request.list');
            @endphp

            <a href="{{ $url }}"><h3>@{{ request_name }}</h3></a>
            @if($_role=='student')
                <button v-show="contact.length != 0 && (contact.status == 'active') && contact._request_status != 'pass'" data-bs-toggle="modal"
                        data-bs-target="#confirmDlt">ブロックする
                </button>
            @endif
        </div>
        <div class="block-middle scroll-nonedefault" id="messagge_content">

            <!-- show imgage -->
            <div class="clearfix box-message right" v-show="path" id="path-file">
                <div class="item">
                    <span aria-hidden="true" @click="cleaData" style="font-size: 24px; color: #f00; font-weight: bold;">&times;</span>
                    <figure><img :src="user.avatar" :alt="user.name"></figure>
                    <figcaption>@{{ user.name}}</figcaption>
                </div>
                <div class="item">
                    <div class="text">
                        <img :src="path">
                    </div>
                    <!-- <span class="time-mess">@{{ mess.created_at }}</span> -->
                </div>
            </div>
            <!-- END show imgage -->

            <div v-for="mess in messages" :class="mess.user_id != user.id ? 'messageLeft' : 'messageRight'">

                <div class="clearfix box-message left" v-if="mess.user_id != user.id">
                    <div class="item">
                        <figure><img :src="contact.avatar" :alt="contact.name"></figure>
                        <figcaption>@{{ contact.name }}</figcaption>
                    </div>
                    <div class="item">

                        <div class="text">
                            <!-- <figure @click="toggler = !toggler" v-show="mess.path"><img :src="mess.path"></figure> -->

                            <figure v-show="mess.path">
                                <vue-pure-lightbox
                                    :thumbnail="mess.path"
                                    :images="[ mess.path ]"
                                />
                            </figure>

                            <p v-html="mess.message"></p>

                        </div>
                        <span class="time-mess">@{{ mess.created_at }}</span>

                    </div>
                </div>

                <div class="clearfix box-message right" v-else>

                    <div class="item">
                        <figure @click="toggler = !toggler" ><img :src="user.avatar" :alt="user.name"></figure>
                        <figcaption>@{{ user.name}}</figcaption>
                    </div>
                    <div class="item">
                        <div class="text">

                        <!-- <img :src="mess.path"> -->
                            <figure v-show="mess.path">
                                <vue-pure-lightbox
                                    :thumbnail="mess.path"
                                    :images="[ mess.path ]"
                                />
                            </figure>

                            <p v-html="mess.message"></p>

                        </div>
                        <span class="time-mess">@{{ mess.created_at }}</span>
                    </div>
                </div>


            </div>


        </div>
    </div>
    <!-- button -->
    <div class="block-bottom" v-show="contact.length != 0 && (contact.status == 'active')">
        <div class="box">
            <!-- v-show="contact.length != 0" -->
            <button class="btn-icon" id="emoji">
                <img src="{{ asset('student/images/message/icon.svg') }}" alt="">
            </button>
            <label class="btn-image2" for="choose-img">
                <input type="file" name="" id="choose-img" hidden><img
                        src="{{ asset('student/images/message/image.svg') }}" alt="">
            </label>

            <div contenteditable="=true"  id="container"
                 cols="30" @keyup.enter="sendMessage" v-html="message" rows="1"></div>
            <textarea style="display: none;" :disabled="contact.length == 0" v-model="message" cols="30" rows="1"
                      @click="sendEvent"></textarea>
            <button :disabled="contact.length == 0" @click="sendMessage" class="btn-send" type="button"><img
                        src="{{ asset('student/images/message/send.svg') }}" alt=""></button>
        </div>
    </div>
</div>
</div>