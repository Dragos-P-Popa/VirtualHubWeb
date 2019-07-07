@extends('layouts.general.general')
@section('app_section', 'Dashboard')

@section('content')
    <div>
        <h1>Dashboard</h1>
        <p lastupdate>{{$current_date}}</p>
    </div>

    <br>
    <br>

    <div class="gradient_section" shadow>
        <div class="gradient_part">
            <div>
                <h1 must_be_white>Server Status</h1>
            </div>
        </div>

        <div>
            <div class="custom_table">
                <div class="custom_table_row">
                    <div class="custom_table_row_left">
                        <p>Local & External IP</p>
                        <p>{{$_SERVER["SERVER_ADDR"]}} & {{$_SERVER["REMOTE_ADDR"]}}</p>
                    </div>
                </div>

                <div class="custom_table_row">
                    <div class="custom_table_row_left">
                        <p>Temperature</p>
                        <p>{{$temp}}</p>
                    </div>
                </div>

                <div class="custom_table_row">
                    <div class="custom_table_row_left">
                        <p>Uptime</p>
                        <p>{{$uptime}}</p>
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
                <h1 must_be_white>Users</h1>
                <p must_be_white>Most recent registered users.</p>
                <a must_be_white href="{{url("dashboard/user/all")}}">View all users</a>
            </div>
        </div>

        <div>
            <div class="custom_table">
                @foreach($newUsers as $user)
                    <div class="custom_table_row">
                        <div class="custom_table_row_left">
                            <p>{{$user->name}}</p>
                            <p>{{$user->created}}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <br>
    <br>

    <div class="gradient_section" shadow>
        <div class="gradient_part">
            <div>
                <h1 must_be_white>Data Set</h1>
                <p must_be_white>From Infinite Flight Airport Editing.</p>
                <a must_be_white href="{{url("dashboard/updatedata")}}">Update Data</a>
            </div>
        </div>

        <div>
            <div class="custom_table">
                <div class="custom_table_row">
                    <div class="custom_table_row_left">
                        <p>Gates</p>
                        <p>126.000</p>
                    </div>
                </div>

                <div class="custom_table_row">
                    <div class="custom_table_row_left">
                        <p>Airports</p>
                        <p>28.825</p>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('javascript')


@endsection