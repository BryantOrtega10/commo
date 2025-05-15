$.ajaxSetup({
    headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
});
$(document).ready(function () {

    let arrRows = {}

    $("body").on("click", ".add-commision-rate", function (e) {
        if ($(".fields-row").length > 0) {
            $(".cancel-row").trigger("click");
        }
        e.preventDefault();
        $.ajax({
            type: "GET",
            url: `${$(this).prop("href")}`,
            success: function (data) {
                if ($(".fields-row").length == 0) {
                    $("#commision-rates-table tbody").prepend(data);
                    
                }
            },
            error: function (data) {
                console.log("error");
                console.log(data);
            },
        });
    });

    $("body").on("click", ".cancel-row", function (e) {
      
        const dataId = $(this).data("id");
        if(dataId != undefined){
            const updateRow = $(this).closest(".fields-row");
            $(updateRow).replaceWith(arrRows[dataId]);
            $(updateRow).removeClass("fields-row");
        }
        else{
            $(this).closest(".fields-row").remove();
        }
        $('#commision-rates-table').scrollLeft($('#commision-rates-table')[0].scrollWidth);
        
    });

    $("body").on("click", ".save-row", function (e) {
        e.preventDefault();
        $("#form-commission-rates").prop("action", $(this).data("url"));
        $("#form-commission-rates").trigger("submit");
    });

    $("body").on("click", ".commission-rate-order-up", function (e) {
        e.preventDefault();
        const dataId = $(this).data("id");
        const oldOrder = parseInt($(`.order[data-id='${dataId}']`).val());
        const newOrder = oldOrder - 1;
        if (newOrder >= 0) {
            const row1 = $(this).closest("tr").prev();
            const row2 = $(this).closest("tr");
            $(row1).find(".order").val(oldOrder);
            $(row2).find(".order").val(newOrder);

            const tempRow = $(row2)[0].outerHTML;
            $(row2).replaceWith($(row1)[0].outerHTML);
            $(row1).replaceWith(tempRow);
            $('#commision-rates-table').scrollLeft($('#commision-rates-table')[0].scrollWidth);
        }
    });

    $("body").on("click", ".commission-rate-order-down", function (e) {
        e.preventDefault();
        const dataId = $(this).data("id");
        const oldOrder = parseInt($(`.order[data-id='${dataId}']`).val());
        const newOrder = oldOrder + 1;
        let maximo = $("#commision-rates-table tbody tr").length;
        if ($(".add-new-row").length > 0) {
            maximo = maximo - 1;
        }

        if (maximo > newOrder) {
            const row1 = $(this).closest("tr");
            const row2 = $(this).closest("tr").next();
            $(row1).find(".order").val(newOrder);
            $(row2).find(".order").val(oldOrder);

            const tempRow = $(row2)[0].outerHTML;
            $(row2).replaceWith($(row1)[0].outerHTML);
            $(row1).replaceWith(tempRow);
            $('#commision-rates-table').scrollLeft($('#commision-rates-table')[0].scrollWidth);
        }
    });

    $("body").on("click", ".update-row", function (e) {
        e.preventDefault();
        const hiddenRow = $(this).closest("tr");
        const dataId = $(this).data("id");
        if ($(".fields-row").length > 0) {
            $(".cancel-row").trigger("click");
        }

        $.ajax({
            type: "GET",
            url: `${$(this).prop("href")}`,
            success: function (data) {
                arrRows[dataId] = $(hiddenRow)[0].outerHTML;
                $(hiddenRow).replaceWith(data);
                $('#commision-rates-table').scrollLeft($('#commision-rates-table')[0].scrollWidth);

            },
            error: function (data) {
                console.log("error");
                console.log(data);
            },
        });
    });


});
