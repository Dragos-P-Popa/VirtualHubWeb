@extends('layouts.main_page')

@section('title', $info["icao"] .' | New Event')
@section('app_section', env('APP_VHW'))
@section('logo', 'vhw')

@section('content')
    <div class="page_title">
        <h1>New Event</h1>
        <p lastupdate>{{$info["name"]}}</p>
    </div>


    <form class="events_form" action="{{url("api/events/new")}}" method="post">
        @csrf
        <h2>Event Details</h2>

        <div class="flex_fields">
            <div>
                <label for="event_name">Name</label>
                <input type="text" name="event_name" id="event_name" required placeholder="100 Years KLM">
            </div>

            <div>
                <label for="event_description">Description</label>
                <textarea type="text" name="event_description" id="event_description" required placeholder="What is this event about?"></textarea>
            </div>

            <div>
                <label for="event_date_time_start">Starts At (UTC)</label>
                <input type="text" name="event_date_time_start" id="event_date_time_start" required placeholder="">
            </div>

            <div>
                <label for="event_date_time_end">Ends At (UTC)</label>
                <input type="text" name="event_date_time_end" id="event_date_time_end" required placeholder="">
            </div>

            <div>
                <label for="event_route">Route</label>
                <textarea type="text" name="event_route" id="event_route" required placeholder="KLAX WAYPOINT1 WAYPOINT2 WAYPOINT3 EHAM"></textarea>
            </div>

            <input hidden type="text" name="event_sections" id="sections_json">
            <input hidden type="text" name="event_airport" id="" value="{{$info["icao"]}}">
        </div>

        <div class="form_seperator" style="display: none">
            <br>
            <hr>
            <br>
        </div>

        <div class="flex_costum_section">

        </div>

        <div class="flex_buttons">
            <button class="add_event_section">Add section</button>
            <input type="submit" value="Submit">
        </div>
    </form>


    
@endsection

@php
$today = date("Y-m-d");

$future60 = date('Y-m-d', strtotime($today. ' + 60 days'));

@endphp

@section('javascript')
    <script src="{{url('resources/js/date-time-picker.min.js')}}"></script>
    <script>
        var custom_sections = 1;

        $('#event_date_time_start').dateTimePicker({
            limitMin: '{{$today}} 00:00:00',
            limitMax: '{{$future60}} 00:00:00',
            mode: 'dateTime',
        });

        $('#event_date_time_end').dateTimePicker({
            limitMin: '{{$today}} 00:00:00',
            limitMax: '{{$future60}} 00:00:00',
            mode: 'dateTime',
        });

        $('.add_event_section').click(function () {
            $custom_section_container = $('.flex_costum_section');
            $new_section_div = $('<div class="event_section"><div> <h2>Section ' + custom_sections + '</h2> <label for="event_custom_section_' + custom_sections + '_name">Title</label> <input type="text" name="event_custom_section_' + custom_sections + '_name" id="event_custom_section_' + custom_sections + '0_name" required placeholder="Section ' + custom_sections + '"> </div> <div> <label for="event_custom_section_' + custom_sections + '_content">Content</label> <textarea type="text" name="event_custom_section_' + custom_sections + '_content" id="event_custom_section_' + custom_sections + '_content" required placeholder="Examples: Required aircraft(s) or airline(s), contact information, notams, etc."></textarea> </div></div>');

            $('.form_seperator').css('display', 'block');

            $custom_section_container.append($new_section_div);

            custom_sections++;
        });

        $('.events_form').submit(function(event) {

            var event_sections = $('.event_section');
            var sections_json = [];

            event_sections.each(function( index ) {
                var section = $( this );

                var title = "";
                var content = "";

                section.find("div").each(function( index ) {
                    var div = $( this );

                    if (index === 0 ) {
                        title = div.find("input").val();
                    }

                    if (index === 1 ) {
                        content = div.find("textarea").val();
                    }


                });

                var Base64={_keyStr:"ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",encode:function(e){var t="";var n,r,i,s,o,u,a;var f=0;e=Base64._utf8_encode(e);while(f<e.length){n=e.charCodeAt(f++);r=e.charCodeAt(f++);i=e.charCodeAt(f++);s=n>>2;o=(n&3)<<4|r>>4;u=(r&15)<<2|i>>6;a=i&63;if(isNaN(r)){u=a=64}else if(isNaN(i)){a=64}t=t+this._keyStr.charAt(s)+this._keyStr.charAt(o)+this._keyStr.charAt(u)+this._keyStr.charAt(a)}return t},decode:function(e){var t="";var n,r,i;var s,o,u,a;var f=0;e=e.replace(/[^A-Za-z0-9\+\/\=]/g,"");while(f<e.length){s=this._keyStr.indexOf(e.charAt(f++));o=this._keyStr.indexOf(e.charAt(f++));u=this._keyStr.indexOf(e.charAt(f++));a=this._keyStr.indexOf(e.charAt(f++));n=s<<2|o>>4;r=(o&15)<<4|u>>2;i=(u&3)<<6|a;t=t+String.fromCharCode(n);if(u!=64){t=t+String.fromCharCode(r)}if(a!=64){t=t+String.fromCharCode(i)}}t=Base64._utf8_decode(t);return t},_utf8_encode:function(e){e=e.replace(/\r\n/g,"\n");var t="";for(var n=0;n<e.length;n++){var r=e.charCodeAt(n);if(r<128){t+=String.fromCharCode(r)}else if(r>127&&r<2048){t+=String.fromCharCode(r>>6|192);t+=String.fromCharCode(r&63|128)}else{t+=String.fromCharCode(r>>12|224);t+=String.fromCharCode(r>>6&63|128);t+=String.fromCharCode(r&63|128)}}return t},_utf8_decode:function(e){var t="";var n=0;var r=c1=c2=0;while(n<e.length){r=e.charCodeAt(n);if(r<128){t+=String.fromCharCode(r);n++}else if(r>191&&r<224){c2=e.charCodeAt(n+1);t+=String.fromCharCode((r&31)<<6|c2&63);n+=2}else{c2=e.charCodeAt(n+1);c3=e.charCodeAt(n+2);t+=String.fromCharCode((r&15)<<12|(c2&63)<<6|c3&63);n+=3}}return t}}

                sections_json.push({
                    "title": title,
                    "content":  Base64.encode(content)
                })

            });

            sections_json = JSON.stringify(sections_json);
            $('#sections_json').val(sections_json);
        });
    </script>
@endsection
