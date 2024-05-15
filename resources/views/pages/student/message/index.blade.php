@extends('layouts.student.index')

@section('body_class','main_body p-message message_type2 pdbottom-page has-alert')
@section('class_main','p-content-message message_type2 pdbottom-page has-alert')

@section('custom_css')
<script src="{{ asset('plugin/js/vue/vue.js') }}"></script>

<link rel="stylesheet" type="text/css" href="{{ asset('js/emoji/dist/emojionearea.min.css') }}" media="screen">

<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css" media="screen">
<link rel="stylesheet" type="text/css" href="http://mervick.github.io/lib/google-code-prettify/skins/tomorrow.css" media="screen">
<script type="text/javascript" src="{{ asset('js/emoji/dist/emojionearea.js') }}"></script>

<!-- <script type="text/javascript" src="{{ asset('js/fslightbox/fslightbox.umd.js') }}"></script> -->
<!-- <script src="https://unpkg.com/vue-image-lightbox-carousel@1.0.1/dist/vue-image-lightbox-carousel.js"></script> -->
<!-- <script src="https://unpkg.com/vue@next"></script>
<script src="https://unpkg.com/vue-easy-lightbox@next/dist/vue-easy-lightbox.umd.min.js"></script> -->

<link rel="stylesheet" href="{{ asset('js/vue_lightbox/index.css') }}">
<script type="text/javascript" src="{{ asset('js/vue_lightbox/vue.js') }}" ></script>

<style>
    .clearfix:after {
      content: " ";
      display: table;
      clear: both;
    }

</style>

@endsection('custom_css')

@section('content')
<div id="app">

    <!-- modal fade popup-style -->
    <div class="modal fade popup-style popup-style1" id="confirmDlt" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <button class="close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body">
                  <h3 class="title">確認</h3>
                  <p class="content-txt">
                      ブロックするとあなたのリクエストは一度<br>
                    キャンセルされ、再度募集を開始します。<br>
                    （追加でチケットは消費しません。）<br>
                    本当によろしいですか？
                  </p>
                </div>
                <form  method="post" action="{{ route('student.setting.block_request') }}">
                  <div class="modal-footer">
                    <div class="fce btn-pp">
                        @method('PUT')
                        @csrf

                        <span class="btn btn-dark" data-bs-dismiss="modal" aria-hidden="true">キャンセル</span>
                        <input type="hidden" name="contact_id"  :value="contact.contact_id" >
                        <button class="btn btn-primary" >ブロックする</button>

                    </div>
                  </div>
              </form>
              </div>
            </div>
    </div>
        <!-- end modal fade -->
        <div class="message-wrapper"  >
                  @include('component.message.menu')
                      <!-- message-content -->
                  @include('component.message.content')

</div>



@endsection

@section('custom_js')
<script>
// jquery
  // $(document).ready(function() {

  // });

    // let URI_CHAT = @JSON(route('ajax.chat'));
  var URL_LOAD = @JSON( route('ajax.load_message') );
  var URL_CHAT = @JSON( route('ajax.chat') );
  var URL_EVENT_SEEN = @JSON( route('ajax.seen') );

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-36251023-1']);
  _gaq.push(['_setDomainName', 'jqueryscript.net']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
</script>
<script type="text/javascript" src="{{ asset('js/message/handle.js') }}"></script>
@endsection
