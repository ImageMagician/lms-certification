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
                        <label for="first_name" class="col-md-4 col-form-label text-md-end">{{ __('First Name') }}</label>
                        <div class="col-md-6">
                            <input id="first_name" type="text" class="form-control @error('name') is-invalid @enderror" name="first_name" value="{{ old('first_name') }}" required autocomplete="first_name" autofocus>

                            @error('first_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="last_name" class="col-md-4 col-form-label text-md-end">{{ __('Last Name') }}</label>
                        <div class="col-md-6">
                            <input id="last_name" type="text" class="form-control @error('last_name') is-invalid @enderror" name="last_name" value="{{ old('last_name') }}" required autocomplete="last_name" autofocus>

                            @error('last_name')
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
                            {{ __('Employer') }}
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
                        <label for="address" class="col-md-4 col-form-label text-md-end">
                            {{ __('Address') }}
                        </label>
                        <div class="col-md-6">
                            <input id="address" type="text" class="form-control @error('address') is-invalid @enderror" name="address" value="{{ old('address') }}" required>
                            @error('address')
                            <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="address" class="col-md-4 col-form-label text-md-end">
                            {{ __('City') }}
                        </label>
                        <div class="col-md-6">
                            <input id="city" type="text" class="form-control @error('city') is-invalid @enderror" name="city" value="{{ old('city') }}" required>
                            @error('city')
                            <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="states" class="col-md-4 col-form-label text-md-end">
                            {{ __('State') }}
                        </label>

                        <div class="col-md-6">
                            <select id="state" name="state" class="form-control">
                                <option value="">&hellip;</option>
                                <option value="AL" @if( old('state') == "AL") selected @endif>Alabama</option>
                                <option value="AK" @if( old('state') == "AK") selected @endif>Alaska</option>
                                <option value="AZ" @if( old('state') == "AZ") selected @endif>Arizona</option>
                                <option value="AR" @if( old('state') == "AR") selected @endif>Arkansas</option>
                                <option value="CA" @if( old('state') == "CA") selected @endif>California</option>
                                <option value="CO" @if( old('state') == "CO") selected @endif>Colorado</option>
                                <option value="CT" @if( old('state') == "CT") selected @endif>Connecticut</option>
                                <option value="DE" @if( old('state') == "DE") selected @endif>Delaware</option>
                                <option value="DC" @if( old('state') == "DC") selected @endif>District of Columbia</option>
                                <option value="FL" @if( old('state') == "FL") selected @endif>Florida</option>
                                <option value="GA" @if( old('state') == "GA") selected @endif>Georgia</option>
                                <option value="HI" @if( old('state') == "HI") selected @endif>Hawaii</option>
                                <option value="ID" @if( old('state') == "ID") selected @endif>Idaho</option>
                                <option value="IL" @if( old('state') == "IL") selected @endif>Illinois</option>
                                <option value="IN" @if( old('state') == "IN") selected @endif>Indiana</option>
                                <option value="IA" @if( old('state') == "IA") selected @endif>Iowa</option>
                                <option value="KS" @if( old('state') == "KS") selected @endif>Kansas</option>
                                <option value="KY" @if( old('state') == "KY") selected @endif>Kentucky</option>
                                <option value="LA" @if( old('state') == "LA") selected @endif>Louisiana</option>
                                <option value="ME" @if( old('state') == "ME") selected @endif>Maine</option>
                                <option value="MD" @if( old('state') == "MD") selected @endif>Maryland</option>
                                <option value="MA" @if( old('state') == "MA") selected @endif>Massachusetts</option>
                                <option value="MI" @if( old('state') == "MI") selected @endif>Michigan</option>
                                <option value="MN" @if( old('state') == "MN") selected @endif>Minnesota</option>
                                <option value="MS" @if( old('state') == "MS") selected @endif>Mississippi</option>
                                <option value="MO" @if( old('state') == "MO") selected @endif>Missouri</option>
                                <option value="MT" @if( old('state') == "MT") selected @endif>Montana</option>
                                <option value="NE" @if( old('state') == "NE") selected @endif>Nebraska</option>
                                <option value="NV" @if( old('state') == "NV") selected @endif>Nevada</option>
                                <option value="NH" @if( old('state') == "NH") selected @endif>New Hampshire</option>
                                <option value="NJ" @if( old('state') == "NJ") selected @endif>New Jersey</option>
                                <option value="NM" @if( old('state') == "NM") selected @endif>New Mexico</option>
                                <option value="NY" @if( old('state') == "NY") selected @endif>New York</option>
                                <option value="NC" @if( old('state') == "NC") selected @endif>North Carolina</option>
                                <option value="ND" @if( old('state') == "ND") selected @endif>North Dakota</option>
                                <option value="OH" @if( old('state') == "OH") selected @endif>Ohio</option>
                                <option value="OK" @if( old('state') == "OK") selected @endif>Oklahoma</option>
                                <option value="OR" @if( old('state') == "OR") selected @endif>Oregon</option>
                                <option value="PA" @if( old('state') == "PA") selected @endif>Pennsylvania</option>
                                <option value="PR" @if( old('state') == "PR") selected @endif>Puerto Rico</option>
                                <option value="RI" @if( old('state') == "RI") selected @endif>Rhode Island</option>
                                <option value="SC" @if( old('state') == "SC") selected @endif>South Carolina</option>
                                <option value="SD" @if( old('state') == "SD") selected @endif>South Dakota</option>
                                <option value="TN" @if( old('state') == "TN") selected @endif>Tennessee</option>
                                <option value="TX" @if( old('state') == "TX") selected @endif>Texas</option>
                                <option value="UT" @if( old('state') == "UT") selected @endif>Utah</option>
                                <option value="VT" @if( old('state') == "VT") selected @endif>Vermont</option>
                                <option value="VA" @if( old('state') == "VA") selected @endif>Virginia</option>
                                <option value="WA" @if( old('state') == "WA") selected @endif>Washington</option>
                                <option value="WV" @if( old('state') == "WV") selected @endif>West Virginia</option>
                                <option value="WY" @if( old('state') == "WY") selected @endif>Wyoming</option>
                            </select>

                            @error('states')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="zip" class="col-md-4 col-form-label text-md-end">
                            {{ __('Zip code') }}
                        </label>
                        <div class="col-md-6">
                            <input id="zip" type="text" class="form-control @error('zip') is-invalid @enderror" name="zip" value="{{ old('zip') }}" required>
                            @error('zip')
                            <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>

                        <div class="col-md-6">
                            <div class="input-group input-group-merge form-password-toggle @error('password') is-invalid @enderror">
                                <input id="password" type="password" class="form-control form-control-merge" name="password" required autocomplete="current-password" />
                                <span onclick="showPassword(this)" class="input-group-text cursor-pointer" tabindex="3"><i id="eye_icon" class="fa fa-eye"></i></span>
                            </div>
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
                            <div class="input-group input-group-merge form-password-toggle">
                                <input id="password_confirmation" type="password" class="form-control @error('password') is-invalid @enderror form-control-merge" name="password_confirmation" required autocomplete="current-password" />
                                <span onclick="showPassword(this)" class="input-group-text cursor-pointer" tabindex="3"><i id="eye_icon" class="fa fa-eye"></i></span>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-0">
                        <div class="col-md-6 offset-md-4">
                            <input type="hidden" name="ref" value="{{session('ref')}}">
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
