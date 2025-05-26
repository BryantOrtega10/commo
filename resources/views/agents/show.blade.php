@extends('adminlte::page')
@section('plugins.Datatables', true)
@section('plugins.Sweetalert2', true)

@section('title', 'Agents')

@section('content_header')
    <div class="row">
        <div class="col-md-9">
            <h1>Agents</h1>
        </div>
        <div class="text-right col-md-3">
            <a href="{{ route('agents.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i>
                Enter a New Agent</a>
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
        <form action="{{route('agents.show')}}" method="POST">
            @csrf
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-10">
                        <div class="row align-items-end">
                            <div class="col-md-2 col-12">
                                <div class="form-group">
                                    <label for="first_name">First Name:</label>
                                    <input type="text" class="form-control" id="first_name" name="first_name"
                                        placeholder="First Name:" value="{{ old('first_name') }}">
                                </div>
                            </div>
                            <div class="col-md-2 col-12">
                                <div class="form-group">
                                    <label for="last_name">Last Name:</label>
                                    <input type="text" class="form-control" id="last_name" name="last_name"
                                        placeholder="Last Name:" value="{{ old('last_name') }}">
                                </div>
                            </div>

                            <div class="col-md-2 col-12">
                                <div class="form-group">
                                    <label for="agent_number">Agent Number:</label>
                                    <input type="text" class="form-control" id="agent_number" name="agent_number"
                                        placeholder="Agent Number:" value="{{ old('agent_number') }}">
                                </div>
                            </div>

                            <div class="col-md-2 col-12">
                                <div class="form-group">
                                    <label for="agency_code">Agency Code:</label>
                                    <select id="agency_code" name="agency_code"
                                        class="form-control @error('agency_code') is-invalid @enderror">
                                        <option value=""></option>
                                        @foreach ($agency_codes as $agency_code)
                                            <option value="{{ $agency_code->id }}"
                                                @if (old('agency_code') == $agency_code->id) selected @endif>
                                                {{ $agency_code->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-2 col-12">
                                <div class="form-group">
                                    <label for="email">Email:</label>
                                    <input type="email" class="form-control" id="email" name="email"
                                        placeholder="Email:" value="{{ old('email') }}">
                                </div>
                            </div>

                            <div class="col-md-2 col-12">
                                <div class="form-group">
                                    <label for="phone">Phone:</label>
                                    <input type="text" class="form-control" id="phone" name="phone"
                                        placeholder="Phone:" value="{{ old('phone') }}">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row another-fields 
                            @if(old('contract_type') === null &&
                                old('agent_title') === null &&
                                old('agent_status') === null &&
                                old('carrier') === null &&
                                old('contract_date_from') === null &&
                                old('contract_date_to') === null &&
                                old('mentor_agent') === null &&
                                old('override_agent') === null)
                                d-none
                            @endif">
                            <div class="col-md-2 col-12">
                                <div class="form-group">
                                    <label for="contract_type">Contract Type:</label>
                                    <select id="contract_type" name="contract_type"
                                        class="form-control">
                                        <option value=""></option>
                                        @foreach ($contract_types as $contract_type)
                                            <option value="{{ $contract_type->id }}"
                                                @if (old('contract_type') == $contract_type->id) selected @endif>{{ $contract_type->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    
                                </div>
                            </div>
                            <div class="col-md-2 col-12">
                                <div class="form-group">
                                    <label for="agent_title">Agent Title:</label>
                                    <select id="agent_title" name="agent_title"
                                        class="form-control">
                                        <option value=""></option>
                                        @foreach ($agent_titles as $agent_title)
                                            <option value="{{ $agent_title->id }}"
                                                @if (old('agent_title') == $agent_title->id) selected @endif>{{ $agent_title->name }}
                                            </option>
                                        @endforeach
                                    </select>
                             
                                </div>
                            </div>
                            <div class="col-md-2 col-12">
                                <div class="form-group">
                                    <label for="agent_status">Agent Status:</label>
                                    <select id="agent_status" name="agent_status"
                                        class="form-control">
                                        <option value=""></option>
                                        @foreach ($agent_statuses as $agent_status)
                                            <option value="{{ $agent_status->id }}"
                                                @if (old('agent_status') == $agent_status->id) selected @endif>{{ $agent_status->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    
                                </div>
                            </div>
                            <div class="col-md-2 col-12">
                                <div class="form-group">
                                    <label for="carrier">Carrier (*):</label>
                                    <select id="carrier" name="carrier"
                                        class="form-control">
                                        <option value=""></option>
                                        @foreach ($carriers as $carrier)
                                            <option value="{{ $carrier->id }}"
                                                @if (old('carrier') == $carrier->id) selected @endif>{{ $carrier->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2 col-12">
                                <div class="form-group">
                                    <label for="contract_date_from">Contract Date From:</label>
                                    <input type="date" class="form-control"
                                        id="contract_date_from" name="contract_date_from" placeholder="Contract Date From:"
                                        value="{{ old('contract_date_from') }}">
                               
                                </div>
                            </div>
                            <div class="col-md-2 col-12">
                                <div class="form-group">
                                    <label for="contract_date_to">Contract Date To:</label>
                                    <input type="date" class="form-control"
                                        id="contract_date_to" name="contract_date_to" placeholder="Contract Date To:"
                                        value="{{ old('contract_date_to') }}">
                               
                                </div>
                            </div>
                            <div class="col-md-2 col-12">
                                <div class="form-group">
                                    <label for="mentor_agent">Mentor Agent:</label>
                                    <select id="mentor_agent" name="mentor_agent"
                                        class="form-control">
                                        <option value=""></option>
                                        @foreach ($defaultAgents as $agent)
                                            <option value="{{ $agent->id }}"
                                                @if (old('mentor_agent') == $agent->id) selected @endif>
                                                    {{ $agent->id }} - {{$agent->first_name}} {{$agent->last_name}}
                                            </option>
                                        @endforeach
                                    </select>                 
                                </div>
                            </div>
                            <div class="col-md-2 col-12">
                                <div class="form-group">
                                    <label for="override_agent">Override Agent:</label>
                                    <select id="override_agent" name="override_agent"
                                        class="form-control">
                                        <option value=""></option>
                                        @foreach ($defaultAgents as $agent)
                                            <option value="{{ $agent->id }}"
                                                @if (old('override_agent') == $agent->id) selected @endif>
                                                    {{ $agent->id }} - {{$agent->first_name}} {{$agent->last_name}}
                                            </option>
                                        @endforeach
                                    </select>                 
                                </div>
                            </div>
                        </div>
                        <div class="text-center">
                            <a href="#" class="show-more">
                                @if(old('contract_type') === null &&
                                    old('agent_title') === null &&
                                    old('agent_status') === null &&
                                    old('carrier') === null &&
                                    old('contract_date_from') === null &&
                                    old('contract_date_to') === null &&
                                    old('mentor_agent') === null &&
                                    old('override_agent') === null)
                                    Show more fields
                                @else
                                    Show less fields
                                @endif                                
                            </a>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <input type="submit" class="btn btn-outline-primary mr-3" value="Search" /><a href="{{route('agents.show')}}"
                            class="btn btn-secondary"><i class="fas fa-redo"></i></a>
                    </div>
                </div>
            </div>
        </form>
    </div>


    <div class="card">
        <div class="card-body">
            <table class="table table-striped datatable min-w-100" data-url="{{route('agents.datatable')}}">
                <thead>
                    <tr>
                        <th>Agent #Id</th>
                        <th>Agent Name</th>
                        <th>Title</th>
                        <th>Status</th>
                        <th>Contract</th>
                        <th>Agency Code</th>
                        <th>Agent #</th>
                        <th>Carrier</th>
                        <th>Override Agents</th>
                        <th>Mentor Agents</th>
                        <th>Contract Date</th>
                        <th>Email</th>
                        <th>Phone</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($agents as $agent)
                        @forelse ($agent->agent_numbers as $agent_number)
                            <tr>
                                <td>{{ $agent->id }}</td>
                                <td><a href="{{ route('agents.update', ['id' => $agent->id]) }}"
                                        class="text-nowrap">{{ $agent->first_name }} {{ $agent->last_name }}</a></td>
                                <td>{{ $agent_number->agent_title?->name }}</td>
                                <td>{{ $agent_number->agent_status?->name }}</td>
                                <td>{{ $agent->contract_type?->name }}</td>
                                <td>{{ $agent_number->agency_code?->name }}</td>
                                <td><a href="{{route('agent_numbers.update',['id' => $agent_number->id])}}" class="text-nowrap">{{ $agent_number->number }}</a></td>
                                <td>{{ $agent_number->carrier?->name }}</td>
                                <td>
                                    @foreach ($agent_number->override_agents as $override_agent)
                                        <div class="agents">{{ $override_agent->agent_number_rel->agent->first_name }} {{ $override_agent->agent_number_rel->agent->last_name }}</div>
                                    @endforeach
                                </td>
                                <td>
                                    @foreach ($agent_number->mentor_agents as $mentor_agent)
                                        <div class="agents">{{ $mentor_agent->agent_number_rel->agent->first_name }} {{ $mentor_agent->agent_number_rel->agent->last_name }}</div>
                                    @endforeach
                                </td>
                                <td> @isset($agent->contract_date)
                                        {{ date('m/d/Y', strtotime($agent->contract_date)) }}
                                    @endisset
                                </td>
                                <td>{{ $agent->email }}</td>
                                <td>{{ $agent->phone }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td>{{ $agent->id }}</td>
                                <td><a href="{{ route('agents.update', ['id' => $agent->id]) }}"
                                        class="text-nowrap">{{ $agent->first_name }} {{ $agent->last_name }}</a></td>
                                <td></td>
                                <td></td>
                                <td>{{ $agent->contract_type->name }}</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>
                                    @isset($agent->contract_date)
                                        {{ date('m/d/Y', strtotime($agent->contract_date)) }}
                                    @endisset
                                </td>
                                <td>{{ $agent->email }}</td>
                                <td>{{ $agent->phone }}</td>
                            </tr>
                        @endforelse
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
    <script src="/js/agents/datatable.js"></script>
    <script>
        $(document).ready(function() {

            // $('.datatable').DataTable({
            //     layout: {
            //         topStart: {
            //             buttons: [
            //                 'copy', 'excel', 'pdf'
            //             ]
            //         }
            //     },
            //     scrollX: true
            // });
        });
    </script>
@stop
