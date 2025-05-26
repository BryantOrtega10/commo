$.ajaxSetup({
    headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
});

$(document).ready(function () {
    let columns = [
        {
            data: "id",
            render: function (data, type, row) {
                return `<input type="checkbox" name="commissionRow[]" value="${data.id}">`;
            },
        },
        {
            data: "actions",
            render: function (data, type, row) {
                return `<a href="${data.edit_href}" class="btn btn-secondary btn-edit">Edit</a> 
                            <a href="${data.delete_href}" class="btn btn-danger ask" data-message="Delete this commission upload row">Delete</a>`;
            },
        },
        {
            data: "status",
        },
        {
            data: "notes",
            render: function (data, type, row) {
                return `<pre>${data}</pre>`;
            },
        },
        {
            data: "statements",
            render: function (data, type, row) {
                return `<a href="${data.href}" class="text-nowrap open-statements-modal">${data.text}</a>`;
            },
        },
    ];
    const headers = JSON.parse($("#headers").val());

    headers.forEach((header) => {
        columns.push({
            data: `${header}`,
        });
    });

    $(".datatable").DataTable({
        pageLength: 50,
        processing: true,
        serverSide: true,
        scrollX: true,
        ajax: {
            url: $(".datatable").data("url"),
            type: "POST",
            data: function (d) {},
        },
        columns: columns,
    });
});
