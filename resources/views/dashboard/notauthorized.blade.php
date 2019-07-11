@extends('layouts.general')
@section('app_section', 'Dashboard')

@section('content')
    <div>
        <h1><i class="fas fa-ban"></i>&nbsp;Restricted Area</h1>
        <p>Authorized Personnel Only</p>
        <br>
        <p>You will be redirected automatically to our homepage after 5 seconds, or click <a
                    href="{{url("")}}">here</a>.</p>
    </div>

@endsection

@section('javascript')
    <script>
        setTimeout(function () {
            window.location.href = '{{url("/")}}';
        }, 5000);
    </script>
@endsection