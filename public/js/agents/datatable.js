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
                d.first_name = $("#first_name").val();
                d.last_name = $("#last_name").val();
                d.agent_number = $("#agent_number").val();
                d.agency_code = $("#agency_code").val();
                d.email = $("#email").val();
                d.phone = $("#phone").val();
                d.contract_type = $("#contract_type").val();
                d.agent_title = $("#agent_title").val();
                d.agent_status = $("#agent_status").val();
                d.carrier = $("#carrier").val();
                d.contract_date_from = $("#contract_date_from").val();
                d.contract_date_to = $("#contract_date_to").val();
                d.mentor_agent = $("#mentor_agent").val();
                d.override_agent = $("#override_agent").val();
            },
        },
        columns: [{
                data: 'agent_id'
            },
            {
                data: 'agent',
                render: function (data, type, row) {
                    return `<a href="${data.href}">${data.text}</a>`;
                },
            },
            {
                data: 'agent_title'
            },
            {
                data: 'agent_status'
            },
            {
                data: 'contract_type'
            },
            {
                data: 'agency_code'
            },
            {
                data: 'agent_number',
                render: function (data, type, row) {
                    return `<a href="${data.href}">${data.text}</a>`;
                },
            },
            {
                data: 'carrier'
            },
            {
                data: 'override_agents',
                render: function (data, type, row) {
                    let html = '';
                    data.items.forEach(override_agent => {
                        html += `<div class="agents">${override_agent}</div>`
                    });
                    return html;
                },
            },
            {
                data: 'mentor_agents',
                render: function (data, type, row) {
                    let html = '';
                    data.items.forEach(override_agent => {
                        html += `<div class="agents">${override_agent}</div>`
                    });
                    return html;
                },
            },
            {
                data: 'contract_date'
            },
            {
                data: 'email'
            },
            {
                data: 'phone'
            },
        ],
    });
});