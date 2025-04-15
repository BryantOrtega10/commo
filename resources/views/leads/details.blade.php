@extends('adminlte::page')
@section('plugins.Datatables', true)
@section('plugins.Quill', true)

@section('title', 'Lead Detail')

@section('adminlte_css_pre')
    <link rel="stylesheet" href="{{ asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css') }}">
@stop

@section('content_header')
    <div class="row">
        <div class="col-md-8 col-12">
            <h1>Lead Detail</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('leads.show') }}">Leads</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $lead->first_name }} {{ $lead->last_name }}
                    </li>
                </ol>
            </nav>
        </div>
        <div class="col-md-4 col-12">
            <div class="row">
                <div class="col-6">
                    <i class="far fa-clock"></i><b class="ml-1 text-primary">Last Update:</b>
                </div>
                <div class="col-6">
                    <b class="text-secondary">{{ date('m/d/Y H:i', strtotime($lead->updated_at)) }}</b>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <i class="far fa-question-circle"></i><b class="ml-1 text-primary">Phase:</b>
                </div>
                <div class="col-6">
                    <b class="text-secondary">{{ $lead->phase?->name }}</b>
                </div>
            </div>
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
    <div class="row align-items-stretch">
        <div class="col-md-4 col-lg-3">
            <form action="{{ route('leads.details', ['id' => $lead->id]) }}" method="POST">
                @csrf
                <div class="card h-100-int">
                    <div class="card-body border-top border-primary rounded border-3 ">
                        <div class="text-right"><a href="{{route('leads.update',['id' => $lead->id])}}" class="btn btn-outline-primary"><i class="fas fa-user-edit"></i></a></div>
                        <div class="text-center">
                            <div class="circle-initials">
                                {{ strtoupper(substr($lead->first_name, 0, 1)) }}{{ strtoupper(substr($lead->last_name, 0, 1)) }}
                            </div>
                            <span class="field">{{ $lead->first_name }} {{ $lead->last_name }}</span>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <span class="text-primary">Email</span><br>
                                <span class="field">{{ $lead->email }}</span>
                            </div>
                            <div class="col-12">
                                <span class="text-primary">Phone</span><br>
                                <span class="field">{{ $lead->phone }}</span>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="status">Status:</label>
                                    <select id="status" name="status"
                                        class="form-control @error('customer_status') is-invalid @enderror">
                                        @foreach ($customer_statuses as $customer_status)
                                            <option value="{{ $customer_status->id }}"
                                                @if (old('status', $lead->fk_status) == $customer_status->id) selected @endif>
                                                {{ $customer_status->name }}
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
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="phase">Phase:</label>
                                    <select id="phase" name="phase"
                                        class="form-control @error('phase') is-invalid @enderror">
                                        @foreach ($phases as $phase)
                                            <option value="{{ $phase->id }}"
                                                @if (old('phase', $lead->fk_phase) == $phase->id) selected @endif>{{ $phase->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('phase')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12">
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
                            <div class="col-12">
                                <span class="text-primary">Registration source</span><br>
                                <span class="field">{{ $lead->registration_s?->name }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="text-right card-footer">
                        <input type="submit" class="btn btn-primary" value="Save Changes" />
                    </div>
                </div>
            </form>
        </div>
        <div class="col-md-8 col-lg-9">
            <div class="row align-items-stretch h-100">
                <div class="col col-special-lead-main">
                    <div class="card card-light bt-int h-50-int">
                        <div class="card-header">Tasks</div>
                        <div class="card-body overflow-y-s">
                            @foreach ($tasks as $task)
                                <div class="card">
                                    <div class="card-body rounded border-left border-3 @switch($task->priority)
                                        @case(1)
                                            border-success
                                            @break
                                        @case(2)
                                            border-warning
                                            @break
                                        @case(3)
                                            border-danger
                                            @break
                                        @default
                                            
                                    @endswitch">
                                        <div class="row">
                                            <div class="col-6">
                                                {{$task->task_name}}
                                            </div>
                                            <div class="col-4">
                                                <span class="text-primary">Expiration:</span> {{ date('m/d/Y H:i', strtotime($task->expiration_date)) }}<br>
                                                <span class="text-primary">Remaining:</span> {{$task->txt_remaining }}
                                            </div>
                                            <div class="col-2">
                                                <a href="{{route('leads.activityDetailsModal',['id' => $task->id])}}" class="btn btn-outline-primary activity-details">Details</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="card card-light bt-int h-50-int">
                        <div class="card-header">Latest activities</div>
                        <div class="card-body overflow-y-s">
                            @foreach ($activityLogs as $activityLog)
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-9">
                                                <b class="text-primary">Description:</b><br>
                                                {{$activityLog->description}}
                                                <br>
                                                @if (strlen(strip_tags($activityLog->activity->description)) > 50)
                                                    {{ substr(strip_tags($activityLog->activity->description), 0, 50) }}...
                                                @else
                                                    {{ strip_tags($activityLog->activity->description) }}
                                                @endif
                                            </div>
                                            <div class="col-3 text-right">
                                                {{ date('m/d/Y H:i', strtotime($activityLog->created_at)) }}<br>
                                                <a href="{{route('leads.activityDetailsModal',['id' => $activityLog->fk_activity])}}" class="btn btn-outline-primary activity-details">Details</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                        </div>
                    </div>
                </div>
                <div class="col col-special-lead-buttons pb-3 text-center">
                    <div class="card h-100 d-inline-block">
                        <div class="card-body p-0 text-center" id="action-buttons-container">
                            <a class="btn btn-special-primary activityModal"
                                href="{{ route('leads.activityModal', ['idLead' => $lead->id, 'type' => 1]) }}"
                                data-toggle="tooltip" data-placement="left" title="Notes">
                                <i class="far fa-address-book"></i>
                            </a>
                            <a class="btn btn-special-primary activityModal"
                                href="{{ route('leads.activityModal', ['idLead' => $lead->id, 'type' => 2]) }}"
                                data-toggle="tooltip" data-placement="left" title="Email">
                                <i class="far fa-envelope"></i>
                            </a>
                            <a class="btn btn-special-primary activityModal"
                                href="{{ route('leads.activityModal', ['idLead' => $lead->id, 'type' => 3]) }}"
                                data-toggle="tooltip" data-placement="left" title="Call">
                                <i class="fas fa-phone-volume"></i>
                            </a>
                            <a class="btn btn-special-primary activityModal"
                                href="{{ route('leads.activityModal', ['idLead' => $lead->id, 'type' => 4]) }}"
                                data-toggle="tooltip" data-placement="left" title="Meet">
                                <i class="fas fa-users"></i>
                            </a>
                            <a class="btn btn-special-primary activityModal"
                                href="{{ route('leads.activityModal', ['idLead' => $lead->id, 'type' => 5]) }}"
                                data-toggle="tooltip" data-placement="left" title="Tasks">
                                <i class="fas fa-tasks"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    @if ($errors->addActivityForm->any())
        @include('activities.partials.activityModal', [
            'type' => $typeActivity,
            'idLead' => $lead->id,
        ])
    @endif

    @if ($errors->detailsActivityForm->any())
        @include('activities.partials.activityDetailsModal', [
           'activity' => $activitySelected
        ])
    @endif


@stop

@section('js')
    <script src="/js/leads/modals.js"></script>
    <script>
        $(document).ready(() => {
            $('[data-toggle="tooltip"]').tooltip({
                container: '#action-buttons-container'
            });
        })
    </script>


    @if ($errors->addActivityForm->any() || $errors->detailsActivityForm->any())
        <script>
            const quill = new Quill('#description', {
                theme: 'snow'
            });

            quill.clipboard.dangerouslyPasteHTML($("#html_desc").val());

            $("#activityModal").modal("show");
        </script>
    @endif
@stop
