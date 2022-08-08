
<meta name="csrf-token" content="{{ csrf_token() }}">
                <!-- Select program content modal -->
                
                <div class="modal" id="confirmDownloadModal" tabindex="-1" aria-labelledby="confirmDownloadModalLabel" aria-hidden="true" data-bs-backdrop="static">
                    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered ">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="confirmDownloadModalLabel">Confirm Action before Downloading</h5>
                                
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    
                            </div> 
            
                            <div class="modal-body" style="height: auto;">
                                <div class="alert alert-info d-flex align-items-center ml-3 mr-3" role="alert" style="text-align:justify">
                                    <i class="bi bi-info-circle-fill pr-2 fs-1"></i>                        
                                        <div> Google Chrome and Mozilla Firefox are strongly recommended for this feature.</div>
                                        
                                </div>
                                This feature is not optimized for use with other web browsers and may perform unexpectedly. 
                                            
                            </div>
                            <div class="modal-footer">
                                <button style="width:60px" type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                                <button id="confirmDownloadBtn"  data-bs-route="{{route('programs.spreadsheet', $program->program_id)}}" class="btn btn-primary">Confirm and Download</button>
                            </div>

                        </div>
                    </div>
                </div> 
                <!-- End of select program content modal --> 
                <script type="application/javascript">
                    
                    
    $(document).ready(function () {
        var xhr;
        
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
