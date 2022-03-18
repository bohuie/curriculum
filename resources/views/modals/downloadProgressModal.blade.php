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
                <p class="mb-2">Please stay on this page while we prepare your summary.</p>
                <div class="progress">
                    <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
                </div>
                <a id="save-file" hidden target="_blank" rel="noopener noreferrer"></a>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary abort" aria-label="Close">Cancel</button>
            </div>
        </div>
    </div>
</div>

<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
    <div id="errorToast" class="toast hide" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header " style="padding:1em;color:#842029;background-color:#f8d7da;border-color:#f5c2c7">
            <i class="bi bi-exclamation-circle-fill pr-2 text-danger"></i>            
            <strong class="me-auto">Error</strong>
            <button type="button" class="btn-close" onclick="toggleErrorToast()" aria-label="Close"></button>
        </div>
        <div class="toast-body alert-danger">
            @if (Request::is('courseWizard/*'))
                We were unable to the download the course summary for {{$course->course_code}} {{$course->course_num}}. 
                <div class="d-flex flex-row-reverse bd-highlight mt-2 pt-2">
                    <a href="mailto:ctl.helpdesk@ubc.ca?subject=UBC Curriculum MAP: Error Generating Course Summary&cc=matthew.penner@ubc.ca&body=There was an error downloading the course summary for {{$course->course_code}} {{$course->course_num}}">
                        <button type="button" class="btn btn-secondary btn-sm">Get Help</button>      
                    </a>  
                </div>        
            @endif
            @if (Request::is('programWizard/*'))
                We were unable to the download the program overview for {{$program->program}}. 
                <div class="d-flex flex-row-reverse bd-highlight mt-2 pt-2">
                    <a href="mailto:ctl.helpdesk@ubc.ca?subject=UBC Curriculum MAP: Error Generating Program Overview&cc=matthew.penner@ubc.ca&body=There was an error downloading the program overview for {{$program->program}}">
                        <button type="button" class="btn btn-secondary btn-sm">Get Help</button>      
                    </a>      
                </div>        
            @endif      
        </div>
    </div>
</div>

<script type="application/javascript">
    $(document).ready(function () {
        var xhr;
        $("#downloadPDF").click(function (e) {
            var route = $(this).data("route");
            xhr = $.ajax({
                type: "GET",
                url: route,
                dataType: "text",
                success: (data, textStatus, jqXHR) => {
                    // Set href as a local object URL
                    $('#save-file').attr('href', data);
                    // Set name of download
                    $('#save-file').attr('download', 'summary.pdf');
                    // trigger download
                    $("#save-file")[0].click();
                    // hide download modal
                    $('#downloadProgressModal').modal('hide');
                    // delete pdf summary after 1 min/60,000 ms
                    setTimeout(() => {deletePDF(route)}, 60000);
                },
                error: (jqXHR, textStatus, error) => {
                    // hide download modal
                    $('#downloadProgressModal').modal('hide');
                    if (textStatus != abort) {
                        // show error toast 
                        toggleErrorToast()                   
                    }
                },
            });   
        });

        $(".abort").click((e) => {
            if (xhr) {
                // abort XMLHttpRequest
                xhr.abort();
                // hide download modal
                $('#downloadProgressModal').modal('hide');
            }
        })
    });

    // toggle the show/hide class of the error toast
    function toggleErrorToast() {
        var errorToast = $("#errorToast");
        if (errorToast.hasClass("hide")) {
            errorToast.removeClass("hide");
            errorToast.addClass("show");
        } else {
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
