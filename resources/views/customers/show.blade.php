@extends('adminlte::page')
@section('plugins.Datatables', true)
@section('plugins.Sweetalert2', true)

@section('title', 'Customers')

@section('content_header')
    <div class="row">
        <div class="col-md-9">
            <h1>Customers</h1>
        </div>
        <div class="text-right col-md-3">
            <a href="{{ route('customers.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i>
                Enter a New Customer</a>
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
                                    <label for="customer_id">Customer Id:</label>
                                    <input type="text" class="form-control" id="customer_id" name="customer_id"
                                        placeholder="Customer Id:" value="{{ old('customer_id') }}">
                                </div>
                            </div>
                            <div class="col-md-2 col-12">
                                <div class="form-group">
                                    <label for="first_name">First/Group Name:</label>
                                    <input type="text" class="form-control" id="first_name" name="first_name"
                                        placeholder="First/Group Name:" value="{{ old('first_name') }}">
                                </div>
                            </div>

                            <div class="col-md-2 col-12">
                                <div class="form-group">
                                    <label for="date_birth">Date of Birth:</label>
                                    <input type="text" class="form-control" id="date_birth" name="date_birth"
                                        placeholder="Date of Birth:" value="{{ old('date_birth') }}">
                                </div>
                            </div>

                            <div class="col-md-2 col-12">
                                <div class="form-group">
                                    <label for="ssn">SSN:</label>
                                    <input type="text" class="form-control" id="ssn" name="ssn"
                                        placeholder="SSN:" value="{{ old('ssn') }}">
                                </div>
                            </div>

                            <div class="col-md-2 col-12">
                                <div class="form-group">
                                    <label for="phone">Phone:</label>
                                    <input type="text" class="form-control" id="phone" name="phone"
                                        placeholder="Phone:" value="{{ old('phone') }}">
                                </div>
                            </div>

                            <div class="col-md-2 col-12">
                                <div class="form-group">
                                    <label for="address">Address:</label>
                                    <input type="text" class="form-control" id="address" name="address"
                                        placeholder="Address:" value="{{ old('address') }}">
                                </div>
                            </div>
                        </div>
                        <div class="row another-fields d-none">
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
                                    <label for="middle_initial">Middle Initial:</label>
                                    <input type="text" class="form-control @error('middle_initial') is-invalid @enderror"
                                        id="middle_initial" name="middle_initial" placeholder="Middle Initial:"
                                        value="{{ old('middle_initial') }}">
                                </div>
                            </div>
                            <div class="col-md-2 col-12">
                                <div class="form-group">
                                    <label for="last_name">Last Name (*):</label>
                                    <input type="text" class="form-control @error('last_name') is-invalid @enderror"
                                        id="last_name" name="last_name" placeholder="Last Name:"
                                        value="{{ old('last_name') }}">
                                </div>
                            </div>
                            <div class="col-md-2 col-12">
                                <div class="form-group">
                                    <label for="suffix">Suffix:</label>
                                    <select id="suffix" name="suffix"
                                        class="form-control @error('suffix') is-invalid @enderror">
                                        <option value=""></option>
                                        @foreach ($suffixes as $suffix)
                                            <option value="{{ $suffix->id }}"
                                                @if (old('suffix') == $suffix->id) selected @endif>{{ $suffix->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2 col-12">
                                <div class="form-group">
                                    <label for="gender">Gender:</label>
                                    <select id="gender" name="gender"
                                        class="form-control @error('gender') is-invalid @enderror">
                                        <option value=""></option>
                                        @foreach ($genders as $gender)
                                            <option value="{{ $gender->id }}"
                                                @if (old('gender') == $gender->id) selected @endif>{{ $gender->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2 col-12">
                                <div class="form-group">
                                    <label for="matiral_status">Marital Status:</label>
                                    <select id="matiral_status" name="matiral_status"
                                        class="form-control @error('matiral_status') is-invalid @enderror">
                                        <option value=""></option>
                                        @foreach ($matiral_statuses as $matiral_status)
                                            <option value="{{ $matiral_status->id }}"
                                                @if (old('matiral_status') == $matiral_status->id) selected @endif>
                                                {{ $matiral_status->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2 col-12">
                                <div class="form-group">
                                    <label for="email">Email:</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                        id="email" name="email" placeholder="Email:" value="{{ old('email') }}">
                                </div>
                            </div>
                            <div class="col-md-2 col-12">
                                <div class="form-group">
                                    <label for="address_2">Address 2:</label>
                                    <input type="text" class="form-control @error('address_2') is-invalid @enderror"
                                        id="address_2" name="address_2" placeholder="Address 2:"
                                        value="{{ old('address_2') }}">
                                </div>
                            </div>
                            <div class="col-md-2 col-12">
                                <div class="form-group">
                                    <label for="county">County:</label>
                                    <select id="county" name="county"
                                        class="form-control @error('county') is-invalid @enderror">
                                        <option value=""></option>
                                        @foreach ($counties as $county)
                                            <option value="{{ $county->id }}"
                                                @if (old('county') == $county->id) selected @endif>{{ $county->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2 col-12">
                                <div class="form-group">
                                    <label for="city">City:</label>
                                    <input type="text" class="form-control @error('city') is-invalid @enderror"
                                        id="city" name="city" placeholder="City:" value="{{ old('city') }}">
                                </div>
                            </div>
                            <div class="col-md-2 col-12">
                                <div class="form-group">
                                    <label for="zip_code">Zip Code:</label>
                                    <input type="text" class="form-control @error('zip_code') is-invalid @enderror"
                                        id="zip_code" name="zip_code" placeholder="Zip Code:"
                                        value="{{ old('zip_code') }}">
                                </div>
                            </div>
                            <div class="col-md-2 col-12">
                                <div class="form-group">
                                    <label for="phone_2">Phone 2:</label>
                                    <input type="tel" class="form-control @error('phone_2') is-invalid @enderror"
                                        id="phone_2" name="phone_2" placeholder="Phone 2:"
                                        value="{{ old('phone_2') }}">
                                </div>
                            </div>
                            <div class="col-md-2 col-12">
                                <div class="form-group">
                                    <label for="registration_source">Registration source:</label>
                                    <select id="registration_source" name="registration_source"
                                        class="form-control @error('registration_source') is-invalid @enderror">
                                        <option value=""></option>
                                        @foreach ($registration_sources as $registration_source)
                                            <option value="{{ $registration_source->id }}"
                                                @if (old('registration_source') == $registration_source->id) selected @endif>
                                                {{ $registration_source->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2 col-12">
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
                            <div class="col-md-2 col-12">
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
                            <div class="col-md-2 col-12">
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
                        <input type="submit" class="btn btn-outline-primary mr-3" value="Search" /><button
                            type="reset" class="btn btn-secondary"><i class="fas fa-redo"></i></button>
                    </div>
                </div>
            </div>
        </form>
    </div>


    <div class="card">
        <div class="card-body">
            <table class="table table-striped datatable min-w-100" data-url="{{ route('customers.datatable') }}">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Customer</th>
                        <th>Date of Birth</th>
                        <th>Address</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Age</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($customers as $customer)
                        <tr>
                            <td>{{ $customer->id }}</td>
                            <td><a href="{{ route('customers.update', ['id' => $customer->id]) }}"
                                    class="text-nowrap">{{ $customer->first_name }} {{ $customer->last_name }}</a></td>
                            <td>
                                @isset($customer->date_birth)
                                    {{ date('m/d/Y', strtotime($customer->date_birth)) }}
                                @endisset
                            </td>
                            <td>{{ $customer->address }}</td>
                            <td>{{ $customer->phone }}</td>
                            <td>{{ $customer->email }}</td>
                            <td>{{ $customer->txt_age }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="detailsModal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-4"><b>Name:</b></div>
                        <div class="col-8"><span id="details-name"></span></div>
                    </div>
                    <div class="row">
                        <div class="col-4"><b>Description:</b></div>
                        <div class="col-8"><span id="details-description"></span></div>
                    </div>
                    <div class="row">
                        <div class="col-4"><b>Sort Order:</b></div>
                        <div class="col-8"><span id="details-sort-order"></span></div>
                    </div>
                    <div class="row">
                        <div class="col-4"><b>Status:</b></div>
                        <div class="col-8"><span class="p-0 px-3 alert text-bigger" id="details-status"></span></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
    <script src="/js/utils/show-more.js"></script>
    <script src="/js/customers/datatable.js"></script>
@stop
