$.ajaxSetup({
    headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
});

$(document).ready(function () {

    $("body").on("change","#all_agents", function(e){
        e.preventDefault();
        if($(this).is(":checked")){
            $(".cont-agents-table").addClass("d-none");
        }
        else{
            $(".cont-agents-table").removeClass("d-none");
        }
    })

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
                d.agent_title = $("#agent_title").val();
                d.statement_date = $("#statement_date").val();
            },
        },
        columns: [
            {
                data: "id",
                render: function (data, type, row) {
                    return `<input type="checkbox" name="agentNumberID[]" value="${data.id}">`;
                },
            },
            {
                data: "agent_id",
            },
            {
                data: "agent",
                render: function (data, type, row) {
                    return `<a href="${data.href}">${data.text}</a>`;
                },
            },
            {
                data: "agent_title",
            },
            {
                data: "agent_status",
            },
            {
                data: "contract_type",
            },
            {
                data: "agency_code",
            },
            {
                data: "agent_number",
                render: function (data, type, row) {
                    return `<a href="${data.href}">${data.text}</a>`;
                },
            },
            {
                data: "carrier",
            },
            {
                data: "override_agents",
                render: function (data, type, row) {
                    let html = "";
                    data.items.forEach((override_agent) => {
                        html += `<div class="agents">${override_agent}</div>`;
                    });
                    return html;
                },
            },
            {
                data: "mentor_agents",
                render: function (data, type, row) {
                    let html = "";
                    data.items.forEach((override_agent) => {
                        html += `<div class="agents">${override_agent}</div>`;
                    });
                    return html;
                },
            },
            {
                data: "contract_date",
            },
            {
                data: "email",
            },
            {
                data: "phone",
            },
        ],
    });
});
