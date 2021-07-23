
@extends('layouts.app')

@section('content')

<div id="app">
    <div class="home">
        <div class="card">
            <div class="card-header wizard text-start">
                <h2>
                    Syllabus Generator 
                    <button type="button" class="btn bg-primary text-white fw-bold col-3 float-right" data-toggle="modal" data-target="#importExistingCourse">Import an existing course</button>
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
                                    <button style="width:60px" type="button" class="btn btn-primary btn-sm"
                                    id="importButton" name="importButton" data-dismiss="modal">Import</button>
                                </div>
                        </div>
                    </div>
                </div>
                <!-- End of Import an Existing Course Modal -->
            </div>

            <div class="card-body">
                <h6 class="card-subtitle mb-4 lh-lg fs-6 text-center">
                    To assist faculty in preparing their syllabi, the Curriculum MAP syllabus generator follows the policies and templates provided by the <a href="https://senate.ubc.ca/okanagan/curriculum/forms">UBC Okanagan <i class="bi bi-box-arrow-up-right"></i></a> and <a href="https://senate.ubc.ca/policies-resources-support-student-success">UBC Vancouver <i class="bi bi-box-arrow-up-right"></i></a> senate. 
                </h6>
                <div class="courseInfo">
                    <form method="POST" id="sylabusGenerator" action="{{!empty($syllabus) ? action('SyllabusController@save', $syllabus->id) : action('SyllabusController@save')}}">
                        @csrf
                        <div class="container">
                                    <div class="row justify-content-center">
                                        <div class="col mb-2 text-end fs-6 ">
                                            <!-- Campus dropdown -->
                                            <span class="requiredField">*
                                                <select  class="form-group text-center" id="campus" name="campus" form="sylabusGenerator" required>
                                                    <option disabled selected value=""> -- Campus -- </option>
                                                    <option value="O">
                                                        UBC Okanagan
                                                    </option>
                                                    <option value="V">UBC Vancouver</option>
                                                </select>
                                            </span>
                                        </div>
                                        <div class="col mb-2 text-start fs-6">
                                            @if (!empty($syllabus))
                                                <input id="land" class="land" type="checkbox" @if ($syllabus->campus == 'O') {{in_array($okanaganSyllabusResources[0]->id, $selectedOkanaganSyllabusResourceIds) ? 'checked' : ''}} @else {{in_array($vancouverSyllabusResources[0]->id, $selectedVancouverSyllabusResourceIds) ? 'checked' : ''}}@endif>
                                                <label for="land">Land Acknowledgement</label>
                                            @else 
                                                <input id="land" class="land" type="checkbox" checked>
                                                <label for="land">Land Acknowledgement</label>
                                            @endif
                                        </div> 
                                    </div>
                                    
                                    <!-- Course Title -->
                                    <div class="row mb-3">
                                        <div class="col-10">
                                            <label for="courseTitle"><span class="requiredField">* </span>Course Title</label>
                                            <input spellcheck="true" id = "courseTitle" name = "courseTitle" class ="form-control" type="text"
                                            placeholder="E.g. Intro to Software development" required value="{{ !empty($syllabus) ? $syllabus->course_title : '' }}">
                                        </div>
                                    </div>
                                    <!-- Course Code, Course Number, Course Credit -->
                                    <div class="row mb-3">
                                        <div class="col-3 ">
                                            <label for="courseCode"><span class="requiredField">* </span>Course Code</label>
                                            <input id = "courseCode" name = "courseCode" class ="form-control" type="text"
                                            placeholder="E.g. CPSC" required value="{{ !empty($syllabus) ? $syllabus->course_code : '' }}">
                                        </div>

                                        <div class="col-2">
                                            <label for="courseNumber"><span class="requiredField">* </span>Course Number</label>
                                            <input id = "courseNumber" name = "courseNumber" class ="form-control" type="text"
                                            placeholder="E.g. 310" required value="{{ !empty($syllabus) ? $syllabus->course_num : '' }}">
                                        </div>
                                        <div id="courseCredit" class="col-2">
                                        </div>
                                    </div>
                                    <!-- Course Instructor, Course Semester -->
                                    <div class="row mb-3">

                                        <div class="col-5">
                                            <label for="courseInstructor"><span class="requiredField">* </span>Course Instructor</label>
                                            
                                            <input id = "courseInstructor" name = "courseInstructor" class ="form-control" type="text"
                                            placeholder="E.g. Dr. J. Doe" required value="{{ !empty($syllabus) ? $syllabus->course_instructor : ''}}">
                                        </div>
                                        <div class="col-3">
                                            <label for="courseSemester"><span class="requiredField">* </span>Course Term</label>
                                            <select id="courseSemester" class="form-control" name="courseSemester" required>
                                                <option value="W1" {{!empty($syllabus) ? (($syllabus->course_term == 'W1') ? 'selected=true' : '') : ''}}>
                                                    Winter Term 1
                                                </option>
                                                <option value="W2" {{!empty($syllabus) ? (($syllabus->course_term == 'W2') ? 'selected=true' : '') : ''}}>
                                                    Winter Term 2
                                                </option>
                                                <option value="S1" {{!empty($syllabus) ? (($syllabus->course_term == 'S1') ? 'selected=true' : '') : ''}}>
                                                    Summer Term 1
                                                </option>
                                                <option value="S2" {{!empty($syllabus) ? (($syllabus->course_term == 'S2') ? 'selected=true' : '') : ''}}>
                                                    Summer Term 2
                                                </option>
                                            </select>
                                        </div>
                                        <!-- Course Year -->
                                        <div class="col-2">
                                            <label for="courseYear"><span class="requiredField">* </span>Course Year</label>
                                            <select id="courseYear" class="form-control" name="courseYear">
                                                <option
                                                value="2023"
                                                {{!empty($syllabus) ? (($syllabus->course_year == '2023') ? 'selected=true' : '') : ''}}
                                                >
                                                    2023
                                                </option>
                                                <option value="2022"
                                                {{!empty($syllabus) ? (($syllabus->course_year == '2022') ? 'selected=true' : '') : ''}}
                                                >
                                                    2022
                                                </option>
                                                <option value="2021"
                                                {{!empty($syllabus) ? (($syllabus->course_year == '2021') ? 'selected=true' : '') : ''}}
                                                >
                                                    2021
                                                </option>
                                                <option value="2020"
                                                {{!empty($syllabus) ? (($syllabus->course_year == '2020') ? 'selected=true' : '') : ''}}
                                                >
                                                    2020
                                                </option>
                                                <option value="2019"
                                                {{!empty($syllabus) ? (($syllabus->course_year == '2019') ? 'selected=true' : '') : ''}}
                                                >
                                                    2019
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <!-- Course Location -->
                                    <div class="row mb-3">
                                        <div class="col-5">
                                            <label for="courseLocation">Course Location</label>
                                            <input id = "courseLocation" name = "courseLocation" class ="form-control" type="text"
                                            placeholder="E.g. WEL 140"
                                            value="{{ !empty($syllabus) ? $syllabus->course_location : ''}}" 
                                            >
                                        </div>
                                        <!-- Office Location -->
                                        <div id="officeLocation" class="col-6">
                                        </div>
                                    </div>

                                    <!-- Course Description -->
                                    <div class="row" id="courseDescription"></div>                                    
                                    <!-- Course Contacts -->
                                    <div class="row" id="courseContacts"></div>

                                    <!-- Office Hours -->
                                    <div class="row mb-3">
                                        <div class="col">
                                            <label for="officeHour">Office Hours</label>
                                            <i class="bi bi-info-circle-fill" data-toggle="tooltip" data-bs-placement="right" title="{{$inputFieldDescriptions['officeHours']}}"></i>
                                            <textarea spellcheck="true" id = "officeHour" name = "officeHour" class ="form-control"
                                            type="date"
                                            form="sylabusGenerator" 
                                            >{{ !empty($syllabus) ? $syllabus->office_hours : ''}}</textarea>
                                        </div>
                                    </div>
                                    <!-- Other Course Staff -->
                                    <div class="row mb-3">
                                        <div class="col">
                                            <label  for="otherCourseStaff">Other Instructional Staff</label>
                                            <i class="bi bi-info-circle-fill" data-toggle="tooltip" data-bs-placement="right" title="{{$inputFieldDescriptions['otherCourseStaff']}}"></i>
                                            <div id="formatStaff" class="collapsibleNotes btn-primary rounded-3" style="overflow:hidden;transition:height 0.3s ease-out;height:auto" data-collapsed="false">
                                                <i class="bi bi-exclamation-triangle-fill fs-5 pl-2 pr-2 pb-1"></i> <span class="fs-6">Place each entry on a newline for the best formatting results.</span>                                        
                                            </div>                                            
                                            <textarea id = "otherCourseStaff" data-formatnoteid="formatStaff" placeholder="E.g. Professor, Dr. Phil, PhD Clinical Psychology, ...&#10;E.g. Instructor, Bill Nye, BS Mechanical Engineering, ..." name = "otherCourseStaff" class ="form-control" form="sylabusGenerator" spellcheck="true">{{ !empty($syllabus) ? $syllabus->other_instructional_staff : ''}}</textarea>
                                        </div>
                                    </div>
                                    <!-- Class Start Time, Class End Time -->
                                    <div class="row mb-3">
                                        <div class="col-3">
                                            <label for="startTime">Class Start Time</label>
                                            <input id = "startTime" name = "startTime" class ="form-control" type="text"
                                            placeholder="E.g. 1:00 PM"
                                            value="{{ !empty($syllabus) ? $syllabus->class_start_time : ''}}"
                                            >
                                        </div>

                                        <div class="col-3">
                                            <label for="endTime">Class End Time</label>
                                            <input id = "endTime" name = "endTime" class ="form-control" type="text"
                                            placeholder="E.g. 2:00 PM"
                                            value="{{ !empty($syllabus) ? $syllabus->class_end_time : ''}}" >
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
                                            <label for="learningOutcome">Learning Outcomes
                                            </label>
                                            <i class="bi bi-info-circle-fill" data-toggle="tooltip" data-bs-placement="right" title="{{$inputFieldDescriptions['learningOutcomes']}}"></i>
                                            <p style="color:gray">
                                                <i>                     Upon successful completion of this course, students will be able to...
                                                </i>
                                            </p>
                                            <div id="formatCLOs" class="collapsibleNotes btn-primary rounded-3" style="overflow:hidden;transition:height 0.3s ease-out;height:auto" data-collapsed="false">
                                                <i class="bi bi-exclamation-triangle-fill fs-5 pl-2 pr-2 pb-1"></i> <span class="fs-6">Place each entry on a newline for the best formatting results.</span>                                        
                                            </div>                                            
                                            <textarea id = "learningOutcome" data-formatnoteid="formatCLOs" placeholder="E.g. Define ... &#10;E.g. Classify ..." name = "learningOutcome" class ="form-control"
                                            type="date" style="height:125px;" form="sylabusGenerator" spellcheck="true">{{ !empty($syllabus) ? $syllabus->learning_outcomes : ''}}</textarea>
                                        </div>
                                    </div>
                                    
                                    <!-- Course Learning Assessments -->
                                    <div class="row mb-3">
                                        <div class="col">
                                            <label for="learningAssessments">Assessments of Learning</label>
                                            <i class="bi bi-info-circle-fill" data-toggle="tooltip" data-bs-placement="right" title="{{$inputFieldDescriptions['learningAssessments']}}"></i>
                                            <div id="formatAssessments" class="collapsibleNotes btn-primary rounded-3" style="overflow:hidden;transition:height 0.3s ease-out;height:auto" data-collapsed="false">
                                                <i class="bi bi-exclamation-triangle-fill fs-5 pl-2 pr-2 pb-1"></i> <span class="fs-6">Place each entry on a newline for the best formatting results.</span>                                        
                                            </div>                                            
                                            <textarea id = "learningAssessments" data-formatnoteid="formatAssessments" placeholder="E.g. Presentation, 25%, Dec 1, ... &#10;E.g. Midterm Exam, 25%, Sept 31, ..." name = "learningAssessments" class ="form-control"
                                            type="date" style="height:125px;" form="sylabusGenerator" spellcheck="true">{{ !empty($syllabus) ? $syllabus->learning_assessments : ''}}</textarea>
                                        </div>
                                    </div>

                                    <!-- Learning Activities -->
                                    <div class="row mb-3">
                                        <div class="col">
                                            <label for="learningActivities">Learning Activities</label>
                                            <i class="bi bi-info-circle-fill" data-toggle="tooltip" data-bs-placement="right" title="{{$inputFieldDescriptions['learningActivities']}}"></i>
                                            <div id="formatActivities" class="collapsibleNotes btn-primary rounded-3" style="overflow:hidden;transition:height 0.3s ease-out;height:auto" data-collapsed="false">
                                                <i class="bi bi-exclamation-triangle-fill fs-5 pl-2 pr-2 pb-1"></i> <span class="fs-6">Place each entry on a newline for the best formatting results.</span>                                        
                                            </div>                                            
                                            <textarea id = "learningActivities" data-formatnoteid="formatActivities" placeholder="E.g. Class participation consists of clicker questions, group discussions ... &#10;E.g. Students are expected to complete class pre-readings ..."name = "learningActivities" class ="form-control"
                                            type="date" style="height:125px;" form="sylabusGenerator" spellcheck="true">{{ !empty($syllabus) ? $syllabus->learning_activities : ''}}</textarea>
                                        </div>
                                    </div>

                                    <!-- Late Policy -->
                                    <div class="row mb-3">
                                        <div class="col ">
                                            <label for="latePolicy">Late policy</label>
                                            <i class="bi bi-info-circle-fill" data-toggle="tooltip" data-bs-placement="right" title="{{$inputFieldDescriptions['latePolicy']}}"></i>
                                            <textarea id = "latePolicy" name = "latePolicy" class ="form-control"
                                            type="date" form="sylabusGenerator" spellcheck="true">{{ !empty($syllabus) ? $syllabus->late_policy : ''}}</textarea>
                                        </div>
                                    </div>

                                    <!-- Course Missing Exam -->
                                    <div class="row mb-3">
                                        <div class="col">
                                            <label for="missingExam">Missed exam policy</label>
                                            <textarea id = "missingExam" name = "missingExam" class ="form-control"
                                            type="date" form="sylabusGenerator" spellcheck="true">{{ !empty($syllabus) ? $syllabus->missed_exam_policy : ''}}</textarea>
                                        </div>
                                    </div>

                                    <!-- Course Missed Activity Policy -->
                                    <div class="row mb-3">
                                        <div class="col">
                                            <label for="missingActivity">Missed Activity Policy</label>
                                            <i class="bi bi-info-circle-fill" data-toggle="tooltip" data-bs-placement="right" title="{{$inputFieldDescriptions['missedActivityPolicy']}}"></i>
                                            <textarea id = "missingActivity" name = "missingActivity" class ="form-control"
                                            type="date" form="sylabusGenerator" spellcheck="true">{{ !empty($syllabus) ? $syllabus->missed_activity_policy : ''}}</textarea>
                                        </div>
                                    </div>

                                    <!-- Course Passing Criteria -->
                                    <div class="row mb-3">
                                        <div class="col">
                                            <label for="passingCriteria">Passing criteria</label>
                                            <textarea id = "passingCriteria" name = "passingCriteria" class ="form-control"
                                            type="date" form="sylabusGenerator" spellcheck="true">{{ !empty($syllabus) ? $syllabus->passing_criteria : ''}}</textarea>
                                        </div>
                                    </div>

                                    <!-- Course Learning Materials -->
                                    <div class="row mb-3">
                                        <div class="col" >
                                            <label for="learningMaterials">Learning Materials</label>
                                            <i class="bi bi-info-circle-fill" data-toggle="tooltip" data-bs-placement="right" title="{{$inputFieldDescriptions['learningMaterials']}}"></i>
                                            <textarea id = "learningMaterials" name = "learningMaterials" class ="form-control"
                                            type="date" form="sylabusGenerator" spellcheck="true">{{ !empty($syllabus) ? $syllabus->learning_materials : ''}}</textarea>
                                        </div>
                                    </div>

                                    <!-- Course Learning Resources -->
                                    <div class="row mb-3">
                                        <div class="col">
                                                <label for="courseLearningResources">Learning Resources</label>
                                                <i class="bi bi-info-circle-fill" data-toggle="tooltip" data-bs-placement="right" title="{{$inputFieldDescriptions['courseLearningResources']}}"></i>
                                                <textarea id = "courseLearningResources" name = "courseLearningResources" class ="form-control" form="sylabusGenerator" spellcheck="true">{{old('courseLearningResources')}}</textarea>
                                        </div>
                                    </div>

                                    <!-- Course Optional Resources -->
                                    <div class="row mb-3 mt-4" >
                                        <div class="col">
                                            <label class="fs-5 mb-3" for="optionalSyllabus"><b>Optional: </b>The below are suggested syllabus sections to communicate various resources on campus.</label>
                                            
                                            <div class="optionalSyllabus">
                                                <ul id="optionalSyllabus" class="text-start" style="list-style-type:none;">
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                        </div>
                    </form>
                </div>
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

