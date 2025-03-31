@extends('adminlte::page')
@section('plugins.Datatables', true)
@section('title', 'New Customer')

@section('content_header')
    <h1>New Customer</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('customers.show') }}">Customers</a></li>
            <li class="breadcrumb-item active" aria-current="page">New Customer</li>
        </ol>
    </nav>
@stop

@section('content')
    <div class="card">
        <form action="{{ route('customers.create') }}" method="post">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="business_type">Business Type:</label>
                            <select id="business_type" name="business_type" class="form-control @error('business_type') is-invalid @enderror">
                                @foreach ($business_types as $business_type)
                                    <option value="{{$business_type->id}}" @if (old('business_type') == $business_type->id || (old('business_type') == null && $business_type->id == 1)) selected @endif>{{$business_type->name}}</option>
                                @endforeach                                
                            </select>
                            @error('business_type')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 first-group-name-row @if(old('business_type') == "1" || old('business_type') == null) col-md-3 @else col-md-6 @endif">
                        <div class="form-group">
                            <label for="first_name" id="first-group-name-label">@if(old('business_type') == "1" || old('business_type') == null) First Name (*): @else Group Name (*): @endif</label>
                            <input type="text" class="form-control @error('first_name') is-invalid @enderror" id="first_name" name="first_name" placeholder="@if(old('business_type') == "1" || old('business_type') == null) First Name @else Group Name @endif" value="{{ old('first_name') }}">
                            @error('first_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="middle_initial" id="middle-group-label">@if(old('business_type') == "1" || old('business_type') == null) Middle Initial: @else Group Contact: @endif</label>
                            <input type="text" class="form-control @error('middle_initial') is-invalid @enderror" id="middle_initial" name="middle_initial" placeholder="@if(old('business_type') == "1" || old('business_type') == null) Middle Initial @else Group Contact @endif" value="{{ old('middle_initial') }}">
                            @error('middle_initial')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 col-12 for-individual @if(old('business_type') == "2") d-none @endif">
                        <div class="form-group">
                            <label for="last_name">Last Name (*):</label>
                            <input type="text" class="form-control @error('last_name') is-invalid @enderror" id="last_name" name="last_name" placeholder="Last Name:" value="{{ old('last_name') }}">
                            @error('last_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 col-12 for-individual @if(old('business_type') == "2") d-none @endif">
                        <div class="form-group">
                            <label for="suffix">Suffix:</label>
                            <select id="suffix" name="suffix" class="form-control @error('suffix') is-invalid @enderror">
                                @foreach ($suffixes as $suffix)
                                    <option value="{{$suffix->id}}" @if (old('suffix') == $suffix->id) selected @endif>{{$suffix->name}}</option>
                                @endforeach                                
                            </select>
                            @error('suffix')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 col-12 for-individual @if(old('business_type') == "2") d-none @endif">
                        <div class="form-group">
                            <label for="date_birth">Date of Birth (*):</label>
                            <input type="date" class="form-control @error('date_birth') is-invalid @enderror" id="date_birth" name="date_birth" placeholder="Date of Birth:" value="{{ old('date_birth') }}">
                            @error('date_birth')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 col-12 for-individual @if(old('business_type') == "2") d-none @endif">
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
                    <div class="col-md-3 col-12 for-individual @if(old('business_type') == "2") d-none @endif">
                        <div class="form-group">
                            <label for="gender">Gender:</label>
                            <select id="gender" name="gender" class="form-control @error('gender') is-invalid @enderror">
                                @foreach ($genders as $gender)
                                    <option value="{{$gender->id}}" @if (old('gender') == $gender->id) selected @endif>{{$gender->name}}</option>
                                @endforeach                                
                            </select>
                            @error('gender')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 col-12 for-individual @if(old('business_type') == "2") d-none @endif">
                        <div class="form-group">
                            <label for="matiral_status">Marital Status:</label>
                            <select id="matiral_status" name="matiral_status" class="form-control @error('matiral_status') is-invalid @enderror">
                                @foreach ($matiral_statuses as $matiral_status)
                                    <option value="{{$matiral_status->id}}" @if (old('matiral_status') == $matiral_status->id) selected @endif>{{$matiral_status->name}}</option>
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
                            <input type="text" class="form-control @error('address') is-invalid @enderror" id="address" name="address" placeholder="Address:" value="{{ old('address') }}">
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
                            <input type="text" class="form-control @error('address_2') is-invalid @enderror" id="address_2" name="address_2" placeholder="Address 2:" value="{{ old('address_2') }}">
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
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" placeholder="Email:" value="{{ old('email') }}">
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
                            <select id="county" name="county" class="form-control @error('county') is-invalid @enderror">
                                @foreach ($counties as $county)
                                    <option value="{{$county->id}}" @if (old('county') == $county->id) selected @endif>{{$county->name}}</option>
                                @endforeach                                
                            </select>
                            @error('county')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            <input type="hidden" id="county-search-url" value="{{route('counties.loadInfo')}}">
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="state">State:</label>
                            <input type="text" readonly class="form-control @error('state') is-invalid @enderror" id="state" name="state" placeholder="State:" value="{{ old('state',(sizeof($counties) > 0 ? $counties[0]->state->name : '')) }}">
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
                            <input type="text" readonly class="form-control @error('region') is-invalid @enderror" id="region" name="region" placeholder="Region:" value="{{ old('region', (sizeof($counties) > 0 ? $counties[0]->region->name : '')) }}">
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
                            <input type="text" class="form-control @error('city') is-invalid @enderror" id="city" name="city" placeholder="City:" value="{{ old('city') }}">
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
                            <input type="text" class="form-control @error('zip_code') is-invalid @enderror" id="zip_code" name="zip_code" placeholder="Zip Code:" value="{{ old('zip_code') }}">
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
                            <input type="tel" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" placeholder="Phone:" value="{{ old('phone') }}">
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
                            <input type="tel" class="form-control @error('phone_2') is-invalid @enderror" id="phone_2" name="phone_2" placeholder="Phone 2:" value="{{ old('phone_2') }}">
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
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="contact_agent">Contact Agent:</label>
                            <input type="text" readonly data-id="contact_agent_id" class="search-agent-input search-icon form-control @error('contact_agent') is-invalid @enderror" id="contact_agent" name="contact_agent" placeholder="Contact Agent:" value="{{ old('contact_agent') }}">
                            @error('contact_agent')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            <input type="hidden" readonly id="contact_agent_id" name="contact_agent_id" value="{{ old('contact_agent_id') }}">
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
    @include('agents.partials.searchModal')
@stop

@section('js')
<script src="/js/customers/search-customer.js"></script>
<script src="/js/counties/load-county-info.js"></script>
<script src="/js/customers/change-business-type.js"></script>
<script src="/js/customers/search-agents.js"></script>
@stop