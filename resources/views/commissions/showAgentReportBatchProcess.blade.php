@extends('adminlte::page')
@section('plugins.Sweetalert2', true)

@section('title', 'Agent Reports Processes')

@section('content_header')
    <h1>Agent Reports Processes</h1>
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
            <div class="row">
                <div class="col-md-3 col-12">
                    <div class="form-group">
                        <label for="processing_start_date">Processing Start Date:</label>
                        <input type="text" class="form-control" id="processing_start_date" name="processing_start_date"
                            readonly value="{{ date('m/d/Y H:i:s', strtotime($report->processing_start_date)) }}">
                    </div>
                </div>
                <div class="col-md-3 col-12">
                    <div class="form-group">
                        <label for="processing_end_date">Processing End Date:</label>
                        <input type="text" class="form-control" id="processing_end_date" name="processing_end_date"
                            readonly @isset($report->processing_end_date) value="{{ date('m/d/Y H:i:s', strtotime($report->processing_end_date)) }}" @endisset>
                    </div>
                </div>
                <div class="col-md-3 col-12">
                    <div class="form-group">
                        <label for="entry_user">Entry User:</label>
                        <input type="text" class="form-control" id="entry_user" name="entry_user" readonly
                            value="{{ $report->entry_user?->name }}">
                    </div>
                </div>
            </div>
            <input type="hidden" id="status" value="{{$report->status}}" />
            <input type="hidden" id="url" value="{{route('commissions.agent-process.showUploadJson',['id' => $report->id])}}" />
            
            <div class="row">
                <div class="col-md-4 text-center col-12"><span class="info-square bg-secondary"></span> <span
                        id="total_rows">({{ $report->total }})</span> Generating</div>
                <div class="col-md-4 text-center col-12"><span class="info-square bg-success"></span> <span
                        id="generated_rows">({{ $report->generated }})</span> Generated</div>
            </div>
            <div class="progress my-3 rounded">
                <div class="progress-bar bg-secondary" id="percentageTotal" role="progressbar"
                    style="width: {{ $percentageTotal }}%" aria-valuenow="{{ $percentageTotal }}" aria-valuemin="0"
                    aria-valuemax="100"></div>
                <div class="progress-bar bg-success" id="percentageGenerated" role="progressbar"
                    style="width: {{ $percentageGenerated }}%" aria-valuenow="{{ $percentageGenerated }}" aria-valuemin="0"
                    aria-valuemax="100"></div>
            </div>
            @if ($report->status == 0)
                <div class="text-center">
                    Generating Report ...
                </div>
            @else
                <div class="text-center">
                    <a href="{{route('commissions.agent-process.download',['id' => $report->id])}}" class="btn btn-outline-success">Download</a> 
                    <a href="{{route('commissions.agent-process.delete',['id' => $report->id])}}" class="btn btn-danger ask" data-message="Delete this batch report">Delete</a>
                </div>
            @endif


        </div>
    </div>
@stop


@section('js')
    <script src="/js/reports/reloadReports.js"></script>
@stop
