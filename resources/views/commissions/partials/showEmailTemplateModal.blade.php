<div class="modal fade" id="showEmailTemplateModal" tabindex="-1" aria-labelledby="showEmailTemplateModal"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('commissions.agent-process.show-email') }}" method="POST" class="form-email">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Email Statement Template</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="modal-response">
                        <input type="hidden" id="html_desc" name="html_desc" value="{{ $template->description }}">
                        <input type="hidden" id="text_desc" name="description">
                        <div id="description"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
