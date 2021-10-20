
@extends('layouts.app')

@section('content')

@if (!empty($syllabus)) 
    <div class="row my-2">
        <div class="col">
            <button type="button" style="width:200px" class="btn btn-success btn-sm float-right" data-toggle="modal" data-target="#duplicateConfirmation">Duplicate Syllabus</button>
            <!-- Duplicate Confirmation Modal -->
            <div class="modal fade" id="duplicateConfirmation" tabindex="-1" role="dialog" aria-labelledby="duplicateConfirmation" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Duplicate Syllabus</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="{{ route('syllabus.duplicate', $syllabus->id) }}" method="GET">
                            @csrf
                            {{method_field('GET')}}
                            <div class="modal-body">

                                <div class="form-group row">
                                    <label for="course_code" class="col-md-3 col-form-label text-md-right"><span class="requiredField">*</span>Course Code</label>
                                    <div class="col-md-8">
                                        <input id="course_code" type="text" pattern="[A-Za-z]+" minlength="1" maxlength="4" class="form-control @error('course_code') is-invalid @enderror" value="{{$syllabus->course_code}}" name="course_code" required autofocus>
                                        @error('course_code')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                        <small id="helpBlock" class="form-text text-muted">
                                            Maximum of Four letter course code e.g. SUST, ASL, COSC etc.
                                        </small>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="course_num" class="col-md-3 col-form-label text-md-right"><span class="requiredField">*</span>Course Number</label>
                                    <div class="col-md-8">
                                        <input id="course_num" type="number" max="699" min="100" pattern="[0-9]*" class="form-control @error('course_num') is-invalid @enderror" name="course_num" value="{{$syllabus->course_num}}" required autofocus>
                                        @error('course_num')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="course_title" class="col-md-3 col-form-label text-md-right"><span class="requiredField">*</span>Course Title</label>
                                    <div class="col-md-8">
                                        <input id="course_title" type="text" class="form-control @error('course_title') is-invalid @enderror" name="course_title" value="{{$syllabus->course_title}} - Copy" required autofocus>
                                        @error('course_title')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>

                            </div>
                            <div class="modal-footer">
                                <button style="width:60px" type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
                                <button style="width:80px" type="submit" class="btn btn-success btn-sm">Duplicate</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

