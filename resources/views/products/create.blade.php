@extends('adminlte::page')
@section('plugins.Datatables', true)
@section('title', 'New Product')

@section('content_header')
    <h1>New Product</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('products.show') }}">Products</a></li>
            <li class="breadcrumb-item active" aria-current="page">New Product</li>
        </ol>
    </nav>
@stop

@section('content')
    <div class="card">
        <form action="{{ route('products.create') }}" method="post">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="description">Plan Description (*):</label>
                            <input type="text" class="form-control @error('description') is-invalid @enderror" id="description" name="description" placeholder="Plan Description:" value="{{ old('description') }}">
                            @error('description')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="carrier">Carrier:</label>
                            <select id="carrier" name="carrier" class="form-control @error('carrier') is-invalid @enderror">
                                <option value=""></option>
                                @foreach ($carriers as $carrier)
                                    <option value="{{$carrier->id}}" @if (old('carrier') == $carrier->id) selected @endif>{{$carrier->name}}</option>
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
                            <select id="business_type" name="business_type" class="form-control @error('business_type') is-invalid @enderror">
                                <option value=""></option>
                                @foreach ($business_types as $business_type)
                                    <option value="{{$business_type->id}}" @if (old('business_type') == $business_type->id) selected @endif>{{$business_type->name}}</option>
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
                            <select id="product_type" name="product_type" class="form-control @error('product_type') is-invalid @enderror">
                                <option value=""></option>
                                @foreach ($product_types as $product_type)
                                    <option value="{{$product_type->id}}" @if (old('product_type') == $product_type->id) selected @endif>{{$product_type->name}}</option>
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
                            <select id="plan_type" name="plan_type" class="form-control @error('plan_type') is-invalid @enderror">
                                <option value=""></option>
                                @foreach ($plan_types as $plan_type)
                                    <option value="{{$plan_type->id}}" @if (old('plan_type') == $plan_type->id) selected @endif>{{$plan_type->name}}</option>
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
                            <label for="tier">Tier:</label>
                            <select id="tier" name="tier" class="form-control @error('tier') is-invalid @enderror">
                                <option value=""></option>
                                @foreach ($tiers as $tier)
                                    <option value="{{$tier->id}}" @if (old('tier') == $tier->id) selected @endif>{{$tier->name}}</option>
                                @endforeach                                
                            </select>
                            @error('tier')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>
                <h5 class="d-inline-block text-primary">Alias</h5> <button class="btn btn-outline-success d-inline-block add-new-alias" type="button">Add New</button>
                <div class="alias-cont">
                    @foreach (old("alias",[]) as $index => $alias_item)
                        <div class="row align-items-end alias-item" data-id="{{$index}}">
                            <div class="col-md-6 col-8">
                                <div class="form-group">
                                    <label for="alias_{{$index}}" class="lb-alias">Alias {{$index + 1}}:</label>
                                    <input type="text" class="form-control @error("alias.".$index) is-invalid @enderror" id="alias_{{$index}}" name="alias[]" value="{{ old("alias.".$index)}}">
                                    @error("alias.".$index)
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6 col-4">
                                <button type="button" class="btn btn-outline-danger delete-alias mb-3" data-id="{{$index}}">Delete</button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="text-right card-footer">
                <input type="submit" class="btn btn-lg btn-primary" value="Save Product" />
            </div>
        </form>
    </div>
@stop

@section('js')
    <script src="/js/products/alias.js"></script>
@stop