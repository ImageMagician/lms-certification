<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Lion Energy') }} @yield('title')</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>

<body>
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
                        <h2 class="text-primary">Admin Login</h2>
                        <form class="auth-login-form mt-2" action="{{route('admin-create')}}" method="post">
                            @csrf
                            @if ($errors->has('msg'))
                                <div class="alert alert-danger">
                                    <strong>{{ $errors->first('msg') }}</strong>
                                </div>
                            @endif
                            <div class="my-3">
                                <label for="name" class="form-label no-style p-0">Name</label>
                                <input type="text" class="form-control" id="name" name="name" autofocus tabindex="1">
                            </div>
                            <div class="my-3">
                                <label for="email" class="form-label no-style p-0">Email</label>
                                <input type="text" class="form-control" id="email" name="email" value="{{old('email') }}" tabindex="2" />
                                @if ($errors->has('email'))
                                    <span class="help-block font-red-mint">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="my-3">
                                    <label class="form-label no-style p-0" for="password">Password</label>
                                    <input type="text" class="form-control" id="password" name="password" tabindex="3" />
                            </div>

                            <div class="my-3">
                                    <label class="form-label no-style p-0" for="password_confirmation">Confirm Password</label>
                                    <input type="text" class="form-control" id="password_confirmation" name="password_confirmation" tabindex="4" />
                            </div>

                            <button type="submit" class="btn btn-primary w-100" tabindex="5">Create Admin</button>
                        </form>
                    </div>
                </div>
                <!-- /Login basic -->
            </div>
        </div>
    </div>
</div>
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
        }    }
</script>
</body>
</html>
