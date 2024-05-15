<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<!-- if IEmeta(http-equiv='X-UA-Compatible', content='IE=edge,chrome=1')-->
<meta http-equiv="X-UA-Compatible" content="IE=9; IE=8; IE=7; IE=EDGE">
<title>{{ $seo->title ?? '' }}</title>
<meta name="keyword" content="{{ $seo->keyword ?? "" }}" />
<meta name="description" content="{{ $seo->description ?? "" }}" />
<meta content="width=device-width, initial-scale=1, maximum-scale=1" name="viewport">

<meta name="robots" content="INDEX,FOLLOW">

<link rel="icon" type="image/x-icon" href="{{ asset('common_img/icon.svg') }}" sizes="32x32" >
<!--JS MAIN -->

<script src="{{ asset('register/js/jquery-2.2.4.min.js') }}"></script>
<script src="{{ asset('register/js/all.min.js') }}"></script>

<link rel="stylesheet" href="{{ asset('css/docx.css') }}">

<!-- CSS MAIN-->
<link rel="stylesheet" href="{{ asset('register/css/style.css') }}">
<link rel="stylesheet" href="{{ asset('register/css/base.css') }}">
<link rel="stylesheet" href="{{ asset('register/css/content.css') }}">


<!-- select2 -->
<script src="{{ asset('plugin/js/select2/select2.min.js') }}" > </script>
<link rel="stylesheet" href="{{ asset('plugin/js/select2/select2-bootstrap.min.css') }}"  />
<link rel="stylesheet" href="{{ asset('plugin/js/select2/select2.min.css') }}"  />
<script src="{{ asset('js/heic2any.js') }}" ></script>

<script src="{{ asset('js/config/image.js') }}" ></script>

@include('component.google.tag_manager')

@include('component.google.gtag')
