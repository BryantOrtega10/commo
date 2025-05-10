@extends('adminlte::page')
@section('plugins.Datatables', true)
@section('title', 'Commission Calculation')

@section('content_header')
    <h1>Commission Calculation</h1>
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
        <div class="card-header">
            Row details
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 text-center col-12"><span class="info-square bg-secondary"></span> Uploaded</div>
                <div class="col-md-4 text-center col-12"><span class="info-square bg-success"></span> Linked</div>
                <div class="col-md-4 text-center col-12"><span class="info-square bg-danger"></span> Error</div>
            </div>
            <div class="progress mt-3 rounded">
                <div class="progress-bar bg-secondary" role="progressbar" style="width: {{$percentageUploaded}}%" aria-valuenow="{{$percentageUploaded}}" aria-valuemin="0" aria-valuemax="100"></div>
                <div class="progress-bar bg-success" role="progressbar" style="width: {{$percentageLinked}}%" aria-valuenow="{{$percentageLinked}}" aria-valuemin="0" aria-valuemax="100"></div>
                <div class="progress-bar bg-danger" role="progressbar" style="width: {{$percentageError}}%" aria-valuenow="{{$percentageError}}" aria-valuemin="0" aria-valuemax="100"></div>
              </div>
        </div>
        
        
    </div>

    <div class="card">
        <div class="card-body">
            <div class="mb-3">
                <a href="{{route('commissions.calculation.linkAll',['id' => $commissionUpload->id])}}" class="btn btn-outline-primary">Link All</a>
                <button class="btn btn-primary">Link selected</button>
                <input type="hidden" id="headers" value="{{ json_encode($headersSnake) }}">
            </div>
            <div>
                <table class="table table-striped datatable min-w-100 "
                    data-url="{{ route('commissions.calculation.datatable', ['id' => $commissionUpload->id]) }}">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Status</th>
                            <th>Note</th>
                            @foreach ($headersTitle as $header)
                                <th>{{ $header }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop

@section('css')

@stop

@section('js')
    <script src="/js/commissions/datatableCommissionInt.js"></script>

@stop
