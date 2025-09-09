@extends('layouts.app')

@section('content')
<div class="row m-0 py-2" style="background:#333; color:white">
        <div class="col-12">
            <div class="row">
                <div class="col-12">
                    <h1 class="h4 mb-0 p-0">
                        {{ $user->first_name . ' ' . $user->last_name }}
                        @if ( $user->cert != null)
                        <span class="border border-info text-center p-2 ms-2 d-inline-block lh-1" style="font-size:.625em; border-radius:4px;">
                            <strong>#{{ $user->cert }}</strong>
                        </span>
                        @elseif ( $activity->training_done != null )
                        <div class="border border-info text-center p-2 ms-2 d-inline-block lh-1" style="font-size:.625em; border-radius:4px;">
                            Training complete. You will be contacted by a Lion Energy representative to finalize your certification.
                        </div>
                        @endif
                        <a href="/info" class="btn btn-outline-light btn-sm float-right">&#9998;</a>
                    </h1>
                </div>
            </div>
            @if ( !empty($user->address) && !empty($user->city) && !empty($user->state) && !empty($user->zip))
            <div>
                Address: {{ $user->address }}, {{ $user->city }}, {{ $user->state }} {{ $user->zip }}
            </div>
            @endif
            <div class="d-flex">
                <div class="flex-fill">
                    Phone number: {{ $user->phone }}
                </div>
                <div class="flex-fill">
                    Email: {{ $user->email }}
                </div>
                <div class="flex-fill">
                    Company: {{ $user->companies }}
                </div>
                <div class="flex-fill">
                    State: {{ $user->state }}
                </div>
                <div class="flex-fill">
                    Rep:
                    @if (!empty($admin))
                        {{ $admin->first_name }} {{ $admin->last_name }}
                    @else
                        N/A
                    @endif
                </div>
            </div>
        </div>
</div>
<div class="bg-med-gray p-2 text-center">
    <a href="{{ asset('docs/Sanctuary_Installation_Guide-100424.pdf') }}" class="btn btn-small btn-secondary mx-2" target="_blank">FULL INSTALLATION GUIDE</a>
    <a href="{{ asset('docs/LION - Sanctuary Warranty_10-1-24.pdf') }}" class="btn btn-small btn-secondary mx-2" target="_blank">WARRANTY DOCUMENTS</a>
