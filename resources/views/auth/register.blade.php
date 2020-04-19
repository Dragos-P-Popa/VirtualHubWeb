@extends('layouts.main_page')
@section('app_section', env('APP_VHW'))
@section('title', 'Register')


@section('content')
    <div class="gradient_section" shadow>
        <div class="gradient_part">
            <div>
                <h1 must_be_white>Phenolix Account</h1>
                <p must_be_white>With a Phenolix Account you can access features from all of our services that requires an account!</p>
            </div>
        </div>

        <div>
            <div>
                <h2>Register</h2>
                <br>
                @error('name')
                <p class="form_error">{{ $message }}</p>
                <br>
                @enderror
                @error('email')
                <p class="form_error">{{ $message }}</p>
                <br>
                @enderror
                @error('password')
                <p class="form_error">{{ $message }}</p>
                <br>
                @enderror
                @error('password_confirmation')
                <p class="form_error">{{ $message }}</p>
                <br>
                @enderror
                @error('ifc_name')
                <p class="form_error">{{ $message }}</p>
                <br>
                @enderror
                @error('g-recaptcha-response')
                <p class="form_error">The captcha needs to be completed</p>
                <br>
                @enderror
                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div>
                        <label for="name">Name</label>
                        <div>
                            <input id="name" type="text" name="name"
                                   value="{{ old('name') }}" placeholder="Max Rackley" required autofocus>
                        </div>
                    </div>
                    <br>

                    <div>
                        <label for="email">Email</label>
                        <div>
                            <input id="email" type="email" name="email"
                                   value="{{ old('email') }}" placeholder="example@domain.com" required autocomplete="email"
                                   autofocus>
                        </div>
                    </div>
                    <br>

                    <div>
                        <label for="password">Password</label>

                        <div>
                            <input id="password" type="password" name="password"
                                   required placeholder="●●●●●●●●" autocomplete="current-password">
                        </div>
                    </div>
                    <br>
                    <div>
                        <label for="password_confirmation">Confirm Password</label>

                        <div>
                            <input id="password_confirmation" type="password" name="password_confirmation"
                                   required placeholder="●●●●●●●●" autocomplete="current-password">
                        </div>
                    </div>
                    <br>

                    <div>
                        <label for="captcha">Captcha</label>

                        {!! NoCaptcha::renderJs() !!}
                        {!! NoCaptcha::display() !!}
                    </div>
                    <br>

                    <div>
                        <div>
                            <p>By creating an account, you agree with our <a href="{{url("terms")}}">Terms and Conditions</a>.</p>
                            <br>
                            <button type="submit" class="btn btn-primary">Register</button>
                            <br>
                            <p>Already have an account? <a href="{{route('login')}}">Login!</a></p>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
