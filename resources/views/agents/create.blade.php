@extends('adminlte::page')
@section('plugins.Datatables', true)
@section('title', 'New Agent')

@section('adminlte_css_pre')
    <link rel="stylesheet" href="{{ asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css') }}">
@stop

@section('content_header')
    <h1>New Agent</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('agents.show') }}">Agents</a></li>
            <li class="breadcrumb-item active" aria-current="page">New Agent</li>
        </ol>
    </nav>
@stop

@section('content')
    <div class="card">
        <form action="{{ route('agents.create') }}" method="post">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="first_name">First Name (*):</label>
                            <input type="text" class="form-control @error('first_name') is-invalid @enderror"
                                id="first_name" name="first_name" placeholder="First Name" value="{{ old('first_name') }}">
                            @error('first_name')
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
                                id="last_name" name="last_name" placeholder="Last Name" value="{{ old('last_name') }}">
                            @error('last_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="date_birth">Date of Birth:</label>
                            <input type="text" class="form-control @error('date_birth') is-invalid @enderror"
                                id="date_birth" name="date_birth" placeholder="Date of Birth"
                                value="{{ old('date_birth') }}">
                            @error('date_birth')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="ssn">SSN:</label>
                            <input type="text" class="form-control @error('ssn') is-invalid @enderror" id="ssn"
                                name="ssn" placeholder="SSN" value="{{ old('ssn') }}">
                            @error('ssn')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="gender">Gender:</label>
                            <select id="gender" name="gender"
                                class="form-control @error('gender') is-invalid @enderror">
                                @foreach ($genders as $gender)
                                    <option value="{{ $gender->id }}" @if (old('gender') == $gender->id) selected @endif>
                                        {{ $gender->name }}</option>
                                @endforeach
                            </select>
                            @error('gender')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="email">Email:</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                                name="email" placeholder="Email:" value="{{ old('email') }}">
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="phone">Phone:</label>
                            <input type="tel" class="form-control @error('phone') is-invalid @enderror" id="phone"
                                name="phone" placeholder="Phone:" value="{{ old('phone') }}">
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
                            <input type="tel" class="form-control @error('phone_2') is-invalid @enderror" id="phone_2"
                                name="phone_2" placeholder="Phone 2:" value="{{ old('phone_2') }}">
                            @error('phone_2')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="address">Address:</label>
                            <input type="text" class="form-control @error('address') is-invalid @enderror"
                                id="address" name="address" placeholder="Address:" value="{{ old('address') }}">
                            @error('address')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="address_2">Address 2:</label>
                            <input type="text" class="form-control @error('address_2') is-invalid @enderror"
                                id="address_2" name="address_2" placeholder="Address 2:"
                                value="{{ old('address_2') }}">
                            @error('address_2')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="state">State:</label>
                            <select id="state" name="state"
                                class="form-control @error('state') is-invalid @enderror">
                                @foreach ($states as $state)
                                    <option value="{{ $state->id }}"
                                        @if (old('state') == $state->id) selected @endif>{{ $state->name }}</option>
                                @endforeach
                            </select>
                            @error('state')
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
                                id="city" name="city" placeholder="City:" value="{{ old('city') }}">
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
                                id="zip_code" name="zip_code" placeholder="Zip Code:" value="{{ old('zip_code') }}">
                            @error('zip_code')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="national_producer">National Producer #:</label>
                            <input type="text" class="form-control @error('national_producer') is-invalid @enderror"
                                id="national_producer" name="national_producer" placeholder="National Producer #:"
                                value="{{ old('national_producer') }}">
                            @error('national_producer')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="license_number">License Number:</label>
                            <input type="text" class="form-control @error('license_number') is-invalid @enderror"
                                id="license_number" name="license_number" placeholder="License Number:"
                                value="{{ old('license_number') }}">
                            @error('license_number')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="sales_region">Sales Region:</label>
                            <select id="sales_region" name="sales_region"
                                class="form-control @error('sales_region') is-invalid @enderror">
                                @foreach ($sales_regions as $sales_region)
                                    <option value="{{ $sales_region->id }}"
                                        @if (old('sales_region') == $sales_region->id) selected @endif>{{ $sales_region->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('sales_region')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <div class="icheck-secondary" title="CMS Certification">
                                <input type="checkbox" name="has_CMS" id="has_CMS"
                                    {{ old('has_CMS') ? 'checked' : '' }}>
                                <label for="has_CMS">CMS Certification</label>
                            </div>
                            @error('has_CMS')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="CMS_date">CMS Date:</label>
                            <input type="date" class="form-control @error('CMS_date') is-invalid @enderror"
                                id="CMS_date" name="CMS_date" placeholder="CMS Date:" value="{{ old('CMS_date') }}">
                            @error('CMS_date')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <div class="icheck-secondary" title="Marketplace Certification">
                                <input type="checkbox" name="has_marketplace_cert" id="has_marketplace_cert"
                                    {{ old('has_marketplace_cert') ? 'checked' : '' }}>
                                <label for="has_marketplace_cert">Marketplace Certification</label>
                            </div>
                            @error('has_marketplace_cert')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="marketplace_cert_date">Marketplace Date:</label>
                            <input type="date"
                                class="form-control @error('marketplace_cert_date') is-invalid @enderror"
                                id="marketplace_cert_date" name="marketplace_cert_date" placeholder="Marketplace Date:"
                                value="{{ old('marketplace_cert_date') }}">
                            @error('marketplace_cert_date')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="contract_date">Contract Date:</label>
                            <input type="date" class="form-control @error('contract_date') is-invalid @enderror"
                                id="contract_date" name="contract_date" placeholder="Contract Date:"
                                value="{{ old('contract_date') }}">
                            @error('contract_date')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="payroll_emp_ID">Payroll/Emp ID:</label>
                            <input type="text" class="form-control @error('payroll_emp_ID') is-invalid @enderror"
                                id="payroll_emp_ID" name="payroll_emp_ID" placeholder="Payroll/Emp ID:"
                                value="{{ old('payroll_emp_ID') }}">
                            @error('payroll_emp_ID')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="contract_type">Contract Type:</label>
                            <select id="contract_type" name="contract_type"
                                class="form-control @error('contract_type') is-invalid @enderror">
                                @foreach ($contract_types as $contract_type)
                                    <option value="{{ $contract_type->id }}"
                                        @if (old('contract_type') == $contract_type->id) selected @endif>{{ $contract_type->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('contract_type')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="company_name">Company Name:</label>
                            <input type="text" class="form-control @error('company_name') is-invalid @enderror"
                                id="company_name" name="company_name" placeholder="Company Name:"
                                value="{{ old('company_name') }}">
                            @error('company_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="company_EIN">Company EIN:</label>
                            <input type="text" class="form-control @error('company_EIN') is-invalid @enderror"
                                id="company_EIN" name="company_EIN" placeholder="Company EIN:"
                                value="{{ old('company_EIN') }}">
                            @error('company_EIN')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label for="agent_notes">Agent Notes:</label>
                            <textarea class="form-control @error('agent_notes') is-invalid @enderror" rows="2" id="agent_notes"
                                name="agent_notes">{{ old('agent_notes') }}</textarea>
                            @error('agent_notes')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-right card-footer">
                <input type="submit" class="btn btn-lg btn-primary" value="Save Agent" />
            </div>
        </form>
    </div>
@stop

@section('js')

@stop
