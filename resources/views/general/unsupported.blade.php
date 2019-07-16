@extends('layouts.main_page')
@section('title', 'Unsupported')
@section('app_section', 'VirtualHub')
@section('logo', 'vhw')


@php


@endphp

@section('content')
    <div class="unsupported">
        <div class="info">
            <h1>Unsupported Browser</h1>
            <p>Please use one of these options to improve your experience.</p>
        </div>

        <div class="blocks_3">
            <div class="block_content">
                <div class="block">
                    <i class="fab fa-chrome"></i>
                    <div>
                        <h3>Chrome</h3>
                        <p><a href="https://www.google.com/chrome/" target="_blank">google.com/chrome</a></p>
                    </div>
                </div>

                <div class="block">
                    <i class="fab fa-safari"></i>
                    <div>
                        <h3>Safari</h3>
                        <p><a href="https://www.apple.com/safari/" target="_blank">apple.com/safari</a></p>
                    </div>
                </div>

                <div class="block">
                    <i class="fab fa-firefox"></i>
                    <div>
                        <h3>Firefox</h3>
                        <p><a href="https://www.mozilla.org/firefox/new/" target="_blank">mozilla.org/firefox</a></p>
                    </div>
                </div>

                <div class="block">
                    <i class="fab fa-edge"></i>
                    <div>
                        <h3>Microsoft Edge 13</h3>
                        <p><a href="https://www.microsoft.com/microsoft-edge" target="_blank">microsoft.com/microsoft-edge</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection