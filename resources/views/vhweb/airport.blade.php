@extends('layouts.main_page')
@section('app_section', env('APP_VHW'))
@section('logo', 'vhw')

@if(!isset($info->error))

    @section('title', $info->name . " - " . $info->icao . " / " . $info->iata . "")
@else
    @section('title', "Not found")
@endif

@section('external_css')
    @if(!isset($info->error))
        <!--suppress VueDuplicateTag -->
        <script src='https://api.mapbox.com/mapbox-gl-js/v1.0.0/mapbox-gl.js'></script>
        <link href='https://api.mapbox.com/mapbox-gl-js/v1.0.0/mapbox-gl.css' rel='stylesheet'/>
    @endif
@endsection

@section('vh_banner')
    @if(!isset($info->error))
        <meta name="apple-itunes-app"
              content="app-id=1278250028, app-argument=virtualhub://airportinfo/weather?icao={{$info->icao}}"/>
    @else
        <meta name="apple-itunes-app" content="app-id=1278250028"/>
    @endif
@endsection

@php
    if (!isset($info->error)) {
        $sm = ["title" => "VirtualHub Web — " . $info->name,
               "description" => "View the weather, charts, gates, and more for {$info->name}.",
               "url" => url("view/" .  $info->icao),
               "keywords" => "airport, " . $info->name . ", " . $info->icao];

               $sharing_text = $sm["description"] . " - " . $sm["url"];
    }

@endphp


@section('meta_keyword', $sm["keywords"])

@section('meta_description', $sm["description"])

