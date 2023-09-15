@extends('layouts.admin')
@section('title')
    Admin Dashboard
@endsection

@section('content')
    <div class="container-fluid py-3">
        <div class="row justify-content-center">
            <div class="col-12 p-4 bg-white border-info-subtle">
                <div class="row">
                    <div class="col-lg-3 col-xl-2 px-lg-2">
                        <div class="p-3 p-lg-4 border mb-3 mb-lg-0">
                            <h2 class="h4">User Stats</h2>
                            <div class="d-inline-block d-lg-block pe-3 pe-lg-0">
                                Total users: {{ $stats['total'] }}
                            </div>
                            <div class="d-inline-block d-lg-block pe-3 pe-lg-0">
                                Cert ready:  {{ $stats['finished'] }}
                            </div>
                            <div class="d-inline-block d-lg-block pe-3 pe-lg-0">
                                In progress: {{ $stats['unfinished'] }}
                            </div>
                            <div class="d-inline-block d-lg-block pe-3 pe-lg-0">
                                Certified:   {{ $stats['certs'] }}
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-9 col-xl-10">
                        <div class="position-relative mb-4 clearfix">
                            <h1 class="h4 d-inline-block">Installer List</h1>
                            <input id="user_search" type="text" class="form-control float-right d-inline-block w-100 w-sm-auto" placeholder="Search users&hellip;">
                        </div>
                        <table class="table admin-table">
                            <tr class="th-header">
                                <th>First name</th>
                                <th>Last Name</th>
                                <th>Company</th>
                                <th>Action</th>
                                @foreach($modules as $m)
                                    <th>Step {{ $m->id }}</th>
                                @endforeach
                            </tr>
                            @foreach($users as $u)
                                @if ( $u->training_done == 1 && $u->cert == null )
                                <tr class="user-line" id="{{$u->first_name}}_{{$u->last_name}}_{{$u->companies}}_{{$u->id}}">
                                    <td class="name w-auto">{{ucwords(strtolower($u->first_name))}}</td>
                                    <td class="name w-auto">{{ucwords(strtolower($u->last_name))}}</td>
                                    <td class="name w-auto">{{ucwords(strtolower($u->companies))}}</td>
                                    <td class="table-action px-lg-2">
                                        @if ( $u->cert === NULL )
                                            <a class="btn btn-small btn-primary w-100" href="{{ route('userDetail', $u->id) }}">
                                                CERTIFY
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
                                            <span class="step-name">Step {{$m->id}}</span>
                                            <div class="text-center small py-1
                                                        @if ( $u->$mod_id === NULL )
                                                            opacity-0 d-inline-block
                                                        @elseif ( $u->$mod_id < 75 )
                                                            bg-red
                                                        @else
                                                            bg-green
                                                        @endif
                                                    ">
                                                {{ $u->$mod_id }}%
                                            </div>
                                        </td>
                                    @endforeach
                                </tr>
                                @endif
                            @endforeach
                            </tr>
                            <tr class="border-0 p-0"><td colspan="16" class="p-0 border-black w-100"></td></tr>
                            @foreach($users as $u)
                                @if (( $u->training_done == null && $u->cert == null ) || $u->cert !== NULL )
                                    <tr class="user-line" id="{{$u->first_name}}_{{$u->last_name}}_{{$u->companies}}_{{$u->id}}">
                                        <td class="name w-auto">{{ucwords(strtolower($u->first_name))}}</td>
                                        <td class="name w-auto">{{ucwords(strtolower($u->last_name))}}</td>
                                        <td class="name w-auto">{{ucwords(strtolower($u->companies))}}</td>
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
                                                    <span class="step-name">Step {{$m->id}}</span>
                                                    <div class="text-center small py-1
                                                        @if ( $u->$mod_id === NULL )
                                                            opacity-0 d-inline-block
                                                        @elseif ( $u->$mod_id < 75 )
                                                            bg-red
                                                        @else
                                                            bg-green
                                                        @endif
                                                    ">
                                                        {{ $u->$mod_id }}%
                                                    </div>
                                            </td>
                                        @endforeach
                                    </tr>
                                @endif
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        const users = document.getElementsByClassName('user-line');

        setTimeout( ()=> {
            const user_search = document.getElementById('user_search');
            user_search.addEventListener('keyup', this.searchUsers, false);
        }, 1000);

        function searchUsers() {
            let i;

            // Take the search value and check the id of each user "tr"
            // If the pattern does not exist, add the class d-none
            const val = this.value;

            // Escape all special characters for the Regex search
            const escapedDynamicString = val.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
            const pattern = new RegExp(escapedDynamicString, "gi");

            for ( i = 0; i < users.length; i++ ) {
                users[i].classList.remove('d-none');
                if (!pattern.test(users[i].id)) {
                    users[i].classList.add('d-none');
                }
            }

            const names = document.getElementsByClassName('name');
            const highlight = document.getElementsByClassName('highlight');

            for ( i = 0; i < highlight.length; i++ ) {
                // remove all previous highlight nodes
                let cont = highlight[i].innerText;
                highlight[i].after(cont);
                highlight[i].remove();
            }

            // add new highlight node around searched text of all class names "name"
            // These are the first and last name fields
            function wrapSubstringWithSpan(inputString, substringToWrap) {
                const escapedDynamicString = substringToWrap.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
                const regex = new RegExp(`(${escapedDynamicString})`, 'gi');
                return inputString.replace(regex, '<span class="highlight">$1</span>');
            }

            for ( i = 0; i < names.length; i++ ) {
                if ( pattern.test( names[i].innerText ) ) {
                    names[i].innerHTML = wrapSubstringWithSpan(names[i].innerText, val);
                }
            }
        }
    </script>
@endsection
