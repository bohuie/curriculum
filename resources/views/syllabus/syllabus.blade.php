@extends('layouts.app')

@section('content')

@include('modals.importCourseModal', ['myCourses' => $myCourses, 'syllabus' => $syllabus])

@include('modals.courseSchedule')

<style>
    .inputFieldDescription {
        font-size: 13px;
        text-align: justify;
        line-height: 2;
        color: #6c757d;
    }
</style>

<div class="m-auto" style="max-width:860px;height:100%;">

    <div class="m-3">
        <h3 class="text-center lh-lg fw-bold mt-4">Syllabus Generator</h3>

        <div class="text-center row justify-content-center">
            <div class="col-2" style="max-width:10%">
                <button type="submit" form="sylabusGenerator" class="btn m-0 p-0" style="background:none;border:none" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Save my syllabus">
                    <i class="btn btn-light rounded-circle m-2 text-secondary bi bi-clipboard2-check-fill"></i>
                    <p style="font-size:12px" class="text-muted m-0">SAVE</p>
                </button>
            </div>
            <!-- PDF Download Disabled while we investigate escaping issues -->
            <!--
            <div class="col-2" style="max-width:10%">
                <button type="submit" name="download" value="pdf" form="sylabusGenerator"
                    class="btn m-0 p-0" style="background:none;border:none" data-bs-toggle="tooltip" data-bs-placement="bottom"
                    title="Save and download my syllabus as PDF">
                    <i class="text-danger bi-file-pdf-fill btn btn-light rounded-circle m-2"></i>
                    <p style="font-size:12px" class="text-muted m-0">PDF</p>
                </button>
            </div>
            -->
            <div class="col-2" style="max-width:10%">
                <button type="submit" name="download" value="word" form="sylabusGenerator"
                    class="btn m-0 p-0" style="background:none;border:none" data-bs-toggle="tooltip" data-bs-placement="bottom"
                    title="Save and Download syllabus as Word"><i
                        class="bi-file-earmark-word-fill text-primary btn btn-light rounded-circle m-2"></i></i>
                    <p style="font-size:12px" class="text-muted m-0">WORD</p>
                </button>
            </div>
            <div class="col-2" style="max-width:10%">
                <span data-bs-toggle="modal" data-bs-target="#importExistingCourse">
                    <button type="button" class="btn m-0 p-0" data-bs-toggle="tooltip"
                        data-bs-placement="bottom" title="Import an existing course" style="background:none;border:none"><i
                            class="text-secondary bi bi-box-arrow-in-down-left btn btn-light rounded-circle m-2"></i>
                            <p style="font-size:12px" class="text-muted m-0">IMPORT</p>
                        </button>
                </span>
            </div>
    
            @if (!empty($syllabus))
                @include('modals.syllabusCollabsModal', ['syllabus' => $syllabus, 'user' => $user])
                <div class="col-2" style="max-width:10%">
                    <span data-bs-toggle="modal" data-bs-target="#addSyllabusCollaboratorsModal{{$syllabus->id}}">
                        <button type="button" class="btn m-0 p-0" style="background:none;border:none" data-bs-toggle="tooltip"
                            data-bs-placement="bottom" title="Add collaborators to my syllabus"><i
                                class="text-primary bi bi-people-fill btn btn-light rounded-circle m-2"></i>
                            <p style="font-size:12px" class="text-muted m-0">PEOPLE</p>
                        </button>
                    </span>
                </div>
        
                @include('modals.duplicateModal', ['syllabus' => $syllabus])
                <div class="col-2" style="max-width:10%">
                    <span data-bs-toggle="modal" data-bs-target="#duplicateConfirmation">
                        <button type="button" class="btn m-0 p-0" style="background:none;border:none" data-bs-toggle="tooltip"
                            data-bs-placement="bottom" title="Make a copy of my syllabus"><i
                                class="btn btn-light rounded-circle m-2 text-success bi bi-files"></i>
                                <p style="font-size:12px" class="text-muted m-0">COPY</p>
                        </button>
                    </span>
                </div>
        
                @include('modals.deleteModal', ['syllabus' => $syllabus])
                <div class="col-2" style="max-width:10%">
                    <span data-bs-toggle="modal" data-bs-target="#deleteSyllabusConfirmation">
                        <button type="button" class="btn m-0 p-0" style="background:none;border:none" data-bs-toggle="tooltip"
                            data-bs-placement="bottom" title="Delete my syllabus"><i class="btn btn-danger rounded-circle m-2 bi bi-trash-fill"></i>
                            <p style="font-size:12px" class="text-muted m-0">DELETE</p>
                        </button>
                    </span>
                </div>
            @endif
        </div>
    </div>
    
    
    
    <div class="alert alert-primary d-flex align-items-center" role="alert" style="text-align:justify">
        <i class="bi bi-info-circle-fill pr-2 fs-3"></i>                        
        <div>
            To assist faculty and instructors in preparing their syllabi, this generator follows the policies and templates provided by the <a target="_blank" rel="noopener noreferrer" href="https://senate.ubc.ca/okanagan/policies/policy-o-130-content-and-distribution-of-course-syllabi-2/">UBC Okanagan</a> and <a target="_blank" rel="noopener noreferrer" href="https://senate.ubc.ca/policies-resources-support-student-success">UBC Vancouver</a> Senates. 
        </div>
    </div>

    @if(empty($syllabus))
        <div class="alert alert-warning">
            <!-- <i class="bi bi-info-circle-fill pr-2 fs-3"></i> -->
            <button type="button" class="close" data-dismiss="alert">×</button>  
            <div>
                {!! $inputFieldDescriptions['saveWarning']!!}
            </div>  
        </div>
    @endif


    <form class="row gy-4 courseInfo needs-validation" novalidate method="POST" id="sylabusGenerator" action="{{!empty($syllabus) ? action([\App\Http\Controllers\SyllabusController::class, 'save'], $syllabus->id) : action([\App\Http\Controllers\SyllabusController::class, 'save'])}}">
        @csrf

        <h5 class="fw-bold col-12 mt-5">Course Information</h5>

        <div class="col-6">
            <label for="courseTitle" class="form-label">Course Title<span class="requiredField"> *</span><span class="requiredBySenateOK"></span></label>
            <input oninput="validateMaxlength()" onpaste="validateMaxlength()" maxlength="100" spellcheck="true" id = "courseTitle" name = "courseTitle" class ="form-control" type="text" placeholder="E.g. Intro to Software development" required value="{{ !empty($syllabus) ? $syllabus->course_title : '' }}">
            <div class="invalid-tooltip">
                Please enter the course title.
            </div>
        </div>

        <div class="col-3">
            <label for="courseCode">Course Code<span class="requiredField"> *</span></label>
            <input id = "courseCode" pattern="[A-Za-z]+" minlength="1" name = "courseCode" oninput="validateMaxlength()" onpaste="validateMaxlength()" maxlength="4" class ="form-control" type="text" placeholder="E.g. CPSC" required value="{{ !empty($syllabus) ? $syllabus->course_code : '' }}">
            <div class="invalid-tooltip">
                Please enter the course code.
            </div>
        </div>
        
        <div class="col-3">
            <label for="courseNumber">Course Number<span class="requiredField"> *</span></label>
            <input id = "courseNumber" name = "courseNumber" oninput="validateMaxlength()" onpaste="validateMaxlength()" maxlength="3" class ="form-control" type="number" placeholder="E.g. 310" value="{{ !empty($syllabus) ? $syllabus->course_num : '' }}">
            <div class="invalid-tooltip">
                Please enter the course number.
            </div>
        </div>

        
        
               <!--
             <input class="form-check-input " id="crossListed" type="checkbox" name="crossListed" value="1" checked>
                    <div id="crossListedCode" class="col-3">
                        <label for="courseCodeCL">Cross-Listed Course Code<span class="requiredField"></span></label>
                        <input id = "courseCodeCL" pattern="[A-Za-z]+" minlength="1" name = "courseCodeCL" oninput="validateMaxlength()" onpaste="validateMaxlength()" maxlength="4" class ="form-control" type="text" placeholder="E.g. CPSC" required value="{{ !empty($syllabus) ? $syllabus->cross_listed_code : '' }}">
                        <div class="invalid-tooltip">
                            Please enter the course code.
                        </div>
                    </div>
                    <div id="crossListedNumber" class="col-3">
                        <label for="courseNumberCL">Cross-Listed Course Number<span class="requiredField"></span></label>
                        <input id = "courseNumberCL" name = "courseNumberCL" oninput="validateMaxlength()" onpaste="validateMaxlength()" maxlength="3" class ="form-control" type="number" placeholder="E.g. 310" value="{{ !empty($syllabus) ? $syllabus->cross_listed_num : '' }}">
                        <div class="invalid-tooltip">
                            Please enter the course number.
                        </div>
                    </div>
            -->
        
            
                @if(!empty($syllabus))
                    @if($syllabus->cross_listed_code && $syllabus->cross_listed_num)
                <div class="col-6">
                    <div class="col-12">
                        <input class="form-check-input " id="crossListed" type="checkbox" name="crossListed" value="1" checked>
                        <label class="form-check-label mb-2" for="crossListed">{!! $inputFieldDescriptions['crossListed'] !!}</label>
                    </div>
                </div>
                    <div id="crossListedCode" class="col-3">
                        <label for="courseCodeCL">Cross-Listed Course Code<span class="requiredField"></span></label>
                        <input id = "courseCodeCL" pattern="[A-Za-z]+" minlength="1" name = "courseCodeCL" oninput="validateMaxlength()" onpaste="validateMaxlength()" maxlength="4" class ="form-control" type="text" placeholder="E.g. CPSC" required value="{{ !empty($syllabus) ? $syllabus->cross_listed_code : '' }}">
                        <div class="invalid-tooltip">
                            Please enter the course code.
                        </div>
                    </div>
                    <div id="crossListedNumber" class="col-3">
                        <label for="courseNumberCL">Cross-Listed Course Number<span class="requiredField"></span></label>
                        <input id = "courseNumberCL" name = "courseNumberCL" oninput="validateMaxlength()" onpaste="validateMaxlength()" maxlength="3" class ="form-control" type="number" placeholder="E.g. 310" value="{{ !empty($syllabus) ? $syllabus->cross_listed_num : '' }}">
                        <div class="invalid-tooltip">
                            Please enter the course number.
                        </div>
                    </div>
                    @else
                <div class="col-6">
                    <div class="col-12">
                        <input class="form-check-input " id="crossListed" type="checkbox" name="crossListed" value="1">
                        <label class="form-check-label mb-2" for="crossListed">{!! $inputFieldDescriptions['crossListed'] !!}</label>
                    </div>
                </div>
                <div id="crossListedCode" class="col-3"></div>
                <div id="crossListedNumber" class="col-3"></div>
                    @endif 
                @else
                <div class="col-6">
                    <div class="col-12">
                    <input class="form-check-input " id="crossListed" type="checkbox" name="crossListed" value="1">
                    <label class="form-check-label mb-2" for="crossListed">{!! $inputFieldDescriptions['crossListed'] !!}</label> 
                    </div>
                </div>
                <div id="crossListedCode" class="col-3"></div>
            <div id="crossListedNumber" class="col-3"></div>
                @endif
    
        
        
        


        


        <div class="col-3">
            <label for="campus" class="form-label">Campus<span class="requiredField"> *</span></label>
            <select class="form-select" id="campus" name="campus" form="sylabusGenerator" required>
                <option selected value="" class="text-muted" disabled hidden> -- Campus -- </option>
                <option value="O">UBC Okanagan</option>
                <option value="V">UBC Vancouver</option>
            </select>
        </div>
        
        <div class="col-3">
            <label for="faculty" class="form-label">Faculty</label>
            <select class="form-select" id="faculty" name="faculty" form="sylabusGenerator" disabled onchange="setDepartments(this.selectedOptions[0].getAttribute('name'))">
                <option value="" class="text-muted"> -- Faculty -- </option>
            </select>
        </div>

        <div class="col-3">
            <label for="department" class="form-label">Department</label>
            <select class="form-select" id="department" name="department" form="sylabusGenerator" disabled>
                <option value="" class="text-muted"> -- Department -- </option>
            </select>
        </div>

        <div id="courseCredit" class="col-3"></div>

        <div class="col-3">
            <label for="startTime">Class Start Time</label>
            <input oninput="validateMaxlength()" onpaste="validateMaxlength()" maxlength="20" id = "startTime" name = "startTime" class ="form-control" type="text" placeholder="E.g. 1:00 PM" value="{{ !empty($syllabus) ? $syllabus->class_start_time : ''}}">
        </div>
        
        <div class="col-3">
            <label for="endTime">Class End Time</label>
            <input oninput="validateMaxlength()" onpaste="validateMaxlength()" maxlength="20" id = "endTime" name = "endTime" class ="form-control" type="text" placeholder="E.g. 2:00 PM" value="{{ !empty($syllabus) ? $syllabus->class_end_time : ''}}" >
        </div>

        <div class="col-3">
            <label for="courseLocation">Course Location</label>
            <input id = "courseLocation" name = "courseLocation" oninput="validateMaxlength()" onpaste="validateMaxlength()" maxlength="150" class ="form-control" type="text" placeholder="E.g. WEL 140" value="{{ !empty($syllabus) ? $syllabus->course_location : ''}}">
        </div>
        
        <div id="officeLocation" class="col-3"></div>

                <!-- Okanagan Course Section -->
                <div class="col-3"id="courseSectionOK"></div>
                
        <div class="col-3">
            <label for="courseYear">Course Year <span class="requiredField">*</span></label>
            <select id="courseYear" class="form-select" name="courseYear" required>
                <option disabled selected value="">  -- Year -- </option>
                <option value="2021" {{!empty($syllabus) ? (($syllabus->course_year == '2021') ? 'selected=true' : '') : ''}}>2021</option>
                <option value="2022" {{!empty($syllabus) ? (($syllabus->course_year == '2022') ? 'selected=true' : '') : ''}}>2022</option>
                <option value="2023" {{!empty($syllabus) ? (($syllabus->course_year == '2023') ? 'selected=true' : '') : ''}}>2023</option>
                <option value="2024" {{!empty($syllabus) ? (($syllabus->course_year == '2024') ? 'selected=true' : '') : ''}}>2024</option>
                <option value="2025" {{!empty($syllabus) ? (($syllabus->course_year == '2025') ? 'selected=true' : '') : ''}}>2025</option>
                <option value="2026" {{!empty($syllabus) ? (($syllabus->course_year == '2026') ? 'selected=true' : '') : ''}}>2026</option>
                <option value="2027" {{!empty($syllabus) ? (($syllabus->course_year == '2027') ? 'selected=true' : '') : ''}}>2027</option>
                <option value="2028" {{!empty($syllabus) ? (($syllabus->course_year == '2028') ? 'selected=true' : '') : ''}}>2028</option>
                <option value="2029" {{!empty($syllabus) ? (($syllabus->course_year == '2029') ? 'selected=true' : '') : ''}}>2029</option>
                <option value="2030" {{!empty($syllabus) ? (($syllabus->course_year == '2030') ? 'selected=true' : '') : ''}}>2030</option>
            </select>
            <div class="invalid-tooltip">
                Please enter the course year.
            </div>
        </div>

        <div class="col-3">
            <label for="courseSemester" class="form-label">Course Term <span class="requiredField">*</span></label>
            <select id="courseSemester" class="form-select" name="courseSemester" required>
                <option disabled selected value=""> -- Term --</option>
                <option value="W1" {{!empty($syllabus) ? (($syllabus->course_term == 'W1') ? 'selected=true' : '') : ''}}>Winter Term 1</option>
                <option value="W2" {{!empty($syllabus) ? (($syllabus->course_term == 'W2') ? 'selected=true' : '') : ''}}>Winter Term 2</option>
                <option value="S1" {{!empty($syllabus) ? (($syllabus->course_term == 'S1') ? 'selected=true' : '') : ''}}>Summer Term 1</option>
                <option value="S2" {{!empty($syllabus) ? (($syllabus->course_term == 'S2') ? 'selected=true' : '') : ''}}>Summer Term 2</option>
                <option value="O" {{!empty($syllabus) ? (($syllabus->course_term != 'W1' && $syllabus->course_term != 'W2' && $syllabus->course_term != 'S1' && $syllabus->course_term != 'S2') ? 'selected=true' : '') : ''}}>Other</option>
            </select>
            <div class="invalid-tooltip">
                Please enter the course term.
            </div>
        </div>

        
        <div id="courseSemesterOther" class="col-3">
            @if (!empty($syllabus))
                @if ($syllabus->course_term != 'W1' && $syllabus->course_term != 'W2' && $syllabus->course_term != 'S1' && $syllabus->course_term != 'S2')
                    <label class="form-label" for="courseSemesterOther">Other</label>
                    <input name="courseSemesterOther" type="text" class="form-control" oninput="validateMaxlength()" onpaste="validateMaxlength()" maxlength="49" value="{{$syllabus->course_term}}">
                @endif
            @endif
        </div>


        <div class="col-3">
            <label for="deliveryModality">Mode of Delivery<span class="requiredField"> *</span></label>
            <select id="deliveryModality" class="form-select" name="deliveryModality" required>
                <option value="O" {{!empty($syllabus) ? (($syllabus->delivery_modality == 'O') ? 'selected=true' : '') : ''}}>Online</option>
                <option value="I" {{!empty($syllabus) ? (($syllabus->delivery_modality == 'I') ? 'selected=true' : '') : ''}}>In-person</option>
                <option value="B" {{!empty($syllabus) ? (($syllabus->delivery_modality == 'B') ? 'selected=true' : '') : ''}}>Hybrid</option>
                <option value="M" {{!empty($syllabus) ? (($syllabus->delivery_modality == 'M') ? 'selected=true' : '') : ''}}>Multi-Access</option>
            </select>
            <div class="invalid-tooltip">
                Please enter the course mode of delivery.
            </div>
        </div>

        <div class="col-9">
            <label for="classDate">Class Meeting Days</label>
            <div class="classDate mt-1">
                <div class="form-check form-check-inline">
                    <input id="monday" type="checkbox" name="schedule[]" value="Mon" class="form-check-input">
                    <label for="monday" class="mr-2 form-check-label" >Monday</label>
                </div>
                
                <div class="form-check form-check-inline">
                    <input id="tuesday" type="checkbox" name="schedule[]" value="Tue" class="form-check-input">
                    <label for="tuesday" class="mr-2 form-check-label">Tuesday</label>
                </div>

                <div class="form-check form-check-inline">
                    <input id="wednesday" type="checkbox" name="schedule[]" value="Wed" class="form-check-input">
                    <label for="wednesday" class="mr-2 form-check-label">Wednesday</label>
                </div>

                <div class="form-check form-check-inline">
                    <input id="thursday" type="checkbox" name="schedule[]" value= "Thu" class="form-check-input">
                    <label for="thursday" class="mr-2 form-check-label">Thursday</label>
                </div>

                <div class="form-check form-check-inline">
                    <input id="friday" type="checkbox" name="schedule[]" value="Fri" class="form-check-input">
                    <label for="friday" class="mr-2 form-check-label">Friday</label>
                </div>
                
                <div class="form-check form-check-inline">
                    <input id="saturday" type="checkbox" name="schedule[]" value="Sat" class="form-check-input">
                    <label for="saturday" class="mr-2 form-check-label">Saturday</label>
                </div>
            </div>
        </div>

        <div class="col-6">
            <label for="endTime">Prerequisites</label><span class="requiredBySenateOK"></span>
            <input oninput="validateMaxlength()" onpaste="validateMaxlength()" maxlength="100" spellcheck="true" id = "prerequisites" name = "prerequisites" class ="form-control" type="text" value="{{ !empty($syllabus) ? $syllabus->prerequisites : ''}}" >
        </div>
        <div class="col-6">
            <label for="endTime">Corequisites</label><span class="requiredBySenateOK"></span>
            <input oninput="validateMaxlength()" onpaste="validateMaxlength()" maxlength="100" spellcheck="true" id = "corequisites" name = "corequisites" class ="form-control" type="text" value="{{ !empty($syllabus) ? $syllabus->corequisites : ''}}" >
        </div>
        
        <!-- Land Acknowledgement Statement -->
        <div class="col-12" id="landAcknowledgement"></div>
        
        <div class="col-12">
            <label for="officeHour"> <h5 class="fw-bold">Office Hours</h5></label><span class="requiredBySenateOK"></span>
            <i class="bi bi-info-circle-fill" data-bs-toggle="tooltip" data-bs-placement="right" title="{{$inputFieldDescriptions['officeHours']}}"></i>
            <textarea oninput="validateMaxlength()" onpaste="validateMaxlength()" maxlength="2500" spellcheck="true" id = "officeHour" name = "officeHour" class ="form-control" type="date" form="sylabusGenerator">{{ !empty($syllabus) ? $syllabus->office_hours : ''}}</textarea>
        </div>
        <!-- Course Description Vancouver -->
        <div class="col-12"id="courseDescription"></div>
        <!-- Course Prerequisites -->
        <div class="col-12" id="coursePrereqs"></div>
        <!-- Course Corequisites -->
        <div class="col-12" id="courseCoreqs"></div>
        <div class="col-3"><label for="courseInstructors"><h5 class="fw-bold">Course Instructor(s)</h5></label></div><div class="col-9"><span class="requiredBySenateOK"></span></div>
