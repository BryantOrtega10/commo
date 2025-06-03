@extends('adminlte::page')
@section('plugins.Sweetalert2', true)

@section('title', 'Products Report')


@section('content_header')
    <div class="row">
        <div class="col-md-12">
            <h1>Products Report</h1>
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
    <form action="{{ route('reports.product.show') }}" method="POST">
        @csrf
        <div class="card card-light">
            <div class="card-body">
                <div class="row">
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
                    
                        <div class="col-md-3">
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
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="plan_type">Plan Type:</label>
                            <select id="plan_type" name="plan_type"
                                class="form-control @error('plan_type') is-invalid @enderror">
                                <option value=""></option>
                                @foreach ($plan_types as $plan_type)
                                    <option value="{{ $plan_type->id }}"
                                        @if (old('plan_type') == $plan_type->id) selected @endif>
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
                </div>
            </div>
            <div class="card-footer text-right">
                <input type="submit" class="btn btn-primary" value="Export to Excel" />
            </div>
    </form>
@stop

@section('js')

@stop
