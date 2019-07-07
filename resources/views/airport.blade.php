@extends('layouts.app')
@section('title', 'Airport | ' . $info["airport"]["icao"])

@php
    use Jenssegers\Agent\Agent;

    $agent = new Agent();
    $os = $agent->platform();
@endphp

@section('content')

    <section>
        <div class="section_header">
            <h2>{{$info["airport"]["name"]}}</h2>
            <p>{{$info["airport"]["icao"]}} | {{$info["airport"]["iata"]}}</p>
            <p>{{$info["airport"]["localdate"]["datefull"]}}</p>
            @if($os == "OS X" || $os == "iOS")
                <a href="http://maps.apple.com/?address={{$info["airport"]["city"]}},{{$info["airport"]["state"]}},{{$info["airport"]["country"]}},{{$info["airport"]["latitude"]}},{{$info["airport"]["longitude"]}}">
                    <p>{{$info["airport"]["city"]}}, {{$info["airport"]["state"]}}, {{$info["airport"]["country"]}}</p>
                    <p>{{$info["airport"]["latitude"]}}, {{$info["airport"]["longitude"]}}
                        , {{$info["airport"]["altitude"]}}</p>
                </a>
            @else
                <a href=http://maps.google.com/?q={{$info["airport"]["city"]}}+{{$info["airport"]["state"]}}+{{$info["airport"]["country"]}}+{{$info["airport"]["latitude"]}}+{{$info["airport"]["longitude"]}}">
                    <p>{{$info["airport"]["city"]}}, {{$info["airport"]["state"]}}, {{$info["airport"]["country"]}}</p>
                    <p>{{$info["airport"]["latitude"]}}, {{$info["airport"]["longitude"]}}
                        , {{$info["airport"]["altitude"]}}</p>
                </a>
            @endif
        </div>
    </section>

    <section>
        <div class="section_header">
            <h2>Weather</h2>
            <p>Weather is updated every hour or earlier.</p>
            <p>Provided by AVWX.info.</p>
        </div>
        <div class="section_content" no_grid>
            <div class="custom_table">
                @foreach($info["weather"]["weather_page_ios_simple"] as $weatherelement)
                    <div class="custom_table_row">
                        <div condition="{{$weatherelement[2]}}" class="custom_table_row_left">
                            <p>{{$weatherelement[0]}}</p>
                            <p>{{$weatherelement[1]}}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section>
        <div class="section_header">
            <h2>Runways</h2>
            <p>Available runways.</p>
            <p>Provided by AVWX.info.</p>
        </div>
        <div class="section_content" no_grid>
            <div class="custom_table">
                @foreach($info["runways"] as $runway)
                    <div class="custom_table_row">
                        <div class="custom_table_row_left">
                            <p>{{$runway["idents"]["i1"]}} - {{$runway["idents"]["i2"]}}</p>
                            <p>{{str_replace( "\n", ' | ', $runway["app_string"] )}}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section>
        <div class="section_header">
            <h2>Gates</h2>
            <p>Available gates.</p>
            <p>Provided by Infinite Flight Airport Repo.</p>
        </div>
        <div class="section_content" id="section_gates" no_grid>
            <div class="custom_table">
                @foreach($info["gates"] as $gate)
                    <div class="custom_table_row">
                        <div class="custom_table_row_left">
                            <p>@if($gate["name"] == "") No Name @else {{$gate["name"]}} @endif</p>
                            <p>Size: {{$gate["size"]}}</p>
                            <p>
                                Aircrafts: {{str_replace("[", "", str_replace("]", "", str_replace("\"", "", str_replace(",", ", ", $gate["aircrafts"]))))}}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section>
        <div class="section_header">
            <h2>Charts</h2>
            @if(count($info["charts"]) == 0)
                <p>There are no charts available.</p>
            @else
                <p>All available charts from VirtualFlight and other Providers.</p>
                <p>{{count($info["charts"])}} available</p>
            @endif
        </div>
        <div class="section_content" no_grid>
            <div class="custom_table">
                @foreach($info["charts"] as $chart)
                    <div id="cr_{{$loop->index}}" class="custom_table_row">
                        <div class="custom_table_row_left">
                            <p id="cn_{{$loop->index}}">{{$chart["Name"]}}</p>
                            <p>{{$chart["Provider"]}}</p>
                        </div>
                        <div class="custom_table_row_right">
                            <button url="{{$chart["Url"]}}" class="view_btn">View</button>
                            @if($chart["Provider"] == "VirtualFlight")
                                <button index="{{$loop->index}}" oldname="{{$chart["Name"]}}" originalname="{{$chart["OriginalName"]}}"
                                        class="rename_btn">Rename
                                </button>
                                <button index="{{$loop->index}}" originalname="{{$chart["OriginalName"]}}" class="delete_btn">Delete</button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection

@section('javascript')
    <script>
        $('.rename_btn').click(function () {

            var e = $(this);

            if (e.hasClass("btn_disabled")) {
                return;
            }

            var new_name_promt = prompt("Enter a new name", e.attr("oldname"));
            var new_name = null;

            if (new_name_promt === "" || new_name_promt === null || new_name_promt === e.attr("oldname")) {

                return;
            } else {
                new_name = new_name_promt;
            }

            e.addClass("btn_disabled");

            var data = {
                "_token": "{{ csrf_token() }}",
                "newname": new_name,
                "oldname": e.attr("originalname"),
            };

            $.ajax({
                url: "{{asset("/chart")}}/rename",
                type: "post",
                data: data,
                success: function (response) {
                    if (response === "refresh") {
                        alert("This chart was renamed by someone else while you where renaming it. This page will be refreshed.");
                        location.reload();
                        return
                    }

                    response = JSON.parse(response);

                    e.attr("originalname", response.newname);
                    e.attr("oldname", response.newappname);

                    var div = e.parent();
                    var view_btn = div.find(".view_btn");
                    view_btn.attr("url", response.url);

                    var delete_btn = div.find(".delete_btn");
                    delete_btn.attr("originalname", response.newname);

                    $('#cn_' + e.attr("index")).html(response.newappname).show();

                    e.removeClass("btn_disabled");
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    var error = "Renaming Failed: " + textStatus + " " + errorThrown;

                    if (error === "Renaming Failed: error unknown status") {
                        alert("Your login session has expired during the renaming process. This page will be reloaded and try again.");
                        location.reload();
                    } else {
                        alert(error);
                    }
                }


            });
        });

        $('.delete_btn').click(function () {
            var confirma = confirm("Are you sure? This chart will be deleted permanently!");

            if (!confirma) {
                return;
            }

            var e = $(this);
            var index = e.attr("index");
            var chart = e.attr("originalname");

            var data = {
                "_token": "{{ csrf_token() }}",
                "chart": chart,
            };

            e.addClass("btn_disabled");

            $.ajax({
                url: "{{asset("/chart")}}/delete",
                type: "post",
                data: data,
                success: function (response) {
                    if (response === "refresh") {
                        alert("This chart was removed by someone else while you where removing it. This page will be refreshed.");
                        location.reload();
                        return
                    }

                    $("#cr_" + index).remove();
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    var error = "Removing Failed: " + textStatus + " " + errorThrown;

                    if (error === "Removing Failed: error unknown status") {
                        alert("Your login session has expired during the removing process. This page will be reloaded and try again.");
                        location.reload();
                    } else {
                        alert(error);
                    }
                }


            });
        });

        $('.view_btn').click(function () {
            window.open($(this).attr('url'), '_blank');
        });

    </script>

@endsection

