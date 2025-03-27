$(document).ready(function() {
    $("body").on("click", ".show-more", function(e) {
        e.preventDefault();
        if ($(".another-fields").hasClass("d-none")) {
            $(this).html("Show less fields");
            $(".another-fields").removeClass("d-none")
        } else {
            $(this).html("Show more fields");
            $(".another-fields").addClass("d-none")
        }
    })
})