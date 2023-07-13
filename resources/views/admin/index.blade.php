@extends('layouts.admin')
@section('title')
    Admin Dashboard
@endsection

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-9">
                <h1>Installer List</h1>
                @php $i = 0; @endphp
                @foreach($users as $u)
                    <a href="{{ route('userDetail', $u->id) }}" class="d-block yyz-card p-3 mt-3">
                        <h2 class="h5">
                            {{ $u->first_name }} {{ $u->last_name }}
                            @if ( $u->cert !== null )
                                <div class="border border-info px-2 ms-2 d-inline-block" style="border-radius:4px; background:#f6f6f6;"><small>{{$u->cert}}</small></div>
                            @elseif ( $activity[$i]->training_done == 1 )
                                <div class="alert alert-warning d-inline-block ms-2 py-1 px-2" style="font-size:small">User ready for certification</div>
                            @endif
                        </h2>
                        <div class="row mt-2 mx-0">
                            <div class="col-md-5">
                                <div class="border-bottom-light">Email: {{ $u->email }}</div>
                                <div class="border-bottom-light">Phone: {{ $u->phone }}</div>
                            </div>
                            <div class="col-md-7">
                                <div class="border-bottom-light">Assoc. companies: {{ $u->companies }}</div>
                                <div class="border-bottom-light">State licenses: {{ $u->states }}</div>
                            </div>
                        </div>
                        <div class="row mt-2 mx-0">
                            @foreach($modules as $m)
                                <div class="col-lg-2 col-md-4 col-sm-6 p-1">
                                    <div class="h-100 border p-2
                                    @foreach($activity as $a)
                                        {{-- determine if module is completed --}}
                                        @php $complete = ''; @endphp
                                        @if ( $a->user_id == $u->id )
                                            @php
                                                $mod_id = 'module_' . sprintf("%02d", $m->id);
                                            @endphp
                                            @if($a->$mod_id > 0)
                                            alert alert-success
                                            @php $complete = 'complete' @endphp
                                            @endif
                                        @endif
                                    @endforeach
                                    ">
                                        <div class="small-steps">STEP {{ $m->id }}</div>
                                        <h3 class="small-title">{{ $m->title }}</h3>
                                        @if (!empty($complete))
                                        <div class="position-absolute" style="bottom:5px; right:5px">
                                            {{ $complete }}
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </a>
                    @php $i++ @endphp
                @endforeach
            </div>
        </div>
    </div>
@endsection
