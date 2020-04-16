@extends('layouts.general')
@section('title', 'Terms & Conditions')
@section('app_section', 'VirtualHub')
@section('logo', 'vhw')


@section('content')
    <div>
        <h1>My account</h1>
        <p lastupdate>{{date( 'l, F jS, Y | H:i' )}}</p>
    </div>

    <br>
    <br>

    <div class="gradient_section" shadow>
        <div class="gradient_part">
            <div>
                <h1 must_be_white>My events</h1>
            </div>
        </div>

        <div>
            <div class="custom_table">
                <div class="custom_table_row">
                    <div class="custom_table_row_left">
                        @if($firstEvent == 'Null')
                            <p>No Events</p>
                            <p>You have no current event. Set some up!</p>
                        @else
                        <p>Next event</p>

                        <br>
                        <h2>{{$firstEvent['title']}}</h2>
                        <p>{{$firstEvent['start']}}</p>
                        <p>{{$firstEvent['description']}}</p>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>

    <br>
    <br>

    <div class="gradient_section" shadow>
        <div class="gradient_part">
            <div>
                <h1 must_be_white>Attending Events</h1>
            </div>
        </div>

        <div>
            <div class="custom_table">
                <div class="custom_table_row">
                    <div class="custom_table_row_left">
                        @if($eventsAttending == 'Null')
                            <p>No Events</p>
                            <p>You are not attending any events.</p>
                        @else
                            <p>Next event</p>

                            <br>
                            <h2>{{get_object_vars($eventsAttending[0])['title']}}</h2>
                            <p>{{get_object_vars($eventsAttending[0])['start']}}</p>
                            <p>{{get_object_vars($eventsAttending[0])['description']}}</p>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>

    @php

    @endphp
@endsection


