@extends('adminlte::page')
@section('plugins.Datatables', true)
@section('plugins.Sweetalert2', true)

@section('title', 'Products')

@section('content_header')
    <div class="row">
        <div class="col-md-9">
            <h1>Products</h1>
        </div>
        <div class="text-right col-md-3">
            <a href="{{ route('products.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i>
                Enter a New Product</a>
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

    <div class="card card-light">
        <div class="card-header">
            Search filters
        </div>
        <form>
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-2 col-12">
                        <div class="form-group">
                            <label for="description">Plan description:</label>
                            <input type="text" class="form-control" id="description" name="description"
                                placeholder="Plan description:" value="{{ old('description') }}">
                        </div>
                    </div>
                    <div class="col-md-2 col-12">
                        <div class="form-group">
                            <label for="carrier">Carrier:</label>
                            <select id="carrier" name="carrier"
                                class="form-control @error('carrier') is-invalid @enderror">
                                <option value=""></option>
                                @foreach ($carriers as $carrier)
                                    <option value="{{ $carrier->id }}"
                                        @if (old('carrier') == $carrier->id) selected @endif>
                                        {{ $carrier->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2 col-12">
                        <div class="form-group">
                            <label for="business_type">Business Type:</label>
                            <select id="business_type" name="business_type"
                                class="form-control @error('business_type') is-invalid @enderror">
                                <option value=""></option>
                                @foreach ($business_types as $business_type)
                                    <option value="{{ $business_type->id }}"
                                        @if (old('business_type') == $business_type->id) selected @endif>
                                        {{ $business_type->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2 col-12">
                        <div class="form-group">
                            <label for="plan_type">Plan type:</label>
                            <input type="text" class="form-control" id="plan_type" name="plan_type"
                                placeholder="Plan type:" value="{{ old('plan_type') }}">
                        </div>
                    </div>
                    <div class="col-md-2 col-12">
                        <div class="form-group">
                            <label for="product_type">Product type:</label>
                            <input type="text" class="form-control" id="product_type" name="product_type"
                                placeholder="Product type:" value="{{ old('product_type') }}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <input type="submit" class="btn btn-outline-primary mr-3" value="Search" />
                        <a href="{{route('customers.show')}}"
                            class="btn btn-secondary"><i class="fas fa-redo"></i></a>
                    </div>
                </div>
            </div>
        </form>
    </div>


    <div class="card">
        <div class="card-body">
            <table class="table table-striped datatable min-w-100" data-url="{{ route('products.datatable') }}">
                <thead>
                    <tr>
                        <th>Plan Description</th>
                        <th>Carrier</th>
                        <th>Business Type</th>
                        <th>Plan Type</th>
                        <th>Product Type</th>
                        <th>Business Segment</th>
                        <th>Tier</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $product)
                        <tr>
                            <td><a href="{{ route('products.update', ['id' => $product->id]) }}"
                                    class="text-nowrap">{{ $product->description }}</a></td>
                            <td>{{$product->carrier?->name}}</td>
                            <td>{{$product->business_segment?->name}}</td>
                            <td>{{$product->business_type?->name}}</td>
                            <td>{{$product->product_type?->name}}</td>
                            <td>{{$product->plan_type?->name}}</td>
                            <td>{{$product->tier?->name}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@stop

@section('js')
    <script src="/js/products/datatable.js"></script>
@stop
