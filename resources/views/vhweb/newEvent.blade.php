@extends('layouts.main_page')

@section('title', $info["icao"] .' | New Event')
@section('app_section', env('APP_VHW'))
@section('logo', 'vhw')

@section('content')
    <div class="page_title">
        <h1>New Event</h1>
        <p lastupdate>{{$info["name"]}}</p>
    </div>


    <form action="" method="post">
        @csrf
        <h2>Event Details</h2>

        <div class="flex_fields">
            <div>
                <label for="event_name">Name</label>
                <input type="text" name="event_name" id="event_name" required placeholder="100 Years KLM">
            </div>

            <div>
                <label for="event_description">Description</label>
                <input type="text" name="event_description" id="event_description" required placeholder="What is this event about?">
            </div>

            <div>
                <label for="event_date_time_start">Starts At</label>
                <input type="text" name="event_date_time_start" id="event_date_time_start" required placeholder="">
            </div>

            <div>
                <label for="event_date_time_end">Ends At</label>
                <input type="text" name="event_date_time_end" id="event_date_time_end" required placeholder="">
            </div>

            <div>
                <label for="event_route">Route</label>
                <textarea type="text" name="event_route" id="event_route" required placeholder="KLAX WAYPOINT1 WAYPOINT2 WAYPOINT3 EHAM"></textarea>
            </div>
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
            $new_section_div = $('<div><div> <h2>Section ' + custom_sections + '</h2> <label for="event_custom_section_' + custom_sections + '_name">Title</label> <input type="text" name="event_custom_section_' + custom_sections + '_name" id="event_custom_section_' + custom_sections + '0_name" required placeholder="Section ' + custom_sections + '"> </div> <div> <label for="event_custom_section_' + custom_sections + '_content">Content</label> <textarea type="text" name="event_custom_section_' + custom_sections + '_content" id="event_custom_section_' + custom_sections + '_content" required placeholder="Examples: Required aircraft(s) or airline(s), contact information, notams, etc."></textarea> </div></div>');

            $('.form_seperator').css('display', 'block');

            $custom_section_container.append($new_section_div);

            custom_sections++;
        })
    </script>
@endsection
