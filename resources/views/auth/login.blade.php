@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-4 my-5">
            <div class="yyz-card p-3">
                <h2 class="text-primary">{{ __('Login') }}</h2>
                <form method="POST" action="{{ route('login') }}">
                    <div class="row my-3">
                        <div class="col-12">
                            <label for="email" class="no-style pt-0 pe-0 pb-1 ps-0">{{ __('Email Address') }}</label>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus tabindex="1">
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row my-3">
                        <div class="col-12">
                            <div class="d-flex justify-content-between">
                                <label for="password" class="no-style pt-0 pe-0 pb-1 ps-0">
                                    {{ __('Password') }}
                                </label>
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" tabindex="5">
                                        <small>{{ __('Forgot Your Password?') }}</small>
                                    </a>
                                @endif
                            </div>
                            <div class="input-group input-group-merge form-password-toggle">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror form-control-merge" name="password" required autocomplete="current-password" tabindex="2" />
                                <span id="password_eye" onclick="showPassword(this)" class="input-group-text cursor-pointer" tabindex="3"><i id="eye_icon" class="fa fa-eye"></i></span>
                            </div>
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row my-3">
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }} tabindex="3">
                                <label class="form-check-label no-style p-0" for="remember">
                                    {{ __('Remember Me') }}
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3 mb-0">
                        <div class="col-12">
                        @csrf
                            <button type="submit" class="btn btn-primary w-100" tabindex="4">
                                {{ __('Login') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="mt-5 text-center">
                Not registered yet? <a href="{{ route('register') }}">Click here</a>.
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css">
@endsection
