@extends('layouts.general.general')
@section('app_section', 'Dashboard')


@section('content')
    <div>
        <h1>All users</h1>
        <p lastupdate>{{$current_date}}</p>
    </div>

    <br>
    <br>

    <div class="table_div">
        <table>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Registered</th>
                <th>Role</th>
                <th>Blockage</th>
            </tr>

            @foreach($users as $user)
                <tr>
                    <td>{{$user->name}}</td>
                    <td>{{$user->email}}</td>
                    <td>{{$user->created_at}}</td>
                    <td>{{$user->role}}</td>

                    <td>
                        @if($user->blocked == false)
                            <a href="{{url("dashboard/user/" . $user->id . "/block")}}">Block</a>
                        @else
                            <a href="{{url("dashboard/user/" . $user->id . "/unblock")}}">Unblock</a>
                        @endif
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
@endsection

@section('javascript')

@endsection