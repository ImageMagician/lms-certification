@extends(('layouts.admin'))

@section('title')
    Admin Login
@endsection
@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="yyz-card p-4 mb-0">
                    <div class="card-body">
                        @if( session()->has('status') )
                            <div class="alert alert-success">
                                Your password has been updated. Please login using the new password.
                            </div>
                        @endif
                        <h2 class="text-primary">Admin Login</h2>
                        <form class="auth-login-form mt-2" action="{{route('adminLoginPost')}}" method="post">
                            @csrf
                            @if ($errors->has('msg'))
                                <div class="alert alert-danger">
                                    <strong>{{ $errors->first('msg') }}</strong>
                                </div>
                            @endif
                            <div class="my-3">
                                <label for="email" class="form-label no-style p-0">Email</label>
                                <input type="text" class="form-control" id="email" name="email" value="@if ( !empty($_GET['email']) ){{ htmlspecialchars($_GET['email']) }}@endif" autofocus tabindex="1" />
                                @if ($errors->has('email'))
                                    <span class="help-block font-red-mint">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="my-3">
                                <div class="d-flex justify-content-between">
                                    <label class="form-label no-style p-0" for="password">Password</label>
                                    <a href="{{ route('admin-forgot') }}">
                                        <small>Forgot Password?</small>
                                    </a>
                                </div>
                                <div class="input-group input-group-merge form-password-toggle">
                                    <input type="password" class="form-control form-control-merge" id="password" name="password" tabindex="2" />
                                    <span id="password_eye" onclick="showPassword(this)" class="input-group-text cursor-pointer" tabindex="3"><i id="eye_icon" class="fa fa-eye"></i></span>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary w-100" tabindex="5">Sign in</button>
                        </form>
                    </div>
                </div>
                <!-- /Login basic -->
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    window.onload = function() {
        const admin_email = document.getElementById('email');
        admin_email.focus();
    }
</script>
@endsection
