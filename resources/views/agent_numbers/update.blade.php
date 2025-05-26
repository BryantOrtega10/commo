@extends('adminlte::page')
{{-- @section('plugins.Datatables', true) --}}
@section('plugins.Dropzone', true)
@section('plugins.Sweetalert2', true)
@section('title', 'Agent Number')

@section('adminlte_css_pre')
    <link rel="stylesheet" href="{{ asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css') }}">
@stop

@section('content_header')
    <h1>Agent Number</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('agents.show') }}">Agents</a></li>
            <li class="breadcrumb-item"><a
                    href="{{ route('agents.update', ['id' => $agent_number->fk_agent]) }}">{{ $agent_number->agent->first_name }}
                    {{ $agent_number->agent->last_name }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">Agent Number</li>
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
        <form action="{{ route('agent_numbers.update', ['id' => $agent_number->id]) }}" method="post">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="number">Agent Number (*):</label>
                            <input type="text" class="form-control @if ($errors->editAgentNumberForm->has('number')) is-invalid @endif"
                                id="number" name="number" placeholder="Agent Number:"
                                value="{{ old('number', $agent_number->number) }}">
                            @if ($errors->editAgentNumberForm->has('number'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->editAgentNumberForm->first('number') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="agency_code">Agency Code:</label>
                            <select id="agency_code" name="agency_code"
                                class="form-control @if ($errors->editAgentNumberForm->has('agency_code')) is-invalid @endif">
                                <option value=""></option>
                                @foreach ($agency_codes as $agency_code)
                                    <option value="{{ $agency_code->id }}"
                                        @if (old('agency_code', $agent_number->fk_agency_code) == $agency_code->id) selected @endif>{{ $agency_code->name }}
                                    </option>
                                @endforeach
                            </select>
                            @if ($errors->editAgentNumberForm->has('agency_code'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->editAgentNumberForm->first('agency_code') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="carrier">Carrier (*):</label>
                            <select id="carrier" name="carrier"
                                class="form-control @if ($errors->editAgentNumberForm->has('carrier')) is-invalid @endif">
                                @foreach ($carriers as $carrier)
                                    <option value="{{ $carrier->id }}" @if (old('carrier', $agent_number->fk_carrier) == $carrier->id) selected @endif>
                                        {{ $carrier->name }}
                                    </option>
                                @endforeach
                            </select>
                            @if ($errors->editAgentNumberForm->has('carrier'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->editAgentNumberForm->first('carrier') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="agent_title">Agent Title:</label>
                            <select id="agent_title" name="agent_title"
                                class="form-control @if ($errors->editAgentNumberForm->has('agent_title')) is-invalid @endif">
                                <option value=""></option>
                                @foreach ($agent_titles as $agent_title)
                                    <option value="{{ $agent_title->id }}"
                                        @if (old('agent_title', $agent_number->fk_agent_title) == $agent_title->id) selected @endif>{{ $agent_title->name }}
                                    </option>
                                @endforeach
                            </select>
                            @if ($errors->editAgentNumberForm->has('agent_title'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->editAgentNumberForm->first('agent_title') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="agent_status">Agent Status:</label>
                            <select id="agent_status" name="agent_status"
                                class="form-control @if ($errors->editAgentNumberForm->has('agent_status')) is-invalid @endif">
                                <option value=""></option>
                                @foreach ($agent_statuses as $agent_status)
                                    <option value="{{ $agent_status->id }}"
                                        @if (old('agent_status', $agent_number->fk_agent_status) == $agent_status->id) selected @endif>{{ $agent_status->name }}
                                    </option>
                                @endforeach
                            </select>
                            @if ($errors->editAgentNumberForm->has('agent_status'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->editAgentNumberForm->first('agent_status') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="agency">Pay to Agency:</label>
                            <select id="agency" name="agency"
                                class="form-control @if ($errors->editAgentNumberForm->has('agency')) is-invalid @endif">
                                <option value=""></option>
                                @foreach ($agencies as $agency)
                                    <option value="{{ $agency->id }}"
                                        @if (old('agency', $agent_number->fk_agency) == $agency->id) selected @endif>{{ $agency->name }}
                                    </option>
                                @endforeach
                            </select>
                            @if ($errors->editAgentNumberForm->has('agency'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->editAgentNumberForm->first('agency') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="contract_rate">Contract Rate:</label>
                            <input type="number" step="0.01" max="1"
                                class="form-control @if ($errors->editAgentNumberForm->has('contract_rate')) is-invalid @endif"
                                id="contract_rate" name="contract_rate" placeholder="Contract Rate:"
                                value="{{ old('contract_rate', $agent_number->contract_rate) }}">
                            @if ($errors->editAgentNumberForm->has('contract_rate'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->editAgentNumberForm->first('contract_rate') }}</strong>
                                </span>
                            @endif

                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="admin_fee">Admin Fee Schedule:</label>
                            <select id="admin_fee" name="admin_fee"
                                class="form-control @if ($errors->editAgentNumberForm->has('admin_fee')) is-invalid @endif">
                                <option value=""></option>
                                @foreach ($admin_fees as $admin_fee)
                                    <option value="{{ $admin_fee->id }}"
                                        @if (old('admin_fee', $agent_number->fk_admin_fee) == $admin_fee->id) selected @endif>{{ $admin_fee->name }}
                                    </option>
                                @endforeach
                            </select>
                            @if ($errors->editAgentNumberForm->has('admin_fee'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->editAgentNumberForm->first('admin_fee') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="row">
                    @for ($i = 1; $i <= 5; $i++)
                        <div class="col-md-3 col-12">
                            <div class="form-group">
                                <label for="mentor_agent_{{ $i }}">Mentor Agent {{ $i }}:</label>
                                <select id="mentor_agent_{{ $i }}" name="mentor_agent_{{ $i }}"
                                    class="form-control @if ($errors->editAgentNumberForm->has('mentor_agent_' . $i)) is-invalid @endif">
                                    <option value=""></option>
                                    @foreach ($agents as $agent)
                                        <option value="{{ $agent->id }}"
                                            @if (old('mentor_agent_' . $i, $mentorAgents[$i - 1]['id'] ?? null) == $agent->id) selected @endif>
                                            {{ $agent->number }} - {{ $agent->agent->first_name }} {{ $agent->agent->last_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @if ($errors->editAgentNumberForm->has('mentor_agent_' . $i))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->editAgentNumberForm->first('mentor_agent_' . $i) }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-3 col-12">
                            <div class="form-group">
                                <label for="start_date_ment_{{ $i }}">Start Date:</label>
                                <input type="date"
                                    class="form-control @if ($errors->editAgentNumberForm->has('start_date_ment_' . $i)) is-invalid @endif"
                                    id="start_date_ment_{{ $i }}" name="start_date_ment_{{ $i }}"
                                    placeholder="Start Date:"
                                    value="{{ old('start_date_ment_' . $i, $mentorAgents[$i - 1]['start_date'] ?? null) }}">
                                @if ($errors->editAgentNumberForm->has('start_date_ment_' . $i))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->editAgentNumberForm->first('start_date_ment_' . $i) }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-3 col-12">
                            <div class="form-group">
                                <label for="end_date_ment_{{ $i }}">End Date:</label>
                                <input type="date"
                                    class="form-control @if ($errors->editAgentNumberForm->has('end_date_ment_' . $i)) is-invalid @endif"
                                    id="end_date_ment_{{ $i }}" name="end_date_ment_{{ $i }}"
                                    placeholder="End Date:"
                                    value="{{ old('end_date_ment_' . $i, $mentorAgents[$i - 1]['end_date'] ?? null) }}">
                                @if ($errors->editAgentNumberForm->has('end_date_ment_' . $i))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->editAgentNumberForm->first('end_date_ment_' . $i) }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-3 col-12"></div>
                    @endfor
                </div>
                <div class="row">
                    @for ($i = 1; $i <= 5; $i++)
                        <div class="col-md-3 col-12">
                            <div class="form-group">
                                <label for="override_agent_{{ $i }}">Override Agent
                                    {{ $i }}:</label>
                                <select id="override_agent_{{ $i }}"
                                    name="override_agent_{{ $i }}"
                                    class="form-control @if ($errors->editAgentNumberForm->has('override_agent_' . $i)) is-invalid @endif">
                                    <option value=""></option>
                                    @foreach ($agents as $agent)
                                        <option value="{{ $agent->id }}"
                                            @if (old('override_agent_' . $i, $overrideAgents[$i - 1]['id'] ?? null) == $agent->id) selected @endif>
                                            {{ $agent->number }} - {{ $agent->agent->first_name }} {{ $agent->agent->last_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @if ($errors->editAgentNumberForm->has('override_agent_' . $i))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->editAgentNumberForm->first('override_agent_' . $i) }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-3 col-12">
                            <div class="form-group">
                                <label for="start_date_over_{{ $i }}">Start Date:</label>
                                <input type="date"
                                    class="form-control @if ($errors->editAgentNumberForm->has('start_date_over_' . $i)) is-invalid @endif"
                                    id="start_date_over_{{ $i }}" name="start_date_over_{{ $i }}"
                                    placeholder="Start Date:"
                                    value="{{ old('start_date_over_' . $i, $overrideAgents[$i - 1]['start_date'] ?? null) }}">
                                @if ($errors->editAgentNumberForm->has('start_date_over_' . $i))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->editAgentNumberForm->first('start_date_over_' . $i) }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-3 col-12">
                            <div class="form-group">
                                <label for="end_date_over_{{ $i }}">End Date:</label>
                                <input type="date"
                                    class="form-control @if ($errors->editAgentNumberForm->has('end_date_over_' . $i)) is-invalid @endif"
                                    id="end_date_over_{{ $i }}" name="end_date_over_{{ $i }}"
                                    placeholder="End Date:"
                                    value="{{ old('end_date_over_' . $i, $overrideAgents[$i - 1]['end_date'] ?? null) }}">
                                @if ($errors->editAgentNumberForm->has('end_date_over_' . $i))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->editAgentNumberForm->first('end_date_over_' . $i) }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-3 col-12"></div>
                    @endfor
                </div>
                <div class="row">
                    <div class="col-md-12 col-12">
                        <div class="form-group">
                            <label for="notes">Agent # Notes:</label>
                            <textarea class="form-control @if ($errors->editAgentNumberForm->has('notes')) is-invalid @endif" rows="5" id="notes"
                                name="notes" placeholder="Agent # Notes:">{{ old('notes', $agent_number->notes) }}</textarea>
                            @if ($errors->editAgentNumberForm->has('notes'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->editAgentNumberForm->first('notes') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-right card-footer">
                <input type="submit" class="btn btn-lg btn-primary" value="Save Agent" />
            </div>
        </form>
    </div>
    
    <form action="#" id="form-commission-rates" method="post">
        @csrf
        <input type="hidden" name="idAgentNumber" value="{{ $agent_number->id }}" />
        <div class="card card-light">
            <div class="card-header">
                Commission Rates
            </div>
            <div class="card-body px-3 py-3">
                <a href="{{ route('commissions.rate.add-new') }}"
                    class="btn btn-outline-success mb-3 add-commision-rate">Add New</a>
                <table class="table table-bordered min-w-100 table-responsive" id="commision-rates-table">
                    <thead>
                        <tr>
                            <th>Bus. Seg</th>
                            <th>Bus. Type</th>
                            <th>Comp Type</th>
                            <th>AMF Comp</th>
                            <th>Plan Type</th>
                            <th>Plan Desc.</th>
                            <th>Product Type</th>
                            <th>Tier</th>
                            <th>County Group</th>
                            <th>Region</th>
                            <th>Policy contract id</th>
                            <th>Tx Type</th>
                            <th>Agent Type</th>
                            <th>Submit From</th>
                            <th>Submit To</th>
                            <th>Stmt. From</th>
                            <th>Stmt. To</th>
                            <th>Orig. Eff. From</th>
                            <th>Orig. Eff. To</th>
                            <th>Benefit Eff. From</th>
                            <th>Benefit Eff. To</th>
                            <th>Flat rate</th>
                            <th>Rate Type</th>
                            <th>Rate Amount</th>
                            <th>Order</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($errors->addNewRate->any())
                            @include('agent_numbers.partials.commissionRateAddRow', [
                                'business_segments' => $business_segments,
                                'business_types' => $business_types,
                                'compensation_types' => $compensation_types,
                                'amf_compensation_types' => $amf_compensation_types,
                                'plan_types' => $plan_types,
                                'products' => $products,
                                'product_types' => $product_types,
                                'tiers' => $tiers,
                                'counties' => $counties,
                                'regions' => $regions,
                                'txTypes' => $txTypes,
                                'agentTypes' => $agentTypes,
                                'rateTypes' => $rateTypes,
                            ])
                        @endif
                        @foreach ($commissionRates as $commissionRate)

                            @if ($errors->editRate->any() && isset($selectedCommissionRate) && $selectedCommissionRate->id == $commissionRate->id)
                                @include('agent_numbers.partials.commissionRateEditRow', [
                                    'commissionRate' => $selectedCommissionRate,
                                    'business_segments' => $business_segments,
                                    'business_types' => $business_types,
                                    'compensation_types' => $compensation_types,
                                    'amf_compensation_types' => $amf_compensation_types,
                                    'plan_types' => $plan_types,
                                    'products' => $products,
                                    'product_types' => $product_types,
                                    'tiers' => $tiers,
                                    'counties' => $counties,
                                    'regions' => $regions,
                                    'txTypes' => $txTypes,
                                    'agentTypes' => $agentTypes,
                                    'rateTypes' => $rateTypes,
                                ])
                            @else
                                <tr>
                                    <td>{{ $commissionRate->business_segment?->name }}</td>
                                    <td>{{ $commissionRate->business_type?->name }}</td>
                                    <td>{{ $commissionRate->compensation_type?->name }}</td>
                                    <td>{{ $commissionRate->amf_compensation_type?->name }}</td>
                                    <td>{{ $commissionRate->plan_type?->name }}</td>
                                    <td>{{ $commissionRate->product?->name }}</td>
                                    <td>{{ $commissionRate->product_type?->name }}</td>
                                    <td>{{ $commissionRate->tier?->name }}</td>
                                    <td>{{ $commissionRate->county?->name }}</td>
                                    <td>{{ $commissionRate->region?->name }}</td>
                                    <td>{{ $commissionRate->policy_contract_id }}</td>
                                    <td>{{ $commissionRate->tx_type?->name }}</td>
                                    <td>{{ $commissionRate->txt_agent_type }}</td>
                                    <td>
                                        @isset($commissionRate->submit_from)
                                            {{ date('m/d/Y', strtotime($commissionRate->submit_from)) }}
                                        @endisset
                                    </td>
                                    <td>
                                        @isset($commissionRate->submit_to)
                                            {{ date('m/d/Y', strtotime($commissionRate->submit_to)) }}
                                        @endisset
                                    </td>
                                    <td>
                                        @isset($commissionRate->statement_from)
                                            {{ date('m/d/Y', strtotime($commissionRate->statement_from)) }}
                                        @endisset
                                    </td>
                                    <td>
                                        @isset($commissionRate->statement_to)
                                            {{ date('m/d/Y', strtotime($commissionRate->statement_to)) }}
                                        @endisset
                                    </td>
                                    <td>
                                        @isset($commissionRate->original_effective_from)
                                            {{ date('m/d/Y', strtotime($commissionRate->original_effective_from)) }}
                                        @endisset
                                    </td>
                                    <td>
                                        @isset($commissionRate->original_effective_to)
                                            {{ date('m/d/Y', strtotime($commissionRate->original_effective_to)) }}
                                        @endisset
                                    </td>
                                    <td>
                                        @isset($commissionRate->benefit_effective_from)
                                            {{ date('m/d/Y', strtotime($commissionRate->benefit_effective_from)) }}
                                        @endisset
                                    </td>
                                    <td>
                                        @isset($commissionRate->benefit_effective_to)
                                            {{ date('m/d/Y', strtotime($commissionRate->benefit_effective_to)) }}
                                        @endisset
                                    </td>
                                    <td>{{ $commissionRate->flat_rate }}</td>
                                    <td>{{ $commissionRate->txt_rate_type }}</td>
                                    <td>{{ $commissionRate->rate_amount }}</td>
                                    <td>
                                        <input type="hidden" class="order" data-id="{{ $commissionRate->id }}"
                                            value="{{ $commissionRate->order }}" />
                                        <button type="button" class="btn btn-outline-secondary commission-rate-order-up"
                                            data-id="{{ $commissionRate->id }}">
                                            <i class="fas fa-chevron-up"></i>
                                        </button>
                                        <button type="button"
                                            class="btn btn-outline-secondary commission-rate-order-down"
                                            data-id="{{ $commissionRate->id }}">
                                            <i class="fas fa-chevron-down"></i>
                                        </button>
                                    </td>
                                    <td class="d-flex" width="230">
                                        <a href="{{ route('commissions.rate.update', ['id' => $commissionRate->id]) }}"
                                            data-id="{{ $commissionRate->id }}" class="btn btn-primary mr-3 update-row">
                                            <i class="fas fa-pen"></i> Update
                                        </a>
                                        <a href="{{ route('commissions.rate.delete', ['id' => $commissionRate->id]) }}" class="btn btn-outline-danger delete-row ask" data-message="Delete this commission rate">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </form>
    <div class="card card-light">
        <div class="card-header">
            File Attachments
        </div>
        <div class="card-body">
            <div class="files-container mb-3">
                @foreach ($agent_number->files as $file)
                    <div class="file-item">
                        <i class="fas fa-file"></i> {{ $file->name }}
                        <a href="{{ Storage::url('agent_numbers/' . $file->route) }}"
                            class="btn btn-outline-primary ml-3 mb-1" download="">Download</a>
                        <a href="{{ route('files.delete', ['id' => $file->id]) }}"
                            class="btn btn-outline-danger ml-3 mb-1 ask" data-message="Remove this file"><i
                                class="fas fa-trash"></i> </a>
                    </div>
                @endforeach
            </div>
            @include('files.partials.upload', ['agent_number_id' => $agent_number->id])
        </div>
    </div>
    <div class="pb-4"></div>

@stop

@section('js')
    <script src="/js/agent-numbers/commission-rates.js"></script>

@stop
