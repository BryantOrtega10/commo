$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$(document).ready(function() {
    
    $(".datatable").DataTable({
        pageLength: 50,
        layout: {
            topStart: {
                buttons: [
                    'copy', 'excel', 'pdf'
                ]
            }
        },
        scrollX: true,
        processing: true,
        serverSide: true,
        ajax: {
            url: $(".datatable").data("url"), 
            type: "POST", 
            data: function(d) {
                d.description = $("#description").val();
                d.carrier = $("#carrier").val();
                d.business_type = $("#business_type").val();
                d.plan_type = $("#plan_type").val();
                d.product_type = $("#product_type").val();
            },
        },
        columns: [
            {
                data: 'description',
                render: function (data, type, row) {
                    return `<a href="${data.href}" class="text-nowrap">${data.text}</a>`;
                },
            },
            {
                data: 'carriers'
            },
            {
                data: 'business_segments'
            },
            {
                data: 'business_types'
            },
            {
                data: 'product_types'
            },
            {
                data: 'plan_types'
            },
            {
                data: 'tiers'
            }
        ],
    });
});