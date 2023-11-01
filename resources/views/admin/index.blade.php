@extends('layouts.admin')
@section('title')
    Admin Dashboard
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="row">
                    <div class="col-lg-3 col-xl-2 py-3 pe-2 ps-3">
                        <div class="p-3 bg-white border mb-3 mb-lg-0 border-r-6">
                            <h2 class="h4">User Stats</h2>
                            <div class="row m-0">
                                <div class="p-2 col-6 border-bottom">
                                    Total users:
                                </div>
                                <div class="p-2 col-6 border-bottom">
                                    {{ $stats['total'] }}
                                </div>
                            </div>
                            <div class="row m-0">
                                <div class="p-2 col-6 border-bottom">
                                    Certified:
                                </div>
                                <div class="p-2 col-6 border-bottom">
                                    {{ $stats['certs'] }}
                                </div>
                            </div>
                            <div class="row m-0">
                                <div class="p-2 col-6 border-bottom">
                                    Cert ready:
                                </div>
                                <div class="p-2 col-6 border-bottom">
                                    {{ $stats['finished'] }}
                                </div>
                            </div>
                            <div class="row m-0">
                                <div class="p-2 col-6">
                                    In progress:
                                </div>
                                <div class="p-2 col-6">
                                    {{ $stats['unfinished'] }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-9 col-xl-10 py-3 pe-3 ps-2">
                        <div class="p-3 bg-white border mb-3 mb-lg-0 border-r-6">
                            <div class="position-relative mb-4 clearfix">
                                <h1 class="h4 d-inline-block">
                                    Installer List
                                    @if ( $admin->partner != null )
                                        : {{ ucwords( $admin->partner ) }}
                                    @endif
                                </h1>
                                <form method="get" action="{{ route('adminDashboard') }}" class="float-right form-control w-sm-auto p-0 ps-1 focus-0">
                                    <input id="user_search" name="search" type="text" class="d-inline-block border-0" placeholder="Search users&hellip;" value="{{ request()->search; }}">
                                    <button type="submit" class="btn">&#128269;</button>
                                </form>
                            </div>
                            {{ $users->links() }}
                            <table class="table admin-table">
                                <tr class="th-header">
                                    <th>First name</th>
                                    <th>Last Name</th>
                                    <th>Company</th>
                                    <th>State</th>
                                    <th>Action</th>
                                    @foreach($modules as $m)
                                        <th>Step {{ $m->id }}</th>
                                    @endforeach
                                </tr>
                                @if (is_null($users))
                                @else
                                    @foreach($users as $u)
                                        @if ( $u->training_done == 1 && is_null( $u->cert ) )
                                        <tr class="user-line" id="{{$u->first_name}}_{{$u->last_name}}_{{$u->companies}}_{{$u->id}}">
                                            <td class="name w-auto">{{ucwords(strtolower($u->first_name))}}</td>
                                            <td class="name w-auto">{{ucwords(strtolower($u->last_name))}}</td>
                                            <td class="name w-auto">{{ucwords(strtolower($u->companies))}}</td>
                                            <td class="name w-auto">{{$u->states}}</td>
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
                                @endif
                                </tr>
                                <tr class="border-0 p-0"><td colspan="17" class="p-0 border-black w-100"></td></tr>
                                @if( is_null( $users ) )
                                    <tr>
                                        <td colspan="17">
                                            <h3 class="py-3 text-center" style="opacity:.5">
                                                No users registered in your region.
                                            </h3>
                                        </td>
                                    </tr>
                                @else
                                    @foreach($users as $u)
                                        @if (( $u->training_done == null && $u->cert == null ) || $u->cert !== NULL )
                                            <tr class="user-line" id="{{$u->first_name}}_{{$u->last_name}}_{{$u->companies}}_{{$u->id}}">
                                                <td class="name w-auto">{{ucwords(strtolower($u->first_name))}}</td>
                                                <td class="name w-auto">{{ucwords(strtolower($u->last_name))}}</td>
                                                <td class="name w-auto">{{ucwords(strtolower($u->companies))}}</td>
                                                <td class="name w-auto">{{$u->states}}</td>
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
                                                            @if ( is_null( $u->$mod_id ) )
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
                                @endif
                            </table>
                            {{ $users->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <style>
        #user_search:focus {
            outline:none;
        }
    </style>
    <script>
        const users = document.getElementsByClassName('user-line');
        /*
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
         */
    </script>
@endsection
