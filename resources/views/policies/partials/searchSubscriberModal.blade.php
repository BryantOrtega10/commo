<div class="modal fade" id="searchSubscriberModal" tabindex="-1" aria-labelledby="searchSubscriberModal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Subscriber search</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="modal-response">
                    <div class="search-subscriber-filters">
                        <div class="row">
                            <div class="col-3 mb-3">
                                <input type="button" class="btn btn-outline-danger clear-selected-subscriber"
                                    value="Clear selected" />
                            </div>
                        </div>

                    </div>
                    <div class="search-subscriber-table-container">
                        <table class="table table-striped min-w-100" id="search-subscriber-table"
                            data-url="{{ route('customers.searchSubscribers') }}">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
