@extends('layouts.admin')
@section('title')
    Admin Dashboard
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="row">
                    <div class="col-lg-3 col-xl-2 py-3 p-3 pe-lg-2">
                        <div class="p-3 bg-white border border-r-6">
                            <h2 class="h4">User Stats</h2>
                            {{-- SEARCH FORM --}}
                            <div id="search_field" class="d-flex form-control mb-3 p-0 ps-1 focus-0 justify-content-end">
                                <form method="get" action="{{ route('adminDashboard') }}" class="w-100 d-flex">
                                    <input id="user_search" name="search" type="text" class="w-100 flex-grow-1 border-0" placeholder="Search users&hellip;" value="@if (!empty($_GET['search'])){{ $_GET['search'] }}@endif">
                                    <button type="submit" class="btn px-1 m-0"><i class="fa-solid fa-magnifying-glass"></i></button>
                                </form>
                                <form method="get" action="{{ route('adminDashboard') }}">
                                    <input type="hidden" name="search" value="">
                                    <button type="submit" class="btn pe-2 ps-1 m-0"><i class="fa-solid fa-times"></i></button>
                                </form>
                            </div>
                            {{-- ALL INSTALLERS --}}
                            <form class="block" action="{{ route('adminDashboard') }}" method="get">
                                @if ( !empty($_GET['search']))
                                <input type="hidden" name="search" value="{{$_GET['search']}}">
                                @endif
                                <input type="hidden" name="filter" value="all">
                                <button type="submit" class="d-block border-0 bg-transparent p-0 w-100 text-start">
                                    <span class="row m-0 @if( empty($_GET['filter']) || $_GET['filter'] === 'all' ) border border-primary @else border-bottom @endif">
                                        <span class="p-2 col-6 border-bottom">
                                            Total users:
                                        </span>
                                        <span class="p-2 col-6 border-bottom">
                                            {{ $stats['total'] }}
                                            <span class="float-right align-middle rounded border-0 bg-light-mid-gray px-1">
                                                <i class="fa-solid fa-magnifying-glass"></i>
                                            </span>
                                        </span>
                                    </span>
                                </button>
                            </form>
                            {{-- CERTIFIED INSTALLERS --}}
                            <form class="block" action="{{ route('adminDashboard') }}" method="get">
                                @if ( !empty($_GET['search']))
                                    <input type="hidden" name="search" value="{{$_GET['search']}}">
                                @endif
                                <input type="hidden" name="filter" value="certified">
                                <button type="submit" class="d-block border-0 bg-transparent p-0 w-100 text-start">
                                    <span class="row m-0 @if( !empty( $_GET['filter'] ) && $_GET['filter'] === 'certified' ) border border-primary @else border-bottom @endif">
                                        <span class="p-2 col-6">
                                            Certified:
                                        </span>
                                        <span class="p-2 col-6">
                                            {{ $stats['certs'] }}
                                            <div class="float-right align-middle rounded border-0 bg-light-mid-gray px-1">
                                                <i class="fa-solid fa-magnifying-glass"></i>
                                            </div>
                                        </span>
                                    </span>
                                </button>
                            </form>
                            {{-- READY TO BE CERTIFIED --}}
                            <form class="block" action="{{ route('adminDashboard') }}" method="get">
                                @if ( !empty($_GET['search']))
                                    <input type="hidden" name="search" value="{{$_GET['search']}}">
                                @endif
                                <input type="hidden" name="filter" value="finished">
                                <button type="submit" class="d-block border-0 bg-transparent p-0 w-100 text-start">
                                    <span class="row m-0 @if( !empty( $_GET['filter'] ) && $_GET['filter'] === 'finished' ) border border-primary @else border-bottom @endif">
                                        <span class="p-2 col-6">
                                            Cert ready:
                                        </span>
                                        <span class="p-2 col-6">
                                            {{ $stats['finished'] }}
                                            <div class="float-right align-middle rounded border-0 bg-light-mid-gray px-1">
                                                <i class="fa-solid fa-magnifying-glass"></i>
                                            </div>
                                        </span>
                                    </span>
                                </button>
                            </form>
                            {{-- IN PROGRESS --}}
                            <form class="block" action="{{ route('adminDashboard') }}" method="get">
                                @if ( !empty($_GET['search']))
                                    <input type="hidden" name="search" value="{{$_GET['search']}}">
                                @endif
                                <input type="hidden" name="filter" value="unfinished">
                                <button type="submit" class="d-block border-0 bg-transparent p-0 w-100 text-start">
                                    <span class="row m-0 @if( !empty( $_GET['filter'] ) && $_GET['filter'] === 'unfinished' ) border border-primary @else border-bottom @endif">
                                        <span class="p-2 col-6">
                                            In progress:
                                        </span>
                                        <span class="p-2 col-6">
                                            {{ $stats['unfinished'] }}
                                            <div class="float-right align-middle rounded border-0 bg-light-mid-gray px-1">
                                                <i class="fa-solid fa-magnifying-glass"></i>
                                            </div>
                                        </span>
                                    </span>
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
                                    <span class="row m-0 @if( !empty( $_GET['filter'] ) && $_GET['filter'] === 'rsm' ) border border-primary @else border-bottom @endif">
                                        <span class="p-2 col-6">
                                            RSM Assigned:
                                        </span>
                                        <span class="p-2 col-6">
                                            {{ $stats['rsm'] }}
                                            <div class="float-right align-middle rounded border-0 bg-light-mid-gray px-1">
                                                <i class="fa-solid fa-magnifying-glass"></i>
                                            </div>
                                        </span>
                                    </span>
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                    <div class="col-lg-9 col-xl-10 pt-0 px-3 pb-3 p-lg-3 ps-lg-2">
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

                            <div class="admin-table mb-3">
                                <div class="row th-header justify-content-end">
                                    <div class="d-none d-xl-block col-xl-3">Modules</div>
                                </div>
                                <div class="row th-header border-bottom align-items-center">
                                    <div class="d-none d-xl-block col-xl-9">
                                        <div class="row">
                                            <div class="col-2">First name</div>
                                            <div class="col-2">Last Name</div>
                                            <div class="col-3">Email</div>
                                            <div class="col-2">Company</div>
                                            <div class="col-1 text-center">State</div>
                                            <div class="col-2 text-center">Cert</div>
                                        </div>
                                    </div>
                                    <div class="d-none d-xl-block col-xl-3">
                                        <div class="row">
                                            @foreach($modules as $m)
                                                <div class="col p-0 text-center">{{ $m->id }}</div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                @foreach($users as $u)
                                    <a href="{{ route('userDetail', $u->id) }}" class="row user-line" id="{{$u->first_name}}_{{$u->last_name}}_{{$u->companies}}_{{$u->id}}">
                                        <div class="col-xl-9">
                                            <div class="d-xl-flex">
                                                <div class="d-inline-block col-xl-2 name vertical-align-middle">{{ucwords(strtolower($u->first_name))}}<span class="d-inline-block d-xl-none pe-1"></span></div>
                                                <div class="d-inline-block col-xl-2 name vertical-align-middle">{{ucwords(strtolower($u->last_name))}}<span class="d-inline-block d-xl-none pe-2"></span></div>
                                                <div class="d-inline-block col-xl-3 overflow-hidden text-nowrap vertical-align-middle">{{ $u->email }}<span class="d-inline-block d-xl-none pe-2">,</span></div>
                                                <div class="d-inline-block col-xl-2 overflow-hidden text-nowrap vertical-align-middle">{{ucwords(strtolower($u->companies))}}<span class="d-inline-block d-xl-none pe-2">,</span></div>
                                                <div class="d-inline-block col-xl-1 text-center vertical-align-middle">{{$u->state}}</div>
                                                <div class="d-block col-xl-2 py-2 py-xl-0">
                                                    @if ( !is_null($u->cert) )
                                                        <button class="btn btn-small btn-warning w-100">
                                                            <strong>{{$u->cert}}</strong>
                                                            @if (!empty($u->cert_date))
                                                                <span class="mx-2">
                                                                    ({{ date("m/d/y", strtotime($u->cert_date)) }})
                                                                </span>
                                                            @endif
                                                        </button>
                                                    @elseif ( !is_null($u->training_done) )
                                                        <button class="btn btn-small btn-success w-100">
                                                            <strong>CERTIFY</strong>
                                                        </button>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-3">
                                            <div class="row">
                                                @foreach($modules as $m)
                                                    <div class="module-container col p-0 text-center vertical-align-middle py-2 py-xl-0">
                                                        @php
                                                            $mod_id = 'module_' . sprintf("%02d", $m->id);
                                                        @endphp
                                                        <div class="module-dot d-inline-block mx-auto rounded-circle vertical-align-middle
                                                            @if ( is_null( $u->$mod_id ) )
                                                                opacity-0 d-inline-block
                                                            @elseif ( $u->$mod_id < 75 )
                                                                bg-red
                                                            @else
                                                                bg-green
                                                            @endif
                                                        "><div class="step-name">{{$m->id}}</div></div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            </div>

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
