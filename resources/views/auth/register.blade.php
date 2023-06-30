@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 py-5">
            <div class="yyz-card p-3">
                <h2 class="text-primary">{{ __('Register') }}</h2>
                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="row mb-3">
                        <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('Name') }}</label>

                        <div class="col-md-6">
                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                        <div class="col-md-6">
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="phone" class="col-md-4 col-form-label text-md-end">{{ __('Phone') }}</label>

                        <div class="col-md-6">
                            <input id="phone" type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}" required>

                            @error('phone')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="companies" class="col-md-4 col-form-label text-md-end">
                            {{ __('Associated Company') }}
                            <i id="companies_question" class="fa fa-question-circle ms-1 position-relative" onmouseover="showCos()" onmouseout="hideCos()">
                                <div id="companies_explainer" class="explainer yyz-card p-2 position-absolute">
                                    List the company for whom you install solar products.
                                </div>
                            </i>
                        </label>

                        <div class="col-md-6">
                            <input id="companies" type="text" class="form-control @error('companies') is-invalid @enderror" name="companies" value="{{ old('companies') }}" required>

                            @error('companies')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>

                        <div class="col-md-6">
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="password-confirm" class="col-md-4 col-form-label text-md-end">{{ __('Confirm Password') }}</label>

                        <div class="col-md-6">
                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                        </div>
                    </div>

                    <div class="row mb-0">
                        <div class="col-md-6 offset-md-4">
                            <button type="submit" class="btn btn-primary">
                                {{ __('Register') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css">
    <style>
        .explainer {
            position:absolute;
            top:50%;
            left:50%;
            font-family:Nunito;
            font-weight:400;
            text-align:left;
            width:300px;
            min-height:0;
            display:none;
            line-height:1.5;
            z-index:1;
        }

        .explainer.show {
            display:block;
        }
    </style>
    <script>
        function showStates() {
            const states = document.getElementById('states_explainer');
            states.classList.add('show');
        }

        function hideStates() {
            const states = document.getElementById('states_explainer');
            states.classList.remove('show');
        }

        function showCos() {
            const companies = document.getElementById('companies_explainer');
            companies.classList.add('show');
        }

        function hideCos() {
            const companies = document.getElementById('companies_explainer');
            companies.classList.remove('show');
        }
    </script>
@endsection
