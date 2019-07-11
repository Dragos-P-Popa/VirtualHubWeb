@extends('layouts.main_page')
@section('app_section', env('APP_VHW'))
@section('logo', 'vhw')

@section('meta_robot', 'no-index, follow')

{{--{{dd(Auth::user()->role)}}--}}

@section('content')
    <div class="custom_table">
        @foreach($ap as $a)
            <div class="custom_table_row">
                <div class="custom_table_row_left">
                    <a href="{{url("view/" . $a->icao)}}">{{$a->icao}}</a>
                </div>
            </div>
        @endforeach
    </div>


@endsection
