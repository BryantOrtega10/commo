$.ajaxSetup({
    headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
});
$(document).ready(function () {
    const status = $("#status").val();
    function getRows() {
        $.ajax({
            type: "GET",
            url: $("#url").val(),
            success: function (commissionUpload) {
                $("#processed_rows").html(commissionUpload.processed_rows)
                $("#error_rows").html(commissionUpload.error_rows)
                $("#percentageUploaded").css("width",`${commissionUpload.percentage_uploaded}%`)
                $("#percentageLinked").css("width",`${commissionUpload.percentage_linked}%`)
                $("#percentageError").css("width",`${commissionUpload.percentage_error}%`)
                if(commissionUpload.status > 2){
                    window.location.reload();
                }
                $('.datatable').DataTable().ajax.reload();
            },
            error: function (data) {
                console.log("error");
                console.log(data);
            },
        });
    }
    if(status == 2){
        getRows();
        setInterval(getRows, 1000);
    }
});
