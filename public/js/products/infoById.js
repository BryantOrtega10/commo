$.ajaxSetup({
    headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
});
$(document).ready(function (e) {
    $("body").on("change", "#product", function (e) {
        e.preventDefault();
        $("#carrier").val("");
        $("#plan_type").val("");
        $("#product_type").val("");
        $("#tier").val("");
        $("#business_segment").val("");
        $("#business_type").val("");
        $.ajax({
            type: "GET",
            url: `${$("#url_product_desc").val()}/${$(this).val()}`,
            success: function (data) {
                $("#carrier").val(data.carrier);
                $("#plan_type").val(data.plan_type);
                $("#product_type").val(data.product_type);
                $("#tier").val(data.tier);
                $("#business_segment").val(data.business_segment);
                $("#business_type").val(data.business_type);
            },
            error: function (data) {
                console.log("error");
                console.log(data);
            },
        });
    });
});
