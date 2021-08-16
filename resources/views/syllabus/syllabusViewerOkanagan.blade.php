
@extends('layouts.app')

@section('content')

<div class="card">
    <!-- header -->
    <div class="card-header wizard ">
        <h4>
            {{$syllabus->course_title}}, {{$syllabus->course_code}} {{$syllabus->course_num}}
        </h4>
    </div>
    <!-- body -->
    <div class="card-body">
        <!-- land acknowledgement -->
        @if (in_array($okanaganSyllabusResources[0]->id, $selectedOkanaganSyllabusResourceIds))
        <div class="mb-4">
            <div class="oSyllabusHeader">
                <h6>{{$okanaganSyllabusResources[0]->title}}</h6>
            </div>
            <p>We respectfully acknowledge the Syilx Okanagan Nation and their peoples, in whose traditional, ancestral, unceded territory UBC Okanagan is situated.</p>
        </div>
        @endif
        <!-- course information -->
        <div class="mb-4">
            <div class="oSyllabusHeader text-decoration-none">
                <h5>{{$syllabus->course_code}} {{$syllabus->course_num}}: {{$syllabus->course_title}}</h5>
            </div>
            <p><b>Campus:</b> @if ($syllabus->campus == 'V') Vancouver @else Okanagan @endif</p>
            <p><b>Instructor:</b> {{$syllabus->course_instructor}}</p>
            <p><b>Duration:</b> {{$syllabus->course_term}} {{$syllabus->course_year}}</p>
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
        <!-- other instructional staff -->
        <div class="mb-4">
            <div class="oSyllabusHeader">
                <h6>
                    Other Instructional Staff
                    <span>
                        <i class="bi bi-info-circle-fill text-dark" data-toggle="tooltip" data-bs-placement="top" title="{{$inputFieldDescriptions['otherCourseStaff']}}"></i>
                    </span>
                </h6>
            </div>
            <p>{{$syllabus->other_instructional_staff}}</p>
        </div>
        <!-- course format -->
        <div class="mb-4">
            <div class="oSyllabusHeader">
                <h6>
                    Course Format
                    <span>
                        <i class="bi bi-info-circle-fill text-dark" data-toggle="tooltip" data-bs-placement="top" title="{{$inputFieldDescriptions['courseStructure']}}"></i>
                    </span>
                </h6>
            </div>
            <p>{{$okanaganSyllabus->course_format}}</p>
        </div>
        <!-- Course Overview, Content and Objectives -->
        <div class="mb-4">
            <div class="oSyllabusHeader">
                <h6>
                    Course Overview, Content and Objectives
                </h6>
            </div>
            <p>{{$okanaganSyllabus->course_overview}}</p>
        </div>
        <!--  learning outcomes -->
        <div class="mb-4">
            <div class="oSyllabusHeader">
                <h6>
                    Learning Outcomes
                    <span>
                        <i class="bi bi-info-circle-fill text-dark" data-toggle="tooltip" data-bs-placement="top" title="{{$inputFieldDescriptions['learningOutcomes']}}"></i>
                    </span>
                </h6>
            </div>
            <p style="color:gray"><i>Upon successful completion of this course, students will be able to...</i></p>
            <p>{{$syllabus->learning_outcomes}}</p>
        </div>
        <!--  learning activities -->
        <div class="mb-4">
            <div class="oSyllabusHeader">
                <h6>
                    Learning Activities
                    <span>
                        <i class="bi bi-info-circle-fill text-dark" data-toggle="tooltip" data-bs-placement="top" title="{{$inputFieldDescriptions['learningActivities']}}"></i>
                    </span>
                </h6>
            </div>
            <p>{{$syllabus->learning_activities}}</p>
        </div>
        <!--  assessments of learning -->
        <div class="mb-4">
            <div class="oSyllabusHeader">
                <h6>
                    Assessments of Learning
                    <span>
                        <i class="bi bi-info-circle-fill text-dark" data-toggle="tooltip" data-bs-placement="top" title="{{$inputFieldDescriptions['learningAssessments']}}"></i>
                    </span>
                </h6>
            </div>
            <p>{{$syllabus->learningAssessments}}</p>
        </div>
        <!--  late policy -->
        <div class="mb-4">
            <div class="oSyllabusHeader">
                <h6>Late Policy
                    <span>
                        <i class="bi bi-info-circle-fill text-dark" data-toggle="tooltip" data-bs-placement="top" title="{{$inputFieldDescriptions['missedActivityPolicy']}}"></i>
                    </span>
                </h6>
            </div>
            <p>{{$syllabus->late_policy}}</p>
        </div>
        <!--  missed exam policy -->
        <div class="mb-4">
            <div class="oSyllabusHeader">
                <h6>Missed Exam Policy</h6>
            </div>
            <p>{{$syllabus->missed_exam_policy}}</p>
        </div>
        <!--  missed activity policy -->
        <div class="mb-4">
            <div class="oSyllabusHeader">
                <h6>Missed Activity Policy
                    <span>
                        <i class="bi bi-info-circle-fill text-dark" data-toggle="tooltip" data-bs-placement="top" title="{{$inputFieldDescriptions['missedActivityPolicy']}}"></i>
                    </span>
                </h6>
            </div>
            <p>{{$syllabus->missed_activity_policy}}</p>
        </div>
        <!--  passing criteria -->
        <div class="mb-4">
            <div class="oSyllabusHeader">
                <h6>Passing Criteria</h6>
            </div>
            <p>{{$syllabus->passing_criteria}}</p>
        </div>
        <!--  learning materials -->
        <div class="mb-4">
            <div class="oSyllabusHeader">
                <h6>
                    Learning Materials
                    <span>
                        <i class="bi bi-info-circle-fill text-dark" data-toggle="tooltip" data-bs-placement="top" title="{{$inputFieldDescriptions['learningMaterials']}}"></i>
                    </span>
                </h6>
            </div>
            <p>{{$syllabus->learning_materials}}</p>
        </div>
        <!-- other course policies -->
        <div class="mb-4">
            <div class="oSyllabusHeader mb-4 text-decoration-none">
                <h5>Other Course Policies and Student Service Resources</h5>
            </div>
            <!-- learning resources -->
            <div class="mb-4">
                <div class="oSyllabusHeader">
                    <h6>Learning Resources
                        <span>
                            <i class="bi bi-info-circle-fill text-dark" data-toggle="tooltip" data-bs-placement="top" title="{{$inputFieldDescriptions['learningResources']}}"></i>
                        </span>
                    </h6>
                </div>
                <p>{{$syllabus->learning_resources}}</p>
            </div>
            @foreach ($okanaganSyllabusResources as $index => $resource) 
                @if (in_array($resource->id, $selectedOkanaganSyllabusResourceIds) && $index != 0)
                <div class="mb-4">
                    <div class="oSyllabusHeader" @if ($resource->id_name == 'safewalk') style="text-align:center" @endif>
                        <h6>{{$resource->title}}</h6>
                    </div>
                    @switch ($resource->id_name)
                        @case('academic')
                        <!-- academic integrity statement -->
                        <p>The academic enterprise is founded on honesty, civility, and integrity.  As members of this enterprise, all students are expected to know, understand, and follow the codes of conduct regarding academic integrity.  At the most basic level, this means submitting only original work done by you and acknowledging all sources of information or ideas and attributing them to others as required.  This also means you should not cheat, copy, or mislead others about what is your work.  Violations of academic integrity (i.e., misconduct) lead to the breakdown of the academic enterprise, and therefore serious consequences arise and harsh sanctions are imposed.  For example, incidences of plagiarism or cheating usually result in a failing grade or mark of zero on the assignment or in the course.  Careful records are kept to monitor and prevent recidivism.
                        <br>
                        <br>    
                        A more detailed description of academic integrity, including the University’s policies and procedures, may be found in the <a href="http://www.calendar.ubc.ca/okanagan/index.cfm?tree=3,54,111,0" target="_blank">Academic Calendar</a></p>
                        @break

                        @case('finals')
                        <p>The examination period for Term X of Fall 201X is XXXX.  Except in the case of examination clashes and hardships (three or more formal examinations scheduled within a 24-hour period) or unforeseen events, students will be permitted to apply for out-of-time final examinations only if they are representing the University, the province, or the country in a competition or performance; serving in the Canadian military; observing a religious rite; working to support themselves or their family; or caring for a family member.  Unforeseen events include (but may not be limited to) the following: ill health or other personal challenges that arise during a term and changes in the requirements of an ongoing job.  
                        <br>
                        <br>
                        Further information on Academic Concession can be found under Policies and Regulation in the Okanagan Academic Calendar <a href="http://www.calendar.ubc.ca/okanagan/index.cfm?tree=3,48,0,0">http://www.calendar.ubc.ca/okanagan/index.cfm?tree=3,48,0,0</a>
                        </p>
                        @break

                        @case('grading')
                        <p>Faculties, departments, and schools reserve the right to scale grades in order to maintain equity among sections and conformity to University, faculty, department, or school norms. Students should therefore note that an unofficial grade given by an instructor might be changed by the faculty, department, or school. Grades are not official until they appear on a student's academic record.
                        <a href="http://www.calendar.ubc.ca/okanagan/index.cfm?tree=3,41,90,1014">http://www.calendar.ubc.ca/okanagan/index.cfm?tree=3,41,90,1014</a>
                        </p>
                        @break

                        @case('disability')
                        <p>The Disability Resource Centre ensures educational equity for students with disabilities and chronic medical conditions. If you are disabled, have an injury or illness and require academic accommodations to meet the course objectives, please contact Earllene Roberts, the Manager for the Disability Resource Centre located in the University Centre building (UNC 214).
                        <br>
                        <br>
                        <b>UNC 214</b> 	250.807.9263
                        email: <a href="earllene.roberts@ubc.ca">earllene.roberts@ubc.ca</a>
                        Web: <a href="www.students.ok.ubc.ca/drc">www.students.ok.ubc.ca/drc</a></p>
                        @break

                        @case('equity')
                        <p>Through leadership, vision, and collaborative action, the Equity & Inclusion Office (EIO) develops action strategies in support of efforts to embed equity and inclusion in the daily operations across the campus. The EIO provides education and training from cultivating respectful, inclusive spaces and communities to understanding unconscious/implicit bias and its operation within in campus environments. UBC Policy 3 prohibits discrimination and harassment on the basis of BC’s Human Rights Code. If you require assistance related to an issue of equity, educational programs, discrimination or harassment please contact the EIO.
                        <br>
                        <br>
                        <b>UNC 216</b> 	250.807.9291
                        email: <a href="equity.ubco@ubc.ca">equity.ubco@ubc.ca</a>
                        Web: <a href="www.equity.ok.ubc.ca">www.equity.ok.ubc.ca</a>
                        </p>
                        @break

                        @case('health')
                        <p>At UBC Okanagan health services to students are provided by Health and Wellness.  Nurses, physicians and counsellors provide health care and counselling related to physical health, emotional/mental health and sexual/reproductive health concerns. As well, health promotion, education and research activities are provided to the campus community.  If you require assistance with your health, please contact Health and Wellness for more information or to book an appointment.
                        <br>
                        <br>
                        <b>UNC 337</b> 	250.807.9270
                        email: <a href="healthwellness.okanagan@ubc.ca">healthwellness.okanagan@ubc.ca</a>
                        Web: <a href="www.students.ok.ubc.ca/health-wellness">www.students.ok.ubc.ca/health-wellness</a>
                        </p>
                        @break

                        @case('student')
                        <p>The Student Learning Hub (LIB 237) is your go-to resource for free math, science, writing, and language learning support. The Hub welcomes undergraduate students from all disciplines and year levels to access a range of supports that include tutoring in math, sciences, languages, and writing, as well as help with study skills and learning strategies. For more information, please visit the Hub’s website (<a href="https://students.ok.ubc.ca/student-learning-hub/">https://students.ok.ubc.ca/student-learning-hub/</a>) or call 250-807-9185.</p>
                        @break

                        @case('copyright')
                        <p>All materials of this course (course handouts, lecture slides, assessments, course readings, etc.) are the intellectual property of the Course Instructor or licensed to be used in this course by the copyright owner. Redistribution of these materials by any means without permission of the copyright holder(s) constitutes a breach of copyright and may lead to academic discipline.</p>
                        @break

                        @case('safewalk')
                        <p class="text-center">Don't want to walk alone at night?  Not too sure how to get somewhere on campus? Call Safewalk at 250-807-8076. 
                        <br>
                        For more information, see: <a href="www.security.ok.ubc.ca">www.security.ok.ubc.ca</a>
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
        <form method="POST" action="{{ action('SyllabusController@syllabusToWordDoc', $syllabus->id) }}">
            @csrf        
            <button type="submit" class="btn btn-primary col-2 btn-sm m-2 float-right">Download <i class="bi bi-download"></i></button>
        </form>
    </div>
</div>

<script type="application/javascript">
    $(document).ready(function () {

        $('[data-toggle="tooltip"]').tooltip();
    });

</script>

@endsection

