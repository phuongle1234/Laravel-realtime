<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <!-- if IEmeta(http-equiv='X-UA-Compatible', content='IE=edge,chrome=1')-->
<meta http-equiv="X-UA-Compatible" content="IE=9; IE=8; IE=7; IE=EDGE">
<title>{{ $seo->title ?? '' }}</title>
<meta name="keyword" content="{{ $seo->keyword ?? "" }}" />
<meta name="description" content="{{ $seo->description ?? "" }}" />
<meta content="width=device-width, initial-scale=1, maximum-scale=1" name="viewport">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="csrf_id" content="{{ isset($_user) ? $_user->id : null }}">
<meta name="token_acsset" content="{{ isset($_token_acsset) ? $_token_acsset : null }}">

<meta name="robots" content="INDEX,FOLLOW">

<link rel="icon" type="image/x-icon" href="{{ asset('common_img/icon.svg') }}" sizes="32x32" >

<!--JS MAIN -->
<script src="{{ asset('teacher/js/jquery-2.2.4.min.js') }}"></script>
<script src="{{ asset('teacher/js/all.min.js') }}"></script>
<!-- CSS MAIN-->
<link rel="stylesheet" href="{{ asset('teacher/css/style.css') }}">
<link rel="stylesheet" href="{{ asset('teacher/css/base.css') }}">
<link rel="stylesheet" href="{{ asset('teacher/css/top.css') }}">
<link rel="stylesheet" href="{{ asset('teacher/css/content.css') }}">
<link rel="stylesheet" href="{{ asset('teacher/css/custom.css') }}">

<link rel="stylesheet" href="{{ asset('css/detail.css') }}">

<script src="{{ asset('plugin/js/echo/echo.common.min.js') }}" ></script>
<script src="{{ asset('plugin/js/socket/socket.io.min.js') }}"></script>
<script src="{{ asset('plugin/js/axios/axios.min.js') }}"></script>

<!-- <script src="//cdnjs.cloudflare.com/ajax/libs/vue/2.6.14/vue.js"></script> -->

<script src="{{ asset('plugin/js/vimeo/player.js') }}"></script>

<script src="{{ asset('plugin/js/jqueryValidate/jquery.validate.min.js') }}"></script>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr@latest/dist/plugins/monthSelect/index.js"></script>
<script src="https://npmcdn.com/flatpickr/dist/l10n/ja.js"></script>

<!-- loading -->
<!-- <link rel="stylesheet" href="{{ asset('loading/style.css') }}"> -->

<link rel="stylesheet" href="{{ asset('teacher/css/slick/slick.css') }}">
<link rel="stylesheet" href="{{ asset('teacher/css/slick/slick-theme.css') }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr@latest/dist/plugins/monthSelect/style.css">

<link rel="stylesheet" href="{{ asset('css/docx.css') }}">
<script src="{{ asset('js/heic2any.js') }}" ></script>

<script src="{{ asset('js/config/image.js') }}" ></script>

@include('component.google.tag_manager')

@include('component.google.gtag')