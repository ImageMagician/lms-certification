@extends('layouts.admin')
@section('title')
    Admin List
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12 p-4 bg-white mt-4">
                <div class="clearfix mb-2">
                    <a class="btn btn-tertiary float-right ms-2" href="{{ route('adminDashboard') }}">Dashboard</a>
                    @if ($admin->super_admin == 1)
                    <a class="btn btn-tertiary float-right ms-2" href="{{ route('admin-new') }}">Add New</a>
                    @endif
                    <h1 class="h3 mb-0">
                        Admin List
                    </h1>
                </div>
                @if ( session('password_status') )
                    <div class="alert alert-info">{{ Session::get('password_status') }}</div>
                @endif

                @if ( session('success') )
                    <div class="alert alert-success">{{ Session::get('success') }}</div>
                @elseif ( session('error'))
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
                                <a href="javascript:;" onclick="deleteConfirmToggle(this)" id="deleteadmin_{{$user->id}}" class="btn btn-tertiary text-decoration-none p-0 mx-1" style="width:24px; height:24px;">&#10006;</a>
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
                                Super
                            @elseif ($user->rsm !== null)
                                Regional Sales Manager
                            @else
                                Standard
                            @endif
                        </td>
                    </tr>
                @endforeach
                </table>
            </div>
        </div>
    </div>
    <div id="alert_delete" class="alert_custom p-3 border border-1">
        <p>Are you sure you want to delete this user?</p>
        <form method="post" action="{{ route('admin-delete') }}" onblur="deleteConfirmToggle(this)">
            @csrf
            <input type="hidden" name="id" id="delete_id" value="">
            <button type="submit" class="btn btn-primary me-2">Delete</button>
            <button type="button" id="btn_cancel" class="btn btn-tertiary" onclick="deleteConfirmToggle(this)">Cancel</button>
        </form>
    </div>
@endsection
@section('scripts')
    <style>
        .alert_custom {
            position:absolute;
            max-width:480px;
            left: 50%;
            top:-250px;
            transform:translateX(-50%);
            background:white;
            box-shadow:0 3px 2px rgba(0,0,0,.2);
            transition:top .25s ease-out;
            border-radius:10px;

            &.show {
                top:40px;
            }
        }
    </style>
    <script>
        function deleteConfirmToggle(e) {
            const del_id = e.id.split('_');
            if (del_id[0] == 'deleteadmin') {
                document.getElementById('delete_id').value = del_id[1];
            }
            document.getElementById('alert_delete').classList.toggle('show');
        }
    </script>
@endsection
