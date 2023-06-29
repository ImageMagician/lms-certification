@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-xl-10">
            @if ( isset($error) )
                <div class="alert alert-success">
                    @if ( $update  == 'installation')
                    <p>The installation information has been successfully updated.</p>
                    @endif
                </div>
                @unset($update)
            @endif
            <div class="container mt-4">
                <div class="row">
                    <div class="col-12 mb-4">
                        <h1 class="h3">
                            {{ $user->name }}
                            <a href="/info" class="btn btn-secondary btn-sm float-right">Edit Info</a>
                        </h1>
                        <div class="row">
                            <div class="col-5">
                                <div class="py-1 border-bottom-light">Phone number: {{ $user->phone }}</div>
                                <div class="py-1 border-bottom-light">Email: {{ $user->email }}</div>
                            </div>
                            <div class="col-7">
                                <div class="py-1 border-bottom-light">Associated companies: {{ $user->companies }}</div>
                                <div class="py-1 border-bottom-light">State certifications: {{ $user->states }}</div>
                            </div>
                        </div>
                        @if ( $user->cert )
                        <div class="row justify-content-center">
                            <div class="col-md-5">
                                <div class="border border-info mt-4 p-3 text-center" style="border-radius:6px;">
                                    <span style="font-size:24px">Certification ID: <strong>{{ $user->cert }}</strong></span>
                                </div>
                            </div>
                        </div>
                        @elseif ( $activity->training_done == 1)
                            <div class="row justify-content-center">
                                <div class="col-12 text-center mt-3">
                                    <div class="alert alert-info p-2">
                                        <span style="font-size:18px">Training complete. You will be contacted by a Lion Energy representative to finalize your certification.</span>
                                    </div>
                                </div>
                            </div>
                        @else
                            @php $steps = 0; @endphp
                            @foreach( $modules as $m )
                                @php
                                    $act_step = 'module_' . sprintf('%02d', $m->id);
                                @endphp
                                @if ( $activity->$act_step !== null )
                                    @php $steps++; @endphp
                                @endif
                            @endforeach
                            @if ( $steps == count($modules) )
                            <div class="row justify-content-center">
                                <div class="col-12 text-center mt-3">
                                    <div class="alert alert-info p-2">
                                        <span style="font-size:18px">Training complete. Click here to be contacted by a Lion Energy representative to finalize your certification.</span><br />
                                        <a href="{{ route('request-cert') }}" class="btn btn-primary mt-2">Request Certification</a>
                                    </div>
                                </div>
                            </div>
                            @endif
                        @endif
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-md-8 mb-3 mb-md-0">
                        <div class="row">
                            <div class="col-12">
                                <h2 class="h5">Training Steps</h2>
                            </div>
                        </div>
                        <div class="row">
                        @foreach ($modules as $m)
                            @if ( $activity != null )
                                @php
                                    $prev_id = ( $m['id']-1 <= 1 ) ? 1 : $m['id']-1;
                                    $mod_prev = ( $m['id']-2 <= 0 ) ? 0 : $m['id']-2;

                                    // Variables to call items
                                    $module_id = 'module_' . sprintf('%02d', $m['id']);
                                    $module_prev = 'module_' . sprintf('%02d', $prev_id);
                                    $module_date = $module_id . '_date';

                                @endphp
                            @endif
                            <div class="col-md-6 pb-4 @if ($m->id > 1 && $activity->$module_prev == null) disabled @endif">
                                <div class="yyz-card pt-3 pe-3 pb-5 ps-3 h-100
                                    @if ( $m->id == 1 && $activity->$module_id == null )
                                        focus
                                    @elseif ( $m->id != 1 && $activity->$module_id == null && $activity->$module_prev !== null )
                                        focus
                                    @endif">
                                    <div class="pb-1">
                                        <small>
                                            Step {{ $m->id }}
                                        </small>
                                        @if ( $activity->$module_id !== null)
                                            <span class="badge badge-success float-right">Complete</span>
                                        @endif
                                   </div>
                                    <h3 class="h5 mb-3">
                                        {{ $m->title }}
                                    </h3>
                                    @if ( $activity->$module_id !== null )
                                        <p>
                                            Quiz:
                                            {{ $answers[$m->id] }} /
                                            {{ $questions[$m->id] }} correct
                                            (@php echo round( $answers[$m->id] / $questions[$m->id] * 100, 2) @endphp%)
                                        </p>
                                        <a href="/modules/{{ $m['id'] }}" class="btn btn-tertiary btn-small d-inline-block">Replay Video</a>
                                        <form action="/modules/@php echo sprintf("%02d", $m->id); @endphp/restart" method="post" class="ms-2 d-inline-block">
                                            @csrf
                                            <input type="hidden" id="module_id" name="module_id" value="{{ $m->id }}">
                                            <input type="hidden" name="_method" value="PUT">
                                            <button type="submit" class="btn btn-tertiary btn-small d-inline-block">Restart Quiz</button>
                                        </form>
                                    @endif

                                    @if ( $activity->$module_id !== null && $activity->$module_id < $m->passing_percentage)
                                        <div class="step-forms">
                                            <form action="/modules/@php echo sprintf("%02d", $m->id); @endphp/rewatch" method="post" class="d-inline-block">
                                                @csrf
                                                <input type="hidden" id="module_id" name="module_id" value="{{ $m->id }}">
                                                <input type="hidden" name="_method" value="PUT">
                                                <button type="submit" class="btn btn-tertiary me-1">Rewatch Video</button>
                                            </form>
                                            <form action="/modules/@php echo sprintf("%02d", $m->id); @endphp/restart" method="post" class="d-inline-block">
                                                @csrf
                                                <input type="hidden" id="module_id" name="module_id" value="{{ $m->id }}">
                                                <input type="hidden" name="_method" value="PUT">
                                                <button type="submit" class="btn btn-tertiary me-1">Restart Quiz</button>
                                            </form>
                                        </div>
                                    @elseif ( ( $activity->$module_id == null && $activity->$module_prev ) || ( $activity->$module_id == null && $m->id == 1))
                                        <a class="btn btn-primary" href="/modules/{{ $m['id'] }}">Watch Video &amp; Take Quiz</a>
                                    @endif

                                   {{-- Last Module --}}
                                    @if ( $m->id == count($modules) )
                                        @if ( $activity->$module_prev !== null )
                                            @if ( $activity->review_end != null )
                                                <div class="alert alert-secondary mt-3 mb-0 p-2">
                                                    @if ( $activity->review_06_admin_request == null)<button onclick="changeDateModal(6)" class="float-right btn btn-tertiary btn-small h-100">Change</button>@endif
                                                    <strong>Site inspection appt:</strong><br />{{ date("M d, Y @ h:i A", strtotime($activity->review_06)) }}

                                                    @if ($activity->review_06_user_request != null)
                                                        <p class="mb-0"><small class="color-red-medium">Your requested change: {{ date('M d, Y @ h:i A', strtotime($activity->review_06_user_request)) }}</small></p>
                                                    @elseif ( $activity->review_06_admin_request != null )
                                                        <p>
                                                            <small class="color-red-medium">Lion Energy&rsquo;s requested change:<br />
                                                                {{ date('M d, Y @ h:i A', strtotime($activity->review_06_admin_request)) }}
                                                            </small>
                                                        </p>
                                                        <p class="mb-0">
                                                            <form action="{{ route('userStep6Accept') }}" method="post">
                                                                @csrf
                                                                <input type="hidden" name="new_datetime" value="{{ $activity->review_06_admin_request }}">
                                                                <button type="submit" class="btn btn-small btn-primary">Accept</button>
                                                                <button type="button" onclick="changeDateModal(6)" class="btn btn-tertiary btn-small ms-2">Suggest New Date/Time</button>
                                                            </form>
                                                        </p>
                                                    @endif
                                                </div>
                                            @endif
                                        @else
                                            <div class="h4 text-center mt-4">
                                                Training Complete
                                            </div>
                                        @endif
                                    @endif

                                    @if ( $activity->$module_id !== null )
                                        <p class="small position-absolute bottom-0">
                                            Last Activity:
                                            @php echo date( "M d, Y", strtotime( $activity->$module_id ) ); @endphp
                                        </p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                        </div>
                    </div>
                    <div class="col-md-4">
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

