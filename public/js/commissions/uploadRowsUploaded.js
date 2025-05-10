$.ajaxSetup({
    headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
});
$(document).ready(function () {
    function getRows() {
        $.ajax({
            type: "GET",
            url: $("#url").val(),
            success: function (commissionUpload) {
                $(".uploaded-rows").html(commissionUpload.uploaded_rows);
                if(commissionUpload.status == 1){
                    window.location.reload();
                }
            },
            error: function (data) {
                console.log("error");
                console.log(data);
            },
        });
    }
    getRows();
    setInterval(getRows, 1000);
});
