$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
$(document).ready(function(e) {


    $("body").on("click", ".activityModal", function(e) {
        e.preventDefault()
        $("#activityModal").remove();
        $.ajax({
            type: 'GET',
            url: $(this).attr("href"),
            success: function(data) {
                $("body").append(data);
                new Quill('#description', {
                    theme: 'snow'
                });
                
                $("#activityModal").modal("show");
            },
            error: function(data) {
                console.log("error");
                console.log(data);
            }
        });
    })

    $("body").on("submit", ".form-activity", function(e) {
        const editor = Quill.find(document.querySelector('#description'));
        $("#html_desc").val(editor.root.innerHTML);
        $("#text_desc").val(editor.getText());
        

    })

    $("body").on("change", "#create_task", function(e) {
        if($(this).is(":checked")){
            $(".for-task").removeClass("d-none")
        }
        else{
            $(".for-task").addClass("d-none")
        }
    });
 
    

})