</div>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-9 mb-3 mb-md-0">
            <div class="row">
                <div class="col-12">
                    <h2 class="h5">Training Steps</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <ul class="nav nav-tabs" id="versionTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button type="button" class="nav-link active" id="version_2" data-bs-toggle="tab" data-bs-target="#modules_2">Sanctuary 2</button>
                        </li>
                        <li class="nav-item">
                            <button type="button" class="nav-link" id="version_3" data-bs-toggle="tab" data-bs-target="#modules_3">Sanctuary 3</button>
                        </li>
                    </ul>
                    <div class="tab-content" id="homeTabContent">
                        @foreach ($activity as $act)
                        <div class="tab-pane fade show @if ( $act['version_num'] === 2 ) active @endif" id="modules_@php echo $act['version_num'] @endphp" role="tabpanel" aria-labelledby="version_@php echo $act['version_num'] @endphp">
                            <table class="table d-sm-table table-tile">
                                <tr class="d-none d-sm-table-row">
                                    <th class="d-block d-sm-table-cell" scope="col">Step</th>
                                    <th class="d-block d-sm-table-cell" scope="col">Status</th>
                                    <th class="d-block d-sm-table-cell" scope="col">Section</th>
                                    <th class="d-block d-sm-table-cell" scope="col">Title</th>
                                    <th class="d-block d-sm-table-cell" scope="col">Results</th>
                                    <th class="d-block d-sm-table-cell" scope="col">Actions</th>
                                </tr>
                                    @foreach ($modules[$act['version_num']] as $m)
                                        @php
                                            $perc = 0;
                                            $prev_id = ( $m['step']-1 <= 1 ) ? 1 : $m['step']-1;
                                            $mod_prev = ( $m['step']-2 <= 0 ) ? 0 : $m['step']-2;

                                            // Variables to call items
                                            $module_id = 'module_' . sprintf('%02d', $m['step']);
                                            $module_prev = 'module_' . sprintf('%02d', $prev_id);
                                            $module_date = $module_id . '_date';
                                        @endphp
                                        @if ( !is_null($activity[$act['version_num']]) )
                                            <tr class="d-block mb-3 mb-sm-0 d-sm-table-row p-2 p-sm-2
                                                @if ( $m['step'] > 1 && ( $activity[$act['version_num']][$module_prev] == null || $activity[$act['version_num']][$module_prev] < 100 ) )
                                                    disabled
                                                @endif

                                                @if ( $m['step'] === 1 && ( is_null( $activity[$act['version_num']][$module_id] )|| $activity[$act['version_num']][$module_id] < 100 ) )
                                                    focus
                                                @elseif (
                                                    $m['step'] > 1 &&
                                                    $activity[$act['version_num']][$module_id] < 100 &&
                                                    $activity[$act['version_num']][$module_prev] == 100
                                                )
                                                    focus
                                                @endif
                                           ">
                                                <td class="d-block d-sm-table-cell text-center p-1 p-sm-2 table-tile-header">
                                                    <span class="d-sm-none d-inline">Step </span>
                                                    {{ $m['step'] }}
                                                </td>
                                                <td class="d-flex d-sm-table-cell p-1 p-sm-2">
                                                    <div class="d-sm-none text-right w-33 pe-2"><strong>Status:</strong></div>
                                                    <div>
                                                        @if ( $activity[$act['version_num']][$module_id] === 100 )
                                                            <span class="badge badge-success d-inline-block w-sm-100 ms-2 ms-sm-0 vertical-align-middle">Complete</span>
                                                        @elseif ( $activity[$act['version_num']][$module_id] > 0 && $activity[$act['version_num']][$module_id] < 100 )
                                                            <span class="badge badge-danger d-inline-block w-sm-100 ms-2 ms-sm-0 vertical-align-middle">Retake</span>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td class="d-flex d-sm-table-cell p-1 p-sm-2">
                                                    <div class="d-sm-none w-33 text-right pe-2"><strong>Section:</strong></div>
                                                    <div>
                                                        {{ ucwords($m['section']) }}
                                                    </div>
                                                </td>
                                                <td class="d-flex d-sm-table-cell p-1 p-sm-2">
                                                    <div class="d-sm-none w-33 text-right pe-2"><strong>Title:</strong></div>
                                                    <div class="d-block">
                                                        {{ $m['title'] }}
                                                    </div>
                                                </td>
                                                <td class="d-flex d-sm-table-cell p-1 p-sm-2">
                                                    <div class="d-sm-none d-block w-33 text-right pe-2"><strong>Results:</strong></div>
                                                    <div class="d-block">
                                                        @if ( $activity[$act['version_num']][$module_id] !== null )
                                                            @if ( $questions[$m['id']] > 0)
                                                                @php $perc = round( $answers[$m['id']] / $questions[$m['id']] * 100, 2); @endphp
                                                                @if ( $perc < 75 )
                                                                    <span class="text-danger"><strong>
                                                                @endif
                                                                {{ $answers[$m['id']] }} /
                                                                 {{ $questions[$m['id']] }} correct
                                                                ({{ $perc }}%)
                                                                @if ( $perc < 75 )
                                                                    </strong></span>
                                                                @endif
                                                            @else
                                                                Quiz restarted. Please continue.
                                                            @endif
                                                        @endif
                                                    </div>
                                                </td>
                                                <td class="d-flex d-sm-table-cell p-1 p-sm-2">
                                                    <div class="d-sm-none d-block w-33 text-right pe-2"><strong>Actions:</strong></div>
                                                    <div class="d-block w-67 w-sm-100">
                                                        @if ( $activity[$act['version_num']][$module_id] !== null )
                                                        <a href="/modules/{{ $m['id'] }}" class="btn btn-tertiary btn-small d-inline-block d-sm-block my-sm-2 my-xxl-0 d-xxl-inline-block me-2 me-sm-0 me-xxl-2 text-nowrap
                                                            @if ($perc < 100)
                                                                btn-danger
                                                            @endif
                                                        ">Replay Video</a>
                                                        <form action="/modules/@php echo sprintf('%02d', $m['id']); @endphp/restart" method="post" class="me-2 me-sm-0 me-xxl-2 my-sm-2 my-xxl-0 d-inline-block d-sm-block d-xxl-inline-block">
                                                            @csrf
                                                            <input type="hidden" id="module_id" name="module_id" value="{{ $m['id'] }}">
                                                            <input type="hidden" name="_method" value="PUT">
                                                            <button type="submit" class="btn btn-tertiary btn-small w-100 text-nowrap
                                                            @if ($perc < 100)
                                                                btn-danger
                                                            @endif
                                                            ">Restart Quiz</button>
                                                        </form>
                                                        @elseif (
                                                            ( $activity[$act['version_num']][$module_id] == null && !is_null( $activity[$act['version_num']][$module_prev] ) && $activity[$act['version_num']][$module_prev] === 100 )
                                                        )
                                                            <a class="btn btn-primary btn-small w-100" href="/modules/{{ $m['id'] }}">Watch Video &amp; Take Quiz</a>
                                                        @endif
                                                    </div>
                                               {{-- Last Module --}}
{{--                                                @if ( $m['step'] == count($modules[$act['version_num']]) )--}}
{{--                                                    @if ( $activity[$act['version_num']][$module_prev] !== null )--}}
{{--                                                        @if ( $activity->review_end != null )--}}
{{--                                                            <div class="alert alert-secondary mt-3 mb-0 p-2">--}}
{{--                                                                @if ( $activity->review_06_admin_request == null)<button onclick="changeDateModal(6)" class="float-right btn btn-tertiary btn-small h-100">Change</button>@endif--}}
{{--                                                                <strong>Site inspection appt:</strong><br />{{ date("M d, Y @ h:i A", strtotime($activity->review_06)) }}--}}

