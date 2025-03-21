<div class="modal fade" id="editCUID" tabindex="-1" aria-labelledby="editCUID" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('cuids.update', ['id' => $cuid->id]) }}" method="POST">
                @csrf
                <input type="hidden" name="cuidID" value="{{ $cuid->id }}" />

                <div class="modal-header">
                    <h5 class="modal-title">Edit New CUID</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4 col-12">
                            <div class="form-group">
                                <label for="cuid">CUID:</label>
                                <input type="text" class="form-control @if ($errors->editCUIDForm->has('cuid')) is-invalid @endif"
                                    id="cuid" name="cuid" placeholder="CUID:" value="{{ old('cuid',$cuid->name) }}">
                                @if ($errors->editCUIDForm->has('cuid'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->editCUIDForm->first('cuid') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4 col-12">
                            <div class="form-group">
                                <label for="carrier">Carrier:</label>
                                <select id="carrier" name="carrier"
                                    class="form-control @if ($errors->editCUIDForm->has('carrier')) is-invalid @endif">
                                    @foreach ($carriers as $carrier)
                                        <option value="{{ $carrier->id }}"
                                            @if (old('carrier',$cuid->fk_carrier) == $carrier->id) selected @endif>{{ $carrier->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @if ($errors->editCUIDForm->has('carrier'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->editCUIDForm->first('carrier') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4 col-12">
                            <div class="form-group">
                                <label for="business_segment">Business Segment:</label>
                                <select id="business_segment" name="business_segment"
                                    class="form-control @if ($errors->editCUIDForm->has('business_segment')) is-invalid @endif">
                                    @foreach ($business_segments as $business_segment)
                                        <option value="{{ $business_segment->id }}"
                                            @if (old('business_segment',$cuid->fk_business_segment) == $business_segment->id) selected @endif>
                                            {{ $business_segment->name }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->editCUIDForm->has('business_segment'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->editCUIDForm->first('business_segment') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4 col-12">
                            <div class="form-group">
                                <label for="validation_date">Validation Date:</label>
                                <input type="date"
                                    class="form-control @if ($errors->editCUIDForm->has('validation_date')) is-invalid @endif"
                                    id="validation_date" name="validation_date" placeholder="Validation Date:"
                                    value="{{ old('validation_date',$cuid->validation_date) }}">
                                @if ($errors->editCUIDForm->has('validation_date'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->editCUIDForm->first('validation_date') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-12 col-12">
                            <div class="form-group">
                                <label for="validation_note">Validation Note:</label>
                                <textarea class="form-control @if ($errors->editCUIDForm->has('validation_note')) is-invalid @endif" rows="5" id="validation_note"
                                    name="validation_note" placeholder="Validation Note:">{{ old('validation_note',$cuid->validation_note) }}</textarea>
                                @if ($errors->editCUIDForm->has('validation_note'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->editCUIDForm->first('validation_note') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" >Edit CUID</button>
                </div>
            </form>
        </div>
    </div>
</div>
