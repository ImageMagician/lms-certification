@extends('layouts.app')
@section('content')
    <div id="quiz" class="container">
        <div class="row justify-content-center">
            <div class="col-sm-8 py-3 py-sm-5">
                @foreach($quiz as $key => $value)
                    <h2 class="h3">{{ $value['question'] }}</h2>
                    @php
                        $answers = json_decode( $value['answer_array'], true);
                    @endphp
                    <form id="quiz_form" action="{{ route('answer') }}" method="post">
                        @foreach($answers as $k => $v)
                            <label for="mod{{$module->id}}_q{{$value->id}}_answer{{$k}}" class="quiz-select">
                                <input type="radio" id="mod{{$module->id}}_q{{$value->id}}_answer{{$k}}" name="answer" value="{{ $k }}" />
                                <span class="answer_icon">{{ $k }}</span>
                                {{ $v }}
                            </label>
                        @endforeach
                        @csrf
                            <input type="hidden" name="module_id" value="{{$module->id}}">
                            <input type="hidden" name="q_id" value="{{$value['q_id']}}">
                            <input type="hidden" name="user_id" value="{{ $user->id }}">
                            <div class="mt-3">
                                <div id="quiz_msg" class="btn d-inline-block d-none me-3 mb-0" style="font-weight:700; color:#900"></div>
                                <button id="quiz_submit" type="submit" class="btn btn-primary me-3 d-none">Next Question</button>
                            </div>
                    </form>
                @endforeach
            </div>
        </div>
    </div>

    <div id="main_overlay_bg" class="overlay_bg"></div>
    <div id="main_overlay_content" class="overlay_content text-center">
        <div class="flashing-bullet">&bull;</div>
        <div class="flashing-bullet">&bull;</div>
        <div class="flashing-bullet">&bull;</div>
    </div>
@endsection

@section('scripts')
    @php
        setcookie('leqca', $value['answer_correct']);
    @endphp

    <script>
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

        setTimeout( () => {
            const submit = document.getElementById('quiz_submit');
            submit.addEventListener('click', showOverlay, false);
        },250);

        function showOverlay() {
            document.getElementById('main_overlay_bg').classList.add('show');
            document.getElementById('main_overlay_content').classList.add('show');
        }
    </script>
@endsection