<div id="app">
    <div class="home">
        <div class="card mt-4">
            <div class="card-header wizard text-start">
                <h2>
                    Syllabus Generator 
                </h2>
                <!-- Import existing course Modal -->
                <div class="modal fade" id="importExistingCourse" tabindex="-1" role="dialog" aria-labelledby="importExistingCourse" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document" style="width:1250px;">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="importExistingCourse">Import an existing course</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>

                            <div class="modal-body" style="height: auto;">
                                <p style="text-align:left;">Choose a course from your list of existing courses to import relevant course information.</p>
                                <table class="table table-hover dashBoard">
                                    <thead>
                                        <tr>
                                            <th scope="col"></th>
                                            <th scope="col">Course Title</th>
                                            <th scope="col">Course Code</th>
                                            <th scope="col">Semester</th>
                                        </tr>
                                    </thead>
                                    
                                    @foreach ($myCourses as $index => $course)
                                    <tbody>
                                        <tr>
                                            <th scope="row">
                                                <input value = {{$course->course_id}} class="form-check-input" type="radio" name="importCourse" id="importCourse"
                                                form = "sylabusGenerator" style="margin-left: 0px">
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
                                                @else
                                                Summer {{$course->year}} Term 2
                                                @endif
                                            </td>
                                        </tr>
                                    </tbody>
                                    @endforeach
                                </table>
                            </div>

                            <div class="modal-footer">
                                <button style="width:60px" type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
                                <button style="width:60px" type="button" class="btn btn-primary btn-sm" id="importButton" name="importButton" data-dismiss="modal">Import</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End of Import an Existing Course Modal -->
            </div>

            <div class="card-body">
                <h6 class="card-subtitle mb-4 lh-lg fs-6 ml-3 mt-2 mr-2">
                    To assist faculty in preparing their syllabi, this generator follows the policies, guidelines and templates provided by the <a target="_blank"href="https://senate.ubc.ca/okanagan/curriculum/forms">UBC Okanagan <i class="bi bi-box-arrow-up-right"></i></a> and <a target="_blank" href="https://senate.ubc.ca/policies-resources-support-student-success">UBC Vancouver <i class="bi bi-box-arrow-up-right"></i></a> senate. 
                </h6>
                <hr class="w-auto text-secondary mb-4 ml-3 mr-3">
                
                <form class="courseInfo needs-validation" novalidate method="POST" id="sylabusGenerator" action="{{!empty($syllabus) ? action('SyllabusController@save', $syllabus->id) : action('SyllabusController@save')}}">
                    @csrf
                    <div class="container">
                        <div class="row mb-4 mr-2">
                            <div class="col-auto fs-6">
                                <button type="button" class="btn btn-primary bg-primary text-white m-0" data-toggle="modal" data-target="#importExistingCourse">Import an existing course <i class="bi bi-box-arrow-in-down-left pl-2"></i></button>
                            </div>
                            <div class="col-3 fs-6 ">
                                <!-- Campus dropdown -->
                                <div class="row">
                                    <label for="campus" class="col-auto col-form-label requiredField">*</label>
                                    <select class="form-select form-select-sm col" id="campus" name="campus" form="sylabusGenerator" required>
                                        <option disabled selected value=""> -- Campus -- </option>
                                        <option value="O">UBC Okanagan</option>
                                        <option value="V">UBC Vancouver</option>
                                    </select>
                                </div>
                            </div>
                            <!-- land acknowledgement -->
                            <div class="col fs-6 form-check align-self-center ml-5">
                                @if (!empty($syllabus))
                                    <input id="land" class="land form-check-input" type="checkbox" @if ($syllabus->campus == 'O') {{in_array($okanaganSyllabusResources[0]->id, $selectedOkanaganSyllabusResourceIds) ? 'checked' : ''}} @else {{in_array($vancouverSyllabusResources[0]->id, $selectedVancouverSyllabusResourceIds) ? 'checked' : ''}}@endif>
                                    <label for="land" class="form-check-label">Land Acknowledgement</label>
                                @else 
                                    <input id="land" class="land form-check-input" type="checkbox" checked>
                                    <label for="land" class="form-check-label">Land Acknowledgement</label>
                                @endif
                            </div> 
                        </div>
                        <!-- Course Title -->
                        <div class="row mb-3">
                            <div class="col-10">
                                <label for="courseTitle" class="form-label"><span class="requiredField">* </span>Course Title</label>
                                <input spellcheck="true" id = "courseTitle" name = "courseTitle" class ="form-control" type="text" placeholder="E.g. Intro to Software development" required value="{{ !empty($syllabus) ? $syllabus->course_title : '' }}">
                                <div class="invalid-tooltip">
                                    Please enter the course title.
                                </div>
                            </div>
                        </div>
                        <!-- Course Code, Course Number, Course Credit -->
                        <div class="row mb-3">
                            <div class="col-3 ">
                                <label for="courseCode"><span class="requiredField">* </span>Course Code</label>
                                <input id = "courseCode" name = "courseCode" class ="form-control" type="text" placeholder="E.g. CPSC" required value="{{ !empty($syllabus) ? $syllabus->course_code : '' }}">
                                <div class="invalid-tooltip">
                                    Please enter the course code.
                                </div>
                            </div>
                            <div class="col-2">
                                <label for="courseNumber"><span class="requiredField">* </span>Course Number</label>
                                <input id = "courseNumber" name = "courseNumber" class ="form-control" type="text" placeholder="E.g. 310" required value="{{ !empty($syllabus) ? $syllabus->course_num : '' }}">
                                <div class="invalid-tooltip">
                                    Please enter the course number.
                                </div>
                            </div>
                            <div id="courseCredit" class="col-2"></div>
                        </div>
                        <!-- Course Instructor, Course Semester, Course Year -->
                        <div class="row mb-3">
                            <div class="col-5">
                                <label for="courseInstructor"><span class="requiredField">* </span>Course Instructor</label>
                                <input id = "courseInstructor" name = "courseInstructor" class ="form-control" type="text" placeholder="E.g. Dr. J. Doe" required value="{{ !empty($syllabus) ? $syllabus->course_instructor : ''}}">
                                <div class="invalid-tooltip">
                                    Please enter the course instructor.
                                </div>
                            </div>
                            <div class="col-3">
                                <label for="courseSemester" class="form-label"><span class="requiredField">* </span>Course Term</label>
                                <select id="courseSemester" class="form-select" name="courseSemester" required>
                                    <option disabled selected value=""> -- Year -- </option>
                                    <option value="W1" {{!empty($syllabus) ? (($syllabus->course_term == 'W1') ? 'selected=true' : '') : ''}}>Winter Term 1</option>
                                    <option value="W2" {{!empty($syllabus) ? (($syllabus->course_term == 'W2') ? 'selected=true' : '') : ''}}>Winter Term 2</option>
                                    <option value="S1" {{!empty($syllabus) ? (($syllabus->course_term == 'S1') ? 'selected=true' : '') : ''}}>Summer Term 1</option>
                                    <option value="S2" {{!empty($syllabus) ? (($syllabus->course_term == 'S2') ? 'selected=true' : '') : ''}}>Summer Term 2</option>
                                    <option value="O" {{!empty($syllabus) ? (($syllabus->course_term == 'O') ? 'selected=true' : '') : ''}}>Other</option>
                                </select>
                                <div class="invalid-tooltip">
                                    Please enter the course semester.
                                </div>
                            </div>
                            <div class="col-2">
                                <label for="courseYear"><span class="requiredField">* </span>Course Year</label>
                                <select id="courseYear" class="form-select" name="courseYear" required>
                                    <option disabled selected value=""> -- Term -- </option>
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
                        </div>
                        <!-- Course Location, Office Location -->
                        <div class="row mb-3">
                            <div class="col-5">
                                <label for="courseLocation">Course Location</label>
                                <input id = "courseLocation" name = "courseLocation" class ="form-control" type="text" placeholder="E.g. WEL 140" value="{{ !empty($syllabus) ? $syllabus->course_location : ''}}">
                            </div>
                            <div id="officeLocation" class="col-6"></div>
                        </div>
                        <!-- Office Hours -->
                        <div class="row mb-3">
                            <div class="col">
                                <label for="officeHour">Office Hours</label>
                                <i class="bi bi-info-circle-fill" data-toggle="tooltip" data-bs-placement="right" title="{{$inputFieldDescriptions['officeHours']}}"></i>
                                <textarea spellcheck="true" id = "officeHour" name = "officeHour" class ="form-control" type="date" form="sylabusGenerator">{{ !empty($syllabus) ? $syllabus->office_hours : ''}}</textarea>
                            </div>
                        </div>
                        <!-- Other Course Staff -->
                        <div class="row mb-3">
                            <div class="col">
                                <label  for="otherCourseStaff">Other Instructional Staff</label>
                                <i class="bi bi-info-circle-fill" data-toggle="tooltip" data-bs-placement="right" title="{{$inputFieldDescriptions['otherCourseStaff']}}"></i>
                                <span class="requiredBySenate"></span>
                                <div id="formatStaff" class="collapsibleNotes btn-primary rounded-3" style="overflow:hidden;transition:height 0.3s ease-out;height:auto" data-collapsed="false">
                                    <i class="bi bi-exclamation-triangle-fill fs-5 pl-2 pr-2 pb-1"></i> <span class="fs-6">Place each entry on a newline for the best formatting results.</span>                                        
                                </div>                                            
                                <textarea id = "otherCourseStaff" data-formatnoteid="formatStaff" placeholder="E.g. Professor, Dr. Phil, PhD Clinical Psychology, ...&#10;E.g. Instructor, Bill Nye, BS Mechanical Engineering, ..." name = "otherCourseStaff" class ="form-control " form="sylabusGenerator" spellcheck="true">{{ !empty($syllabus) ? $syllabus->other_instructional_staff : ''}}</textarea>
                            </div>
                        </div>
                        <!-- Class Start Time, Class End Time -->
                        <div class="row mb-3">
                            <div class="col-3">
                                <label for="startTime">Class Start Time</label>
                                <input id = "startTime" name = "startTime" class ="form-control" type="text" placeholder="E.g. 1:00 PM" value="{{ !empty($syllabus) ? $syllabus->class_start_time : ''}}">
                            </div>
                            <div class="col-3">
                                <label for="endTime">Class End Time</label>
                                <input id = "endTime" name = "endTime" class ="form-control" type="text" placeholder="E.g. 2:00 PM" value="{{ !empty($syllabus) ? $syllabus->class_end_time : ''}}" >
                            </div>
                        </div>
                        <!-- Class Meeting Days -->
                        <div class="row mb-3">
                            <div class="col ">
                                <label for="classDate">Class Meeting Days</label>
                                <div class="classDate">
                                    <input id="monday" type="checkbox" name="schedule[]" value="Mon">
                                    <label for="monday" class="mr-2">Monday</label>

                                    <input id="tuesday" type="checkbox" name="schedule[]" value="Tue">
                                    <label for="tuesday" class="mr-2">Tuesday</label>

                                    <input id="wednesday" type="checkbox" name="schedule[]" value="Wed">
                                    <label for="wednesday" class="mr-2">Wednesday</label>

                                    <input id="thursday" type="checkbox" name="schedule[]" value= "Thu">
                                    <label for="thursday" class="mr-2">Thursday</label>

                                    <input id="friday" type="checkbox" name="schedule[]" value="Fri">
                                    <label for="friday" class="mr-2">Friday</label>
                                </div>
                            </div>
                        </div>
                        <!-- Course Instructor Biographical Statement -->
                        <div class="row" id="courseInstructorBio"></div>
                        <!-- Course Prerequisites -->
                        <div class="row" id="coursePrereqs"></div>
                        <!-- Course Corequisites -->
                        <div class="row" id="courseCoreqs"></div>
                        <!-- Course Contacts -->
                        <div class="row" id="courseContacts"></div>
                        <!-- Course Structure -->
                        <div class="row" id="courseStructure"></div>
                        <!-- Course Schedule -->
                        <div class="row" id="courseSchedule"></div>
                        <!-- Course Format -->
                        <div class="row" id="courseFormat"></div>
                        <!-- Course Overview -->
                        <div class="row" id="courseOverview"></div>

                        <!-- Learning Outcomes -->
                        <div class="row mb-3">
                            <div class="col ">
                                <label for="learningOutcome">Learning Outcomes</label>
                                <i class="bi bi-info-circle-fill" data-toggle="tooltip" data-bs-placement="right" title="{{$inputFieldDescriptions['learningOutcomes']}}"></i>
                                <span class="requiredBySenate"></span>
                                <p style="color:gray"><i>Upon successful completion of this course, students will be able to...</i></p>
                                <div id="formatCLOs" class="collapsibleNotes btn-primary rounded-3" style="overflow:hidden;transition:height 0.3s ease-out;height:auto" data-collapsed="false">
                                    <i class="bi bi-exclamation-triangle-fill fs-5 pl-2 pr-2 pb-1"></i> <span class="fs-6">Place each entry on a newline for the best formatting results.</span>                                        
                                </div>                                            
                                <textarea id = "learningOutcome" data-formatnoteid="formatCLOs" placeholder="E.g. Define ... &#10;E.g. Classify ..." name = "learningOutcome" class ="form-control" type="date" style="height:125px;" form="sylabusGenerator" spellcheck="true">{{ !empty($syllabus) ? $syllabus->learning_outcomes : ''}}</textarea>
                            </div>
                        </div>
                        <!-- Course Learning Assessments -->
                        <div class="row mb-3">
                            <div class="col">
                                <label for="learningAssessments">Assessments of Learning</label>
                                <i class="bi bi-info-circle-fill" data-toggle="tooltip" data-bs-placement="right" title="{{$inputFieldDescriptions['learningAssessments']}}"></i>
                                <span class="requiredBySenate"></span>
                                <div id="formatAssessments" class="collapsibleNotes btn-primary rounded-3" style="overflow:hidden;transition:height 0.3s ease-out;height:auto" data-collapsed="false">
                                    <i class="bi bi-exclamation-triangle-fill fs-5 pl-2 pr-2 pb-1"></i> <span class="fs-6">Place each entry on a newline for the best formatting results.</span>                                        
                                </div>                                            
                                <textarea id = "learningAssessments" data-formatnoteid="formatAssessments" placeholder="E.g. Presentation, 25%, Dec 1, ... &#10;E.g. Midterm Exam, 25%, Sept 31, ..." name = "learningAssessments" class ="form-control" type="date" style="height:125px;" form="sylabusGenerator" spellcheck="true">{{ !empty($syllabus) ? $syllabus->learning_assessments : ''}}</textarea>
                            </div>
                        </div>
                        <!-- Learning Activities -->
                        <div class="row mb-3">
                            <div class="col">
                                <label for="learningActivities">Learning Activities</label>
                                <i class="bi bi-info-circle-fill" data-toggle="tooltip" data-bs-placement="right" title="{{$inputFieldDescriptions['learningActivities']}}"></i>
                                <span class="requiredBySenate"></span>
                                <div id="formatActivities" class="collapsibleNotes btn-primary rounded-3" style="overflow:hidden;transition:height 0.3s ease-out;height:auto" data-collapsed="false">
                                    <i class="bi bi-exclamation-triangle-fill fs-5 pl-2 pr-2 pb-1"></i> <span class="fs-6">Place each entry on a newline for the best formatting results.</span>                                        
                                </div>                                            
                                <textarea id = "learningActivities" data-formatnoteid="formatActivities" placeholder="E.g. Class participation consists of clicker questions, group discussions ... &#10;E.g. Students are expected to complete class pre-readings ..."name = "learningActivities" class ="form-control" type="date" style="height:125px;" form="sylabusGenerator" spellcheck="true">{{ !empty($syllabus) ? $syllabus->learning_activities : ''}}</textarea>
                            </div>
                        </div>

                        <!-- course schedule table -->
                        <div class="row">
                            <div class="col mb-3">
                                <label for="courseSchedule">Course Schedule</label>
                                <i class="bi bi-info-circle-fill has-tooltip"  data-bs-placement="right" title="{{$inputFieldDescriptions['courseSchedule']}}"></i>
                            </div>
                        </div>
                        <!-- course schedule toolbar -->
                        <div class="row">
                            <table class="table table-sm w-50 text-center table-borderless" style="table-layout:auto;">
                                <tr>
                                    <td>
                                        <button id="createTable" title="Create Table" type="button" class="btn btn-sm btn-secondary fs-6" data-bs-toggle="modal" data-bs-target="#createCourseScheduleTblModal"><i class="bi bi-plus pr-1"></i><i class="bi bi-grid-3x3"></i></button>
                                        <button id="delTable" title="Delete Table" type="button" class="btn btn-sm btn-danger fs-6" data-bs-toggle="modal" data-bs-target="#delCourseScheduleTbl"><i class="bi bi-trash-fill pr-1"></i><i class="bi bi-grid-3x3"></i></button> 
                                    </td>
                                    <td>
                                        <button title="Add Column Right" type="button" class="addCol btn btn-sm btn-secondary fs-6" data-side="right"><i class="bi bi-plus pr-1"></i><i class="bi bi-layout-sidebar-inset-reverse"></i></button>
                                        <button title="Add Column Left" type="button" class="addCol btn btn-sm btn-secondary fs-6" data-side="left"><i class="bi bi-plus pr-1"></i><i class="bi bi-layout-sidebar-inset"></i></button>
                                        <button id="delCols" title="Delete Column(s)" type="button" class="btn btn-sm btn-danger fs-6" ><i class="bi bi-trash-fill pr-1"></i><i class="bi bi-layout-three-columns"></i></button>                                
                                    </td>
                                    <td>
                                        <button title="Add Row Top" type="button" class="addRow btn btn-sm btn-secondary fs-6" data-side="top"><i class="bi bi-plus pr-1"></i><i class="bi bi-table"></i></i></button>
                                        <button title="Add Row Bottom" type="button" class="addRow btn btn-sm btn-secondary fs-6" data-side="bottom"><i class="bi bi-plus pr-1"></i><i class="bi bi-border-bottom"></i></i></button>
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <!-- div where course schedule table is created from scratch  -->
                        <div id="courseScheduleTblDiv" class="row">

                            @if (!empty($syllabus))
                                @if ($myCourseScheduleTbl['rows']->count() > 0)
                                <table id="courseScheduleTbl" class="table table-responsive">
                                    <tbody>
                                        @foreach ($myCourseScheduleTbl['rows'] as $rowIndex => $row)
                                            <!-- table header -->
                                            @if ($rowIndex == 0)
                                                <tr class="table-primary fw-bold">
                                                    @foreach ($row as $headerIndex => $header)
                                                    <td>
                                                        <textarea name="courseScheduleTblHeadings[]" form="sylabusGenerator" type="text" class="form-control" spellcheck="true" placeholder="Column heading here ...">{{$header->val}}</textarea>
                                                    </td>
                                                    @endforeach
                                                    <td></td>
                                                </tr>
                                            @else
                                                <tr>
                                                    @foreach ($row as $colIndex => $data)
                                                    <td>
                                                        <textarea name="courseScheduleTblRows[]" form="sylabusGenerator" type="text" class="form-control" spellcheck="true" placeholder="Data here ...">{{$data->val}}</textarea>
                                                    </td>
                                                    @endforeach
                                                    <td style="vertical-align:center; text-align:center">
                                                        <i class="bi bi-x-circle-fill text-danger fs-4 btn" onclick="delCourseScheduleRow(this)"></i>
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                                @endif
                            @endif

                        </div>

                        <!-- TODO template-->
                        <!-- <div id="courseScheduleTableTemplate" >
                            <table id="table" data-toolbar="#toolbar" data-search="true" data-show-refresh="true" data-show-toggle="true" data-show-columns="true" data-show-columns-toggle-all="true" data-detail-view="true" data-show-export="true" data-click-to-select="true" data-detail-formatter="detailFormatter" data-minimum-count-columns="2" data-show-pagination-switch="true" data-reorderable-rows="true" data-use-row-attr-func="true">
                            </table>
                        </div> -->

                        <!-- Late Policy -->
                        <div class="row mb-3">
                            <div class="col ">
                                <label for="latePolicy">Late policy</label>
                                <i class="bi bi-info-circle-fill" data-toggle="tooltip" data-bs-placement="right" title="{{$inputFieldDescriptions['latePolicy']}}"></i>
                                <textarea id = "latePolicy" name = "latePolicy" class ="form-control" type="date" form="sylabusGenerator" spellcheck="true">{{ !empty($syllabus) ? $syllabus->late_policy : ''}}</textarea>
                            </div>
                        </div>
                        <!-- Course Missing Exam -->
                        <div class="row mb-3">
                            <div class="col">
                                <label for="missingExam">Missed exam policy</label>
                                <textarea id = "missingExam" name = "missingExam" class ="form-control" type="date" form="sylabusGenerator" spellcheck="true">{{ !empty($syllabus) ? $syllabus->missed_exam_policy : ''}}</textarea>
                            </div>
                        </div>
                        <!-- Course Missed Activity Policy -->
                        <div class="row mb-3">
                            <div class="col">
                                <label for="missingActivity">Missed Activity Policy</label>
                                <i class="bi bi-info-circle-fill" data-toggle="tooltip" data-bs-placement="right" title="{{$inputFieldDescriptions['missedActivityPolicy']}}"></i>
                                <textarea id = "missingActivity" name = "missingActivity" class ="form-control" type="date" form="sylabusGenerator" spellcheck="true">{{ !empty($syllabus) ? $syllabus->missed_activity_policy : ''}}</textarea>
                            </div>
                        </div>
                        <!-- Course Passing Criteria -->
                        <div class="row mb-3">
                            <div class="col">
                                <label for="passingCriteria">Passing criteria</label>
                                <textarea id = "passingCriteria" name = "passingCriteria" class ="form-control" type="date" form="sylabusGenerator" spellcheck="true">{{ !empty($syllabus) ? $syllabus->passing_criteria : ''}}</textarea>
                            </div>
                        </div>
                        <!-- Course Learning Materials -->
                        <div class="row mb-3">
                            <div class="col" >
                                <label for="learningMaterials">Learning Materials</label>
                                <i class="bi bi-info-circle-fill" data-toggle="tooltip" data-bs-placement="right" title="{{$inputFieldDescriptions['learningMaterials']}}"></i>
                                <textarea id = "learningMaterials" name = "learningMaterials" class ="form-control" type="date" form="sylabusGenerator" spellcheck="true">{{ !empty($syllabus) ? $syllabus->learning_materials : ''}}</textarea>
                            </div>
                        </div>
                        <!-- Course Learning Resources -->
                        <div class="row mb-3">
                            <div class="col">
                                <label for="learningResources">Learning Resources</label>
                                <i class="bi bi-info-circle-fill" data-toggle="tooltip" data-bs-placement="right" title="{{$inputFieldDescriptions['learningResources']}}"></i>
                                <span class="requiredBySenate"></span>
                                <textarea id = "learningResources" name = "learningResources" class ="form-control" form="sylabusGenerator" spellcheck="true">{{ !empty($syllabus) ? $syllabus->learning_resources : ''}}</textarea>
                            </div>
                        </div>
                        <!-- Course Overview -->
                        <div class="row" id="learningAnalytics"></div>

                        <!-- Course Optional Resources -->
                        <div class="row mb-3 mt-4" >
                            <div class="col">
                                <label class="fs-5 mb-3" for="optionalSyllabus"><b>Optional: </b>The below are suggested syllabus sections to communicate various resources on campus.</label>
                                <div class="optionalSyllabus form-check">
                                    <ul id="optionalSyllabus" class="text-start" style="list-style-type:none;">
                                    </ul>
                                </div>
                            </div>
                        </div>                 
                    </div>                                    
                </form>
            </div>

            <div class="card-footer p-4">
                <div style="display:flex; flex-flow:row nowrap; justify-content:flex-end;">
                    <button type="submit" class="btn btn-primary col-2 btn-sm m-2" form="sylabusGenerator">Save</button>
                    <button type="submit" name="download" value="1" class="btn btn-primary col-2 btn-sm m-2" form="sylabusGenerator">Save and Download <i class="bi bi-download"></i></button>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="createCourseScheduleTblModal" class="modal fade" data-bs-backdrop="static" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create a Course Schedule Table</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="createCourseScheduleTblForm">
                <div class="modal-body">
                    <div class="row g-3 mb-2">
                        <div class="col-12">
                            <label for="courseScheduleTblTitle" class="form-label">Title</label>
                            <input id="courseScheduleTblTitle" name="courseScheduleTblTitle" id="courseScheduleTblTitle" type="text" class="form-control">
                        </div>
                        <div class="col-6">
                            <label for="courseScheduleTblRowsCount" class="form-label">Number of Rows</label>
                            <input id="courseScheduleTblRowsCount" name="numRows" type="number" min="1" max="42" step="1" class="form-control">
                        </div>
                        <div class="col-6">
                            <label for="courseScheduleTblColsCount" class="form-label">Number of Columns</label>
                            <input id="courseScheduleTblColsCount" name="numCols" type="number" min="1" max="6" step="1" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Course Schedule Table Modal -->
