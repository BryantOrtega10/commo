<div class="modal fade" id="showEditModal" tabindex="-1" aria-labelledby="showEditModal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form action="{{route('commissions.calculation.update',['id' => $commissionRow->id])}}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Row</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="modal-response">
                        @foreach ($data as $index => $value) 
                            @php
                                $endWord = explode("_",$index);
                                $endWord = last($endWord);
                            @endphp
                            <div class="row form-group">
                                <div class="col-3">
                                    {{ucwords(str_replace("_", " ", $index))}}
                                </div>
                                <div class="col-9">
                                    <input type="{{($endWord == 'date' ? 'date' : 'text')}}" class="form-control" name="data_{{$index}}" value="{{$value}}" />
                                </div>
                            </div>
                        @endforeach

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>
