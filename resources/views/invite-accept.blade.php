@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 py-5">
            <div class="yyz-card p-3">
                <h2 class="text-primary">{{ __('Finish Registration') }}</h2>
                @if( Session::get('error'))
                    <div class="alert alert-info">
                        <p class="mb-0">We are sorry, but the invitation credentials do not mach our records. Contact us at <a href="mailto:ess.support@lionenergy.com">ess.support@lionenergy.com</a> for further assistance.</p>
                    </div>
                @else
                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="row mb-3">
                        <label for="first_name" class="col-md-4 col-form-label text-md-end">{{ __('First Name') }}</label>
                        <div class="col-md-6">
                            <input id="first_name"
                                   type="text"
                                   class="form-control @error('name') is-invalid @enderror"
                                   name="first_name"
                                   value="{{ $user->first_name }}"
                                   required
                                   autocomplete="first_name"
                                   autofocus
                                   readonly
                            >

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
                            <input id="last_name"
                                   type="text"
                                   class="form-control @error('last_name') is-invalid @enderror"
                                   name="last_name"
                                   value="{{ $user->last_name }}"
                                   required
                                   autocomplete="last_name"
                                   autofocus
                                   readonly
                            >

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
                            <input id="email"
                                   type="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   name="email"
                                   value="{{ $user->email }}"
                                   required
                                   autocomplete="email"
                                   readonly
                            >

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
                            <input id="phone" type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}" required tabindex="1">

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
                            <input id="companies" type="text" class="form-control @error('companies') is-invalid @enderror" name="companies" value="{{ old('companies') }}" required tabindex="2">

                            @error('companies')
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
                            <select id="states" name="states" class="form-control" tabindex="3">
                                <option value="">&hellip;</option>
                                <option value="AL" @if( old('states') == "AL") selected @endif>Alabama</option>
                                <option value="AK" @if( old('states') == "AK") selected @endif>Alaska</option>
                                <option value="AZ" @if( old('states') == "AZ") selected @endif>Arizona</option>
                                <option value="AR" @if( old('states') == "AR") selected @endif>Arkansas</option>
                                <option value="CA" @if( old('states') == "CA") selected @endif>California</option>
                                <option value="CO" @if( old('states') == "CO") selected @endif>Colorado</option>
                                <option value="CT" @if( old('states') == "CT") selected @endif>Connecticut</option>
                                <option value="DE" @if( old('states') == "DE") selected @endif>Delaware</option>
                                <option value="DC" @if( old('states') == "DC") selected @endif>District of Columbia</option>
                                <option value="FL" @if( old('states') == "FL") selected @endif>Florida</option>
                                <option value="GA" @if( old('states') == "GA") selected @endif>Georgia</option>
                                <option value="HI" @if( old('states') == "HI") selected @endif>Hawaii</option>
                                <option value="ID" @if( old('states') == "ID") selected @endif>Idaho</option>
                                <option value="IL" @if( old('states') == "IL") selected @endif>Illinois</option>
                                <option value="IN" @if( old('states') == "IN") selected @endif>Indiana</option>
                                <option value="IA" @if( old('states') == "IA") selected @endif>Iowa</option>
                                <option value="KS" @if( old('states') == "KS") selected @endif>Kansas</option>
                                <option value="KY" @if( old('states') == "KY") selected @endif>Kentucky</option>
                                <option value="LA" @if( old('states') == "LA") selected @endif>Louisiana</option>
                                <option value="ME" @if( old('states') == "ME") selected @endif>Maine</option>
                                <option value="MD" @if( old('states') == "MD") selected @endif>Maryland</option>
                                <option value="MA" @if( old('states') == "MA") selected @endif>Massachusetts</option>
                                <option value="MI" @if( old('states') == "MI") selected @endif>Michigan</option>
                                <option value="MN" @if( old('states') == "MN") selected @endif>Minnesota</option>
                                <option value="MS" @if( old('states') == "MS") selected @endif>Mississippi</option>
                                <option value="MO" @if( old('states') == "MO") selected @endif>Missouri</option>
                                <option value="MT" @if( old('states') == "MT") selected @endif>Montana</option>
                                <option value="NE" @if( old('states') == "NE") selected @endif>Nebraska</option>
                                <option value="NV" @if( old('states') == "NV") selected @endif>Nevada</option>
                                <option value="NH" @if( old('states') == "NH") selected @endif>New Hampshire</option>
                                <option value="NJ" @if( old('states') == "NJ") selected @endif>New Jersey</option>
                                <option value="NM" @if( old('states') == "NM") selected @endif>New Mexico</option>
                                <option value="NY" @if( old('states') == "NY") selected @endif>New York</option>
                                <option value="NC" @if( old('states') == "NC") selected @endif>North Carolina</option>
                                <option value="ND" @if( old('states') == "ND") selected @endif>North Dakota</option>
                                <option value="OH" @if( old('states') == "OH") selected @endif>Ohio</option>
                                <option value="OK" @if( old('states') == "OK") selected @endif>Oklahoma</option>
                                <option value="OR" @if( old('states') == "OR") selected @endif>Oregon</option>
                                <option value="PA" @if( old('states') == "PA") selected @endif>Pennsylvania</option>
                                <option value="PR" @if( old('states') == "PR") selected @endif>Puerto Rico</option>
                                <option value="RI" @if( old('states') == "RI") selected @endif>Rhode Island</option>
                                <option value="SC" @if( old('states') == "SC") selected @endif>South Carolina</option>
                                <option value="SD" @if( old('states') == "SD") selected @endif>South Dakota</option>
                                <option value="TN" @if( old('states') == "TN") selected @endif>Tennessee</option>
                                <option value="TX" @if( old('states') == "TX") selected @endif>Texas</option>
                                <option value="UT" @if( old('states') == "UT") selected @endif>Utah</option>
                                <option value="VT" @if( old('states') == "VT") selected @endif>Vermont</option>
                                <option value="VA" @if( old('states') == "VA") selected @endif>Virginia</option>
                                <option value="WA" @if( old('states') == "WA") selected @endif>Washington</option>
                                <option value="WV" @if( old('states') == "WV") selected @endif>West Virginia</option>
                                <option value="WY" @if( old('states') == "WY") selected @endif>Wyoming</option>
                            </select>

                            @error('states')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>

                        <div class="col-md-6">
                            <div class="input-group input-group-merge form-password-toggle">
                                <input id="password"
                                       type="password"
                                       class="form-control @error('password') is-invalid @enderror"
                                       name="password"
                                       required
                                       autocomplete="new-password"
                                       tabindex="4"
                                >
                                <span id="password_confirmation_eye"
                                      onclick="showPassword(this)"
                                      class="@error('password_confirmation') is-invalid @enderror input-group-text cursor-pointer"
                                      tabindex="5"
                                ><i class="fa fa-eye"></i></span>
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="password_confirmation" class="col-md-4 col-form-label text-md-end">{{ __('Confirm Password') }}</label>

                        <div class="col-md-6">
                            <div class="input-group input-group-merge form-password-toggle">
                                <input type="password"
                                       class="form-control form-control-merge"
                                       id="password_confirmation"
                                       name="password_confirmation"
                                       tabindex="6"
                                />
                                <span id="password_confirmation_eye"
                                      onclick="showPassword(this)"
                                      class="@error('password_confirmation') is-invalid @enderror input-group-text cursor-pointer"
                                      tabindex="7"
                                ><i id="eye_icon" class="fa fa-eye"></i></span>
                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row mb-0">
                        <div class="col-md-6 offset-md-4">
                            <input type="hidden" name="ref" value="{{session('ref')}}">
                            <input type="hidden" name="admin" value="{{ $admin }}">
                            <button type="submit" class="btn btn-primary" tabindex="8">
                                {{ __('Register') }}
                            </button>
                        </div>
                    </div>
                </form>
                @endif
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
        setTimeout( () => {
            console.log('loaded');
            document.getElementById('phone').focus();
        }, 500);
    </script>
@endsection
