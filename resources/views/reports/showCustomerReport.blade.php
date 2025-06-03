@extends('adminlte::page')
@section('plugins.Sweetalert2', true)

@section('title', 'Customer Report')


@section('content_header')
    <div class="row">
        <div class="col-md-12">
            <h1>Customer Report</h1>
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
    <form action="{{ route('reports.customer.show') }}" method="POST">
        @csrf
        <div class="card card-light">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="date_birth_start">Date of Birth Start :</label>
                            <input type="date" class="form-control @error('date_birth_start') is-invalid @enderror"
                                id="date_birth_start" name="date_birth_start" value="{{ old('date_birth_start') }}">
                            @error('date_birth_start')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="date_birth_end">Date of Birth End :</label>
                            <input type="date" class="form-control @error('date_birth_end') is-invalid @enderror"
                                id="date_birth_end" name="date_birth_end" value="{{ old('date_birth_end') }}">
                            @error('date_birth_end')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="city">Date of Birth End :</label>
                            <input type="text" class="form-control @error('city') is-invalid @enderror" id="city"
                                name="city" value="{{ old('city') }}">
                            @error('city')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="county">County:</label>
                            <select id="county" name="county" class="form-control">
                                <option value=""></option>
                                @foreach ($counties as $county)
                                    <option value="{{ $county->id }}" @if (old('county') == $county->id) selected @endif>
                                        {{ $county->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="contact_agent">Contact Agent:</label>
                            <select id="contact_agent" name="contact_agent" class="form-control">
                                <option value=""></option>
                                @foreach ($agents as $contact_agent)
                                    <option value="{{ $contact_agent->id }}"
                                        @if (old('contact_agent') == $contact_agent->id) selected @endif>{{ $contact_agent->first_name }}
                                        {{ $contact_agent->last_name }}
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
