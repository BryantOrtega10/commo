@extends('adminlte::page')
@section('plugins.Datatables', true)
@section('plugins.Sweetalert2', true)

@section('title', 'Agents')

@section('adminlte_css_pre')
    <link rel="stylesheet" href="{{ asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css') }}">
@stop


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

    <form action="{{ route('commissions.agent-report.show') }}" method="POST">
        @csrf
        <div class="card card-light">
            <div class="card-header">
                Search filters
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
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
                            <label for="agency">Pay to Agency:</label>
                            <select id="agency" name="agency"
                                class="form-control">
                                <option value=""></option>
                                @foreach ($agencies as $agency)
                                    <option value="{{ $agency->id }}"
                                        @if (old('agency') == $agency->id) selected @endif>{{ $agency->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
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
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 col-12">
                <div class="form-group">
                    <div class="icheck-secondary" title="All Agents">
                        <input type="checkbox" name="all_agents" id="all_agents" checked>
                        <label for="all_agents">All Agents</label>
                    </div>
                </div>
            </div>
        </div>


    <div class="card d-none cont-agents-table">
        <div class="card-header">
            Agents
        </div>
        <div class="card-body">
            <table class="table table-striped datatable min-w-100"
                data-url="{{ route('commissions.agent-report.datatable') }}">
                <thead>
                    <tr>
                        <th></th>
                        <th>Agent #Id</th>
                        <th>Agent Name</th>
                        <th>Title</th>
                        <th>Status</th>
                        <th>Contract</th>
                        <th>Agency Code</th>
                        <th>Agent #</th>
                        <th>Carrier</th>
                        <th>Override Agents</th>
                        <th>Mentor Agents</th>
                        <th>Contract Date</th>
                        <th>Email</th>
                        <th>Phone</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
    <div class="text-right">
        <input type="submit" class="btn btn-primary" value="Export to PDF" />
    </div>
    </form>
@stop

@section('js')
    <script src="/js/commissions/agentsDatatable.js"></script>
@stop
