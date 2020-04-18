@extends('layouts.main_page')
@section('app_section', env('APP_VHW'))
@section('title', 'Login')

@section('content')
    <div class="gradient_section" shadow>
        <div class="gradient_part">
            <div>
                <h1 must_be_white>Welcome Back, </h1>
                <p must_be_white>Login in with your Phenolix Account.</p>
            </div>
        </div>

        <div>
            <div>
                <h2>Login</h2>
                <br>
                @error('email')
                <p class="form_error">{{ $message }}</p>
                <br>
                @enderror
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div>
                        <label for="email">Email</label>
                        <div>
                            <input id="email" type="email" name="email"
                                   value="{{ old('email') }}" placeholder="example@domain.com" required autocomplete="email" autofocus>
                        </div>
                    </div>
                    <br>

                    <div>
                        <label for="password">Password</label>

                        <div>
                            <input id="password" type="password" name="password"
                                   required placeholder="12!3?4#5678" autocomplete="current-password">
                        </div>
                    </div>

                    <div>
                        <label class="form-check-label" hidden for="remember">Remeber Me &nbsp; <input hidden
                                                                                                       class="form-check-input"
                                                                                                       type="checkbox"
                                                                                                       name="remember"
                                                                                                       id="remember" {{ old('remember') ? 'checked' : '' }}></label>
                    </div>
                    <br>

                    <div>
                        <div>
                            <button type="submit" class="btn btn-primary">{{ __('Login') }}</button>
                            @if (Route::has('password.request'))
                                <a hidden class="btn btn-link" href="{{ route('password.request') }}">
                                    {{ __('Forgot Your Password?') }}
                                </a>
                            @endif
                            <br>
                            <p>Don't have an account? <a href="{{route('register')}}">Register here!</a></p>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
