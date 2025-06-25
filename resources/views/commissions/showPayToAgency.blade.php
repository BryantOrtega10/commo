@extends('adminlte::page')
@section('plugins.Datatables', true)
@section('plugins.Sweetalert2', true)

@section('title', 'Pay to Agency Report')


@section('content_header')
    <div class="row">
        <div class="col-md-12">
            <h1>Pay to Agency Report</h1>
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
    <form action="{{ route('commissions.pay-to-agency.show') }}" method="POST">
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
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="agency">Pay to Agency:</label>
                            <select id="agency" name="agency" class="form-control">
                                <option value=""></option>
                                @foreach ($agencies as $agency)
                                    <option value="{{ $agency->id }}" @if (old('agency') == $agency->id) selected @endif>
                                        {{ $agency->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="export_type">Export format:</label>
                            <select id="export_type" name="export_type" class="form-control">
                                <option value="pdf">PDF</option>
                                <option value="excel">Excel</option>
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
