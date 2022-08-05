
<meta name="csrf-token" content="{{ csrf_token() }}">
                <!-- Select program content modal -->
                
                <div class="modal" id="setContentPDF" tabindex="-1" aria-labelledby="setContentPDFLabel" aria-hidden="true" data-bs-backdrop="static">
                    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered ">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="setContentPDFLabel">Select Content to Include in Program Summary</h5>
                                
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    
                              
                            </div>
            
                            <div class="modal-body" style="height: auto;">
                                <form name="setContentForm" id="setContentForm">
                                @csrf
                                <div class="form-group">
                                    <table class="table table-light table-bordered" style="margin: auto;"> 
                                        <thead>
                                            <tr class="table-primary">
                                                <th>Content</th>
                                                <th>Include</th>
                                            </tr>
                                        </thead>
                                        <tr>
                                            <td>
                                                Program Learning Outcomes
                                            </td>
                                            <td>
                                                <input value="1" class="form-check-input" checked="checked" type="checkbox" name="PLOs" id="PLOs" form ="setContentForm" style="margin-left: 38px">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Mapping Scales
                                            </td>
                                            <td>
                                                <input value="1" class="form-check-input" checked="checked" type="checkbox" name="mapping_scales" id="yes_mapping_scales" form ="setContentForm" style="margin-left: 38px">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Frequency Distribution Tables
                                            </td>
                                            <td>
                                                <input value="1" class="form-check-input" checked="checked" type="checkbox" name="freq_dist_tables" id="yes_freq_dist_tables" form ="setContentForm" style="margin-left: 38px">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                PLOs to CLOs Bar Chart
                                            </td>
                                            <td>
                                                <input value="1" class="form-check-input" checked="checked" type="checkbox" name="clos_bar" id="yes_clos_bar" form ="setContentForm" style="margin-left: 38px">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Assessment Methods Bar Chart
                                            </td>
                                            <td>
                                                <input value="1" class="form-check-input" checked="checked" type="checkbox" name="assessment_methods_bar" id="yes_assessment_methods_bar" form ="setContentForm" style="margin-left: 38px">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Learning Activities Bar Chart
                                            </td>
                                            <td>
                                                <input value="1" class="form-check-input" checked="checked" type="checkbox" name="learning_activities_bar" id="yes_learning_activities_bar" form ="setContentForm" style="margin-left: 38px">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Ministry Standards Bar Chart
                                            </td>
                                            <td>
                                                <input value="1" class="form-check-input" checked="checked" type="checkbox" name="ministry_stds_bar" id="yes_ministry_stds_bar" form ="setContentForm" style="margin-left: 38px">
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button style="width:60px" type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                                <button id="downloadPartialPDFBtn"  data-route="{{route('programs.pdf', $program->program_id)}}" type="submit" class="btn btn-primary">Submit</button>
                                <input value="1" name="formFilled" id="formFilled" form ="setContentForm"hidden>
                            </div>
                        </form>
                        </div>
                    </div>
                </div>
                <!-- End of select program content modal -->
                <script type="application/javascript">
                    
                    
    $(document).ready(function () {
        var xhr;
        
        $('#setContentForm').submit((e) => {
            e.preventDefault();
            e.stopPropagation();
            route = $("#downloadPartialPDFBtn").data("route");
            xhr = $.ajax({
            type: "GET",
            url: route,
            dataType: "text",
            data: $("#setContentForm").serializeArray(),
            headers: {
                'X-CSRF-Token': '{{ csrf_token() }}',
            },
            beforeSend: (jqXHR, settings) => {
                // show download modal
                $('#setContentPDF').modal('toggle');
                $('#downloadProgressModal').modal('show');
                
            },  
            success: (data, textStatus, jqXHR) => {
                
                $('#downloadProgressModal').modal('hide');
                // check if controller handled an error
                if (data == -1) 
                    showErrorToast()
                else {
                    // close error toast if open
                    hideErrorToast();
                    // Set href as a local object URL
                    $('#save-file').attr('href', data);
                    // trigger download
                    $("#save-file")[0].click();
                    // delete pdf summary after 15 sec/15,000 ms

                    //Getting error 
                    setTimeout(() => {deletePDF(route)}, 15000);
                }
            },
            error: (jqXHR, textStatus, error) => {
                // hide download modal
                $('#downloadProgressModal').modal('hide');
                if (textStatus != "abort") {
                    // show error toast 
                    showErrorToast();                
                }
            },
        });     
           
        });
        
    });

    

    function abort(event) {
        if (xhr) {
            // abort XMLHttpRequest
            xhr.abort();
            // hide download modal
            $('#downloadProgressModal').modal('hide');
        }
    }

    // show the error toast
    function showErrorToast() {
        var errorToast = $("#errorToast");
        if (errorToast.hasClass("hide")) {
            errorToast.removeClass("hide");
            errorToast.addClass("show");
        } 
    }

    // hide the error toast
    function hideErrorToast() {
        var errorToast = $("#errorToast");
        if (errorToast.hasClass("show")) {
            errorToast.removeClass("show");
            errorToast.addClass("hide");
        } 
    }


    function deletePDF(route) {
        var token = $("meta[name='csrf-token']").attr("content");
        $.ajax({
            type: "DELETE",
            url: route,
            headers: {
                'X-CSRF-Token': '{{ csrf_token() }}',
            },
            dataType: "text",
            success: (data, textStatus, jqXHR) => {
                console.log(data);
            },
            error: (jqXHR, textStatus, error) => {
                console.log(error);
            },
        }); 
    }
</script>
