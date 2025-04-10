@extends('adminlte::page')
@section('plugins.Datatables', true)
@section('plugins.Dropzone', true)
@section('plugins.Sweetalert2', true)

@section('title', 'Edit Lead')

@section('content_header')
    <h1>Edit Lead</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('leads.show') }}">Leads</a></li>
            <li class="breadcrumb-item"><a href="{{ route('leads.details',['id' => $lead->id]) }}">Details</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit Lead</li>
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
        <form action="{{ route('leads.update', ['id' => $lead->id]) }}" method="post">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div
                        class="col-12 col-md-3">
                        <div class="form-group">
                            <label for="first_name">First Name (*):</label>
                            <input type="text" class="form-control @error('first_name') is-invalid @enderror"
                                id="first_name" name="first_name" placeholder="First Name"
                                value="{{ old('first_name', $lead->first_name) }}">
                            @error('first_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="middle_initial">Middle Initial:</label>
                            <input type="text" class="form-control @error('middle_initial') is-invalid @enderror"
                                id="middle_initial" name="middle_initial" placeholder="Middle Initial"
                                value="{{ old('middle_initial', $lead->middle_initial) }}">
                            @error('middle_initial')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="last_name">Last Name (*):</label>
                            <input type="text" class="form-control @error('last_name') is-invalid @enderror"
                                id="last_name" name="last_name" placeholder="Last Name:"
                                value="{{ old('last_name', $lead->last_name) }}">
                            @error('last_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 col-12 ">
                        <div class="form-group">
                            <label for="suffix">Suffix:</label>
                            <select id="suffix" name="suffix"
                                class="form-control @error('suffix') is-invalid @enderror">
                                @foreach ($suffixes as $suffix)
                                    <option value="{{ $suffix->id }}" @if (old('suffix', $lead->fk_suffix) == $suffix->id) selected @endif>
                                        {{ $suffix->name }}</option>
                                @endforeach
                            </select>
                            @error('suffix')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 col-12 ">
                        <div class="form-group">
                            <label for="date_birth">Date of Birth (*):</label>
                            <input type="date" class="form-control @error('date_birth') is-invalid @enderror"
                                id="date_birth" name="date_birth" placeholder="Date of Birth:"
                                value="{{ old('date_birth', $lead->date_birth) }}">
                            @error('date_birth')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 col-12 ">
                        <div class="form-group">
                            <label for="ssn">SSN:</label>
                            <input type="text" class="form-control @error('ssn') is-invalid @enderror" id="ssn"
                                name="ssn" placeholder="SSN:" value="{{ old('ssn', $lead->ssn) }}">
                            @error('ssn')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 col-12 ">
                        <div class="form-group">
                            <label for="gender">Gender:</label>
                            <select id="gender" name="gender"
                                class="form-control @error('gender') is-invalid @enderror">
                                @foreach ($genders as $gender)
                                    <option value="{{ $gender->id }}"
                                        @if (old('gender', $lead->fk_gender) == $gender->id) selected @endif>{{ $gender->name }}</option>
                                @endforeach
                            </select>
                            @error('gender')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 col-12 ">
                        <div class="form-group">
                            <label for="matiral_status">Marital Status:</label>
                            <select id="matiral_status" name="matiral_status"
                                class="form-control @error('matiral_status') is-invalid @enderror">
                                @foreach ($matiral_statuses as $matiral_status)
                                    <option value="{{ $matiral_status->id }}"
                                        @if (old('matiral_status', $lead->fk_matiral_status) == $matiral_status->id) selected @endif>{{ $matiral_status->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('matiral_status')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6 col-12">
                        <div class="form-group">
                            <label for="address">Address:</label>
                            <input type="text" class="form-control @error('address') is-invalid @enderror"
                                id="address" name="address" placeholder="Address:"
                                value="{{ old('address', $lead->address) }}">
                            @error('address')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6 col-12">
                        <div class="form-group">
                            <label for="address_2">Address 2:</label>
                            <input type="text" class="form-control @error('address_2') is-invalid @enderror"
                                id="address_2" name="address_2" placeholder="Address 2:"
                                value="{{ old('address_2', $lead->address_2) }}">
                            @error('address_2')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="email">Email:</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                id="email" name="email" placeholder="Email:"
                                value="{{ old('email', $lead->email) }}">
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="county">County:</label>
                            <select id="county" name="county"
                                class="form-control @error('county') is-invalid @enderror">
                                @foreach ($counties as $county)
                                    <option value="{{ $county->id }}"
                                        @if (old('county', $lead->fk_county) == $county->id) selected @endif>{{ $county->name }}</option>
                                @endforeach
                            </select>
                            @error('county')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            <input type="hidden" id="county-search-url" value="{{ route('counties.loadInfo') }}">
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="state">State:</label>
                            <input type="text" readonly class="form-control @error('state') is-invalid @enderror"
                                id="state" name="state" placeholder="State:"
                                value="{{ old('state', isset($lead->fk_county) > 0 ? $lead->county->state->name : '') }}">
                            @error('state')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="region">Region:</label>
                            <input type="text" readonly class="form-control @error('region') is-invalid @enderror"
                                id="region" name="region" placeholder="Region:"
                                value="{{ old('region', isset($lead->fk_county) > 0 ? $lead->county->region->name : '') }}">
                            @error('region')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="city">City:</label>
                            <input type="text" class="form-control @error('city') is-invalid @enderror"
                                id="city" name="city" placeholder="City:"
                                value="{{ old('city', $lead->city) }}">
                            @error('city')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="zip_code">Zip Code:</label>
                            <input type="text" class="form-control @error('zip_code') is-invalid @enderror"
                                id="zip_code" name="zip_code" placeholder="Zip Code:"
                                value="{{ old('zip_code', $lead->zip_code) }}">
                            @error('zip_code')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="phone">Phone:</label>
                            <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                                id="phone" name="phone" placeholder="Phone:"
                                value="{{ old('phone', $lead->phone) }}">
                            @error('phone')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="phone_2">Phone 2:</label>
                            <input type="tel" class="form-control @error('phone_2') is-invalid @enderror"
                                id="phone_2" name="phone_2" placeholder="Phone 2:"
                                value="{{ old('phone_2', $lead->phone_2) }}">
                            @error('phone_2')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="registration_source">Registration source:</label>
                            <select id="registration_source" name="registration_source"
                                class="form-control @error('registration_source') is-invalid @enderror">
                                @foreach ($registration_sources as $registration_source)
                                    <option value="{{ $registration_source->id }}"
                                        @if (old('registration_source', $lead->fk_registration_s) == $registration_source->id) selected @endif>
                                        {{ $registration_source->name }}</option>
                                @endforeach
                            </select>
                            @error('registration_source')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="status">Status:</label>
                            <select id="status" name="status"
                                class="form-control @error('customer_status') is-invalid @enderror">
                                @foreach ($customer_statuses as $lead_status)
                                    <option value="{{ $lead_status->id }}"
                                        @if (old('status', $lead->fk_status) == $lead_status->id) selected @endif>{{ $lead_status->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('status')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="phase">Phase:</label>
                            <select id="phase" name="phase"
                                class="form-control @error('phase') is-invalid @enderror">
                                @foreach ($phases as $phase)
                                    <option value="{{ $phase->id }}"
                                        @if (old('phase', $lead->fk_phase) == $phase->id) selected @endif>{{ $phase->name }}</option>
                                @endforeach
                            </select>
                            @error('phase')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="legal_basis">Legal Basis:</label>
                            <select id="legal_basis" name="legal_basis"
                                class="form-control @error('legal_basis') is-invalid @enderror">
                                @foreach ($legal_basis_m as $legal_basis)
                                    <option value="{{ $legal_basis->id }}"
                                        @if (old('legal_basis', $lead->fk_legal_basis) == $legal_basis->id) selected @endif>{{ $legal_basis->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('legal_basis')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="referring_customer">Referring Customer:</label>
                            <input type="text" readonly data-id="referring_customer_id"
                                class="search-customer-input search-icon form-control @error('referring_customer_id') is-invalid @enderror"
                                id="referring_customer" name="referring_customer" placeholder="Referring Customer:"
                                value="{{ old('referring_customer', isset($lead->fk_customer) ? $lead->customer->first_name . ' ' . $lead->customer->last_name : '') }}">
                            @error('referring_customer_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            <input type="hidden" readonly id="referring_customer_id" name="referring_customer_id"
                                value="{{ old('referring_customer_id', $lead->fk_customer) }}">
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-right card-footer">
                <input type="submit" class="btn btn-lg btn-primary" value="Save Lead" />
            </div>
        </form>
    </div>

    <div class="card card-light">
        <div class="card-header">
            File Attachments
        </div>
        <div class="card-body">
            <div class="files-container mb-3">
                @foreach ($lead->files as $file)
                    <div class="file-item">
                        <i class="fas fa-file"></i> {{ $file->name }}
                        <a href="{{ Storage::url('customers/' . $file->route) }}"
                            class="btn btn-outline-primary ml-3 mb-1" download="">Download</a>
                        <a href="{{ route('files.delete', ['id' => $file->id]) }}"
                            class="btn btn-outline-danger ml-3 mb-1 ask" data-message="Remove this file"><i
                                class="fas fa-trash"></i> </a>
                    </div>
                @endforeach
            </div>
            @include('files.partials.upload', ['customer_id' => $lead->id])
        </div>
    </div>



    @include('customers.partials.searchModal')
    <div class="py-5"></div>

@stop

@section('js')
    
    <script src="/js/customers/search-customer.js"></script>
    <script src="/js/counties/load-county-info.js"></script>
    
@stop
