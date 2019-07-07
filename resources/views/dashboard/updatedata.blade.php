@extends('layouts.general.general')
@section('app_section', 'Dashboard')


@section('content')
    <div>
        <h1>Update Data Sets</h1>
        <p lastupdate>{{$current_date}}</p>
    </div>

    <br>
    <br>

    <div>
        <p>Gates, Runways and ATC will be updated in the database. Changes will be visible immediately. During the
            update, some services may fail to work for a few seconds.</p>
        <br>
        <h2>Instructions:</h2>
        <ol>
            <li>Download copy of <a href="https://github.com/InfiniteFlightAirportEditing/Airports">this respository</a>
                (zip).
            </li>
            <li>Unzip the and place the directory in resources/python/.</li>
            <li>Run dataaupdater.py.</li>
            <li>import .sql files here <a
                        href="https://vh-net.com/phpmyadmin">VirtualFlight Database</a>.
            </li>
            <li>The .geojson file must be imported in MapBox Gates Dataset.</li>
            <li>Remove all the 4 generated files and the Airport-Development folder for storage saving.</li>
            <li>Done!</li>
        </ol>
    </div>
@endsection

@section('javascript')

@endsection