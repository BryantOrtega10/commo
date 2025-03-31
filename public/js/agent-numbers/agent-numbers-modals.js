$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
$(document).ready(function(e) {
    $("body").on("click", ".add-agent-number", function(e) {
        e.preventDefault()
        $("#addNewAgentNumberModal").remove();
        $.ajax({
            type: 'GET',
            url: $(this).attr("href"),
            success: function(data) {
                $("body").append(data);
                $("#addNewAgentNumberModal").modal("show");
            },
            error: function(data) {
                console.log("error");
                console.log(data);
            }
        });
    })

    $("body").on("click", ".edit-agent-number", function(e) {
        e.preventDefault()
        $("#editAgentNumberModal").remove();
        $.ajax({
            type: 'GET',
            url: $(this).attr("href"),
            success: function(data) {
                $("body").append(data);
                $("#editAgentNumberModal").modal("show");
            },
            error: function(data) {
                console.log("error");
                console.log(data);
            }
        });
    })
})

