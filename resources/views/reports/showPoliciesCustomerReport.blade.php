@extends('adminlte::page')
@section('plugins.Sweetalert2', true)

@section('title', 'Policies and Customers Report')


@section('content_header')
    <div class="row">
        <div class="col-md-12">
            <h1>Policies and Customers Report</h1>
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
    <form action="{{ route('reports.policy-customer.show') }}" method="POST">
        @csrf
        <div class="card card-light">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="agent_number">Writting Agent Number:</label>
                            <select id="agent_number" name="agent_number" class="form-control">
                                <option value=""></option>
                                @foreach ($agentNumbers as $agentNumber)
                                    <option value="{{ $agentNumber->id }}"
                                        @if (old('agent_number') == $agentNumber->id) selected @endif>{{ $agentNumber->number }} -
                                        {{ $agentNumber->agent->first_name }} {{ $agentNumber->agent->last_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="subscriber_name">Subscriber Name:</label>
                            <input type="text" class="form-control @error('subscriber_name') is-invalid @enderror"
                                id="subscriber_name" name="subscriber_name" value="{{ old('subscriber_name') }}">
                            @error('subscriber_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="subscriber_date_birth">Subscriber DOB:</label>
                            <input type="date" class="form-control @error('subscriber_date_birth') is-invalid @enderror"
                                id="subscriber_date_birth" name="subscriber_date_birth"
                                value="{{ old('subscriber_date_birth') }}">
                            @error('subscriber_date_birth')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="city">City:</label>
                            <input type="text" class="form-control @error('city') is-invalid @enderror" id="city"
                                name="city" value="{{ old('city') }}">
                            @error('city')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="county">County:</label>
                            <select id="county" name="county" class="form-control">
                                <option value=""></option>
                                @foreach ($counties as $county)
                                    <option value="{{ $county->id }}" @if (old('county') == $county->id) selected @endif>
                                        {{ $county->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="carrier">Carrier:</label>
                            <select id="carrier" name="carrier"
                                class="form-control @error('carrier') is-invalid @enderror">
                                <option value=""></option>
                                @foreach ($carriers as $carrier)
                                    <option value="{{ $carrier->id }}" @if (old('carrier') == $carrier->id) selected @endif>
                                        {{ $carrier->name }}</option>
                                @endforeach
                            </select>
                            @error('carrier')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="product">Plan Description:</label>
                            <select id="product" name="product"
                                class="form-control @error('product') is-invalid @enderror">
                                <option value=""></option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}" @if (old('product') == $product->id) selected @endif>
                                        {{ $product->description }}</option>
                                @endforeach
                            </select>
                            @error('product')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="plan_type">Plan Type:</label>
                            <select id="plan_type" name="plan_type"
                                class="form-control @error('plan_type') is-invalid @enderror">
                                <option value=""></option>
                                @foreach ($plan_types as $plan_type)
                                    <option value="{{ $plan_type->id }}"
                                        @if (old('plan_type') == $plan_type->id) selected @endif>
                                        {{ $plan_type->name }}</option>
                                @endforeach
                            </select>
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
                            <select id="product_type" name="product_type"
                                class="form-control @error('product_type') is-invalid @enderror">
                                <option value=""></option>
                                @foreach ($product_types as $product_type)
                                    <option value="{{ $product_type->id }}"
                                        @if (old('product_type') == $product_type->id) selected @endif>{{ $product_type->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('product_type')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="business_type">Business Type:</label>
                            <select id="business_type" name="business_type"
                                class="form-control @error('business_type') is-invalid @enderror">
                                <option value=""></option>
                                @foreach ($business_types as $business_type)
                                    <option value="{{ $business_type->id }}"
                                        @if (old('business_type') == $business_type->id) selected @endif>{{ $business_type->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('business_type')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="business_segment">Business Segment:</label>
                            <select id="business_segment" name="business_segment"
                                class="form-control @error('business_segment') is-invalid @enderror">
                                <option value=""></option>
                                @foreach ($business_segments as $business_segment)
                                    <option value="{{ $business_segment->id }}"
                                        @if (old('business_segment') == $business_segment->id) selected @endif>{{ $business_segment->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('business_segment')
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
                                        @if (old('policy_status') == $policy_status->id) selected @endif>{{ $policy_status->name }}
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
                            <label for="app_submit_date_start">App Submit Date Start:</label>
                            <input type="date"
                                class="form-control @error('app_submit_date_start') is-invalid @enderror"
                                id="app_submit_date_start" name="app_submit_date_start"
                                value="{{ old('app_submit_date_start') }}">
                            @error('app_submit_date_start')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="app_submit_date_end">App Submit Date End:</label>
                            <input type="date" class="form-control @error('app_submit_date_end') is-invalid @enderror"
                                id="app_submit_date_end" name="app_submit_date_end"
                                value="{{ old('app_submit_date_end') }}">
                            @error('app_submit_date_end')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="app_id">App ID:</label>
                            <input type="text" class="form-control @error('app_id') is-invalid @enderror"
                                id="app_id" name="app_id" value="{{ old('app_id') }}">
                            @error('app_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="contract_num">Contract #:</label>
                            <input type="text" class="form-control @error('contract_num') is-invalid @enderror"
                                id="contract_num" name="contract_num" value="{{ old('contract_num') }}">
                            @error('contract_num')
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
                                        @if (old('enrollment_method') == $enrollment_method->id) selected @endif>{{ $enrollment_method->name }}
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
                            <label for="client_source">Client Source:</label>
                            <select id="client_source" name="client_source"
                                class="form-control @error('client_source') is-invalid @enderror">
                                <option value=""></option>
                                @foreach ($registration_sources as $client_source)
                                    <option value="{{ $client_source->id }}"
                                        @if (old('client_source') == $client_source->id) selected @endif>
                                        {{ $client_source->name }}</option>
                                @endforeach
                            </select>
                            @error('client_source')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="request_effective_date_start">Request Effective Date Start:</label>
                            <input type="date"
                                class="form-control @error('request_effective_date_start') is-invalid @enderror"
                                id="request_effective_date_start" name="request_effective_date_start"
                                value="{{ old('request_effective_date_start') }}">
                            @error('request_effective_date_start')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="request_effective_date_end">Request Effective Date End:</label>
                            <input type="date"
                                class="form-control @error('request_effective_date_end') is-invalid @enderror"
                                id="request_effective_date_end" name="request_effective_date_end"
                                value="{{ old('request_effective_date_end') }}">
                            @error('request_effective_date_end')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="original_effective_date_start">Original Effective Date Start:</label>
                            <input type="date"
                                class="form-control @error('original_effective_date_start') is-invalid @enderror"
                                id="original_effective_date_start" name="original_effective_date_start"
                                value="{{ old('original_effective_date_start') }}">
                            @error('original_effective_date_start')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="original_effective_date_end">Original Effective Date End:</label>
                            <input type="date"
                                class="form-control @error('original_effective_date_end') is-invalid @enderror"
                                id="original_effective_date_end" name="original_effective_date_end"
                                value="{{ old('original_effective_date_end') }}">
                            @error('original_effective_date_end')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="benefit_effective_date_start">Benefit Effective Date Start:</label>
                            <input type="date"
                                class="form-control @error('benefit_effective_date_start') is-invalid @enderror"
                                id="benefit_effective_date_start" name="benefit_effective_date_start"
                                value="{{ old('benefit_effective_date_start') }}">
                            @error('benefit_effective_date_start')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="benefit_effective_date_end">Benefit Effective Date End:</label>
                            <input type="date"
                                class="form-control @error('benefit_effective_date_end') is-invalid @enderror"
                                id="benefit_effective_date_end" name="benefit_effective_date_end"
                                value="{{ old('benefit_effective_date_end') }}">
                            @error('benefit_effective_date_end')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="user">Entry User:</label>
                            <select id="user" name="user"
                                class="form-control @error('user') is-invalid @enderror">
                                <option value=""></option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}"
                                        @if (old('user') == $user->id) selected @endif>
                                        {{ $user->name }}</option>
                                @endforeach
                            </select>
                            @error('user')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="entry_date_start">Entry Date Start:</label>
                            <input type="date"
                                class="form-control @error('entry_date_start') is-invalid @enderror"
                                id="entry_date_start" name="entry_date_start"
                                value="{{ old('entry_date_start') }}">
                            @error('entry_date_start')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="entry_date_end">Entry Date End:</label>
                            <input type="date"
                                class="form-control @error('entry_date_end') is-invalid @enderror"
                                id="entry_date_end" name="entry_date_end"
                                value="{{ old('entry_date_end') }}">
                            @error('entry_date_end')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer text-right">
                <input type="submit" class="btn btn-primary" value="Export to Excel" />
            </div>
    </form>
@stop

@section('js')

@stop
