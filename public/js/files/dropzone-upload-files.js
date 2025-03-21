
let myDropzone = new Dropzone("#file-upload-form", {
    clickable: true, // Asegura que sea clickeable
    init: function() {
        this.on('success', function(file, response) {
            $(".files-container").append(`<div class="file-item">
                <i class="fas fa-file"></i> ${response.filename}
                <a href="${response.route}" class="btn btn-outline-primary ml-3 mb-1"
                    download="">Download</a>
                <a href="${response.removeRoute}" class="btn btn-outline-danger ml-3 mb-1 ask" data-message="Remove this file"><i
                                class="fas fa-trash"></i> </a>
            </div>`);
        });
        this.on('error', function(file, response) {
            alertSwal("Error: " + response.error)
        });
    }
});