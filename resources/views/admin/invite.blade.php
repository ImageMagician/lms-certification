@extends('layouts.admin')
@section('title')
    Invite Installer
@endsection

@section('content')
    <div class="container py-5">
        <div class="row">
            <div class="col-8 offset-2">
                <div class="yyz-card p-4">
                    <h1>Invite New Installer</h1>
                    <p>Submit the installer's contact information to send them an invitation to complete the certification process.</p>
                    @if ( Session::get('status') )
                        <div class="alert alert-success mb-3">
                            {{ Session::get('status') }}
                        </div>
                    @elseif ( Session::get('duplicate') )
                        <div class="alert alert-danger mb-3">
                            {{ Session::get('duplicate') }}
                        </div>
                    @endif
                    <form action="{{ route('invite-installer-process') }}">
                        <div class="input-group mb-3">
                            <label for="email" class="input-group-text">Email</label>
                            <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}">
                        </div>
                        <div class="input-group mb-3">
                            <label for="first_name" class="input-group-text">First Name</label>
                            <input type="text" name="first_name" id="first_name" class="form-control @error('first_name') is-invalid @enderror" value="{{ old('first_name') }}">
                        </div>
                        <div class="input-group mb-3">
                            <label for="last_name" class="input-group-text">Last Name</label>
                            <input type="text" name="last_name" id="last_name" class="form-control @error('last_name') is-invalid @enderror" value="{{ old('last_name') }}">
                        </div>
                        <div>
                            @csrf
                            <button type="submit" class="btn btn-primary me-2">Submit</button>
                            <button type="reset" class="btn btn-tertiary">Clear</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <style>
        .input-group-text {
            width: 40%;
        }

        @media (min-width:481px) {
            .input-group-text {
                width: 33%;
            }
        }

        @media (min-width:600px) {
            .input-group-text {
                width: 33%;
            }
        }

        @media (min-width:768px) {
            .input-group-text {
                width: 25%;
            }
        }

        @media (min-width:992px) {
            .input-group-text {
                width: 15%;
            }
        }
    </style>
@endsection