@section('meta_social')
    @if(!isset($info["error"]))
        <meta name="title" content="{{$sm["title"]}}">

        <!-- Open Graph / Facebook -->
        <meta property="og:type" content="website">
        <meta property="og:url" content="{{$sm["url"]}}">
        <meta property="og:title" content="{{$sm["title"]}}">
        <meta property="og:description" content="{{$sm["description"]}}">

        <!-- Twitter -->
        <meta property="twitter:card" content="summary_large_image">
        <meta property="twitter:url" content="{{$sm["url"]}}">
        <meta property="twitter:title" content="{{$sm["title"]}}">
        <meta property="twitter:description" content="{{$sm["description"]}}">
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
                            <img src="{{asset("/images/appstore.png")}}" alt="">
                        </a>

                        <a href="https://play.google.com/store/apps/details?id=com.virtualflight.VirtualHub"
                           target="_blank">
                            <img src="{{asset("/images/playstore.png")}}" alt="">
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
    <div class="top_airport_info">
        <div class="searchbar">
            <div class="input_bar">
                <label for="search_bar"><i class="fas fa-search"></i></label>
                <input autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false"
                       onkeyup="updateSearchSuggestions(this.value)" id="search_bar" type="text"
                       placeholder="Search for an airport">
            </div>
            <div class="hidden search_suggestions" shadow>
                <div class="custom_table" id="search_suggestions">

                </div>
            </div>
            <div class="fillUp"></div>
        </div>
    </div>

    @if(isset($info->error))


    @else
        <div class="preview">
            <div class="preview_content">
                <div class="preview_left">
                    <div class="custom_table">
                        <div class="custom_table_row">
                            <div class="custom_table_row_left">
                                <h1>{{$info->name}}</h1>
                                <h2>{{$info->icao}} | {{$info->iata}}</h2>
                                <h3>{{$info->city}}@if($info->state != "")
                                        ,@endif {{$info->state}}@if($info->country != "")
                                        , @endif{{$info->country}}</h3>
                                <h3 id="timezone">{{$info->localdate->datefull}}</h3>
                                <a class="share_native" onclick="share('{{$sm["title"]}}', '{{$sm["url"]}}')">Share</a>

                                <div class="sharing">
                                    <div>
                                        <a onclick="copy_text('{{$sm["url"]}}')"><i class="fas fa-link"></i></a>
                                    </div>
                                    <div>
                                        <a href="https://community.infiniteflight.com/new-topic?title=&body=[{{$sm["title"]}}]({{$sm["url"]}})"
                                           class="ifc_share" target="_blank"><i class="fas fa-infinity"></i></a>
                                    </div>
                                    <div>
                                        <a href="https://www.facebook.com/sharer/sharer.php?u={{$sm["url"]}}" target="_blank"><i
                                                class="fab fa-facebook-f"></i></a>
                                    </div>
                                    <div>
                                        <a href="https://twitter.com/intent/tweet?url={{$sm["url"]}}" target="_blank"><i
                                                class="fab fa-twitter"></i></a>
                                    </div>
                                    <div>
                                        <a href="https://wa.me/?text={{$sm["url"]}}" target="_blank"><i class="fab fa-whatsapp"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="custom_table_row" @if($agent->isDesktop()) @if(count($info["weather"]) != 0) window="weather_window" @endif @endif>
                            <div class="custom_table_row_left">
                                <p>Weather</p>
                                @if(count($info->weather) == 0)
                                    No Weather Available
                                @else
                                    @foreach($info->weather as $weatherelement)
                                        @if(strtolower($weatherelement[0]) === "metar" )
                                            <p condition="{{$weatherelement[2]}}">{{$weatherelement[1]}}</p>
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                            @if(count($info->weather) != 0)
                                @if($agent->isDesktop())
                                    <div class="custom_table_row_right">
                                        <p><i class="fas fa-arrow-right"></i></p>
                                    </div>
                                @endif
                            @endif
                        </div>

                        <div class="custom_table_row" @if($agent->isDesktop()) @if(count($info->charts) != 0) window="chart_window" @endif @endif>
                            <div class="custom_table_row_left">
                                <p>Charts</p>
                                <p>@if(count($info->charts) == 0) No Charts @else {{count($info->charts)}} @endif
                                    Available</p>
                            </div>
                            @if(count($info->charts) != 0)
                                @if($agent->isDesktop())
                                    <div class="custom_table_row_right">
                                        <p><i class="fas fa-arrow-right"></i></p>
                                    </div>
                                @endif
                            @endif
                        </div>
                        <div class="custom_table_row" @if($agent->isDesktop()) @if(count($info->gates) != 0) window="gates_window" @endif @endif>
                            <div class="custom_table_row_left">
                                <p>Gates</p>
                                <p>@if(count($info->gates) == 0) No Gates @else {{count($info->gates)}} @endif
                                    Available</p>
                            </div>
                            @if(count($info->gates) != 0)
                                @if($agent->isDesktop())
                                    <div class="custom_table_row_right">
                                        <p><i class="fas fa-arrow-right"></i></p>
                                    </div>
                                @endif
                            @endif
                        </div>
                        <div class="custom_table_row" @if($agent->isDesktop()) @if(count($info->runways) != 0) window="runway_window" @endif @endif>
                            <div class="custom_table_row_left">
                                <p>Runways</p>
                                <p>@if(count($info->runways) == 0) No Runways @else {{count($info->runways)}} @endif
                                    Available</p>
                            </div>
                            @if(count($info->runways) != 0)
                                @if($agent->isDesktop())
                                    <div class="custom_table_row_right">
                                        <p><i class="fas fa-arrow-right"></i></p>
                                    </div>
                                @endif
                            @endif
                        </div>

                        <div class="custom_table_row" @if(count($info->events) != 0) window="events_window" @endif>
                            <div class="custom_table_row_left">
                                <p>Events</p>
                                @if(count($info->events) == 0)
                                    <p>No events for this airport</p>
                                @else
                                    <p>{{count($info->events)}} @if(count($info->events) == 1) Event @else
                                            Events @endif Coming Up Soon</p>
                                    <p id="ev_list_str">Next event: <br> {{$info->events[0]->title}}
                                        | </p>
                                @endif
                                <br>
                                <p><a href="{{url("events/" . $info->icao . "/new")}}">New event</a></p>
                            </div>
                            @if(count($info["events"]) != 0)
                                <div class="custom_table_row_right">
                                    <p><i class="fas fa-arrow-right"></i></p>
                                </div>
                            @endif
                        </div>


                    </div>
                </div>
                <div class="preview_right">
                    <div class="preview_map" id='map'></div>
                </div>
            </div>

            <ins class="adsbygoogle" data-ad-client="ca-pub-5010357477858879" data-ad-slot="1027867489"></ins>
        </div>


        @if(count($info->charts) != 0)
            <div class="chart_window hidden">
                <h2 class="window_title">Charts | {{$info->icao}}</h2>
                <div class="charts_container">
                    <div class="charts_left">
                        <div class="custom_table">
                            @foreach($info->charts as $chart)
                                <div class="custom_table_row" onclick="openChart('{{$chart->Url}}')">
                                    <div class="custom_table_row_left">
                                        <p>{{$chart->Name}}</p>
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
        @endif

        @if(count($info->runways) != 0)
            <div class="runway_window hidden">
                <h2 class="window_title">Runways | {{$info->icao}}</h2>
                <div class="runway_container">
                    <div class="runway_left">
                        <div class="custom_table">
                            @foreach($info->runways as $runway)
                                <div class="custom_table_row">
                                    <div class="custom_table_row_left">
                                        <p>{{$runway->app_idents}}</p>
                                        <p>{!! str_replace("\n","<br>", $runway->app_dimension) !!}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if(count($info->gates) != 0)
            <div class="gates_window hidden">
                <div class="window_title">
                    <h2>Gates | {{$info->icao}}</h2>
                </div>

                <div class="gates_container">
                    <div class="gates_left">
                        <div class="custom_table">
                            @foreach($info->gates as $gate)
                                <div class="custom_table_row"
                                     onclick="goToLocation({{$gate->longitude}}, {{$gate->latitude}})">
                                    <div class="custom_table_row_left">
                                        <p>@if($gate->name == "") No Name @else {{$gate->name}} @endif</p>
                                        <p>{!! nl2br($gate->app_string) !!}</p>
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
        @endif


        @if(count($info->weather) != 0)
            <div class="weather_window hidden">
                <h2 class="window_title">Weather | {{$info->icao}}</h2>
                <div class="weather_container">
                    <div class="weather_left">
                        <div class="custom_table">
                            @foreach($info->weather as $weatherelement)
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
        @endif

        @if(count($info->events) != 0)
            <div class="events_window hidden">
                <h2 class="window_title">Events | {{$info->icao}}</h2>
                <div class="events_container">
                    <div class="events_left">
                        <div class="custom_table">
                            @foreach($info->events as $event)
                                <div class="custom_table_row" json='{{json_encode($event)}}' onclick="">
                                    <div class="custom_table_row_left">
                                        <p>{{$event->title}}</p>
                                        <p isoformat="{{$event->start}}"></p>
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

                        <div class="gates_map hidden" id='events_map'></div>

                    </div>
                </div>
            </div>
        @endif

    @endif
    <noscript>
        <div>
            <h1>Your browser does not support JavaScript!</h1>
        </div>
    </noscript>
