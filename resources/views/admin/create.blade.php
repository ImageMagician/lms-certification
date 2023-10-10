@extends('layouts.admin')
@section('content')
<div id="app" class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="yyz-card p-4 mb-0">
                    <div class="card-body">
                        @if( session()->has('status') )
                            <div class="alert alert-success">
                                Your password has been updated. Please login using the new password.
                            </div>
                        @endif
                        <div class="mb-0 clearfix">
                            <a href="{{ route('admin-list') }}" class="float-right btn btn-tertiary">Back</a>
                            <h2 class="h3 text-primary">Create New Admin</h2>
                        </div>
                        <form class="auth-login-form mt-2" action="{{ route('admin-create') }}" method="post">
                            @if ($errors->has('msg'))
                                <div class="alert alert-danger">
                                    <strong>{{ $errors->first('msg') }}</strong>
                                </div>
                            @endif
                            <div class="my-3">
                                <label for="first_name" class="form-label no-style p-0">First Name</label>
                                <input type="text" class="form-control" id="first_name" name="first_name" tabindex="1">
                                @if ($errors->has('first_name'))
                                    <span class="help-block font-red-mint">
                                        <strong>{{ $errors->first('first_name') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="my-3">
                                <label for="last_name" class="form-label no-style p-0">Last Name</label>
                                <input type="text" class="form-control" id="last_name" name="last_name" tabindex="2">
                                @if ($errors->has('last_name'))
                                    <span class="help-block font-red-mint">
                                        <strong>{{ $errors->first('last_name') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="my-3">
                                <label for="email" class="form-label no-style p-0">Email</label>
                                <input type="text" class="form-control" id="email" name="email" value="{{old('email') }}" tabindex="3" />
                                @if ($errors->has('email'))
                                    <span class="help-block font-red-mint">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="my-3">
                                <label for="role" class="form-label no-style p-0">Admin Level</label>
                                <select name="role" id="role" class="form-control" tabindex="4">
                                    <option value="0">Standard</option>
                                    <option value="1">Super Admin</option>
                                    <option value="2">Regional Sales Manager</option>
                                </select>
                            </div>
                            <div>
                                @csrf
                                <button type="submit" class="btn btn-primary w-100" tabindex="5">Create Admin</button>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- /Login basic -->
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
    function showPassword() {
        const obj = document.getElementById('password');
        const eye = document.getElementById('eye_icon');
        if (obj.getAttribute('type') == 'password') {
            obj.setAttribute('type', 'text');
            eye.classList.remove('fa-eye');
            eye.classList.add('fa-eye-slash');
        } else {
            obj.setAttribute('type', 'password');
            eye.classList.add('fa-eye');
            eye.classList.remove('fa-eye-slash');
        }
    }

    window.onload = function(){
       document.getElementById('first_name').focus();
    }
</script>
@endsection
