<div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Please change your password</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" action="{{route('users.changePassword')}}">
                @csrf
                <div class="modal-body">
                    <div class="modal-response">
                        <div class="row"> 
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label for="password">Password:</label>
                                    <input type="password" class="form-control @if ($errors->changePasswordForm->has('password')) is-invalid @endif"
                                        id="password" name="password" placeholder="Password:" value="{{ old('password') }}">
                                    @if ($errors->changePasswordForm->has('password'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->changePasswordForm->first('password') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label for="repeat_password">Repeat Password:</label>
                                    <input type="password" class="form-control @if ($errors->changePasswordForm->has('repeat_password')) is-invalid @endif"
                                        id="repeat_password" name="repeat_password" placeholder="Repeat Password:" value="{{ old('repeat_password') }}">
                                    @if ($errors->changePasswordForm->has('repeat_password'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->changePasswordForm->first('repeat_password') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>               
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Change password</button>
                    
                </div>
            </form>
        </div>
    </div>
</div>