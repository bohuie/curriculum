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
                                    <input value = {{$course->course_id}} class="form-check-input" type="radio" name="importCourse" id="importCourse" form = "sylabusGenerator" style="margin-left: 0px">
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
                <button type="button" class="btn btn-primary col-3" id="importButton" name="importButton" data-bs-dismiss="modal"><i class="fw-bold bi bi-box-arrow-in-down-left"></i> Import</button>
            </div>
        </div>
    </div>
</div>