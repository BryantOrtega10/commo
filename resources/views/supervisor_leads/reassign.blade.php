@extends('adminlte::page')
@section('plugins.Datatables', true)
@section('plugins.Dropzone', true)
@section('plugins.Sweetalert2', true)

@section('title', 'Reassign Lead')

@section('content_header')
    <h1>Reassign Lead</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('supervisor.leads.show') }}">Leads</a></li>
            <li class="breadcrumb-item active" aria-current="page">Reassign</li>
        </ol>
    </nav>
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
        <form action="{{ route('supervisor.leads.reassign', ['id' => $lead->id]) }}" method="post">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="agent">Contact Agent:</label>
                            <select id="agent" name="agent" class="form-control">
                                <option value=""></option>
                                @foreach ($agents as $agent)
                                    <option value="{{ $agent->id }}"
                                        @if (old('agent', $lead->fk_agent) == $agent->id) selected @endif>{{ $agent->id }} - {{ $agent->first_name }} {{ $agent->last_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('agent')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-right card-footer">
                <input type="submit" class="btn btn-lg btn-primary" value="Save Lead" />
            </div>
        </form>
    </div>

 
    <div class="py-5"></div>

@stop

@section('js')
    
@stop
