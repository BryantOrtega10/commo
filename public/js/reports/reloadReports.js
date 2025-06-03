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
            success: function (report) {
                $("#total_rows").html(report.total)
                $("#generated_rows").html(report.generated)
                $("#percentageTotal").css("width",`${report.percentageTotal}%`)
                $("#percentageGenerated").css("width",`${report.percentageGenerated}%`)
                if(report.status > 0){
                    window.location.reload();
                }
            },
            error: function (data) {
                console.log("error");
                console.log(data);
            },
        });
    }
    if(status == 0){
        getRows();
        setInterval(getRows, 1000);
    }
});
