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
                           preload="auto"
                    ></video>
                    <div id="quiz_btn_overlay">
                        <div id="quiz_btn">
                                <a href="{{ $module->id }}/quiz" class="btn btn-primary w-100">Start Quiz</a> &nbsp;
                                <button id="btn_restart_video" class="btn btn-primary w-100">Restart Video</button>
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
                <h1>
                    <a href="{{route('home')}}" class="float-right btn btn-outline-secondary">Home</a>
                    {{$module->title}}
                </h1>
                <h2 class="h3">{{$module->description}}</h2>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        setTimeout( () => {
            const vid_player = document.getElementById('video_player');
            $(vid_player).on('ended', () => {
                document.getElementById('quiz_btn_overlay').className = 'show';
                $.ajax({
                    url: "{{ route('video-end', ['id' => $module->id ]) }}",
                    type: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        user: {{ $user->id }},
                        video: {{ $module->id }}
                    },
                    success: (response) => {
                        console.log('video completion logged.');
                    },
                    error: (xhr, status, error) => {
                        console.error('video log couldn\'t be completed.', error);
                }
                });
            });


        const btn_restart = document.getElementById('btn_restart_video');
            btn_restart.addEventListener('click', function () {
                document.getElementById('quiz_btn_overlay').classList.remove('show');
                vid_player.play();
            })
        },100);
    </script>
@endsection
