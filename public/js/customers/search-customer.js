$.ajaxSetup({
    headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
});

$(document).ready(function (e) {
    
    let calledBy;

    $("#search-customer-table").DataTable({
        pageLength: 50,
        processing: true,
        serverSide: true,
        ajax: {
            url: $("#search-customer-table").data("url"), 
            type: "POST"
        },
        columns: [{
                data: 'id'
            },
            {
                data: 'name'
            },
            {
                data: 'email'
            },
            {
                data: 'phone'
            },
            {
                data: 'actions',
                render: function (data, type, row) {
                    return `<button class="search-customer-btn-select btn btn-outline-primary" data-name="${data.name}" data-id="${data.id}">Select</button>`;
                },
            }
        ],
    });
    

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
    
    
});
