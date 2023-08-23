@extends('layouts.admin')
@section('title')
    Admin Dashboard
@endsection

@section('content')
    <div class="container-fluid py-3">
        <div class="row justify-content-center">
            <div class="col-xxl-10">
                <h1 class="h4">Installer List</h1>
                <table class="table">
                    <tr>
                        <th>First name</th>
                        <th>Last Name</th>
                        <th>Cert Status</th>
                        @foreach($modules as $m)
                            <th>Step {{ $m->id }}</th>
                        @endforeach
                    </tr>
                    @foreach($users as $u)
                    <tr>
                        <td class="w-auto"><a href="{{route('userDetail', ['id'=>$u->id])}}">{{$u->first_name}}</a></td>
                        <td class="w-auto"><a href="{{route('userDetail', ['id'=>$u->id])}}">{{$u->last_name}}</a></td>
                        <td>
                            @if($u->cert)
                                {{ $u->cert }}
                            @else
                                @foreach ($activity as $a)
                                    @if ($a->user_id == $u->id && $a->training_done == 1)
                                        <a class="btn btn-small btn-primary" href="{{ route('userDetail', $u->id) }}">
                                            CERTIFY
                                        </a>
                                    @endif
                                @endforeach
                            @endif
                        </td>
                        @foreach($modules as $m)
                            <td class="px-1">
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
                    @endforeach
                </table>
                @foreach($users as $u)
                    <div class="d-block p-3 border-bottom-light">
                        <div class="row">
                            <div class="col-md-2">
                                <a href="{{ route('userDetail', $u->id) }}" class="btn btn-primary w-100 btn-small">
                                <h2 class="h5 mb-0">
                                    {{ $u->first_name }} {{ $u->last_name }}
                                    @if ( $u->cert !== null )
                                        <div class="border border-info px-2 ms-2 d-inline-block vertical-align-bottom" style="border-radius:4px; background:#f6f6f6;"><small>{{$u->cert}}</small></div>
                                    @else
                                        @foreach ($activity as $a)
                                            @if ($a->user_id == $u->id && $a->training_done == 1)
                                                <div class="alert alert-warning d-inline-block ms-2 py-1 px-2" style="font-size:small">
                                                    User ready for certification
                                                </div>
                                            @endif
                                        @endforeach
                                    @endif
                                </h2>
                                </a>
                                <div>Email: {{ $u->email }}</div>
                                <div>Phone: {{ $u->phone }}</div>
                                <div>Company: {{ $u->companies }}</div>
                                <div>State: {{ $u->states }}</div>
                            </div>
                            <div class="col-md-10">
                                <div class="row">
                                    @foreach($modules as $m)
                                        <div class="col-lg-1 col-md-2 col-sm-6 p-1">
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
                                                @if (!empty($complete))
                                                    <div class="position-absolute" style="bottom:5px; right:5px">
                                                        {{ $complete }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
