@extends('layouts.app')
@section('content')
    @if ( $module->video !== null)
    <div class="bg-black">
        <div class="container">
            <div class="row">
                <div class="col-12 position-relative">
                    <video id="video_player"
                           class="w-100 align-bottom"
                           controls
                           src="{{ $module->video }}"
                           poster="{{ $module->video_poster }}"
                    ></video>
                    <div id="quiz_btn_overlay">
                        <div id="quiz_btn">
                            <p>
                                <a href="{{ $module->id }}/quiz" class="btn btn-primary">Start Quiz</a> &nbsp;
                                <button onclick="replayVideo()" class="btn btn-primary">Restart Video</button>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
    <div class="container">
        <div class="row my-5 justify-content-center">
            <div class="col-12">
                <h1>{{$module->title}}</h1>
                <h2>{{$module->description}}</h2>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        setTimeout( () => {
            const vid_player = document.getElementById('video_player');
            if ( vid_player !== null ) {
                vid_player.addEventListener('ended', () => {
                    document.getElementById('quiz_btn_overlay').className = 'show';
                });
            }
        },100);
    </script>
@endsection
