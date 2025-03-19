$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
$(document).ready(function(e) {
    $("body").on("change", "#county", function(e) {
        e.preventDefault()
        $.ajax({
            type: 'GET',
            url: `${$("#county-search-url").val()}/${$(this).val()}`,
            success: function(data) {
                if (data.success) {
                    $("#state").val(data.county.state.name)
                    $("#region").val(data.county.region.name)
                }
            },
            error: function(data) {
                console.log("error");
                console.log(data);
            }
        });
    })
})