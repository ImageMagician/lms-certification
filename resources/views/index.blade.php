@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">

            <div class="col-md-10 py-5">
                <div class="row align-items-center">
                    <div class="col-sm-4 text-center">
                        <img src="{{ asset('storage/sanctuary_1-1_163x600.png') }}" alt="Santuary 1:1 System">
                    </div>
                    <div class="col-sm-8">

                    <h1>Welcome</h1>
                    <h5 style="font-weight:700; margin-bottom:1em">
                        Welcome to the Lion Energy Sanctuary certification process. The process consists of 6 steps.
                    </h5>
                    <ol>
                        <li><strong>Video and quiz</strong> - product overview.</li>
                        <li><strong>Video and quiz</strong> – product installation.</li>
                        <li><strong>Identify your first installation project</strong> that will be used to complete your training and certification with guidance from your Lion Energy trainer.</li>
                        <li><strong>Upload project design documents and site photos</strong> for the first installation followed by a review and consultation with your Lion Energy trainer.</li>
                        <li><strong>Upload photographs of the completed installation</strong> and wiring for your Lion trainer to review.</li>
                        <li><strong>Commissioning the system</strong> of the first installation by your Lion Energy trainer.</li>
                    </ol>
                    <p>
                        Upon completion, you will receive a certification number that allows you to install the Lion Sanctuary system going forward.
                    </p>

                    <div class="yyz-card p-3 my-4" style="min-height:0;">
                        <strong>NOTE: Warranty of the Sanctuary is voided if installed by a non-certified individual.</strong>
                        Please make sure that any and all installers complete this commissioning training.
                    </div>
                    <p>
                        Welcome aboard!
                    </p>
                    <p>
                        <a href="/login" class="btn btn-primary me-4">Login</a>
                        <a href="/register" class="btn btn-primary">Register</a>
                    </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <style>
        ol {
            padding-top:1em;
            padding-bottom:1em;
            border-top:1px solid rgba(0,0,0,.1);
            border-bottom:1px solid rgba(0,0,0,.1);
        }
        li {
            margin-bottom:0.75em;
        }

        li:last-child {
            margin-bottom:0;
        }
    </style>
@endsection
