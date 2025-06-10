@extends('adminlte::page')
@section('plugins.Datatables', true)
@section('title', 'New User')

@section('content_header')
    <h1>New User</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('users.show') }}">Users</a></li>
            <li class="breadcrumb-item active" aria-current="page">New User</li>
        </ol>
    </nav>
@stop

@section('content')
    <div class="card">
        <form action="{{ route('users.update',['id' => $user->id]) }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="name">Name:</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                                name="name" placeholder="Name:" value="{{ old('name',$user->name) }}">
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="email">Email:</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                                name="email" placeholder="Email:" value="{{ old('email',$user->email) }}">
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="role">Role:</label>
                            <select class="form-control @error('role') is-invalid @enderror" id="role" name="role">
                                @foreach ($roles as $role)
                                    <option value="{{ $role }}" @if (old('role',$user->role) == $role) selected @endif>
                                        {{ ucfirst($role) }}</option>
                                @endforeach
                            </select>
                            @error('role')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="status">Status:</label>
                            <select id="status" name="status"
                                class="form-control @error('status') is-invalid @enderror">
                                <option value="1" @if (old('status',$user->status) == '1') selected @endif>Active</option>
                                <option value="0" @if (old('status',$user->status) == '0') selected @endif>Inactive</option>
                            </select>
                            @error('status')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="change_password">Change Password:</label>
                            <select id="change_password" name="change_password"
                                class="form-control @error('change_password') is-invalid @enderror">
                                <option value="0" @if (old('change_password',$user->change_password) == '0') selected @endif>No</option>
                                <option value="1" @if (old('change_password',$user->change_password) == '1') selected @endif>Yes</option>
                            </select>
                            @error('change_password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>
                <span class="note">Leave empty to not change the password or image</span>
                <div class="row">
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="password">Password:</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                id="password" name="password" placeholder="Password:" value="{{ old('password') }}">
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="repeat_password">Repeat Password:</label>
                            <input type="password" class="form-control @error('repeat_password') is-invalid @enderror"
                                id="repeat_password" name="repeat_password" placeholder="Repeat Password:"
                                value="{{ old('repeat_password') }}">
                            @error('repeat_password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="image">Select image:</label>
                            <button type="button" class="btn btn-outline-primary select-image">Select File</button>
                            <input type="file" id="image" name="image" class="d-none" accept="image/*">
                            @error('image')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>

            </div>
            <div class="text-right card-footer">
                <input type="submit" class="btn btn-lg btn-primary" value="Save User" />
            </div>
        </form>
    </div>
@stop

@section('js')
    <script>
        $(document).ready(function(e) {
            $("body").on("click", ".select-image", function() {
                $("#image").trigger("click")
            })
        })
    </script>
@stop
