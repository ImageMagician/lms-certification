@extends('layouts.app')
@section('content')
    <div id="quiz" class="container">
        <div class="row justify-content-center">
            <div class="col-8 py-5">
                @foreach($quiz as $key => $value)
                    <h2>{{ $value['question'] }}</h2>
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
                                <button type="submit" class="btn btn-primary me-3 d-none">Next Question</button>
                            </div>
                    </form>
                @endforeach
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @php
        setcookie('leqca', $value['answer_correct']);
    @endphp
@endsection
