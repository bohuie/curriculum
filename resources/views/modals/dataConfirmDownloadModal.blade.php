
<meta name="csrf-token" content="{{ csrf_token() }}">
                <!-- Select program content modal -->
                
                <div class="modal" id="dataConfirmDownloadModal" tabindex="-1" aria-labelledby="dataConfirmDownloadModalLabel" aria-hidden="true" data-bs-backdrop="static">
                    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered ">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="dataConfirmDownloadModalLabel">Confirm Action before Downloading</h5>
                                
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    
                            </div> 
            
                            <div class="modal-body" style="height: auto;">
                                The new data import feature enables users to seamlessly export raw datasets, providing a flexible foundation for in-depth analysis and insights. Please checkout our User Guide for more information which can be downloaded here:
                                <br>
                                </n>
                                <div style="text-align: center;"> 
                                <div><button id="downloadUserGuideBtn"  data-bs-route="{{route('programs.downloadUserGuide', $program->program_id)}}" class="btn btn-primary btn-sm">Download User Guide</button></div>
                                </div>
                            </div>
                            
                            <div class="modal-footer">
                            
                                <button style="width:60px" type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    
                                <button id="dataConfirmDownloadBtn"  data-bs-route="{{route('programs.dataSpreadsheet', $program->program_id)}}" class="btn btn-primary">Confirm and Download</button>
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
