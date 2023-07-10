@extends('layouts.app')
@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h1 class="h6" style="padding-bottom:0.25em; margin-bottom:0.5em; letter-spacing: 0.5em; border-bottom:1px solid rgba(0,0,0,.1)">QUIZ RESULTS</h1>
                <h2 class="h2">{{ $module->title }}</h2>
                <h3 class="h4 mb-3">
                    @if ( $answers[$module->id] == $q_tot[$module->id] )
                        Congratulations! You have answered all the questions correctly.
                    @else
                        You have answered {{ $answers[$module->id] }} of the {{ $q_tot[$module->id] }} questions correctly.
                    @endif
                </h3>
                    @if ( $answers[$module->id] < $q_tot[$module->id] )
                    <div class="upload_container">

                    <h3 class="pb-2 mb-4">Incorrect Responses</h3>
                    @foreach( $q_list as $q )
                        @foreach ($a_list as $a)
                            @if ( $a->q_id == $q->q_id )
                                @if($a->answer != $q->answer_correct)
                                    <div style="border-top:1px solid rgba(0,0,0,.1); padding:1rem 0; margin:0.5rem 0">
                                    <h4>{{ $q->question }}</h4>
                                    @php
                                        $incorrect = '';
                                        $correct = '';
                                        $a_decode = json_decode( $q->answer_array, true);
                                    @endphp
                                    @foreach($a_decode as $key => $value)
                                        @if ( $key == $q->answer_correct)
                                            @php $correct = $value; @endphp
                                        @elseif ( $key != $a->answer)
                                            @php $incorrect = $value; @endphp
                                        @endif
                                    @endforeach
                                    <p class="mb-0">Incorrect: {{ $incorrect }}</p>
                                    <p><strong>Correct: {{ $correct }}</strong></p>
                                    <button class="vid-btn btn btn-secondary btn-small" id="vid_snippet-{{$q['video_snippet_start']}}-{{$q['video_snippet_end']}}" type="button">Rewatch Video Section</button>
                                   </div>
                                @endif
                            @endif
                        @endforeach
                    @endforeach
                    </div>
                @endif

                <div class="mt-3">
                    <form action="/modules/@php echo sprintf("%02d", $module->id); @endphp/restart" method="post" class="d-inline-block me-2 mb-2 w-100 w-sm-auto">
                        @csrf
                        <input type="hidden" id="module_id" name="module_id" value="{{ $module->id }}">
                        <input type="hidden" name="_method" value="PUT">
                        <button type="submit" class="btn btn-tertiary w-100">Restart Quiz</button>
                    </form>
                    <form action="/modules/@php echo sprintf("%02d", $module->id); @endphp/rewatch" method="post" class="d-inline-block me-2 mb-2 w-100 w-sm-auto">
                        @csrf
                        <input type="hidden" id="module_id" name="module_id" value="{{ $module->id }}">
                        <input type="hidden" name="_method" value="PUT">
                        <button type="submit" class="btn btn-tertiary w-100">Rewatch Video</button>
                    </form>
                    <a href="/home" class="btn @if ($mod_check == null) btn-primary @else btn-tertiary @endif me-2 mb-2 w-100 w-sm-auto vertical-align-bottom">Main Menu</a>
                    @if ( $mod_check != null)
                        <a href="/modules/{{ $next }}" class="btn btn-primary me-2 mb-2 w-100 w-sm-auto vertical-align-bottom">Next Module</a>
                    @else
                        <h4 class="mt-3">Training complete</h4>
                        <p>
                            You have completed the video training for certification. A Lion Energy representative will reach out to you
                            via email or phone to complete your training and supply you with a Lion Energy-approved certification number
                            needed to install the Lion Energy Sanctuary.
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div id="overlay_bg"></div>
    <div id="overlay_content">
        <button id="close_btn"></button>
        <video id="video_player"
               class="w-100 align-bottom"
               controls
               src="{{ $module->video }}"
               poster="{{ $module->video_poster }}"
               type="video/mp4"
               preload="auto"
        ></video>
    </div>
@endsection
@section('scripts')
    <script>
        setTimeout( ()=> {
            // got through all video snippet buttons and add event listener
            let vb;
            const vidBtns = document.querySelectorAll('.vid-btn');
            for( vb = 0; vb < vidBtns.length; vb++ ) {
                vidBtns[vb].addEventListener('click', showVid, false);
            }

            // set event listener to the overlay_bg
            document.getElementById('overlay_bg').addEventListener('click', toggleVid, false);
            document.getElementById('close_btn').addEventListener('click', toggleVid, false);
        }, 250);

        function showVid() {
            // the video id stores the start and stop time for the snippet wanted
            // [1] = start
            // [2] = end
            const vidId = this.id.split('-');
            const video = document.getElementById('video_player');

            // split the current src to make sure we can strip
            const src = video.src.split('#');
            const time = '#t=' + vidId[1] + ',' + vidId[2];

            const new_src = src[0] + time;
            video.src = new_src;
            toggleVid();
        }

        function toggleVid() {
            const video = document.getElementById('video_player');
            document.getElementById('overlay_bg').classList.toggle('show');
            document.getElementById('overlay_content').classList.toggle('show');
            if ( !video.paused ) {
                video.pause();
            }
        }
    </script>
@endsection
