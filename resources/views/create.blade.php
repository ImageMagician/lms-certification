@extends('layouts.app')
@section('content')
    <form action="/create/process" method="post">
        <input type="text" name="name" placeholder="name"><br />
        <input type="text" name="email" placeholder="email"><br />
        <input type="text" name="password" placeholder="password">
        @csrf
        <button type="submit">Submit</button>
    </form>
@endsection