@if (!empty($syllabus) && $syllabusInstructors->count() > 0)
    @foreach ($syllabusInstructors as $syllabusInstructor)
        <div class="instructor row g-3 align-items-end m-0 p-0">
            <div class="col-5">
                <label for="courseInstructor">Name<span class="requiredField"> *</span></label>
                <input name = "courseInstructor[]" oninput="validateMaxlength()" onpaste="validateMaxlength()" maxlength="75" class ="form-control" type="text" placeholder="E.g. Dr. J. Doe" required value="{{$syllabusInstructor->name}}">
                <div class="invalid-tooltip">
                Please enter the course instructor.
                </div>
            </div>

            <div class="col-5">
                <label for="courseInstructorEmail">Email<span class="requiredField"> *</span></label>
                <input name = "courseInstructorEmail[]" oninput="validateMaxlength()" onpaste="validateMaxlength()" maxlength="75" class ="form-control" type="email" placeholder="E.g. jane.doe@ubc.ca" value="{{$syllabusInstructor->email}}" required>
                <div class="invalid-tooltip">
                    Please enter the instructors email.
                </div>
            </div>

            <div class="col-2">
                <button type="button" class="btn btn-danger col" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Delete instructor" onclick="delInstructor(this)"><i class="bi bi-trash-fill"></i> Delete</button>
            </div>
        </div>
    @endforeach 
@else 
    <div class="instructor row g-3 align-items-end m-0 p-0">
        <div class="col-5">
            <label for="courseInstructor">Name<span class="requiredField"> *</span></label>
            <input name = "courseInstructor[]" oninput="validateMaxlength()" onpaste="validateMaxlength()" maxlength="75" class ="form-control" type="text" placeholder="E.g. Dr. J. Doe" required >
            <div class="invalid-tooltip">
            Please enter the course instructor.
            </div>
        </div>

        <div class="col-5">
            <label for="courseInstructorEmail">Email<span class="requiredField"> *</span></label>
            <input name = "courseInstructorEmail[]" oninput="validateMaxlength()" onpaste="validateMaxlength()" maxlength="75" class ="form-control" type="email" placeholder="E.g. jane.doe@ubc.ca" required>
            <div class="invalid-tooltip">
                Please enter the instructors email.
            </div>
        </div>

        <div class="col-2">
            <button type="button" class="btn btn-danger col" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Delete instructor" onclick="delInstructor(this)">Delete</button>
        </div>
    </div>
@endif

