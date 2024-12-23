<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="modal fade" id="downloadProgressModal" tabindex="-1" aria-labelledby="downloadProgressModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered ">
        <div class="modal-content">
            <div class="modal-header">
                @if (Request::is('courseWizard/*'))
                    <h5 class="modal-title" id="downloadProgressModalLabel">Downloading course summary for {{$course->course_code}} {{$course->course_num}} ...</h5>
                @endif
                @if (Request::is('programWizard/*'))
                    <h5 class="modal-title" id="downloadProgressModalLabel">Downloading program overview for {{$program->program}} ...</h5>
                @endif
            </div>  
            <div class="modal-body">
                <p class="mb-2">This may take up to 5 minutes.</p>
                <p class="mb-2">If this message does not go away automatically within 5 minutes or less, please close the window and check your Downloads folder.</p>
                <p class="mb-2">Please stay on this page while we prepare your summary.</p>
                <div class="progress">
                    <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
                </div>
                <a id="save-file" hidden download></a>
            </div>
            <div class="modal-footer">
                <button id="cancelDownloadBtn" type="button" class="btn btn-secondary" aria-label="Close">Cancel</button>
            </div>
        </div>
    </div>
</div>



<script type="application/javascript">
    $(document).ready(function () {
        var xhr;

        $("#downloadPDFBtn").click((e) =>
            downloadPDF(e.currentTarget)
        );
        $("#downloadDataBtn").click((e) =>
             downloadCourseExcel(e.currentTarget)
        );
        $("#confirmDownloadBtn").click((e) =>
            downloadExcel(e.currentTarget)
        );
        $("#cancelDownloadBtn").click((e) =>
            abort(e.currentTarget)
        );
        $("#dataConfirmDownloadBtn").click((e) =>
            downloadDataExcel(e.currentTarget)
        )
        $("#downloadUserGuideBtn").click((e) =>
            downloadUserGuide(e.currentTarget)
        )
        
    });

    // Course level pdf
    function downloadPDF(trigger) {
        var route = $(trigger).data("route");
        xhr = $.ajax({
            type: "GET",
            url: route,
            dataType: "text",
            beforeSend: (jqXHR, settings) => {
                // show download modal
                $('#downloadProgressModal').modal('show');
            },  
            success: (data, textStatus, jqXHR) => {
                // hide download modal
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
    }

    // Course level excel
    function downloadCourseExcel(trigger) {
        var route = $(trigger).data("route");
        xhr = $.ajax({
            type: "GET",
            url: route,
            dataType: "text",
            beforeSend: (jqXHR, settings) => {
                // show download modal
                $('#downloadProgressModal').modal('show');
            },  
            success: (data, textStatus, jqXHR) => {
                // hide download modal
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
    }
        

    // Program Level excel with charts
    function downloadExcel(trigger) {
        var route = "{{route('programs.spreadsheet', $program->program_id)}}";
        xhr = $.ajax({
            type: "GET",
            url: route,
            dataType: "text",
            beforeSend: (jqXHR, settings) => {
                // hide confirmation modal and show download modal
                $('#confirmDownloadModal').modal('hide');
                $('#downloadProgressModal').modal('show');
            },  
            success: (data, textStatus, jqXHR) => {
                // hide download modal
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
    }

    // Program Level Data Excel 
    function downloadDataExcel(trigger) {
        var route = "{{route('programs.dataSpreadsheet', $program->program_id)}}";
        xhr = $.ajax({
            type: "GET",
            url: route,
            dataType: "text",
            beforeSend: (jqXHR, settings) => {
                // hide confirmation modal and show download modal
                $('#dataConfirmDownloadModal').modal('hide');
                $('#downloadProgressModal').modal('show');
            },  
            success: (data, textStatus, jqXHR) => {
                // hide download modal
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
    }

        // Download User Guide
        function downloadUserGuide(trigger) {
        var route = "{{route('programs.downloadUserGuide', $program->program_id)}}";
        xhr = $.ajax({
            type: "GET",
            url: route,
            dataType: "text",
            beforeSend: (jqXHR, settings) => {
                // hide confirmation modal and show download modal
                $('#dataConfirmDownloadModal').modal('hide');
                $('#downloadProgressModal').modal('show');
            },  
            success: (data, textStatus, jqXHR) => {
                // hide download modal
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
    }

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
            data: {
                "_token": token,
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
