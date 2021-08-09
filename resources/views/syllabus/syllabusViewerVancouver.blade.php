
@extends('layouts.app')

@section('content')

<div class="card">
    <!-- header -->
    <div class="card-header wizard ">
        <h4>
            <b>Vancouver Syllabus: </b> <span class="fs-5">{{$syllabus->course_title}}, {{$syllabus->course_code}} {{$syllabus->course_num}}</span>
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
            <p><b>Instructor:</b> {{$syllabus->course_instructor}}</p>
            <p><b>Office Location:</b> {{$vancouverSyllabus->office_location}}</p>
            <p><b>Duration:</b> {{$syllabus->course_term}} {{$syllabus->course_year}}</p>
            <p><b>Class Location:</b> {{$syllabus->course_location}}</p>
            <p><b>Class Days:</b> {{$syllabus->class_meeting_days}}</p>
            <p><b>Class Hours:</b> {{$syllabus->class_start_time}} - {{$syllabus->class_end_time}}</p>
            <p><b>Office Hours:</b> {{$syllabus->office_hours}}</p>
        </div>
        <!-- course prerequisites -->
        <div class="mb-4">
            <div class="vSyllabusHeader2">
                <h6>PREREQUISITES</h6>
            </div>
            <p>{{$vancouverSyllabus->course_prereqs}}</p>
        </div>
        <!-- course corequisites -->
        <div class="mb-4">
            <div class="vSyllabusHeader2">
                <h6>COREQUISITES</h6>
            </div>
            <p>{{$vancouverSyllabus->course_coreqs}}</p>
        </div>
        <!-- course contacts -->
        <div class="mb-4">
            <div class="vSyllabusHeader">
                <h6>CONTACTS</h6>
            </div>
            <p>{{$vancouverSyllabus->contacts}}</p>
        </div>
        <!-- course instructor biographical statement -->
        <div class="mb-4">
            <div class="vSyllabusHeader">
                <h6>COURSE INSTRUCTOR BIOGRAPHICAL STATEMENT</h6>
            </div>
            <p>{{$vancouverSyllabus->instructor_bio}}</p>
        </div>
        <!-- other instructional staff -->
        <div class="mb-4">
            <div class="vSyllabusHeader">
                <h6>OTHER INSTRUCTIONAL STAFF</h6>
            </div>
            <p>{{$syllabus->other_instructional_staff}}</p>
        </div>
        <!-- course structure -->
        <div class="mb-4">
            <div class="vSyllabusHeader">
                <h6>COURSE STRUCTURE</h6>
            </div>
            <p>{{$vancouverSyllabus->course_structure}}</p>
        </div>
        <!-- schedule of topics -->
        <div class="mb-4">
            <div class="vSyllabusHeader">
                <h6>SCHEDULE OF TOPICS</h6>
            </div>
            <p>{{$vancouverSyllabus->course_schedule}}</p>
        </div>
        <!--  learning outcomes -->
        <div class="mb-4">
            <div class="vSyllabusHeader">
                <h6>LEARNING OUTCOMES</h6>
            </div>
            <p>{{$syllabus->learning_outcomes}}</p>
        </div>
        <!--  learning activities -->
        <div class="mb-4">
            <div class="vSyllabusHeader">
                <h6>LEARNING ACTIVITIES</h6>
            </div>
            <p>{{$syllabus->learning_activities}}</p>
        </div>
        <!--  learning materials -->
        <div class="mb-4">
            <div class="vSyllabusHeader">
                <h6>LEARNING MATERIALS</h6>
            </div>
            <p>{{$syllabus->learning_materials}}</p>
        </div>
        <!--  assessments of learning -->
        <div class="mb-4">
            <div class="vSyllabusHeader">
                <h6>ASSESSMENTS OF LEARNING</h6>
            </div>
            <p>{{$syllabus->learningAssessments}}</p>
        </div>
        <!--  passing criteria -->
        <div class="mb-4">
            <div class="vSyllabusHeader">
                <h6>PASSING CRITERIA</h6>
            </div>
            <p>{{$syllabus->passing_criteria}}</p>
        </div>
        <!--  late policy -->
        <div class="mb-4">
            <div class="vSyllabusHeader">
                <h6>LATE POLICY</h6>
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
                <h6>MISSED ACTIVITY POLICY</h6>
            </div>
            <p>{{$syllabus->missed_activity_policy}}</p>
        </div>
        <!--  university policies -->
        <div class="mb-4">
            <div class="vSyllabusHeader">
                <h6>UNIVERSITY POLICIES</h6>
            </div>
            <p>UBC provides resources to support student learning and to maintain healthy lifestyles but recognizes that sometimes crises arise and so there are additional resources to access including those for survivors of sexual violence. UBC values respect for the person and ideas of all members of the academic community. Harassment and discrimination are not tolerated nor is suppression of academic freedom. UBC provides appropriate accommodation for students with disabilities and for religious observances. UBC values academic honesty and students are expected to acknowledge the ideas generated by others and to uphold the highest academic standards in all of their actions.
                
            Details of the policies and how to access support are available on the <a href="https://senate.ubc.ca/policies-resources-support-student-success" target="_blank">UBC Senate website</a>.</p>
        </div>
        <!-- other course policies -->
        <div class="mb-4">
            <div class="vSyllabusHeader mb-4">
                <h6>OTHER COURSE POLICIES</h6>
            </div>
            <!-- learning analytics -->
            <div class="mb-4">
                <div class="vSyllabusHeader2">
                    <h6>LEARNING ANALYTICS</h6>
                </div>
                <p>{{$vancouverSyllabus->learning_analytics}}</p>
            </div>
            <!-- learning resources -->
            <div class="mb-4">
                <div class="vSyllabusHeader2">
                    <h6>LEARNING RESOURCES</h6>
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
                        <p><a href="https://students.ubc.ca/about-student-services/centre-for-accessibility" target="_blank">Centre for Accessibility</a></p>
                        @break

                        @case('copyright')
                        <p>All materials of this course (course handouts, lecture slides, assessments, course readings, etc.) are the intellectual property of the Course Instructor or licensed to be used in this course by the copyright owner. Redistribution of these materials by any means without permission of the copyright holder(s) constitutes a breach of copyright and may lead to academic discipline.</p>
                        @break

                    @endswitch
                </div>
                @endif
            @endforeach
        </div>
    </div>
    <!-- footer -->
    <div class="card-footer p-4">
        <button type="button" class="btn btn-primary col-2 btn-sm m-2 float-right" >Download <i class="bi bi-download"></i></button>
    </div>
</div>

@endsection

