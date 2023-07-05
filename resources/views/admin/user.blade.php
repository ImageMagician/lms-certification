@extends('layouts.admin')
@section('title')
    User Detail : {{ $user->name }}
@endsection

@section('content')

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-xl-10">
                <h1 class="h2 clearfix">
                    User Detail : {{ $user->name }}
                    <a href="{{ route('adminDashboard') }}" class="btn btn-tertiary float-right">Admin Dashboard</a>
                </h1>
                <div class="row mb-4">
                    <div class="col-sm-6">
                        <div class="py-1 border-bottom-light">Phone: {{ $user->phone }}</div>
                        <div class="py-1 border-bottom-light">Email: <a href="mailto:{{ $user->email }}">{{ $user->email }}</a></div>
                    </div>
                    <div class="col-sm-6">
                        <div class="py-1 border-bottom-light">States certified: {{ $user->states }}</div>
                        <div class="py-1 border-bottom-light">Companies affiliated: {{ $user->companies }}</div>
                    </div>
                </div>
                @if($user->cert)
                    <div class="row justify-content-center mb-3">
                        <div class="col-md-5">
                            <div class="border border-info p-3 text-center" style="border-radius:6px;">
                                <span style="font-size:24px">Certification ID: <strong>{{ $user->cert }}</strong></span>
                            </div>
                        </div>
                    </div>
                @elseif ($activity->$mod_last != null)
                    <div class="row justify-content-center mb-3">
                        <div class="col-12">
                            <div class="alert alert-info p-3 text-center">
                                <p>
                                    This user has completed all the training courses. Please review their results and contact them if necessary. Otherwise, click "certify user" if they are approved.
                                </p>
                                <a href="{{ route('userDetailStep', ['id' => $user->id, 'step' => $m_count]) }}" class="btn btn-primary">Certify User</a>
                            </div>
                        </div>
                    </div>
                @endif
                @if(Session::has('success'))
                    <div class="alert alert-success mt-2">
                        <p class="mb-0">{{ Session::get('success') }}</p>
                    </div>

                @elseif( Session::has('error'))
                    <div class="alert alert-warning mt-2">
                        <p class="mb-0">{{ Session::get('error') }}</p>
                    </div>
                @endif
                <div class="row">
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-12">
                                <h3 class="h5">
                                    Training Steps
                                </h3>
                            </div>
                        </div>
                       <div class="row mb-3 mb-md-4">
                           @foreach ($modules as $m)
                            <div class="col-md-6 p-2">
                                <div class="yyz-card p-3 h-100">
                                    <div class="mb-2">
                                        <small>
                                            Step {{ $m->id }}
                                        </small>
                                        @php
                                            $mod_id = 'module_' . sprintf('%02d', $m->id);;
                                        @endphp
                                        @if ( $activity->$mod_id !== null )
                                            <span class="badge badge-success float-right">Complete</span>
                                        @endif
                                    </div>
                                    <h3 class="h5 strong mb-3">
                                        {{ $m->title }}
                                    </h3>
                                    @if ( $activity->$mod_id !== null)
                                    Quiz: {{$answers[$m->id]}} / {{$questions[$m->id]}} correct (@php echo round( $answers[$m->id] / $questions[$m->id] * 100, 2 ) . '%'; @endphp).
                                        @if ( $m->id == count($modules) && $user->cert == null)
                                            <div id="review_date_form" class="show">
                                                <p class="mt-3">
                                                    Contact installer to set up on-site inspection appointment.
                                                </p>
                                                <p>
                                                    Phone: {{ $user->phone }}<br />
                                                    Email: <a href="mailto:{{ $user->email }}">{{ $user->email }}</a>
                                                </p>
                                                <a href="{{ route('userDetailStep', ['id' => $user->id, 'step' => $m->id]) }}" class="btn btn-primary">Certify User</a>
                                            </div>
                                        @endif
                                    @else
                                        <p>Not started</p>
                                    @endif
                                </div>
                            </div>
                           @endforeach
                         </div>
                    </div>
                    <div class="col-md-4">
                        <div class="row">
                            <div class="col-12">
                                <button type="button" id="AddNotesBtn" onclick="newMsg()" class="btn btn-tertiary btn-small float-right w-auto">Add</button>
                                <h3 class="h5">
                                    Messages
                                </h3>
                            </div>
                        </div>
                        <div id="msg_parent">
                            <div id="msg_content">
                                @if( count($messages) > 0 )
                                    @foreach($messages as $msg)
                                        <div class="row mb-2 p-0 align-items-end">
                                            <div class="col-11 @if($msg->admin_id !== null) offset-1 @endif">
                                                <div class="note-bubble @if($msg->admin_id == null) admin @endif">
                                                    <div class="small-steps">
                                                        {{ date('M d, Y', strtotime($msg->created_at)) }} @
                                                        {{ date('h:i:s A', strtotime($msg->created_at)) }}
                                                    </div>
                                                    <p class="m-0">
                                                        @php echo html_entity_decode($msg->message) @endphp
                                                    </p>
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

    <div id="overlay_bg" onclick="newMsg()"></div>
    <div id="overlay_content" class="yyz-card p-3">
        <form action="{{ route('admin-msg') }}" method="post" onsubmit="processingOverlay()">
            <label for="message" class="d-block no-style p-0">Add Message</label>
            <textarea name="message" id="message" class="note w-100 p-2"></textarea>
            @csrf
            <input type="hidden" name="user_id" value="{{ $user->id }}">
            <input type="hidden" name="admin_id" value="{{ $admin->id }}">
            <button type="submit" class="btn btn-primary my-3">Add Message</button>
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
        function newMsg() {
            document.getElementById('overlay_bg').classList.toggle('show');
            document.getElementById('overlay_content').classList.toggle('show');
            if ( document.getElementById('overlay_bg').classList.contains('show') ) {
                document.getElementById('message').focus();
            }
        }

        function parseNote () {
            document.getElementById('note').innerHTML = document.querySelector('.ql-editor').innerHTML;
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
    </script>
@endsection
