$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$(document).ready(function() {
    let columns = [{
            data: 'id',
            render: function(data, type, row) {
                return `<input type="checkbox" name="commissionRow[]" value="${data.id}">`;
            },
        },
        {
            data: 'status'
        },
        {
            data: 'notes'
        },
    ]
    const headers = JSON.parse($("#headers").val());
    
    headers.forEach(header => {
        columns.push({
            data: `${header}`
        })
    });

    $(".datatable").DataTable({
        pageLength: 50,
        processing: true,
        serverSide: true,
        scrollX: true,
        ajax: {
            url: $(".datatable").data("url"),
            type: "POST",
            data: function(d) {

            },
        },
        columns: columns,
    });
});