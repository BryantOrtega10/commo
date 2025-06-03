@extends('adminlte::page')
@section('plugins.Sweetalert2', true)

@section('title', 'Agent Report')


@section('content_header')
    <div class="row">
        <div class="col-md-12">
            <h1>Agent Report</h1>
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
    <form action="{{ route('reports.agent.show') }}" method="POST">
        @csrf
        <div class="card card-light">
            <div class="card-body">
                <div class="row">
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
                            <label for="agent_status">Agent Status:</label>
                            <select id="agent_status" name="agent_status" class="form-control">
                                <option value=""></option>
                                @foreach ($agent_statuses as $agent_status)
                                    <option value="{{ $agent_status->id }}"
                                        @if (old('agent_status') == $agent_status->id) selected @endif>{{ $agent_status->name }}
                                    </option>
                                @endforeach
                            </select>

                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="mentor_agent_number">Menor Agent Number:</label>
                            <select id="mentor_agent_number" name="mentor_agent_number" class="form-control">
                                <option value=""></option>
                                @foreach ($agentNumbers as $agentNumber)
                                    <option value="{{ $agentNumber->id }}"
                                        @if (old('mentor_agent_number') == $agentNumber->id) selected @endif>{{ $agentNumber->number }} - {{ $agentNumber->agent->first_name }} {{ $agentNumber->agent->last_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="override_agent_number">Override Agent Number:</label>
                            <select id="override_agent_number" name="override_agent_number" class="form-control">
                                <option value=""></option>
                                @foreach ($agentNumbers as $agentNumber)
                                    <option value="{{ $agentNumber->id }}"
                                        @if (old('override_agent_number') == $agentNumber->id) selected @endif>{{ $agentNumber->number }} - {{ $agentNumber->agent->first_name }} {{ $agentNumber->agent->last_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="sales_region">Sales Region:</label>
                            <select id="sales_region" name="sales_region" class="form-control">
                                <option value=""></option>
                                @foreach ($sales_regions as $sales_region)
                                    <option value="{{ $sales_region->id }}"
                                        @if (old('sales_region') == $sales_region->id) selected @endif>{{ $sales_region->name }}
                                    </option>
                                @endforeach
                            </select>

                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="agent_title">Agent Title:</label>
                            <select id="agent_title" name="agent_title" class="form-control">
                                <option value=""></option>
                                @foreach ($agent_titles as $agent_title)
                                    <option value="{{ $agent_title->id }}"
                                        @if (old('agent_title') == $agent_title->id) selected @endif>{{ $agent_title->name }}
                                    </option>
                                @endforeach
                            </select>

                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer text-right">
                <input type="submit" class="btn btn-primary" value="Export to Excel" />
            </div>
    </form>
@stop

@section('js')

@stop
