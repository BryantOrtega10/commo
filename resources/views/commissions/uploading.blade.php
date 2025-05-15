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
        <div class="card-body text-center">
            <input type="hidden" id="url" value="{{route('commissions.calculation.loadUploadedRows', ['id' => $commissionUpload->id])}}">
            <h2><span class="uploaded-rows">{{$commissionUpload->uploaded_rows}}</span> Uploaded<br>rows</h2>
            <div class="loader m-auto"></div>
        </div>
    </div>
@stop

@section('css')
    <link href="/css/loader.css" rel="stylesheet">
@stop

@section('js')
    <script src="/js/commissions/reloadRowsUploaded.js"></script>
@stop
