<div class="modal fade" id="activityModal" tabindex="-1" aria-labelledby="activityModal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Details of the {{$activity->txt_type}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" action="{{route('leads.activityDetailsModal',['id' => $activity->id])}}" class="form-activity">
                @csrf
                <input type="hidden" name="resendMail" id="resendMail" value="0" />
                <input type="hidden" name="activityId" id="activityId" value="{{$activity->id}}" />
                <div class="modal-body">
                    <div class="modal-response">
                        <div class="row"> 
                            <div class="col-12">
                                <input type="hidden" id="html_desc" name="html_desc" value="{{old('html_desc', $activity->description)}}">
                                <input type="hidden" id="text_desc" name="description" value="{{strip_tags($activity->description)}}">
                                <div id="description"></div>
                            </div>
                            <div class="col-12">
                                @if ($errors->detailsActivityForm->has('description'))
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $errors->detailsActivityForm->first('description') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="col-12 @if($activity->task_name != null) d-none @endif">
                                <div class="form-group">
                                    <div class="icheck-secondary" title="Create task?">
                                        <input type="checkbox" name="create_task" id="create_task" value="1"
                                            {{ old('create_task', ($activity->task_name != null)) ? 'checked' : '' }}
                                           >
                                        <label for="create_task">Create task?</label>
                                    </div>
                                    @if ($errors->detailsActivityForm->has('create_task'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->detailsActivityForm->first('create_task') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-3 col-12 for-task @if($activity->task_name == null) d-none @endif">
                                <div class="form-group">
                                    <label for="task_name">Task name:</label>
                                    <input type="text" class="form-control @if ($errors->detailsActivityForm->has('task_name')) is-invalid @endif"
                                        id="task_name" name="task_name" placeholder="Task name:" value="{{ old('task_name',$activity->task_name) }}">
                                    @if ($errors->detailsActivityForm->has('task_name'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->detailsActivityForm->first('task_name') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-3 col-12 for-task @if($activity->task_name == null) d-none @endif">
                                <div class="form-group">
                                    <label for="expiration_date">Expiration date:</label>
                                    <input type="date" class="form-control @if ($errors->detailsActivityForm->has('expiration_date')) is-invalid @endif"
                                        id="expiration_date" name="expiration_date" placeholder="Expiration date:" value="{{ old('expiration_date',substr($activity->expiration_date, 0, 10)) }}">
                                    @if ($errors->detailsActivityForm->has('expiration_date'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->detailsActivityForm->first('expiration_date') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-3 col-12 for-task @if($activity->task_name == null) d-none @endif">
                                <div class="form-group">
                                    <label for="expiration_hour">Expiration hour:</label>
                                    <input type="time" class="form-control @if ($errors->detailsActivityForm->has('expiration_hour')) is-invalid @endif"
                                        id="expiration_hour" name="expiration_hour" placeholder="Expiration date:" value="{{ old('expiration_hour',substr($activity->expiration_date, 11, 5)) }}">
                                    @if ($errors->detailsActivityForm->has('expiration_hour'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->detailsActivityForm->first('expiration_hour') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-3 col-12 for-task @if($activity->task_name == null) d-none @endif">
                                <div class="form-group">
                                    <label for="priority">Priority:</label>
                                    <select id="priority" name="priority"
                                        class="form-control @if ($errors->detailsActivityForm->has('priority')) is-invalid @endif">
                                            <option value="1" @if (old('priority',$activity->priority) == "1") selected @endif>Low</option>
                                            <option value="2" @if (old('priority',$activity->priority) == "2") selected @endif>Medium</option>
                                            <option value="3" @if (old('priority',$activity->priority) == "3") selected @endif>High</option>                                        
                                    </select>
                                    @if ($errors->detailsActivityForm->has('priority'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->detailsActivityForm->first('priority') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-3 col-12 for-task @if($activity->task_name == null) d-none @endif">
                                <div class="form-group">
                                    <label for="isDone">Task is done?:</label>
                                    <select id="isDone" name="isDone"
                                        class="form-control @if ($errors->detailsActivityForm->has('isDone')) is-invalid @endif">
                                            <option value="0" @if (old('isDone',$activity->isDone) == "0") selected @endif>No</option>
                                            <option value="1" @if (old('isDone',$activity->isDone) == "1") selected @endif>Yes</option>
                                    </select>
                                    @if ($errors->detailsActivityForm->has('isDone'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->detailsActivityForm->first('isDone') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update {{$activity->txt_type}}</button>
                    @if ($activity->type == 2)
                        <button type="button" class="btn btn-outline-primary resend-mail">Forward Email</button>
                    @endif
                    
                </div>
            </form>
        </div>
    </div>
</div>
