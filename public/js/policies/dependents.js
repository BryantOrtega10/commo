$(document).ready(function () {
    $("body").on("click", ".add-new-dependent", function(e){
        e.preventDefault();
        const relationships = JSON.parse($("#relationships_json").val());
        let relationships_txt = "";
        relationships.forEach(relationship => {
            relationships_txt += `<option value="${relationship.id}">${relationship.name}</option>`;
        });

        $(".dependents-cont").append(`
            <div class="row align-items-end dependent-item">
                            <div class="col-md-6 col-6">
                                <h5 class="dependent-title">Dependent #${$(".dependent-item").length + 1}</h5>
                            </div>
                            <div class="col-md-6 col-6 text-right">
                                <button type="button" class="btn btn-outline-danger remove-dependent">Remove</button>
                            </div>
                            <div class="col-md-3 col-12">
                                <div class="form-group">
                                    <label for="dependent_first_name_${$(".dependent-item").length}" class="lb-first_name">First Name:</label>
                                    <input type="text" class="form-control txt-first_name" id="dependent_first_name_${$(".dependent-item").length}" name="dependent_first_name[]" >                                </div>
                            </div>
                            <div class="col-md-3 col-12">
                                <div class="form-group">
                                    <label for="dependent_last_name_${$(".dependent-item").length}" class="lb-last_name">Last Name:</label>
                                    <input type="text" class="form-control txt-last_name" id="dependent_last_name_${$(".dependent-item").length}" name="dependent_last_name[]" >                                    
                                </div>
                            </div>
                            <div class="col-md-2 col-12">
                                <div class="form-group">
                                    <label for="dependent_date_birth_${$(".dependent-item").length}" class="lb-date_birth">Date of Birth:</label>
                                    <input type="date" class="form-control txt-date_birth" id="dependent_date_birth_${$(".dependent-item").length}" name="dependent_date_birth[]" >
                                   
                                </div>
                            </div>
                            <div class="col-md-2 col-12">
                                <div class="form-group">
                                    <label for="dependent_relationship_${$(".dependent-item").length}" class="lb-relationship">Relationship To Applicant:</label>
                                    <select id="dependent_relationship_${$(".dependent-item").length}" name="dependent_relationship[]"
                                        class="form-control txt-relationship">
                                        <option value=""></option>
                                        ${relationships_txt}
                                    </select>                                    
                                </div>
                            </div>
                            <div class="col-md-2 col-12">
                                <div class="form-group">
                                    <label for="dependent_date_add_${$(".dependent-item").length}" class="lb-date_add">Date Added To Policy:</label>
                                    <input type="date" class="form-control txt-date_add" id="dependent_date_add_${$(".dependent-item").length}" name="dependent_date_add[]">                                   
                                </div>
                            </div>
                        </div>`);
    });

    $("body").on("click", ".remove-dependent", function(e){
        $(this).closest(".dependent-item").remove();
        $(".dependent-item").each(function(i, dependentItem) {
            $(dependentItem).find(".dependent-title").html(`Dependent #${i + 1}`);

            $(dependentItem).find(".lb-first_name").prop("for", `dependent_first_name_${i}`);
            $(dependentItem).find(".txt-first_name").prop("id", `dependent_first_name_${i}`);

            $(dependentItem).find(".lb-last_name").prop("for", `dependent_last_name_${i}`);
            $(dependentItem).find(".txt-last_name").prop("id", `dependent_last_name_${i}`);

            $(dependentItem).find(".lb-date_birth").prop("for", `dependent_date_birth_${i}`);
            $(dependentItem).find(".txt-date_birth").prop("id", `dependent_date_birth_${i}`);

            $(dependentItem).find(".lb-relationship").prop("for", `dependent_relationship_${i}`);
            $(dependentItem).find(".txt-relationship").prop("id", `dependent_relationship_${i}`);

            $(dependentItem).find(".lb-date_add").prop("for", `dependent_date_add_${i}`);
            $(dependentItem).find(".txt-date_add").prop("id", `dependent_date_add_${i}`);
        });
   
    });
});