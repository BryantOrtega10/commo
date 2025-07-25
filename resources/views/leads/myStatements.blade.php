@extends('adminlte::page')
@section('plugins.Datatables', true)
@section('plugins.Sweetalert2', true)

@section('title', 'Leads')

@section('content_header')
    <h1>My Statements</h1>
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

    <div class="card card-light">
        <div class="card-header">
            Search filters
        </div>
        <form method="GET">
            <div class="card-body">
                <div class="row align-items-center">

                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="start_date">Start Date:</label>
                            <input type="date" class="form-control" id="start_date" name="start_date"
                                placeholder="Start Date:" value="{{ old('start_date') }}">
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="end_date">End Date:</label>
                            <input type="date" class="form-control" id="end_date" name="end_date"
                                placeholder="End Date:" value="{{ old('end_date') }}">
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <input type="submit" class="btn btn-outline-primary mr-3" value="Search" />
                    </div>
                </div>

            </div>
        </form>
    </div>


    <div class="card">
        <div class="card-body">
            <table class="table table-striped datatable min-w-100" data-url="">
                <thead>
                    <tr>
                        <th>Agent Number</th>
                        <th>Date</th>
                        <th>Total</th>
                        <th>Number of policies </th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($statements as $statement)
                        <tr>
                            <td>{{$statement->agent_number->number}}</td>
                            <td>{{date("m/d/Y", strtotime($statement->statement_date))}}</td>
                            <td>$ {{number_format($statement->total,2)}}</td>
                            <td>{{$statement->number_policies}}</td>
                            <td><a href="{{route('my-statements.generate',['id' => $statement->id])}}" class="btn btn-outline-primary">Download PDF</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@stop

@section('js')
    <script src="/js/leads/myNotifications.js"></script>
    <script src="/js/leads/mySettlementsDatatable.js"></script>

@stop
