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
                        Welcome to the Lion Energy Sanctuary certification process.
                    </h5>
                    <p>
                        Welcome to the Lion Energy Sanctuary certification process. This process will walk you through a series of instructional videos that will cover a brief overview of the Sanctuary and how to mount, wire, and commission the Sanctuary system.
                        Each video is followed by a brief quiz to help you learn and retain the important components of commissioning the system.
                    </p>
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