{{--                                                                @if ($activity->review_06_user_request != null)--}}
{{--                                                                    <p class="mb-0"><small class="color-red-medium">Your requested change: {{ date('M d, Y @ h:i A', strtotime($activity->review_06_user_request)) }}</small></p>--}}
{{--                                                                @elseif ( $activity->review_06_admin_request != null )--}}
{{--                                                                    <p>--}}
{{--                                                                        <small class="color-red-medium">Lion Energy&rsquo;s requested change:<br />--}}
{{--                                                                            {{ date('M d, Y @ h:i A', strtotime($activity->review_06_admin_request)) }}--}}
{{--                                                                        </small>--}}
{{--                                                                    </p>--}}
{{--                                                                    <div class="mb-0">--}}
{{--                                                                        <form action="{{ route('userStep6Accept') }}" method="post">--}}
{{--                                                                            @csrf--}}
{{--                                                                            <input type="hidden" name="new_datetime" value="{{ $activity->review_06_admin_request }}">--}}
{{--                                                                            <button type="submit" class="btn btn-small btn-primary">Accept</button>--}}
{{--                                                                            <button type="button" onclick="changeDateModal(6)" class="btn btn-tertiary btn-small ms-2">Suggest New Date/Time</button>--}}
{{--                                                                        </form>--}}
{{--                                                                    </div>--}}
{{--                                                                @endif--}}
{{--                                                            </div>--}}
{{--                                                        @endif--}}
{{--                                                    @endif--}}
{{--                                                @endif--}}
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                            </table>
                        </div>
                        @endforeach
                    </div>

                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="row">
                <div class="col-12">
                    <button type="button" id="AddNotesBtn" onclick="newNote()" class="float-right btn btn-tertiary btn-small d-inline-block w-auto float-right">Add</button>
                    <h2 class="h5">Messages</h2>
                </div>
            </div>
            <div id="msg_parent" style="position:relative; height:100%; border:1px solid rgba(0,0,0,.1); overflow-y:auto; overflow-x:clip;">
                <div id="msg_content" class="position-absolute px-2 w-100">
                    @if( count($messages) > 0 )
                        @foreach($messages as $msg)
                            <div class="row mb-2 p-0 align-items-end">
                                <div class="col-11 @if($msg->admin_id == null) offset-1 @endif">
                                    <div class="note-bubble @if($msg->admin_id !== null) admin @endif">
                                        <div class="small-steps mb-1">
                                            {{ date('M d, Y', strtotime($msg->created_at)) }} @
                                            {{ date('h:i:s A', strtotime($msg->created_at)) }}
                                        </div>
                                            @php echo html_entity_decode($msg->message) @endphp
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="row">
                            <div class="col-12">
                                <p class="mt-5 msg-no-notes">No messages yet</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
