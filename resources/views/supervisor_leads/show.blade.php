@extends('adminlte::page')
@section('plugins.Datatables', true)
@section('plugins.Sweetalert2', true)

@section('title', 'Leads')

@section('content_header')
    <div class="row">
        <div class="col-md-9">
            <h1>Leads</h1>
        </div>
        <div class="text-right col-md-3">
            <a href="{{ route('supervisor.leads.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i>
                Create Lead</a>
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
                            <div class="col-md-3 col-12">
                                <div class="form-group">
                                    <label for="first_name">First Name:</label>
                                    <input type="text" class="form-control" id="first_name" name="first_name"
                                        placeholder="First Name:" value="{{ old('first_name') }}">
                                </div>
                            </div>
                            <div class="col-md-3 col-12">
                                <div class="form-group">
                                    <label for="last_name">Last Name:</label>
                                    <input type="text" class="form-control @error('last_name') is-invalid @enderror"
                                        id="last_name" name="last_name" placeholder="Last Name:"
                                        value="{{ old('last_name') }}">
                                </div>
                            </div>
                            <div class="col-md-3 col-12">
                                <div class="form-group">
                                    <label for="email">Email:</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                        id="email" name="email" placeholder="Email:" value="{{ old('email') }}">
                                </div>
                            </div>
                            <div class="col-md-3 col-12">
                                <div class="form-group">
                                    <label for="phone">Phone:</label>
                                    <input type="text" class="form-control" id="phone" name="phone"
                                        placeholder="Phone:" value="{{ old('phone') }}">
                                </div>
                            </div>

                        </div>
                        <div class="row another-fields d-none">
                            <div class="col-md-3 col-12">
                                <div class="form-group">
                                    <label for="status">Status:</label>
                                    <select id="status" name="status"
                                        class="form-control @error('customer_status') is-invalid @enderror">
                                        <option value=""></option>
                                        @foreach ($customer_statuses as $customer_status)
                                            <option value="{{ $customer_status->id }}"
                                                @if (old('status') == $customer_status->id) selected @endif>
                                                {{ $customer_status->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 col-12">
                                <div class="form-group">
                                    <label for="date_birth_from">From Date of Birth:</label>
                                    <input type="text" class="form-control" id="date_birth_from" name="date_birth_from"
                                        placeholder="From Date of Birth:" value="{{ old('date_birth_from') }}">
                                </div>
                            </div>
                            <div class="col-md-3 col-12">
                                <div class="form-group">
                                    <label for="date_birth_to">To Date of Birth:</label>
                                    <input type="text" class="form-control" id="date_birth_to" name="date_birth_to"
                                        placeholder="To Date of Birth:" value="{{ old('date_birth_to') }}">
                                </div>
                            </div>
                            <div class="col-md-3 col-12">
                                <div class="form-group">
                                    <label for="phase">Phase:</label>
                                    <select id="phase" name="phase"
                                        class="form-control @error('phase') is-invalid @enderror">
                                        <option value=""></option>
                                        @foreach ($phases as $phase)
                                            <option value="{{ $phase->id }}"
                                                @if (old('phase') == $phase->id) selected @endif>{{ $phase->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 col-12">
                                <div class="form-group">
                                    <label for="legal_basis">Legal Basis:</label>
                                    <select id="legal_basis" name="legal_basis"
                                        class="form-control @error('legal_basis') is-invalid @enderror">
                                        <option value=""></option>
                                        @foreach ($legal_basis_m as $legal_basis)
                                            <option value="{{ $legal_basis->id }}"
                                                @if (old('legal_basis') == $legal_basis->id) selected @endif>
                                                {{ $legal_basis->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                        </div>
                        <div class="text-center">
                            <a href="#" class="show-more">Show more fields</a>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <input type="submit" class="btn btn-outline-primary mr-3" value="Search" />
                        <a href="{{ route('supervisor.leads.show') }}" class="btn btn-secondary"><i class="fas fa-redo"></i></a>
                    </div>
                </div>
            </div>
        </form>
    </div>


    <div class="card">
        <div class="card-body">
            <table class="table table-striped datatable min-w-100" data-url="{{ route('supervisor.leads.datatable') }}">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Status</th>
                        <th>Agent</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($leads as $lead)
                        <tr>
                            <td><a href="{{ route('supervisor.leads.details', ['id' => $lead->id]) }}"
                                    class="text-nowrap">{{ $lead->first_name }} {{ $lead->last_name }}</a></td>
                            <td>{{ $lead->email }}</td>
                            <td>{{ $lead->phone }}</td>
                            <td>{{ $lead->status?->name }}</td>
                            <td><a href="{{ route('supervisor.leads.details', ['id' => $lead->id]) }}"
                                    class="btn btn-outline-primary">View</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @if ($change_pass || $errors->addActivityForm->any())
        @include('users.partials.changePasswordModal')
    @endif
@stop

@section('js')
    <script src="/js/utils/show-more.js"></script>
    <script src="/js/supervisor-leads/datatable.js"></script>
    @if ($change_pass || $errors->addActivityForm->any())
        <script>
            $("#changePasswordModal").modal("show")
        </script>
    @endif
@stop
