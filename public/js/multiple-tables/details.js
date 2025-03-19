$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
$(document).ready(function(e) {
    $("body").on("click", ".details", function(e) {
        e.preventDefault()
        $.ajax({
            type: 'GET',
            url: $(this).attr("href"),
            success: function(data) {
                if (data.success) {
                    $("#detailsModal").modal("show");
                    $("#details-name").html(data.item.name);
                    $("#details-description").html(data.item.description);
                    $("#details-sort-order").html(data.item.sort_order);
                    $("#details-status").html(data.item.txt_status);
                    $("#details-status").removeClass("alert-success");
                    $("#details-status").removeClass("alert-secondary");
                    if(data.item.status){
                        $("#details-status").addClass("alert-success");                                
                    }
                    else{
                        $("#details-status").addClass("alert-secondary");
                    }
                }
            },
            error: function(data) {
                console.log("error");
                console.log(data);
            }
        });
    })
})