<div id="overlay_bg" onclick="newNote()"></div>
<div id="overlay_content" class="yyz-card p-3">
    <form action="{{ route('add-message') }}" method="post" onsubmit="processingOverlay()">
        <label for="message" class="d-block no-style p-0">New Message</label>
        <textarea name="message" id="message" class="note"></textarea>
        @csrf
        <input type="hidden" name="user_id" value="{{ $user->id }}">
        <button type="submit" class="btn btn-primary my-3">Add Note</button>
    </form>
</div>

{{--<div id="change_date_overlay" class="overlay_bg" onclick="changeDateModal()"></div>--}}
{{--<div id="change_date_content" class="overlay_content yyz-card p-4" style="position:fixed; left:50%; top: 50%; transform:translate(-50%, -50%); width:400px;">--}}
{{--    <form id="change_date_form" action="{{ route('userStep3Change') }}" method="post" onsubmit="processingOverlay()">--}}
{{--        <h3 class="h4">Propose New Date &amp; Time</h3>--}}
{{--        <div class="row mb-3">--}}
{{--            <div class="col-6">--}}
{{--                <label class="pt-0 pb-2 px-0" for="date">Date</label>--}}
{{--                <input type  = "date"--}}
{{--                       name  = "date"--}}
{{--                       id    = "date"--}}
{{--                       class = "w-100"--}}
{{--                       value = @if ( $activity->review_06_user_request != null ){{ date("Y-m-d", strtotime($activity->review_06_user_request)) }}@elseif ( $activity->review_06 != null ){{ date("Y-m-d", strtotime($activity->review_06 ) ) }}@endif--}}
{{--                >--}}
{{--            </div>--}}
{{--            <div class="col-6">--}}
{{--                <label class="pt-0 pb-2 px-0" for="time">Time</label>--}}
{{--                <input type="time"--}}
{{--                       name="time"--}}
{{--                       id="time"--}}
{{--                       class="w-100"--}}
{{--                       value = @if ( $activity->review_06_user_request != null ){{ date("H:i", strtotime($activity->review_06_user_request)) }}@elseif ( $activity->review_06 != null ){{ date("H:i", strtotime($activity->review_06 ) ) }}@endif--}}
{{--                >--}}
{{--            </div>--}}
{{--        </div>--}}
{{--        @csrf--}}
{{--        <button type="submit" class="btn btn-primary">Submit</button>--}}
{{--    </form>--}}
{{--</div>--}}

<div id="main_overlay_bg" class="overlay_bg"></div>
<div id="main_overlay_content" class="overlay_content text-center">
    <div class="flashing-bullet">&bull;</div>
    <div class="flashing-bullet">&bull;</div>
    <div class="flashing-bullet">&bull;</div>
</div>
@endsection

