@extends('adminlte::page')
@section('plugins.Datatables', true)
@section('plugins.Dropzone', true)
@section('plugins.Sweetalert2', true)
@section('title', 'Agent Details')

@section('adminlte_css_pre')
    <link rel="stylesheet" href="{{ asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css') }}">
@stop

@section('content_header')
    <h1>Agent Details</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('agents.show') }}">Agents</a></li>
            <li class="breadcrumb-item active" aria-current="page">Details</li>
        </ol>
    </nav>
@stop

@section('content')
    <div class="card">
        <form action="{{ route('agents.update', ['id' => $agent->id]) }}" method="post">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="first_name">First Name (*):</label>
                            <input type="text" class="form-control @error('first_name') is-invalid @enderror"
                                id="first_name" name="first_name" placeholder="First Name"
                                value="{{ old('first_name', $agent->first_name) }}">
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
                                id="last_name" name="last_name" placeholder="Last Name"
                                value="{{ old('last_name', $agent->last_name) }}">
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
                                value="{{ old('date_birth', $agent->date_birth) }}">
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
                                name="ssn" placeholder="SSN" value="{{ old('ssn', $agent->ssn) }}">
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
                                    <option value="{{ $gender->id }}" @if (old('gender', $agent->gender) == $gender->id) selected @endif>
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
                                name="email" placeholder="Email:" value="{{ old('email', $agent->email) }}">
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
                                name="phone" placeholder="Phone:" value="{{ old('phone', $agent->phone) }}">
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
                                name="phone_2" placeholder="Phone 2:" value="{{ old('phone_2', $agent->phone_2) }}">
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
                                id="address" name="address" placeholder="Address:"
                                value="{{ old('address', $agent->address) }}">
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
                                value="{{ old('address_2', $agent->address_2) }}">
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
                                        @if (old('state', $agent->fk_state) == $state->id) selected @endif>{{ $state->name }}</option>
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
                                id="city" name="city" placeholder="City:"
                                value="{{ old('city', $agent->city) }}">
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
                                value="{{ old('zip_code', $agent->zip_code) }}">
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
                                value="{{ old('national_producer', $agent->national_producer) }}">
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
                                value="{{ old('license_number', $agent->license_number) }}">
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
                                        @if (old('sales_region', $agent->fk_sales_region) == $sales_region->id) selected @endif>{{ $sales_region->name }}
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
                                    {{ old('has_CMS', $agent->has_CMS) ? 'checked' : '' }}>
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
                                id="CMS_date" name="CMS_date" placeholder="CMS Date:"
                                value="{{ old('CMS_date', $agent->CMS_date) }}">
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
                                    {{ old('has_marketplace_cert', $agent->has_marketplace_cert) ? 'checked' : '' }}>
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
                                value="{{ old('marketplace_cert_date', $agent->marketplace_cert_date) }}">
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
                                value="{{ old('contract_date', $agent->contract_date) }}">
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
                                value="{{ old('payroll_emp_ID', $agent->payroll_emp_ID) }}">
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
                                        @if (old('contract_type', $agent->fk_contract_type) == $contract_type->id) selected @endif>{{ $contract_type->name }}
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
                            <label for="company_EIN">Company EIN:</label>
                            <input type="text" class="form-control @error('company_EIN') is-invalid @enderror"
                                id="company_EIN" name="company_EIN" placeholder="Company EIN:"
                                value="{{ old('company_EIN', $agent->company_EIN) }}">
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
                                name="agent_notes">{{ old('agent_notes', $agent->agent_notes) }}</textarea>
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

    <div class="card card-light">
        <div class="card-header">
            File Attachments
        </div>
        <div class="card-body">
            <div class="files-container mb-3">
                @foreach ($agent->files as $file)
                    <div class="file-item">
                        <i class="fas fa-file"></i> {{ $file->name }}
                        <a href="{{ Storage::url('agents/' . $file->route) }}" class="btn btn-outline-primary ml-3 mb-1"
                            download="">Download</a>
                        <a href="{{ route('files.delete', ['id' => $file->id]) }}"
                            class="btn btn-outline-danger ml-3 mb-1 ask" data-message="Remove this file"><i
                                class="fas fa-trash"></i> </a>
                    </div>
                @endforeach
            </div>
            @include('files.partials.upload', ['agent_id' => $agent->id])
        </div>
    </div>

    <div class="card card-light">
        <div class="card-header">
            Agent Numbers
        </div>
        <div class="card-body px-3 py-3">
            <a href="{{ route('agent_numbers.create', ['id' => $agent->id]) }}"
                class="btn btn-outline-success mb-3 add-cuid">Add New</a>
            <table class="table table-striped min-w-100" id="agent-numbers-table">
                <thead>
                    <tr>
                        <th>Agent Number</th>
                        <th>Carrier</th>
                        <th>Pay to Agency</th>
                        <th>Agency Code</th>
                        <th>Contract Rate</th>
                        <th width="150">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($agent->agent_numbers as $agent_number)
                        <tr>
                            <td>{{ $agent_number->number }}</td>
                            <td>{{ $agent_number->carrier?->name }}</td>
                            <td>{{ $agent_number->agency?->name }}</td>
                            <td>{{ $agent_number->agency_code?->name }}</td>
                            <td>{{ $agent_number->contract_rate }}</td>
                            <td>
                                <a href="{{ route('agent_numbers.updateModal', ['id' => $agent_number->id]) }}"
                                    class="btn btn-outline-primary edit-agent-number">Edit</a>
                                <a href="{{ route('agent_numbers.delete', ['id' => $agent_number->id]) }}"
                                    class="btn btn-outline-danger ask" data-message="Delete this agent number">Delete</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    
    @if ($errors->addNewAgentNumberForm->any())
        @include('agent_numbers.createModal', [
            'agentID' => $agent->id,
            'carriers' => $carriers,
            'agency_codes' => $agency_codes,
            'agent_titles' => $agent_titles,
            'agent_statuses' => $agent_statuses,
            'agencies' => $agencies,
            'admin_fees' => $admin_fees,
            'agents' => $agents,
        ])
    @endif
    
    @if ($errors->editAgentNumberForm->any())
        
        @include('agent_numbers.updateModal', [
            'agent_number' => $selectedAgentNumber,
            'carriers' => $carriers,
            'agency_codes' => $agency_codes,
            'agent_titles' => $agent_titles,
            'agent_statuses' => $agent_statuses,
            'agencies' => $agencies,
            'admin_fees' => $admin_fees,
            'agents' => $agents,
        ])
    @endif

@stop

@section('js')
    <script>
        $(document).ready(function() {
            $('#agent-numbers-table').DataTable({
                scrollX: true,
                paging: false
            });
        });
    </script>
    <script src="/js/agent-numbers/agent-numbers-modals.js"></script>

    @if ($errors->addNewAgentNumberForm->any())
        <script>
            $("#addNewAgentNumberModal").modal("show");
        </script>
    @endif

    @if ($errors->editAgentNumberForm->any())
        <script>
            $("#editAgentNumberModal").modal("show");
        </script>
     @endif
@stop
