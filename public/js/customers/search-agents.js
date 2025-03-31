$.ajaxSetup({
    headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
});

$(document).ready(function (e) {
    
    let calledBy;

    
    $("#search-agent-table").DataTable({
        pageLength: 50,
        processing: true,
        serverSide: true,
        ajax: {
            url: $("#search-agent-table").data("url"), 
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
                    return `<button class="search-agent-btn-select btn btn-outline-primary" data-name="${data.name}" data-id="${data.id}">Select</button>`;
                },
            }
        ],
    });


    $("body").on("click", ".search-agent-btn-select", function (e) {
        if(calledBy){
            calledBy.val($(this).data("name"));
            $(`#${calledBy.data("id")}`).val($(this).data("id"))
        }
        $("#searchAgentModal").modal("hide");
    });


    $("body").on("click", ".clear-selected-agent", function (e) {
        if(calledBy){
            calledBy.val("");
            $(`#${calledBy.data("id")}`).val("")
        }
        $("#searchAgentModal").modal("hide");
    });

    $("body").on("click", ".search-agent-input", function (e) {
        $("#searchAgentModal").modal("show");
        calledBy = $(this);
    });

   
});
