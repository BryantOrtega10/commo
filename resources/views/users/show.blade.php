@extends('adminlte::page')
@section('plugins.Datatables', true)
@section('plugins.Sweetalert2', true)

@section('title', 'Users')

@section('content_header')
    <div class="row">
        <div class="col-md-9">
            <h1>Users</h1>
        </div>
        <div class="text-right col-md-3">
            <a href="{{ route('users.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i>
                Enter a New User</a>
        </div>
    </div>
@stop

@section('content')
    @if (session('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <table class="table table-striped datatable min-w-100" data-url="{{ route('users.datatable') }}">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    
                </tbody>
            </table>
        </div>
    </div>
@stop

@section('js')
    <script src="/js/users/datatable.js"></script>
@stop
