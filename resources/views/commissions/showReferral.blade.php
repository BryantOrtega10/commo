@extends('adminlte::page')
@section('plugins.Datatables', true)
@section('plugins.Sweetalert2', true)

@section('title', 'Referral Report')


@section('content_header')
    <div class="row">
        <div class="col-md-12">
            <h1>Referral Report</h1>
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
    <form action="{{ route('commissions.referral.show') }}" method="POST">
        @csrf
        <div class="card card-light">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="statement_date">Statement Date:</label>
                            <input type="date" class="form-control @error('statement_date') is-invalid @enderror"
                                id="statement_date" name="statement_date" required value="{{ old('statement_date') }}">
                            @error('statement_date')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="agent">Agent Name:</label>
                            <select id="agent" name="agent" required class="form-control">
                                <option value=""></option>
                                @foreach ($agents as $agent)
                                    <option value="{{ $agent->id }}">{{ $agent->last_name }} {{ $agent->first_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>


                    
                </div>
            </div>
        </div>
        <div class="text-right">
            <input type="submit" class="btn btn-primary" value="Export" />
        </div>
    </form>
@stop

@section('js')

@stop
