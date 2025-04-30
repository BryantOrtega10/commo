@extends('adminlte::page')
@section('plugins.Datatables', true)
@section('plugins.Sweetalert2', true)

@section('title', 'Leads')

@section('content_header')
    <h1>My Settlements</h1>
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
        <form>
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
                        <th>Date</th>
                        <th>Total</th>
                        <th>Number of policies </th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($settlements as $settlement)
                        {{-- TODO: Tabla de settlements --}}
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
