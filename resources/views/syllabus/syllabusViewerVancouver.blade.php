
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

        <!-- Land Acknowledgement -->
        @if($syllabus->land_acknow == 1)
        <div class="mb-4">
            <div class="vSyllabusHeader2">
                <h6 class>LAND ACKNOWLEDGEMENT</h6>
            </div>
            <br>
            <div class="col-12">
                <blockquote> UBC’s Point Grey Campus is located on the traditional, ancestral, and unceded territory of the xwməθkwəy̓əm (Musqueam) people. The land it is situated on has always been a place of learning for the Musqueam people, who for millennia have passed on their culture, history, and traditions from one generation to the next on this site.</blockquote>
            </div>
        </div>
        @endif

        <!-- course description -->
        <div class="mb-4">
            <div class="vSyllabusHeader">
                <h6>COURSE DESCRIPTION
                    <span>
                        <i class="bi bi-info-circle-fill text-dark" data-toggle="tooltip" data-bs-placement="top" title="{{$inputFieldDescriptions['courseDescription']}}"></i>
                    </span>
                </h6>
            </div>
            @php ($course_description=explode("\n",$vancouverSyllabus->course_description))
            @foreach($course_description as $course_des)
            <p>{{$course_des}}</p>
            @endforeach     
            
        </div>

        <!-- course prerequisites -->
        <div class="mb-4">
            <div class="vSyllabusHeader">
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
            <table class="table table-light table-borderless">
                <thead>
                    <tr class="table-primary">
                        <th style="width:5%"></th>
                        <th>Course Prerequisites</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach (explode(PHP_EOL, $vancouverSyllabus->course_prereqs) as $index => $coursePreReq)
                        <tr>
                            <td>{{$index + 1}}</td>
                            <td>{{$coursePreReq}}</td>
                        </tr>
                    @endforeach                                               
                </tbody>
            </table>                                    
        </div>
        <!-- course corequisites -->
        <div class="mb-4">
            <div class="vSyllabusHeader">
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
            <table class="table table-light table-borderless">
                <thead>
                    <tr class="table-primary">
                        <th style="width:5%"></th>
                        <th>Course corequisite</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach (explode(PHP_EOL, $vancouverSyllabus->course_coreqs) as $index => $courseCoReq)
                        <tr>
                            <td>{{$index + 1}}</td>
                            <td>{{$courseCoReq}}</td>
                        </tr>
                    @endforeach                                               
                </tbody>
            </table>                                    
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
            <table class="table table-light table-borderless">
                <thead>
                    <tr class="table-primary">
                        <th style="width:5%"></th>
                        <th>Contact</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach (explode(PHP_EOL, $vancouverSyllabus->course_contacts) as $index => $contact)
                        <tr>
                            <td>{{$index + 1}}</td>
                            <td>{{$contact}}</td>
                        </tr>
                    @endforeach                                               
                </tbody>
            </table>                                    
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
            @php ($instructor_bio=explode("\n",$vancouverSyllabus->instructor_bio))
            @foreach($instructor_bio as $instr_bio)
            <p>{{$instr_bio}}</p>
            @endforeach     

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
            <table class="table table-light table-borderless">
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
            @php ($course_structure=explode("\n",$vancouverSyllabus->course_structure))
            @foreach($course_structure as $course_struct)
            <p>{{$course_struct}}</p>
            @endforeach     
        
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
                    <div>
                        <table id="courseScheduleTbl" class="table \" style="width:100%">
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
                    </div>
                    @endif
                @endif
            </div>
        </div>
        <!--  learning outcomes -->
        @if($syllabus->learning_outcomes)
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
            @php ($learning_outcomes=explode("\n",$syllabus->learning_outcomes))
            @foreach($learning_outcomes as $learning_outcome)
            <p>{{$learning_outcome}}</p>
            @endforeach     

        </div>
        @endif
        <!--  learning activities -->
        @if($syllabus->learning_activities)
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
            @php ($learning_activities=explode("\n",$syllabus->learning_activities))
            @foreach($learning_activities as $learning_activity)
            <p>{{$learning_activity}}</p>
            @endforeach     
            
        </div>
        @endif                               
        <!--  learning materials -->
        @if($syllabus->learning_materials)
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

            @php ($learning_materials=explode("\n",$syllabus->learning_materials))
            @foreach($learning_materials as $learning_material)
            <p>{{$learning_material}}</p>
            @endforeach   

        </div>
        @endif
        <!--  assessments of learning -->
        @if($syllabus->learning_assessments)
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
            @php ($learning_assessments=explode("\n",$syllabus->learning_assessments))
            @foreach($learning_assessments as $learning_assess)
            <p>{{$learning_assess}}</p>
            @endforeach                            
        </div>
        @endif
        <!--  course alignment table -->
        @if (isset($courseAlignment))
            <div class="mb-4">
                <div class="vSyllabusHeader">
                    <h6>
                        COURSE ALIGNMENT
                    </h6>
                </div>
                <table class="table table-light table-bordered" >
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
        <div class="vSyllabusHeader">
                    <h6>
                        PROGRAM ALIGNMENT
                    </h6>
                </div>
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
        <!-- learning resources -->
        @if($syllabus->learning_resources)
            <div class="mb-4">
                    <div class="vSyllabusHeader">
                        <h6>LEARNING RESOURCES
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
        @endif
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

                        <p class="text-center">UBC provides appropriate accommodation for students with disabilities. <br>
                        <ul style=“list-style-type:square”>
                            <li><a href="https://students.ubc.ca/about-student-services/centre-for-accessibility" target="_blank" rel="noopener noreferrer">Centre for Accessibility</a></li>
                            <li><a href="http://www.calendar.ubc.ca/vancouver/index.cfm?tree=3,34,0,0">Academic Calendar language concerning Accommodation for Students with Disabilities</a></li>
                            <li><a href="https://universitycounsel.ubc.ca/policies/disability-accommodation-policy/">Joint Board and Senate Policy LR7: Disability Accommodation</a></li>
                        </ul>
                        </p>
                        @break

                        @case('concession')
                        <p>In accordance with <a href="https://senate.ubc.ca/sites/senate.ubc.ca/files/downloads/va_V-135.1_Academic-Concession_20200415.pdf">UBC Policy V135</a>, academic concessions are generally granted when students are facing an unexpected situation or circumstance that prevents them from completing graded work or exams. Students may request an academic concession for unanticipated changes in personal responsibilities that create a conflict, medical circumstances, or compassionate grounds.
                        <br>
                        <br>
                        In accordance with <a href="https://senate.ubc.ca/sites/senate.ubc.ca/files/downloads/va_V-135.1_Academic-Concession_20200415.pdf">UBC Policy V135</a>, Section 10, students’ requests for academic concession should be made as early as reasonably possible, in writing to their instructor or academic advising office or equivalent in accordance with the procedures for <a href="https://senate.ubc.ca/sites/senate.ubc.ca/files/downloads/va_V-135.1_Academic-Concession_20200415.pdf">Policy V135</a> and those set out by the student’s faculty/school. The requests should clearly state the grounds for the concession and the anticipated duration of the conflict and or hindrance to academic work. In some situations, this self-declaration is sufficient, but the submission of supporting documentation may be required along with, or following, the self-declaration.
                        </p>
                        @break

                        @case('support')
                        <p>UBC provides resources to support student learning and to maintain healthy lifestyles but recognizes that sometimes crises arise and so there are additional resources to access including those for survivors of sexual assault.
                        <br>
                        <ul style=“list-style-type:square”>
                            <li><a href="https://students.ubc.ca/enrolment/academic-learning-resources">Central Resource to Support Student Learning</a></li>
                            <li><a href="https://students.ubc.ca/health">Central Resource for Student Health </a>and <a href="https://students.ubc.ca/health/accessing-crisis-support-services">Crisis Support.</a></li>
                            <li>Resources for the prevention of sexual violence and for support for survivors: <a href="https://svpro.ubc.ca/">UBC SVPRO </a>and <a href="https://www.amssasc.ca/">AMS SASC</a></li>
                            <li><a href="http://www.calendar.ubc.ca/vancouver/">Academic Calendar language concerning seeking Academic Concessions</a> if academic work is disrupted by ill health, medical issues, on compassionate grounds or if conflicting responsibilities arise during a course</li>
                        </ul>
                        </p>
                        @break

                        @case('harass')
                        <p>UBC values respect for the person and ideas of all members of the academic community. Harassment and discrimination are not tolerated nor is suppression of academic freedom.
                        <br>
                        <ul style=“list-style-type:square”>
                            <li><a href="http://www.calendar.ubc.ca/vancouver/index.cfm?tree=3,33,87,0,">Academic Calendar language concerning Freedom from Harrassment and Discrimination</a></li>
                            <li><a href="https://universitycounsel.ubc.ca/policies/discrimination-policy/">Board of Governors Policy SC7: Discrimination</a></li>
                            <li><a href="http://www.calendar.ubc.ca/vancouver/index.cfm?tree=3,33,0,0">Academic Calendar Language Concerning Academic Freedom</a></li>
                        </ul>
                        </p>
                        @break

                        @case('religious')
                        <p>UBC provides appropriate accommodation for students for religious, spiritual and cultural observances. <br>
                        <ul style=“list-style-type:square”>
                            <li><a href="http://www.calendar.ubc.ca/vancouver/">Academic Calendar language concerning Religious observances</a></li>
                            <li><a href="https://senate.ubc.ca/religious-holidays-observances/">Religious Observances</a></li>
                        </ul>
                        </p>
                        @break

                        @case('honesty')
                        <p>UBC values academic honesty and students are expected to acknowledge the ideas generated by others and to uphold the highest academic standards in all of their actions <br>
                        <ul style=“list-style-type:square”>
                            <li><a href="http://www.calendar.ubc.ca/vancouver/index.cfm?tree=3,286,0,0#15620">Academic Calendar language concerning Academic Honesty and Standards</a></li>
                            <li><a href="http://www.calendar.ubc.ca/vancouver/index.cfm?tree=3,54,0,0">Academic Calendar language concerning Student Conduct and Discipline</a></li>
                        </ul>
                        </p>
                        @break

                    @endswitch
                </div>
                @endif
            @endforeach
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
            <!--  passing criteria -->
        <div class="mb-4">
            <div class="vSyllabusHeader2">
                <h6>PASSING/GRADING CRITERIA</h6>
            </div>
            @php ($passing_criteria=explode("\n",$syllabus->passing_criteria))
            @foreach($passing_criteria as $passing_crit)
            <p>{{$passing_crit}}</p>
            @endforeach
        </div>

        <!--  custom resource -->
        @if($syllabus->custom_resource_title && $syllabus->custom_resource)
        <div class="mb-4">
            <div class="vSyllabusHeader2">
                <h6>{{strtoupper($syllabus->custom_resource_title)}}</h6>
            </div>
            @php ($custom_resources=explode("\n",$syllabus->custom_resource))
            @foreach($custom_resources as $custom_res)
            <p>{{$custom_res}}</p>
            @endforeach
        </div>
        @endif

        <!--  late policy -->
        <div class="mb-4">
            <div class="vSyllabusHeader2">
                <h6>LATE POLICY
                    <span>
                        <i class="bi bi-info-circle-fill text-dark" data-toggle="tooltip" data-bs-placement="top" title="{{$inputFieldDescriptions['missedActivityPolicy']}}"></i>
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
            <div class="vSyllabusHeader2">
                <h6>MISSED EXAM POLICY</h6>
            </div>
            @php ($missed_exam_policy=explode("\n",$syllabus->missed_exam_policy))
            @foreach($missed_exam_policy as $missed_exam)
            <p>{{$missed_exam}}</p>
            @endforeach
        </div>
        <!--  missed activity policy -->
        <div class="mb-4">
            <div class="vSyllabusHeader2">
                <h6>MISSED ACTIVITY POLICY
                    <span>
                        <i class="bi bi-info-circle-fill text-dark" data-toggle="tooltip" data-bs-placement="top" title="{{$inputFieldDescriptions['missedActivityPolicy']}}"></i>
                    </span>
                </h6>
            </div>
            
            @php ($missed_activity_policy=explode("\n",$syllabus->missed_activity_policy))
            @foreach($missed_activity_policy as $missed_activity)
            <p>{{$missed_activity}}</p>
            @endforeach
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
                @php ($learning_analytics=explode("\n",$vancouverSyllabus->learning_analytics))
                @foreach($learning_analytics as $learning_analytic)
                <p>{{$learning_analytic}}</p>
                @endforeach
            </div>
            <div class="mb-4">
                @if($syllabus->copyright)
                    <div class="vSyllabusHeader">
                        <h6>COPYRIGHT STATEMENT</h6>
                    </div>
                    
                    <p>All materials of this course (course handouts, lecture slides, assessments, course readings, etc.) are the intellectual property of the Course Instructor or licensed to be used in this course by the copyright owner. Redistribution of these materials by any means without permission of the copyright holder(s) constitutes a breach of copyright and may lead to academic discipline.</p>
                @endif
            </div>
    
        </div>
    </div>
    <!-- footer -->
    <div class="card-footer p-4">
            <button class="btn btn-primary dropdown-toggle m-2 col-4 float-right" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                Download
            </button>
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

