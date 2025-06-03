@extends('adminlte::page')
@section('plugins.Sweetalert2', true)

@section('title', 'Policies Report')


@section('content_header')
    <div class="row">
        <div class="col-md-12">
            <h1>Policies Report</h1>
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
    <form action="{{ route('reports.policies.show') }}" method="POST">
        @csrf
        <div class="card card-light">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="app_submit_date_start">App Submit Date Start:</label>
                            <input type="date" class="form-control @error('app_submit_date_start') is-invalid @enderror"
                                id="app_submit_date_start" name="app_submit_date_start" value="{{ old('app_submit_date_start') }}">
                            @error('app_submit_date_start')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="app_submit_date_end">App Submit Date End:</label>
                            <input type="date" class="form-control @error('app_submit_date_end') is-invalid @enderror"
                                id="app_submit_date_end" name="app_submit_date_end" value="{{ old('app_submit_date_end') }}">
                            @error('app_submit_date_end')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="original_effective_date_start">Original Effective Date Start:</label>
                            <input type="date" class="form-control @error('original_effective_date_start') is-invalid @enderror"
                                id="original_effective_date_start" name="original_effective_date_start" value="{{ old('original_effective_date_start') }}">
                            @error('original_effective_date_start')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="original_effective_date_end">Original Effective Date End:</label>
                            <input type="date" class="form-control @error('original_effective_date_end') is-invalid @enderror"
                                id="original_effective_date_end" name="original_effective_date_end" value="{{ old('original_effective_date_end') }}">
                            @error('original_effective_date_end')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="benefit_effective_date_start">Benefit Effective Date Start:</label>
                            <input type="date" class="form-control @error('benefit_effective_date_start') is-invalid @enderror"
                                id="benefit_effective_date_start" name="benefit_effective_date_start" value="{{ old('benefit_effective_date_start') }}">
                            @error('benefit_effective_date_start')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="benefit_effective_date_end">Benefit Effective Date End:</label>
                            <input type="date" class="form-control @error('benefit_effective_date_end') is-invalid @enderror"
                                id="benefit_effective_date_end" name="benefit_effective_date_end" value="{{ old('benefit_effective_date_end') }}">
                            @error('benefit_effective_date_end')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="client_source">Client Source:</label>
                            <select id="client_source" name="client_source"
                                class="form-control @error('client_source') is-invalid @enderror">
                                <option value=""></option>
                                @foreach ($registration_sources as $client_source)
                                    <option value="{{ $client_source->id }}" @if (old('client_source') == $client_source->id) selected @endif>
                                        {{ $client_source->name }}</option>
                                @endforeach
                            </select>
                            @error('client_source')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="agent_number">Writting Agent Number:</label>
                            <select id="agent_number" name="agent_number" class="form-control">
                                <option value=""></option>
                                @foreach ($agentNumbers as $agentNumber)
                                    <option value="{{ $agentNumber->id }}"
                                        @if (old('agent_number') == $agentNumber->id) selected @endif>{{ $agentNumber->number }} - {{ $agentNumber->agent->first_name }} {{ $agentNumber->agent->last_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="business_segment">Business Segment:</label>
                            <select id="business_segment" name="business_segment" class="form-control @error('business_segment') is-invalid @enderror">
                                <option value=""></option>
                                @foreach ($business_segments as $business_segment)
                                    <option value="{{$business_segment->id}}" @if (old('business_segment') == $business_segment->id) selected @endif>{{$business_segment->name}}</option>
                                @endforeach                                
                            </select>
                            @error('business_segment')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="business_type">Business Type:</label>
                            <select id="business_type" name="business_type"
                                class="form-control @error('business_type') is-invalid @enderror">
                                <option value=""></option>
                                @foreach ($business_types as $business_type)
                                    <option value="{{ $business_type->id }}"
                                        @if (old('business_type') == $business_type->id) selected @endif>{{ $business_type->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('business_type')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
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
                            <label for="description">Plan Description:</label>
                            <input type="text" class="form-control @error('description') is-invalid @enderror"
                                id="description" name="description" placeholder="Plan Description:"
                                value="{{ old('description') }}">
                            @error('description')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="plan_type">Plan Type:</label>
                            <select id="plan_type" name="plan_type"
                                class="form-control @error('plan_type') is-invalid @enderror">
                                <option value=""></option>
                                @foreach ($plan_types as $plan_type)
                                    <option value="{{ $plan_type->id }}" @if (old('plan_type') == $plan_type->id) selected @endif>
                                        {{ $plan_type->name }}</option>
                                @endforeach
                            </select>
                            @error('plan_type')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="product_type">Product Type:</label>
                            <select id="product_type" name="product_type"
                                class="form-control @error('product_type') is-invalid @enderror">
                                <option value=""></option>
                                @foreach ($product_types as $product_type)
                                    <option value="{{ $product_type->id }}"
                                        @if (old('product_type') == $product_type->id) selected @endif>{{ $product_type->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('product_type')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
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