<div id="change_date_overlay" class="overlay_bg" onclick="changeDateModal()"></div>
<div id="change_date_content" class="overlay_content yyz-card p-4" style="position:fixed; left:50%; top: 50%; transform:translate(-50%, -50%); width:400px;">
    <form id="change_date_form" action="{{ route('userStep3Change') }}" method="post" onsubmit="processingOverlay()">
        <h3 class="h4">Propose New Date &amp; Time</h3>
        <div class="row mb-3">
            <div class="col-6">
                <label class="pt-0 pb-2 px-0" for="date">Date</label>
                <input type  = "date"
                       name  = "date"
                       id    = "date"
                       class = "w-100"
                       value = @if ( $activity->review_06_user_request != null ){{ date("Y-m-d", strtotime($activity->review_06_user_request)) }}@elseif ( $activity->review_06 != null ){{ date("Y-m-d", strtotime($activity->review_06 ) ) }}@endif
                >
            </div>
            <div class="col-6">
                <label class="pt-0 pb-2 px-0" for="time">Time</label>
                <input type="time"
                       name="time"
                       id="time"
                       class="w-100"
                       value = @if ( $activity->review_06_user_request != null ){{ date("H:i", strtotime($activity->review_06_user_request)) }}@elseif ( $activity->review_06 != null ){{ date("H:i", strtotime($activity->review_06 ) ) }}@endif
                >
            </div>
        </div>
        @csrf
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>

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

        function changeDateModal(e) {
            let db1, db2, db3, date, time;
            if ( e == 3 ) {
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
            else if ( e == 6 ) {
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

            const link = ( e == 3 ) ? '{{ route('userStep3Change') }}' : '{{ route('userStep6Change') }}';

            if ( date != undefined && time != undefined ) {
                document.getElementById('date').value = date;
                document.getElementById('time').value = time;
            }
            document.getElementById('change_date_form').setAttribute('action', link);

            document.getElementById('change_date_overlay').classList.toggle('show');
            document.getElementById('change_date_content').classList.toggle('show');
        }

        function processingOverlay() {
            document.getElementById('main_overlay_bg').classList.toggle('show');
            document.getElementById('main_overlay_content').classList.toggle('show');
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
        }, 200);

    </script>
@endsection
