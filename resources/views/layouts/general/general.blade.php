@php
    $current_year = date('Y');

@endphp

        <!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, viewport-fit=cover">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito+Sans:300,400,600,700,800&display=swap" rel="stylesheet">
    <link href="{{ asset('resources/css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('resources/css/loading.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css"
          integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" crossorigin="anonymous">
    @yield('external_css')
    @yield('vh_banner')
    @yield('meta_social')
    <link rel="shortcut icon" href="{{asset("storage/app/images")}}/favicon.ico">
    <link rel="icon" sizes="16x16 32x32 64x64" href="{{asset("storage/app/images")}}/favicon.ico">
    <link rel="icon" type="image/png" sizes="196x196" href="{{asset("storage/app/images")}}/favicon-192.png">
    <link rel="icon" type="image/png" sizes="160x160" href="{{asset("storage/app/images")}}/favicon-160.png">
    <link rel="icon" type="image/png" sizes="96x96" href="{{asset("storage/app/images")}}/favicon-96.png">
    <link rel="icon" type="image/png" sizes="64x64" href="{{asset("storage/app/images")}}/favicon-64.png">
    <link rel="icon" type="image/png" sizes="32x32" href="{{asset("storage/app/images")}}/favicon-32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset("storage/app/images")}}/favicon-16.png">
    <link rel="apple-touch-icon" href="{{asset("storage/app/images")}}/favicon-57.png">
    <link rel="apple-touch-icon" sizes="114x114" href="{{asset("storage/app/images")}}/favicon-114.png">
    <link rel="apple-touch-icon" sizes="72x72" href="{{asset("storage/app/images")}}/favicon-72.png">
    <link rel="apple-touch-icon" sizes="144x144" href="{{asset("storage/app/images")}}/favicon-144.png">
    <link rel="apple-touch-icon" sizes="60x60" href="{{asset("storage/app/images")}}/favicon-60.png">
    <link rel="apple-touch-icon" sizes="120x120" href="{{asset("storage/app/images")}}/favicon-120.png">
    <link rel="apple-touch-icon" sizes="76x76" href="{{asset("storage/app/images")}}/favicon-76.png">
    <link rel="apple-touch-icon" sizes="152x152" href="{{asset("storage/app/images")}}/favicon-152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="{{asset("storage/app/images")}}/favicon-180.png">
    <meta name="msapplication-TileColor" content="#00B80A">
    <meta name="msapplication-TileImage" content="{{asset("storage/app/images")}}/favicon-144.png">
    <meta name="msapplication-config" content="{{asset("storage/app/images")}}/browserconfig.xml">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="color-scheme" value="light dark">
    <title>{{ config('app.name', 'Laravel') }} | @yield('app_section')</title>
</head>
<body>
@include('cookieConsent::index')

<nav>
    <div class="container">
        <div class="nav_left">
            <a href="{{ url('/') }}">
                <div class="logo"></div>
            </a>
        </div>
        <div class="nav_right">
            <div class="nav_links">
                <div>
                    <a href="{{route('login')}}">Login</a>
                </div>
            </div>
        </div>
    </div>
</nav>

@yield('banner')

<main class="container main">
    <div class="page_navigation">
        @yield('page_navigation')
    </div>

    @yield('content')
</main>

<footer>
    <div class="container">
        <div class="footer_left">
            <a href="https://www.instagram.com/virtualflight__/" target="_blank"><i class="fab fa-instagram"></i>
                VirtualFlight__</a>&nbsp;&nbsp;&nbsp;
            <a href="https://twitter.com/virtual_flight" target="_blank"><i class="fab fa-twitter"></i>
                Virtual_Flight</a>
        </div>
        <div class="footer_right">
            <p>© {{ config('app.name', 'Phenolix') }} LTD, 2018 - {{$current_year}}</p>
        </div>
    </div>

{{--    <ul></ul>--}}
{{--    <form>--}}
{{--        <input>--}}
{{--        <textarea></textarea>--}}
{{--        <button>send</button>--}}
{{--    </form>--}}

{{--    <div class="model" style="display: none;">--}}
{{--        <li class="message">--}}
{{--            <i class="when">0</i>--}}
{{--            <b class="who"></b>:--}}
{{--            <span class="what"></span>--}}
{{--        </li>--}}
{{--    </div>--}}
</footer>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/gun/gun.js"></script>

<script src="{{ asset('resources/js/app.js') }}"></script>
{{--<script src="{{ asset('resources/js/bootstrap.js') }}"></script>--}}
{{--<script>--}}
{{--    $(document).ready(function () {--}}
{{--        $("body").on("contextmenu", function (e) {--}}
{{--            return false;--}}
{{--        });--}}
{{--    });--}}

{{--    var gun = Gun('https://gentle-lowlands-81280.herokuapp.com/gun').get('converse/' + location.hash.slice(1));--}}
{{--    $('form').on('submit', function(event) {--}}
{{--        event.preventDefault();--}}
{{--        var message = {};--}}
{{--        message.who = $('form').find('input').val();--}}
{{--        message.what = $('form').find('textarea').val();--}}
{{--        message.when = new Date().getTime();--}}
{{--        gun.set(message);--}}
{{--        $('form').find('textarea').val("");--}}
{{--    });--}}
{{--    gun.map().val(function(message, id) {--}}
{{--        if (!message) {--}}
{{--            return;--}}
{{--        }--}}
{{--        var $li = $(--}}
{{--            $('#' + id).get(0) ||--}}
{{--            $('.model').find('.message').clone(true).attr('id', id).appendTo('ul')--}}
{{--        );--}}
{{--        $li.find('.who').text(message.who);--}}
{{--        $li.find('.what').text(message.what);--}}
{{--        $li.find('.when').text(message.when);--}}
{{--    });--}}

{{--</script>--}}
@yield('javascript')
</body>
</html>

