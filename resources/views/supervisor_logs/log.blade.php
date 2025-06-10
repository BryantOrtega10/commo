@extends('adminlte::page')
@section('plugins.Datatables', true)
@section('plugins.Sweetalert2', true)

@section('title', 'Log')


@section('content_header')
    <div class="row">
        <div class="col-md-12">
            <h1>Log</h1>
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
    <form action="{{ route('supervisor-logs.log',['id' => $userID]) }}" method="POST">
        @csrf
        <div class="card card-light">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="start_date">Start Date:</label>
                            <input type="datetime-local" class="form-control @error('start_date') is-invalid @enderror"
                                id="start_date" name="start_date" required value="{{ old('start_date') }}">
                            @error('start_date')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="end_date">End Date:</label>
                            <input type="datetime-local" class="form-control @error('end_date') is-invalid @enderror"
                                id="end_date" name="end_date" required value="{{ old('end_date') }}">
                            @error('end_date')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="text-right">
            <input type="submit" class="btn btn-primary" value="Download Log" />
        </div>
    </form>
@stop

@section('js')

@stop
