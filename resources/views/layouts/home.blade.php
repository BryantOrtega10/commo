@extends('adminlte::page')
@section('plugins.Datatables', true)
@section('plugins.Sweetalert2', true)

@section('title', 'Home')

@section('content_header')
    <div class="row">
        <div class="col-md-9">
            <h1>Hi, {{ auth()->user()->name }}</h1>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-12 col-md-4">
            <a href="{{ route('policies.show') }}">
                <div class="card card-home">
                    <div class="card-body">
                        <i class="fas fa-shield-alt"></i>
                        <span>Policies</span>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-12 col-md-4">
            <a href="{{ route('customers.show') }}">
                <div class="card card-home">
                    <div class="card-body">
                        <i class="fas fa-user-circle"></i>
                        <span>Customers</span>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-12 col-md-4">
            <a href="{{ route('agents.show') }}">
                <div class="card card-home">
                    <div class="card-body">
                        <i class="fas fa-headset"></i>
                        <span>Agents</span>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-12 col-md-4">
            <a href="{{ route('products.show') }}">
                <div class="card card-home">
                    <div class="card-body">
                        <i class="fas fa-tags"></i>
                        <span>Products</span>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-12 col-md-4">
            <a href="{{ route('reports.agent.show') }}">
                <div class="card card-home">
                    <div class="card-body">
                        <i class="fas fa-file"></i>
                        <span>Reports</span>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-12 col-md-4">
            <a href="{{ route('commissions.calculation') }}">
                <div class="card card-home">
                    <div class="card-body">
                        <i class="fas fa-receipt"></i>
                        <span>Commissions</span>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-12 col-md-4">
            <a href="{{ route('users.show') }}">
                <div class="card card-home">
                    <div class="card-body">
                        <i class="fas fa-users"></i>
                        <span>Users</span>
                    </div>
                </div>
            </a>
        </div>

    </div>
@stop
