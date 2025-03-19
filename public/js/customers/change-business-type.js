$(document).ready(function (e) {

    $("body").on("change", "#business_type", function (e) {
        if($(this).val() == "1"){
            $(".for-individual").removeClass("d-none");
            $(".first-group-name-row").removeClass("col-md-6");
            $(".first-group-name-row").addClass("col-md-3");
            
            $("#first-group-name-label").html("First Name (*):");
            $("#first_name").prop("placeholder","First Name (*):");

            $("#middle-group-label").html("Middle Initial:");
            $("#middle_initial").prop("placeholder","Middle Initial:");

        }
        else{
            $(".for-individual").addClass("d-none");
            $(".first-group-name-row").removeClass("col-md-3");
            $(".first-group-name-row").addClass("col-md-6");

            $("#first-group-name-label").html("Group Name (*):");
            $("#first_name").prop("placeholder","Group Name (*):");

            $("#middle-group-label").html("Group Contact:");
            $("#middle_initial").prop("placeholder","Group Contact:");

            
            
        }
    });

});
