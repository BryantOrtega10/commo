@extends('adminlte::page')
@section('plugins.Datatables', true)
@section('plugins.Sweetalert2', true)

@section('title', 'Agent Commission Statement Balances')


@section('content_header')
    <div class="row">
        <div class="col-md-12">
            <h1>Agent Commission Statement Balances</h1>
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
    <form action="{{ route('commissions.statement-balances.show') }}" method="POST">
        @csrf
        <div class="card card-light">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="statement_date">Statement Date:</label>
                            <input type="date" class="form-control @error('statement_date') is-invalid @enderror"
                                id="statement_date" name="statement_date" value="{{ old('statement_date') }}">
                            @error('statement_date')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <div class="icheck-secondary" title="Show Only Agents with Negative Balances">
                                <input type="checkbox" name="showOnlyNegative" id="showOnlyNegative">
                                <label for="showOnlyNegative">Show Only Agents with Negative Balances</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="minBalance">Min. Balance:</label>
                            <input type="date" class="form-control @error('minBalance') is-invalid @enderror"
                                id="minBalance" name="minBalance" value="{{ old('minBalance') }}">
                            @error('minBalance')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="maxBalance">Max. Balance:</label>
                            <input type="date" class="form-control @error('maxBalance') is-invalid @enderror"
                                id="maxBalance" name="maxBalance" value="{{ old('maxBalance') }}">
                            @error('maxBalance')
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
            <input type="submit" class="btn btn-primary" value="Export to Excel" />
        </div>
    </form>
@stop

@section('js')

@stop
