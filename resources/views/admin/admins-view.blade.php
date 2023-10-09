@extends('layouts.admin')
@section('title')
    Admin List
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12 p-4 bg-white mt-4">
                <h1 class="h3">Admin List</h1>
                @if ( Session::has('password_status') )
                    <div class="alert alert-info">{{ Session::get('password_status') }}</div>
                @endif

                @if ( Session::has('action') )
                    <div class="alert alert-success">{{ Session::get('action') }}</div>
                @elseif (Session::has('error'))
                    <div class="alert alert-danger">{{ Session::get('error') }}</div>
                @endif
                <table class="table">
                    <tr>
                        <th>&nbsp;</th>
                        <th>ID</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                        <th>Privilege</th>
                    </tr>
                @foreach ($list as $user)
                    <tr
                    @if ( $admin->id == $user->id )
                        style="box-shadow:0 0 0 6px rgba(134, 183, 254, .5); z-index:1;"
                    class="position-relative"
                    @endif
                    >
                        <td class="w-auto">
                            @if ( $admin->id !== $user->id && $admin->super_admin == 1 )
                                <a href="{{ route('admin-delete', ['id'=>$user->id]) }}" class="btn btn-tertiary text-decoration-none p-0 mx-1" style="width:24px; height:24px;">&#10006;</a>
                            @elseif ( $admin->super_admin === 1)
                                <div class="mx-1 d-inline-block vertical-align-middle" style="width:24px; height:24px"></div>
                            @endif
                            @if ( $admin->id == $user->id || $admin->super_admin == 1 )
                            <a href="{{ route('admin-individual', ['id'=>$user->id]) }}" class="btn btn-tertiary text-decoration-none p-0 mx-1" style="width:24px; height:24px;">&#9998;</a>
                            @endif
                        </td>
                        <td>{{$user->id}}</td>
                        <td>{{$user->first_name}}</td>
                        <td>{{$user->last_name}}</td>
                        <td>{{$user->email}}</td>
                        <td>
                            @if ($user->super_admin == 1)
                                Admin
                            @elseif ($user->rsm == 1)
                                Regional Rep
                            @else
                                User
                            @endif
                        </td>
                    </tr>
                @endforeach
                </table>
            </div>
        </div>
    </div>
@endsection
