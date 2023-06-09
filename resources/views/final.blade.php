@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-sm-9">
                <form action="{{ route('final-post') }}" method="post">
                @foreach( $questions as $q)
                    <div class="row">
                        <div class="col-12 py-3">
                            <h3>{{ $q['question'] }}</h3>
                            @php
                                $q_list = json_decode( $q['answer_array']);
                            @endphp
                            @foreach ( $q_list as $key=>$value)
                                <label for="mod{{ $q['module_id'] }}_q{{$q['q_id']}}_answer{{$key}}" class="quiz-select">
                                    <input type="radio" onclick="changeCSS(this)" id="mod{{ $q['module_id'] }}_q{{$q['q_id']}}_answer{{$key}}" name="m{{ $q['module_id'] }}_q{{$q['q_id']}}" value="{{ $key }}" />{{ $value }}
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endforeach
                    @csrf
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript" src="{{ asset('js/quizzes.js') }}"></script>
@endsection
