$(document).ready(function () {
    $("body").on("click", ".add-new-alias", function(e){
        e.preventDefault();
        $(".alias-cont").append(`<div class="row align-items-end alias-item">
                                    <div class="col-md-6 col-8">
                                        <div class="form-group">
                                            <label for="alias_${$(".alias-item").length}" class="lb-alias">Alias ${$(".alias-item").length + 1}:</label>
                                            <input type="text" class="form-control" id="alias_${$(".alias-item").length}" name="alias[]">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-4">
                                        <button type="button" class="btn btn-outline-danger delete-alias mb-3">Delete</button>
                                    </div>
                                </div>
                                `);
    });

    $("body").on("click", ".delete-alias", function(e){
        $(this).closest(".alias-item").remove();
        //Rename IDs
        
        $(".alias-item").each(function(i, aliasItem) {
            $(aliasItem).find(".lb-alias").html(`Alias ${i + 1}`);
            $(aliasItem).find(".lb-alias").prop("for", `alias_${i}`);
            $(aliasItem).find(".form-control").prop("id", `alias_${i}`);
        });
   
    });
});