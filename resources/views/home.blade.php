@extends('layouts.app')

@section('title', 'Home')

@php
    $uptime = shell_exec("uptime");
    $current_date = date('l, F jS, Y | H:i:s');
@endphp

@section('content')
    <section>
        <div class="section_header">
            <h2>Flight plans</h2>
            <p>All flight plans in the database.</p>
        </div>
        <div class="section_content">
            <div class="sub_section">
                <div class="sub_header">
                    <h3>Live Server</h3>
                    <p>Flight plans from Infinite Flight Expert Server.</p>
                </div>
                <div class="sub_content">
                    <ul>
                        <li>Last fetched on 23 May 2019 at 15:45</li>
                        <li>Status: Succeeded</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <section>
        <div class="section_header">
            <h2>Charts</h2>
            <p>All available charts.</p>
        </div>
        <div class="section_content">
            <div class="sub_section">
                <div class="sub_header">
                    <h3>Upload</h3>
                    <p>Upload new charts.</p>
                </div>
                <div class="sub_content">
                    <ul>
                        <li><a href="#">KLAX - Depart...</a></li>
                        <li><a href="#">EHAM - Aerodr...</a></li>
                        <li><a href="#">TNCM - Runway...</a></li>
                        <li><a href="#">More...</a></li>
                    </ul>
                </div>
            </div>

            <div class="sub_section">
                <div class="sub_header">
                    <h3>Manage</h3>
                    <p>Actions for the charts.</p>
                </div>
                <div class="sub_content">
                    <ul>
                        <li><a href="#">Manage charts</a></li>
                        <li><a href="#">Upload new chart</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <section>
        <div class="section_header">
            <h2>API</h2>
            <p>API Users.</p>
        </div>
        <div class="section_content">
            <div class="sub_section">
                <div class="sub_header">
                    <h3>Recent Calls</h3>
                    <p>Recent API calls by users.</p>
                </div>
                <div class="sub_content">
                    <ul>
                        <li>Brandon | Gates | 23/05/2019 at 12:54</li>
                        <li>VirtualHub | Charts | 23/05/2019 at 17:00</li>
                    </ul>
                </div>
            </div>

            <div class="sub_section">
                <div class="sub_header">
                    <h3>Manage</h3>
                    <p>Actions for the charts.</p>
                </div>
                <div class="sub_content">
                    <ul>
                        <li><a href="#">Manage users</a></li>
                        <li><a href="#">New User</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <section>
        <div class="section_header">
            <h2>Other Data</h2>
            <p>Available Datasets.</p>
        </div>
        <div class="section_content">
            <div class="sub_section">
                <div class="sub_header">
                    <h3>Fixes</h3>
                    <a href="#">Update set</a>
                </div>
            </div>

            <div class="sub_section">
                <div class="sub_header">
                    <h3>Aircrafts</h3>
                    <a href="#">Update set</a>
                </div>
            </div>

            <div class="sub_section">
                <div class="sub_header">
                    <h3>Airports</h3>
                    <a href="#">Update set</a>
                </div>
            </div>

            <div class="sub_section">
                <div class="sub_header">
                    <h3>Gates</h3>
                    <a href="#">Update set</a>
                </div>
            </div>
        </div>
    </section>

    <section>
        <div class="section_header">
            <h2>Server Status</h2>
            <p>VirtualFlight Server.</p>
        </div>
        <div class="section_content">
            <div class="sub_section">
                <div class="sub_header">
                    <h3>Uptime</h3>
                    <p>{{$uptime}}</p>
                </div>
            </div>

            <div class="sub_section">
                <div class="sub_header">
                    <h3>Date & Time</h3>
                    <p>{{$current_date}}</p>
                </div>
            </div>

            <div class="sub_section">
                <div class="sub_header">
                    <h3>Local & External IP</h3>
                    <p>{{$_SERVER["SERVER_ADDR"]}} | {{$_SERVER["REMOTE_ADDR"]}}</p>
                </div>
            </div>

            <div class="sub_section">
                <div class="sub_header">
                    <h3>Software</h3>
                    <p>{{$_SERVER["SERVER_SOFTWARE"]}}</p>
                </div>
            </div>
        </div>
    </section>

@endsection
