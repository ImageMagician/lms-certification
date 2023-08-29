@extends('layouts.admin')
@section('title')
    Admin Dashboard
@endsection

@section('content')
    <div class="container-fluid py-3">
        <div class="row justify-content-center">
            <div class="col-xxl-10 p-4 bg-white border-info-subtle">
                <h1 class="h4">Installer List</h1>
                <table class="table admin-table">
                    <tr class="th-header">
                        <th>First name</th>
                        <th>Last Name</th>
                        <th>Action</th>
                        @foreach($modules as $m)
                            <th>Step {{ $m->id }}</th>
                        @endforeach
                    </tr>
                    @foreach($users as $u)
                        @foreach ( $activity as $a )
                            @if ($a->user_id == $u->id && $a->training_done == 1 && $u->cert == null)
                                <tr>
                                    <td class="name w-auto">{{$u->first_name}}</td>
                                    <td class="name w-auto">{{$u->last_name}}</td>
                                    <td class="table-action px-lg-2">
                                        @if($u->cert)
                                            {{ $u->cert }}
                                        @else
                                            @foreach ($activity as $a)
                                                @if ($a->user_id == $u->id && $a->training_done == 1 && $u->cert != null)
                                                    <a class="btn btn-small btn-tertiary w-100" href="{{ route('userDetail', $u->id) }}">
                                                        {{$u->cert}}
                                                    </a>
                                                @elseif ($a->user_id == $u->id && $a->training_done == 1 && $u->cert == null)
                                                    <a class="btn btn-small btn-primary w-100" href="{{ route('userDetail', $u->id) }}">
                                                        CERTIFY
                                                    </a>
                                                @elseif ($a->user_id == $u->id && $a->training_done == null)
                                                    <a class="btn btn-small btn-tertiary w-100" href="{{ route('userDetail', $u->id) }}">
                                                        VIEW
                                                    </a>
                                                @endif
                                            @endforeach
                                        @endif
                                    </td>
                                    @foreach($modules as $m)
                                        <td>
                                            @php
                                                $mod_id = 'module_' . sprintf("%02d", $m->id);
                                            @endphp
                                            @foreach($activity as $a)
                                                @if ($a->user_id == $u->id)
                                                    {{-- determine if module is completed --}}
                                                    <span class="step-name">Step {{$m->id}}</span>
                                                    <div class="text-center small py-1
                                                        @if ( $a->$mod_id == null )
                                                            opacity-0 d-inline-block
                                                        @elseif ( $u->$mod_id < 75 )
                                                            bg-red
                                                        @else
                                                            bg-green
                                                        @endif
                                                    ">
                                                        {{ $u->$mod_id }}%
                                                    </div>
                                                @endif
                                            @endforeach
                                        </td>
                                    @endforeach
                                </tr>
                            @endif
                        @endforeach
                    @endforeach
                    </tr>
                    <tr class="border-0 p-0"><td colspan="15" class="p-0 border-black w-100"></td></tr>
                    @foreach($users as $u)
                        @foreach ( $activity as $a )
                            @if ($a->user_id == $u->id && ( ( $a->training_done == null && $u->cert == null ) || ( $u->cert != null ) ) )
                                <tr>
                                    <td class="name w-auto">{{ucwords(strtolower($u->first_name))}}</td>
                                    <td class="name w-auto">{{ucwords(strtolower($u->last_name))}}</td>
                                    <td class="table-action px-lg-2">
                                        @if ( $u->cert == null )
                                            <a class="btn btn-small btn-tertiary w-100" href="{{ route('userDetail', $u->id) }}">
                                                VIEW
                                            </a>
                                        @else
                                            <a class="btn btn-small btn-warning w-100" href="{{ route('userDetail', $u->id) }}">
                                                <strong>{{$u->cert}}</strong>
                                            </a>
                                        @endif
                                    </td>
                                    @foreach($modules as $m)
                                        <td>
                                            @php
                                                $mod_id = 'module_' . sprintf("%02d", $m->id);
                                            @endphp
                                            @foreach($activity as $a)
                                                @if ($a->user_id == $u->id)
                                                    {{-- determine if module is completed --}}
                                                    <span class="step-name">Step {{$m->id}}</span>
                                                    <div class="text-center small py-1
                                                        @if ( $a->$mod_id == null )
                                                            opacity-0 d-inline-block
                                                        @elseif ( $u->$mod_id < 75 )
                                                            bg-red
                                                        @else
                                                            bg-green
                                                        @endif
                                                    ">
                                                        {{ $u->$mod_id }}%
                                                    </div>
                                                @endif
                                            @endforeach
                                        </td>
                                    @endforeach
                                </tr>
                            @endif
                        @endforeach
                    @endforeach
                </table>
            </div>
        </div>
    </div>
@endsection
