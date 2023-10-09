@extends('layouts.admin')
@section('title')
    Admin List
@endsection

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 p-4 bg-white mt-4">
                <h1 class="h3">Admin User : {{ $user->first_name }} {{ $user->last_name }}</h1>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <strong>Whoops!</strong> There were some problems with your input.<br><br>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form action="{{ route('admin-update') }}" method="post">
                    <div class="row mt-3">
                        <div class="col-sm-4 text-right">
                            <label for="email" class="form-label my-2">Email/Username</label>
                        </div>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="email" name="email" readonly value="{{ $user->email }}" />
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-sm-4 text-right">
                            <label for="first_name" class="form-label my-2">First Name</label>
                        </div>
                        <div class="col sm-8">
                            <input id="first_name" name="first_name" type="text" class="form-control" value="{{ $user->first_name }}" tabindex="1">
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-sm-4 text-right">
                            <label for="last_name" class="form-label my-2">Last Name</label>
                        </div>
                        <div class="col sm-8">
                            <input id="last_name" name="last_name" type="text" class="form-control" value="{{ $user->last_name }}" tabindex="2">
                        </div>
                    </div>
                    <div class="row pt-3">
                        <div class="col-sm-4 text-right">
                            <label for="password" class="form-label my-2">Set Password</label>
                        </div>
                        <div class="col sm-8">
                            <input type="password" id="password" name="password" class="form-control" value="" tabindex="3">
                        </div>
                    </div>
                    <div class="row pt-3">
                        <div class="col-sm-4 text-right">
                            <label class="form-label my-2" for="password_confirmation">Confirm Password</label>
                        </div>
                        <div class="col sm-8">
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" tabindex="4" />
                        </div>
                    </div>
                    @if ($admin->super_admin == 1 )
                    <div class="row pt-3">
                        <div class="col-sm-4 text-right">
                            <label class="form-label my-2" for="super_admin">Access</label>
                        </div>
                        <div class="col sm-8">
                            <select id="super_admin" name="super_admin" class="form-control"
                            @if ( $admin->id == $user->id )
                                disabled
                            @endif
                            >
                                <option value="">User</option>
                                <option value="1"
                                    @if ( $user->super_admin == 1)
                                        selected
                                    @endif
                                >Admin</option>
                            </select>
                        </div>
                    </div>
                    @endif
                    <div class="row pt-3">
                        <div class="col-sm-4"></div>
                        <div class="col-sm-8">
                            @csrf
                            <input type="hidden" name="user_id" value="{{ $user->id }}">
                            <button type="submit" class="btn btn-primary me-2">Submit</button>
                            <a href="{{ route('admin-list') }}" class="btn btn-tertiary">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
