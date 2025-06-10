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
        },
        columns: [
            {
                data: 'name'
            },
            {
                data: 'email'
            },
            {
                data: 'role'
            },
            {
                data: 'status',
                render: function (data, type, row) {
                    return `<span class="p-0 px-3 alert ${ data.number ? 'alert-success' : 'alert-secondary' } text-bigger">${ data.text }</span>`;
                },
            },
            {
                data: 'actions',
                render: function (data, type, row) {
                    return `<a href="${data.edit}" class="btn btn-outline-primary">Edit</a>
                            <a href="${data.log}" class="btn btn-outline-secondary">Log</a>`;
                },
            },
        ],
    });
});