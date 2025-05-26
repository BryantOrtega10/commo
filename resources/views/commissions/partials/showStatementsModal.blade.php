<div class="modal fade" id="showStatementsModal" tabindex="-1" aria-labelledby="showStatementsModal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Statements</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="modal-response">
                    <div class="statements-table-container">
                        <table class="table table-striped min-w-100" id="statements-table">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Agent</th>
                                    <th>Agent Type</th>
                                    <th>Compensation Ammount</th>
                                    <th>Applied rate</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($statementItems as $statementItem)
                                    <tr>
                                        <td>{{$statementItem->id}}</td>
                                        <td>{{$statementItem->statement?->agent_number?->agent?->first_name}} {{$statementItem->statement?->agent_number?->agent?->last_name}}</td>
                                        <td>{{$statementItem->txt_agent_type}}</td>
                                        <td>$ {{number_format($statementItem->comp_amount,2)}}</td>
                                        <td>{{$statementItem->commission_rate?->rate_amount}}</td>
                                    </tr>
                                @endforeach
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
