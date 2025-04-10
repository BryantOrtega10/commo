@extends('adminlte::page')
@section('plugins.Datatables', true)
@section('title', 'New Lead')

@section('content_header')
    <h1>New Lead</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('leads.show') }}">Leads</a></li>
            <li class="breadcrumb-item active" aria-current="page">New Lead</li>
        </ol>
    </nav>
@stop

@section('content')
    <div class="card">
        <form action="{{ route('leads.create') }}" method="post">
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
                            <label for="email">Email (*):</label>
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
                            <label for="phone">Phone (*):</label>
                            <input type="tel" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" placeholder="Phone:" value="{{ old('phone') }}">
                            @error('phone')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 col-12 for-individual">
                        <div class="form-group">
                            <label for="date_birth">Date of Birth:</label>
                            <input type="date" class="form-control @error('date_birth') is-invalid @enderror" id="date_birth" name="date_birth" placeholder="Date of Birth:" value="{{ old('date_birth') }}">
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
                            <input type="text" class="form-control @error('ssn') is-invalid @enderror" id="ssn" name="ssn" placeholder="SSN:" value="{{ old('ssn') }}">
                            @error('ssn')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="status">Status:</label>
                            <select id="status" name="status" class="form-control @error('customer_status') is-invalid @enderror">
                                @foreach ($customer_statuses as $customer_status)
                                    <option value="{{$customer_status->id}}" @if (old('status') == $customer_status->id) selected @endif>{{$customer_status->name}}</option>
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
                            <select id="phase" name="phase" class="form-control @error('phase') is-invalid @enderror">
                                @foreach ($phases as $phase)
                                    <option value="{{$phase->id}}" @if (old('phase') == $phase->id) selected @endif>{{$phase->name}}</option>
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
                            <select id="legal_basis" name="legal_basis" class="form-control @error('legal_basis') is-invalid @enderror">
                                @foreach ($legal_basis_m as $legal_basis)
                                    <option value="{{$legal_basis->id}}" @if (old('legal_basis') == $legal_basis->id) selected @endif>{{$legal_basis->name}}</option>
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
                            <label for="registration_source">Registration source:</label>
                            <select id="registration_source" name="registration_source" class="form-control @error('registration_source') is-invalid @enderror">
                                @foreach ($registration_sources as $registration_source)
                                    <option value="{{$registration_source->id}}" @if (old('registration_source') == $registration_source->id) selected @endif>{{$registration_source->name}}</option>
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
                            <label for="referring_customer">Referring Customer:</label>
                            <input type="text" readonly data-id="referring_customer_id" class="search-customer-input search-icon form-control @error('referring_customer') is-invalid @enderror" id="referring_customer" name="referring_customer" placeholder="Referring Customer:" value="{{ old('referring_customer') }}">
                            @error('referring_customer')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            <input type="hidden" readonly id="referring_customer_id" name="referring_customer_id" value="{{ old('referring_customer_id') }}">
                        </div>
                    </div>
                </div>      
            </div>
            <div class="text-right card-footer">
                <input type="submit" class="btn btn-lg btn-primary" value="Save Customer" />
            </div>
        </form>
    </div>
    @include('customers.partials.searchModal')
@stop

@section('js')
<script src="/js/customers/search-customer.js"></script>
@stop