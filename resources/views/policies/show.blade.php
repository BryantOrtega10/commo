@extends('adminlte::page')
@section('plugins.Datatables', true)
@section('plugins.Sweetalert2', true)

@section('title', 'Policies')

@section('content_header')
    <div class="row">
        <div class="col-md-9">
            <h1>Policies</h1>
        </div>
        <div class="text-right col-md-3">
            <a href="{{ route('policies.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i>
                Enter a New Policy</a>
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
                    <div class="col-md-10">
                        <div class="row align-items-end">
                            <div class="col-md-2 col-12">
                                <div class="form-group">
                                    <label for="agent_number">Agent Number:</label>
                                    <input type="text" class="form-control" id="agent_number" name="agent_number"
                                        placeholder="Agent Number:" value="{{ old('agent_number') }}">
                                </div>
                            </div>
                            <div class="col-md-2 col-12">
                                <div class="form-group">
                                    <label for="cuid">CIUD:</label>
                                    <input type="text" class="form-control" id="cuid" name="cuid"
                                        placeholder="CUID:" value="{{ old('cuid') }}">
                                </div>
                            </div>

                            <div class="col-md-2 col-12">
                                <div class="form-group">
                                    <label for="product_type">Product Type:</label>
                                    <select id="product_type" name="product_type"
                                        class="form-control @error('product_type') is-invalid @enderror">
                                        <option value=""></option>
                                        @foreach ($product_types as $product_type)
                                            <option value="{{ $product_type->id }}"
                                                @if (old('product_type') == $product_type->id) selected @endif>
                                                {{ $product_type->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-2 col-12">
                                <div class="form-group">
                                    <label for="num_contract">Contract #:</label>
                                    <input type="text" class="form-control" id="num_contract" name="num_contract"
                                        placeholder="Contract #:" value="{{ old('num_contract') }}">
                                </div>
                            </div>

                            <div class="col-md-2 col-12">
                                <div class="form-group">
                                    <label for="application_id">Application Id:</label>
                                    <input type="text" class="form-control" id="application_id" name="application_id"
                                        placeholder="Application Id:" value="{{ old('application_id') }}">
                                </div>
                            </div>

                            <div class="col-md-2 col-12">
                                <div class="form-group">
                                    <label for="status">Policy Status:</label>
                                    <select id="status" name="status"
                                        class="form-control @error('status') is-invalid @enderror">
                                        <option value=""></option>
                                        <option value="0">Inactive</option>
                                        <option value="1">Active</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row another-fields d-none">
                           
                        </div>
                        <div class="text-center">
                            <a href="#" class="show-more">Show more fields</a>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <input type="submit" class="btn btn-outline-primary mr-3" value="Search" />
                        <a href="{{route('policies.show')}}"
                            class="btn btn-secondary"><i class="fas fa-redo"></i></a>
                    </div>
                </div>
            </div>
        </form>
    </div>


    <div class="card">
        <div class="card-body">
            <table class="table table-striped datatable min-w-100" data-url="{{ route('policies.datatable') }}">
                <thead>
                    <tr>
                        <th>Policy Id</th>
                        <th>Suscriber</th>
                        <th>Date of Birth</th>
                        <th>Carrier</th>
                        <th>Prod. Type</th>
                        <th>Plan Description</th>
                        <th>Application Id</th>
                        <th>Contract #</th>
                        <th>Orig. Eff.</th>
                        <th>Benefit Eff.</th>
                        <th>Cancel Date</th>
                        <th>Policy Status</th>
                        <th>Writting Agent</th>
                        <th>Writting Agent #</th>
                    </tr>
                </thead>
                <tbody>
                    
                </tbody>
            </table>
        </div>
    </div>
@stop

@section('js')
    <script src="/js/utils/show-more.js"></script>
    <script src="/js/policies/datatable.js"></script>
@stop
