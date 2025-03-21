$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
$(document).ready(function(e) {
    $("body").on("click", ".add-cuid", function(e) {
        e.preventDefault()
        $("#addNewCUID").remove();
        $.ajax({
            type: 'GET',
            url: $(this).attr("href"),
            success: function(data) {
                $("body").append(data);
                $("#addNewCUID").modal("show");
            },
            error: function(data) {
                console.log("error");
                console.log(data);
            }
        });
    })

    $("body").on("click", ".edit-cuid", function(e) {
        e.preventDefault()
        $("#editCUID").remove();
        $.ajax({
            type: 'GET',
            url: $(this).attr("href"),
            success: function(data) {
                $("body").append(data);
                $("#editCUID").modal("show");
            },
            error: function(data) {
                console.log("error");
                console.log(data);
            }
        });
    })
})

