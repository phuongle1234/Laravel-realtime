<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<!-- if IEmeta(http-equiv='X-UA-Compatible', content='IE=edge,chrome=1')-->
<meta http-equiv="X-UA-Compatible" content="IE=9; IE=8; IE=7; IE=EDGE">
<title>{{ env('APP_NAME') }}</title>

<meta content="width=device-width, initial-scale=1, maximum-scale=1" name="viewport">
<meta name="robots" content="NOINDEX,NOFOLLOW">
<meta name="csrf-token" content="{{ csrf_token() }}">

<meta name="csrf_id" content="{{ isset($_user) ? $_user->id : null }}">

<meta name="token_acsset" content="{{ $_user->access_token ?? null }}">

<link rel="icon" type="image/x-icon" href="{{ asset('common_img/icon.svg') }}" sizes="32x32" >
<!--JS MAIN -->
<script src="{{ asset('js/jquery-2.2.4.min.js') }}"></script>
<script src="{{ asset('js/all.min.js') }}"></script>
<!-- CSS MAIN-->
<link rel="stylesheet" href="{{ asset('css/style.css') }}">
<link rel="stylesheet" href="{{ asset('css/base.css') }}">

<link rel="stylesheet" href="{{ asset('css/detail.css') }}">

<!-- scocket -->
<script src="//cdnjs.cloudflare.com/ajax/libs/axios/0.21.1/axios.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/socket.io/2.4.0/socket.io.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/laravel-echo/1.11.5/echo.common.min.js" integrity="sha512-0NImKtmJqOw2UHr5m7rxYtSfpLXBNocYb819PzpIm7yz8BNvlLWVtWPJOr+cCdKpnPoCqpnCBfNEu+ePTF9EVA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script src="{{ asset('plugin/js/select2/select2.min.js') }}" > </script>
<link rel="stylesheet" href="{{ asset('plugin/js/select2/select2.min.css') }}" />

<link rel="stylesheet" href="{{ asset('css/docx.css') }}">