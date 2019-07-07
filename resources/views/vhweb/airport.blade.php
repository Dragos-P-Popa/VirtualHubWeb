@extends('layouts.vhw')
@section('app_section', env('APP_VHW'))

{{--{{dd(Auth::user()->role)}}--}}

@if(!isset($info["error"]))
    @section('title', $info["airport"]["icao"])
@else
    @section('title', "Not found")
@endif

@section('external_css')
    @if(!isset($info["error"]))
        <script src='https://api.mapbox.com/mapbox-gl-js/v1.0.0/mapbox-gl.js'></script>
        <link href='https://api.mapbox.com/mapbox-gl-js/v1.0.0/mapbox-gl.css' rel='stylesheet'/>
    @endif
@endsection

@section('vh_banner')
    @if(!isset($info["error"]))
        <meta name="apple-itunes-app"
              content="app-id=1278250028, app-argument=virtualhub://airportinfo/weather?icao={{$info["airport"]["icao"]}}"/>
    @else
        <meta name="apple-itunes-app" content="app-id=1278250028"/>
    @endif
@endsection

@section('meta_social')
    @if(!isset($info["error"]))
        <meta name="title" content="VirtualHub Web —  {{$info["airport"]["name"]}}">
        <meta name="description"
              content="View full details for this airport and more in the app or on your desktop. Download VirtualHub now from the stores, available on iOS and Android!">

        <!-- Open Graph / Facebook -->
        <meta property="og:type" content="website">
        <meta property="og:url" content="{{asset('vhw/' . $info["airport"]["icao"])}}">
        <meta property="og:title" content="VirtualHub Web —  {{$info["airport"]["name"]}}">
        <meta property="og:description"
              content="View full details for this airport and more in the app or on your desktop. Download VirtualHub now from the stores, available on iOS and Android!">

        <!-- Twitter -->
        <meta property="twitter:card" content="summary_large_image">
        <meta property="twitter:url" content="{{asset('vhw/' . $info["airport"]["icao"])}}">
        <meta property="twitter:title" content="VirtualHub Web —  {{$info["airport"]["name"]}}">
        <meta property="twitter:description"
              content="View full details for this airport and more in the app or on your desktop. Download VirtualHub now from the stores, available on iOS and Android!">
    @endif
@endsection

@php
    use Jenssegers\Agent\Agent;

    $agent = new Agent();
    $os = $agent->platform();

if( !function_exists('mobile_user_agent_switch') ){
	function mobile_user_agent_switch(){
		$device = '';

		if( stristr($_SERVER['HTTP_USER_AGENT'],'ipad') ) {
			$device = "ios";
		} else if( stristr($_SERVER['HTTP_USER_AGENT'],'iphone') || strstr($_SERVER['HTTP_USER_AGENT'],'iphone') ) {
			$device = "ios";
		} else if( stristr($_SERVER['HTTP_USER_AGENT'],'ipod') || strstr($_SERVER['HTTP_USER_AGENT'],'ipod') ) {
			$device = "ios";
		} else if( stristr($_SERVER['HTTP_USER_AGENT'],'blackberry') ) {
			$device = "blackberry";
		} else if( stristr($_SERVER['HTTP_USER_AGENT'],'android') ) {
			$device = "android";
		}

		if( $device ) {
			return $device;
		} return false; {
			return false;
		}
	}
}
@endphp

@section('banner')
    @if($agent->isMobile() || $agent->isPhone() || $agent->isTablet())
        <div shadow class="app_banner">
            <div class="container">
                <div class="banner_left">
                    <div class="vh_logo">

                    </div>

                    <div class="store_badges">
                        <a href="https://itunes.apple.com/app/virtualhub/id1278250028?mt=8" target="_blank">
                            <img src="{{asset("storage/app/images/appstore.png")}}" alt="">
                        </a>

                        <a href="https://play.google.com/store/apps/details?id=com.virtualflight.VirtualHub"
                           target="_blank">
                            <img src="{{asset("storage/app/images/playstore.png")}}" alt="">
                        </a>
                    </div>
                </div>

                <div class="banner_right">
                    <div class="close_banner" onclick="closeBanner()">
                        <i class="far fa-times-circle"></i>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection

