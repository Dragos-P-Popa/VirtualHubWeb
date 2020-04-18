@extends('layouts.main_page')

@section('title', 'Home')
@section('app_section', env('APP_VHW'))
@section('logo', 'vhw')

@section('external_css')
    <script src='https://api.mapbox.com/mapbox-gl-js/v1.0.0/mapbox-gl.js'></script>
    <link href='https://api.mapbox.com/mapbox-gl-js/v1.0.0/mapbox-gl.css' rel='stylesheet'/>
@endsection

@section('vh_banner')
    <meta name="apple-itunes-app" content="app-id=1278250028, app-argument=virtualhub://"/>
@endsection

@section('header_style', 'header_style')

@php
    use Illuminate\Support\Facades\DB;

    $airport = DB::select( "SELECT COUNT(id) as 'total' FROM vh_airports" );
    $runways = DB::select( "SELECT COUNT(id) as 'total' FROM vh_runways" );
    $gates = DB::select( "SELECT COUNT(id) as 'total' FROM vh_gates" );
    $total_searches = DB::select( "SELECT * FROM realtime_data WHERE name = 'total_vh_airportinfo_api_used'" );
    $populair_airports = DB::select( "SELECT name, populairity, icao FROM vh_airports ORDER BY populairity DESC LIMIT 4" );

@endphp

@section('big_header')
    <a href="{{url("api/sitemap/allairports")}}" hidden>hidden</a>

    <div class="vh_header">
        <div class="container">
            <div class="header_text">
                <h1 must_be_white>VirtualHub Net</h1>
                <p must_be_white>Your personal assistant!</p>
            </div>

            <div class="header_searchbar" shadow>
                <div class="bar">
                    <input autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" id="search_bar"
                           type="text"
                           placeholder="Search for an airport...">
                    <a href="#"><i class="fas fa-search"></i></a>
                </div>
                <div class="hidden search_suggestions" shadow>
                    <div class="custom_table" id="search_suggestions">

                    </div>
                </div>
            </div>
        </div>

        <div class="data_count container" shadow>
            <div class="collection_view">
                <div class="collection_item">
                    <p>Airports</p>
                    <p>{{number_format($airport[0]->total , 0, ',', '.')}}</p>
                </div>
                <div class="collection_item">
                    <p>Gates</p>
                    <p>{{number_format($gates[0]->total , 0, ',', '.')}}</p>
                </div>
                <div class="collection_item">
                    <p>Runways</p>
                    <p>{{number_format($runways[0]->total , 0, ',', '.')}}</p>
                </div>
                <div class="collection_item">
                    <p>Total Searches</p>
                    <p>{{number_format($total_searches[0]->total , 0, ',', '.')}}</p>
                </div>
            </div>
        </div>
    </div>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
@endsection

@section('content')
    <div class="blocks_3">
        <h2>How it works</h2>
        <br>
        <div class="block_content">
            <div class="block">
                <i class="fas fa-search"></i>
                <div>
                    <h3>Search</h3>
                    <p>You can search an airport by name, icao, iata, country and city in the shown searchbar.</p>
                </div>
            </div>

            <div class="block">
                <i class="far fa-eye"></i>
                <div>
                    <h3>Planning</h3>
                    <p>Plan a runway, gate based on your aircraft and weather. Find a good taxi route and take part of
                        an event.</p>
                </div>
            </div>

            <div class="block">
                <i class="fas fa-globe-americas"></i>
                <div>
                    <h3>Fly</h3>
                    <p>Got everything ready? It's then time to fly! *Follow ATC instructions if available!*</p>
                </div>
            </div>
        </div>
    </div>

    <br>
    <br>
    <br>
    <br>
    <hr>
    <br>
    <br>
    <br>
    <br>

    <div class="list_2_rows">
        <h2>What we offer</h2>
        <br>
        <div class="list_content">
            <div class="list_item">
                <i class="fas fa-cloud-sun"></i>
                <div>
                    <h3>Weather</h3>
                    <p>You can search an airport by name, icao, iata, country and city in the shown searchbar.</p>
                </div>
            </div>

            <div class="list_item">
                <i class="far fa-file-alt"></i>
                <div>
                    <h3>Charts</h3>
                    <p>View airport overviews, star and sid charts, and many other types.</p>
                </div>
            </div>

            <div class="list_item">
                <i class="fab fa-markdown"></i>
                <div>
                    <h3>Gates</h3>
                    <p>Find the perfect gate for your aircraft! Both on your departure and arrival airport.</p>
                </div>
            </div>

            <div class="list_item">
                <i class="fas fa-road"></i>
                <div>
                    <h3>Runways</h3>
                    <p>Check if the airport can handle your aircraft. Is the runway long enough?</p>
                </div>
            </div>

            <div class="list_item">
                <i class="fas fa-broadcast-tower"></i>
                <div>
                    <h3>ATC</h3>
                    <p>Check if the airport offers an Approach ATC or one of the other ATC's.</p>
                </div>
            </div>

            <div class="list_item">
                <i class="fas fa-calendar-alt"></i>
                <div>
                    <h3>Events</h3>
                    <p>See if there will be an event soon at the selected airport and join. Or create your own
                        event.</p>
                </div>
            </div>
        </div>
    </div>

    <br>
    <br>
    <br>
    <br>
    <hr>
    <br>
    <br>
    <br>
    <br>

    <h2>Our top 4 populair airports</h2>
    <br>
    <div class="data_count" shadow>
        <div class="collection_view">
            @foreach($populair_airports as $airport)
                <div class="collection_item small">
                    <p><span>{{number_format($airport->populairity , 0, ',', '.')}}</span> Views</p>
                    <p><a href="{{url("/view")}}/{{$airport->icao}}">{{$airport->name}}</a></p>
                </div>
            @endforeach
        </div>
    </div>


@endsection

@section('javascript')
    <script>
        var searchdelay;

        window.onload = function () {
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

                updateSearchSuggestions($(this).val())
            });
        };

        $('input').focusout(function () {
            setTimeout(function () {
                $(".search_suggestions").addClass("hidden")
            }, 500);
        });

        $('input').focusin(function () {
            updateSearchSuggestions($(this).val());
        });

        function updateSearchSuggestions(query) {
            var sugg_table_div = $(".search_suggestions");
            var sugg_table = $(".search_suggestions > .custom_table");
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
    </script>
@endsection
