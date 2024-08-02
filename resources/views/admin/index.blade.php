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
                            {{-- SEARCH FORM --}}
                            <div id="search_field" class="d-flex form-control mb-3 p-0 ps-1 focus-0 justify-content-end">
                                <form method="get" action="{{ route('adminDashboard') }}">
                                    <input id="user_search" name="search" type="text" class="flex-grow-1 border-0" placeholder="Search users&hellip;" value="@if (!empty($_GET['search'])){{ $_GET['search'] }}@endif">
                                    <button type="submit" class="btn px-2 m-0"><i class="fa-solid fa-magnifying-glass"></i></button>
                                </form>
                                <form method="get" action="{{ route('adminDashboard') }}">
                                    <input type="hidden" name="search" value="">
                                    <button type="submit" class="btn px-2 m-0"><i class="fa-solid fa-times"></i></button>
                                </form>
                            </div>
                            {{-- ALL INSTALLERS --}}
                            <form class="block" action="{{ route('adminDashboard') }}" method="get">
                                @if ( !empty($_GET['search']))
                                <input type="hidden" name="search" value="{{$_GET['search']}}">
                                @endif
                                <input type="hidden" name="filter" value="all">
                                <button type="submit" class="d-block border-0 bg-transparent p-0 w-100 text-start">
                                    <div class="row m-0 @if( empty($_GET['filter']) || $_GET['filter'] === 'all' ) border border-primary @else border-bottom @endif">
                                        <div class="p-2 col-6 border-bottom">
                                            Total users:
                                        </div>
                                        <div class="p-2 col-6 border-bottom">
                                            {{ $stats['total'] }}
                                            <div class="float-right align-middle rounded border-0 bg-light-mid-gray px-1">
                                                <i class="fa-solid fa-magnifying-glass"></i>
                                            </div>
                                        </div>
                                    </div>
                                </button>
                            </form>
                            {{-- CERTIFIED INSTALLERS --}}
                            <form class="block" action="{{ route('adminDashboard') }}" method="get">
                                @if ( !empty($_GET['search']))
                                    <input type="hidden" name="search" value="{{$_GET['search']}}">
                                @endif
                                <input type="hidden" name="filter" value="certified">
                                <button type="submit" class="d-block border-0 bg-transparent p-0 w-100 text-start">
                                    <div class="row m-0 @if( !empty( $_GET['filter'] ) && $_GET['filter'] === 'certified' ) border border-primary @else border-bottom @endif">
                                        <div class="p-2 col-6">
                                            Certified:
                                        </div>
                                        <div class="p-2 col-6">
                                            {{ $stats['certs'] }}
                                            <div class="float-right align-middle rounded border-0 bg-light-mid-gray px-1">
                                                <i class="fa-solid fa-magnifying-glass"></i>
                                            </div>
                                        </div>
                                    </div>
                                </button>
                            </form>
                            {{-- READY TO BE CERTIFIED --}}
                            <form class="block" action="{{ route('adminDashboard') }}" method="get">
                                @if ( !empty($_GET['search']))
                                    <input type="hidden" name="search" value="{{$_GET['search']}}">
                                @endif
                                <input type="hidden" name="filter" value="finished">
                                <button type="submit" class="d-block border-0 bg-transparent p-0 w-100 text-start">
                                    <div class="row m-0 @if( !empty( $_GET['filter'] ) && $_GET['filter'] === 'finished' ) border border-primary @else border-bottom @endif">
                                        <div class="p-2 col-6">
                                            Cert ready:
                                        </div>
                                        <div class="p-2 col-6">
                                            {{ $stats['finished'] }}
                                            <div class="float-right align-middle rounded border-0 bg-light-mid-gray px-1">
                                                <i class="fa-solid fa-magnifying-glass"></i>
                                            </div>
                                        </div>
                                    </div>
                                </button>
                            </form>
                            {{-- IN PROGRESS --}}
                            <form class="block" action="{{ route('adminDashboard') }}" method="get">
                                @if ( !empty($_GET['search']))
                                    <input type="hidden" name="search" value="{{$_GET['search']}}">
                                @endif
                                <input type="hidden" name="filter" value="unfinished">
                                <button type="submit" class="d-block border-0 bg-transparent p-0 w-100 text-start">
                                    <div class="row m-0 @if( !empty( $_GET['filter'] ) && $_GET['filter'] === 'unfinished' ) border border-primary @else border-bottom @endif">
                                        <div class="p-2 col-6">
                                            In progress:
                                        </div>
                                        <div class="p-2 col-6">
                                            {{ $stats['unfinished'] }}
                                            <div class="float-right align-middle rounded border-0 bg-light-mid-gray px-1">
                                                <i class="fa-solid fa-magnifying-glass"></i>
                                            </div>
                                        </div>
                                    </div>
                                </button>
                            </form>
                            {{-- LINKED TO ADMIN ACCOUNT --}}
                            @if ($stats['rsm'] > 0)
                            <form action="{{ route('adminDashboard') }}" method="get" class="block">
                                @if ( !empty($_GET['search']))
                                    <input type="hidden" name="search" value="{{$_GET['search']}}">
                                @endif
                                <input type="hidden" name="filter" value="rsm">
                                <button type="submit" class="d-block border-0 bg-transparent p-0 w-100 text-start">
                                    <div class="row m-0 @if( !empty( $_GET['filter'] ) && $_GET['filter'] === 'rsm' ) border border-primary @else border-bottom @endif">
                                        <div class="p-2 col-6">
                                            RSM Assigned:
                                        </div>
                                        <div class="p-2 col-6">
                                            {{ $stats['rsm'] }}
                                            <div class="float-right align-middle rounded border-0 bg-light-mid-gray px-1">
                                                <i class="fa-solid fa-magnifying-glass"></i>
                                            </div>
                                        </div>
                                    </div>
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                    <div class="col-lg-9 col-xl-10 py-3 pe-3 ps-2">
                        <div class="p-3 bg-white border mb-3 mb-lg-0 border-r-6">
                            <div class="flex mb-2">
                                <h1 class="h4">
                                    Installer List
                                    @if (!empty($_GET['filter']) )
                                        @if ( $_GET['filter'] === 'certified' )
                                            : Certified
                                        @elseif ( $_GET['filter'] === 'finished' )
                                            : Cert Ready
                                        @elseif ($_GET['filter'] === 'unfinished')
                                            : In Progress
                                        @elseif ($_GET['filter'] === 'rsm')
                                            : RSM Assigned
                                        @endif
                                    @endif
                                </h1>
                                {{-- Pagination --}}
                                {{ $users->links() }}
                            </div>
                            @if ( count($users) == 0 )
                                <h2 class="text-center py-5" style="opacity:.5">No users found for &ldquo;@if (!empty( $_GET['search'])){{ $_GET['search'] }}@endif&rdquo;.</h2>
                            @else

                            <table class="table admin-table">
                                <tr class="th-header">
                                    <th colspan="6" class="border-0 py-0"></th>
                                    <th colspan="12" class="text-center border-0 py-0">Modules</th>
                                </tr>
                                <tr class="th-header">
                                    <th>First name</th>
                                    <th>Last Name</th>
                                    <th>Email</th>
                                    <th>Company</th>
                                    <th class="text-center">State</th>
                                    <th class="text-center">Action</th>
                                    @foreach($modules as $m)
                                        <th class="text-center">{{ $m->id }}</th>
                                    @endforeach
                                </tr>
                                @foreach($users as $u)
                                    <tr class="user-line" id="{{$u->first_name}}_{{$u->last_name}}_{{$u->companies}}_{{$u->id}}">
                                        <td class="name w-auto vertical-align-middle">{{ucwords(strtolower($u->first_name))}}<span class="d-inline-block d-xl-none pe-1"></span></td>
                                        <td class="name w-auto vertical-align-middle">{{ucwords(strtolower($u->last_name))}}<span class="d-inline-block d-xl-none pe-3"></span></td>
                                        <td class="w-auto vertical-align-middle">{{ $u->email }}<span class="d-inline-block d-xl-none pe-3">,</span></td>
                                        <td class="w-auto text-wrap vertical-align-middle">{{ucwords(strtolower($u->companies))}}<span class="d-inline-block d-xl-none pe-3">,</span></td>
                                        <td class="w-auto text-center vertical-align-middle">{{$u->states}}</td>
                                        <td class="table-action px-lg-2 vertical-align-middle">
                                            @if ( !is_null($u->cert) )
                                                <a class="btn btn-small btn-warning w-100" href="{{ route('userDetail', $u->id) }}">
                                                    <strong>{{$u->cert}}</strong>
                                                </a>
                                            @elseif ( !is_null($u->training_done) )
                                                <a class="btn btn-small btn-success w-100" href="{{ route('userDetail', $u->id) }}">
                                                    <strong>CERTIFY</strong>
                                                </a>
                                            @else
                                                <a class="btn btn-small btn-tertiary w-100" href="{{ route('userDetail', $u->id) }}">
                                                    VIEW
                                                </a>
                                            @endif
                                        </td>
                                        @foreach($modules as $m)
                                            <td class="text-center vertical-align-middle">
                                                @php
                                                    $mod_id = 'module_' . sprintf("%02d", $m->id);
                                                @endphp
                                                <div class="step-name">{{$m->id}}</div>
                                                <div style="height:18px; width:18px;" class="d-inline-block mx-auto rounded-circle vertical-align-middle
                                                    @if ( is_null( $u->$mod_id ) )
                                                        opacity-0 d-inline-block
                                                    @elseif ( $u->$mod_id < 75 )
                                                        bg-red
                                                    @else
                                                        bg-green
                                                    @endif
                                                "></div>
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </table>

                            {{ $users->links() }}
                            @endif
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
    <script
        src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo="
        crossorigin="anonymous"></script>
    <script>
        setTimeout(()=>{
            $('#MyTabs a').on('click', function (e) {
                e.preventDefault()
                $(this).tab('show')
            });
        }, 500);

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
