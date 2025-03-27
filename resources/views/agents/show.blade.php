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
        <form>
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
                        <div class="row another-fields d-none">
                            {{-- TODO --}}
                        </div>
                        <div class="text-center">
                            <a href="#" class="show-more">Show more fields</a>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <input type="submit" class="btn btn-outline-primary mr-3" value="Search" /><button type="reset"
                            class="btn btn-secondary"><i class="fas fa-redo"></i></button>
                    </div>
                </div>
            </div>
        </form>
    </div>


    <div class="card">
        <div class="card-body">
            <table class="table table-striped datatable min-w-100">
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
                        <th>Override Agent</th>
                        <th>Mentor Agent</th>
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
                                <td>{{ $agent_number->agent_title->name }}</td>
                                <td>{{ $agent_number->agent_status->name }}</td>
                                <td>{{ $agent->contract_type->name }}</td>
                                <td>{{ $agent_number->agency_code->name }}</td>
                                <td><a href="#">{{ $agent_number->id }}</a></td>
                                <td>{{ $agent_number->carrier->name }}</td>
                                <td>{{ $agent_number->override_agent?->agent->first_name }}
                                    {{ $agent_number->override_agent?->agent->last_name }}</td>
                                <td>{{ $agent_number->mentor_agent?->agent->first_name }}
                                    {{ $agent_number->mentor_agent?->agent->last_name }}</td>
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
    <script>
        $(document).ready(function() {

            $('.datatable').DataTable({
                layout: {
                    topStart: {
                        buttons: [
                            'copy', 'excel', 'pdf'
                        ]
                    }
                },
                scrollX: true
            });
        });
    </script>
@stop
