$.ajaxSetup({
    headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
});

$(document).ready(function () {
    $(".datatable").DataTable({
        pageLength: 50,
        layout: {
            topStart: {
                buttons: ["copy", "excel", "pdf"],
            },
        },
        scrollX: true,
        processing: true,
        serverSide: true,
        ajax: {
            url: $(".datatable").data("url"),
            type: "POST",
            data: function (d) {
                //d.business_type = $("#business_type").val();
            },
        },
        columns: [
            {
                data: "policy_id",
                render: function (data, type, row) {
                    return `<a href="${data.href}" class="text-nowrap">${data.text}</a>`;
                },
            },
            {
                data: "suscriber",
                render: function (data, type, row) {
                    return `<a href="${data.href}" class="text-nowrap">${data.text}</a>`;
                },
            },
            { data: "date_birth" },
            { data: "carrier" },
            { data: "product_type" },
            {
                data: "product",
                render: function (data, type, row) {
                    return `<a href="${data.href}" class="text-nowrap">${data.text}</a>`;
                },
            },
            { data: "application_id" },
            { data: "contract_id" },
            { data: "original_effective_date" },
            { data: "benefit_effective_date" },
            { data: "cancel_date" },
            { data: "status" },
            {
                data: "agent",
                render: function (data, type, row) {
                    return `<a href="${data.href}" class="text-nowrap">${data.text}</a>`;
                },
            },
            {
                data: "agent_number",
                render: function (data, type, row) {
                    return `<a href="${data.href}" class="text-nowrap">${data.text}</a>`;
                },
            }
        ],
    });
});
