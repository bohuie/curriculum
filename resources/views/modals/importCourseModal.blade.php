<div class="modal fade" id="importExistingCourse" tabindex="-1" role="dialog" aria-labelledby="importExistingCourse" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document" style="width:1250px;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importExistingCourse">Import an existing course</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>            
            </div>

            <div class="modal-body" style="height: auto;">
                <div class="alert alert-primary d-flex align-items-center" role="alert" style="text-align:justify">
                    <i class="bi bi-info-circle-fill pr-2 fs-3"></i>                        
                    <div>
                        Choose a course from your list of existing courses to import relevant course information.                    
                    </div>
                </div>

                <table class="table table-hover table-light">
                    <thead>
                        <tr class="table-primary">
                            <th class="w-auto" scope="col"></th>
                            <th class="w-50"scope="col">Course Title</th>
                            <th class="w-25" scope="col">Course Code</th>
                            <th class="w-25" scope="col">Semester</th>
                        </tr>
                    </thead>
                                    
                    @foreach ($myCourses as $index => $course)
                        <tbody>
                            <tr>
                                <th scope="row">
                                    @if (!empty($syllabus) && isset($syllabus->course_id))
                                        <input name="import_course_settings[courseId]" value={{$course->course_id}} class="form-check-input importCourseId" type="radio" form = "sylabusGenerator" style="margin-left: 0px" @if($course->course_id == $syllabus->course_id) checked @endif>
                                    @else 
                                        <input name="import_course_settings[courseId]" value={{$course->course_id}} class="form-check-input importCourseId" type="radio" form = "sylabusGenerator" style="margin-left: 0px" @if ($index == 0) checked @endif>
                                    @endif
                                </th>
                                <td>{{$course->course_title}}</td>
                                <td>{{$course->course_code}} {{$course->course_num}}</td>
                                <td>
                                    @if($course->semester == "W1")
                                        Winter {{$course->year}} Term 1
                                    @elseif ($course->semester == "W2")
                                        Winter {{$course->year}} Term 2
                                    @elseif ($course->semester == "S1")
                                        Summer {{$course->year}} Term 1
                                    @elseif ($course->semester == "S2")
                                        Summer {{$course->year}} Term 2
                                    @else
                                        Other {{$course->year}}
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    @endforeach
                </table>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary col-3" data-bs-dismiss="modal">Cancel</button>                
                <button type="button" class="btn btn-primary col-3" id="importSettingsBtn" name="importButton" data-bs-target="#importOptionsModal" data-bs-toggle="modal" data-bs-dismiss="modal" onclick="fillImportSettingsModal()">Next <i class="bi bi-arrow-right"></i></button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="importOptionsModal" aria-hidden="true" aria-labelledby="importOptionsModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalToggleLabel2">Import settings</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-primary d-flex align-items-center col-12 " role="alert">
                    <i class="bi bi-info-circle-fill pr-2 fs-3"></i>                        
                    <div>
                        Select the course components to import.                    
                    </div>
                </div>
                <form id="importCourseSettingsForm">
                    <div class="form-check m-2">
                        <input class="form-check-input" type="checkbox" checked value="" id="importLearningOutcomes" name="importLearningOutcomes" >
                        <label class="form-check-label" for="importLearningOutcomes">
                            Course Learning Outcomes
                        </label>
                    </div>

                    <div class="form-check m-2">
                        <input class="form-check-input" type="checkbox" checked value="" id="importAssessmentMethods" name="importAssessmentMethods">
                        <label class="form-check-label" for="importAssessmentMethods">
                            Assessment Methods
                        </label>
                    </div>

                    <div class="form-check m-2">
                        <input class="form-check-input" type="checkbox" checked value="" id="importLearningActivities" name="importLearningActivities">
                        <label class="form-check-label" for="importLearningActivities">
                            Teaching and Learning Activities
                        </label>
                    </div>

                    <div class="form-check m-2">
                        <input class="form-check-input" type="checkbox" checked value="" id="importCourseAlignment" name="importCourseAlignment">
                        <label class="form-check-label" for="importCourseAlignment">
                            Course Alignment Table
                        </label>
                    </div>

                    <div id="importProgramsSection"></div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary col-3" data-bs-target="#importExistingCourse" data-bs-toggle="modal" data-bs-dismiss="modal"><i class="bi bi-arrow-left"></i> Back</button>
                <button type="button" id="importButton" class="btn btn-primary col-3" data-bs-dismiss="modal"><i class="fw-bold bi bi-box-arrow-in-down-left"></i> Import</button>
            </div>
        </div>
    </div>
</div>

<script>
    function fillImportSettingsModal() {
        importProgramsSection = $('#importProgramsSection')
        importProgramsSection.empty();
        courseId = $('.importCourseId:checked').val();
        $.ajax({
            type: "GET",
            url: `/course/${courseId}/programs`,
            headers: {
                'X-CSRF-Token': '{{ csrf_token() }}',
            },
        }).done(function(data) {
            if (data.length > 0) {
                title = `
                    <div class="mt-3">
                        <b class="m-2">Course Alignment to Program</b>
                    </div>`;
                importProgramsSection.append(title);
                data.forEach(function(program) {
                    option = `
                        <div class="form-check m-2">
                            <input id="${program["program"]}" class="form-check-input" type="checkbox" checked value="${program["program"]}" name="${program["program_id"]}">
                            <label class="form-check-label" for="${program["program"]}">${program["program"]}</label>
                        </div>`;
                    importProgramsSection.append(option);
                });
            }
        });
    }
</script>