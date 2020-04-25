@extends('layouts.main_page')
@section('app_section', env('APP_VHW'))
@section('logo', 'vhw')


@section('content')

    <!--suppress ALL -->
    <div class="events_window">
        <h2 class="window_title">Events | Dragos</h2>
        <div class="events_container">
            <div class="events_left">
                <div class="custom_table">
                    @foreach($events as $event)
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

                <div class="gates_map" id='events_map'></div>

            </div>
        </div>
    </div>

@endsection

@section('javascript')

        <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
        <script>


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


                @guest
                table.append("<div class=\"custom_table_row\"><div class=\"custom_table_row_left\"><p>Join</p><p>The event manager has the rights to remove or change your gate at anytime.</p><br><button onclick='login()'>Login To Join</button></div></div>");
                @endguest

                @auth

                var current_user = {{Auth::user()->id}};

                if (json.user_id == current_user){
                    table.append("<div class=\"custom_table_row\"><div class=\"custom_table_row_left\"><p>Remove event</p><a href=\"/api/events/" + json.id + "/remove/\"=>Remove this event</a>");
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

        </script>

@endsection

