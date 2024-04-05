
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
        @if (in_array($okanaganSyllabusResources[0]->id, $selectedOkanaganSyllabusResourceIds))
        <div class="mb-4">
            <div>
                <h6 class="oSyllabusHeader">{{$okanaganSyllabusResources[0]->title}}</h6>
            </div>
            <p>We respectfully acknowledge the Syilx Okanagan Nation and their peoples, in whose traditional, ancestral, unceded territory UBC Okanagan is situated.</p>
        </div>
        @endif
        <!-- course information -->
        <div class="mb-4">
            <div>
                <h5 class="oSyllabusHeader text-decoration-none">{{$syllabus->course_code}} {{$syllabus->course_num}}: {{$syllabus->course_title}}</h5>
            </div>
            <p><b>Campus:</b> @if ($syllabus->campus == 'V') Vancouver @else Okanagan @endif</p>
            <p><b>Faculty:</b> {{$syllabus->faculty}}</p>
            <p><b>Department:</b> {{$syllabus->department}}</p>
            <p><b>Instructor(s):</b> {{$syllabusInstructors}}</p>
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
        <!-- other instructional staff -->
        <div class="mb-4">
            <div>
                <h6 class="oSyllabusHeader">
                    Other Instructional Staff
                    <span>
                        <i class="bi bi-info-circle-fill text-dark" data-toggle="tooltip" data-bs-placement="top" title="{{$inputFieldDescriptions['otherCourseStaff']}}"></i>
                    </span>
                </h6>
            </div>
            <table class="table table-light table-bordered">

                <thead>
                    <tr class="table-primary">
                        <th style="width:5%"></th>
                        <th>Other Instructional Staff</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach (explode(PHP_EOL, $syllabus->other_instructional_staff) as $index => $staff)
                        <tr>
                            <td>{{$index + 1}}</td>
                            <td>{{$staff}}</td>
                        </tr>
                    @endforeach                                               
                </tbody>
            </table>                                    
        </div>

               <!-- course description -->
               <div class="mb-4">
            <div>
                <h6 class="oSyllabusHeader">
                    Course Description
                    <span>
                        <i class="bi bi-info-circle-fill text-dark" data-toggle="tooltip" data-bs-placement="top" title="{{$inputFieldDescriptions['okanaganCourseDescription']}}"></i>
                    </span>
                </h6>
            </div>
            @php ($course_description=explode("\n",$okanaganSyllabus->course_description))
            @foreach($course_description as $course_desc)
            <p>{{$course_desc}}</p>
            @endforeach 
            
        </div>
        <!-- course format -->
        <div class="mb-4">
            <div>
                <h6 class="oSyllabusHeader">
                    Course Format
                    <span>
                        <i class="bi bi-info-circle-fill text-dark" data-toggle="tooltip" data-bs-placement="top" title="{{$inputFieldDescriptions['courseStructure']}}"></i>
                    </span>
                </h6>
            </div>
            @php ($course_format=explode("\n",$okanaganSyllabus->course_format))
            @foreach($course_format as $course_form)
            <p>{{$course_form}}</p>
            @endforeach 
        </div>
        <!-- Course Overview, Content and Objectives -->
        <div class="mb-4">
            <div>
                <h6 class="oSyllabusHeader">
                    Course Overview, Content and Objectives
                </h6>
            </div>
            @php ($course_overview=explode("\n",$okanaganSyllabus->course_overview))
            @foreach($course_overview as $course_over)
            <p>{{$course_over}}</p>
            @endforeach 
        </div>

        
        <!--  learning outcomes -->
        <div class="mb-4">
            <div>
                <h6 class="oSyllabusHeader">
                    Learning Outcomes
                    <span>
                        <i class="bi bi-info-circle-fill text-dark" data-toggle="tooltip" data-bs-placement="top" title="{{$inputFieldDescriptions['learningOutcomes']}}"></i>
                    </span>
                </h6>
            </div>
            <p style="color:gray"><i>Upon successful completion of this course, students will be able to...</i></p>
            <table class="table table-light table-bordered">
                <thead>
                    <tr class="table-primary">
                        <th style="width:5%"></th>
                        <th>Learning Outcome</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach (explode(PHP_EOL, $syllabus->learning_outcomes) as $index => $learningOutcome)
                        <tr>
                            <td>{{$index + 1}}</td>
                            <td>{{$learningOutcome}}</td>
                        </tr>
                    @endforeach                                               
                </tbody>
            </table>                                    
        </div>
        <!--  learning activities -->
        <div class="mb-4">
            <div>
                <h6 class="oSyllabusHeader">
                    Learning Activities
                    <span>
                        <i class="bi bi-info-circle-fill text-dark" data-toggle="tooltip" data-bs-placement="top" title="{{$inputFieldDescriptions['learningActivities']}}"></i>
                    </span>
                </h6>
            </div>
            <table class="table table-light table-bordered">
                <thead>
                    <tr class="table-primary">
                        <th style="width:5%"></th>
                        <th>Teaching and Learning Activity</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach (explode(PHP_EOL, $syllabus->learning_activities) as $index => $learningActivity)
                        <tr>
                            <td>{{$index + 1}}</td>
                            <td>{{$learningActivity}}</td>
                        </tr>
                    @endforeach                                               
                </tbody>
            </table>  
        </div>
        <!--  learning materials -->
        <div class="mb-4">
            <div>
                <h6 class="oSyllabusHeader">
                    Learning Materials
                    <span>
                        <i class="bi bi-info-circle-fill text-dark" data-toggle="tooltip" data-bs-placement="top" title="{{$inputFieldDescriptions['learningMaterials']}}"></i>
                    </span>
                </h6>
            </div>
            @php ($learning_materials=explode("\n",$syllabus->learning_materials))
            @foreach($learning_materials as $learning_mat)
            <p>{{$learning_mat}}</p>
            @endforeach 
        </div>

        <!-- learning resources -->
        <div class="mb-4">
                <div>
                    <h6 class="oSyllabusHeader">Learning Resources
                        <span>
                            <i class="bi bi-info-circle-fill text-dark" data-toggle="tooltip" data-bs-placement="top" title="{{$inputFieldDescriptions['learningResources']}}"></i>
                        </span>
                    </h6>
                </div>
                @php ($learning_resources=explode("\n",$syllabus->learning_resources))
                @foreach($learning_resources as $learning_res)
                <p>{{$learning_res}}</p>
                @endforeach 
        </div>

        <!--  assessments of learning -->
        <div class="mb-4">
            <div>
                <h6 class="oSyllabusHeader">
                    Assessments of Learning
                    <span>
                        <i class="bi bi-info-circle-fill text-dark" data-toggle="tooltip" data-bs-placement="top" title="{{$inputFieldDescriptions['learningAssessments']}}"></i>
                    </span>
                </h6>
            </div>
            <table class="table table-light table-bordered">
                <thead>
                    <tr class="table-primary">
                        <th style="width:5%"></th>
                        <th>Learning Assessment</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach (explode(PHP_EOL, $syllabus->learning_assessments) as $index => $learningAssessments)
                        <tr>
                            <td>{{$index + 1}}</td>
                            <td>{{$learningAssessments}}</td>
                        </tr>
                    @endforeach                                               
                </tbody>
            </table>                                    
        </div>

 
        <!--  course alignment table -->
        @if (isset($courseAlignment))
        
            <div class="mb-4">
                <div>
                    <h6 class="oSyllabusHeader">
                        Course Alignment
                    </h6>
                </div>
                <table class="table table-light table-bordered " >
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
        @endif
        @if (isset($outcomeMaps))
            <div class="p-0 m-0" id="outcomeMapsDiv">  
                @foreach ($outcomeMaps as $programId => $outcomeMap)
                    <div class="p-0 m-0" id="outcomeMapsDiv"> 
                        <h5 class="fw-bold pt-4 mb-2 col-12 pt-4 mb-4 mt-2">
                            {{$outcomeMap["program"]->program}}                 
                            
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
        <!--  course schedule table -->
        <div class="mb-4">
            <div>
                <h6 class="oSyllabusHeader">
                    Course Schedule
                    <span>
                        <i class="bi bi-info-circle-fill text-dark" data-toggle="tooltip" data-bs-placement="top" title="{{$inputFieldDescriptions['courseSchedule']}}"></i>
                    </span>
                </h6>
            </div>
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
       
        <!--  learning materials -->
        <div class="mb-4">
            <div>
                <h6 class="oSyllabusHeader">
                    Learning Materials
                    <span>
                        <i class="bi bi-info-circle-fill text-dark" data-toggle="tooltip" data-bs-placement="top" title="{{$inputFieldDescriptions['learningMaterials']}}"></i>
                    </span>
                </h6>
            </div>
            @php ($learning_materials=explode("\n",$syllabus->learning_materials))
            @foreach($learning_materials as $learning_mat)
            <p>{{$learning_mat}}</p>
            @endforeach  
        </div>
    
        <!-- other course policies -->
        <div class="mb-4">
            <div>
                <h5 class="oSyllabusHeader mb-4 text-decoration-none">Other Course Policies</h5>
            </div>
         <!--  late policy -->
         <div class="mb-4">
            <div>
                <h6 class="oSyllabusHeader">Late Policy
                    <span>
                        <i class="bi bi-info-circle-fill text-dark" data-toggle="tooltip" data-bs-placement="top" title="{{$inputFieldDescriptions['latePolicy']}}"></i>
                    </span>
                </h6>
            </div>
            
            @php ($late_policy=explode("\n",$syllabus->late_policy))
            @foreach($late_policy as $late_pol)
            <p>{{$late_pol}}</p>
            @endforeach  
        </div>
        <!--  missed exam policy -->
        <div class="mb-4">
            <div>
                <h6  class="oSyllabusHeader">Missed Exam Policy</h6>
            </div>
            @php ($missed_exam_policy=explode("\n",$syllabus->missed_exam_policy))
            @foreach($missed_exam_policy as $missed_exam)
            <p>{{$missed_exam}}</p>
            @endforeach  
            
        </div>
        <!--  missed activity policy -->
        <div class="mb-4">
            <div>
                <h6 class="oSyllabusHeader">Missed Activity Policy</h6>
            </div>
            @php ($missed_activity_policy=explode("\n",$syllabus->missed_activity_policy))
            @foreach($missed_activity_policy as $missed_activity)
            <p>{{$missed_activity}}</p>
            @endforeach  
        </div>
        <!--  passing criteria -->
        <div class="mb-4">
            <div>
                <h6 class="oSyllabusHeader">Passing/Grading Criteria</h6>
            </div>
            @php ($passing_criteria=explode("\n",$syllabus->passing_criteria))
            @foreach($passing_criteria as $passing_crit)
            <p>{{$passing_crit}}</p>
            @endforeach  
        </div>
