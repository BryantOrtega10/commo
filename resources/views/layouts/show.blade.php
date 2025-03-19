@extends('adminlte::page')
@section('plugins.Datatables', true)
@section('plugins.DefaultDatatable', true)

@section('plugins.Sweetalert2', true)

@section('title', $defaults['plural-title'])

@section('content_header')
    <div class="row">
        <div class="col-md-9">
            <h1>{{ $defaults['plural-title'] }}</h1>
        </div>
        <div class="text-right col-md-3">
            <a href="{{ route($defaults['base-route'] . '.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i>
                Create New</a>
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
    <div class="card">
        <div class="card-body">
            <table class="table table-striped datatable">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Sort Order</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($items as $item)
                        <tr>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->description }}</td>
                            <td>{{ $item->sort_order }}</td>
                            <td>
                                <span
                                    class="p-0 px-3 alert {{ $item->status ? 'alert-success' : 'alert-secondary' }} text-bigger">{{ $item->txt_status }}</span>
                            </td>
                            <td class="text-right">
                                <a href="{{ route($defaults['base-route'] . '.update', ['id' => $item->id]) }}"
                                    class="btn btn-outline-primary"><i class="fas fa-pen"></i> Edit</a>
                                <a href="{{ route($defaults['base-route'] . '.details', ['id' => $item->id]) }}"
                                    class="btn btn-outline-secondary details"><i class="fas fa-eye"></i> Details</a>
                                <a href="{{ route($defaults['base-route'] . '.delete', ['id' => $item->id]) }}"
                                    class="btn btn-outline-danger ask"
                                    data-message="Delete this {{ $defaults['singular-title'] }}"><i
                                        class="fas fa-trash"></i> Delete</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @include("layouts.partials.detailsModal")
@stop

@section('js')
    <script src="/js/multiple-tables/details.js"></script>
@stop
