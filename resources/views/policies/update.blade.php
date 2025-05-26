@extends('adminlte::page')
@section('title', 'Policy Details')
@section('plugins.Dropzone', true)
@section('plugins.Datatables', true)

@section('adminlte_css_pre')
    <link rel="stylesheet" href="{{ asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css') }}">
@stop

@section('content_header')
    <h1>Policy Details</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('policies.show') }}">Policies</a></li>
            <li class="breadcrumb-item active" aria-current="page">Policy Details</li>
        </ol>
    </nav>
@stop

@section('content')
    <form action="{{ route('policies.update', ['id' => $policy->id]) }}" method="post">
        @csrf
        <div class="card card-light">
            <div class="card-header">
                Subscriber
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="subscriber">Subscriber:</label>
                            <input type="text" readonly class="form-control @error('subscriber') is-invalid @enderror"
                                id="subscriber" name="subscriber"
                                value="{{ $policy->customer->first_name }} {{ $policy->customer->last_name }}">
                            @error('subscriber')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="cuid">CUID:</label>
                            <input type="text" readonly class="form-control @error('cuid') is-invalid @enderror"
                                id="cuid" name="cuid" value="{{ $policy->customer->cuid?->name }}">
                            @error('cuid')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card card-light">
            <div class="card-header">
                Agent
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="agent_number">Writing Agent:</label>
                            <input type="text" readonly class="form-control @error('agent_number') is-invalid @enderror"
                                id="agent_number" name="agent_number"
                                value="{{ $policy->agent_number->number }} - {{ $policy->agent_number->agent?->first_name }} {{ $policy->agent_number->agent?->last_name }}">
                            @error('agent_number')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="agent_number_1">Carrier Agent 1:</label>
                            <select id="agent_number_1" name="agent_number_1"
                                class="form-control @error('agent_number_1') is-invalid @enderror">
                                <option value=""></option>
                                @foreach ($agentNumbers as $agent_number)
                                    <option value="{{ $agent_number->id }}"
                                        @if (old('agent_number_1',$policy->fk_agent_number_1) == $agent_number->id) selected @endif>{{ $agent_number->number }} -
                                        {{ $agent_number->agent->first_name }} {{ $agent_number->agent->last_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('agent_number_1')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="agent_number_2">Carrier Agent 2:</label>
                            <select id="agent_number_2" name="agent_number_2"
                                class="form-control @error('agent_number_2') is-invalid @enderror">
                                <option value=""></option>
                                @foreach ($agentNumbers as $agent_number)
                                    <option value="{{ $agent_number->id }}"
                                        @if (old('agent_number_2',$policy->fk_agent_number_2) == $agent_number->id) selected @endif>{{ $agent_number->number }} -
                                        {{ $agent_number->agent->first_name }} {{ $agent_number->agent->last_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('agent_number_2')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card card-light">
            <div class="card-header">
                Plan
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="product">Plan Description:</label>
                            <select id="product" name="product"
                                class="form-control @error('product') is-invalid @enderror">
                                <option value=""></option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}" @if (old('product', $policy->fk_product) == $product->id) selected @endif>
                                        {{ $product->description }}</option>
                                @endforeach
                            </select>
                            @error('product')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            <input type="hidden" value="{{ route('products.loadInfo') }}" id="url_product_desc" />
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="carrier">Carrier:</label>
                            <input type="text" class="form-control @error('carrier') is-invalid @enderror" id="carrier"
                                name="carrier" value="{{ old('carrier', $policy->product->carrier?->name) }}" readonly>
                            @error('carrier')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="plan_type">Plan Type:</label>
                            <input type="text" class="form-control @error('plan_type') is-invalid @enderror"
                                id="plan_type" name="plan_type"
                                value="{{ old('plan_type', $policy->product->plan_type?->name) }}" readonly>
                            @error('plan_type')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="product_type">Product Type:</label>
                            <input type="text" class="form-control @error('product_type') is-invalid @enderror"
                                id="product_type" name="product_type"
                                value="{{ old('product_type', $policy->product->product_type?->name) }}" readonly>
                            @error('product_type')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="tier">Tier:</label>
                            <input type="text" class="form-control @error('tier') is-invalid @enderror" id="tier"
                                name="tier" value="{{ old('tier', $policy->product->tier?->name) }}" readonly>
                            @error('tier')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="business_segment">Business Segment:</label>
                            <input type="text" class="form-control @error('business_segment') is-invalid @enderror"
                                id="business_segment" name="business_segment"
                                value="{{ old('business_segment', $policy->product->business_segment?->name) }}" readonly>
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
                            <input type="text" class="form-control @error('business_type') is-invalid @enderror"
                                id="business_type" name="business_type"
                                value="{{ old('business_type', $policy->product->business_type?->name) }}" readonly>
                            @error('business_type')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card card-light">
            <div class="card-header">
                Policy Details
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="app_submit_date">App Submit Date:</label>
                            <input type="date" class="form-control @error('app_submit_date') is-invalid @enderror"
                                id="app_submit_date" name="app_submit_date"
                                value="{{ old('app_submit_date', $policy->app_submit_date) }}">
                            @error('app_submit_date')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="request_effective_date">Requested Effective Date:</label>
                            <input type="date"
                                class="form-control @error('request_effective_date') is-invalid @enderror"
                                id="request_effective_date" name="request_effective_date"
                                value="{{ old('request_effective_date', $policy->request_effective_date) }}">
                            @error('request_effective_date')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="original_effective_date">Original Effective Date:</label>
                            <input type="date"
                                class="form-control @error('original_effective_date') is-invalid @enderror"
                                id="original_effective_date" name="original_effective_date"
                                value="{{ old('original_effective_date', $policy->original_effective_date) }}">
                            @error('original_effective_date')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="application_id">Application Id:</label>
                            <input type="text" class="form-control @error('application_id') is-invalid @enderror"
                                id="application_id" name="application_id"
                                value="{{ old('application_id', $policy->application_id) }}">
                            @error('application_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="eligibility_id">Eligibility Id:</label>
                            <input type="text" class="form-control @error('eligibility_id') is-invalid @enderror"
                                id="eligibility_id" name="eligibility_id"
                                value="{{ old('eligibility_id', $policy->eligibility_id) }}">
                            @error('eligibility_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="proposal_id">Proposal Id:</label>
                            <input type="text" class="form-control @error('proposal_id') is-invalid @enderror"
                                id="proposal_id" name="proposal_id"
                                value="{{ old('proposal_id', $policy->proposal_id) }}">
                            @error('proposal_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="contract_id">Contract Id:</label>
                            <input type="text" class="form-control @error('contract_id') is-invalid @enderror"
                                id="contract_id" name="contract_id"
                                value="{{ old('contract_id', $policy->contract_id) }}">
                            @error('contract_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="num_adults"># of Adults:</label>
                            <input type="number" class="form-control @error('num_adults') is-invalid @enderror"
                                id="num_adults" name="num_adults" value="{{ old('num_adults', $policy->num_adults) }}">
                            @error('num_adults')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="num_dependents"># of Dependents:</label>
                            <input type="number" class="form-control @error('num_dependents') is-invalid @enderror"
                                id="num_dependents" name="num_dependents"
                                value="{{ old('num_dependents', $policy->num_dependents) }}">
                            @error('num_dependents')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="premium">Premium:</label>
                            <input type="number" step="0.01"
                                class="form-control @error('premium') is-invalid @enderror" id="premium"
                                name="premium" value="{{ old('premium', $policy->premium) }}">
                            @error('premium')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="enrollment_method">Enrollment Method:</label>
                            <select id="enrollment_method" name="enrollment_method"
                                class="form-control @error('enrollment_method') is-invalid @enderror">
                                <option value=""></option>
                                @foreach ($enrollment_methods as $enrollment_method)
                                    <option value="{{ $enrollment_method->id }}"
                                        @if (old('enrollment_method', $policy->fk_enrollment_method) == $enrollment_method->id) selected @endif>{{ $enrollment_method->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('enrollment_method')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="policy_status">Policy Status:</label>
                            <select id="policy_status" name="policy_status"
                                class="form-control @error('policy_status') is-invalid @enderror">
                                <option value=""></option>
                                @foreach ($policy_statuses as $policy_status)
                                    <option value="{{ $policy_status->id }}"
                                        @if (old('policy_status', $policy->fk_policy_status) == $policy_status->id) selected @endif>{{ $policy_status->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('policy_status')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="cancel_date">Cancel Date:</label>
                            <input type="date" class="form-control @error('cancel_date') is-invalid @enderror"
                                id="cancel_date" name="cancel_date"
                                value="{{ old('cancel_date', $policy->cancel_date) }}">
                            @error('cancel_date')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="county">Policy County:</label>
                            <select id="county" name="county"
                                class="form-control @error('county') is-invalid @enderror">
                                @foreach ($counties as $county)
                                    <option value="{{ $county->id }}"
                                        @if (old('county', $policy->fk_county) == $county->id) selected @endif>{{ $county->name }}</option>
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
                            <label for="state">Policy State:</label>
                            <input type="text" readonly class="form-control @error('state') is-invalid @enderror"
                                id="state" name="state" placeholder="State:"
                                value="{{ old('state', $policy->county?->state?->name) }}">
                            @error('state')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="region">Policy Region:</label>
                            <input type="text" readonly class="form-control @error('region') is-invalid @enderror"
                                id="region" name="region" placeholder="Region:"
                                value="{{ old('region', $policy->county?->region?->name) }}">
                            @error('region')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="benefit_effective_date">Benefit Effective Date:</label>
                            <input type="date"
                                class="form-control @error('benefit_effective_date') is-invalid @enderror"
                                id="benefit_effective_date" name="benefit_effective_date"
                                value="{{ old('benefit_effective_date', $policy->benefit_effective_date) }}">
                            @error('benefit_effective_date')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="reenrollment">Reenrollment:</label>
                            <select id="reenrollment" name="reenrollment"
                                class="form-control @error('reenrollment') is-invalid @enderror">
                                <option value="1" @if (old('reenrollment', $policy->reenrollment) == '1') selected @endif>Yes</option>
                                <option value="0" @if (old('reenrollment', $policy->reenrollment) == '0') selected @endif>No</option>
                            </select>
                            @error('reenrollment')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="entry_date">Entry Date:</label>
                            <input type="date" class="form-control @error('entry_date') is-invalid @enderror"
                                id="entry_date" name="entry_date"
                                value="{{ old('entry_date', date('Y-m-d', strtotime($policy->created_at))) }}" readonly>
                            @error('entry_date')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="entry_user">Entry User:</label>
                            <input type="text" class="form-control @error('entry_user') is-invalid @enderror"
                                id="entry_user" name="entry_user"
                                value="{{ old('entry_user', $policy->entry_user->name) }}" readonly>
                            @error('entry_user')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-12 col-12">
                        <div class="form-group">
                            <label for="review_note">Review Note:</label>
                            <textarea rows="5" class="form-control @error('review_note') is-invalid @enderror" id="review_note"
                                name="review_note">{{ old('review_note', $policy->review_note) }}</textarea>
                            @error('review_note')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-12 col-12">
                        <div class="form-group">
                            <div class="icheck-secondary" title="Is Non Commissionable">
                                <input type="checkbox" name="non_commissionable" id="non_commissionable"
                                    {{ old('non_commissionable', $policy->non_commissionable) ? 'checked' : '' }}>
                                <label for="non_commissionable">Is Non Commissionable</label>
                            </div>
                            @error('non_commissionable')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="validation_date">Validation Date:</label>
                            <input type="text" class="form-control @error('validation_date') is-invalid @enderror"
                                id="validation_date" name="validation_date"
                                value="{{ old('validation_date', $policy->validation_date) }}" readonly>
                            @error('validation_date')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="validation_filename">Validation Filename:</label>
                            <input type="text" class="form-control @error('validation_filename') is-invalid @enderror"
                                id="validation_filename" name="validation_filename"
                                value="{{ old('validation_filename', $policy->validation_filename) }}" readonly>
                            @error('validation_filename')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="entry_method">Entry Method:</label>
                            <input type="text" class="form-control @error('entry_method') is-invalid @enderror"
                                id="entry_method" name="entry_method"
                                value="{{ old('entry_method', $policy->txt_entry_method) }}" readonly>
                            @error('entry_method')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="auto_entry_comp_type">Auto Entry Comp Type:</label>
                            <input type="text"
                                class="form-control @error('auto_entry_comp_type') is-invalid @enderror"
                                id="auto_entry_comp_type" name="auto_entry_comp_type"
                                value="{{ old('auto_entry_comp_type', $policy->auto_entry_comp_type) }}" readonly>
                            @error('auto_entry_comp_type')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="auto_entry_date">Auto Entry Date:</label>
                            <input type="text" class="form-control @error('auto_entry_date') is-invalid @enderror"
                                id="auto_entry_date" name="auto_entry_date"
                                value="{{ old('auto_entry_date', $policy->auto_entry_date) }}" readonly>
                            @error('auto_entry_date')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="auto_entry_filename">Auto Entry Filename:</label>
                            <input type="text" class="form-control @error('auto_entry_filename') is-invalid @enderror"
                                id="auto_entry_filename" name="auto_entry_filename"
                                value="{{ old('auto_entry_filename', $policy->auto_entry_filename) }}" readonly>
                            @error('auto_entry_filename')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-12 col-12">
                        <div class="form-group">
                            <label for="auto_entry_note">Auto Entry Note:</label>
                            <textarea rows="5" readonly class="form-control @error('auto_entry_note') is-invalid @enderror"
                                id="auto_entry_note" name="auto_entry_note">{{ old('auto_entry_note', $policy->auto_entry_note) }}</textarea>
                            @error('auto_entry_note')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card card-light">
            <div class="card-header">
                Dependents
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-12 text-right">
                        <button class="btn btn-outline-success d-inline-block add-new-dependent" type="button">Add
                            New</button>
                        <input type="hidden" value="{{ json_encode($relationships) }}" id="relationships_json" />
                    </div>
                </div>
                <div class="dependents-cont">
                    @foreach (old('dependent_first_name', $policy->dependents) as $index => $dependent_item)
                        <input type="hidden" name="dependent_ids[]" value="{{(isset($policy->dependents[$index]) ? $policy->dependents[$index]->id : '')}}" />
                        <div class="row align-items-end dependent-item">
                            <div class="col-md-6 col-6">
                                <h5 class="dependent-title">Dependent #{{ $index + 1 }}</h5>
                            </div>
                            <div class="col-md-6 col-6 text-right">
                                <button type="button" class="btn btn-outline-danger remove-dependent">Remove</button>
                            </div>
                            <div class="col-md-3 col-12">
                                <div class="form-group">
                                    <label for="dependent_first_name_{{ $index }}" class="lb-first_name">First
                                        Name:</label>
                                    <input type="text"
                                        class="form-control @error('dependent_first_name.' . $index) is-invalid @enderror"
                                        id="dependent_first_name_{{ $index }}" name="dependent_first_name[]"
                                        value="{{ old('dependent_first_name.' . $index, isset($policy->dependents[$index]) ? $policy->dependents[$index]->first_name : '') }}">
                                    @error('dependent_first_name.' . $index)
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3 col-12">
                                <div class="form-group">
                                    <label for="dependent_last_name_{{ $index }}" class="lb-last_name">Last
                                        Name:</label>
                                    <input type="text"
                                        class="form-control @error('dependent_last_name.' . $index) is-invalid @enderror"
                                        id="dependent_last_name_{{ $index }}" name="dependent_last_name[]"
                                        value="{{ old('dependent_last_name.' . $index, isset($policy->dependents[$index]) ? $policy->dependents[$index]->last_name : '') }}">
                                    @error('dependent_last_name.' . $index)
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-2 col-12">
                                <div class="form-group">
                                    <label for="dependent_date_birth_{{ $index }}" class="lb-date_birth">Date of
                                        Birth:</label>
                                    <input type="date"
                                        class="form-control @error('dependent_date_birth.' . $index) is-invalid @enderror"
                                        id="dependent_date_birth_{{ $index }}" name="dependent_date_birth[]"
                                        value="{{ old('dependent_date_birth.' . $index, isset($policy->dependents[$index]) ? $policy->dependents[$index]->date_birth : '') }}">
                                    @error('dependent_date_birth.' . $index)
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-2 col-12">
                                <div class="form-group">
                                    <label for="dependent_relationship_{{ $index }}"
                                        class="lb-relationship">Relationship To Applicant:</label>
                                    <select id="dependent_relationship_{{ $index }}"
                                        name="dependent_relationship[]"
                                        class="form-control @error('dependent_relationship.' . $index) is-invalid @enderror">
                                        <option value=""></option>
                                        @foreach ($relationships as $relationship)
                                            <option value="{{ $relationship->id }}"
                                                @if (old(
                                                        'dependent_relationship.' . $index,
                                                        isset($policy->dependents[$index]) ? $policy->dependents[$index]->fk_relationship : '') == $relationship->id) selected @endif>
                                                {{ $relationship->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('dependent_relationship.' . $index)
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-2 col-12">
                                <div class="form-group">
                                    <label for="dependent_date_add_{{ $index }}" class="lb-date_add">Date Added
                                        To Policy:</label>
                                    <input type="date"
                                        class="form-control @error('dependent_date_add.' . $index) is-invalid @enderror"
                                        id="dependent_date_add_{{ $index }}" name="dependent_date_add[]"
                                        value="{{ old('dependent_date_add.' . $index, isset($policy->dependents[$index]) ? $policy->dependents[$index]->date_added : '') }}">
                                    @error('dependent_date_add.' . $index)
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="text-right pb-5">
            <input type="submit" class="btn btn-lg btn-primary" value="Save Policy" />
        </div>
    </form>
    <div class="card card-light">
        <div class="card-header">
            File Attachments
        </div>
        <div class="card-body">
            <div class="files-container mb-3">
                @foreach ($policy->files as $file)
                    <div class="file-item">
                        <i class="fas fa-file"></i> {{ $file->name }}
                        <a href="{{ Storage::url('policies/' . $file->route) }}"
                            class="btn btn-outline-primary ml-3 mb-1" download="">Download</a>
                        <a href="{{ route('files.delete', ['id' => $file->id]) }}"
                            class="btn btn-outline-danger ml-3 mb-1 ask" data-message="Remove this file"><i
                                class="fas fa-trash"></i> </a>
                    </div>
                @endforeach
            </div>
            @include('files.partials.upload', ['policy_id' => $policy->id])
        </div>
    </div>

    <div class="card card-light ">
        <div class="card-header">
            Commission Transactions
        </div>
        <div class="card-body px-3 py-3">
            <table class="table table-striped min-w-100" id="commision-table">
                <thead>
                    <tr>
                        <th>Comm Tx Id</th>
                        <th>Hold</th>
                        <th>Hold Note</th>
                        <th>Release Date</th>
                        <th>Carrier Statem.</th>
                        <th>Check Date</th>
                        <th>Writting Agent</th>
                        <th>Writting Agent #</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- TODO --}}
                </tbody>
            </table>
        </div>
    </div>



    <div class="pb-5"></div>

    @include('policies.partials.searchSubscriberModal')
@stop

@section('js')
    <script>
        $(document).ready(function() {
            $('#commision-table').DataTable({
                scrollX: true,
                paging: false
            });

        });
    </script>
    <script src="/js/policies/search-subscriber.js"></script>
    <script src="/js/policies/dependents.js"></script>
    <script src="/js/products/infoById.js"></script>
    <script src="/js/counties/load-county-info.js"></script>
@stop
