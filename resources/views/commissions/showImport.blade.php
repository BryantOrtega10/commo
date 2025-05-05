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
        <div class="card-body">
            <div class="mb-3">
                <a href="#" class="btn btn-outline-primary">Link All</a>
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
