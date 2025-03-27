<div class="modal fade" id="addNewAgentNumberModal" tabindex="-1" aria-labelledby="addNewAgentNumberModal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('agent_numbers.create', ['id' => $agentID]) }}" method="POST">
                @csrf
                <input type="hidden" name="agentID" value="{{ $agentID }}" />

                <div class="modal-header">
                    <h5 class="modal-title">Add New Agent Number</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4 col-12">
                            <div class="form-group">
                                <label for="number">Agent Number (*):</label>
                                <input type="text" class="form-control @if ($errors->addNewAgentNumberForm->has('number')) is-invalid @endif"
                                    id="number" name="number" placeholder="Agent Number:" value="{{ old('number') }}">
                                @if ($errors->addNewAgentNumberForm->has('number'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->addNewAgentNumberForm->first('number') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4 col-12">
                            <div class="form-group">
                                <label for="agency_code">Agency Code:</label>
                                <select id="agency_code" name="agency_code"
                                    class="form-control @if ($errors->addNewAgentNumberForm->has('agency_code')) is-invalid @endif">
                                    <option value=""></option>
                                    @foreach ($agency_codes as $agency_code)
                                        <option value="{{ $agency_code->id }}"
                                            @if (old('agency_code') == $agency_code->id) selected @endif>{{ $agency_code->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @if ($errors->addNewAgentNumberForm->has('agency_code'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->addNewAgentNumberForm->first('agency_code') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4 col-12">
                            <div class="form-group">
                                <label for="carrier">Carrier (*):</label>
                                <select id="carrier" name="carrier"
                                    class="form-control @if ($errors->addNewAgentNumberForm->has('carrier')) is-invalid @endif">
                                    @foreach ($carriers as $carrier)
                                        <option value="{{ $carrier->id }}"
                                            @if (old('carrier') == $carrier->id) selected @endif>{{ $carrier->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @if ($errors->addNewAgentNumberForm->has('carrier'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->addNewAgentNumberForm->first('carrier') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4 col-12">
                            <div class="form-group">
                                <label for="agent_title">Agent Title:</label>
                                <select id="agent_title" name="agent_title"
                                    class="form-control @if ($errors->addNewAgentNumberForm->has('agent_title')) is-invalid @endif">
                                    <option value=""></option>
                                    @foreach ($agent_titles as $agent_title)
                                        <option value="{{ $agent_title->id }}"
                                            @if (old('agent_title') == $agent_title->id) selected @endif>{{ $agent_title->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @if ($errors->addNewAgentNumberForm->has('agent_title'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->addNewAgentNumberForm->first('agent_title') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4 col-12">
                            <div class="form-group">
                                <label for="agent_status">Agent Status:</label>
                                <select id="agent_status" name="agent_status"
                                    class="form-control @if ($errors->addNewAgentNumberForm->has('agent_status')) is-invalid @endif">
                                    <option value=""></option>
                                    @foreach ($agent_statuses as $agent_status)
                                        <option value="{{ $agent_status->id }}"
                                            @if (old('agent_status') == $agent_status->id) selected @endif>{{ $agent_status->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @if ($errors->addNewAgentNumberForm->has('agent_status'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->addNewAgentNumberForm->first('agent_status') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4 col-12">
                            <div class="form-group">
                                <label for="agency">Pay to Agency:</label>
                                <select id="agency" name="agency"
                                    class="form-control @if ($errors->addNewAgentNumberForm->has('agency')) is-invalid @endif">
                                    <option value=""></option>
                                    @foreach ($agencies as $agency)
                                        <option value="{{ $agency->id }}"
                                            @if (old('agency') == $agency->id) selected @endif>{{ $agency->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @if ($errors->addNewAgentNumberForm->has('agency'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->addNewAgentNumberForm->first('agency') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4 col-12">
                            <div class="form-group">
                                <label for="contract_rate">Contract Rate (*):</label>
                                <input type="number" step="0.01" max="1" class="form-control @if ($errors->addNewAgentNumberForm->has('contract_rate')) is-invalid @endif"
                                    id="contract_rate" name="contract_rate" placeholder="Contract Rate:" value="{{ old('contract_rate') }}">
                                @if ($errors->addNewAgentNumberForm->has('contract_rate'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->addNewAgentNumberForm->first('contract_rate') }}</strong>
                                    </span>
                                @endif
                                
                            </div>
                        </div>
                        <div class="col-md-4 col-12">
                            <div class="form-group">
                                <label for="admin_fee">Admin Fee Schedule:</label>
                                <select id="admin_fee" name="admin_fee"
                                    class="form-control @if ($errors->addNewAgentNumberForm->has('admin_fee')) is-invalid @endif">
                                    <option value=""></option>
                                    @foreach ($admin_fees as $admin_fee)
                                        <option value="{{ $admin_fee->id }}"
                                            @if (old('admin_fee') == $admin_fee->id) selected @endif>{{ $admin_fee->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @if ($errors->addNewAgentNumberForm->has('admin_fee'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->addNewAgentNumberForm->first('admin_fee') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        @for ($i = 1; $i <= 5; $i++)
                            <div class="col-md-4 col-12">
                                <div class="form-group">
                                    <label for="mentor_agent_{{$i}}">Mentor Agent {{$i}}:</label>
                                    <select id="mentor_agent_{{$i}}" name="mentor_agent_{{$i}}"
                                        class="form-control @if ($errors->addNewAgentNumberForm->has('mentor_agent_'.$i)) is-invalid @endif">
                                        <option value=""></option>
                                        @foreach ($agents as $agent)
                                            <option value="{{ $agent->id }}"
                                                @if (old('mentor_agent_'.$i) == $agent->id) selected @endif>
                                                    {{ $agent->id }} - {{$agent->first_name}} {{$agent->last_name}}
                                            </option>
                                        @endforeach
                                    </select>
                                    @if ($errors->addNewAgentNumberForm->has('mentor_agent_'.$i))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->addNewAgentNumberForm->first('mentor_agent_'.$i) }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endfor

                        @for ($i = 1; $i <= 5; $i++)
                            <div class="col-md-4 col-12">
                                <div class="form-group">
                                    <label for="override_agent_{{$i}}">Override Agent {{$i}}:</label>
                                    <select id="override_agent_{{$i}}" name="override_agent_{{$i}}"
                                        class="form-control @if ($errors->addNewAgentNumberForm->has('override_agent_'.$i)) is-invalid @endif">
                                        <option value=""></option>
                                        @foreach ($agents as $agent)
                                            <option value="{{ $agent->id }}"
                                                @if (old('override_agent_'.$i) == $agent->id) selected @endif>
                                                    {{ $agent->id }} - {{$agent->first_name}} {{$agent->last_name}}
                                            </option>
                                        @endforeach
                                    </select>
                                    @if ($errors->addNewAgentNumberForm->has('override_agent_'.$i))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->addNewAgentNumberForm->first('override_agent_'.$i) }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endfor

                        <div class="col-md-12 col-12">
                            <div class="form-group">
                                <label for="notes">Agent # Notes:</label>
                                <textarea class="form-control @if ($errors->addNewAgentNumberForm->has('notes')) is-invalid @endif" rows="5" id="notes"
                                    name="notes" placeholder="Agent # Notes:">{{ old('notes') }}</textarea>
                                @if ($errors->addNewAgentNumberForm->has('notes'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->addNewAgentNumberForm->first('notes') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" >Add New Agent Number</button>
                </div>
            </form>
        </div>
    </div>
</div>
