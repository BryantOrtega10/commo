$.ajaxSetup({
    headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
});

$(document).ready(function (e) {
    
    let calledBy;

    $("#search-customer-table").DataTable();

    $("body").on("click", ".search-customer-btn-select", function (e) {
        if(calledBy){
            calledBy.val($(this).data("name"));
            $(`#${calledBy.data("id")}`).val($(this).data("id"))
        }
        $("#searchCustomerModal").modal("hide");
    });


    $("body").on("click", ".clear-selected-customer", function (e) {
        if(calledBy){
            calledBy.val("");
            $(`#${calledBy.data("id")}`).val("")
        }
        $("#searchCustomerModal").modal("hide");
    });

    $("body").on("click", ".search-customer-input", function (e) {
        $("#searchCustomerModal").modal("show");
        calledBy = $(this);
    });
    
    $("body").on("submit", ".search-customer-form", function (e) {
        e.preventDefault();
        var formdata = new FormData(this);
        $.ajax({
            type: 'POST',
            url: $(this).attr("action"),
            cache: false,
            processData: false,
            contentType: false,
            data: formdata,
            success: function(data) {
                $(".modal-response").html(data);
                $("#search-customer-table").DataTable();
            },
            error: function(data) {
                console.log("error");
                console.log(data);
            }
        });
    });
});