</div>
        
        <!-- Additional Course-Specific Information-->
        <div class="mb-4">
                    <div>
                        <h6 class="oSyllabusHeader">{{ !empty($syllabus) ? $syllabus->custom_resource_title : ''}}</h6>
                    </div>
                    @php ($custom_resource=explode("\n",$syllabus->custom_resource))
                    @foreach($custom_resource as $custom_res)
                    <p>{{$custom_res}}</p>
                    @endforeach  
        </div>

        <!-- student services resources -->
        <div class="mb-4">
            <div>
                <h5 class="oSyllabusHeader mb-4 text-decoration-none">Student Service Resources</h5>
            </div>
            
            @foreach ($okanaganSyllabusResources as $index => $resource) 
                @if (in_array($resource->id, $selectedOkanaganSyllabusResourceIds) && $index != 0)
                <div class="mb-4">
                    <div @if ($resource->id_name == 'safewalk') style="text-align:center" @endif>
                        <h6 class="oSyllabusHeader">{{$resource->title}}</h6>
                    </div>
                    @switch ($resource->id_name)
                        @case('academic')
                        <!-- academic integrity statement -->
                        <p class="text-center">The academic enterprise is founded on honesty, civility, and integrity.  As members of this enterprise, all students are expected to know, understand, and follow the codes of conduct regarding academic integrity.  At the most basic level, this means submitting only original work done by you and acknowledging all sources of information or ideas and attributing them to others as required.  This also means you should not cheat, copy, or mislead others about what is your work.  Violations of academic integrity (i.e., misconduct) lead to the breakdown of the academic enterprise, and therefore serious consequences arise and harsh sanctions are imposed.  For example, incidences of plagiarism or cheating usually result in a failing grade or mark of zero on the assignment or in the course.  Careful records are kept to monitor and prevent recidivism.
                        <br>
                        <br>    
                        A more detailed description of academic integrity, including the University’s policies and procedures, may be found in the <a href="http://www.calendar.ubc.ca/okanagan/index.cfm?tree=3,54,111,0" target="_blank" rel="noopener noreferrer">Academic Calendar</a></p>
                        @break

                        @case('finals')
                        <p class="text-center">The examination period for Term X of Fall 201X is XXXX.  Except in the case of examination clashes and hardships (three or more formal examinations scheduled within a 24-hour period) or unforeseen events, students will be permitted to apply for out-of-time final examinations only if they are representing the University, the province, or the country in a competition or performance; serving in the Canadian military; observing a religious rite; working to support themselves or their family; or caring for a family member.  Unforeseen events include (but may not be limited to) the following: ill health or other personal challenges that arise during a term and changes in the requirements of an ongoing job.  
                        <br>
                        <br>
                        Further information on Academic Concession can be found under Policies and Regulation in the Okanagan Academic Calendar <a href="http://www.calendar.ubc.ca/okanagan/index.cfm?tree=3,48,0,0">http://www.calendar.ubc.ca/okanagan/index.cfm?tree=3,48,0,0</a>
                        </p>
                        @break

                        @case('grading')
                        <p class="text-center">Faculties, departments, and schools reserve the right to scale grades in order to maintain equity among sections and conformity to University, faculty, department, or school norms. Students should therefore note that an unofficial grade given by an instructor might be changed by the faculty, department, or school. Grades are not official until they appear on a student's academic record.
                        <a href="http://www.calendar.ubc.ca/okanagan/index.cfm?tree=3,41,90,1014">http://www.calendar.ubc.ca/okanagan/index.cfm?tree=3,41,90,1014</a>
                        </p>
                        @break

                        @case('disability')
                        <p class="text-center">The DRC facilitates disability-related accommodations and programming initiatives to remove barriers for students with disabilities and ongoing medical conditions. If you require academic accommodations to achieve the objectives of a course please contact the DRC at:
                        <br>
                        <br>
                        <b>UNC 215</b> 	250.807.8053
                        email: <a href="drc.questions@ubc.ca">drc.questions@ubc.ca</a>
                        Web: <a href="http://www.students.ok.ubc.ca/drc">http://www.students.ok.ubc.ca/drc</a></p>
                        @break

                        @case('equity')
                        <p class="text-center">Through leadership, vision, and collaborative action, the Equity & Inclusion Office (EIO) develops action strategies in support of efforts to embed equity and inclusion in the daily operations across the campus. The EIO provides education and training from cultivating respectful, inclusive spaces and communities to understanding unconscious/implicit bias and its operation within in campus environments. UBC Policy 3 prohibits discrimination and harassment on the basis of BC’s Human Rights Code. If you require assistance related to an issue of equity, educational programs, discrimination or harassment please contact the EIO.
                        <br>
                        <br>
                        <b>UNC 216</b> 	250.807.9291
                        email: <a href="equity.ubco@ubc.ca">equity.ubco@ubc.ca</a>
                        Web: <a href="www.equity.ok.ubc.ca">www.equity.ok.ubc.ca</a>
                        </p>
                        @break

                        @case('health')
                        <p class="text-center">At UBC Okanagan health services to students are provided by Student Wellness.  Nurses, physicians and counsellors provide health care and counselling related to physical health, emotional/mental health and sexual/reproductive health concerns. As well, health promotion, education and research activities are provided to the campus community.  If you require assistance with your health, please contact Student Wellness for more information or to book an appointment.<br>
                        <br>
                        <b>UNC 337</b> 	250.807.9270
                        email: <a href="healthwellness.okanagan@ubc.ca">healthwellness.okanagan@ubc.ca</a>
                        Web: <a href="www.students.ok.ubc.ca/health-wellness">www.students.ok.ubc.ca/health-wellness</a>
                        </p>
                        @break

                        @case('ombud')
                        <p class="text-center">The Office of the Ombudsperson for Students is an independent, confidential and impartial resource to ensure students are treated fairly. The Ombuds Office helps students navigate campus-related fairness concerns. They work with UBC community members individually and at the systemic level to ensure students are treated fairly and can learn, work and live in a fair, equitable and respectful environment. Ombuds helps students gain clarity on UBC policies and procedures, explore options, identify next steps, recommend resources, plan strategies and receive objective feedback to promote constructive problem solving. If you require assistance, please feel free to reach out for more information or to arrange an appointment.
                        <br>
                        <br>
                        <b>UNC 328</b> 	250.807.9818
                        email: <a href="ombuds.office.ok@ubc.ca ">ombuds.office.ok@ubc.ca </a>
                        Web: <a href="www.ombudsoffice.ubc.ca">www.ombudsoffice.ubc.ca</a>
                        </p>
                        @break

                        @case('student')
                        <p class="text-center">The Student Learning Hub (LIB 237) is your go-to resource for free math, science, writing, and language learning support. The Hub welcomes undergraduate students from all disciplines and year levels to access a range of supports that include tutoring in math, sciences, languages, and writing, as well as help with study skills and learning strategies. Students are encouraged to visit often and early to build the skills, strategies and behaviours that are essential to being a confident and independent learner. For more information, please visit the Hub’s website.
                        <br>
                        <br>
                        <b>LIB 237</b> 	250.807.8491
                        email: <a href="learning.hub@ubc.ca">learning.hub@ubc.ca</a>
                        Web: <a href="https://www.students.ok.ubc.ca/slh">https://www.students.ok.ubc.ca/slh</a></p>
                        @break

                        @case('global')
                        <p class="text-center">The Global Engagement Office provides advising and resources to assist International students in navigating immigration, health insurance, and settlement matters, as well as opportunities for intercultural learning, and resources for Go Global experiences available to all UBC Okanagan students, and more.
                        <br><br>
                        Come and see us – we are here to help! You may also contact <a href="geo.ubco@ubc.ca">geo.ubco@ubc.ca</a></p>

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
        <div class="mb-4">

        @if($syllabus->copyright==1 && $syllabus->cc_license==NULL)
            <p class="text-center">
            <h6><strong><u> Copyright Statement </u></strong></h6></n>
            All materials of this course (course handouts, lecture slides, assessments, course readings, etc.) are the intellectual property of the Course Instructor or licensed to be used in this course by the copyright owner. Redistribution of these materials by any means without permission of the copyright holder(s) constitutes a breach of copyright and may lead to academic discipline.</p>
        @else
            @if($syllabus->cc_license!=NULL)
                <p class="text-center">
                <h6><strong><u>Creative Commons Open Copyright License</u></strong></h6></n>
                {{$syllabus->course_code}} {{$syllabus->course_num}}: {{$syllabus->course_title}} © 2022 by {{$syllabus->course_instructor}} is licensed under {{$syllabus->cc_license}}. Visit the Creative Commons Website for more information (<a href="https://creativecommons.org/licenses/">click here</a>).<p>
            @endif
        @endif

        </div>
    </div>
    <!-- footer -->
    <div class="card-footer p-4">
        <button class="btn btn-primary dropdown-toggle m-2 col-4 float-right" type="button" data-bs-toggle="dropdown" aria-expanded="false">Download</button>
        <ul class="dropdown-menu" aria-labelledby="dropdownMenu2">
                <li>
                    <form method="POST" action="{{ action([\App\Http\Controllers\SyllabusController::class, 'download'], [$syllabus->id, 'pdf']) }}">
                    @csrf        
                        <button type="submit" name="download" value="pdf" class="dropdown-item" type="button">
                            <i class="bi-file-pdf-fill text-danger"></i> PDF
                        </button>
                    </form>
                </li>
                <li>
                    <form method="POST" action="{{ action([\App\Http\Controllers\SyllabusController::class, 'download'], [$syllabus->id, 'word']) }}">
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

