$.ajaxSetup({
    headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
});
$(document).ready(function () {
    $("body").on("click", ".edit-email-template", function (e) {
        e.preventDefault();
        $("#showEmailTemplateModal").remove();
        $.ajax({
            type: "GET",
            url: $(this).attr("href"),
            success: function (data) {
                $("body").append(data);
                const quill = new Quill("#description", {
                    theme: "snow",
                });
                quill.clipboard.dangerouslyPasteHTML($("#html_desc").val());
                $("#showEmailTemplateModal").modal("show");
            },
            error: function (data) {
                console.log("error");
                console.log(data);
            },
        });
    });

    $("body").on("submit", ".form-email", function (e) {
        const editor = Quill.find(document.querySelector("#description"));
        $("#html_desc").val(editor.root.innerHTML);
        $("#text_desc").val(editor.getText());
    });

    $("body").on("click", ".add-agent", function (e) {
        e.preventDefault();
        if ($("#agent").val() != "") {
            const $selectedOption = $("#agent option:selected");
            const text = $selectedOption.text();
            const id = $selectedOption.val();

            $selectedOption.remove();

            $(".selected-agents")
                .append(`<div class="col-md-3 col-12 selected-agent-item">
                                            <input type="hidden" name="selectedAgent[]" value="${id}" />
                                            <span>${text}</span>
                                            <a href="#" class="remove-agent text-danger" data-id="${id}" data-text="${text}" ><i class="fas fa-trash-alt"></i></a>
                                          </div>`);
        }
    });
    $("body").on("click", ".remove-agent", function (e) {
        e.preventDefault();
        const id = $(this).data("id");
        const text = $(this).data("text");
        $("#agent").append(
            $("<option>", {
                value: id,
                text: text,
            })
        );
        $(this).closest(".selected-agent-item").remove();
    });

    $("body").on("click", ".btn-generate-individual", function (e) {
        if ($("input[name='selectedAgent[]']")[0] == undefined) {
            e.preventDefault();
            alertSwal("Select at least one agent number");
        } else {
            $("#agent-report-processes-form").prop(
                "action",
                $("#urlIndividual").val()
            );
            $("#agent-report-processes-form").trigger("submit");
        }
    });

    $("body").on("click", ".btn-generate-batch", function (e) {
        $("#agent-report-processes-form").prop("action", $("#urlBatch").val());
        $("#agent-report-processes-form").trigger("submit");
    });

    $("body").on("click", ".btn-email-individual", function (e) {
        if ($("input[name='selectedAgent[]']")[0] == undefined) {
            e.preventDefault();
            alertSwal("Select at least one agent number");
        } else {
            $("#agent-report-processes-form").prop(
                "action",
                $("#urlEmailIndividual").val()
            );
            $("#agent-report-processes-form").trigger("submit");
        }
    });

    $("body").on("click", ".btn-email-batch", function (e) {
        $("#agent-report-processes-form").prop(
            "action",
            $("#urlEmailBatch").val()
        );
        $("#agent-report-processes-form").trigger("submit");
    });

    $("#agent-report-processes-form").submit(function (e) {
        if ($("#statement_date").val() == "") {
            e.preventDefault();
            alertSwal("Statement date is required");
        }
    });
});
