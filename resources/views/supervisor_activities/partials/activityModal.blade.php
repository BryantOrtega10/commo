<div class="modal fade" id="activityModal" tabindex="-1" aria-labelledby="activityModal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{["","Create Note","Send Email","Register call","Register meeting", "Create Task"][$type]}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" action="{{route('supervisor.leads.createActivity',['idLead' => $idLead])}}" class="form-activity">
                @csrf
                <input type="hidden" name="type" value="{{$type}}" />
                <div class="modal-body">
                    <div class="modal-response">
                        <div class="row"> 
                            <div class="col-12">
                                <input type="hidden" id="html_desc" name="html_desc" value="{{old('html_desc')}}">
                                <input type="hidden" id="text_desc" name="description">
                                <div id="description"></div>
                            </div>
                            <div class="col-12">
                                @if ($errors->addActivityForm->has('description'))
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $errors->addActivityForm->first('description') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="col-12 @if($type == '5') d-none @endif">
                                <div class="form-group">
                                    <div class="icheck-secondary" title="Create task?">
                                        <input type="checkbox" name="create_task" id="create_task" value="1"
                                            {{ old('create_task') ? 'checked' : '' }}
                                            @if($type == '5') checked @endif>
                                        <label for="create_task">Create task?</label>
                                    </div>
                                    @if ($errors->addActivityForm->has('create_task'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->addActivityForm->first('create_task') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-3 col-12 for-task @if($type <> '5' && old('create_task') == null) d-none @endif">
                                <div class="form-group">
                                    <label for="task_name">Task name:</label>
                                    <input type="text" class="form-control @if ($errors->addActivityForm->has('task_name')) is-invalid @endif"
                                        id="task_name" name="task_name" placeholder="Task name:" value="{{ old('task_name') }}">
                                    @if ($errors->addActivityForm->has('task_name'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->addActivityForm->first('task_name') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-3 col-12 for-task @if($type <> '5' && old('create_task') == null) d-none @endif">
                                <div class="form-group">
                                    <label for="expiration_date">Expiration date:</label>
                                    <input type="date" class="form-control @if ($errors->addActivityForm->has('expiration_date')) is-invalid @endif"
                                        id="expiration_date" name="expiration_date" placeholder="Expiration date:" value="{{ old('expiration_date') }}">
                                    @if ($errors->addActivityForm->has('expiration_date'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->addActivityForm->first('expiration_date') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-3 col-12 for-task @if($type <> '5' && old('create_task') == null) d-none @endif">
                                <div class="form-group">
                                    <label for="expiration_hour">Expiration hour:</label>
                                    <input type="time" class="form-control @if ($errors->addActivityForm->has('expiration_hour')) is-invalid @endif"
                                        id="expiration_hour" name="expiration_hour" placeholder="Expiration date:" value="{{ old('expiration_hour') }}">
                                    @if ($errors->addActivityForm->has('expiration_hour'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->addActivityForm->first('expiration_hour') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-3 col-12 for-task @if($type <> '5' && old('create_task') == null) d-none @endif">
                                <div class="form-group">
                                    <label for="priority">Priority:</label>
                                    <select id="priority" name="priority"
                                        class="form-control @if ($errors->addActivityForm->has('priority')) is-invalid @endif">
                                            <option value="1" @if (old('priority') == "1") selected @endif>Low</option>
                                            <option value="2" @if (old('priority') == "2") selected @endif>Medium</option>
                                            <option value="3" @if (old('priority') == "3") selected @endif>High</option>                                        
                                    </select>
                                    @if ($errors->addActivityForm->has('priority'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->addActivityForm->first('priority') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">{{["","Create Note","Send Email","Register call","Register meeting", "Create Task"][$type]}}</button>
                    
                </div>
            </form>
        </div>
    </div>
</div>
