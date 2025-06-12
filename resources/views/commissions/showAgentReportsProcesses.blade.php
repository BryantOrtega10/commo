@extends('adminlte::page')
@section('plugins.Datatables', true)
@section('plugins.Sweetalert2', true)
@section('plugins.Quill', true)


@section('title', 'Agent Reports Processes')

@section('adminlte_css_pre')
    <link rel="stylesheet" href="{{ asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css') }}">
@stop


@section('content_header')
    <div class="row">
        <div class="col-md-9">
            <h1>Agent Reports Processes</h1>
        </div>
        <div class="text-right col-md-3">
            <a href="{{ route('commissions.agent-process.show-email') }}"
                class="btn btn-outline-primary edit-email-template">Email Statements</a>
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

    <form action="" id="agent-report-processes-form" method="POST">
        @csrf
        <input type="hidden" id="urlBatch" value="{{ route('commissions.agent-process.generate-batch') }}">
        <input type="hidden" id="urlIndividual" value="{{ route('commissions.agent-process.generate-individual') }}">
        <input type="hidden" id="urlEmailBatch" value="{{ route('commissions.agent-process.send-mail-batch') }}">
        <input type="hidden" id="urlEmailIndividual" value="{{ route('commissions.agent-process.send-mail-individual') }}">



        <div class="card card-light">
            <div class="card-header">
                Search filters
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="statement_date">Statement Date:</label>
                            <input type="date" class="form-control @error('statement_date') is-invalid @enderror"
                                id="statement_date" name="statement_date" required value="{{ old('statement_date') }}"
                                required>
                            @error('statement_date')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
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
                            <label for="exportFile">Export File Type:</label>
                            <select id="exportFile" name="exportFile" class="form-control">
                                <option value="0">Pdf</option>
                                <option value="1">Excel</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer text-right">
                <input type="button" class="btn btn-primary btn-generate-batch" value="Generate" />
                <input type="button" class="btn btn-outline-primary btn-email-batch" value="Send Emails" />

            </div>
        </div>

        <div class="card card-light">
            <div class="card-header">
                Individual or Multiple Agent Statements
            </div>
            <div class="card-body">
                <div class="row align-items-end">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="agent">Agent Name:</label>
                            <select id="agent" name="agent" class="form-control">
                                <option value=""></option>
                                @foreach ($agents as $agent)
                                    <option value="{{ $agent->id }}">{{ $agent->last_name }} {{ $agent->first_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <button type="button" class="btn btn-outline-success mb-3 add-agent">Add</a>
                    </div>
                </div>
                <div class="cont-selected-agents">
                    <h5>Selected Agents</h5>
                    <div class="row selected-agents">

                    </div>
                </div>
            </div>
            <div class="card-footer text-right">
                <input type="button" class="btn btn-primary btn-generate-individual" value="Generate" />
                <input type="button" class="btn btn-outline-primary btn-email-individual" value="Send Emails" />
            </div>
        </div>
    </form>
    @if (sizeof($reports) > 0)
        <div class="card card-light">
            <div class="card-header">
                Active Reports
            </div>
            <div class="card-body">
                <table class="table table-striped datatable min-w-100">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Statement Date</th>
                            <th>Export File Type</th>
                            <th>Status</th>
                            <th>Entry User</th>
                            <th width="120">Actions</th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($reports as $report)
                            <tr>
                                <td>{{ $report->id }}</td>
                                <td>{{ date('m/d/Y', strtotime($report->statement_date)) }}</td>
                                <td>{{ $report->txt_export_file_type }}</td>
                                <td>{{ $report->txt_status }}</td>
                                <td>{{ $report->entry_user?->name }}</td>
                                <td><a href="{{ route('commissions.agent-process.showUpload', ['id' => $report->id]) }}"
                                        class="btn btn-outline-primary">View Report</td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif


@stop

@section('js')
    <script src="/js/commissions/agentReportProcess.js"></script>
@stop