@section('content')
    <div class="searchbar">
        <div class="input_bar">
            <label for="search_bar"><i class="fas fa-search"></i></label>
            <input autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" onkeyup="updateSearchSuggestions(this.value)" id="search_bar" type="text"
                   placeholder="Search for an airport">
        </div>
        <div class="hidden search_suggestions" shadow>
            <div class="custom_table" id="search_suggestions">

            </div>
        </div>
        <div class="fillUp"></div>
    </div>

    @if(isset($info["error"]))


    @else
        <div class="preview">
            <div class="preview_content">
                <div class="preview_left">
                    <div class="custom_table">
                        <div class="custom_table_row">
                            <div class="custom_table_row_left">
                                <h1>{{$info["airport"]["name"]}}</h1>
                                <h2>{{$info["airport"]["icao"]}} | {{$info["airport"]["iata"]}}</h2>
                                <h3>{{$info["airport"]["city"]}}@if($info["airport"]["state"] != "")
                                        ,@endif {{$info["airport"]["state"]}}@if($info["airport"]["country"] != "")
                                        , @endif{{$info["airport"]["country"]}}</h3>
                                <h3 id="timezone">{{$info["airport"]["localdate"]["datefull"]}}</h3>
                            </div>
                        </div>
                        <div class="custom_table_row" window="weather_window">
                            <div class="custom_table_row_left">
                                <p>Weather</p>
                                @if(count($info["weather"]) == 0)
                                    No Weather Available
                                @else
                                    @foreach($info["weather"] as $weatherelement)
                                        @if(strtolower($weatherelement[0]) === "metar" )
                                            <p condition="{{$weatherelement[2]}}">{{$weatherelement[1]}}</p>
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                            @if(count($info["weather"]) != 0)
                                @if($agent->isDesktop())
                                    <div class="custom_table_row_right">
                                        <p><i class="fas fa-arrow-right"></i></p>
                                    </div>
                                @endif
                            @endif
                        </div>

                        <div class="custom_table_row" @if(count($info["charts"]) != 0) window="chart_window" @endif>
                            <div class="custom_table_row_left">
                                <p>Charts</p>
                                <p>@if(count($info["charts"]) == 0) No Charts @else {{count($info["charts"])}} @endif
                                    Available</p>
                            </div>
                            @if(count($info["charts"]) != 0)
                                @if($agent->isDesktop())
                                    <div class="custom_table_row_right">
                                        <p><i class="fas fa-arrow-right"></i></p>
                                    </div>
                                @endif
                            @endif
                        </div>
                        <div class="custom_table_row" @if(count($info["gates"]) != 0) window="gates_window" @endif>
                            <div class="custom_table_row_left">
                                <p>Gates</p>
                                <p>@if(count($info["gates"]) == 0) No Gates @else {{count($info["gates"])}} @endif
                                    Available</p>
                            </div>
                            @if(count($info["gates"]) != 0)
                                @if($agent->isDesktop())
                                    <div class="custom_table_row_right">
                                        <p><i class="fas fa-arrow-right"></i></p>
                                    </div>
                                @endif
                            @endif
                        </div>
                        <div class="custom_table_row" @if(count($info["runways"]) != 0) window="runway_window" @endif>
                            <div class="custom_table_row_left">
                                <p>Runways</p>
                                <p>@if(count($info["runways"]) == 0) No Runways @else {{count($info["runways"])}} @endif
                                    Available</p>
                            </div>
                            @if(count($info["runways"]) != 0)
                                @if($agent->isDesktop())
                                    <div class="custom_table_row_right">
                                        <p><i class="fas fa-arrow-right"></i></p>
                                    </div>
                                @endif
                            @endif
                        </div>

                        <div class="custom_table_row" @if(count($info["events"]) != 0) window="events_window" @endif>
                            <div class="custom_table_row_left">
                                <p>Events</p>
                                @if(count($info["events"]) == 0)
                                    <p>No events for this airport</p>
                                @else
                                    <p>{{count($info["events"])}} Events Coming Up Soon</p>
                                    <p>Next event: <br> {{$info["events"][0]["name"]}}
                                        | {{$info["events"][0]["date_time"]}}</p>
                                @endif
                            </div>
                            <div class="custom_table_row_right">
                                <p><i class="fas fa-arrow-right"></i></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="preview_right">
                    <div class="preview_map" id='map'></div>
                </div>
            </div>

            <ins class="adsbygoogle" data-ad-client="ca-pub-5010357477858879" data-ad-slot="1027867489"></ins>
        </div>


        <div class="chart_window hidden">
            <h2 class="window_title">Charts | {{$info["airport"]["icao"]}}</h2>
            <div class="charts_container">
                <div class="charts_left">
                    <div class="custom_table">
                        @foreach($info["charts"] as $chart)
                            <div class="custom_table_row" onclick="openChart('{{$chart["Url"]}}')">
                                <div class="custom_table_row_left">
                                    <p>{{$chart["Name"]}}</p>
                                </div>

                                <div class="custom_table_row_right">
                                    <p><i class="fas fa-arrow-right"></i></p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="charts_right" id="chart_viewer">

                </div>
            </div>
        </div>

        <div class="runway_window hidden">
            <h2 class="window_title">Runways | {{$info["airport"]["icao"]}}</h2>
            <div class="runway_container">
                <div class="runway_left">
                    <div class="custom_table">
                        @foreach($info["runways"] as $runway)
                            <div class="custom_table_row">
                                <div class="custom_table_row_left">
                                    <p>{{$runway["app_idents"]}}</p>
                                    <p>{!! str_replace("\n","<br>", $runway["app_dimension"]) !!}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="gates_window hidden">
            <div class="window_title">
                <h2>Gates | {{$info["airport"]["icao"]}}</h2>
                <label for="gates_filter"></label>
                <select id="gates_filter filter_select">
                    <option value="0" disabled selected>Order By</option>
                    <optgroup label="Ascending">
                        <option value="volvo">Name</option>
                        <option value="volvo">Size</option>
                        <option value="volvo">Type</option>
                        <option value="volvo">Aircraft</option>
                    </optgroup>
                    <optgroup label="Descending">
                        <option value="saab">Name</option>
                        <option value="saab">Size</option>
                        <option value="saab">Type</option>
                        <option value="saab">Aircraft</option>
                    </optgroup>
                </select>
            </div>
            <div class="gates_container">
                <div class="gates_left">
                    <div class="custom_table">
                        @foreach($info["gates"] as $gate)
                            <div class="custom_table_row"
                                 onclick="goToLocation({{$gate["latitude"]}}, {{$gate["longitude"]}})">
                                <div class="custom_table_row_left">
                                    <p>@if($gate["name"] == "") No Name @else {{$gate["name"]}} @endif</p>
                                    <p>{!! nl2br($gate["app_string"]) !!}</p>
                                </div>
                                <div class="custom_table_row_right">
                                    <p><i class="fas fa-arrow-right"></i></p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="gates_right" id="gates_viewer">
                    <div class="gates_map" id='gates_map'></div>
                </div>
            </div>
        </div>

        <div class="weather_window hidden">
            <h2 class="window_title">Weather | {{$info["airport"]["icao"]}}</h2>
            <div class="weather_container">
                <div class="weather_left">
                    <div class="custom_table">
                        @foreach($info["weather"] as $weatherelement)
                            <div class="custom_table_row">
                                <div condition="{{$weatherelement[2]}}" class="custom_table_row_left">
                                    <p>{{$weatherelement[0]}}</p>
                                    <p>{{$weatherelement[1]}}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>


        <div class="events_window hidden">
            <h2 class="window_title">Events | {{$info["airport"]["icao"]}}</h2>
            <div class="events_container">
                <div class="events_left">
                    <div class="custom_table">
                        @foreach($info["events"] as $event)
                            <div class="custom_table_row" json='{{json_encode($event)}}' onclick="">
                                <div class="custom_table_row_left">
                                    <p>{{$event["name"]}}</p>
                                    <p isoformat="{{$event["date_time"]}}"></p>
                                </div>

                                <div class="custom_table_row_right">
                                    <p><i class="fas fa-arrow-right"></i></p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="events_right" id="events_viewer">
                    <div class="custom_table">

                    </div>
                </div>
            </div>
        </div>

    @endif

