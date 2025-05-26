$.ajaxSetup({
    headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
});
$(document).ready(function () {
    $("body").on("click", ".open-statements-modal", function (e) {
        e.preventDefault()
        $("#showStatementsModal").remove();
        $.ajax({
            type: 'GET',
            url: $(this).attr("href"),
            success: function(data) {
                $("body").append(data);
                $("#showStatementsModal").modal("show");
            },
            error: function(data) {
                console.log("error");
                console.log(data);
            }
        });
    });


    $("body").on("click", ".btn-edit", function (e) {
        e.preventDefault()
        $("#showEditModal").remove();
        $.ajax({
            type: 'GET',
            url: $(this).attr("href"),
            success: function(data) {
                $("body").append(data);
                $("#showEditModal").modal("show");
            },
            error: function(data) {
                console.log("error");
                console.log(data);
            }
        });
    });
});