<a id="addInstructorBtn" class="link-primary col-2" onclick="addInstructor()" style="cursor:pointer"><i class="bi bi-plus"></i> Add instructor</a>

        <!-- Course Contacts -->
        <div id="courseContacts" class="col-12"></div>
        <!-- Course Instructor Biographical Statement -->
        <div class="col-12" id="courseInstructorBio"></div>

        <div class="col-12">
            <label for="otherCourseStaff"><h5 class="fw-bold">Other Instructional Staff</h5></label>
            <span class="requiredBySenate"></span>
            <i class="bi bi-info-circle-fill" data-bs-toggle="tooltip" data-bs-placement="right" title="{{$inputFieldDescriptions['otherCourseStaff']}}"></i>
            <div id="formatStaff" class="collapsibleNotes btn-primary rounded-3"
                style="overflow:hidden;transition:height 0.3s ease-out;height:auto" data-collapsed="false">
                <i class="bi bi-exclamation-triangle-fill fs-5 pl-2 pr-2 pb-1"></i> <span class="fs-6">Place each entry
                    on a new line for the best formatting
                    results.</span>
            </div>
            <textarea oninput="validateMaxlength()" onpaste="validateMaxlength()" maxlength="1000" id="otherCourseStaff"
                data-formatnoteid="formatStaff"
                placeholder="E.g. Professor, Dr. Phil, PhD Clinical Psychology, ...&#10;E.g. Instructor, Bill Nye, BS Mechanical Engineering, ..."
                name="otherCourseStaff" class="form-control " form="sylabusGenerator"
                spellcheck="true" style="height:125px;">{{ !empty($syllabus) ? $syllabus->other_instructional_staff : ''}}</textarea>
        </div>

        <div class="alert alert-info">
        <i class="bi bi-info-circle-fill pr-2 fs-3"></i>  
            <button type="button" class="close" data-dismiss="alert">×</button>
                <a target="_blank" rel="noopener noreferrer" href="https://ctlt-inclusiveteaching.sites.olt.ubc.ca/files/2019/08/inclusive-syllabus-digital.pdf">Guidelines to write an inclusive syllabus</a>.
        </div>

        <!-- Okanaga Course Description -->
        <div class="col-12"id="courseDesc"></div>
        <!-- Course Format -->
        <div class="col-12" id="courseFormat"></div>
        <!-- Course Overview -->
        <div class="col-12" id="courseOverview"></div>
        
        <!-- Course Structure -->
        <div class="col-12" id="courseStructure"></div>

        <!-- course schedule table -->
        <div class="col mb-3">
            <label for="courseSchedule">
                <table>
                    <tr>
                        <td><div><h5 class="fw-bold">Schedule of Topics and Assessments</h5></td>
                        <td><span class="requiredBySenateOK"></span></td>
                        <td><i class="bi bi-info-circle-fill" data-bs-toggle="tooltip" data-bs-placement="right" title="{{$inputFieldDescriptions['courseSchedule']}}"></i></td>
                    </tr>
                </table>
            </label>
            <br>
            <span data-bs-toggle="tooltip" data-bs-placement="top" title="Create Course Schedule Table">
                <button @if (!empty($syllabus)) @if ($courseScheduleTblRowsCount> 0) hidden @endif @endif id="createTableBtn" type="button" class="btn btn-light rounded-pill m-2" data-bs-toggle="modal" data-bs-target="#createCourseScheduleTblModal" style="font-color:#002145">
                    <i class="bi bi-plus"></i>
                    <span class="iconify-inline" data-icon="fluent:table-48-filled"></span>
                </button>
            </span>

        </div>

        <!-- course schedule toolbar -->
        <div id="courseScheduleTblToolbar" class="row mb-1" @if (!empty($syllabus)) @if ($courseScheduleTblRowsCount <= 0) hidden @endif @else hidden @endif>
            <div class="col-12 text-center">
                <span title="Row Limit Reached!" data-bs-trigger="manual" data-bs-toggle="popover"
                    data-bs-placement="bottom" data-bs-content="You have reached the maximum number of rows allowed">
                    <button type="button" class="col-2 addRow btn m-0 p-0" style="background:none;border:none" data-side="top" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Add row on top">
                        <i class="btn btn-light rounded-pill m-2 text-secondary bi bi-plus">
                            <span class="iconify-inline ml-1" data-icon="clarity:view-columns-line" data-rotate="270deg"></span>
                        </i>
                        <p style="font-size:12px" class="text-muted m-0">ADD ROW TOP</p>
                    </button>

                    <button type="button" class="col-2 addRow btn m-0 p-0" style="background:none;border:none" data-side="bottom" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Add row on bottom">
                        <i class="btn btn-light rounded-pill m-2 text-secondary bi bi-plus">
                            <span class="iconify-inline ml-1" data-icon="clarity:view-columns-line" data-rotate="90deg"></span>
                        </i>
                        <p style="font-size:12px" class="text-muted m-0">ADD ROW BOTTOM</p>
                    </button>                    
                </span>

                <span title="Column Limit Reached!" data-bs-trigger="manual" data-bs-toggle="popover"
                    data-bs-placement="bottom" data-bs-content="You have reached the maximum number of columns allowed">
                    <button type="button" class="col-2 addCol btn m-0 p-0" style="background:none;border:none" data-side="left" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Add column on left">
                        <i class="btn btn-light rounded-pill m-2 text-secondary bi bi-plus">
                            <span class="iconify-inline ml-1" data-icon="clarity:view-columns-line" data-rotate="180deg"></span>
                        </i>
                        <p style="font-size:12px" class="text-muted m-0">ADD COLUMN LEFT</p>
                    </button>  

                    <button type="button" class="col-2 addCol btn m-0 p-0" style="background:none;border:none" data-side="right" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Add column on left">
                        <i class="btn btn-light rounded-pill m-2 text-secondary bi bi-plus">
                            <span class="iconify-inline ml-1" data-icon="clarity:view-columns-line"></span>
                        </i>
                        <p style="font-size:12px" class="text-muted m-0">ADD COLUMN RIGHT</p>
                    </button>  
                </span>

                <button id="delCols" type="button" class="col-2 btn m-0 p-0" style="background:none;border:none" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Delete column(s)">
                    <i class="btn btn-light rounded-pill m-2 text-danger bi bi-trash-fill">
                        <span class="iconify-inline ml-1" data-icon="fluent:column-triple-20-filled"></span>
                    </i>
                    <p style="font-size:12px" class="text-muted m-0">DELETE COLUMN(S)</p>
                </button>

                
                <span data-bs-toggle="tooltip" data-bs-placement="bottom" title="Delete Table" class="col-2">
                    <button id="delTable" type="button" class="btn m-0 p-0" style="background:none;border:none" data-bs-toggle="modal" data-bs-target="#delCourseScheduleTbl">
                        <i class="btn btn-danger rounded-pill m-2 bi bi-trash-fill">
                            <span class="iconify-inline" data-icon="fluent:table-48-filled"></span>
                        </i>
                        <p style="font-size:12px" class="text-muted m-0">DELETE TABLE</p>
                    </button>                    
                </span>
            </div>
        </div>

        <!-- div where course schedule table is created from scratch  -->
        <div id="courseScheduleTblDiv">
            @if (!empty($syllabus))
                @if ($courseScheduleTblRowsCount > 0)
                    <table id="courseScheduleTbl" class="table table-light align-middle reorder-tbl-rows">
                        <thead>
                            <tr class="table-primary">
                                <th></th>
                                @foreach ($myCourseScheduleTbl['rows'][0] as $headerIndex => $header)
                                <th>
                                    <textarea name="courseScheduleTblHeadings[]" form="sylabusGenerator" type="text"
                                        class="form-control" spellcheck="true"
                                        placeholder="Column heading here ...">{{$header->val}}</textarea>
                                </th>
                                @endforeach
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($myCourseScheduleTbl['rows'] as $rowIndex => $row)
                            @if ($rowIndex != 0)
                            <tr>
                                <td class="align-middle fs-5">↕</td>
                                @foreach ($row as $colIndex => $data)
                                <td>
                                    <textarea name="courseScheduleTblRows[]" form="sylabusGenerator" type="text"
                                        class="form-control" spellcheck="true"
                                        placeholder="Data here ...">{{$data->val}}</textarea>
                                </td>
                                @endforeach
                                <td class="align-middle">
                                    <i class="bi bi-x-circle-fill text-danger fs-4 btn"
                                        onclick="delCourseScheduleRow(this)"></i>
                                </td>
                            </tr>
                            @endif
                            @endforeach
                        </tbody>
                    </table>
                @endif
            @endif
        </div>

        <div class="col-12">
            <label for="learningOutcome"><h5 class="fw-bold">Learning Outcomes or Objectives</h5></label><span class="requiredBySenateOK"></span>
            <span class="requiredBySenate"></span>
            <i class="bi bi-info-circle-fill" data-bs-toggle="tooltip" data-bs-placement="right" title="{{$inputFieldDescriptions['learningOutcomes']}}"></i>
            <p class="inputFieldDescription"><i>Upon successful completion of this course, students will be able to ...</i></p>
            <div id="formatCLOs" class="collapsibleNotes btn-primary rounded-3" style="overflow:hidden;transition:height 0.3s ease-out;height:auto" data-collapsed="false">
                <i class="bi bi-exclamation-triangle-fill fs-5 pl-2 pr-2 pb-1"></i> <span class="fs-6">Place each entry on a new line for the best formatting results.</span>                                        
            </div>                                            
            <textarea oninput="validateMaxlength()" onpaste="validateMaxlength()" maxlength="17500" id = "learningOutcome" data-formatnoteid="formatCLOs" placeholder="E.g. Define ... &#10;E.g. Classify ..." name = "learningOutcome" class ="form-control" type="date" style="height:125px;" form="sylabusGenerator" spellcheck="true">{{ !empty($syllabus) ? $syllabus->learning_outcomes : ''}}</textarea>
        </div>
        
        <div class="col-12">
            <label for="learningActivities"><h5 class="fw-bold">Learning Activities</h5></label><span class="requiredBySenateOK"></span>
            <span class="requiredBySenate"></span>
            <i class="bi bi-info-circle-fill" data-bs-toggle="tooltip" data-bs-placement="right" title="{{$inputFieldDescriptions['learningActivities']}}"></i>
            <div id="formatActivities" class="collapsibleNotes btn-primary rounded-3" style="overflow:hidden;transition:height 0.3s ease-out;height:auto" data-collapsed="false">
                <i class="bi bi-exclamation-triangle-fill fs-5 pl-2 pr-2 pb-1"></i> <span class="fs-6">Place each entry on a new line for the best formatting results.</span>                                        
            </div>                                            
            <textarea oninput="validateMaxlength()" onpaste="validateMaxlength()" maxlength="52431" id = "learningActivities" data-formatnoteid="formatActivities" placeholder="E.g. Class participation consists of clicker questions, group discussions ... &#10;E.g. Students are expected to complete class pre-readings ..."name = "learningActivities" class ="form-control" type="date" style="height:125px;" form="sylabusGenerator" spellcheck="true">{{ !empty($syllabus) ? $syllabus->learning_activities : ''}}</textarea>
        </div>
        <!-- Course Learning Materials -->
        <div class="col-12">
            <label for="learningMaterials"><h5 class="fw-bold">Learning Materials</h5></label><span class="requiredBySenateOK"></span>
            <span class="requiredBySenate"></span>
            <p class="inputFieldDescription">{!! $inputFieldDescriptions['learningMaterials'] !!}</p>
            <div id="formatLM" class="collapsibleNotes btn-primary rounded-3" style="overflow:hidden;transition:height 0.3s ease-out;height:auto" data-collapsed="false">
                <i class="bi bi-exclamation-triangle-fill fs-5 pl-2 pr-2 pb-1"></i> <span class="fs-6">Place each entry
                    on a new line for the best formatting
                    results.</span>
                </div>
            <textarea data-formatnoteid="formatLM" style="height:125px" oninput="validateMaxlength()" onpaste="validateMaxlength()" maxlength="10000"
                id="learningMaterials" name="learningMaterials" class="form-control" type="date" form="sylabusGenerator"
                spellcheck="true">{{ !empty($syllabus) ? $syllabus->learning_materials : ''}}</textarea>
        </div>
        
    
        <div class="col-12">
            <label for="learningAssessments"><h5 class="fw-bold">Methods of Assessment</h5></label><span class="requiredBySenateOK"></span>
            <span class="requiredBySenate"></span>
            <i class="bi bi-info-circle-fill" data-bs-toggle="tooltip" data-bs-placement="right" title="{{$inputFieldDescriptions['learningAssessments']}}"></i>
            <div id="formatAssessments" class="collapsibleNotes btn-primary rounded-3" style="overflow:hidden;transition:height 0.3s ease-out;height:auto" data-collapsed="false">
                <i class="bi bi-exclamation-triangle-fill fs-5 pl-2 pr-2 pb-1"></i> <span class="fs-6">Place each entry on a new line for the best formatting results.</span>                                        
            </div>                                            
            <textarea oninput="validateMaxlength()" onpaste="validateMaxlength()" maxlength="10000" id = "learningAssessments" data-formatnoteid="formatAssessments" placeholder="E.g. Presentation, 25%, Dec 1, ... &#10;E.g. Midterm Exam, 25%, Sept 31, ..." name = "learningAssessments" class ="form-control" type="date" style="height:125px;" form="sylabusGenerator" spellcheck="true">{{ !empty($syllabus) ? $syllabus->learning_assessments : ''}}</textarea>
        </div>

       

        <!-- Course Structure -->
        <div class="col-12" id="courseStructure"></div>
        


        @if (isset($courseAlignment))
            <div class="p-0 m-0" id="courseAlignment"> 
                <h5 class="fw-bold pt-4 mb-2 col-12 pt-4 mb-4 mt-2">
                    Course Alignment                                     
                    <button id="removeCourseAlignment" type="button" class="btn btn-danger float-right" onclick="removeSection(this)">Remove Section</button>
                    <input hidden name="import_course_settings[courseId]" value="{{$syllabus->course_id}}">
                    <input hidden name="import_course_settings[importCourseAlignment]" value="{{$syllabus->course_id}}">
                    

                </h5>            
                <div class="col-12" id="courseAlignmentTable">
                    <table class="table table-light table-bordered table " >
                        <thead>
                            <tr class="table-primary">
                                <th class="w-50">Course Learning Outcome</th>
                                <th>Student Assessment Method</th>
                                <th>Teaching and Learning Activity</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($courseAlignment as $clo)
                                <tr>
                                    <td scope="row">
                                        <b>{{$clo->clo_shortphrase}}</b><br>
                                        {{$clo->l_outcome}}
                                    </td>
                                    <td>{{$clo->assessmentMethods->implode('a_method', ', ')}}</td>
                                    <td>{{$clo->learningActivities->implode('l_activity', ', ')}}</td>
                                </tr>   
                            @endforeach                 
                        </tbody>
                    </table>
                </div>
            </div>
        @else 
            <div class="p-0 m-0" id="courseAlignment"></div>    
        @endif

        @if (isset($outcomeMaps))
            <div class="p-0 m-0" id="outcomeMapsDiv">  
                @foreach ($outcomeMaps as $programId => $outcomeMap)
                    <div class="p-0 m-0" id="outcomeMapsDiv"> 
                        <h5 class="fw-bold pt-4 mb-2 col-12 pt-4 mb-4 mt-2">
                            {{$outcomeMap["program"]->program}}                 
                            <button type="button" class="btn btn-danger float-right" onclick="removeSection(this)">Remove Section</button>
                            <input hidden name="import_course_settings[programs][]" value="{{$programId}}">
                        </h5>  

                        @if ($outcomeMap['program']->mappingScaleLevels->count() < 1)
                            <div class="col-12">
                                <div class="alert alert-warning wizard">
                                    <i class="bi bi-exclamation-circle-fill"></i>A mapping scale has not been set for this program.                  
                                </div>
                            </div>
                        @else 
                            <div class="col-12">
                                <table class="table table-bordered table-light">
                                    <thead>
                                        <tr class="table-primary">
                                            <th colspan="2">Mapping Scale</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($outcomeMap['program']->mappingScaleLevels as $mappingScale)
                                            <tr>
                                                <td>
                                                <div style="background-color:{{$mappingScale->colour}};height: 10px; width: 10px;"></div>
                                                    {{$mappingScale->title}}<br>
                                                    ({{$mappingScale->abbreviation}})
                                                </td>
                                                <td>
                                                    {{$mappingScale->description}}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif

                        @if (isset($outcomeMap['outcomeMap']) > 0)
                            <div class="col-12">
                                <div style="overflow: auto;">
                                    <table class="table table-bordered table-light">
                                        <thead>
                                            <tr class="table-primary">
                                                <th colspan="1" class="w-auto">CLO</th>
                                                <th colspan="{{$outcomeMap['program']->programLearningOutcomes->count()}}">Program Learning Outcome</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <th></th>
                                                @foreach ($outcomeMap['program']->ploCategories as $category)
                                                    @if ($category->plos->count() > 0)
                                                        <th class="table-active w-auto" colspan="{{$category->plos->count()}}" style="min-width:5%; white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{$category->plo_category}}</th>  
                                                    @endif          
                                                @endforeach
                                                @if ($outcomeMap['program']->programLearningOutcomes->where('plo_category_id', null)->count() > 0)
                                                    <th class="table-active w-auto text-center" colspan="{{$outcomeMap['program']->programLearningOutcomes->where('plo_category_id', null)->count()}}" style="min-width:5%; white-space:nowrap;overflow:hidden;text-overflow:ellipsis">Uncategorized PLOs</th>
                                                @endif
                                            </tr> 
                                            <tr>
                                                <td></td>
                                                @foreach ($outcomeMap['program']->ploCategories as $category)
                                                    @if ($category->plos->count() > 0)
                                                        @foreach ($category->plos as $plo)
                                                            <td style="height:0; text-align: left;">
                                                                @if ($plo->plo_shortphrase)
                                                                    {{$plo->plo_shortphrase}}
                                                                @else 
                                                                    {{$plo->pl_outcome}}
                                                                @endif
                                                            </td>
                                                        @endforeach
                                                    @endif
                                                @endforeach
                                                @if ($outcomeMap['program']->programLearningOutcomes->where('plo_category_id', null)->count() > 0)
                                                    @foreach ($outcomeMap['program']->programLearningOutcomes->where('plo_category_id', null) as $uncategorizedPLO)
                                                        <td style="height:0; text-align: left;">
                                                            @if ($uncategorizedPLO->plo_shortphrase)
                                                                {{$uncategorizedPLO->plo_shortphrase}}
                                                            @else 
                                                                {{$uncategorizedPLO->pl_outcome}}
                                                            @endif
                                                        </td>
                                                    @endforeach
                                                @endif
                                            </tr>
                                            @foreach ($outcomeMap['clos'] as $clo) 
                                                <tr>
                                                    <td class="w-auto"> 
                                                        @if (isset($clo->clo_shortphrase))
                                                            {{$clo->clo_shortphrase}}
                                                        @else 
                                                            {{$clo->l_outcome}}
                                                        @endif
                                                    </td>
                                                    @foreach ($outcomeMap['program']->ploCategories as $category)
                                                        @if ($category->plos->count() > 0)
                                                            @foreach ($category->plos as $plo)
                                                                @if (!array_key_exists($plo->pl_outcome_id, $outcomeMap['outcomeMap']))
                                                                    <td></td>
                                                                @else 
                                                                    @if (!array_key_exists($clo->l_outcome_id, $outcomeMap['outcomeMap'][$plo->pl_outcome_id]))
                                                                        <td></td>
                                                                    @else 
                                                                        <td class="text-center align-middle" style="background-color:{{$outcomeMap['outcomeMap'][$plo->pl_outcome_id][$clo->l_outcome_id]->colour}}">{{$outcomeMap['outcomeMap'][$plo->pl_outcome_id][$clo->l_outcome_id]->abbreviation}}</td>
                                                                    @endif
                                                                @endif
                                                            @endforeach
                                                        @endif
                                                    @endforeach
                                                    @if ($outcomeMap['program']->programLearningOutcomes->where('plo_category_id', null)->count() > 0)
                                                        @foreach ($outcomeMap['program']->programLearningOutcomes->where('plo_category_id', null) as $uncategorizedPLO)
                                                            @if (!array_key_exists($uncategorizedPLO->pl_outcome_id, $outcomeMap['outcomeMap']))
                                                                <td></td>
                                                            @else 
                                                                @if (!array_key_exists($clo->l_outcome_id, $outcomeMap['outcomeMap'][$uncategorizedPLO->pl_outcome_id]))
                                                                    <td></td>
                                                                @else 
                                                                    <td class="text-center align-middle" style="background-color:{{$outcomeMap['outcomeMap'][$uncategorizedPLO->pl_outcome_id][$clo->l_outcome_id]->colour}}">{{$outcomeMap['outcomeMap'][$uncategorizedPLO->pl_outcome_id][$clo->l_outcome_id]->abbreviation}}</td>
                                                                @endif
                                                            @endif
                                                        @endforeach
                                                    @endif                                
                                                </tr>
                                            @endforeach 
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @else 
                            <div class="col-12">
                                <div class="alert alert-warning wizard">
                                    <i class="bi bi-exclamation-circle-fill"></i>Course learning outcomes have not been mapped to program learning outcomes for this program.                 
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>  
        @else 
            <div class="p-0 m-0" id="outcomeMapsDiv"></div>    
        @endif

        <!-- learning analytics and learning resources OK -->
        <div class="col-12">
                    <!-- Course Learning Resources -->
                    <label for="learningResources"><h5 class="fw-bold">Learning Resources</h5></label>
            <span class="requiredBySenate"></span>
            <i class="bi bi-info-circle-fill" data-bs-toggle="tooltip" data-bs-placement="right" title="{{$inputFieldDescriptions['learningResources']}}"></i>
            <div id="formatLR" class="collapsibleNotes btn-primary rounded-3" style="overflow:hidden;transition:height 0.3s ease-out;height:auto" data-collapsed="false">
                <i class="bi bi-exclamation-triangle-fill fs-5 pl-2 pr-2 pb-1"></i> <span class="fs-6">Place each entry
                    on a new line for the best formatting
                    results.</span>
                </div> 
            <textarea data-formatnoteid="formatLR" style="height:125px" oninput="validateMaxlength()" onpaste="validateMaxlength()" maxlength="30000"
                id="learningResources" name="learningResources" class="form-control" form="sylabusGenerator"
                spellcheck="true">{{ !empty($syllabus) ? $syllabus->learning_resources : ''}}</textarea>
        </div>

        <!-- University Policies -->
        <div class="col-12" id="uniPolicy"></div>
        <br>
        <div>
        <h5 class="fw-bold">Other Course Policies</h5></label>
            <br>
            <!-- Late Policy -->
            <div class="col-12">
                <label for="latePolicy"><h7 class="fw-bold">Late Policy</h7></label>
                <i class="bi bi-info-circle-fill" data-bs-toggle="tooltip" data-bs-placement="right" title="{{$inputFieldDescriptions['latePolicy']}}"></i>
                <div id="formatLP" class="collapsibleNotes btn-primary rounded-3" style="overflow:hidden;transition:height 0.3s ease-out;height:auto" data-collapsed="false">
                    <i class="bi bi-exclamation-triangle-fill fs-5 pl-2 pr-2 pb-1"></i> <span class="fs-6">Place each entry
                        on a new line for the best formatting
                        results.</span>
                    </div>
                <textarea data-formatnoteid="formatLP" style="height:125px" oninput="validateMaxlength()" onpaste="validateMaxlength()" maxlength="10000" id="latePolicy"
                    name="latePolicy" class="form-control" type="date" form="sylabusGenerator"
                    spellcheck="true">{{ !empty($syllabus) ? $syllabus->late_policy : ''}}</textarea>
            </div>
            <br>
            <!-- Course Missing Exam -->
            <div class="col-12">
                <label for="missingExam"><h7 class="fw-bold">Missed Exam Policy</h7></label>
                <div id="formatME" class="collapsibleNotes btn-primary rounded-3" style="overflow:hidden;transition:height 0.3s ease-out;height:auto" data-collapsed="false">
                    <i class="bi bi-exclamation-triangle-fill fs-5 pl-2 pr-2 pb-1"></i> <span class="fs-6">Place each entry
                        on a new line for the best formatting
                        results.</span>
                    </div> 
                <textarea data-formatnoteid="formatME" style="height:125px" oninput="validateMaxlength()" onpaste="validateMaxlength()" maxlength="10000" id="missingExam"
                    name="missingExam" class="form-control" type="date" form="sylabusGenerator"
                    spellcheck="true">{{ !empty($syllabus) ? $syllabus->missed_exam_policy : ''}}</textarea>
            </div>
            <br>
            <!-- Course Missed Activity Policy -->
            <div class="col-12">
                <label for="missingActivity"><h7 class="fw-bold">Missed Activity Policy</h7></label><span class="requiredBySenateOK"></span>
                <p class="inputFieldDescription">{!! $inputFieldDescriptions['missedActivityPolicy'] !!}</p>
                <div id="formatMAP" class="collapsibleNotes btn-primary rounded-3" style="overflow:hidden;transition:height 0.3s ease-out;height:auto" data-collapsed="false">
                    <i class="bi bi-exclamation-triangle-fill fs-5 pl-2 pr-2 pb-1"></i> <span class="fs-6">Place each entry
                        on a new line for the best formatting
                        results.</span>
                    </div>
                <textarea data-formatnoteid="formatMAP" style="height:125px" oninput="validateMaxlength()" onpaste="validateMaxlength()" maxlength="10000" id="missingActivity"
                    name="missingActivity" class="form-control" type="date" form="sylabusGenerator"
                    spellcheck="true">{{ !empty($syllabus) ? $syllabus->missed_activity_policy : ''}}</textarea>
            </div>
            <br>
            <!-- Course Passing Criteria -->
            <div class="col-12">
                <label for="passingCriteria"><h7 class="fw-bold">Passing/Grading Criteria</h7></label>
                <div id="formatPC" class="collapsibleNotes btn-primary rounded-3" style="overflow:hidden;transition:height 0.3s ease-out;height:auto" data-collapsed="false">
                    <i class="bi bi-exclamation-triangle-fill fs-5 pl-2 pr-2 pb-1"></i> <span class="fs-6">Place each entry
                        on a new line for the best formatting
                        results.</span>
                    </div>
                <textarea data-formatnoteid="formatPC" style="height:125px" oninput="validateMaxlength()" onpaste="validateMaxlength()" maxlength="10000" id="passingCriteria"
                    name="passingCriteria" class="form-control" type="date" form="sylabusGenerator"
                    spellcheck="true">{{ !empty($syllabus) ? $syllabus->passing_criteria : ''}}</textarea>
            </div>
        
            <br>
             <!-- learning analytics -->
            <div class="col-12" id="learningAnalytics"></div>
        </div>

        <!-- Additional Course-Specific Information-->
        <div class="col-12">
            <h5 class="fw-bold">Additional Course-Specific Information</h5></label>
            <p class="inputFieldDescription">{{$inputFieldDescriptions['customResource']}}</p> 
                    <textarea data-formatnoteid="formatCS" style="height:25px;overflow:hidden;resize:none" oninput="validateMaxlength()" onpaste="validateMaxlength()" resize="none" maxlength="10000" id="customResourceTitle" 
                    name="customResourceTitle" class="form-control" type="date" form="sylabusGenerator"
                        spellcheck="true" placeholder="Title of Custom Section">{{ !empty($syllabus) ? $syllabus->custom_resource_title : ''}}</textarea>
                    <br>
                    <div id="formatCS" class="collapsibleNotes btn-primary rounded-3" style="overflow:hidden;transition:height 0.3s ease-out;height:auto" data-collapsed="false">
                    <i class="bi bi-exclamation-triangle-fill fs-5 pl-2 pr-2 pb-1"></i> <span class="fs-6">Place each entry
                        on a new line for the best formatting
                        results.</span>
                    </div>
                    <textarea data-formatnoteid="formatCS" style="height:125px" oninput="validateMaxlength()" onpaste="validateMaxlength()" maxlength="10000" id="customResource" 
                    name="customResource" class="form-control" type="date" form="sylabusGenerator"
                        spellcheck="true" placeholder="Body Content of Custom Section">{{ !empty($syllabus) ? $syllabus->custom_resource : ''}}</textarea>
        </div>
        <br>

        <!-- Policies and Regulations -->

        <div class="col-12" id="policiesAndRegulations"></div>

        <!-- Statement of UBC Values -->

        <div class="col-12" id="statementUBCValues"></div>

        
        <!-- Statement of Student Support -->

        <div class="col-12" id="statementStudentSupport"></div>


        <!-- Optional Statements -->
        <div class="col-12" id="optionalStatements"></div>

        <!-- Copyright Statement -->
        <div class="col-12" id="crStatement"></div>
        
        <!-- Creative Commons -->
        
        <div class="col-12">
        <label for="creativeCommons"><h5 class="fw-bold">Copyright Statement</h5></label>
            <p class="inputFieldDescription">
            @if(!empty($syllabus))
                @if($syllabus->campus=="O")
                    {!! $inputFieldDescriptions['creativeCommons'] !!}
                @endif
            @endif
            </p>
                    @if(!empty($syllabus))
                        @if($syllabus->copyright==null)
                        <input type="radio" id="noneCopyright" name="copyright" value="2" style="margin-right: 8px;" form="sylabusGenerator" checked/>
                        <label>None</label>
                        @else
                        <input type="radio" id="noneCopyright" name="copyright" value="2" style="margin-right: 8px" form="sylabusGenerator" />
                        <label>None</label>
                        @endif
                        <br>
                        @if($syllabus->copyright)
                        <input type="radio" id="yesCopyright" name="copyright" value="1" style="margin-right: 8px" form="sylabusGenerator"checked/>
                        <label>Include a Copyright Statement</label>
                        <div id="copyrightEx">
                            <blockquote> All materials of this course (course handouts, lecture slides, assessments, course readings, etc.) are the intellectual property of the Course Instructor or licensed to be used in this course by the copyright owner. Redistribution of these materials by any means without permission of the copyright holder(s) constitutes a breach of copyright and may lead to academic discipline.</blockquote>
                        </div> 
                        @else
                        <input type="radio" id="yesCopyright" name="copyright" value="1" style="margin-right: 8px" form="sylabusGenerator"/>
                        <label>Include a Copyright Statement</label>
                        
                        <div id="copyrightEx"></div>
                        @endif
        
                    @if(!empty($syllabus))
                        @if($syllabus->campus == "O")
                            @if($syllabus->copyright==1 || !isset($syllabus->cc_license))
                                <input type="radio" id="noCopyright" name="copyright" value="0" style="margin-right: 8px" form="sylabusGenerator"/>
                            @else
                                <input type="radio" id="noCopyright" name="copyright" value="0" style="margin-right: 8px" form="sylabusGenerator" checked/>
                            @endif
                        <label>Include a Creative Commons Open Copyright License</label>
                        <div> 
                        <div class="col-12">
                            <br>
                        
                            <div id="creativeCommonsInput" class="col-12">
                        @if(isset($syllabus->cc_license))
                            <h6><strong><u>Select a Creative Commons License:</u></strong></h6>
                            <br>
                                <table>
                                    <tr>
                                        <td>
                                            @if($syllabus->cc_license == 'CC BY' || $syllabus->cc_license == NULL)
                                                <input type="radio" id="creativeCommons" name="creativeCommons" value="CC BY" style="margin-right: 8px" form="sylabusGenerator" checked/>
                                            @else
                                                <input type="radio" id="creativeCommons" name="creativeCommons" value="CC BY" style="margin-right: 8px" form="sylabusGenerator"/>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="col-12">
                                                <strong>Attribution: </strong>
                                                <strong>CC BY </strong> 
                                                <br>
                                                This license lets others distribute, remix, adapt, and build upon your work, even commercially, as long as they credit you for the original creation. This is the most accommodating of licenses offered. Recommended for maximum dissemination and use of licensed materials.
                                                <br>
                                                <a href="https://creativecommons.org/licenses/by/4.0/">View License Deed</a> | <a href="https://creativecommons.org/licenses/by/4.0/legalcode">View Legal Code</a>
                                            </div>
                                            <br>
                                        <td>
                                    </tr>
                                    <tr>
                                        <td>
                                        @if($syllabus->cc_license == 'CC BY-SA')
                                            <input type="radio" id="creativeCommons" name="creativeCommons" value="CC BY-SA" style="margin-right: 8px" form="sylabusGenerator" checked/>
                                        @else
                                            <input type="radio" id="creativeCommons" name="creativeCommons" value="CC BY-SA" style="margin-right: 8px" form="sylabusGenerator"/>
                                        @endif
                                        </td>
                                        <td>
                                            <div class="col-12">
                                                <strong>Attribution-ShareAlike: </strong>
                                                <strong>CC BY-SA</strong>
                                                <br>
                                                This license lets others remix, adapt, and build upon your work even for commercial purposes, as long as they credit you and license their new creations under the identical terms. This license is often compared to “copyleft” free and open source software licenses. All new works based on yours will carry the same license, so any derivatives will also allow commercial use. This is the license used by Wikipedia, and is recommended for materials that would benefit from incorporating content from Wikipedia and similarly licensed projects.
                                                <br>
                                                <a href="https://creativecommons.org/licenses/by-sa/4.0/">View License Deed</a> | <a href="https://creativecommons.org/licenses/by-sa/4.0/legalcode">View Legal Code</a>
                                            </div>
                                            <br>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                        @if ($syllabus->cc_license == 'CC BY-ND')
                                            <input type="radio" id="creativeCommons" name="creativeCommons" value="CC BY-ND" style="margin-right: 8px" form="sylabusGenerator" checked/>
                                        @else
                                            <input type="radio" id="creativeCommons" name="creativeCommons" value="CC BY-ND" style="margin-right: 8px" form="sylabusGenerator"/>
                                        @endif
                                        </td>
                                        <td>
                                            <div class="col-12">
                                                <strong>Attribution-NoDerivs: </strong>
                                                <strong>CC BY-ND</strong>
                                                <br>
                                                This license lets others reuse the work for any purpose, including commercially; however, it cannot be shared with others in adapted form, and credit must be provided to you.
                                                <br>
                                                <a href="https://creativecommons.org/licenses/by-nd/4.0/">View License Deed</a> | <a href="https://creativecommons.org/licenses/by-nd/4.0/legalcode">View Legal Code</a>
                                            </div>
                                            <br>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                        @if($syllabus->cc_license == 'CC BY-NC')
                                            <input type="radio" id="creativeCommons" name="creativeCommons" value="CC BY-NC" style="margin-right: 8px" form="sylabusGenerator" checked/>
                                        @else
                                            <input type="radio" id="creativeCommons" name="creativeCommons" value="CC BY-NC" style="margin-right: 8px" form="sylabusGenerator"/>
                                        @endif
                                        </td>
                                        <td>
                                            <div class="col-12">
                                                <strong>Attribution-NonCommercial: </strong>
                                                <strong>CC BY-NC</strong>
                                                <br>
                                                This license lets others remix, adapt, and build upon your work non-commercially, and although their new works must also acknowledge you and be non-commercial, they don’t have to license their derivative works on the same terms.
                                                <br>
                                                <a href="https://creativecommons.org/licenses/by-nc/4.0/">View License Deed</a> | <a href="https://creativecommons.org/licenses/by-nc/4.0/legalcode">View Legal Code</a>
                                            </div>
                                            <br>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                        @if($syllabus->cc_license == 'CC BY-NC-SA')
                                            <input type="radio" id="creativeCommons" name="creativeCommons" value="CC BY-NC-SA" style="margin-right: 8px" form="sylabusGenerator" checked/>
                                        @else
                                            <input type="radio" id="creativeCommons" name="creativeCommons" value="CC BY-NC-SA" style="margin-right: 8px" form="sylabusGenerator"/>
                                        @endif
                                        </td>
                                        <td>
                                            <div class="col-12">
                                                <strong>Attribution-NonCommercial: </strong>
                                                <strong>CC BY-NC-SA</strong>
                                                <br>
                                                This license lets others remix, adapt, and build upon your work non-commercially, as long as they credit you and license their new creations under the identical terms.
                                                <br>
                                                <a href="https://creativecommons.org/licenses/by-nc-sa/4.0/">View License Deed</a> | <a href="https://creativecommons.org/licenses/by-nc-sa/4.0/legalcode">View Legal Code</a>
                                            </div>
                                            <br>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                        @if($syllabus->cc_license == 'CC BY-NC-ND')
                                            <input type="radio" id="creativeCommons" name="creativeCommons" value="CC BY-NC-ND" style="margin-right: 8px" form="sylabusGenerator" checked/>
                                        @else
                                            <input type="radio" id="creativeCommons" name="creativeCommons" value="CC BY-NC-ND" style="margin-right: 8px" form="sylabusGenerator"/>
                                        @endif
                                        </td>
                                        <td>
                                            <div class="col-12">
                                                <strong>Attribution-NonCommercial-NoDerivs: </strong>
                                                <strong>CC BY-NC-ND</strong>
                                                <br>
                                                This license is the most restrictive of our six main licenses, only allowing others to download your works and share them with others as long as they credit you, but they can’t change them in any way or use them commercially.<br>
                                                <a href="https://creativecommons.org/licenses/by-nc-nd/4.0/">View License Deed</a> | <a href="https://creativecommons.org/licenses/by-nc-nd/4.0/legalcode">View Legal Code</a>
                                            </div>
                                            <br>
                                        </td>
                                    </tr>
                                </table>
                                @endif
                                </div>
                            
                        </div>
                        
            </div>
            @endif
        @endif
                        
                    @else
                        <input type="radio" id="noneCopyright" name="copyright" value="2" style="margin-right: 8px" form="sylabusGenerator" checked/>
                        <label>None</label>
                        <br>
                        <input type="radio" id="yesCopyright" name="copyright" value="1" style="margin-right: 8px" form="sylabusGenerator"/>
                        <label>Include a Copyright Statement</label>
                        <div id="copyrightEx"></div>
                        <input type="radio" id="noCopyright" name="copyright" value="0" style="margin-right: 8px" form="sylabusGenerator"/>
                                <label>Include a Creative Commons Open Copyright License</label>
                                <div class="col-12">
                                <br>
                                    <div id="creativeCommonsInput" class="col-12">
                                        </div>
                                </div>
                
                    @endif
        </div>
          
        <!-- Land Acknowledgement Statement -->
        <div class="col-12" id="landAcknowledgement"></div>
    </form>

    <div class="m-3">
        <div class="text-center row justify-content-center">
            <div class="col-2" style="max-width:10%">
                <button type="submit" form="sylabusGenerator" class="btn m-0 p-0" style="background:none;border:none" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Save my syllabus">
                    <i class="btn btn-light rounded-circle m-2 text-secondary bi bi-clipboard2-check-fill"></i>
                    <p style="font-size:12px" class="text-muted m-0">SAVE</p>
                </button>
            </div>
            <!-- PDF Download Disabled while we investigate escaping issues -->
            <!--
            <div class="col-2" style="max-width:10%">
                <button type="submit" name="download" value="pdf" form="sylabusGenerator"
                    class="btn m-0 p-0" style="background:none;border:none" data-bs-toggle="tooltip" data-bs-placement="bottom"
                    title="Save and download my syllabus as PDF">
                    <i class="text-danger bi-file-pdf-fill btn btn-light rounded-circle m-2"></i>
                    <p style="font-size:12px" class="text-muted m-0">PDF</p>
                </button>
            </div>
            -->
            <div class="col-2" style="max-width:10%">
                <button type="submit" name="download" value="word" form="sylabusGenerator"
                    class="btn m-0 p-0" style="background:none;border:none" data-bs-toggle="tooltip" data-bs-placement="bottom"
                    title="Save and Download syllabus as Word"><i
                        class="bi-file-earmark-word-fill text-primary btn btn-light rounded-circle m-2"></i></i>
                    <p style="font-size:12px" class="text-muted m-0">WORD</p>
                </button>
            </div>
            <div class="col-2" style="max-width:10%">
                <span data-bs-toggle="modal" data-bs-target="#importExistingCourse">
                    <button type="button" class="btn m-0 p-0" data-bs-toggle="tooltip"
                        data-bs-placement="bottom" title="Import an existing course" style="background:none;border:none"><i
                            class="text-secondary bi bi-box-arrow-in-down-left btn btn-light rounded-circle m-2"></i>
                            <p style="font-size:12px" class="text-muted m-0">IMPORT</p>
                        </button>
                </span>
            </div>
    
            @if (!empty($syllabus))
                <div class="col-2" style="max-width:10%">
                    <span data-bs-toggle="modal" data-bs-target="#addSyllabusCollaboratorsModal{{$syllabus->id}}">
                        <button type="button" class="btn m-0 p-0" style="background:none;border:none" data-bs-toggle="tooltip"
                            data-bs-placement="bottom" title="Add collaborators to my syllabus"><i
                                class="text-primary bi bi-people-fill btn btn-light rounded-circle m-2"></i>
                            <p style="font-size:12px" class="text-muted m-0">PEOPLE</p>
                        </button>
                    </span>
                </div>
        
                @include('modals.duplicateModal', ['syllabus' => $syllabus])
                <div class="col-2" style="max-width:10%">
                    <span data-bs-toggle="modal" data-bs-target="#duplicateConfirmation">
                        <button type="button" class="btn m-0 p-0" style="background:none;border:none" data-bs-toggle="tooltip"
                            data-bs-placement="bottom" title="Make a copy of my syllabus"><i
                                class="btn btn-light rounded-circle m-2 text-success bi bi-files"></i>
                                <p style="font-size:12px" class="text-muted m-0">COPY</p>
                        </button>
                    </span>
                </div>
        
                @include('modals.deleteModal', ['syllabus' => $syllabus])
                <div class="col-2" style="max-width:10%">
                    <span data-bs-toggle="modal" data-bs-target="#deleteSyllabusConfirmation">
                        <button type="button" class="btn m-0 p-0" style="background:none;border:none" data-bs-toggle="tooltip"
                            data-bs-placement="bottom" title="Delete my syllabus"><i class="btn btn-danger rounded-circle m-2 bi bi-trash-fill"></i>
                            <p style="font-size:12px" class="text-muted m-0">DELETE</p>
                        </button>
                    </span>
                </div>
            @endif
        </div>
    </div>  
