@extends('layouts.app')
@section('title', 'Charts | ' . $icao)

@php
    use Jenssegers\Agent\Agent;

    $agent = new Agent();
    $os = $agent->platform();
@endphp

@section('content')
    <section>
        <div class="section_header">
            <h2>{{$icao}} | {{$name}}</h2>
            <p>You can rename the chart here, or permanently delete it.</p>
        </div>
        <div class="section_content" no_grid>
            <div class="custom_table">
                <div class="custom_table_row">
                    <div class="custom_table_row_left" style="width: 100%">
                        <input id="new_name" type="text" placeholder="{{$name}}">
                    </div>
                    <div class="custom_table_row_right">
                        <button id="rename_btn">Rename</button>
                        <button id="delete_btn">Delete</button>
                        <a href="{{asset("/chart")}}/rename/{{$name}}">Rename</a>
                        <a href="{{asset("/chart")}}/delete/{{$name}}">Delete</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('javascript')
    <script>
        $('#rename_btn').click(function () {
            var new_name = $('#new_name').val();
            if (confirm('Are you sure you want to rename this chart from {{$name}} to ' + new_name + '.')) {

            } else {

            }
        });
    </script>

@endsection

