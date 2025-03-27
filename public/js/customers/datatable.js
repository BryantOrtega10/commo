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
            url: $(".datatable").data("url"), // URL de la API o endpoint que devuelve los datos
            type: "POST", // Método HTTP, puede ser POST o GET
            data: function(d) {
                d.business_type = $("#business_type").val();
                d.first_name = $("#first_name").val();
                d.middle_initial = $("#middle_initial").val();
                d.last_name = $("#last_name").val();
                d.suffix = $("#suffix").val();
                d.date_birth = $("#date_birth").val();
                d.ssn = $("#ssn").val();
                d.gender = $("#gender").val();
                d.matiral_status = $("#matiral_status").val();
                d.email = $("#email").val();
                d.address = $("#address").val();
                d.address_2 = $("#address_2").val();
                d.county = $("#county").val();
                d.city = $("#city").val();
                d.zip_code = $("#zip_code").val();
                d.phone = $("#phone").val();
                d.phone_2 = $("#phone_2").val();
                d.registration_source = $("#registration_source").val();
                d.status = $("#status").val();
                d.phase = $("#phase").val();
                d.legal_basis = $("#legal_basis").val();
            },
        },
        columns: [{
                data: 'id'
            },
            {
                data: 'customer',
                render: function (data, type, row) {
                    return `<a href="${data.href}" class="text-nowrap">${data.text}</a>`;
                },
            },
            {
                data: 'date_birth'
            },
            {
                data: 'address'
            },
            {
                data: 'phone'
            },
            {
                data: 'email'
            },
            {
                data: 'age'
            }
        ],
    });
});