@endsection

@section('javascript')
    @if(!isset($info["error"]))
        <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
        <script>
            mapboxgl.accessToken = 'pk.eyJ1Ijoic3VkYWZseSIsImEiOiJjajBwb3pkMGYwMDBvMnFvNTd6czFobHBtIn0.yOmnz0zEI7QvEpaEVFmz2Q';
            var light_style = "mapbox://styles/sudafly/cjm89d4011nu02smk5a7e0d8h";
            var dark_style = "mapbox://styles/sudafly/cjt5olcru0ony1fojxep1jayk";
            var current_style = "mapbox://styles/sudafly/cjm89d4011nu02smk5a7e0d8h";

            var myVar = setInterval(time, 60000);
            var searchdelay;

            var isSetMap = false;

            var main_map_loaded = false;
            var gate_map_loaded = false;
            var event_map_loaded = false;


            var currentWindow = "";

            var main_map = new mapboxgl.Map({
                container: 'map',
                style: current_style,
                center: [
                    {{$info["airport"]["longitude"]}},
                    {{$info["airport"]["latitude"]}}],
                zoom: 14,
                pitch: 60
            });

            var gate_map = new mapboxgl.Map({
                container: 'gates_map',
                style: current_style,
                center: [
                    {{$info["airport"]["longitude"]}},
                    {{$info["airport"]["latitude"]}}],
                zoom: 15,
            });

            {{--var event_map = new mapboxgl.Map({--}}
            {{--    container: 'gates_map',--}}
            {{--    style: current_style,--}}
            {{--    center: [--}}
            {{--        {{$info["airport"]["longitude"]}},--}}
            {{--        {{$info["airport"]["latitude"]}}],--}}
            {{--    zoom: 15,--}}
            {{--});--}}

            main_map.on('load', function () {
                main_map_loaded = true;
                mapDarkMode();
                rotateCamera(0);
            });

            gate_map.on('load', function () {
                gate_map_loaded = true;
                mapDarkMode();
            });

            // event_map.on('load', function () {
            //     event_map_loaded = true;
            //     mapDarkMode();
            // });

            function rotateCamera(timestamp) {
                main_map.rotateTo((timestamp / 150) % 360, {duration: 0});
                requestAnimationFrame(rotateCamera);
                mapDarkMode();
            }

            function time() {
                $.ajax({
                    url: "{{asset("/api")}}/timezone",
                    type: "post",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "timezone": "{{$info["airport"]["timezone"]}}",
                    },
                    success: function (response) {
                        $("#timezone").html(response).show();
                    },
                    error: function (jqXHR, textStatus, errorThrown) {


                    }
                });
            }

            function openApp() {
                @if($agent->is('iPhone') || $agent->is('iPad') || $agent->is('iPod'))
                setTimeout(function () {
                    window.location = "https://itunes.apple.com/appdir";
                }, 50);
                window.location = 'virtualhub://airportinfo?icao={{$info["airport"]["icao"]}}';
                @endif

                @if($agent->is('Android'))

                @endif
            }

            function mapDarkMode() {
                var new_style = "";

                if ($('body').css("backgroundColor") == "rgb(34, 34, 34)") {
                    new_style = dark_style;
                } else {
                    new_style = light_style;
                }

                if (current_style !== new_style) {
                    current_style = new_style;
                    main_map.setStyle(current_style);
                    @if($agent->isDesktop())
                    gate_map.setStyle(current_style);
                    @endif
                }
            }

            {{--@if($agent->isMobile() || $agent->isPhone() || $agent->isTablet())--}}
            {{--$("[window]").click(function () {--}}
            {{--    alert("View more in our app! Available for free on iOS or Android.");--}}
            {{--    showBanner();--}}
            {{--});--}}

            {{--@endif--}}

            function openChart(url) {
                document.getElementById("chart_viewer").innerHTML = '<embed src="' + url + '" type="application/pdf">';
            }

            $("[window]").click(function () {
                openWindow($(this).attr("window"));
                $(window).trigger('resize');
            });

            $(".close").click(function () {
                closeWindow(true);
                $(window).trigger('resize');
            });

            $("[json]").click(function () {
                var e = $(this);
                var table = $("#events_viewer").children(".custom_table");
                var json = JSON.parse(e.attr("json"));
                var options = {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric',
                    hour: 'numeric',
                    minute: 'numeric'
                };
                var date = new Date(json.date_time);

                table.empty();

                table.append("<div class=\"custom_table_row\"><div class=\"custom_table_row_left\"><p>" + json.name + "</p><p>" + json.description + "</p></div></div>");

                table.append("<div class=\"custom_table_row\"><div class=\"custom_table_row_left\"><p>When</p><p>" + date.toLocaleString("en-US", options) + " (Your Timezone)</p></div></div>");

                for (var section in json.custom_info) {
                    table.append("<div class=\"custom_table_row\"><div class=\"custom_table_row_left\"><p>" + json.custom_info[section][0] + "</p><p>" + json.custom_info[section][1] + "</p></div></div>");
                }


                @auth


                @endauth

                @guest
                table.append("<div class=\"custom_table_row\"><div class=\"custom_table_row_left\"><p>Join</p><p>The event manager has the rights to remove or change your gate at anytime.</p><br><button onclick='login()'>Login To Join</button></div></div>");
                @endguest
            });

            window.onload = function () {
                document.cookie = "vhw_redirect=null; path=/";
                sectionFromUrl();

                $("[isoformat]").each(function (index) {
                    var e = $(this);
                    var date = new Date(e.attr("isoformat"));
                    var options = {
                        weekday: 'long',
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric',
                        hour: 'numeric',
                        minute: 'numeric'
                    };
                    e.html(date.toLocaleString("en-US", options)).show();
                });

                var input = document.getElementById("search_bar");

                input.addEventListener("keyup", function (event) {
                    if (event.keyCode === 13) {
                        event.preventDefault();
                        // Trigger the button element with a click
                        var list = document.getElementById("search_suggestions").childNodes;

                        if (list.length !== 0) {
                            list[0].click();
                        }
                    }
                });
            };

            function login() {
                var cookieAccepted = getCookie("laravel_cookie_consent");

                if (cookieAccepted) {
                    document.cookie = "vhw_redirect={{$info["airport"]["icao"]}}; path=/";
                }

                setTimeout(function () {
                    window.location = "{{asset('/login')}}";
                }, 100);
            }

            function openWindow(w) {
                currentWindow = w;
                urlWindow = "";

                $('.preview').css("display", "none");
                $('.searchbar').css("display", "none");
                $('.' + this.currentWindow).css("display", "block");
                $('.close').css("display", "block");


                switch (currentWindow) {
                    case "weather_window":
                        urlWindow = "weather";
                        break;

                    case "chart_window":
                        urlWindow = "charts";
                        break;

                    case "gates_window":
                        urlWindow = "gates";
                        break;

                    case "runway_window":
                        urlWindow = "runways";
                        break;

                    case "events_window":
                        urlWindow = "events";
                        break;

                    default:
                        break;
                }

                switch (getPathLastSegment()) {
                    case "weather":
                        removePathLastSegment(true);
                        break;

                    case "charts":
                        removePathLastSegment(true);
                        break;

                    case "gates":
                        removePathLastSegment(true);
                        break;

                    case "runways":
                        removePathLastSegment(true);
                        break;

                    case "events":
                        removePathLastSegment(true);
                        break;

                    default:
                        break;
                }

                if (getPathLastSegment() !== urlWindow) {
                    updateUrlAdd(urlWindow);
                }

                main_map.resize();
                gate_map.resize();
            }

            window.onpopstate = function(event) {
                if (currentWindow !== "") {
                    closeWindow(false);
                } else {
                    switch (getPathLastSegment()) {
                        case "weather":
                            openWindow("weather_window");
                            break;

                        case "charts":
                            openWindow("weather_window");
                            break;

                        case "gates":
                            openWindow("weather_window");
                            break;

                        case "runways":
                            openWindow("weather_window");
                            break;

                        case "events":
                            openWindow("weather_window");
                            break;

                        default:
                            break;
                    }
                }
            };


            function closeWindow(allow) {
                $('.' + currentWindow).css("display", "none");
                $('.preview').css("display", "block");
                $('.searchbar').css("display", "block");
                $('.close').css("display", "none");

                if (getPathLastSegment() !== "{{$info["airport"]["icao"]}}") {
                    removePathLastSegment(allow);
                }

                currentWindow = "";
                main_map.resize();
                gate_map.resize();
            }


            function goToLocation(lat, long) {
                if (gate_map_loaded) {
                    gate_map.flyTo({
                        center: [
                            long,
                            lat],
                        zoom: 18,
                    });
                }
            }

            function sectionFromUrl() {
                urlWindow = "";

                switch (getPathLastSegment()) {
                    case "weather":
                        urlWindow = "weather_window";
                        break;

                    case "charts":
                        urlWindow = "chart_window";
                        break;

                    case "gates":
                        urlWindow = "gates_window";
                        break;

                    case "runways":
                        urlWindow = "runway_window";
                        break;

                    case "events":
                        urlWindow = "events_window";
                        break;

                    default:
                        break;
                }

                if (urlWindow !== "") {
                    openWindow(urlWindow);
                }
            }

            function getPathLastSegment() {
                var parts = document.location.href.toLowerCase().split('/');
                return parts.pop() || parts.pop()
            }

            function removePathLastSegment(allow) {
                var the_arr = document.location.href.toLowerCase().split('/');
                the_arr.pop();

                if (allow) {
                    window.history.pushState('', "", (the_arr.join('/')));
                }
            }

            function updateUrlAdd(path) {
                window.history.pushState('', "", document.location.href + "/" + path);
            }

            function loadChart(url, name) {

                var params = [['_token', '{{ csrf_token() }}'], ['name', name], ['url', url]];

                var inputs = $.map(params, function (e, i) {
                    return '<input type="hidden" name="' + e[0] + '" value="' + encodeURIComponent(e[1]) + '"/>';
                });

                var form = '<form action="{{asset("/view")}}/chart" id="hidden-form" method="post" target="_blank">' + inputs.join('') + '</form>';

                $('#hidden-div').html(form);
                $('#hidden-form').submit();
            }

            $('input').focusout(function () {
                setTimeout(function () {
                    $(".search_suggestions").addClass("hidden")
                }, 500);
            });

            $('input').focusin(function () {
                updateSearchSuggestions($(this).val());
            });

            function updateSearchSuggestions(query) {
                var sugg_table_div = $(".searchbar > .search_suggestions");
                var sugg_table = $(".searchbar > .search_suggestions > .custom_table");
                var url = "{{asset("/view")}}/";

                clearTimeout(searchdelay);

                if (query === "" || query.length <= 1) {
                    sugg_table_div.addClass("hidden");
                    return;
                }

                searchdelay = setTimeout(function () {
                    $.ajax({
                        url: "{{asset("/api")}}/search/" + query,
                        type: "get",
                        success: function (response) {
                            sugg_table.empty();

                            response.forEach(el => {
                                sugg_table.append("<div onclick='window.location.href = \"" + url + el.icao + "\";' class=\"custom_table_row\">" +
                                    "                    <div class=\"custom_table_row_left\">" +
                                    "                        <p>" + el.app_string + "</p>" +
                                    "                        <p>" + el.name + "</p>" +
                                    "                    </div>" +
                                    "                    <div class=\"custom_table_row_right\">" +
                                    "                        <p><i class=\"fas fa-arrow-right\"></i></p>" +
                                    "                    </div>" +
                                    "                </div>");
                            });

                            if (response.length === 0) {
                                sugg_table_div.addClass("hidden")
                            } else {
                                sugg_table_div.removeClass("hidden")
                            }
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            sugg_table_div.addClass("hidden")
                        }
                    });
                }, 300);
            }

            function getCookie(name) {
                var re = new RegExp(name + "=([^;]+)");
                var value = re.exec(document.cookie);
                return (value != null) ? unescape(value[1]) : null;
            }

        </script>

        <script>
            (adsbygoogle = window.adsbygoogle || []).push({});
        </script>

    @endif
@endsection