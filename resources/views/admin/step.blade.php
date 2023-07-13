@extends('layouts.admin')
@section('title')
    User Detail : {{ $user->name }}
@endsection
@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-9">
                <div>Step {{ session('step') }}</div>
                <h1 class="h2 mb-4">{{ $module->title }} : {{ $user->first_name }} {{ $user->last_name }}
                <a href="{{ route('userDetail', ['id'=>$user->id]) }}" class="btn btn-tertiary float-right">Return to Dashboard</a>
                </h1>
{{--  STEP 3 --}}
{{--                @if( session('step') == 3)
                    <div class="row my-3">
                        <div class="col-md-6 mb-4 mb-md-0">
                            <div class="yyz-card p-3 h-100">
                                <p>
                                    The following location address has been submitted for installation.
                                </p>
                                @if ( $activity->install_address !== null)
                                <p class="h3">
                                    <a href="//maps.google.com/maps?q={{ $activity->install_address }},{{ $activity->install_city }},%20{{ $activity->install_state }}%20{{ $activity->install_zip }}" target="_blank">
                                    {{ $activity->install_address }}<br />
                                    {{ $activity->install_city }}, {{ $activity->install_state }} {{ $activity->install_zip }}
                                    </a>
                                </p>
                                @php
                                    $gmaploc = str_replace(' ', '%20', $activity->install_address) . ',' . str_replace(' ' , '%20', $activity->install_city) . ',' . $activity->install_state . '%20' . $activity->install_zip;
                                @endphp
                                <div class="mapouter mt-3">
                                    <div class="gmap_canvas">
                                        <iframe
                                            width="100%"
                                            height="100%"
                                            id="gmap_canvas"
                                            src="https://maps.google.com/maps?q={{ $gmaploc }}&t=&z=16&ie=UTF8&iwloc=&output=embed"></iframe>
                                    </div>
                                </div>
                                @else
                                    <p class="h3">No address submitted</p>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="yyz-card p-3 mb-4">
                                <div><small>Step 3</small></div>
                                <h4>Install Location &amp; Documentation Review</h4>
                                @if ( session('success3') )
                                    <div class="alert alert-success px-2 py-1">
                                        Appointment updated.
                                    </div>
                                @elseif ( session('error3') )
                                    <div class="alert alert-danger px-2 py-1">
                                        Error updating appointment.
                                    </div>
                                @endif
                                <div id="step3set" class="@if ( $activity->review_03 != null ) d-none @endif">
                                    <form action="{{ route('apptSet') }}" method="post" onsubmit="processingOverlay()">
                                        <p>
                                            Schedule a call to review steps 3 and 4 with the user:
                                        </p>
                                        <p>
                                            {{ $user->phone }}<br />
                                            {{ $user->email }}
                                        </p>
                                        <fieldset>
                                            <label for="review_date" class="no-style p-0 pe-2">Appointment date:</label><br />
                                            <input type="date"
                                                   name="review_03_date"
                                                   class="me-2"
                                                   value="@if( strtotime($activity->review_03_user_request) > 0){{ date("Y-m-d", strtotime( $activity->review_03_user_request ) ) }}@elseif ( strtotime($activity->review_03) > 0){{ date("Y-m-d", strtotime( $activity->review_03 ) ) }}@endif"
                                            >
                                            <input type="time"
                                                   name="review_03_time"
                                                   value="@if ( strtotime($activity->review_03) > 0){{ date("H:i:s", strtotime( $activity->review_03 ) ) }}@endif"
                                            >
                                        </fieldset>
                                        <input type="hidden" name="module" value="{{ session('step') }}">
                                        @csrf
                                        <div class="mt-3">
                                            <button type="submit" class="btn btn-small btn-primary">Set Appointment Date/Time</button>
                                            <button type="button" class="btn btn-small btn-tertiary ms-2" onclick="step3appt()">Cancel</button>
                                        </div>
                                    </form>
                                </div>
                                <div id="step3planned" class="@if ( $activity->review_03 == null ) d-none @endif">
                                    Review date/time:
                                    <p class="h4">
                                        {{ date("M d, Y @ h:i A", strtotime( $activity->review_03 ) ) }}
                                    </p>
                                    @if ( $activity->review_03_user_request == null )
                                    <div>
                                        <button id="changeAppt" type="button" class="btn btn-small btn-tertiary btn-small" onclick="step3appt()">Change Appointment</button>
                                    </div>
                                    @else
                                    <div class="alert alert-danger mt-2 mb-0">
                                        {{ $user->name }} has requested a date/time change:<br/>
                                        {{ date('M d, Y @ h:i A', strtotime($activity->review_03_user_request)) }}
                                        <form action="{{ route('apptSet') }}" method="post" class="mt-2">
                                            <input type="hidden" name="review_03_date" value="{{ date('Y-m-d', strtotime($activity->review_03_user_request)) }}">
                                            <input type="hidden" name="review_03_time" value="{{ date('H:i', strtotime($activity->review_03_user_request)) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-primary btn-small">Accept Date/Time</button>
                                            <button id="changeAppt" type="button" class="btn btn-tertiary btn-small ms-2" onclick="step3appt()">Suggest Other Date/Time</button>
                                        </form>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            <div class="yyz-card p-3">
                                <div><small>Step 6</small></div>
                                <h4>Commissioning Inspection</h4>
                                <div id="step6set" class="@if( $activity->review_06 != null) d-none @endif">
                                    <form action="{{ route('final-inspect') }}" method="post" onsubmit="processingOverlay()">
                                        <p>
                                            If possible, schedule on-site inspection date now (optional)
                                        </p>
                                        <fieldset>
                                            <label for="review_date" class="no-style p-0 pe-2">Inspection date:</label><br />
                                            <input type="date"
                                                   name="review_06_date"
                                                   class="me-2"
                                                   value="@if ( strtotime($activity->review_06_user_request) > 0){{ date("Y-m-d", strtotime( $activity->review_06_user_request ) ) }}@elseif( strtotime($activity->review_06) > 0){{ date("Y-m-d", strtotime( $activity->review_06 ) ) }}@endif"
                                            >
                                            <input type="time"
                                                   name="review_06_time"
                                                   value="@if ( strtotime($activity->review_06_user_request) > 0){{ date("H:i", strtotime( $activity->review_06_user_request ) ) }}@elseif ( strtotime($activity->review_06) > 0){{ date("H:i", strtotime( $activity->review_06 ) ) }}@endif"
                                            >
                                        </fieldset>
                                        @csrf
                                        <div class="mt-3">
                                            <button type="submit" class="btn btn-small btn-primary">Set Inspection Date</button>
                                            <button type="button" class="btn btn-small btn-tertiary ms-2" onclick="step6appt()">Cancel</button>
                                        </div>
                                    </form>
                                </div>
                               <div id="step6planned" class="@if ( $activity->review_06 == null ) d-none @endif">
                                    Review date/time:
                                    <p class="h3">
                                        {{ date("M d, Y @ h:i A", strtotime( $activity->review_06 ) ) }}
                                    </p>
                                    @if ( $activity->review_06_user_request == null )
                                       <div>
                                           <button id="change6Appt" type="button" class="btn btn-tertiary btn-small" onclick="step6appt()">Change Appointment</button>
                                       </div>
                                    @else
                                       <div class="alert alert-danger mt-2 mb-0">
                                           {{ $user->name }} has requested a date/time change:<br/>
                                           {{ date('M d, Y @ h:i A', strtotime($activity->review_06_user_request)) }}
                                           <form action="{{ route('final-inspect') }}" method="post" class="mt-2">
                                               <input type="hidden" name="review_06_date" value="{{ date('Y-m-d', strtotime($activity->review_06_user_request)) }}">
                                               <input type="hidden" name="review_06_time" value="{{ date('H:i', strtotime($activity->review_06_user_request)) }}">
                                               @csrf
                                               <button type="submit" class="btn btn-primary btn-small">Accept Date/Time</button>
                                               <button id="change6Appt" type="button" class="btn btn-tertiary btn-small ms-2" onclick="step6change()">Suggest Other Date/Time</button>
                                           </form>
                                       </div>
                                    @endif
                                </div>
                            </div>
                    </div>
{{--  STEP 4  --
                @elseif ( session('step') == 4 )
                    <div class="row">
                        <div class="col-12 mb-3">
                            @if ( $activity->module_04 == 1)
                                <button class="btn btn-tertiary" disabled>Documents are Approved</button>
                            @else
                            <form action="{{ route("userDetailPost") }}" class="d-inline-block me-3" method="post" onsubmit="processingOverlay()">
                                @csrf
                                <input type="hidden" name="module" value="{{ $module->id }}">
                                <input type="hidden" name="user" value="{{ $user->id }}">
                                <button type="submit" class="btn btn-primary">Approve Documents</button>
                            </form>
                            @endif
                            <a href="{{ route('userDetail', ['id'=>$user->id]) }}" class="btn btn-tertiary">Return to Dashboard</a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 px-2">
                            <div class="yyz-card yyz-card-docs position-relative">
                                <h3 class="h4">Location Images</h3>
                                @php $dcount = 0; @endphp
                                @foreach( $docs as $d )
                                    @if ( $d->image_cat == 'image' && $d->module_id == $module->id )
                                        @php $dcount++; @endphp
                                    @endif
                                @endforeach
                                @if ( $dcount > 0 )
                                    <div id="loc_image_container" class="zoom-view mb-3">
                                        <button type="button" onclick="showAllImages()" class="close-x">&#10799;</button>
                                        @foreach ($docs as $d)
                                            @if ($d->image_cat == 'image' && $d->module_id == $module->id)
                                                <div class="doc-container d-inline-block border m-1">
                                                    <img src="{{ asset('/storage/' . $d['image_path']) }}" onclick="showAllImages()" width="80" alt="" class="cursor-pointer">
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                    <p class="mt-3"><small>Click any image to for a close-up view of all images.</small></p>
                                @else
                                    <div class="msg-no-docs">No images uploaded.</div>
                                @endif
                             </div>
                        </div>
                        <div class="col-md-4 px-2">
                            <div class="yyz-card yyz-card-docs">
                                <h3 class="h4">One-line</h3>
                                @php $dcount = 0; @endphp
                                @foreach( $docs as $d)
                                    @if ($d['image_cat'] == 'oneline')
                                        @php $dcount++; @endphp
                                    @endif
                                @endforeach
                                @if ( $dcount > 0 )
                                   <div id="oneline_container" class="mb-3">
                                        @foreach ($docs as $d)
                                            @if ($d['image_cat'] == 'oneline')
                                                <a href="{{ asset('/storage/' . $d['image_path']) }}" target="_blank" class="doc-container d-block border p-2">
                                                    <div class="d-flex overflow-hidden">
                                                        <div class="doc-img">
                                                            <img src="{{ asset('/docs/pdf.png') }}" width="50" alt="PDF Download" class="me-2">
                                                        </div>
                                                        <div class="doc-title">
                                                            {{ $d['image_title'] }}
                                                            <div style="font-size:.75em; margin-top:1em;">({{ date("M d, Y", strtotime($d['updated_at']) ) }})</div>
                                                        </div>
                                                    </div>
                                                </a>
                                            @endif
                                        @endforeach
                                    </div>
                                @else
                                    <div class="msg-no-docs">No documents uploaded.</div>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4 px-2">
                            <div class="yyz-card yyz-card-docs">
                                <h3 class="h4">Additional Documents</h3>
                                @php $dcount = 0; @endphp
                                @foreach( $docs as $d)
                                    @if ($d['image_cat'] == 'doc')
                                        @php $dcount++; @endphp
                                    @endif
                                @endforeach
                                @if ( $dcount > 0 )
                                     <div id="loc_doc_container" class="mb-3">
                                        @foreach ($docs as $d)
                                            @if ($d['image_cat'] == 'doc')
                                                @php
                                                    // get file extension
                                                    if ( str_contains($d['image_ext'], 'doc' ) ) {
                                                        $icon_link = 'word.png';
                                                        $icon_name = 'Word';
                                                    }
                                                    elseif ( $d['image_ext'] == 'pdf' ) {
                                                        $icon_link = 'pdf.png';
                                                        $icon_name = 'PDF';
                                                    }
                                                    elseif ( str_contains( $d['image_ext'], 'xls' ) || $d['image_ext'] == 'csv' ) {
                                                        $icon_link = 'excel.png';
                                                        $icon_name = 'Excel';
                                                    }
                                                    else {
                                                        $icon_link = 'txt.png';
                                                        $icon_name = 'Text';
                                                    }
                                                @endphp
                                                <a href="{{ asset('/storage/' . $d['image_path']) }}" target="_blank" class="doc-container d-block border p-2 mb-2">
                                                    <div class="d-flex overflow-hidden">
                                                        <div class="doc-img">
                                                            <img src="{{ asset('/docs/' . $icon_link) }}" width="50" alt="{{ $icon_name }} Download" class="me-2">
                                                        </div>
                                                        <div class="doc-title">
                                                            {{ $d['image_title'] }}
                                                            <div style="font-size:.75em; margin-top:1em;">({{ date("M d, Y", strtotime($d['updated_at']) ) }})</div>
                                                        </div>
                                                    </div>
                                                </a>
                                            @endif
                                        @endforeach
                                    </div>
                                @else
                                    <div class="msg-no-docs">No documents uploaded.</div>
                                @endif
                             </div>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-md-8">
                            <h3 class="h5">Admin Notes</h3>
                        </div>
                        <div class="col-md-4">
                            <button type="button" id="AddNotesBtn" onclick="newMsg()" class="btn btn-tertiary btn-small float-right w-auto">Add</button>
                            <h3 class="h5">Messages with {{$user->name}}</h3>
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-md-8">
                            <form action="{{ route('admin-module-note') }}" method="post" onsubmit="parseNote()">
                                <div id="quill_note">
                                    @if ( !empty( $notes ) )
                                        @php
                                            $date_time = date('M d, Y', strtotime($notes->updated_at)) . ' @ ' . date('h:i:s A', strtotime($notes->updated_at));
                                            echo html_entity_decode($notes->note);
                                        @endphp
                                    @endif
                                </div>
                                <textarea name="note" id="note" class="w-100 p-2 d-none"></textarea>
                                @csrf
                                <input type="hidden" name="user_id" value="{{ $user->id }}">
                                <input type="hidden" name="admin_id" value="{{ $admin->id }}">
                                <input type="hidden" name="module_id" value="{{$module->id}}">
                                <button type="submit" class="btn btn-primary my-3">Save Note</button>
                            </form>
                            @if ( !empty( $date_time ) )
                                <p class="mt-2"><small>Last updated: {{ $date_time }}</small></p>
                            @endif
                        </div>
                        <div class="col-lg-4">
                            <div id="msg_parent">
                                <div id="msg_content">
                                    @if( count($msgs) > 0 )
                                        @foreach($msgs as $msg)
                                            @if ( $msg->module == null)
                                                <div class="row mb-2 p-0 align-items-end">
                                                    <div class="col-11 @if($msg->admin_id !== null) offset-1 @endif">
                                                        <div class="note-bubble @if($msg->admin_id !== null) admin @endif">
                                                            <div class="small-steps mb-1">
                                                                {{ date('M d, Y @ h:i:s A', strtotime($msg->created_at)) }}
                                                            </div>
                                                            @php echo html_entity_decode($msg->message) @endphp
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
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

                @elseif ( session('step') == 5)
                    <div class="row">
                        <div class="col-12 mb-3">
                            <form action="{{ route("userDetailPost") }}" class="d-inline-block me-3" method="post" onsubmit="processingOverlay()">
                                @csrf
                                <input type="hidden" name="module" value="{{ $module->id }}">
                                <input type="hidden" name="user" value="{{ $user->id }}">
                                <button type="submit" class="btn btn-primary">Approve Images</button>
                            </form>
                            <a href="{{ route('userDetail', ['id'=>$user->id]) }}" class="btn btn-tertiary">Return to Dashboard</a>
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-12 px-2">
                            <div class="yyz-card yyz-card-docs position-relative">
                                <h3 class="h4">Location Images</h3>
                                @php $dcount = 0; @endphp
                                @foreach( $docs as $d )
                                    @if ( $d->module_id == $module->id )
                                        @php $dcount++; @endphp
                                    @endif
                                @endforeach
                                @if ( $dcount > 0 )
                                    <div id="loc_image_container" class="zoom-view mb-3">
                                        <button type="button" onclick="showAllImages()" class="close-x">&#10799;</button>
                                        @foreach ($docs as $d)
                                            @if ($d->image_cat == 'image' && $d->module_id == $module->id)
                                                <div class="doc-container d-inline-block border m-1">
                                                    <img src="{{ asset('/storage/' . $d['image_path']) }}" onclick="showAllImages()" width="80" alt="" class="cursor-pointer">
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                    <p class="mt-3"><small>Click any image to for a close-up view of all images.</small></p>
                                @else
                                    <div class="msg-no-docs">No images uploaded.</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-md-8 col-sm-6">
                            <h3 class="h5">Admin Notes</h3>
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <button type="button" id="AddNotesBtn" onclick="newMsg()" class="btn btn-tertiary btn-small float-right w-auto">Add</button>
                            <h3 class="h5">Messages with {{$user->name}}</h3>
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-8">
                            <form action="{{ route('admin-module-note') }}" method="post" onsubmit="parseNote()">
                                <div id="quill_note">
                                    @if ( !empty( $notes ) )
                                        @php
                                            $date_time = date('M d, Y', strtotime($notes->updated_at)) . ' @ ' . date('h:i:s A', strtotime($notes->updated_at));
                                            echo html_entity_decode($notes->note);
                                        @endphp
                                    @endif
                                </div>
                                <textarea name="note" id="note" class="w-100 p-2 d-none"></textarea>
                                @if ( !empty( $date_time ) )
                                    <p class="mt-2"><small>Last updated: {{ $date_time }}</small></p>
                                @endif                                @csrf
                                <input type="hidden" name="user_id" value="{{ $user->id }}">
                                <input type="hidden" name="admin_id" value="{{ $admin->id }}">
                                <input type="hidden" name="module_id" value="{{$module->id}}">
                                <button type="submit" class="btn btn-primary my-3">Save Note</button>
                            </form>

                        </div>
                        <div class="col-4">
                            <div id="msg_parent">
                                <div id="msg_content">
                                    @if( count($msgs) > 0 )
                                        @foreach($msgs as $msg)
                                            @if ( $msg->module == null)
                                                <div class="row mb-2 p-0 align-items-end">
                                                    <div class="col-11 @if($msg->admin_id !== null) offset-1 @endif">
                                                        <div class="note-bubble @if($msg->admin_id !== null) admin @endif">
                                                            <div class="small-steps mb-1">
                                                                {{ date('M d, Y @ h:i:s A', strtotime($msg->created_at)) }}
                                                            </div>
                                                            @php echo html_entity_decode($msg->message) @endphp
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
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
--}}
                @if ( session('step') == $m_count)
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <p>
                                Clicking the button below indicates the installer has completed the full training and had their installation approved via on-site inspection.
                                An official certification number will be auto-generated for you to provide to them.
                            </p>
                            <form action="{{ route("userDetailPost") }}" class="d-inline-block me-3" method="post" onsubmit="processingOverlay()">
                                @csrf
                                <button type="submit" class="btn btn-primary">Certify Installer</button>
                            </form>
                            <a href="{{ route('userDetail', ['id'=>$user->id]) }}" class="btn btn-tertiary">Return to Dashboard</a>
                        </div>
                        <div class="col-md-4">
                            <div>
                                <button type="button" id="AddNotesBtn" onclick="newMsg()" class="btn btn-tertiary btn-small float-right w-auto">Add</button>
                                <h3 class="h5">Messages with {{$user->name}}</h3>
                            </div>
                            <div id="msg_parent">
                                <div id="msg_content">
                                    @if( count($msgs) > 0 )
                                        @foreach($msgs as $msg)
                                            @if ( $msg->module == null)
                                                <div class="row mb-2 p-0 align-items-end">
                                                    <div class="col-11 @if($msg->admin_id !== null) offset-1 @endif">
                                                        <div class="note-bubble @if($msg->admin_id !== null) admin @endif">
                                                            <div class="small-steps mb-1">
                                                                {{ date('M d, Y @ h:i:s A', strtotime($msg->created_at)) }}
                                                            </div>
                                                            @php echo html_entity_decode($msg->message) @endphp
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
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
                @endif
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
            <input type="hidden" name="module_id" value="{{ $module->id }}">
            <button type="submit" class="btn btn-primary my-3">Add Message</button>
        </form>
    </div>

    <div id="step6_overlay_bg" class="overlay_bg" onclick="step6change()"></div>
    <div id="step6_overlay_content" class="overlay_content yyz-card p-4" style="width:400px">
        <form id="change_date_form" action="{{ route('final-suggest') }}" method="post" onsubmit="processingOverlay()">
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
    <style>
        .mapouter {
            height:auto;
            position:absolute;
            left:1rem;
            right:1rem;
            bottom:1rem;
            top:150px;
        }
        .gmap_canvas {
            overflow:hidden;
            background:none!important;
            height:100%;
            width:100%;
        }

        @media(max-width:991px) {
            .mapouter {
                position:static;
                height:300px;
            }
        }
    </style>
    <script>
        if ( document.getElementById('quill_note') !== null) {
            document.addEventListener("DOMContentLoaded", function() {
                var toolbarOptions = [
                    [{ 'header': [1, 2, 3, false ]}],
                    ['bold', 'italic', 'underline'],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],

                    [{ 'color': [] }],
                    [{ 'align': [] }],

                    ['clean']
                ];

                quill = new Quill('#quill_note', {
                    modules: {
                        syntax: false,
                        toolbar: toolbarOptions
                    },
                    theme: 'snow'
                });

                window.quill = quill

            });
        }

        function step3appt() {
            document.getElementById('step3set').classList.toggle('d-none');
            document.getElementById('step3planned').classList.toggle('d-none');
        }

        function step6appt() {
            document.getElementById('step6set').classList.toggle('d-none');
            document.getElementById('step6planned').classList.toggle('d-none');
        }

        function step6change() {
            document.getElementById('step6_overlay_bg').classList.toggle('show');
            document.getElementById('step6_overlay_content').classList.toggle('show');
        }

        function showAllImages() {
            document.getElementById('loc_image_container').classList.toggle('display-all');
        }

        function newMsg() {
            document.getElementById('overlay_bg').classList.toggle('show');
            document.getElementById('overlay_content').classList.toggle('show');
            if ( document.getElementById('overlay_bg').classList.contains('show') ) {
                document.getElementById('message').focus();
            }
        }

        function parseNote () {
            document.getElementById('note').innerHTML = document.querySelector('.ql-editor').innerHTML;
            processingOverlay();
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
            if (m_list != null) {
                m_height = document.getElementById('msg_content').offsetHeight;
                m_list.scrollTop += m_height;
            }
        }, 200);

    </script>
@endsection

