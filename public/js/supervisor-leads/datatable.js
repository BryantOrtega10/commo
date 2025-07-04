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
        processing: true,
        serverSide: true,
        ajax: {
            url: $(".datatable").data("url"), 
            type: "POST", 
            data: function(d) {
                d.first_name = $("#first_name").val();
                d.last_name = $("#last_name").val();
                d.email = $("#email").val();
                d.phone = $("#phone").val();
                d.status = $("#status").val();
                d.date_birth_from = $("#date_birth_from").val();
                d.date_birth_to = $("#date_birth_to").val();
                d.phase = $("#phase").val();
                d.legal_basis = $("#legal_basis").val();
            },
        },
        columns: [
            {
                data: 'lead',
                render: function (data, type, row) {
                    return `<a href="${data.href}" class="text-nowrap">${data.text}</a>`;
                },
            },
            {
                data: 'email'
            },
            {
                data: 'phone'
            },
            {
                data: 'status'
            },
            {
                data: 'agent'
            },
            {
                data: 'actions',
                render: function (data, type, row) {
                    return `<a href="${data.view}" class="btn btn-outline-primary">View</a>
                            <a href="${data.reassign}" class="btn btn-outline-secondary">Reassign</a>`;
                },
            }
        ],
    });
});