<div id="delCourseScheduleTbl" class="modal fade" data-bs-backdrop="static" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Course Schedule Confirmation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete your course schedule?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary col-3" data-bs-dismiss="modal">Cancel</button>
                <button id="delCourseScheduleBtn" type="button" class="btn btn-danger col-3">Yes</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Course Schedule Table Columns Modal -->
<div id="delColsModal" class="modal fade" data-bs-backdrop="static" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Column(s)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="delColsForm">
                <div class="modal-body">
                    <p>Which columns would you like to delete?</p>
                    <div id="courseScheduleTblColsList"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary col-3" data-bs-dismiss="modal">Cancel</button>
                    <button id="delColsBtn" type="submit" class="btn btn-danger col-3">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="application/javascript">
    $(document).ready(function () {

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
                // get table title
                var tblTitle = event.target.elements.courseScheduleTblTitle.value;
                // get num rows 
                var numRows = event.target.elements.numRows.value;
                // get num cols
                var numCols = event.target.elements.numCols.value;
                // create <table> element
                var tbl = document.createElement('table');
                tbl.setAttribute('id', 'courseScheduleTbl');
                tbl.setAttribute('class', 'table table-responsive');
                // create table <caption> element with table title
                var tblCaption = document.createElement('caption');
                tblCaption.innerHTML = tblTitle;
                tblCaption.setAttribute('class', 'caption-top');
                // put <caption> in <table>
                tbl.appendChild(tblCaption);
                // create <tbody> element
                var tblBody = document.createElement('tbody');
                // iterate over rows 
                for (let rowIndex = 0; rowIndex < numRows; rowIndex++) {
                    // create <row> element
                    var row = document.createElement('tr');
                    if (rowIndex === 0) row.setAttribute('class', 'table-primary fw-bold');
                    // iterate over cols
                    for (let colIndex = 0; colIndex < numCols; colIndex++) {
                        // create <td> element 
                        var cell = document.createElement('td');
                        // create <textarea>
                        var inputCell = document.createElement('textarea');
                        inputCell.setAttribute('form', 'sylabusGenerator');
                        inputCell.setAttribute('type', 'text');
                        inputCell.setAttribute('class', 'form-control');
                        inputCell.setAttribute('spellcheck', 'true');
                        // set input attributes for column headers, otherwise set input attributes for data cells
                        if (rowIndex == 0) {
                            inputCell.setAttribute('placeholder', 'Column heading here ...');
                            inputCell.setAttribute('name', 'courseScheduleTblHeadings[]');
                        } else {
                            inputCell.setAttribute('placeholder', 'Data here ...');                        
                            inputCell.setAttribute('name', 'courseScheduleTblRows[]');
                        }
                        // put inputCell in <td>
                        cell.appendChild(inputCell);
                        // put <td> in <row>
                        row.appendChild(cell);
                    }
                    // add action cell to row if it is not the header row
                    if (rowIndex != 0) {
                        // create <td> element for row actions
                        var actionsCell = document.createElement('td');
                        // center row actions
                        actionsCell.setAttribute('style', 'vertical-align:center;text-align:center');
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
                    } else {
                        // create empty <td>
                        var actionColTdHeader = document.createElement('td');
                        // put <td> in <row>
                        row.appendChild(actionColTdHeader);
                    }
                    // put <row> in <tbody>
                    tblBody.appendChild(row);
                }
                // put <tbody> in <table> 
                tbl.appendChild(tblBody);
                // put <table> in course schedule table div
                courseScheduleTblDiv.appendChild(tbl);
            }

            createCourseScheduleTblModal.hide();

        });

        // event listener on delete course schedule button
        $('#delCourseScheduleBtn').on('click', function(event) {
            var courseScheduleTblDiv = document.getElementById('courseScheduleTblDiv');
            // remove all child nodes 
            $(courseScheduleTblDiv).empty();
            var delCourseScheduleTblModal = bootstrap.Modal.getInstance(document.getElementById('delCourseScheduleTbl'));
            // close modal
            delCourseScheduleTblModal.hide();
        });

        // TODO
        $('#courseScheduleTemplateBtn').on('click', function (event){
            console.log('use a course schedule table template ')
            $('#courseScheduleTableTemplate').removeClass('visually-hidden');
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
                if (numCols < $('#courseScheduleTblColsCount').attr('max')) {  
                    // add a new <td> to each <row>
                    Array.from(courseScheduleTbl.rows).forEach((row, rowIndex) => {
                        // create a <textarea>
                        var inputCell = document.createElement('textarea');
                        inputCell.setAttribute('form', 'sylabusGenerator');
                        inputCell.setAttribute('type', 'text');
                        inputCell.setAttribute('class', 'form-control');
                        inputCell.setAttribute('spellcheck', 'true');
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
                                newCell = row.insertCell(0);
                                newCell.appendChild(inputCell);
                                // row.prepend(cell);
                                break;
                            case 'right': 
                                // put <td> in <row> at the back (insert col on the right)
                                newCell = row.insertCell(numCols - 1);
                                newCell.appendChild(inputCell);
                                // row.appendChild(cell);
                                break;
                            default: 
                                // put <td> in <row> at the front (insert col on the left)
                                row.prepend(cell);
                                break;
                        }
                    });
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
                console.log(cols);
                // foreach col create a checkbox with label and place it in the delColsModal 
                cols.for
                Array.from(cols).forEach((col, colIndex) => {
                    // only add relevant col headers to del cols modal
                    if (colIndex < cols.length - 1) {
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
                        // create, style and set attributes for <label>
                        var colLabel = document.createElement('label');
                        colLabel.setAttribute('for', 'col-heading-' + (colIndex + 1).toString());
                        colLabel.setAttribute('class', 'form-check-label');
                        colLabel.innerHTML = (col.firstElementChild.value.length === 0) ? 'Column #' + (colIndex + 1).toString() : col.firstElementChild.value; 
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
                    // create  <td> element 
                    var cell = document.createElement('td');
                    // create <textarea>
                    var inputCell = document.createElement('textarea');
                    inputCell.setAttribute('form', 'sylabusGenerator');
                    inputCell.setAttribute('name', 'courseScheduleTblRows[]');
                    inputCell.setAttribute('type', 'text');
                    inputCell.setAttribute('class', 'form-control');
                    inputCell.setAttribute('spellcheck', 'true');
                    // set placeholder values for <textarea>
                    inputCell.setAttribute('placeholder', 'Data here ...');
                    // put inputCell in <td>
                    cell.appendChild(inputCell);
                    // switch on side to add row
                    switch (side) {
                        case 'top':
                            // add a row at the top
                            let topRow = courseScheduleTbl.insertRow(1);
                            // add a cell for each col to the new row
                            for (let colIndex = 0; colIndex < numCols - 1; colIndex++) {
                                // clone input cell to add it to a row multiple times
                                topRow.appendChild(cell.cloneNode(true));
                            }
                            // create <td> element for row actions
                            var actionsCell = document.createElement('td');
                            // center row actions
                            actionsCell.setAttribute('style', 'vertical-align:center;text-align:center;width:20px');
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
                            let bottomRow = courseScheduleTbl.insertRow();
                            // add a cell for each col to the new row
                            for (let colIndex = 0; colIndex < numCols - 1; colIndex++) {
                                // clone input cell to add it to a row multiple times
                                bottomRow.appendChild(cell.cloneNode(true));
                            }
                            // create <td> element for row actions
                            var actionsCell = document.createElement('td');
                            // center row actions
                            actionsCell.setAttribute('style', 'vertical-align:center;text-align:center');
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
                }
            }
        });

        var syllabus = <?php echo json_encode($syllabus);?>;
        $('[data-toggle="tooltip"]').tooltip();
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
        (submitter.currentTarget) ? $(submitter.currentTarget).parents('tr').remove() : $(submitter).parents('tr').remove();
    }

    // Import course info into using GET AJAX call
    function importCourseInfo() {
        var course_id = $('input[name="importCourse"]:checked').val();
        $.ajax({
            type: "GET",
            url: "/syllabusGenerator/import/course",
            data: {course_id : course_id},
            headers: {
                'X-CSRF-Token': '{{ csrf_token() }}',
            },
        }).done(function(data) {
            // get fields we want to populate
            var c_title_input = $('#courseTitle');
            var c_code_input = $('#courseCode');
            var c_num_input = $('#courseNumber');
            var c_year_input = $('#courseYear');
            var c_term_input = $('#courseSemester');
            var a_method_input = $('#learningAssessments');
            var l_outcome_input = $('#learningOutcome');
            var l_activities_input = $('#learningActivities');
            // get saved data 
            var decode_data = JSON.parse(data);
            var c_title = decode_data['c_title'];
            var c_code = decode_data['c_code'];
            var c_num = decode_data['c_num'];
            var c_year = decode_data['c_year'];
            var c_term = decode_data['c_term'];
            var a_methods = decode_data['a_methods'];
            var l_outcomes = decode_data['l_outcomes'];
            var l_activities = decode_data['l_activities'];
            // format saved data
            var a_methods_text = "";
            var l_outcomes_text = "";
            var l_activities_text = "";
            a_methods.forEach(element => {
                a_methods_text += element.a_method + " " + element.weight + "%\n";
            });
            for(var i = 0; i < l_outcomes.length; i++) {
                l_outcomes_text += (i+1) + ". " + l_outcomes[i].l_outcome + "\n";
            }
            for(var i = 0; i < l_activities.length; i++) {
                l_activities_text += l_activities[i].l_activity + "\n";
            }
            // import saved and formatted data
            c_title_input.val(c_title);
            c_code_input.val(c_code);
            c_num_input.val(c_num);

            c_year_input.val(c_year);
            c_term_input.val(c_term);
            a_method_input.val(a_methods_text);
            l_outcome_input.val(l_outcomes_text);
            l_activities_input.val(l_activities_text);
        });
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

        // list of vancouver syllabus resources
        var vancouverOptionalList = `
            @if (!isset($selectedVancouverSyllabusResourceIds)) 
                @foreach($vancouverSyllabusResources as $index => $vSyllabusResource)
                    @if ($index != 0)
                    <li>
                        <input class="form-check-input" id="{{$vSyllabusResource->id_name}}" type="checkbox" name="vancouverSyllabusResources[{{$vSyllabusResource->id}}]" value="{{$vSyllabusResource->id_name}}" checked>
                        <label class="form-check-label" for="{{$vSyllabusResource->id_name}}">{{$vSyllabusResource->title}}</label>   
                    </li>
                    @endif
                @endforeach
            @else
                @foreach($vancouverSyllabusResources as $index => $vSyllabusResource)
                    @if ($index != 0)
                    <li>
                        <input class="form-check-input" id="{{$vSyllabusResource->id_name}}" type="checkbox" name="vancouverSyllabusResources[{{$vSyllabusResource->id}}]" value="{{$vSyllabusResource->id_name}}" {{in_array($vSyllabusResource->id, $selectedVancouverSyllabusResourceIds) ? 'checked' : ''}}>
                        <label class="form-check-label" for="{{$vSyllabusResource->id_name}}">{{$vSyllabusResource->title}}</label>   
                    </li>
                    @endif
                @endforeach
            @endif

            `;
        // list of okanagan syllabus resources
        var okanaganOptionalList = `
            @if (!isset($selectedOkanaganSyllabusResourceIds)) 
                @foreach($okanaganSyllabusResources as $index => $oSyllabusResource)
                    @if ($index != 0)
                    <li>
                        <input id="{{$oSyllabusResource->id_name}}" type="checkbox" name="okanaganSyllabusResources[{{$oSyllabusResource->id}}]" value="{{$oSyllabusResource->id_name}}" checked>
                        <label for="{{$oSyllabusResource->id_name}}">{{$oSyllabusResource->title}}</label>   
                    </li>
                    @endif
                @endforeach
            @else
                @foreach($okanaganSyllabusResources as $index => $oSyllabusResource)
                    @if ($index != 0)
                    <li>
                        <input id="{{$oSyllabusResource->id_name}}" type="checkbox" name="okanaganSyllabusResources[{{$oSyllabusResource->id}}]" value="{{$oSyllabusResource->id_name}}" {{in_array($oSyllabusResource->id, $selectedOkanaganSyllabusResourceIds) ? 'checked' : ''}}>
                        <label for="{{$oSyllabusResource->id_name}}">{{$oSyllabusResource->title}}</label>   
                    </li>
                    @endif
                @endforeach
            @endif
            `;

        var courseCredit = `
            <label for="courseCredit">
                <span class="requiredField">* </span>
                Course Credit
            </label>
            <input name = "courseCredit" class ="form-control" type="number" min="0" step="1"placeholder="E.g. 3" required value="{{isset($vancouverSyllabus) ? $vancouverSyllabus->course_credit : ''}}">
            <div class="invalid-tooltip">
                Please enter the course course credits.
            </div>
            `;
        
        var officeLocation = `
            <label for="officeLocation"><span class="requiredField">* </span>Office Location</label>
            <i class="bi bi-info-circle-fill has-tooltip"  data-bs-placement="right" title="{{$inputFieldDescriptions['officeLocation']}}"></i>
            <input name = "officeLocation" class ="form-control" type="text" placeholder="E.g. WEL 140" value="{{isset($vancouverSyllabus) ? $vancouverSyllabus->office_location : ''}}" required>
            <div class="invalid-tooltip">
                Please enter your office location.
            </div>

            `;

        var courseDescription = `
            <div class="col mb-3">
                <label for="courseDescription">Course Description</label>
                <i class="bi bi-info-circle-fill has-tooltip"  data-bs-placement="right" title="{{$inputFieldDescriptions['courseDescription']}}"></i>
                <textarea name = "courseDescription" class ="form-control" type="date" form="sylabusGenerator">{{isset($vancouverSyllabus) ? $vancouverSyllabus->course_description : ''}}</textarea>
            </div>
            `;

        var courseContacts = `
            <div class="col mb-3">
                <label for="courseContacts">Contacts</label>
                <i class="bi bi-info-circle-fill has-tooltip"  data-bs-placement="right" title="{{$inputFieldDescriptions['courseContacts']}}"></i>
                <span class="requiredBySenate"></span>
                <div id="formatContacts" class="collapsibleNotes btn-primary rounded-3" style="overflow:hidden;transition:height 0.3s ease-out;height:auto" data-collapsed="false">
                    <i class="bi bi-exclamation-triangle-fill fs-5 pl-2 pr-2 pb-1"></i> <span class="fs-6">Place each entry on a newline for the best formatting results.</span>                                        
                </div>                                            
                <textarea id="courseContacts" data-formatnoteid="formatContacts" name = "courseContacts" placeholder="E.g. Professor, Jane Doe, jane.doe@ubc.ca, +1 234 567 8900, ... &#10;Teaching Assistant, John Doe, john.doe@ubc.ca, ..."class ="form-control" type="date" form="sylabusGenerator">{{isset($vancouverSyllabus) ? $vancouverSyllabus->course_contacts : ''}}</textarea>
            </div>
            `;

        var coursePrereqs = `
            <div class="col mb-3">
                <label for="coursePrereqs">Course Prerequisites</label>
                <i class="bi bi-info-circle-fill has-tooltip" data-bs-placement="right" title="{{$inputFieldDescriptions['coursePrereqs']}}"></i>
                <span class="requiredBySenate"></span>
                <div id="formatPrereqs" class="collapsibleNotes btn-primary rounded-3" style="overflow:hidden;transition:height 0.3s ease-out;height:auto" data-collapsed="false">
                    <i class="bi bi-exclamation-triangle-fill fs-5 pl-2 pr-2 pb-1"></i> <span class="fs-6">Place each entry on a newline for the best formatting results.</span>                                        
                </div>                                            
                <textarea id="coursePrereqs" data-formatnoteid="formatPrereqs"name = "coursePrereqs" placeholder="E.g. CPSC 210 or EECE 210 or CPEN 221 &#10;E.g. CPSC 121 or MATH 220"class ="form-control" type="text" form="sylabusGenerator" >{{isset($vancouverSyllabus) ? $vancouverSyllabus->course_prereqs : ''}}</textarea>
            </div>
            `;
        var courseCoreqs = `
            <div class="col mb-3">
                <label for="courseCoreqs">Course Corequisites</label>
                <i class="bi bi-info-circle-fill has-tooltip" data-bs-placement="right" title="{{$inputFieldDescriptions['courseCoreqs']}}"></i>
                <span class="requiredBySenate"></span>
                <div id="formatCoreqs"class="collapsibleNotes btn-primary rounded-3" style="overflow:hidden;transition:height 0.3s ease-out;height:auto" data-collapsed="false" >
                    <i class="bi bi-exclamation-triangle-fill fs-5 pl-2 pr-2 pb-1"></i> <span class="fs-6">Place each entry on a newline for the best formatting results.</span>                                        
                </div>                                            
                <textarea id = "courseCoreqs" data-formatnoteid="formatCoreqs"placeholder="E.g. CPSC 107 or CPSC 110 &#10;E.g. CPSC 210" name = "courseCoreqs" class ="form-control" type="text" form="sylabusGenerator">{{isset($vancouverSyllabus) ? $vancouverSyllabus->course_coreqs : ''}}</textarea>
            </div>
            `;
        var courseInstructorBio = `
            <div class="col mb-3">
                    <label for="courseInstructorBio">Course Instructor Biographical Statement</label>
                    <i class="bi bi-info-circle-fill has-tooltip" data-bs-placement="right" title="{{$inputFieldDescriptions['instructorBioStatement']}}"></i>
                    <textarea id = "courseInstructorBio" name = "courseInstructorBio" class ="form-control" form="sylabusGenerator" spellcheck="true">{{isset($vancouverSyllabus) ? $vancouverSyllabus->instructor_bio : ''}}</textarea>
            </div>
            `;

        var courseSchedule = `
            <div class="col mb-3">
                <label for="courseSchedule">Course Schedule</label>
                <i class="bi bi-info-circle-fill has-tooltip"  data-bs-placement="right" title="{{$inputFieldDescriptions['courseSchedule']}}"></i>
                <span class="requiredBySenate"></span>
                <button type="button" class="btn bg-primary btn-sm text-white mb-2 float-right" data-bs-toggle="modal" data-bs-target="#courseScheduleModal"><i class="text-white bi bi-pencil-fill btn-icon"></i> Course Schedule Table</button> 
                <textarea name = "courseSchedule" class ="form-control" type="text" form="sylabusGenerator" spellcheck="true">{{isset($vancouverSyllabus) ? $vancouverSyllabus->course_schedule : ''}}</textarea>
            </div>
            `;        
        
        var courseStructure = `
            <div class="col mb-3">
                <label for="courseStructure">Course Structure</label>
                <i class="bi bi-info-circle-fill has-tooltip" data-bs-placement="right" title="{{$inputFieldDescriptions['courseStructure']}}"></i>
                <span class="requiredBySenate"></span>
                <textarea name = "courseStructure" class ="form-control" type="text" form="sylabusGenerator" spellcheck="true">{{isset($vancouverSyllabus) ? $vancouverSyllabus->course_structure : ''}}</textarea>
            </div>
            `;

        var learningAnalytics = `
            <div class="col mb-3">
                <label for="learningAnalytics">Learning Analytics</label>
                <i class="bi bi-info-circle-fill has-tooltip"  data-bs-placement="right" title="{{$inputFieldDescriptions['learningAnalytics']}}"></i>                                            
                <textarea id="learningAnalytics" name = "learningAnalytics" class ="form-control" type="text" form="sylabusGenerator">{{isset($vancouverSyllabus) ? $vancouverSyllabus->learning_analytics : ''}}</textarea>
            </div>
            `;
        var courseFormat = `
            <div class="col mb-3">
                <label for="courseFormat">Course Format</label>
                <textarea name = "courseFormat" class ="form-control" type="text" form="sylabusGenerator" spellcheck="true">{{ isset($okanaganSyllabus) ? $okanaganSyllabus->course_format: ''}}</textarea>
            </div>
            `;
        var courseOverview = `
            <div class="col mb-3">
                <label for="courseOverview">Course Overview, Content and Objectives</label>
                <textarea name = "courseOverview" class ="form-control" type="text" form="sylabusGenerator" spellcheck="true">{{ isset($okanaganSyllabus) ? $okanaganSyllabus->course_overview : ''}}</textarea>
            </div>        
            `;

        var requiredBySenateLabel = `
            <span class="d-inline-block has-tooltip" tabindex="0" data-toggle="tooltip" data-bs-placement="top" title="This section is required in your syllabus by Vancouver Senate policy V-130">
                <button type="button" class="btn btn-danger btn-sm mb-2 disabled" style="font-size:10px;">Required by policy</button> 
            </span>
            `;
        
        // get campus select element
        var campus = $('#campus');
        // check if its value is 'V'
        if(campus.val() == 'V'){
            $('input.land').attr('name', 'vancouverSyllabusResources[{{$vancouverSyllabusResources[0]->id}}]');
            $('input.land').attr('value', '{{$vancouverSyllabusResources[0]->id_name}}');

            // add data specific to vancouver campus
            $('#optionalSyllabus').html(vancouverOptionalList);
            $('#courseCredit').html(courseCredit);
            $('#officeLocation').html(officeLocation);
            $('#courseContacts').html(courseContacts);
            $('#coursePrereqs').html(coursePrereqs);
            $('#courseCoreqs').html(courseCoreqs);
            $('#courseStructure').html(courseStructure);
            $('#courseSchedule').html(courseSchedule);
            $('#courseInstructorBio').html(courseInstructorBio);
            $('#courseDescription').html(courseDescription);
            $('#learningAnalytics').html(learningAnalytics);
            $('.requiredBySenate').html(requiredBySenateLabel);

            // remove data specific to okanangan campus
            $('#courseFormat').empty();
            $('#courseOverview').empty();
        }
        else
        {
            $('input.land').attr('name', 'okanaganSyllabusResources[{{$okanaganSyllabusResources[0]->id}}]');
            $('input.land').attr('value', '{{$okanaganSyllabusResources[0]->id_name}}');

            // add data specific to okanagan campus
            $('#optionalSyllabus').html(okanaganOptionalList);
            $('#courseFormat').html(courseFormat);
            $('#courseOverview').html(courseOverview);
            // remove data specific to vancouver campus
            $('#courseCredit').empty();
            $('#officeLocation').empty();
            $('#courseContacts').empty();
            $('#coursePrereqs').empty();
            $('#courseCoreqs').empty();
            $('#courseStructure').empty();
            $('#courseSchedule').empty();
            $('#courseInstructorBio').empty();
            $('#courseDescription').empty();
            $('#learningAnalytics').empty();
            $('.requiredBySenate').empty();
        }

        var formatNotes = document.querySelectorAll('.collapsibleNotes').forEach(function(note) {
            // collapse sections when document is ready
            var isCollapsed = note.dataset.collapsed === 'true';
            if (!isCollapsed) {
                collapseSection(note);
            }
        });

    }
</script>

@endsection
