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

    <meta name="description"
          content="@yield('meta_description', 'View details like charts, gates and more for this airport. Download VirtualHub now from the stores, available on iOS and Android!')"/>
    <meta name="keywords" content="@yield('meta_keyword', 'airport, information, details')"/>
    <meta name="author" content="Phenolix">
    <meta name="robots" content="@yield('meta_robot', 'index, follow')"/>
    <meta name="revisit-after" content="1 days"/>

    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito+Sans:300,400,600,700,800&display=swap" rel="stylesheet">
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css"
          integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" crossorigin="anonymous">
    @yield('external_css')
    @yield('vh_banner')
    @yield('meta_social')

    <link rel="apple-touch-icon" sizes="180x180" href="{{url("")}}/images/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="{{url("")}}/images/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="{{url("")}}/images/favicon-16x16.png">
    <link rel="manifest" href="{{url("")}}/images/site.webmanifest">
    <link rel="mask-icon" href="{{url("")}}/images/safari-pinned-tab.svg" color="#0090ff">
    <link rel="shortcut icon" href="{{url("")}}/images/favicon.ico">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-config" content="{{url("")}}/images/browserconfig.xml">
    <meta name="theme-color" content="#ffffff">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="color-scheme" value="light dark">
    <title>{{ config('app.name', 'Phenolix') }} | @yield('app_section') | @yield('title')</title>
</head>
<body>

<div class="cookieDiv">
    <div shadow>
        @include('cookieConsent::index')
    </div>
</div>

<nav s="@yield('header_style', '')" class="@yield('header_style', "normal_style")">
    <div class="container">
        <div class="nav_left">
            <a href="{{ url('/') }}">
                <div class="logo @yield('logo', "px")"></div>
            </a>
        </div>
        <div class="nav_right">
            <div class="nav_links">
                {{-- <div>
                    <p><a href="{{url('contact')}}">Contact</a></p>
                </div>

                Global site tag (gtag.js) - Google Analytics
                @guest
                    <div>
                        <p><a href="{{route('login')}}">Login</a></p>
                    </div>

                    <div>
                        <p><a href="{{route('register')}}">Register</a></p>
                    </div>
                @else

                    <div>
                        <p><a href="{{url('account/')}}">My account</a></p>
                    </div>

                    <div>
                        <p><a href="{{ route('logout') }}"
                              onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                        </p>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST"
                              style="display: none;">
                            @csrf
                        </form>
                    </div>
                @endguest
                --}}
            </div>

            <div class="close" style="display: none">
                <i class="fas fa-times"></i>
            </div>
        </div>
    </div>
</nav>

@yield('banner')

<main>
    @yield('big_header')
</main>

<main class="container main">
    <div class="page_navigation">
        @yield('page_navigation')
    </div>

    @yield('content')
</main>

<footer>
    <div class="container">
        <div class="footer_left">
            <a href="https://www.instagram.com/phenolix_ltd/" target="_blank"><i class="fab fa-instagram"></i>
                Phenolix_ltd&nbsp;&nbsp;|</a>&nbsp;
            <a href="https://twitter.com/virtual_flight" target="_blank"><i class="fab fa-twitter"></i>
                Virtual_Flight</a>
        </div>
        <div class="footer_right">
            <p>© {{ config('app.name', 'Phenolix') }} LTD, 2018 - {{$current_year}}</p>
        </div>
    </div>
</footer>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

<script src="{{ mix('js/app.js') }}"></script>
@if(url("") == "http://vh-net.com" || url("") == "https://vh-net.com")
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
@endif
<script>
    (adsbygoogle = window.adsbygoogle || []).push({
        google_ad_client: "ca-pub-5010357477858879",
        enable_page_level_ads: true
    });
</script>

<script>
    $(document).ready(function () {
        $("body").on("contextmenu", function (e) {
            return false;
        });
    });

    function closeBanner() {
        $(".app_banner").fadeOut();
    }

    function showBanner() {
        $(".app_banner").fadeIn();
    }
</script>
@yield('javascript')

@if(url("") == "http://vh-net.com" || url("") == "https://vh-net.com")
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-96063635-2"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }

        gtag('js', new Date());

        gtag('config', 'UA-96063635-2');
    </script>
@endif


</body>
</html>