@endsection

@section('javascript')
    @if(!isset($info->error))
        <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
        <script>
            if (!mapboxgl.supported()) {
                window.open("{{url("/unsupported/")}}", "_self")
            }

            mapboxgl.accessToken = 'pk.eyJ1Ijoic3VkYWZseSIsImEiOiJjajBwb3pkMGYwMDBvMnFvNTd6czFobHBtIn0.yOmnz0zEI7QvEpaEVFmz2Q';
            var light_style = "mapbox://styles/sudafly/cjm89d4011nu02smk5a7e0d8h";
            var dark_style = "mapbox://styles/sudafly/cjt5olcru0ony1fojxep1jayk";
            var current_style = "mapbox://styles/sudafly/cjm89d4011nu02smk5a7e0d8h";

            var myVar = setInterval(time, 60000);
            var searchdelay;

            var isSetMap = false;

            var main_map_loaded = false;
            var gate_map_loaded = false;
            var events_map_loaded = false;

            var currentWindow = "";
            var selectedEvent;

                    @auth
            var current_user = {{Auth::user()->id}};

                    @endauth

            var main_map = new mapboxgl.Map({
                    container: 'map',
                    style: current_style,
                    center: [
                        {{$info->longitude}},
                        {{$info->latitude}}],
                    zoom: 14,
                    pitch: 60
                });

            var gate_map = new mapboxgl.Map({
                container: 'gates_map',
                style: current_style,
                center: [
                    {{$info->longitude}},
                    {{$info->latitude}}],
                zoom: 15,
            });

                    @if(count($info["events"]) != 0)
            var events_map = new mapboxgl.Map({
                    container: 'events_map',
                    style: current_style,
                    center: [
                        {{$info->longitude}},
                        {{$info->latitude}}],
                    zoom: 15,
                    // minZoom: 11,
                    maxBounds: [
                        [{{$info->bounds[2]}}, {{$info->bounds[0]}}],
                        [{{$info->bounds[3]}}, {{$info->bounds[1]}}]// Northeast coordinates
                    ]
                });
            @endif

            {{--                    @php--}}
            {{--                        $randomGates = array_rand($info["gates"], 7);--}}
            {{--                        $zooms = [15, 16, 17, 18];--}}
            {{--                        $pitch = [30, 40, 50, 55, 60];--}}

            {{--                        $java_array = '{--}}
            {{--                                    "camera": {--}}
            {{--                                     center: [' . $info->["longitude"] . ', ' . $info->["latitude"] . '],--}}
            {{--                                     zoom: 12,--}}
            {{--                                     pitch: 10--}}
            {{--                                     }--}}
            {{--                                  },';--}}

            {{--                        foreach ($randomGates as $gate) {--}}
            {{--                            $java_array = $java_array . '{--}}
            {{--                                    "camera": {--}}
            {{--                                     center: [' . $info["gates"][$gate]["latitude"] . ', ' . $info["gates"][$gate]["longitude"] . '],--}}
            {{--                                     zoom: ' . $zooms[array_rand($zooms)] . ',--}}
            {{--                                     pitch: ' . $pitch[array_rand($pitch)] . '--}}
            {{--                                     }--}}
            {{--                                  },';--}}
            {{--                        }--}}


            {{--                        $java_array = substr($java_array, 0, -1);--}}
            {{--                    @endphp--}}

            {{--            var locations = [{!! $java_array !!}];--}}

            main_map.on('load', function () {
                main_map_loaded = true;
                mapDarkMode();
                rotateCamera(0);
                // playback(0);
            });

            gate_map.on('load', function () {
                gate_map_loaded = true;
                mapDarkMode();
            });

            @if(count($info->events) != 0)
            events_map.on('load', function () {
                events_map_loaded = true;
                mapDarkMode();

                events_map.on('click', 'gates', function (e) {
                    var gate = events_map.queryRenderedFeatures(e.point)[0];
                    var gate_id = gate.properties.id;

                    $.ajax({
                        url: "{{url("/api/events/join")}}",
                        type: "post",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "event_id": selectedEvent,
                            "gate_id": gate_id,
                        },
                        success: function (response) {
                            if (response.success == true) {
                                updateOccupiedGates();
                            } else {
                                updateOccupiedGates();
                                alert(response.reason)
                            }
                        },
                        error: function (jqXHR, textStatus, errorThrown) {


                        }
                    });

                });
            });


            function updateOccupiedGates() {

                $.ajax({
                    url: "{{url("/api/events/gates")}}",
                    type: "post",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "event_id": selectedEvent,
                    },
                    success: function (response) {
                        if (response.success == true) {
                            $(".occupied_gate").remove();

                            for (var i = 0; i < response.gates.length; i++) {
                                var obj = response.gates[i];

                                var el = document.createElement('i');

                                if (obj.user_id == current_user) {
                                    el.className = 'occupied_gate byuser fas fa-check';
                                } else {
                                    el.className = 'occupied_gate fas fa-times';
                                }

                                new mapboxgl.Marker(el)
                                    .setLngLat([obj.latitude, obj.longitude])
                                    .addTo(events_map);
                            }

                        } else {
                            alert(response.reason)
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {


                    }
                });
            }

            @endif

            function playback(index) {
                main_map.flyTo(locations[index].camera);

                main_map.once('moveend', function () {

                    window.setTimeout(function () {
                        index = (index + 1 === locations.length) ? 0 : index + 1;
                        playback(index);
                    }, 3000); // After callback, show the location for 3 seconds.
                });
            }

            function rotateCamera(timestamp) {
                main_map.rotateTo((timestamp / 150) % 360, {duration: 0});
                requestAnimationFrame(rotateCamera);
                mapDarkMode();
            }

            function time() {
                $.ajax({
                    url: "{{url("/api/timezone")}}",
                    type: "post",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "timezone": "{{$info->timezone}}",
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
                window.location = 'virtualhub://airportinfo?icao={{$info->icao}}';
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
                    @if(count($info->events) != 0)
                    events_map.setStyle(current_style);

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
                var table = $("#events_viewer > .custom_table");
                var json = JSON.parse(e.attr("json"));

                var v = convert_utc_to_local(json.start);
                var v2 = convert_utc_to_local(json.start, "ev_list_str");

                table.empty();

                selectedEvent = json.id;

                table.append("<div class=\"custom_table_row\"><div class=\"custom_table_row_left\"><p>" + json.title + "</p><p>" + json.description + "</p></div></div>");

                table.append("<div class=\"custom_table_row\"><div class=\"custom_table_row_left\"><p>When (Your Timezone)</p><p>From: " + convert_utc_to_local(json.start) + "<br>To: " + convert_utc_to_local(json.end) + "</p></div></div>");

                table.append("<div class=\"custom_table_row\"><div class=\"custom_table_row_left\"><p>Route</p><p>" + json.route + "</p></div></div>");

                table.append("<div class=\"custom_table_row\"><div class=\"custom_table_row_left\"><p>Server</p><p>" + json.server + "</p></div></div>");



                var Base64 = {
                    _keyStr: "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=", encode: function (e) {
                        var t = "";
                        var n, r, i, s, o, u, a;
                        var f = 0;
                        e = Base64._utf8_encode(e);
                        while (f < e.length) {
                            n = e.charCodeAt(f++);
                            r = e.charCodeAt(f++);
                            i = e.charCodeAt(f++);
                            s = n >> 2;
                            o = (n & 3) << 4 | r >> 4;
                            u = (r & 15) << 2 | i >> 6;
                            a = i & 63;
                            if (isNaN(r)) {
                                u = a = 64
                            } else if (isNaN(i)) {
                                a = 64
                            }
                            t = t + this._keyStr.charAt(s) + this._keyStr.charAt(o) + this._keyStr.charAt(u) + this._keyStr.charAt(a)
                        }
                        return t
                    }, decode: function (e) {
                        var t = "";
                        var n, r, i;
                        var s, o, u, a;
                        var f = 0;
                        e = e.replace(/[^A-Za-z0-9\+\/\=]/g, "");
                        while (f < e.length) {
                            s = this._keyStr.indexOf(e.charAt(f++));
                            o = this._keyStr.indexOf(e.charAt(f++));
                            u = this._keyStr.indexOf(e.charAt(f++));
                            a = this._keyStr.indexOf(e.charAt(f++));
                            n = s << 2 | o >> 4;
                            r = (o & 15) << 4 | u >> 2;
                            i = (u & 3) << 6 | a;
                            t = t + String.fromCharCode(n);
                            if (u != 64) {
                                t = t + String.fromCharCode(r)
                            }
                            if (a != 64) {
                                t = t + String.fromCharCode(i)
                            }
                        }
                        t = Base64._utf8_decode(t);
                        return t
                    }, _utf8_encode: function (e) {
                        e = e.replace(/\r\n/g, "\n");
                        var t = "";
                        for (var n = 0; n < e.length; n++) {
                            var r = e.charCodeAt(n);
                            if (r < 128) {
                                t += String.fromCharCode(r)
                            } else if (r > 127 && r < 2048) {
                                t += String.fromCharCode(r >> 6 | 192);
                                t += String.fromCharCode(r & 63 | 128)
                            } else {
                                t += String.fromCharCode(r >> 12 | 224);
                                t += String.fromCharCode(r >> 6 & 63 | 128);
                                t += String.fromCharCode(r & 63 | 128)
                            }
                        }
                        return t
                    }, _utf8_decode: function (e) {
                        var t = "";
                        var n = 0;
                        var r = c1 = c2 = 0;
                        while (n < e.length) {
                            r = e.charCodeAt(n);
                            if (r < 128) {
                                t += String.fromCharCode(r);
                                n++
                            } else if (r > 191 && r < 224) {
                                c2 = e.charCodeAt(n + 1);
                                t += String.fromCharCode((r & 31) << 6 | c2 & 63);
                                n += 2
                            } else {
                                c2 = e.charCodeAt(n + 1);
                                c3 = e.charCodeAt(n + 2);
                                t += String.fromCharCode((r & 15) << 12 | (c2 & 63) << 6 | c3 & 63);
                                n += 3
                            }
                        }
                        return t
                    }
                }

                for (var section in json.sections) {
                    table.append("<div class=\"custom_table_row\"><div class=\"custom_table_row_left\"><p>" + json.sections[section].title + "</p><p>" + Base64.decode(json.sections[section].content) + "</p></div></div>");
                }

                table.append("<div class=\"custom_table_row\"><div class=\"custom_table_row_left\"><p>Manager</p><p>" + json.event_manager + "</p></div></div>");


                @guest
                table.append("<div class=\"custom_table_row\"><div class=\"custom_table_row_left\"><p>Join</p><p>The event manager has the rights to remove or change your gate at anytime.</p><br><button onclick='login()'>Login To Join</button></div></div>");
                @endguest

                @auth
                @if(count($info->events) != 0)
                table.append("<div class=\"custom_table_row\"><div class=\"custom_table_row_left\"><p>Join</p><p>Select an gate to join</p></div></div>");

                $("#events_map").removeClass("hidden");
                events_map.resize();
                updateOccupiedGates();

                @endif

                var current_user = {{Auth::user()->id}};

                if (json.user_id == current_user){
                    table.append("<div class=\"custom_table_row\"><div class=\"custom_table_row_left\"><p>Remove event</p><a href='{{url('/api/events/' . '23' . '/remove/')}}'>Remove this event</a>");


                    console.log(json);
                }

                @endauth
            });

            function convert_utc_to_local(time, id = "") {
                var options = {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric',
                    hour: 'numeric',
                    minute: 'numeric'
                };
                var date = new Date(time);

                var v = date.toLocaleString("en-US", options);
                if (id !== "") {
                    $('#' + id).append(v)
                } else {
                    return v
                }
            }

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

                if (navigator.share) {
                    $(".sharing").remove();
                } else {
                    $(".share_native").remove();
                }
            };

            function login() {
                var cookieAccepted = getCookie("laravel_cookie_consent");

                if (cookieAccepted) {
                    document.cookie = "vhw_redirect={{$info->icao}}; path=/";
                }

                setTimeout(function () {
                    window.location = "{{asset('/login')}}";
                }, 100);
            }

            function openWindow(w) {
                currentWindow = w;
                urlWindow = "";

                $('.preview').css("display", "none");
                $('.top_airport_info').css("display", "none");
                $('.nav_links').css("display", "none");
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

                @if(count($info->events) != 0)
                events_map.resize();

                @endif
            }

            window.onpopstate = function (event) {
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
                $('.top_airport_info').css("display", "block");
                $('.nav_links').css("display", "flex");
                $('.close').css("display", "none");

                if (getPathLastSegment() !== "{{$info->icao}}") {
                    removePathLastSegment(allow);
                }

                currentWindow = "";
                main_map.resize();
                gate_map.resize();
                @if(count($info->events) != 0)
                events_map.resize();

                @endif
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

            function copy_text(str) {
                var el = document.createElement('textarea');
                el.value = str;
                el.setAttribute('readonly', '');
                el.style = {position: 'absolute', left: '-9999px'};
                document.body.appendChild(el);
                el.select();
                document.execCommand('copy');
                document.body.removeChild(el);

                alert("Page link has been copied!")
            }

            function share(title, url) {
                navigator.share({
                    title: title,
                    url: url
                }).then(() => {
                    console.log('Thanks for sharing!');
                }).catch(console.error);
            }
        </script>

        <script>
            (adsbygoogle = window.adsbygoogle || []).push({});
        </script>

    @endif
@endsection
