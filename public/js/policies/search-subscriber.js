$.ajaxSetup({
    headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
});

$(document).ready(function (e) {
    
    let calledBy;

    $("#search-subscriber-table").DataTable({
        pageLength: 50,
        processing: true,
        serverSide: true,
        ajax: {
            url: $("#search-subscriber-table").data("url"), 
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
                    return `<button class="search-subscriber-btn-select btn btn-outline-primary" data-name="${data.name}" data-id="${data.id}">Select</button>`;
                },
            }
        ],
    });
    

    $("body").on("click", ".search-subscriber-btn-select", function (e) {
        if(calledBy){
            calledBy.val($(this).data("name"));
            $(`#${calledBy.data("id")}`).val($(this).data("id"))
            
        }
        $("#first_name").val("")
        $("#last_name").val("")
        $("#date_birth").val("")
        $("#ssn").val("")
        $("#searchSubscriberModal").modal("hide");
    });


    $("body").on("click", ".clear-selected-subscriber", function (e) {
        if(calledBy){
            calledBy.val("");
            $(`#${calledBy.data("id")}`).val("")
        }
        $("#searchSubscriberModal").modal("hide");
    });

    $("body").on("click", ".search-subscriber-input", function (e) {
        $("#searchSubscriberModal").modal("show");
        calledBy = $(this);
    });
    
    
});
