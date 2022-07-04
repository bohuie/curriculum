
@extends('layouts.app')

@section('content')

<div class="card mt-4">
    <!-- header -->
    <div class="card-header wizard ">
        <h4>
            {{$syllabus->course_title}}, {{$syllabus->course_code}} {{$syllabus->course_num}}
        </h4>
    </div>
    <!-- body -->
    <div class="card-body">
        <!-- land acknowledgement -->
        @if (in_array($vancouverSyllabusResources[0]->id, $selectedVancouverSyllabusResourceIds))
        <div class="mb-4">
            <div class="vSyllabusHeader">
                <h6>{{strtoupper($vancouverSyllabusResources[0]->title)}}</h6>
            </div>
            <p>We acknowledge that the UBC Vancouver campus is situated within the traditional, ancestral and unceded territory of the Musqueam.</p>
        </div>
        @endif
        <!-- course information -->
        <div class="mb-4">
            <div class="vSyllabusHeader">
                <h6>COURSE INFORMATION</h6>
            </div>
            <table class="table table-bordered">
                <tr class="table-secondary">
                    <th class="w-50">Course Title</th>
                    <th class="w-25">Course Code, Number</th>
                    <th class="w-25">Credit Value</th>
                </tr>
                <tbody>
                    <tr>
                        <td>{{$syllabus->course_title}}</td>
                        <td>
                            {{$syllabus->course_code}}
                            {{$syllabus->course_num}}
                        </td>
                        <td>{{$vancouverSyllabus->course_credit}}</td>
                    </tr>
                </tbody>
            </table>
            <p><b>Campus:</b> @if ($syllabus->campus == 'V') Vancouver @else Okanagan @endif</p>
            <p><b>Faculty:</b> {{$syllabus->faculty}}</p>
            <p><b>Department:</b> {{$syllabus->department}}</p>
            <p><b>Instructor(s):</b> {{$syllabusInstructors}}</p>
            <p><b>Office Location
                <span>
                    <i class="bi bi-info-circle-fill text-dark" data-toggle="tooltip" data-bs-placement="top" title="{{$inputFieldDescriptions['officeLocation']}}"></i>
                </span>
                </b> 
                {{$vancouverSyllabus->office_location}}
            </p>
            <p><b>Duration:</b> {{$syllabus->course_term}} {{$syllabus->course_year}}</p>
            @switch($syllabus->delivery_modality)
                @case('M')
                    <p><b>Delivery Modality:</b> Multi-Access</p>
                    @break
                @case('I')
                    <p><b>Delivery Modality:</b> In-Person</p>
                    @break
                @case('B')
                    <p><b>Delivery Modality:</b> Hybrid</p>
                    @break
                @default
                    <p><b>Delivery Modality:</b> Online</p>
            @endswitch
            <p><b>Class Location:</b> {{$syllabus->course_location}}</p>
            <p><b>Class Days:</b> {{$syllabus->class_meeting_days}}</p>
            <p><b>Class Hours:</b> {{$syllabus->class_start_time}} - {{$syllabus->class_end_time}}</p>
            <p><b>Office Hours                     
                <span>
                    <i class="bi bi-info-circle-fill text-dark" data-toggle="tooltip" data-bs-placement="top" title="{{$inputFieldDescriptions['officeHours']}}"></i>
                </span>
                </b> 
                {{$syllabus->office_hours}}
            </p>
        </div>
        <!-- course prerequisites -->
        <div class="mb-4">
            <div class="vSyllabusHeader2">
                <h6>
                    PREREQUISITES
                    <span>
                        <i class="bi bi-info-circle-fill text-dark" data-toggle="tooltip" data-bs-placement="top" title="{{$inputFieldDescriptions['coursePrereqs']}}"></i>
                        <span class="d-inline-block has-tooltip " tabindex="0" data-toggle="tooltip" data-bs-placement="top" title="This section is required in your syllabus by Vancouver Senate policy V-130">
                            <button type="button" class="btn btn-danger btn-sm mb-1 disabled" style="font-size:10px;">Required by policy</button> 
                        </span>
                    </span>
                </h6>
            </div>
            <p>{{$vancouverSyllabus->course_prereqs}}</p>
        </div>
        <!-- course corequisites -->
        <div class="mb-4">
            <div class="vSyllabusHeader2">
                <h6>
                    COREQUISITES
                    <span>
                        <i class="bi bi-info-circle-fill text-dark" data-toggle="tooltip" data-bs-placement="top" title="{{$inputFieldDescriptions['courseCoreqs']}}"></i>
                        <span class="d-inline-block has-tooltip " tabindex="0" data-toggle="tooltip" data-bs-placement="top" title="This section is required in your syllabus by Vancouver Senate policy V-130">
                            <button type="button" class="btn btn-danger btn-sm mb-1 disabled" style="font-size:10px;">Required by policy</button> 
                        </span>
                    </span>
                </h6>
            </div>
            <p>{{$vancouverSyllabus->course_coreqs}}</p>
        </div>
        <!-- course contacts -->
        <div class="mb-4">
            <div class="vSyllabusHeader">
                <h6>
                    CONTACTS
                    <span>
                        <i class="bi bi-info-circle-fill text-dark" data-toggle="tooltip" data-bs-placement="top" title="{{$inputFieldDescriptions['courseContacts']}}"></i>
                        <span class="d-inline-block has-tooltip " tabindex="0" data-toggle="tooltip" data-bs-placement="top" title="This section is required in your syllabus by Vancouver Senate policy V-130">
                            <button type="button" class="btn btn-danger btn-sm mb-1 disabled" style="font-size:10px;">Required by policy</button> 
                        </span>
                    </span>
                </h6>
            </div>
            <p>{{$vancouverSyllabus->contacts}}</p>
        </div>
        <!-- course instructor biographical statement -->
        <div class="mb-4">
            <div class="vSyllabusHeader">
                <h6>COURSE INSTRUCTOR BIOGRAPHICAL STATEMENT
                    <span>
                        <i class="bi bi-info-circle-fill text-dark" data-toggle="tooltip" data-bs-placement="top" title="{{$inputFieldDescriptions['instructorBioStatement']}}"></i>
                    </span>
                </h6>
            </div>
            <p>{{$vancouverSyllabus->instructor_bio}}</p>
        </div>
        <!-- other instructional staff -->
        <div class="mb-4">
            <div class="vSyllabusHeader">
                <h6>
                    OTHER INSTRUCTIONAL STAFF
                    <span>
                        <i class="bi bi-info-circle-fill text-dark" data-toggle="tooltip" data-bs-placement="top" title="{{$inputFieldDescriptions['otherCourseStaff']}}"></i>
                        <span class="d-inline-block has-tooltip " tabindex="0" data-toggle="tooltip" data-bs-placement="top" title="This section is required in your syllabus by Vancouver Senate policy V-130">
                            <button type="button" class="btn btn-danger btn-sm disabled mb-1" style="font-size:10px;">Required by policy</button> 
                        </span>
                    </span>
                </h6>
            </div>
            <p>{{$syllabus->other_instructional_staff}}</p>
        </div>
        <!-- course structure -->
        <div class="mb-4">
            <div class="vSyllabusHeader">
                <h6>
                    COURSE STRUCTURE
                    <span>
                        <i class="bi bi-info-circle-fill text-dark" data-toggle="tooltip" data-bs-placement="top" title="{{$inputFieldDescriptions['courseStructure']}}"></i>
                        <span class="d-inline-block has-tooltip " tabindex="0" data-toggle="tooltip" data-bs-placement="top" title="This section is required in your syllabus by Vancouver Senate policy V-130">
                            <button type="button" class="btn btn-danger btn-sm mb-1 disabled" style="font-size:10px;">Required by policy</button> 
                        </span>
                    </span>
                </h6>
            </div>
            <p>{{$vancouverSyllabus->course_structure}}</p>
        </div>
        <!-- schedule of topics -->
        <div class="mb-4">
            <div class="vSyllabusHeader">
                <h6>
                    SCHEDULE OF TOPICS
                    <span>
                        <i class="bi bi-info-circle-fill text-dark" data-toggle="tooltip" data-bs-placement="top" title="{{$inputFieldDescriptions['courseSchedule']}}"></i>
                        <span class="d-inline-block has-tooltip " tabindex="0" data-toggle="tooltip" data-bs-placement="top" title="This section is required in your syllabus by Vancouver Senate policy V-130">
                            <button type="button" class="btn btn-danger btn-sm mb-1 disabled" style="font-size:10px;">Required by policy</button> 
                        </span>
                    </span>
                </h6>
            </div>
            <p>{{$vancouverSyllabus->course_schedule}}</p>
            <br>
            <!-- course schedule table  -->
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
                                            {{$header->val}}
                                        </td>
                                        @endforeach
                                    </tr>
                                @else
                                    <tr>
                                        @foreach ($row as $colIndex => $data)
                                        <td>
                                            {{$data->val}}
                                        </td>
                                        @endforeach
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                    @endif
                @endif
            </div>
        </div>
        <!--  learning outcomes -->
        <div class="mb-4">
            <div class="vSyllabusHeader">
                <h6>
                    LEARNING OUTCOMES
                    <span>
                        <i class="bi bi-info-circle-fill text-dark" data-toggle="tooltip" data-bs-placement="top" title="{{$inputFieldDescriptions['learningOutcomes']}}"></i>
                        <span class="d-inline-block has-tooltip " tabindex="0" data-toggle="tooltip" data-bs-placement="top" title="This section is required in your syllabus by Vancouver Senate policy V-130">
                            <button type="button" class="btn btn-danger btn-sm disabled mb-1" style="font-size:10px;">Required by policy</button> 
                        </span>
                    </span>
                </h6>
            </div>
            <p style="color:gray"><i>Upon successful completion of this course, students will be able to...</i></p>
            <p>{{$syllabus->learning_outcomes}}</p>
        </div>
        <!--  learning activities -->
        <div class="mb-4">
            <div class="vSyllabusHeader">
                <h6>
                    LEARNING ACTIVITIES
                    <span>
                        <i class="bi bi-info-circle-fill text-dark" data-toggle="tooltip" data-bs-placement="top" title="{{$inputFieldDescriptions['learningActivities']}}"></i>
                        <span class="d-inline-block has-tooltip " tabindex="0" data-toggle="tooltip" data-bs-placement="top" title="This section is required in your syllabus by Vancouver Senate policy V-130">
                            <button type="button" class="btn btn-danger btn-sm mb-1 disabled" style="font-size:10px;">Required by policy</button> 
                        </span>
                    </span>
                </h6>
            </div>
            <p>{{$syllabus->learning_activities}}</p>
        </div>
        <!--  learning materials -->
        <div class="mb-4">
            <div class="vSyllabusHeader">
                <h6>
                    LEARNING MATERIALS
                    <span>
                        <i class="bi bi-info-circle-fill text-dark" data-toggle="tooltip" data-bs-placement="top" title="{{$inputFieldDescriptions['learningMaterials']}}"></i>
                        <span class="d-inline-block has-tooltip " tabindex="0" data-toggle="tooltip" data-bs-placement="top" title="This section is required in your syllabus by Vancouver Senate policy V-130">
                            <button type="button" class="btn btn-danger btn-sm mb-1 disabled" style="font-size:10px;">Required by policy</button> 
                        </span>
                    </span>
                </h6>
            </div>
            <p>{{$syllabus->learning_materials}}</p>
        </div>
        <!--  assessments of learning -->
        <div class="mb-4">
            <div class="vSyllabusHeader">
                <h6>
                    ASSESSMENTS OF LEARNING
                    <span>
                        <i class="bi bi-info-circle-fill text-dark" data-toggle="tooltip" data-bs-placement="top" title="{{$inputFieldDescriptions['learningAssessments']}}"></i>
                        <span class="d-inline-block has-tooltip " tabindex="0" data-toggle="tooltip" data-bs-placement="top" title="This section is required in your syllabus by Vancouver Senate policy V-130">
                            <button type="button" class="btn btn-danger btn-sm mb-1 disabled" style="font-size:10px;">Required by policy</button> 
                        </span>
                    </span>
                </h6>
            </div>
            <p>{{$syllabus->learningAssessments}}</p>
        </div>
        <!--  passing criteria -->
        <div class="mb-4">
            <div class="vSyllabusHeader">
                <h6>PASSING/GRADING CRITERIA</h6>
            </div>
            <p>{{$syllabus->passing_criteria}}</p>
        </div>
        <!--  late policy -->
        <div class="mb-4">
            <div class="vSyllabusHeader">
                <h6>LATE POLICY
                    <span>
                        <i class="bi bi-info-circle-fill text-dark" data-toggle="tooltip" data-bs-placement="top" title="{{$inputFieldDescriptions['missedActivityPolicy']}}"></i>
                    </span>
                </h6>
            </div>
            <p>{{$syllabus->late_policy}}</p>
        </div>
        <!--  missed exam policy -->
        <div class="mb-4">
            <div class="vSyllabusHeader">
                <h6>MISSED EXAM POLICY</h6>
            </div>
            <p>{{$syllabus->missed_exam_policy}}</p>
        </div>
        <!--  missed activity policy -->
        <div class="mb-4">
            <div class="vSyllabusHeader">
                <h6>MISSED ACTIVITY POLICY
                    <span>
                        <i class="bi bi-info-circle-fill text-dark" data-toggle="tooltip" data-bs-placement="top" title="{{$inputFieldDescriptions['missedActivityPolicy']}}"></i>
                    </span>
                </h6>
            </div>
            <p>{{$syllabus->missed_activity_policy}}</p>
        </div>
        <!--  university policies -->
        <div class="mb-4">
            <div class="vSyllabusHeader">
                <h6>UNIVERSITY POLICIES</h6>
            </div>
            <p>UBC provides resources to support student learning and to maintain healthy lifestyles but recognizes that sometimes crises arise and so there are additional resources to access including those for survivors of sexual violence. UBC values respect for the person and ideas of all members of the academic community. Harassment and discrimination are not tolerated nor is suppression of academic freedom. UBC provides appropriate accommodation for students with disabilities and for religious observances. UBC values academic honesty and students are expected to acknowledge the ideas generated by others and to uphold the highest academic standards in all of their actions.
                
            Details of the policies and how to access support are available on the <a href="https://senate.ubc.ca/policies-resources-support-student-success" target="_blank" rel="noopener noreferrer">UBC Senate website</a>.</p>
        </div>
        <!-- other course policies -->
        <div class="mb-4">
            <div class="vSyllabusHeader mb-4">
                <h6>OTHER COURSE POLICIES</h6>
            </div>
            <!-- learning analytics -->
            <div class="mb-4">
                <div class="vSyllabusHeader2">
                    <h6>LEARNING ANALYTICS
                        <span>
                            <i class="bi bi-info-circle-fill text-dark" data-toggle="tooltip" data-bs-placement="top" title="{{$inputFieldDescriptions['learningAnalytics']}}"></i>
                        </span>
                    </h6>
                </div>
                <p>{{$vancouverSyllabus->learning_analytics}}</p>
            </div>
            <!-- learning resources -->
            <div class="mb-4">
                <div class="vSyllabusHeader2">
                    <h6>LEARNING RESOURCES
                        <span>
                            <i class="bi bi-info-circle-fill text-dark" data-toggle="tooltip" data-bs-placement="top" title="{{$inputFieldDescriptions['learningResources']}}"></i>
                        </span>
                    </h6>
                </div>
                <p>{{$syllabus->learning_resources}}</p>
            </div>
            @foreach ($vancouverSyllabusResources as $index => $resource) 
                @if (in_array($resource->id, $selectedVancouverSyllabusResourceIds) && $index != 0)
                <div class="mb-4">
                    <div class="vSyllabusHeader2">
                        <h6>{{strtoupper($resource->title)}}</h6>
                    </div>
                    @switch ($resource->id_name)
                        @case('academic')
                        <!-- academic integrity statement -->
                        <p>The academic enterprise is founded on honesty, civility, and integrity. As members of this enterprise, all students are expected to know, understand, and follow the codes of conduct regarding academic integrity. At the most basic level, this means submitting only original work done by you and acknowledging all sources of information or ideas and attributing them to others as required. This also means you should not cheat, copy, or mislead others about what is your work. Violations of academic integrity (i.e., misconduct) lead to the breakdown of the academic enterprise, and therefore serious consequences arise and harsh sanctions are imposed. For example, incidences of plagiarism or cheating may result in a mark of zero on the assignment or exam and more serious consequences may apply if the matter is referred to the President’s Advisory Committee on Student Discipline. Careful records are kept in order to monitor and prevent recurrences. A more detailed description of academic integrity, including the University’s policies and procedures, may be found in the Academic Calendar.</p>
                        @break

                        @case('disability')
                        <p><a href="https://students.ubc.ca/about-student-services/centre-for-accessibility" target="_blank" rel="noopener noreferrer">Centre for Accessibility</a></p>
                        @break

                        @case('copyright')
                        <p>All materials of this course (course handouts, lecture slides, assessments, course readings, etc.) are the intellectual property of the Course Instructor or licensed to be used in this course by the copyright owner. Redistribution of these materials by any means without permission of the copyright holder(s) constitutes a breach of copyright and may lead to academic discipline.</p>
                        @break

                        @case('concession')
                        <p>In accordance with <a href="https://senate.ubc.ca/sites/senate.ubc.ca/files/downloads/va_V-135.1_Academic-Concession_20200415.pdf">UBC Policy V135</a>, academic concessions are generally granted when students are facing an unexpected situation or circumstance that prevents them from completing graded work or exams. Students may request an academic concession for unanticipated changes in personal responsibilities that create a conflict, medical circumstances, or compassionate grounds.
                        <br>
                        <br>
                        In accordance with <a href="https://senate.ubc.ca/sites/senate.ubc.ca/files/downloads/va_V-135.1_Academic-Concession_20200415.pdf">UBC Policy V135</a>, Section 10, students’ requests for academic concession should be made as early as reasonably possible, in writing to their instructor or academic advising office or equivalent in accordance with the procedures for <a href="https://senate.ubc.ca/sites/senate.ubc.ca/files/downloads/va_V-135.1_Academic-Concession_20200415.pdf">Policy V135</a> and those set out by the student’s faculty/school. The requests should clearly state the grounds for the concession and the anticipated duration of the conflict and or hindrance to academic work. In some situations, this self-declaration is sufficient, but the submission of supporting documentation may be required along with, or following, the self-declaration.
                        </p>
                        @break

                    @endswitch
                </div>
                @endif
            @endforeach
        </div>
    </div>
    <!-- footer -->
    <div class="card-footer p-4">
            <button class="btn btn-primary dropdown-toggle m-2 col-4 float-right" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                Download
            </button>
            <ul class="dropdown-menu" aria-labelledby="dropdownMenu2">
                <li>
                    <form method="POST" action="{{ action('SyllabusController@download', [$syllabus->id, 'pdf']) }}">
                    @csrf        
                        <button type="submit" name="download" value="pdf" class="dropdown-item" type="button">
                            <i class="bi-file-pdf-fill text-danger"></i> PDF
                        </button>
                    </form>
                </li>
                <li>
                    <form method="POST" action="{{ action('SyllabusController@download', [$syllabus->id, 'word']) }}">
                    @csrf        
                        <button type="submit" name="download" value="word" class="dropdown-item" type="button">
                            <i class="bi-file-earmark-word-fill text-primary"></i> Word
                        </button>
                    </form>
                </li>
            </ul>

    </div>
</div>

<script type="application/javascript">
    $(document).ready(function () {

        $('[data-toggle="tooltip"]').tooltip();
    });

</script>

@endsection

