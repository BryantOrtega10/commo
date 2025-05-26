@extends('adminlte::page')
@section('plugins.Datatables', true)
@section('plugins.Sweetalert2', true)

@section('title', 'Agents')


@section('content_header')
    <div class="row">
        <div class="col-md-12">
            <h1>Unlinked Error Report</h1>
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
    <form action="{{ route('commissions.all-sales.show') }}" method="POST">
        @csrf
        <div class="card card-light">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="statement_start_date">Statement Start Date:</label>
                            <input type="date" class="form-control @error('statement_start_date') is-invalid @enderror"
                                id="statement_start_date" name="statement_start_date" required
                                value="{{ old('statement_start_date') }}">
                            @error('statement_start_date')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="statement_end_date">Statement End Date:</label>
                            <input type="date" class="form-control @error('statement_end_date') is-invalid @enderror"
                                id="statement_end_date" name="statement_end_date" required
                                value="{{ old('statement_end_date') }}">
                            @error('statement_end_date')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="agentNumberBase">Agent Number:</label>
                            <select id="agentNumberBase" name="agentNumberBase" class="form-control">
                                <option value=""></option>
                                @foreach ($agentNumbers as $agentNumber)
                                    <option value="{{ $agentNumber->id }}">{{ $agentNumber->number }} -
                                        {{ $agentNumber->agent->last_name }} {{ $agentNumber->agent->first_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="agency_code">Agency Code:</label>
                            <select id="agency_code" name="agency_code"
                                class="form-control @error('agency_code') is-invalid @enderror">
                                <option value=""></option>
                                @foreach ($agency_codes as $agency_code)
                                    <option value="{{ $agency_code->id }}"
                                        @if (old('agency_code') == $agency_code->id) selected @endif>
                                        {{ $agency_code->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="carrier">Carrier:</label>
                            <select id="carrier" name="carrier" class="form-control">
                                <option value=""></option>
                                @foreach ($carriers as $carrier)
                                    <option value="{{ $carrier->id }}" @if (old('carrier') == $carrier->id) selected @endif>
                                        {{ $carrier->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="text-right">
            <input type="submit" class="btn btn-primary append-rates" value="Generate Report" />
        </div>
    </form>
@stop

@section('js')

@stop