@section('scripts')
    <script>
        function newNote() {
            document.getElementById('overlay_bg').classList.toggle('show');
            document.getElementById('overlay_content').classList.toggle('show');
            if ( document.getElementById('overlay_bg').classList.contains('show') ) {
                document.getElementById('message').focus();
            }
            else {
                document.getElementById('msg_note').value = '';
            }
        }
        {{--
        function changeDateModal(e) {
            let db1, db2, db3, date, time;
            if ( e === 3 ) {
                db1 = '{{ strtotime($activity->review_03) }}';
                db2 = '{{ strtotime($activity->review_03_user_request) }}';
                db3 = '{{ strtotime($activity->review_03_admin_request) }}';
                if ( db2 > 0 ) {
                    date = '{{ date("Y-m-d", strtotime($activity->review_03_user_request)) }}';
                    time = '{{ date("H:i", strtotime($activity->review_03_user_request)) }}';
                }
                if ( db3 > 0 ) {
                    date = '{{ date("Y-m-d", strtotime($activity->review_03_admin_request)) }}';
                    time = '{{ date("H:i", strtotime($activity->review_03_admin_request)) }}';
                }
                else if ( db1 > 0 ) {
                    date = '{{ date("Y-m-d", strtotime($activity->review_03)) }}';
                    time = '{{ date("H:i", strtotime($activity->review_03)) }}';
                }
            }
            else if ( e === 6 ) {
                db1 = '{{ strtotime($activity->review_06) }}';
                db2 = '{{ strtotime($activity->review_06_user_request) }}';
                db3 = '{{ strtotime($activity->review_06_admin_request) }}';
                if ( db2 > 0 ) {
                    date = '{{ date("Y-m-d", strtotime($activity->review_06_user_request)) }}';
                    time = '{{ date("H:i", strtotime($activity->review_06_user_request)) }}';
                }
                else if ( db3 > 0 ) {
                    date = '{{ date("Y-m-d", strtotime($activity->review_06_admin_request)) }}';
                    time = '{{ date("H:i", strtotime($activity->review_06_admin_request)) }}';
                }
                else if ( db1 > 0 ) {
                    date = '{{ date("Y-m-d", strtotime($activity->review_06)) }}';
                    time = '{{ date("H:i", strtotime($activity->review_06)) }}';
                }
            }

            const link = ( e === 3 ) ? '{{ route('userStep3Change') }}' : '{{ route('userStep6Change') }}';

            if ( date !== undefined && time !== undefined ) {
                document.getElementById('date').value = date;
                document.getElementById('time').value = time;
            }
            document.getElementById('change_date_form').setAttribute('action', link);

            document.getElementById('change_date_overlay').classList.toggle('show');
            document.getElementById('change_date_content').classList.toggle('show');
        }

        --}}
        function processingOverlay() {
            document.getElementById('main_overlay_bg').classList.toggle('show');
            document.getElementById('main_overlay_content').classList.toggle('show');

            document.getElementById('overlay_bg').classList.remove('show');
            document.getElementById('overlay_content').classList.remove('show');
        }

        setInterval(function() {
            const bullets = document.querySelectorAll('.flashing-bullet');
            let bF = 0;
            for( let i = 0; i < bullets.length; i++) {
                if ( bullets[i].classList.contains('focus') ) {
                    bF = i + 1;
                    if ( bF >= bullets.length ) {
                        bF = 0;
                    }
                    bullets[i].classList.remove('focus');
                }
            }
            bullets[bF].classList.add('focus');
        }, 400);

        setTimeout( function() {
            m_list = document.getElementById('msg_parent');
            m_height = document.getElementById('msg_content').offsetHeight;
            m_list.scrollTop += m_height;

            const cert_btn = document.getElementById('btn_request_cert');
            if ( cert_btn !== null) {
                cert_btn.addEventListener('click', () => {
                    document.getElementById('main_overlay_bg').classList.add('show');
                    document.getElementById('main_overlay_content').classList.add('show');
                });
            }
        }, 200);


    </script>
@endsection