</div>

<script type="application/javascript">
    
    var syllabus = <?php echo json_encode($syllabus);?>;
    var faculties = <?php echo json_encode($faculties);?>;

    var vFaculties = faculties.filter(item => {
        return item.campus_id === 1;
    });
    var oFaculties = faculties.filter(item => {
        return item.campus_id === 2;
    });
    var departments = <?php echo json_encode($departments);?>;

    $(document).ready(function () {

        $(function () {
            $('[data-bs-toggle="popover"]').popover()
        })

        $('[data-bs-toggle="tooltip"]').tooltip();


        // event listener on select term dropdown
        $('#courseSemester').on('change', function(event) { 
            // insert a text input if user selects other
            if ($('#courseSemester').val() == 'O') {
                $('#courseSemesterOther').html(`
                    <label class="form-label" for="courseSemesterOther">Other</label>
                    <input class="form-control" type="text" name="courseSemesterOther" oninput="validateMaxlength()" onpaste="validateMaxlength()" maxlength="49">
                    <div class="invalid-tooltip">
                        Please specify the course term.
                    </div>
                `);
            } else {
                // remove html for other course term input
                $('#courseSemesterOther').html('');
            }
        });

        //event listener for Cross Listed
        $('#crossListed').on('change', function() {
            if(this.checked){
                $('#crossListedCode').html(`
                
                <label for="courseCodeCL">Cross-Listed Course Code<span class="requiredField"></span></label>
            <input id = "courseCodeCL" pattern="[A-Za-z]+" minlength="1" name = "courseCodeCL" oninput="validateMaxlength()" onpaste="validateMaxlength()" maxlength="4" class ="form-control" type="text" placeholder="E.g. CPSC" required value="{{ !empty($syllabus) ? $syllabus->cross_listed_code : '' }}">
            <div class="invalid-tooltip">
                Please enter the course code.
            </div>

                `);
                $('#crossListedNumber').html(`
                    <label for="courseNumberCL">Cross-Listed Course Number<span class="requiredField"></span></label>
                    <input id = "courseNumberCL" name = "courseNumberCL" oninput="validateMaxlength()" onpaste="validateMaxlength()" maxlength="3" class ="form-control" type="number" placeholder="E.g. 310" value="{{ !empty($syllabus) ? $syllabus->cross_listed_num : '' }}">
                    <div class="invalid-tooltip">
                        Please enter the course number.
                    </div>
                `);
            } else {
                $('#crossListedCode').html('');
                $('#crossListedNumber').html('');
            }
        });
        //event listeners for Creative Commons License Input
        $('#noneCopyright').on('click', function(event) {
            //mankey
            $('#copyrightEx').html(``);
            $('#creativeCommonsInput').html(``);
        });
        
        $('#yesCopyright').on('click', function(event) {
            //mankey
            $('#copyrightEx').html(` 
            <div>
                <blockquote> All materials of this course (course handouts, lecture slides, assessments, course readings, etc.) are the intellectual property of the Course Instructor or licensed to be used in this course by the copyright owner. Redistribution of these materials by any means without permission of the copyright holder(s) constitutes a breach of copyright and may lead to academic discipline.</blockquote>
            </div> 
            `);
            $('#creativeCommonsInput').html(``);

        });

        $('#noCopyright').on('click', function(event) {

            $('#creativeCommonsInput').html(
            `
                            <h6><strong><u>Select a Creative Commons License:</u></strong></h6>
                            <br>
                                <table>
                                    <tr>
                                        <td>
                                            <input type="radio" id="creativeCommons" name="creativeCommons" value="CC BY" style="margin-right: 8px" form="sylabusGenerator"checked/>
                                        </td>
                                        <td>
                                            <div class="col-12">
                                                <strong>Attribution: </strong>
                                                <strong>CC BY</strong>
                                                <br>
                                                This license lets others distribute, remix, adapt, and build upon your work, even commercially, as long as they credit you for the original creation. This is the most accommodating of licenses offered. Recommended for maximum dissemination and use of licensed materials.
                                                <br>
                                                <a href="https://creativecommons.org/licenses/by/4.0/">View License Deed</a> | <a href="https://creativecommons.org/licenses/by/4.0/legalcode">View Legal Code</a>
                                            </div>
                                            <br>
                                        <td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input type="radio" id="creativeCommons" name="creativeCommons" value="CC BY-SA" style="margin-right: 8px" form="sylabusGenerator"/>
                                        </td>
                                        <td>
                                            <div class="col-12">
                                                <strong>Attribution-ShareAlike: </strong>
                                                <strong>CC BY-SA</strong>
                                                <br>
                                                This license lets others remix, adapt, and build upon your work even for commercial purposes, as long as they credit you and license their new creations under the identical terms. This license is often compared to “copyleft” free and open source software licenses. All new works based on yours will carry the same license, so any derivatives will also allow commercial use. This is the license used by Wikipedia, and is recommended for materials that would benefit from incorporating content from Wikipedia and similarly licensed projects.
                                                <br>
                                                <a href="https://creativecommons.org/licenses/by-sa/4.0/">View License Deed</a> | <a href="https://creativecommons.org/licenses/by-sa/4.0/legalcode">View Legal Code</a>
                                            </div>
                                            <br>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input type="radio" id="creativeCommons" name="creativeCommons" value="CC BY-ND" style="margin-right: 8px" form="sylabusGenerator"/>
                                        </td>
                                        <td>
                                            <div class="col-12">
                                                <strong>Attribution-NoDerivs: </strong>
                                                <strong>CC BY-ND</strong>
                                                <br>
                                                This license lets others reuse the work for any purpose, including commercially; however, it cannot be shared with others in adapted form, and credit must be provided to you.
                                                <br>
                                                <a href="https://creativecommons.org/licenses/by-nd/4.0/">View License Deed</a> | <a href="https://creativecommons.org/licenses/by-nd/4.0/legalcode">View Legal Code</a>
                                            </div>
                                            <br>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input type="radio" id="creativeCommons" name="creativeCommons" value="CC BY-NC" style="margin-right: 8px" form="sylabusGenerator"/>
                                        </td>
                                        <td>
                                            <div class="col-12">
                                                <strong>Attribution-NonCommercial: </strong>
                                                <strong>CC BY-NC</strong>
                                                <br>
                                                This license lets others remix, adapt, and build upon your work non-commercially, and although their new works must also acknowledge you and be non-commercial, they don’t have to license their derivative works on the same terms.
                                                <br>
                                                <a href="https://creativecommons.org/licenses/by-nc/4.0/">View License Deed</a> | <a href="https://creativecommons.org/licenses/by-nc/4.0/legalcode">View Legal Code</a>
                                            </div>
                                            <br>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input type="radio" id="creativeCommons" name="creativeCommons" value="CC BY-NC-SA" style="margin-right: 8px" form="sylabusGenerator"/>
                                        </td>
                                        <td>
                                            <div class="col-12">
                                                <strong>Attribution-NonCommercial: </strong>
                                                <strong>CC BY-NC-SA</strong>
                                                <br>
                                                This license lets others remix, adapt, and build upon your work non-commercially, as long as they credit you and license their new creations under the identical terms.
                                                <br>
                                                <a href="https://creativecommons.org/licenses/by-nc-sa/4.0/">View License Deed</a> | <a href="https://creativecommons.org/licenses/by-nc-sa/4.0/legalcode">View Legal Code</a>
                                            </div>
                                            <br>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input type="radio" id="creativeCommons" name="creativeCommons" value="CC BY-NC-ND" style="margin-right: 8px" form="sylabusGenerator"/>
                
                                        </td>
                                        <td>
                                            <div class="col-12">
                                                <strong>Attribution-NonCommercial-NoDerivs: </strong>
                                                <strong>CC BY-NC-ND</strong>
                                                <br>
                                                This license is the most restrictive of our six main licenses, only allowing others to download your works and share them with others as long as they credit you, but they can’t change them in any way or use them commercially.
                                                <br>
                                                <a href="https://creativecommons.org/licenses/by-nc-nd/4.0/">View License Deed</a> | <a href="https://creativecommons.org/licenses/by-nc-nd/4.0/legalcode">View Legal Code</a>
                                            </div>
                                            <br>
                                        </td>
                                    </tr>
                                </table>
                                 `);

            $('#copyrightEx').html(``);
            
        });
        
    
        // event listener on create course schedule submit form button
        $('#createCourseScheduleTblForm').on('submit', function(event) { 
            // prevent default submit procedure
            event.preventDefault();
            event.stopPropagation();
            var createCourseScheduleTblModal = bootstrap.Modal.getInstance(document.getElementById('createCourseScheduleTblModal'));
            // get course schedule table div
            var courseScheduleTblDiv = document.getElementById('courseScheduleTblDiv');
            // create table if it doesn't exist
            if (!document.getElementById('courseScheduleTbl')) {
                // get num rows 
                var numRows = event.target.elements.numRows.value;
                // get num cols
                var numCols = event.target.elements.numCols.value;
                // create <table> element
                var tbl = document.createElement('table');
                tbl.setAttribute('id', 'courseScheduleTbl');
                tbl.setAttribute('class', 'table align-middle reorder-tbl-rows table-light');
                // create <thead> element
                var tblHead = document.createElement('thead');
                // create <tbody> element
                var tblBody = document.createElement('tbody');
                // iterate over rows 
                for (let rowIndex = 0; rowIndex < parseInt(numRows) + 1; rowIndex++) {
                    // create <row> element
                    var row = document.createElement('tr');
                    if (rowIndex === 0) row.setAttribute('class', 'table-primary');
                    // iterate over cols

                    for (let colIndex = 0; colIndex < parseInt(numCols) + 1; colIndex++) {
                        
                        // create <textarea>
                        var inputCell = document.createElement('textarea');
                        inputCell.setAttribute('form', 'sylabusGenerator');
                        inputCell.setAttribute('type', 'text');
                        inputCell.setAttribute('class', 'form-control');
                        inputCell.setAttribute('spellcheck', 'true');
                        inputCell.setAttribute('maxlength', '1000');
                        inputCell.setAttribute('onpaste', 'validateMaxlength');
                        inputCell.setAttribute('oninput', 'validateMaxlength()');
                        // if first row, create and style <th> cells, otherwise create and style <td> cells
                        if (rowIndex === 0) {
                            // create <th> element
                            headerCell = document.createElement('th');
                            if (colIndex != 0) {
                                // set input attributes for column headers
                                inputCell.setAttribute('placeholder', 'Column heading here ...');
                                inputCell.setAttribute('name', 'courseScheduleTblHeadings[]');
                                headerCell.appendChild(inputCell);
                                // put inputCell in <th>
                                headerCell.appendChild(inputCell);
                            }
                            // put <th> in <row>                           
                            row.appendChild(headerCell);
                        } else {
                            // create <td> element 
                            var cell = document.createElement('td');
                            if (colIndex == 0) {
                                cell.setAttribute('class', 'align-middle fs-5 draggable');
                                cell.addEventListener('mousedown', mouseDownHandler);
                                cell.innerHTML = "↕";
                            } else {
                                // set input attributes for data cells
                                inputCell.setAttribute('placeholder', 'Data here ...');                        
                                inputCell.setAttribute('name', 'courseScheduleTblRows[]');
                                // put inputCell in <td>
                                cell.appendChild(inputCell);
                            }
                            // put <td> in <row>
                            row.appendChild(cell);
                        }
                    }
                    // add action cell to row if it is not the header row
                    if (rowIndex != 0) {
                        // create <td> element for row actions
                        var actionsCell = document.createElement('td');
                        // center row actions
                        actionsCell.setAttribute('class', 'align-middle');
                        // create delete action icon
                        var delAction = document.createElement('i');
                        // style delete action icon
                        delAction.setAttribute('class', 'bi bi-x-circle-fill text-danger fs-4 btn');
                        // add on click listener to del row
                        delAction.onclick = delCourseScheduleRow;                        
                        // put <i> in <td>
                        actionsCell.appendChild(delAction);
                        // put actions cell in <row>
                        row.appendChild(actionsCell);
                        // put <row> in <tbody>
                        tblBody.appendChild(row);
                    } else {
                        // create empty <td>
                        var actionColTdHeader = document.createElement('th');
                        // put <td> in <row>
                        row.appendChild(actionColTdHeader);
                        // put <tr> in <thead>
                        tblHead.appendChild(row);
                    }
                }
                // put <thead> in <table>
                tbl.appendChild(tblHead);
                // put <tbody> in <table> 
                tbl.appendChild(tblBody);
                // put <table> in course schedule table div
                courseScheduleTblDiv.appendChild(tbl);
                // show the course schedule table toolbar
                $('#courseScheduleTblToolbar').removeAttr('hidden');
                // hide create table btn
                $('#createTableBtn').attr('hidden', 'true');
            }

            createCourseScheduleTblModal.hide();

        });

        // event listener on delete course schedule button
        $('#delCourseScheduleBtn').on('click', function(event) {
            var courseScheduleTblDiv = document.getElementById('courseScheduleTblDiv');
            // remove all child nodes 
            $(courseScheduleTblDiv).empty();
            // show create a course schedule table button
            $('#createTableBtn').removeAttr('hidden');
            $('#courseScheduleTblToolbar').attr('hidden', 'true');

            var delCourseScheduleTblModal = bootstrap.Modal.getInstance(document.getElementById('delCourseScheduleTbl'));
            // close modal
            delCourseScheduleTblModal.hide();
        });


        // event listener on add col buttons
        $('.addCol').on('click', function (event) { 
            // get the course schedule table 
            var courseScheduleTbl = document.getElementById('courseScheduleTbl');
            // if course schedule table exists, add col to the side indicated by the button clicked
            if (courseScheduleTbl) {
                // get which side to add the col to 
                var side = event.currentTarget.dataset.side;
                // get the num of cols in the tbl 
                var numCols = courseScheduleTbl.rows[0].cells.length;
                // add col if there are less than 6 cols
                if (numCols < parseInt($('#courseScheduleTblColsCount').attr('max')) + 2) {  
                    // add a new <td> to each <row>
                    Array.from(courseScheduleTbl.rows).forEach((row, rowIndex) => {
                        // create a <textarea>
                        var inputCell = document.createElement('textarea');
                        inputCell.setAttribute('form', 'sylabusGenerator');
                        inputCell.setAttribute('type', 'text');
                        inputCell.setAttribute('class', 'form-control');
                        inputCell.setAttribute('spellcheck', 'true');
                        inputCell.setAttribute('maxlength', '1000');
                        inputCell.setAttribute('onpaste', 'validateMaxlength');
                        inputCell.setAttribute('oninput', 'validateMaxlength()');
                        // set input attributes for column headers, otherwise set input attributes for data cells
                        if (rowIndex == 0) {
                            inputCell.setAttribute('placeholder', 'Column heading here ...');
                            inputCell.setAttribute('name', 'courseScheduleTblHeadings[]');
                        } else {
                            inputCell.setAttribute('placeholder', 'Data here ...');                        
                            inputCell.setAttribute('name', 'courseScheduleTblRows[]');
                        }
                        // add column on the correct side
                        switch (side) {
                            case 'left':
                                // put <td> in <row> at the front (insert col on left)
                                if (rowIndex == 0) {
                                    headerCell = document.createElement('th');
                                    headerCell.appendChild(inputCell);
                                    row.cells[0].after(headerCell);
                                    // row.prepend(headerCell);
                                } else {
                                    newCell = row.insertCell(1);
                                    newCell.appendChild(inputCell);
                                }
                                break;
                            case 'right': 
                                // put <td> in <row> at the back (insert col on the right)
                                newCell = row.insertCell(numCols - 1);
                                // if header row, make sure new cell has <th> tags
                                if (rowIndex == 0) newCell.outerHTML = `<th>${inputCell.outerHTML}</th>`;
                                // add <textarea> to data cell
                                newCell.appendChild(inputCell);
                                // row.appendChild(cell);
                                break;
                        }
                    });
                } else {
                    // 
                    var popover = bootstrap.Popover.getInstance(event.currentTarget.parentNode);
                    popover.show();
                    // hide popover after 3 seconds
                    setTimeout(function() { popover.hide(); }, 3000);
                }
            }
        });

        // event listener on delete column(s) button in course schedule table toolbar
        // updates the delCols confirmation modal with info about the columns
        $('#delCols').on('click', function (event) {
            // get the course schedule table 
            var courseScheduleTbl = document.getElementById('courseScheduleTbl');
            // if table exists, update and show delCols confirmation modal 
            if (courseScheduleTbl) {
                // get modal for deleting cols
                var delColsModalEl = document.getElementById('delColsModal');
                var delColsModal = new bootstrap.Modal(delColsModalEl);
                // get div where cols should be listed
                var courseScheduleTblColsListDiv = document.getElementById('courseScheduleTblColsList');
                // empty the div where cols should be listed to refresh the list
                $(courseScheduleTblColsListDiv).empty();
                // get the column cells from the first row
                var cols = courseScheduleTbl.rows[0].cells;
                // foreach col create a checkbox with label and place it in the delColsModal 
                Array.from(cols).forEach((col, colIndex) => {
                    // only add relevant col headers to del cols modal
                    if (colIndex < cols.length - 1 && colIndex > 0) {
                        // <div> foreach <input> and <label>
                        var colDiv = document.createElement('div');
                        // add bootstrap form elements styling
                        colDiv.setAttribute('class', 'form-check form-check-inline');
                        // create, style and set attributes for <input> 
                        var colCheckbox = document.createElement('input');
                        colCheckbox.setAttribute('id', 'col-heading-' + (colIndex + 1).toString());
                        colCheckbox.setAttribute('type', 'checkbox');
                        colCheckbox.setAttribute('name', 'colIndex');
                        colCheckbox.setAttribute('class', 'form-check-input');
                        colCheckbox.setAttribute('value', colIndex.toString());
                        colCheckbox.setAttribute('maxlength', '1000');
                        colCheckbox.setAttribute('onpaste', 'validateMaxlength');
                        colCheckbox.setAttribute('oninput', 'validateMaxlength()');
                        // create, style and set attributes for <label>
                        var colLabel = document.createElement('label');
                        colLabel.setAttribute('for', 'col-heading-' + (colIndex + 1).toString());
                        colLabel.setAttribute('class', 'form-check-label');
                        colLabel.innerHTML = (col.firstElementChild.value.length === 0) ? 'Column #' + (colIndex).toString() : col.firstElementChild.value; 
                        // put <input> in <div>
                        colDiv.appendChild(colCheckbox);
                        // put <label> in <div>
                        colDiv.appendChild(colLabel);
                        // put inner <div> in outer <div> 
                        courseScheduleTblColsListDiv.appendChild(colDiv);
                    }
                });
                // show the delCols confirmation modal
                delColsModal.show();
            }
        });

        $('#delColsForm').on('submit', function (event) {
            // prevent default submit procedure
            event.preventDefault();
            event.stopPropagation(); 
            // get del cols confirmation modal   
            var delColsModal = bootstrap.Modal.getInstance(document.getElementById('delColsModal'));   
            // get the columns to delete from the del cols confirmation form 
            var colsToDelete = $(this).serializeArray().map((input, index) => {
                return input.value;
            });
            // sort colsToDelete in descending order to ensure cols with the greatest positions are deleted first. 
            colsToDelete.sort(function(a, b) {return b - a});
            // get the course schedule table 
            var courseScheduleTbl = document.getElementById('courseScheduleTbl');
            // if table exists, del specified cols
            if (courseScheduleTbl) {
                // iterate over table rows 
                Array.from(courseScheduleTbl.rows).forEach((row, rowIndex) => {
                    // iterate over columns to delete
                    colsToDelete.forEach((colToDelete) => {
                        // delete cells from every row
                        row.deleteCell(colToDelete);
                        
                    });
                });
            }
            delColsModal.hide();
        });

        $('.addRow').on('click', function (event) { 
            // get the course schedule table 
            var courseScheduleTbl = document.getElementById('courseScheduleTbl');

            // if course schedule table has been created 
            if (courseScheduleTbl) {
                // get which side to add the row to 
                var side = event.currentTarget.dataset.side;
                // get the number of cols in the tbl
                var numCols = courseScheduleTbl.rows[0].cells.length;
                // if num rows in the tbl is less than the max, add row
                if (courseScheduleTbl.rows.length < $('#courseScheduleTblRowsCount').attr('max')) {
                    // create <textarea>
                    var inputCell = document.createElement('textarea');
                    inputCell.setAttribute('form', 'sylabusGenerator');
                    inputCell.setAttribute('name', 'courseScheduleTblRows[]');
                    inputCell.setAttribute('type', 'text');
                    inputCell.setAttribute('class', 'form-control');
                    inputCell.setAttribute('spellcheck', 'true');
                    inputCell.setAttribute('maxlength', '1000');
                    inputCell.setAttribute('onpaste', 'validateMaxlength');
                    inputCell.setAttribute('oninput', 'validateMaxlength()');
                    // set placeholder values for <textarea>
                    inputCell.setAttribute('placeholder', 'Data here ...');
                    // switch on side to add row
                    switch (side) {
                        case 'top':
                            // add a row at the top
                            let topRow = courseScheduleTbl.tBodies[0].insertRow(0);
                            // add a cell for each col to the new row
                            for (let colIndex = 0; colIndex < numCols - 1; colIndex++) {
                                // create  <td> element 
                                var cell = document.createElement('td');
                                if (colIndex == 0) {
                                    cell.setAttribute('class', 'align-middle fs-5 draggable');
                                    cell.addEventListener('mousedown', mouseDownHandler);
                                    cell.innerHTML = "↕";
                                } else { 
                                    // put inputCell in <td>
                                    cell.appendChild(inputCell.cloneNode());
                                }
                                topRow.appendChild(cell);

                            }
                            // create <td> element for row actions
                            var actionsCell = document.createElement('td');
                            // center row actions
                            actionsCell.setAttribute('class', 'align-middle');
                            // create delete action icon
                            var delAction = document.createElement('i');
                            // style delete action icon
                            delAction.setAttribute('class', 'bi bi-x-circle-fill text-danger fs-4 btn');
                            // add on click listener to del row
                            delAction.onclick = delCourseScheduleRow;                        
                            // put <i> in <td>
                            actionsCell.appendChild(delAction);
                            // put actions cell in <row>
                            topRow.appendChild(actionsCell);
                            break;

                        case 'bottom':
                            // add a row at the bottom
                            let bottomRow = courseScheduleTbl.tBodies[0].insertRow(-1);
                            // add a cell for each col to the new row
                            for (let colIndex = 0; colIndex < numCols - 1; colIndex++) {
                                // clone input cell to add it to a row multiple times
                                var cell = document.createElement('td');
                                if (colIndex == 0) {
                                    cell.setAttribute('class', 'align-middle fs-5 draggable');
                                    cell.addEventListener('mousedown', mouseDownHandler);
                                    cell.innerHTML = "↕";
                                } else { 
                                    // put inputCell in <td>
                                    cell.appendChild(inputCell.cloneNode());
                                }
                                bottomRow.appendChild(cell);
                            }
                            // create <td> element for row actions
                            var actionsCell = document.createElement('td');
                            // center row actions
                            actionsCell.setAttribute('class', 'align-middle');
                            // create delete action icon
                            var delAction = document.createElement('i');
                            // style delete action icon
                            delAction.setAttribute('class', 'bi bi-x-circle-fill text-danger fs-4 btn');
                            // add on click listener to del row
                            delAction.onclick = delCourseScheduleRow;                        
                            // put <i> in <td>
                            actionsCell.appendChild(delAction);
                            // put actions cell in <row>
                            bottomRow.appendChild(actionsCell);
                            break;
                        default:
                            let row = courseScheduleTbl.insertRow();                
                    }
                } else {
                    var popover = bootstrap.Popover.getInstance(event.currentTarget.parentNode);
                    popover.show();
                    // hide popover after 3 seconds
                    setTimeout(function() { popover.hide(); }, 3000);
                }
            }
        });

        // add on change event listener to campus select 
        $('#campus').change(function(){
            onChangeCampus();
            });
        
        // use custom bootstrap input validation
        $('#sylabusGenerator').submit(function(event){
            var invalidFields = $('#sylabusGenerator :invalid');
            if (invalidFields.length > 0) {
                event.preventDefault();
                event.stopPropagation();
                $('html, body').animate({
                    scrollTop: $(invalidFields[0]).offset().top - 100,
                });
                $(this).addClass('was-validated');
            // all fields are valid
            } else {
                $(this).removeClass('was-validated');
            }
        });
        
        // add on click event listener to import course info button
        $('#importButton').click(importCourseInfo);
        // trigger campus dropdown change based on saved syllabus
        if (syllabus['campus'] === 'O') {
            $('#campus').val('O').trigger('change');
        } else if (syllabus['campus'] === 'V') {
            $('#campus').val('V').trigger('change');
        }
        // use saved class meeting days
        if (syllabus['class_meeting_days']) {
            // split class meeting days string into an array
            const classMeetingDays = syllabus['class_meeting_days'].split("/");
            // mark days included in classMeetingDays as checked

            if (classMeetingDays.includes('Mon')) {
                $('#monday').attr('checked', 'true');
            }
            if (classMeetingDays.includes('Tue')) {
                $('#tuesday').attr('checked', 'true');
            }
            if (classMeetingDays.includes('Wed')) {
                $('#wednesday').attr('checked', 'true');
            }
            if (classMeetingDays.includes('Thu')) {
                $('#thursday').attr('checked', 'true');
            }
            if (classMeetingDays.includes('Fri')) {
                $('#friday').attr('checked', 'true');
            }
			if (classMeetingDays.includes('Sat')) {
                $('#saturday').attr('checked', 'true');
            }
        }
        // use event delegation to show format note on focus in
        document.getElementById("sylabusGenerator").addEventListener('focusin', function (event) {
            var formatNoteId = event.target.dataset.formatnoteid;
            if (formatNoteId) {
                var note = document.querySelector('#' + formatNoteId);
                var isCollapsed = note.dataset.collapsed === 'true';

                if (isCollapsed) {
                    expandSection(note);
                    note.setAttribute('data-collapsed', 'false');
                } 
            }
        });
        
        // use event delegation to hide format note on focus out
        document.getElementById("sylabusGenerator").addEventListener('focusout', function (event) {
            var formatNoteId = event.target.dataset.formatnoteid;
            if (formatNoteId) {
                var note = document.querySelector('#' + formatNoteId);
                var isCollapsed = note.dataset.collapsed === 'true';

                if (!isCollapsed) {
                    collapseSection(note);
                    note.setAttribute('data-collapsed', 'true');
                }

            }
        });
        // update syllabus form with the campus specific info
        onChangeCampus();
    });

    // delete a course schedule row
    function delCourseScheduleRow(submitter) {
        // get delete row confirmation modal
        var delRowModalEl = document.getElementById('delRowModal');
        // instantiate new bootstrap modal
        var delRowModal = new bootstrap.Modal(delRowModalEl);
        // set on click listener for delete confirmation
        $('#delRowBtn').on('click', function (event) {
            // delete row
            (submitter.target) ? $(submitter.target).parents('tr').remove() : $(submitter).parents('tr').remove();
            // hide modal
            delRowModal.hide();
        });
        // show modal
        delRowModal.show();

    }

    function addInstructor() {
        instructorHTML =   `
        <div class="instructor row g-3 align-items-end m-0 p-0">
            <div class="col-5">
                <label for="courseInstructor">Name<span class="requiredField"> *</span></label>
                <input id = "courseInstructor" name = "courseInstructor[]" oninput="validateMaxlength()" onpaste="validateMaxlength()" maxlength="75" class ="form-control" type="text" placeholder="E.g. Dr. J. Doe" required>
                <div class="invalid-tooltip">
                    Please enter the course instructor.
                </div>
            </div>

            <div class="col-5">
                <label for="courseInstructorEmail">Email<span class="requiredField"> *</span></label>
                <input id = "courseInstructorEmail" name = "courseInstructorEmail[]" oninput="validateMaxlength()" onpaste="validateMaxlength()" maxlength="75" class ="form-control" type="email" placeholder="E.g. jane.doe@ubc.ca" required value="">
                <div class="invalid-tooltip">
                    Please enter the instructors email.
                </div>
            </div>
            
            <div class="col-2">
                <button type="button" class="btn btn-danger col" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Delete instructor" onclick="delInstructor(this)"><i class="bi bi-trash-fill"></i> Delete</button>
            </div>
        </div>`;

        $('#addInstructorBtn').before(instructorHTML);
    }

    function delInstructor(delInstructorBtn) {
        // at least 1 instructor is required
        if ($('.instructor').length > 1)
            delInstructorBtn.parentNode.parentNode.remove();
    }

    // Import course info into using GET AJAX call
    function importCourseInfo() {
        var course_id = $('.importCourseId:checked').val();
        // get user specified course componenets to import
        var importCourseSettings = $('#importCourseSettingsForm').serializeArray();

        $.ajax({
            type: "GET",
            url: "/syllabusGenerator/import/course",
            data: {
                course_id : course_id, 
                importCourseSettings: importCourseSettings
            },
            headers: {
                'X-CSRF-Token': '{{ csrf_token() }}',
            },
        }).done(function(data) {
            data = JSON.parse(data);
            $('#courseTitle').val(data['c_title']);
            $('#courseCode').val(data['c_code']);
            $('#courseNumber').val(data['c_num']);
            $('#deliveryModality').val(data['c_del']);
            $('#courseYear').val(data['c_year']);
            $('#courseSemester').val(data['c_term']);

            if (data.hasOwnProperty('l_outcomes')) {
                var l_outcomes = data['l_outcomes'];
                var l_outcomes_text = "";
                for(var i = 0; i < l_outcomes.length; i++) {
                    l_outcomes_text += l_outcomes[i].l_outcome + "\n";
                }
                $('#learningOutcome').val(l_outcomes_text);
            }
            if (data.hasOwnProperty('a_methods')) {
                var a_methods = data['a_methods'];
                var a_methods_text = "";
                a_methods.forEach(element => {
                    a_methods_text += element.a_method + " " + element.weight + "%\n";
                });
                $('#learningAssessments').val(a_methods_text);
            }
            if (data.hasOwnProperty('l_activities')) {
                var l_activities = data['l_activities'];
                var l_activities_text = "";
                for(var i = 0; i < l_activities.length; i++) {
                    l_activities_text += l_activities[i].l_activity + "\n";
                }
                $('#learningActivities').val(l_activities_text);
            }
            if (data.hasOwnProperty('course_alignment')) {
                $('#courseAlignment').empty();
                courseAlignmentHTML = getCourseAlignmentHTML(course_id, data['course_alignment']);
                $('#courseAlignment').append(courseAlignmentHTML);
            }
            if (data.hasOwnProperty('programs')) {
                $('#outcomeMapsDiv').empty();
                programs = data['programs'];
                for (programId in programs) {
                    closForOutcomeMaps = data['closForOutcomeMaps'];
                    programOutcomeMapHTML = getProgramOutcomeMapSectionHTML(closForOutcomeMaps, programs[programId]);
                    $('#outcomeMapsDiv').append(programOutcomeMapHTML);
                }
            }

        });
    }

    function getProgramOutcomeMapSectionHTML(clos, program) {
        headerHTML = `
            <h5 class="fw-bold pt-4 mb-2 col-12 pt-4 mb-4 mt-2">
                ${program['programTitle']}                                    
                <button id="" type="button" class="btn btn-danger float-right" onclick="removeSection(this)">Remove Section</button>
                <input hidden name="import_course_settings[programs][]" value="${program['programId']}">
            </h5>
        `;
        mappingScalesHTML = getMappingScalesHTML(program['mappingScales']);
        // return early without outcome map if no mapping scale has been set for this program
        // if (program['mappingScales'].length < 1) 
        //     return `<div class="mb-4">${headerHTML + mappingScalesHTML}</div>`;

        
        outcomeMapHTML = getProgramOutcomeMapHTML(program['programLearningOutcomes'], program['categories'], program['uncategorizedPlos'], clos, program['outcomeMap']);

        return `<div class="mb-4">${headerHTML + mappingScalesHTML + outcomeMapHTML}</div>`;
    }

    function getProgramOutcomeMapHTML(plos, categories, uncategorizedPLOs, clos, outcomeMap) {
        if (outcomeMap.length == 0)
            return `    
                <div class="col-12">
                    <div class="alert alert-warning wizard">
                        <i class="bi bi-exclamation-circle-fill"></i>Course learning outcomes have not been mapped to program learning outcomes for this program.                 
                    </div>
                </div>`;
        
        categoryHeaderCells = ``;
        categorizedPLOCells = ``;
        categorizedOutcomeMapCells = [];
        categories.forEach((category, categoryIndex) => {
            if (category['plos'].length > 0) {
                th = `
                    <th class="table-active w-auto" colspan="${category['plos'].length}" style="min-width:5%; white-space:nowrap;overflow:hidden;text-overflow:ellipsis">${category['plo_category']}</th>            
                `;
                categoryHeaderCells += th;
                category['plos'].forEach((plo, index) => {

                    if (plo['plo_shortphrase']) {
                        td = `
                        <td style="height:0; text-align: left;">
                            ${plo['plo_shortphrase']}
                        </td>`;
                    } else {
                        td = `
                        <td style="height:0; text-align: left;">
                            ${plo['pl_outcome']}
                        </td>`;
                    }

                    clos.forEach((clo, index) => {
                        if (!categorizedOutcomeMapCells[clo['l_outcome_id']])
                            categorizedOutcomeMapCells[clo['l_outcome_id']] = '';

                        if (outcomeMap.length < 1) 
                            categorizedOutcomeMapCells[clo['l_outcome_id']] += '<td></td>'
                        else {
                            mappingScale = outcomeMap[plo['pl_outcome_id']][clo['l_outcome_id']];
                            if (mappingScale) {
                                mapTd = `<td class="text-center align-middle" style="background-color:${mappingScale['colour']}">${mappingScale['abbreviation']}</td>`;
                                categorizedOutcomeMapCells[clo['l_outcome_id']] += mapTd;
                            } else {
                                mapTd = `<td></td>`;
                                categorizedOutcomeMapCells[clo['l_outcome_id']] += mapTd;
                            }
                        }                            
                    });
                    
                    categorizedPLOCells += td;
                })
            }
        });

        uncategorizedHeaderCells = ``;
        uncategorizedPLOCells = ``;
        uncategorizedOutcomeMapCells = [];
        if (Object.keys(uncategorizedPLOs).length > 0) {
            uncategorizedHeaderCells = `
                <th class="table-active w-auto text-center" colspan="${Object.keys(uncategorizedPLOs).length}" style="min-width:5%; white-space:nowrap;overflow:hidden;text-overflow:ellipsis">Uncategorized PLOs</th>
            `;
            for ([key, plo] of Object.entries(uncategorizedPLOs)) {
                if (plo['plo_shortphrase']) {
                    td = `
                    <td style="height:0; text-align: left;">
                        ${plo['plo_shortphrase']}
                    </td>`;
                } else {
                    td = `
                    <td style="height:0; text-align: left;">
                        ${plo['pl_outcome']}
                    </td>`;
                }
                uncategorizedPLOCells += td;
                // TODO: loop over clos, check if this plo has been mapped
                clos.forEach((clo, index) => {
                    if (!uncategorizedOutcomeMapCells[clo['l_outcome_id']])
                        uncategorizedOutcomeMapCells[clo['l_outcome_id']] = '';

                    if (outcomeMap.length < 1) 
                        uncategorizedOutcomeMapCells[clo['l_outcome_id']] += '<td></td>'
                    else {
                        if (!(plo['pl_outcome_id'] in outcomeMap)) {
                            mapTd = `<td></td>`;
                            categorizedOutcomeMapCells[clo['l_outcome_id']] += mapTd;
                        } else {
                            if (!(clo['l_outcome_id'] in outcomeMap[plo['pl_outcome_id']])) {
                                mapTd = `<td></td>`;
                                categorizedOutcomeMapCells[clo['l_outcome_id']] += mapTd;
                            } else {
                                mappingScale = outcomeMap[plo['pl_outcome_id']][clo['l_outcome_id']];
                                mapTd = `<td class="text-center align-middle" style="background-color:${mappingScale['colour']}">${mappingScale['abbreviation']}</td>`;
                                categorizedOutcomeMapCells[clo['l_outcome_id']] += mapTd;
                            }
                        }
                    }
                });
            }
        }

        outcomeMapTableCategoriesRowHTML = `
            <tr>
                <th></th>
                ${categoryHeaderCells}
                ${uncategorizedHeaderCells}
            </tr>
        `;

        outcomeMapTablePLOsRowHTML = `
            <tr>
                <td></td>
                ${categorizedPLOCells}
                ${uncategorizedPLOCells}
            </tr>
        `;
        emptyRow = '';


        outcomeMapTableBodyHTML = ``;
        clos.forEach((clo, index) => {
            if (clo['clo_shortphrase'])
                cloCellText = clo["clo_shortphrase"];
            else 
                cloCellText = clo["l_outcome"];

            if (!categorizedOutcomeMapCells[clo['l_outcome_id']])
                categorizedOutcomeMapCells[clo['l_outcome_id']] = '';

            if (!uncategorizedOutcomeMapCells[clo['l_outcome_id']])
                uncategorizedOutcomeMapCells[clo['l_outcome_id']] = '';

            tr = `
                <tr>
                    <td class="w-auto"> 
                        ${cloCellText}
                    </td>
                    ${categorizedOutcomeMapCells[clo['l_outcome_id']]}
                    ${uncategorizedOutcomeMapCells[clo['l_outcome_id']]}

                </tr>
            `;

            outcomeMapTableBodyHTML += tr;

        });

        outcomeMapTableHTML = `
        <div class="col-12">
            <div style="overflow: auto;">
                <table class="table table-bordered table-light">
                    <thead>
                        <tr class="table-primary">
                            <th colspan="1" class="w-auto">CLO</th>
                            <th colspan="${plos.length}">Program Learning Outcome</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${outcomeMapTableCategoriesRowHTML}
                        ${outcomeMapTablePLOsRowHTML}
                        ${outcomeMapTableBodyHTML}
                    </tbody>
                </table>
            </div>
        </div>
        `;
        return outcomeMapTableHTML;
    }

    function getMappingScalesHTML(mappingScales) {
        if (mappingScales.length > 0) {
            mappingScalesTableBodyHTML = ``;
            mappingScales.forEach((mappingScale, index) => {
                row = `
                    <tr>
                        <td>
                            <div style="background-color:${mappingScale['colour']};height: 10px; width: 10px;"></div>
                            ${mappingScale['title']}<br>
                            (${mappingScale['abbreviation']})
                        </td>
                        <td>
                            ${mappingScale['description']}
                        </td>
                    </tr>
                `;
                mappingScalesTableBodyHTML += row;
            });
            mappingScalesTableHTML = `
                <div class="col-12">
                    <table class="table table-bordered table-light">
                        <thead>
                            <tr class="table-primary">
                                <th colspan="2">Mapping Scale</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${mappingScalesTableBodyHTML}
                        </tbody>
                    </table>
                </div>
            `; 
            return mappingScalesTableHTML;
        } else {
            noMappingScalesHTML = `
                <div class="col-12">
                    <div class="alert alert-warning wizard">
                        <i class="bi bi-exclamation-circle-fill"></i>A mapping scale has not been set for this program.                  
                    </div>
                </div>`;
            return noMappingScalesHTML;
        }

    }

    function getCourseAlignmentHTML(courseId, courseAlignment) {
        // course alignment table body
        tbody = ``;
        courseAlignment.forEach(function(learningOutcome){
            // create comma separated string of asssessment methods
            assessmentMethodsText = learningOutcome["assessment_methods"].reduce(function (acc, assessmentMethod, index) { 
                if (index == 0) 
                    return acc + assessmentMethod['a_method'];
                else 
                    return acc + ', ' + assessmentMethod['a_method']
            }, '');
            // create comma separated string of learning activities
            learningActivitiesText = learningOutcome["learning_activities"].reduce(function (acc, learningActivity, index) { 
                if (index == 0) 
                    return acc + learningActivity['l_activity'];
                else 
                    return acc + ', ' + learningActivity['l_activity']
            }, '');
        // course alignment table row
        cloShortphraseHTML = '';
        // check if clo shortphrase is null
        if (learningOutcome['clo_shortphrase'])
            cloShortphraseHTML = `<b>${learningOutcome['clo_shortphrase']}</b><br>`
        row = `
                <tr>
                    <td scope="row">
                        ${cloShortphraseHTML}
                        ${learningOutcome["l_outcome"]}
                    </td>
                    <td>${assessmentMethodsText}</td>
                    <td>${learningActivitiesText}</td>
                </tr>
            `;
            tbody += row;
        });
        // course alignment section
        return `
            <h5 class="fw-bold pt-4 mb-2 col-12 pt-4 mb-4 mt-2">
                Course Alignment                                     
                <button id="removeCourseAlignment" type="button" class="btn btn-danger float-right" onclick="removeSection(this)">Remove Section</button>
                <input hidden name="import_course_settings[importCourseAlignment]" value="${courseId}">

            </h5>            
            <div class="col-12" id="courseAlignmentTable">
                <table class="table table-light table-bordered table " >
                    <thead>
                        <tr class="table-primary">
                            <th class="w-50">Course Learning Outcome</th>
                            <th>Student Assessment Method</th>
                            <th>Teaching and Learning Activity</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${tbody}
                    </tbody>
                </table>
            </div>
        `;
    }

    function removeSection(btn){
        $(btn.parentNode.parentNode).empty();
    }

    function expandSection(element) {
        // get the height of the element's inner content, regardless of its actual size
        var sectionHeight = element.scrollHeight;
        
        // have the element transition to the height of its inner content
        element.style.height = sectionHeight + 'px';

        // when the next css transition finishes (which should be the one we just triggered)
        element.addEventListener('transitioned', function(e) {
            // remove this event listener so it only gets triggered once
            element.removeEventListener('transitioned', arguments.callee);
            
            // remove "height" from the element's inline styles, so it can return to its initial value
            element.style.height = null;
        });
        
        // mark the section as "currently not collapsed"
        element.setAttribute('data-collapsed', 'false');
    }

    function collapseSection(element) {
        // get the height of the element's inner content, regardless of its actual size
        var sectionHeight = element.scrollHeight;

        // temporarily disable all css transitions
        var elementTransition = element.style.transition;
        element.style.transition = '';
        
        // on the next frame (as soon as the previous style change has taken effect),
        // explicitly set the element's height to its current pixel height, so we 
        // aren't transitioning out of 'auto'
        requestAnimationFrame(function() {
            element.style.height = sectionHeight + 'px';
            element.style.transition = elementTransition;
            
            // on the next frame (as soon as the previous style change has taken effect),
            // have the element transition to height: 0
            requestAnimationFrame(function() {
            element.style.height = 0 + 'px';
            });
        });
        
        // mark the section as "currently collapsed"
        element.setAttribute('data-collapsed', 'true');
    }

    // Function changes optional verison of syllabus
    function onChangeCampus() {

        $('.courseInfo').tooltip(
            {
                selector: '.has-tooltip'
            }     
        );

       
        //different statements for each campus
        var okanaganCourseDescription = `
            
                <label for="courseDescription"><h5 class="fw-bold">Course Description</h5></label><span class="requiredBySenateOK"></span>
                <p class="inputFieldDescription">{!! $inputFieldDescriptions['okanaganCourseDescription'] !!}</p>
                <div id="formatDesc" class="collapsibleNotes btn-primary rounded-3" style="overflow:hidden;transition:height 0.3s ease-out;height:auto" data-collapsed="false">
                <i class="bi bi-exclamation-triangle-fill fs-5 pl-2 pr-2 pb-1"></i> <span class="fs-6">Place each paragraph
                    on a new line for the best formatting
                    results.</span>
                </div>
                <textarea style="height:125px" maxlength="7500" oninput="validateMaxlength()" onpaste="validateMaxlength()"  name = "courseDesc" class ="form-control" type="date" data-formatnoteid="formatDesc" form="sylabusGenerator">{{isset($okanaganSyllabus) ? $okanaganSyllabus->course_description : ''}}</textarea>`;
                

        
        var vancouverOptionalListDesc = `
            <p class="inputFieldDescription">
                The below are suggested sections to include in your syllabus which communicate various resources on campus that support student success.
                <a href="https://senate.ubc.ca/policies-resources-support-student-success/" target="_blank" rel="noopener noreferrer">Language taken from the UBC Vancouver senate website.</a>
            </p>`;
        
        var okanaganOptionalListDesc = `
            <p class="inputFieldDescription">
                The below are suggested sections to include in your syllabus which communicate various resources on campus that support student success.
                <a href="https://senate.ubc.ca/okanagan/forms/" target="_blank" rel="noopener noreferrer">Language taken from the UBC Okanagan senate website</a> and other campus partners. Instructors may choose to add or edit this content as relevant.
            </p>`;

         // list of vancouver syllabus resources
        var vancouverOptionalList = `
            @if (!isset($selectedVancouverSyllabusResourceIds)) 
                @foreach($vancouverSyllabusResources as $index => $vSyllabusResource)
                    <div class="col-6">
                        <input class="form-check-input " id="{{$vSyllabusResource->id_name}}" type="checkbox" name="vancouverSyllabusResources[{{$vSyllabusResource->id}}]" value="{{$vSyllabusResource->id_name}}" checked>
                        <label class="form-check-label mb-2" for="{{$vSyllabusResource->id_name}}">{{$vSyllabusResource->title}}</label>   
                    </div>
                @endforeach
            @else
                @foreach($vancouverSyllabusResources as $index => $vSyllabusResource)
                    <div class="col-6">
                        <input class="form-check-input" id="{{$vSyllabusResource->id_name}}" type="checkbox" name="vancouverSyllabusResources[{{$vSyllabusResource->id}}]" value="{{$vSyllabusResource->id_name}}" {{in_array($vSyllabusResource->id, $selectedVancouverSyllabusResourceIds) ? 'checked' : ''}}>
                        <label class="form-check-label  mb-2" for="{{$vSyllabusResource->id_name}}">{{$vSyllabusResource->title}}</label>   
                    </div>
                @endforeach
            @endif

            `;
        // list of okanagan syllabus resources
        var okanaganOptionalList = `
            @if (!isset($selectedOkanaganSyllabusResourceIds)) 
                @foreach($okanaganSyllabusResources as $index => $oSyllabusResource)
                    <div class="col-6">
                        <input class="form-check-input " id="{{$oSyllabusResource->id_name}}" type="checkbox" name="okanaganSyllabusResources[{{$oSyllabusResource->id}}]" value="{{$oSyllabusResource->id_name}}" checked>
                        <label class="form-check-label mb-2" for="{{$oSyllabusResource->id_name}}">{{$oSyllabusResource->title}}</label>   
                    </div>
                @endforeach
            @else
                @foreach($okanaganSyllabusResources as $index => $oSyllabusResource)
                    <div class="col-6 ">
                        <input class="form-check-input " id="{{$oSyllabusResource->id_name}}" type="checkbox" name="okanaganSyllabusResources[{{$oSyllabusResource->id}}]" value="{{$oSyllabusResource->id_name}}" {{in_array($oSyllabusResource->id, $selectedOkanaganSyllabusResourceIds) ? 'checked' : ''}}>
                        <label class="form-check-label mb-2" for="{{$oSyllabusResource->id_name}}">{{$oSyllabusResource->title}}</label>   
                    </div>
                @endforeach
            @endif
            `;

        var courseCredit = `
            <label for="courseCredit">Credit Value <span class="requiredField">*</span></label>
            <input maxlength="2" oninput="validateMaxlength()" onpaste="validateMaxlength()" name = "courseCredit" class ="form-control" type="number" min="0" step="1"placeholder="E.g. 3" required value="{{isset($vancouverSyllabus) ? $vancouverSyllabus->course_credit : ''}}">
            <div class="invalid-tooltip">
                Please enter the course course credits.
            </div>
            `;
        
        var officeLocation = `
            <label for="officeLocation">Office Location <span class="requiredField">*</span></label>
            <i class="bi bi-info-circle-fill has-tooltip"  data-bs-placement="right" title="{{$inputFieldDescriptions['officeLocation']}}"></i>
            <input maxlength="191" oninput="validateMaxlength()" onpaste="validateMaxlength()" name = "officeLocation" class ="form-control" type="text" placeholder="E.g. WEL 140" value="{{isset($vancouverSyllabus) ? $vancouverSyllabus->office_location : ''}}" required>
            <div class="invalid-tooltip">
                Please enter your office location.
            </div>

            `;

        var courseDescription = `
            
                <label for="courseDescription">Course Description</label>
                <i class="bi bi-info-circle-fill has-tooltip"  data-bs-placement="right" title="{{$inputFieldDescriptions['courseDescription']}}"></i>
                <textarea maxlength="7500" oninput="validateMaxlength()" onpaste="validateMaxlength()"  name = "courseDescription" class ="form-control" type="date" form="sylabusGenerator">{{isset($vancouverSyllabus) ? $vancouverSyllabus->course_description : ''}}</textarea>
            

            `;

        var courseContacts = `
            <label for="courseContacts"><h5 class="fw-bold">Contacts</h5></label>
            <span class="requiredBySenate"></span>
            <p class="inputFieldDescription">{{$inputFieldDescriptions['courseContacts']}}</p>
            <div id="formatContacts" class="collapsibleNotes btn-primary rounded-3" style="overflow:hidden;transition:height 0.3s ease-out;height:auto" data-collapsed="false">
                <i class="bi bi-exclamation-triangle-fill fs-5 pl-2 pr-2 pb-1"></i> <span class="fs-6">Place each entry on a new line for the best formatting results.</span>                                        
            </div>                                            
            <textarea style="height:125px" maxlength="7500" oninput="validateMaxlength()" onpaste="validateMaxlength()"  id="courseContacts" data-formatnoteid="formatContacts" name = "courseContacts" placeholder="E.g. Professor, Jane Doe, jane.doe@ubc.ca, +1 234 567 8900, ... &#10;Teaching Assistant, John Doe, john.doe@ubc.ca, ..."class ="form-control" type="date" form="sylabusGenerator">{{isset($vancouverSyllabus) ? $vancouverSyllabus->course_contacts : ''}}</textarea>
            `;

        var coursePrereqs = `
                <label for="coursePrereqs"><h5 class="fw-bold">Course Prerequisites</h5></label>
                <span class="requiredBySenate"></span>
                <p class="inputFieldDescription">{{$inputFieldDescriptions['coursePrereqs']}}</p>
                <div id="formatPrereqs" class="collapsibleNotes btn-primary rounded-3" style="overflow:hidden;transition:height 0.3s ease-out;height:auto" data-collapsed="false">
                    <i class="bi bi-exclamation-triangle-fill fs-5 pl-2 pr-2 pb-1"></i> <span class="fs-6">Place each entry on a new line for the best formatting results.</span>                                        
                </div>                                            
                <textarea style="height:125px" maxlength="7500" oninput="validateMaxlength()" onpaste="validateMaxlength()"  id="coursePrereqs" data-formatnoteid="formatPrereqs"name = "coursePrereqs" placeholder="E.g. CPSC 210 or EECE 210 or CPEN 221 &#10;E.g. CPSC 121 or MATH 220"class ="form-control" type="text" form="sylabusGenerator" >{{isset($vancouverSyllabus) ? $vancouverSyllabus->course_prereqs : ''}}</textarea>
            `;

        var courseCoreqs = `
                <label for="courseCoreqs"><h5 class="fw-bold">Course Corequisites</h5></label>
                <span class="requiredBySenate"></span>
                <p class="inputFieldDescription">{{$inputFieldDescriptions['courseCoreqs']}}</p>
                <div id="formatCoreqs"class="collapsibleNotes btn-primary rounded-3" style="overflow:hidden;transition:height 0.3s ease-out;height:auto" data-collapsed="false" >
                    <i class="bi bi-exclamation-triangle-fill fs-5 pl-2 pr-2 pb-1"></i> <span class="fs-6">Place each entry on a new line for the best formatting results.</span>                                        
                </div>                                            
                <textarea style="height:125px" maxlength="7500" oninput="validateMaxlength()" onpaste="validateMaxlength()"  id = "courseCoreqs" data-formatnoteid="formatCoreqs"placeholder="E.g. CPSC 107 or CPSC 110 &#10;E.g. CPSC 210" name = "courseCoreqs" class ="form-control" type="text" form="sylabusGenerator">{{isset($vancouverSyllabus) ? $vancouverSyllabus->course_coreqs : ''}}</textarea>
            `;
        var courseInstructorBio = `
            <label for="courseInstructorBio"><h5 class="fw-bold">Course Instructor Biographical Statement</h5></label>
            <p class="inputFieldDescription">{{$inputFieldDescriptions['instructorBioStatement']}}</p>
            <div id="formatCIB" class="collapsibleNotes btn-primary rounded-3" style="overflow:hidden;transition:height 0.3s ease-out;height:auto" data-collapsed="false">
                <i class="bi bi-exclamation-triangle-fill fs-5 pl-2 pr-2 pb-1"></i> <span class="fs-6">Place each paragraph
                    on a new line for the best formatting
                    results.</span>
                </div> 
            <textarea data-formatnoteid="formatCIB" style="height:125px" maxlength="7500" oninput="validateMaxlength()" onpaste="validateMaxlength()"  id = "courseInstructorBio" name = "courseInstructorBio" class ="form-control" form="sylabusGenerator" spellcheck="true">{{isset($vancouverSyllabus) ? $vancouverSyllabus->instructor_bio : ''}}</textarea>
            
            `;     
        
        var courseStructure = `
                <label for="courseStructure"><h5 class="fw-bold">Course Structure</h5></label>
                <span class="requiredBySenate"></span>
                <p class="inputFieldDescription">{{$inputFieldDescriptions['courseStructure']}}</p>
                <div id="formatCourseStructure" class="collapsibleNotes btn-primary rounded-3" style="overflow:hidden;transition:height 0.3s ease-out;height:auto" data-collapsed="false">
                <i class="bi bi-exclamation-triangle-fill fs-5 pl-2 pr-2 pb-1"></i> <span class="fs-6">Place each entry
                    on a new line for the best formatting
                    results.</span>
                </div>
                <textarea data-formatnoteid="formatCourseStructure" maxlength="7500" style="height:125px" oninput="validateMaxlength()" onpaste="validateMaxlength()"  name = "courseStructure" class ="form-control" type="text" form="sylabusGenerator" spellcheck="true">{{isset($vancouverSyllabus) ? $vancouverSyllabus->course_structure : ''}}</textarea>
            `;

        var learningAnalytics = `
                <label for="learningAnalytics"><h7 class="fw-bold">Learning Analytics</h7></label>
                <p class="inputFieldDescription">{{$inputFieldDescriptions['learningAnalytics']}}</p>
                <div id="formatLAnal" class="collapsibleNotes btn-primary rounded-3" style="overflow:hidden;transition:height 0.3s ease-out;height:auto" data-collapsed="false">
                <i class="bi bi-exclamation-triangle-fill fs-5 pl-2 pr-2 pb-1"></i> <span class="fs-6">Place each entry
                    on a new line for the best formatting
                    results.</span>
                </div> 
                <textarea data-formatnoteid="formatLAnal" style="height:125px" maxlength="7500" oninput="validateMaxlength()" onpaste="validateMaxlength()"  id="learningAnalytics" name = "learningAnalytics" class ="form-control" type="text" form="sylabusGenerator">{{isset($vancouverSyllabus) ? $vancouverSyllabus->learning_analytics : ''}}</textarea>
            `;
        var courseFormat = `
                <label for="courseFormat"><h5 class="fw-bold">Course Structure</h5></label><span class="requiredBySenateOK"></span>
                <p class="inputFieldDescription">{!! $inputFieldDescriptions['courseStructureOK'] !!}</p>
                <div id="formatFormat" class="collapsibleNotes btn-primary rounded-3" style="overflow:hidden;transition:height 0.3s ease-out;height:auto" data-collapsed="false">
                <i class="bi bi-exclamation-triangle-fill fs-5 pl-2 pr-2 pb-1"></i> <span class="fs-6">Place each entry
                    on a new line for the best formatting
                    results.</span>
                </div>
                <textarea data-formatnoteid="formatFormat" style="height:125px" oninput="validateMaxlength()" onpaste="validateMaxlength()" maxlength="7500" name = "courseFormat" class ="form-control" type="text" form="sylabusGenerator" spellcheck="true">{{ isset($okanaganSyllabus) ? $okanaganSyllabus->course_format: ''}}</textarea>
            `;
        var courseOverview = `
                <label for="courseOverview"><h5 class="fw-bold">Course Overview, Content and Objectives</h5></label>
                <div id="formatOverview" class="collapsibleNotes btn-primary rounded-3" style="overflow:hidden;transition:height 0.3s ease-out;height:auto" data-collapsed="false">
                <i class="bi bi-exclamation-triangle-fill fs-5 pl-2 pr-2 pb-1"></i> <span class="fs-6">Place each entry
                    on a new line for the best formatting
                    results.</span>
                </div>
                <textarea data-formatnoteid="formatOverview" style="height:125px" oninput="validateMaxlength()" onpaste="validateMaxlength()" maxlength="7500" name = "courseOverview" class ="form-control" type="text" form="sylabusGenerator" spellcheck="true">{{ isset($okanaganSyllabus) ? $okanaganSyllabus->course_overview : ''}}</textarea>
            `;

        var requiredBySenateLabel = `
            <span class="d-inline-block has-tooltip ml-2 mr-2" tabindex="0" data-bs-toggle="tooltip" data-bs-placement="top" title="This section is required in your syllabus under Vancouver Senate policy V-130">
                <button type="button" class="btn btn-danger btn-sm mb-2 disabled" style="font-size:10px;">Required by policy</button> 
            </span>
            `;

        var requiredBySenateLabelOK = `
            <span class="d-inline-block has-tooltip ml-2 mr-2" tabindex="0" data-bs-toggle="tooltip" data-bs-placement="top" title="This section is required in your syllabus under Okanagan Senate policy">
                <button type="button" class="btn btn-danger btn-sm mb-2 disabled" style="font-size:10px;">Required by policy</button> 
            </span>
            `;
        var uniPolicyVan = `
            <label for="learningMaterials"><h5 class="fw-bold">University Policies</h5></label>
            <span class="requiredBySenate"></span>
            <p class="inputFieldDescription">{!! $inputFieldDescriptions['uniPolicy'] !!}</p> 
            <br>
            <div class="col-12">
            <blockquote> UBC provides resources to support student learning and to maintain healthy lifestyles but recognizes that sometimes crises arise and so there are additional resources to access including those for survivors of sexual violence. UBC values respect for the person and ideas of all members of the academic community. Harassment and discrimination are not tolerated nor is suppression of academic freedom. UBC provides appropriate accommodation for students with disabilities and for religious observances. UBC values academic honesty and students are expected to acknowledge the ideas generated by others and to uphold the highest academic standards in all of their actions.
            <br><br>Details of the policies and how to access support are available on <a href="https://senate.ubc.ca/policies-resources-support-student-success/">the UBC Senate Website</a>.</blockquote>
            </div>
         `;

         var crStatement = `
            <label for="copyright"><h5 class="fw-bold">© Copyright</h5></label>
            <br>
            <div class="col-12">
                <blockquote> All materials of this course (course handouts, lecture slides, assessments, course readings, etc.) are the intellectual property of the Course Instructor or licensed to be used in this course by the copyright owner. Redistribution of these materials by any means without permission of the copyright holder(s) constitutes a breach of copyright and may lead to academic discipline.</blockquote>
                <div class="col-6">
                @if(!empty($syllabus))
                    @if($syllabus->copyright)
                        <input class="form-check-input " id="copyright" type="checkbox" name="copyright" value="1" checked>
                    @else
                        <input class="form-check-input " id="copyright" type="checkbox" name="copyright" value="1">
                    @endif
                    <label class="form-check-label mb-2" for="copyright">Include in Syllabus</label>   
                @endif
                </div>
            </div>
         `;

         var landAcknowledgementV = `
            <label for="landAcknowledgement"><h5 class="fw-bold">Land Acknowledgement</h5></label>
            <br>
            <div class="col-12">
                <blockquote> UBC’s Point Grey Campus is located on the traditional, ancestral, and unceded territory of the xwməθkwəy̓əm (Musqueam) people. The land it is situated on has always been a place of learning for the Musqueam people, who for millennia have passed on their culture, history, and traditions from one generation to the next on this site.</blockquote>
                <div class="col-6">
                @if(!empty($syllabus))
                    @if($syllabus->land_acknow)
                        <input class="form-check-input " id="landAck" type="checkbox" name="landAck" value="1" checked>
                    @else
                        <input class="form-check-input " id="landAck" type="checkbox" name="landAck" value="1">
                    @endif
                    <label class="form-check-label mb-2" for="landAck">Include in Syllabus</label>  
                @else
                    <input class="form-check-input " id="landAck" type="checkbox" name="landAck" value="1">
                    <label class="form-check-label mb-2" for="landAck">Include in Syllabus</label>  
                @endif
                </div>
            </div>
         `;

         var landAcknowledgementO = `
            <label for="landAcknowledgement"><h5 class="fw-bold">Land Acknowledgement</h5></label>
            <br>
            <div class="col-12">
                <blockquote> We respectfully acknowledge the Syilx Okanagan Nation and their peoples, in whose traditional, ancestral, unceded territory UBC Okanagan is situated.</blockquote>
                <div class="col-6">
                @if(!empty($syllabus))
                    @if($syllabus->land_acknow)
                        <input class="form-check-input " id="landAck" type="checkbox" name="landAck" value="1" checked>
                    @else
                        <input class="form-check-input " id="landAck" type="checkbox" name="landAck" value="1">
                    @endif
                    <label class="form-check-label mb-2" for="landAck">Include in Syllabus</label>  
                @else
                    <input class="form-check-input " id="landAck" type="checkbox" name="landAck" value="1">
                    <label class="form-check-label mb-2" for="landAck">Include in Syllabus</label>  
                @endif
                </div>
            </div>
         `;

         var optionalStatements =`

         <h5  class="fw-bold">Additional UBC language regarding academic integrity, academic misconduct, and use of generative artificial intelligence (GenAI)</h5>
                @if(!empty($syllabus))
                    <div id="optionalSyllabusDesc"></div>
                @else
                    <p class="inputFieldDescription"> You may choose to add the below statements to your syllabi to clarify expectations in your course. For additional language available visit <a href="https://academicintegrity.ubc.ca/generative-ai-syllabus/">UBC's Academic Integrity website</a>.
                        
                    </p>
                @endif
            <div class="form-check m-4">
                <div class="row" id="optionalSyllabus"></div>
            </div>
            `;
            var statementUBCValues =`
            
            <label for="statementUBCValues"><h5 class="fw-bold">Statement of UBC Values</h5></label><span class="requiredBySenateOK"></span>
            <br>
            <div class="col-12">
                <blockquote> UBC creates an exceptional learning environment that fosters global citizenship, advances a civil and sustainable society, and supports outstanding research to serve the people of British Columbia, Canada, and the world. UBC’s core values are excellence, integrity, respect, academic freedom, and accountability.</blockquote>
            </div>
            `;

            var statementStudentSupport =`
            
            <label for="statementStudentSupport"><h5 class="fw-bold">Statement regarding Resources to Support Student Success</h5></label><span class="requiredBySenateOK"></span>
            <br>
            <div class="col-12">
                <blockquote> Visit <a href="https://students.ok.ubc.ca/support/"> the Student Support and Resources page</a> to find one-on-one help or explore resources to support your experience at UBC Okanagan, as well as many other campus services available to all students. </blockquote>
            </div>
            `;

            var policiesAndRegulations =`
            
            <label for="policiesAndRegulations"><h5 class="fw-bold">Statement on Policies and Regulations</h5></label><span class="requiredBySenateOK"></span>
            <br>
            <div class="col-12">
                <blockquote> Visit <a href="https://okanagan.calendar.ubc.ca/campus-wide-policies-and-regulations">UBC Okanagan's Academic Calendar</a> for a list of campus-wide regulations and policies, as well as term <a href="https://okanagan.calendar.ubc.ca/dates-and-deadlines">dates and deadlines</a>.</blockquote>
            </div>
            `;

        var courseSectionOK =  `
            <label for="endTime">Course Section</label>
            <input oninput="validateMaxlength()" onpaste="validateMaxlength()" maxlength="20" id = "courseSection" name = "courseSection" class ="form-control" type="text" placeholder="E.g. 001" value="{{ !empty($syllabus) ? $syllabus->course_section : ''}}" >`;
        
        // get campus select element
        var campus = $('#campus');
        // check if its value is 'V'
        if(campus.val() == 'V'){
            // add data specific to vancouver campus
            //$('#optionalSyllabusDesc').html(vancouverOptionalListDesc);
            //$('#optionalSyllabus').html(vancouverOptionalList);
            $('#courseCredit').html(courseCredit);
            $('#officeLocation').html(officeLocation);
            $('#courseContacts').html(courseContacts);
            $('#courseContacts').removeClass('m-0 p-0');
            $('#coursePrereqs').html(coursePrereqs);
            $('#coursePrereqs').removeClass('m-0 p-0');
            $('#courseCoreqs').html(courseCoreqs);
            $('#courseCoreqs').removeClass('m-0 p-0');
            $('#courseStructure').html(courseStructure);
            $('#courseStructure').removeClass('m-0 p-0');
            $('#courseInstructorBio').html(courseInstructorBio);
            $('#courseInstructorBio').removeClass('m-0 p-0');
            $('#courseDescription').html(courseDescription);
            $('#learningAnalytics').html(learningAnalytics);
            $('#uniPolicy').html(uniPolicyVan);
            //$('#crStatement').html(crStatement);
            $('#landAcknowledgement').html(landAcknowledgementV);
            $('.requiredBySenate').html(requiredBySenateLabel);
           

            // remove data specific to okanangan campus
            $('#courseFormat').empty();
            $('#courseFormat').addClass('m-0 p-0');
            $('#courseOverview').empty();
            $('#courseDesc').empty();
            $('#courseOverview').addClass('m-0 p-0');
            $('#optionalStatements').empty();
            $('#courseSectionOK').empty();
            $('.requiredBySenateOK').empty();
            $('#statementUBCValues').empty();
            $('#statementStudentSupport').empty();
            $('#policiesAndRegulations').empty();
            // update faculty dropdown
            setFaculties('Vancouver');
        }   
        else
        {
            // add data specific to okanagan campus
            $('#optionalStatements').html(optionalStatements);
            $('#optionalSyllabusDesc').html(okanaganOptionalListDesc);
            $('#optionalSyllabus').html(okanaganOptionalList);
            $('#courseFormat').html(courseFormat);
            $('#courseFormat').removeClass('m-0 p-0');
            $('#courseOverview').html(courseOverview);
            $('#courseDesc').html(okanaganCourseDescription);
            $('#courseOverview').removeClass('m-0 p-0');
            $('#courseSectionOK').html(courseSectionOK);
            $('#statementUBCValues').html(statementUBCValues);
            $('#statementStudentSupport').html(statementStudentSupport);
            $('#policiesAndRegulations').html(policiesAndRegulations);
            $('.requiredBySenateOK').html(requiredBySenateLabelOK);

            // remove data specific to vancouver campus
            $('#courseCredit').empty();
            $('#officeLocation').empty();
            $('#courseContacts').empty();
            $('#courseContacts').addClass('m-0 p-0');
            $('#coursePrereqs').empty();
            $('#coursePrereqs').addClass('m-0 p-0');
            $('#courseCoreqs').empty();
            $('#courseCoreqs').addClass('m-0 p-0');
            $('#courseStructure').empty();
            $('#courseStructure').addClass('m-0 p-0');
            $('#courseInstructorBio').empty();
            $('#courseInstructorBio').addClass('m-0 p-0');
            $('#courseDescription').empty();
            $('#learningAnalytics').empty();
            $('.requiredBySenate').empty();
            $('#uniPolicy').empty();
            $('#crStatement').empty();
            $('#landAcknowledgement').html(landAcknowledgementO);

            // update faculty dropdown
            setFaculties('Okanagan');
        }

        var formatNotes = document.querySelectorAll('.collapsibleNotes').forEach(function(note) {
            // collapse sections when document is ready
            var isCollapsed = note.dataset.collapsed === 'true';
            if (!isCollapsed) {
                collapseSection(note);
            }
        });
    }

    // activates faculty dropdown with faculties from the given campus
    function setFaculties(campus) {
        $('#faculty').removeAttr('disabled');
        $('#faculty').empty();
        $('#department').empty();


        placeholderFaculty = `<option value="" class="text-muted" > -- Faculty -- </option>`;
        placeholderDept = `<option value="" class="text-muted"> -- Department -- </option>`;
        
        $('#faculty').append(placeholderFaculty);
        $('#department').append(placeholderDept);

        if (campus == 'Vancouver') {
            vFaculties.forEach(function(faculty, index) {
                $('#faculty').append($(`<option name="${faculty.faculty_id}" />`).val(faculty.faculty).text(faculty.faculty));
                // change selected value if syllabus has a faculty
                if (faculty.faculty == syllabus.faculty) {
                    $('#faculty').val(faculty.faculty);
                    setDepartments(faculty.faculty_id);
                }
            });
        } else if (campus == 'Okanagan') {
            oFaculties.forEach(function(faculty, index) {
                $('#faculty').append($(`<option name="${faculty.faculty_id}" />`).val(faculty.faculty).text(faculty.faculty));
                // change selected value if syllabus has a faculty
                if (faculty.faculty == syllabus.faculty) {
                    $('#faculty').val(syllabus.faculty);
                    setDepartments(faculty.faculty_id);
                }
            });
        }
    }

    // activates department dropdown with departments from the given faculty
    function setDepartments(facultyId) {
        $('#department').removeAttr('disabled');
        $('#department').empty();
        placeholder = `<option value="" class="text-muted"> -- Department -- </option>`;
        $('#department').append(placeholder);
        filteredDepartments = departments.filter(department => {
            return department.faculty_id == facultyId;
        });

        filteredDepartments.forEach(function(department, index) {
            $('#department').append($('<option />').val(department.department).text(department.department));
            // change selected value if syllabus has a faculty
            if (department.department == syllabus.department) {
                $('#department').val(syllabus.department);
            }

        });
    }
    
    //This method is used to make sure that the proper amount of characters are entered so it doesn't exceed the max character limits
    function validateMaxlength(e){
        //Whitespaces are counted as 1 but character wise are 2 (\n).
        var MAX_LENGTH = event.target.getAttribute("maxlength");
        var currentLength = event.target.value.length;
        var whiteSpace = event.target.value.split(/\n/).length;
        if((currentLength+(whiteSpace))>MAX_LENGTH)
        { 
            //Goes to MAX_LENGTH-(whiteSpace)+1 because it starts at 1
            event.target.value = event.target.value.substr(0,MAX_LENGTH-(whiteSpace)+1);	        
        }
    } 
</script>
<!-- Include script to reorder table rows-->
<script src="{{ asset('js/drag_drop_tbl_row.js') }}"></script>
<!-- Include stylesheet to style reorder table rows -->
<link rel="stylesheet" href="{{ asset('css/drag_drop_tbl_row.css' ) }}">
@endsection