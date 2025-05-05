@extends('adminlte::page')
@section('plugins.Datatables', true)
@section('title', 'Commission Calculation')

@section('content_header')
    <h1>Commission Calculation</h1>
    
@stop

@section('content')
    <div class="card">
        <form action="{{ route('commissions.calculation.import') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="card-header">
                Import Commission Transactions
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="template">Template:</label>
                            <select id="template" name="template"
                                class="form-control @error('template') is-invalid @enderror">
                                <option value=""></option>
                                @foreach ($templates as $template)
                                    <option value="{{ $template->id }}" @if (old('template') == $template->id) selected @endif>
                                        {{ $template->name }}</option>
                                @endforeach
                            </select>
                            @error('template')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="statement_date">Statement Date:</label>
                            <input type="date" class="form-control @error('statement_date') is-invalid @enderror"
                                id="statement_date" name="statement_date" value="{{ old('statement_date') }}">
                            @error('statement_date')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6 col-12">
                        <a href="#" download id="download-template" class="btn btn-outline-primary mt-4">Download Template</a>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="carrier">Carrier:</label>
                            <select id="carrier" name="carrier"
                                class="form-control @error('carrier') is-invalid @enderror">
                                <option value=""></option>
                                @foreach ($carriers as $carrier)
                                    <option value="{{ $carrier->id }}" @if (old('carrier') == $carrier->id) selected @endif>
                                        {{ $carrier->name }}</option>
                                @endforeach
                            </select>
                            @error('carrier')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="check_date">Check Date:</label>
                            <input type="date" class="form-control @error('check_date') is-invalid @enderror"
                                id="check_date" name="check_date" value="{{ old('check_date') }}">
                            @error('check_date')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="file-excel">Select excel file:</label>
                            <button type="button" class="btn btn-outline-primary select-excel">Select File</button>
                            <input type="file" id="file-excel" name="file-excel" class="d-none" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
                            @error('file-excel')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-right card-footer">
                <input type="submit" class="btn btn-primary" value="Submit" />
            </div>
        </form>
    </div>
@stop

@section('js')
    <script>
        $(document).ready(function(e) {
            $("body").on("click", ".select-excel", function() {
                $("#file-excel").trigger("click")
            })
        })
    </script>
@stop
