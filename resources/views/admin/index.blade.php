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
                                    <td class="table-action">
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
                                        <td class="px-lg-1">
                                            @foreach($activity as $a)
                                                @if ($a->user_id == $u->id)
                                                    {{-- determine if module is completed --}}
                                                    @php
                                                        $mod_id = 'module_' . sprintf("%02d", $m->id);
                                                        $q_tot = 0;
                                                        $a_tot = 0;
                                                    @endphp
                                                    @if ($a->$mod_id != null)
                                                        @foreach($questions as $question)
                                                            @if ($question->module_id == $m->id )
                                                                @php
                                                                    $q_tot++;
                                                                @endphp
                                                                @foreach ($answers as $answer)
                                                                    @if ( $question->module_id == $answer->module_id && $question->q_id == $answer->q_id && $answer->user_id == $u->id)
                                                                        @if ( $question->answer_correct == $answer->answer)
                                                                            @php
                                                                                $a_tot++;
                                                                            @endphp
                                                                        @endif
                                                                    @endif
                                                                @endforeach
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                <span class="step-name">Step {{$m->id}}</span>
                                                <div class="text-center small py-1 perc-{{$a_tot}}-{{$q_tot}}
                                                    @php $perc = 0; @endphp
                                                    @if ( $q_tot > 0)
                                                        @php
                                                            $perc = round($a_tot / $q_tot * 100, 2);
                                                        @endphp
                                                        @if ( $perc < 75 )
                                                            bg-red
                                                        @else
                                                            bg-green
                                                        @endif
                                                    @endif
                                                ">
                                                    @if ($perc > 0 )
                                                        {{ $perc }}%
                                                    @endif
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
                            @if ($a->user_id == $u->id && ( ( $a->training_done == null && $u->cert == null ) || ($a->training_done != null && $u->cert != null ) ) )
                                <tr>
                                    <td class="name w-auto">{{$u->first_name}}</td>
                                    <td class="name w-auto">{{$u->last_name}}</td>
                                    <td class="table-action">
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
                                        <td class="px-lg-1">
                                            @foreach($activity as $a)
                                                @if ($a->user_id == $u->id)
                                                    {{-- determine if module is completed --}}
                                                    @php
                                                        $mod_id = 'module_' . sprintf("%02d", $m->id);
                                                        $q_tot = 0;
                                                        $a_tot = 0;
                                                    @endphp
                                                    @if ($a->$mod_id != null)
                                                        @foreach($questions as $question)
                                                            @if ($question->module_id == $m->id )
                                                                @php
                                                                    $q_tot++;
                                                                @endphp
                                                                @foreach ($answers as $answer)
                                                                    @if ( $question->module_id == $answer->module_id && $question->q_id == $answer->q_id && $answer->user_id == $u->id)
                                                                        @if ( $question->answer_correct == $answer->answer)
                                                                            @php
                                                                                $a_tot++;
                                                                            @endphp
                                                                        @endif
                                                                    @endif
                                                                @endforeach
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                    @php $perc = 0; @endphp
                                                    @if ( $q_tot > 0)
                                                        <span class="step-name">Step {{$m->id}}</span>
                                                        @php
                                                            $perc = round($a_tot / $q_tot * 100, 2);
                                                        @endphp
                                                        @if ( $perc < 75 )
                                                            @php $badge = 'bg-red'; @endphp
                                                        @else
                                                            @php $badge = 'bg-green'; @endphp
                                                        @endif
                                                    @else
                                                        @php $badge = 'opacity-0'; @endphp
                                                    @endif
                                                    <div class="text-center small py-1 {{$badge}}">
                                                        {{ $perc }}%
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