<script type="application/javascript">
    $(document).ready(function () {

        var syllabus = <?php echo json_encode($syllabus);?>;
    
        $('[data-toggle="tooltip"]').tooltip();
        // add on change event listener to campus select 
        $('#campus').change(function(){
            onChangeCampus();
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
            var decode_data = JSON.parse(data);
            var c_title = decode_data['c_title'];
            var c_code = decode_data['c_code'];
            var c_num = decode_data['c_num'];
            var c_year = decode_data['c_year'];
            var c_term = decode_data['c_term'];
            var a_methods = decode_data['a_methods'];
            var l_outcomes = decode_data['l_outcomes'];
            var a_methods_text = "";
            var l_outcomes_text = "";

            a_methods.forEach(element => {
                a_methods_text += element.a_method + " " + element.weight + "%\n";
            });

            for(var i = 0; i < l_outcomes.length; i++) {
                l_outcomes_text += (i+1) + ". " + l_outcomes[i].l_outcome + "\n";
            }
            var c_title_input = $('#courseTitle');
            var c_code_input = $('#courseCode');
            var c_num_input = $('#courseNumber');
            var c_year_input = $('#courseYear');
            var c_term_input = $('#courseSemester');
            var a_method_input = $('#learningAssessments');
            var l_outcome_input = $('#learningOutcome');

            c_title_input.val(c_title);
            c_code_input.val(c_code);
            c_num_input.val(c_num);
            c_year_input.val(c_year);
            c_term_input.val(c_term);
            a_method_input.val(a_methods_text);
            l_outcome_input.val(l_outcomes_text);
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
                        <input id="{{$vSyllabusResource->id_name}}" type="checkbox" name="vancouverSyllabusResources[{{$vSyllabusResource->id}}]" value="{{$vSyllabusResource->id_name}}" checked>
                        <label for="{{$vSyllabusResource->id_name}}">{{$vSyllabusResource->title}}</label>   
                    </li>
                    @endif
                @endforeach
            @else
                @foreach($vancouverSyllabusResources as $index => $vSyllabusResource)
                    @if ($index != 0)
                    <li>
                        <input id="{{$vSyllabusResource->id_name}}" type="checkbox" name="vancouverSyllabusResources[{{$vSyllabusResource->id}}]" value="{{$vSyllabusResource->id_name}}" {{in_array($vSyllabusResource->id, $selectedVancouverSyllabusResourceIds) ? 'checked' : ''}}>
                        <label for="{{$vSyllabusResource->id_name}}">{{$vSyllabusResource->title}}</label>   
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
            `;
        
        var officeLocation = `
            <label for="officeLocation">Office Location</label>
            <input name = "officeLocation" class ="form-control" type="text" placeholder="E.g. WEL 140" value="{{isset($vancouverSyllabus) ? $vancouverSyllabus->office_location : ''}}">
            `;

        var courseDescription = `
            <div class="col">
                <label for="courseDescription">Course Description</label>
                <i class="bi bi-info-circle-fill has-tooltip"  data-bs-placement="right" title="{{$inputFieldDescriptions['courseDescription']}}"></i>
                <textarea name = "courseDescription" class ="form-control" type="date" form="sylabusGenerator">{{isset($vancouverSyllabus) ? $vancouverSyllabus->course_description : ''}}</textarea>
            </div>
            `;

        var courseContacts = `
            <div class="col">
                <label for="courseContacts">Contacts</label>
                <i class="bi bi-info-circle-fill has-tooltip"  data-bs-placement="right" title="{{$inputFieldDescriptions['courseContacts']}}"></i>
                <div id="formatContacts" class="collapsibleNotes btn-primary rounded-3" style="overflow:hidden;transition:height 0.3s ease-out;height:auto" data-collapsed="false">
                    <i class="bi bi-exclamation-triangle-fill fs-5 pl-2 pr-2 pb-1"></i> <span class="fs-6">Place each entry on a newline for the best formatting results.</span>                                        
                </div>                                            
                <textarea id="courseContacts" data-formatnoteid="formatContacts" name = "courseContacts" placeholder="E.g. Professor, Jane Doe, jane.doe@ubc.ca, +1 234 567 8900, ... &#10;Teaching Assistant, John Doe, john.doe@ubc.ca, ..."class ="form-control" type="date" form="sylabusGenerator">{{isset($vancouverSyllabus) ? $vancouverSyllabus->course_contacts : ''}}</textarea>
            </div>
            `;

        var coursePrereqs = `
            <div class="col">
                <label for="coursePrereqs">Course Prerequisites</label>
                <i class="bi bi-info-circle-fill has-tooltip" data-bs-placement="right" title="{{$inputFieldDescriptions['coursePrereqs']}}"></i>
                <div id="formatPrereqs" class="collapsibleNotes btn-primary rounded-3" style="overflow:hidden;transition:height 0.3s ease-out;height:auto" data-collapsed="false">
                    <i class="bi bi-exclamation-triangle-fill fs-5 pl-2 pr-2 pb-1"></i> <span class="fs-6">Place each entry on a newline for the best formatting results.</span>                                        
                </div>                                            
                <textarea id="coursePrereqs" data-formatnoteid="formatPrereqs"name = "coursePrereqs" placeholder="E.g. CPSC 210 or EECE 210 or CPEN 221 &#10;E.g. CPSC 121 or MATH 220"class ="form-control" type="text" form="sylabusGenerator" >{{isset($vancouverSyllabus) ? $vancouverSyllabus->course_prereqs : ''}}</textarea>
            </div>
            `;
        var courseCoreqs = `
            <div class="col">
                <label for="courseCoreqs">Course Corequisites</label>
                <i class="bi bi-info-circle-fill has-tooltip" data-bs-placement="right" title="{{$inputFieldDescriptions['courseCoreqs']}}"></i>
                <div id="formatCoreqs"class="collapsibleNotes btn-primary rounded-3" style="overflow:hidden;transition:height 0.3s ease-out;height:auto" data-collapsed="false" >
                    <i class="bi bi-exclamation-triangle-fill fs-5 pl-2 pr-2 pb-1"></i> <span class="fs-6">Place each entry on a newline for the best formatting results.</span>                                        
                </div>                                            
                <textarea id = "courseCoreqs" data-formatnoteid="formatCoreqs"placeholder="E.g. CPSC 107 or CPSC 110 &#10;E.g. CPSC 210" name = "courseCoreqs" class ="form-control" type="text" form="sylabusGenerator">{{isset($vancouverSyllabus) ? $vancouverSyllabus->course_coreqs : ''}}</textarea>
            </div>
            `;
        var courseInstructorBio = `
            <div class="col">
                    <label for="courseInstructorBio">Course Instructor Biographical Statement</label>
                    <i class="bi bi-info-circle-fill has-tooltip" data-bs-placement="right" title="{{$inputFieldDescriptions['instructorBioStatement']}}"></i>
                    <textarea id = "courseInstructorBio" name = "courseInstructorBio" class ="form-control" form="sylabusGenerator" spellcheck="true">{{isset($vancouverSyllabus) ? $vancouverSyllabus->instructor_bio : ''}}</textarea>
            </div>
            `;

        var courseSchedule = `
            <div class="col">
                <label for="courseSchedule">Course Schedule</label>
                <i class="bi bi-info-circle-fill has-tooltip"  data-bs-placement="right" title="{{$inputFieldDescriptions['courseSchedule']}}"></i>
                <textarea name = "courseSchedule" class ="form-control" type="text" form="sylabusGenerator" spellcheck="true">{{isset($vancouverSyllabus) ? $vancouverSyllabus->course_schedule : ''}}</textarea>
            </div>
            `;        
        
        var courseStructure = `
            <div class="col">
                <label for="courseStructure">Course Structure</label>
                <i class="bi bi-info-circle-fill has-tooltip" data-bs-placement="right" title="{{$inputFieldDescriptions['courseStructure']}}"></i>
                <textarea name = "courseStructure" class ="form-control" type="text" form="sylabusGenerator" spellcheck="true">{{isset($vancouverSyllabus) ? $vancouverSyllabus->course_structure : ''}}</textarea>
            </div>
            `;

        var courseFormat = `
            <div class="col">
                <label for="courseFormat">Course Format</label>
                <textarea name = "courseFormat" class ="form-control" type="text" form="sylabusGenerator" spellcheck="true">{{ isset($okanaganSyllabus) ? $okanaganSyllabus->course_format: ''}}</textarea>
            </div>
            `;
        var courseOverview = `
            <div class="col">
                <label for="courseOverview">Course Overview, Content and Objectives</label>
                <textarea name = "courseOverview" class ="form-control" type="text" form="sylabusGenerator" spellcheck="true">{{ isset($okanaganSyllabus) ? $okanaganSyllabus->course_overview : ''}}</textarea>
            </div>        
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
        }

        var formatNotes = document.querySelectorAll('.collapsibleNotes').forEach(function(note) {
            // collapse sections when document is ready
            var isCollapsed = note.dataset.collapsed === 'true';
            console.log(isCollapsed);
            if (!isCollapsed) {
                collapseSection(note);
            }
        });

    }

</script>

@endsection
