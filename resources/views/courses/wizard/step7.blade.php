@extends('layouts.app')

@section('content')
<!-- Download Error Notification -->
<div aria-live="polite" aria-atomic="true" class="position-relative">
    <div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index:11">
        <div id="errorToast" class="toast hide" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header " style="padding:1em;color:#842029;background-color:#f8d7da;border-color:#f5c2c7">
                <i class="bi bi-exclamation-circle-fill pr-2 text-danger"></i>            
                <strong class="me-auto">Error</strong>
                <button type="button" class="btn-close" onclick="hideErrorToast()" aria-label="Close"></button>
            </div>
            <div class="toast-body alert-danger">
                We were unable to the download the course summary for {{$course->course_code}} {{$course->course_num}}. 
                <div class="d-flex flex-row-reverse bd-highlight mt-2 pt-2">
                    <a href="mailto:ctl.helpdesk@ubc.ca?subject=UBC Curriculum MAP: Error Generating Course Summary&body=There was an error downloading the course summary for {{$course->course_code}} {{$course->course_num}}">
                        <button type="button" class="btn btn-secondary btn-sm">Get Help</button>      
                    </a>  
                </div>        
            </div>
        </div>
    </div>
</div>

<div>
    <div class="row justify-content-center">
        <div class="col-md-12">
            @include('courses.wizard.header')

            <div class="card ">
                <!-- Include download progress subview for PDF -->
                @include('modals.downloadProgressModal', ['course' => $course])
                <h3 class="card-header wizard" >
                    <div class="row">
                        <div class="col text-left">
                            <button id="downloadPDFBtn" type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#downloadProgressModal" data-route="{{route('courses.pdf', $course->course_id)}}">
                                Download<i class="bi bi-download pl-2"></i>
                            </button>
                            <button id="downloadDataBtn" type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#downloadProgressModal" data-route="{{route('courses.dataSpreadsheet', $course->course_id)}}">
                                Download Data<i class="bi bi-download pl-2"></i>
                            </button>
                        </div>
                        <div class="col">
                            {{$course->course_code}} {{$course->course_num}}: Course Summary
                        </div>

                        <div class="col text-right">
                            <button id="courseOverviewHelp" style="border: none; background: none; outline: none;" data-bs-toggle="modal" href="#guideModal">
                                <i class="bi bi-question-circle" style="color:#002145;"></i>
                            </button>
                        </div>
                        <div class="text-left">
                            @include('layouts.guide')
                        </div>
                    </div>
                </h3>

                <div class="card-body m-2">
                    <div class="alert alert-primary d-flex align-items-center" role="alert" style="text-align:justify">
                        <i class="bi bi-info-circle-fill pr-2 fs-3"></i>                        
                        <div>
                            You can review	and	download the mapped course here. To edit information, select from the numbered tabs above. Click finish only when you have completed the mapping process.                    
                        </div>
                    </div>                            

                    <div class="card mt-4 mb-4">
                        <h5 class="card-header">
                            Course Learning Outcomes
                        </h5>
                        <div class="body m-4 mb-2">
                            @if(count($l_outcomes)<1)
                                <div class="alert alert-warning wizard">
                                    <i class="bi bi-exclamation-circle-fill"></i>There are no course learning outcomes set for this course. <a class="alert-link" href="{{route('courseWizard.step1', $course->course_id)}}">Add course learning outcomes.</a>                     
                                </div>
                            @else
                                <table class="table table-light table-bordered table" >
                                    <tr class="table-primary">
                                        <th class="text-center">#</th>
                                        <th>Course Learning Outcome</th>
                                    </tr>

                                    @foreach($l_outcomes as $index => $l_outcome)
                                    <tr>
                                        <td class="text-center fw-bold" style="width:5%" >{{$index+1}}</td>
                                        <td>
                                            <b>{{$l_outcome->clo_shortphrase}}</b><br>
                                            {{$l_outcome->l_outcome}}
                                        </td>
                                    </tr>
                                    @endforeach


                                </table>
                            @endif
                        </div>
                    </div>



                    <div class="card mt-4 mb-4">

                        <h5 class="card-header">
                            Student Assessment Methods
                        </h5>

                        <div class="body m-4 mb-2">

                            @if(count($course->assessmentMethods)<1)
                                <div class="alert alert-warning wizard">
                                    <i class="bi bi-exclamation-circle-fill"></i>There are no student assessment methods set for this course. <a class="alert-link" href="{{route('courseWizard.step2', $course->course_id)}}">Add student assessment methods.</a>                     
                                </div>

                            @else
                                <table class="table table-light table-bordered table" >
                                    <tr class="table-primary">
                                        <th class="text-center">#</th>
                                        <th>Student Assessment Method</th>
                                        <th>Weight</th>
                                    </tr>

                                    @foreach($course->assessmentMethods->sortBy('pos_in_alignment')->values() as $index => $a_method)
                                    <tr>
                                        <td class="text-center fw-bold" style="width:5%" >{{$index+1}}</td>
                                        <td>{{$a_method->a_method}}</td>
                                        <td>{{$a_method->weight}}%</td>
                                    </tr>
                                    @endforeach

                                    <tr class="table-secondary fw-bold">
                                        <td></td>
                                        <td>Total</td>
                                        <td>{{$assessmentMethodsTotal}}%</td>
                                    </tr>

                                </table>
                            @endif
                        </div>
                    </div>

                    <div class="card mt-4 mb-4">

                        <h5 class="card-header">
                            Teaching and Learning Activities
                        </h5>

                        <div class="body m-4 mb-2">
                            @if(count($course->learningActivities)<1)
                                <div class="alert alert-warning wizard">
                                    <i class="bi bi-exclamation-circle-fill"></i>There are no teaching and learning activities set for this course. <a class="alert-link" href="{{route('courseWizard.step3', $course->course_id)}}">Add teaching and learning activities.</a>                     
                                </div>

                            @else
                                <table class="table table-light table-bordered table" >
                                    <tr class="table-primary">
                                        <th class="text-center">#</th>
                                        <th>Teaching and Learning Activity</th>
                                    </tr>

                                    @foreach($course->learningActivities->sortBy('l_activities_pos')->values() as $index => $l_activity)
                                    <tr>
                                        <td class="text-center fw-bold" style="width:5%" >{{$index+1}}</td>
                                        <td>{{$l_activity->l_activity}}</td>
                                    </tr>
                                    @endforeach

                                </table>
                            @endif 
                        </div>
                    </div>

                    <div class="card mt-4 mb-4">
                        <h5 class="card-header">
                            Course Alignment
                        </h5>
                        <div class="body m-4 mb-2">

                            @if(count($l_outcomes)<1)
                                <div class="alert alert-warning wizard">
                                    <i class="bi bi-exclamation-circle-fill"></i>There are no course learning outcomes set for this course. <a class="alert-link" href="{{route('courseWizard.step1', $course->course_id)}}">Add course learning outcomes.</a>                     
                                </div>

                            @else
                                @if ($oAct < 1 && $oAss < 1)
                                    <div class="alert alert-warning wizard">
                                        <i class="bi bi-exclamation-circle-fill"></i>Course learning outcomes have not been constructively aligned with student assessment methods and teaching and learning activities for this course.  <a class="alert-link" href="{{route('courseWizard.step4', $course->course_id)}}">Constructively align course.</a>                     
                                    </div>
                                @else 
                                    <table class="table table-light table-bordered table" >
                                        <tr class="table-primary">
                                            <th class="text-center">#</th>
                                            <th>Course Learning Outcome</th>
                                            <th>Student Assessment Method</th>
                                            <th>Teaching and Learning Activity</th>
                                        </tr>
                                    
                                        @foreach($l_outcomes as $index => $l_outcome)
                                        <tr>
                                            <td style="width:5%" >{{$index+1}}</td>
                                            <td>
                                                <b>{{$l_outcome->clo_shortphrase}}</b><br>
                                                {{$l_outcome->l_outcome}}
                                            </td>
                                            <td>
                                                @foreach($outcomeAssessments as $oa)
                                                    @if($oa->l_outcome_id == $l_outcome->l_outcome_id )
                                                        {{$oa->a_method}}<br>
                                                    @endif

                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach($outcomeActivities as $oa)
                                                    @if($oa->l_outcome_id == $l_outcome->l_outcome_id )
                                                        {{$oa->l_activity}}<br>
                                                    @endif

                                                @endforeach
                                            </td>
                                        </tr>
                                        @endforeach
                                    @endif
                                </table>
                            @endif
                        </div>
                    </div>
                    
                    <div class="card mt-4 mb-4">
                        <h5 class="card-header">
                            Program Outcome Maps
                        </h5>
                        <div class="body m-4 mb-2">
                            @if ($course->programs->count() < 1)
                                <div class="alert alert-warning wizard">
                                    <i class="bi bi-exclamation-circle-fill pr-2 fs-5"></i>This course does not belong to any programs yet.                    
                                </div>
                            @else 
                                @foreach ($course->programs as $index => $courseProgram)
                                <div class="card border-light">
                                    <div class="card-header fw-bold text-decoration-underline">Program {{$index + 1}}. {{$courseProgram->program}}</div>
                                    <div class="card-body">
                                        <div class="mb-4">
                                            <h5 class="card-title">Program Learning Outcomes</h5>

                                            @if($courseProgram->programLearningOutcomes->count() <1)
                                                <div class="alert alert-warning wizard">
                                                    <i class="bi bi-exclamation-circle-fill pr-2 fs-5"></i>Program learning outcomes have not been set for this program.                    
                                                </div>

                                            @else
                                                <table class="table table-light table-bordered table" >
                                                    <tr class="table-primary">
                                                        <th class="text-center">#</th>
                                                        <th>Program Learning Outcome</th>
                                                    </tr>

                                                    @if ($courseProgram->ploCategories->count() > 0)
                                                        <?php $pos = 0 ?>

                                                        @foreach ($courseProgram->ploCategories as $ploCategory) 
                                                            @if ($ploCategory->plos->count() > 0)
                                                                <tr>
                                                                    <td colspan="2" class="table-active">{{$ploCategory->plo_category}}</td>
                                                                </tr>
                                                                @foreach ($ploCategory->plos as $plo)
                                                                <?php $pos++ ?>
                                                                <tr>
                                                                    <td style="width:5%" >{{$pos}}</td>
                                                                    <td>
                                                                        <b>{{$plo->plo_shortphrase}}</b><br>
                                                                        {{$plo->pl_outcome}}
                                                                    </td>
                                                                </tr>
                                                                @endforeach
                                                            @endif
                                                        @endforeach
                                                        <?php $hasRan = FALSE ?>
                                                        @foreach ($courseProgram->programLearningOutcomes as $plo) 
                                                            @if (!isset($plo->category))
                                                                @if (! $hasRan)
                                                                    <tr>
                                                                        <td class="table-active" colspan="2">Uncategorized PLOs</td>
                                                                    </tr>
                                                                    <?php $hasRan = TRUE ?>
                                                                @endif
                                                            <tr>
                                                                <td>{{($pos++) + 1}}</td>
                                                                <td>
                                                                    <b>{{$plo->plo_shortphrase}}</b><br>
                                                                    {{$plo->pl_outcome}}
                                                                </td>
                                                            </tr>
                                                            @endif
                                                        @endforeach   
                                                    @else
                                                        <tr>
                                                            <td class="table-active" colspan="42">Uncategorized PLOs</td>
                                                        </tr>                                         
                                                        @foreach($courseProgram->programLearningOutcomes as $index => $pl_outcome)

                                                        <tr>
                                                            <td style="width:5%" >{{$index + 1}}</td>
                                                            <td>
                                                                <b>{{$pl_outcome->plo_shortphrase}}</b><br>
                                                                {{$pl_outcome->pl_outcome}}

                                                            </td>
                                                        </tr>
                                                        @endforeach
                                                    @endif
                                                </table>
                                            @endif
                                        </div>

                                        <div class="mb-4">
                                            <h5 class="card-title">Mapping Scale</h5>
                                            <p>The mapping scale indicates the degree to which a program learning outcome is addressed by a course learning outcome.</p>

                                            @if ($courseProgram->mappingScaleLevels->count() < 1) 
                                                <div class="alert alert-warning wizard">
                                                    <i class="bi bi-exclamation-circle-fill"></i>A mapping scale has not been set for this program.                  
                                                </div>
                                            @else 
                                                <table class="table table-bordered table-sm">
                                                        
                                                    <tr class="table-primary">
                                                        <th colspan="2">Mapping Scale</th>
                                                    </tr>
                                                    <tbody>
                                                        @foreach($courseProgram->mappingScaleLevels as $programMappingScale)
                                                            <tr>
                                                                <td>
                                                                    <div style="background-color:{{$programMappingScale->colour}}; height: 10px; width: 10px;"></div>
                                                                    {{$programMappingScale->title}}<br>
                                                                    ({{$programMappingScale->abbreviation}})
                                                                </td>
                                                                <td>
                                                                    {{$programMappingScale->description}}
                                                                </td>

                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            @endif
                                        </div>

                                        <div class="mb-4">
                                            <h5 class="card-title">Outcome Map: Course Learning Outcomes to Program Learning Outcomes</h5>
                                            <p>This table shows the alignment of course learning outcomes (CLOs) to program learning outcomes for this program.</p>
                                            
                                            @if (!array_key_exists($courseProgram->program_id, $courseProgramsOutcomeMaps)) 
                                                <div class="alert alert-warning wizard">
                                                    <i class="bi bi-exclamation-circle-fill"></i>Course learning outcomes have not been mapped to this programs learning outcomes. <a class="alert-link" href="{{route('courseWizard.step5', $course->course_id)}}">Map CLOs to PLOs.</a>                
                                                </div>

                                            @else
                                                <div style="overflow: auto;">
                                                    <table class="table table-bordered table-light">
                                                        <tbody>
                                                            <tr class="table-primary">
                                                                <th colspan="1" class="w-auto">CLO</th>
                                                                <th colspan="{{$courseProgram->programLearningOutcomes->count()}}">Program Learning Outcome</th>
                                                            </tr>
                                                            <tr>
                                                                <td></td>
                                                                @foreach ($courseProgram->ploCategories as $ploCategory)
                                                                    @if ($ploCategory->plos->count() > 0)
                                                                        <th class="table-active w-auto" colspan="{{$ploCategory->plos->count()}}" style="min-width:5%; white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{$ploCategory->plo_category}}</th>
                                                                    @endif
                                                                @endforeach
                                                                @if ($courseProgram->programLearningOutcomes->where('plo_category_id', null)->count() > 0) 
                                                                    <th class="table-active w-auto text-center" colspan="{{$courseProgram->programLearningOutcomes->where('plo_category_id', null)->count()}}" style="min-width:5%; white-space:nowrap;overflow:hidden;text-overflow:ellipsis">Uncategorized PLOs</th>
                                                                @endif
                                                            </tr>
                                        
                                                            <tr>
                                                                <td></td>
                                                                <?php $pos = 1 ?>
                                                                @foreach ($courseProgram->ploCategories as $ploCategory)
                                                                    @if ($ploCategory->plos->count() > 0)
                                                                        @foreach($ploCategory->plos as $plo)
                                                                            <td style="height:0; text-align: left;">
                                                                                <span >
                                                                                    @if(isset($plo->plo_shortphrase))
                                                                                        <b>{{$pos}}.</b><br>
                                                                                        {{$plo->plo_shortphrase}}
                                                                                    @else
                                                                                        PLO: {{$pos}}
                                                                                    @endif

                                                                                </span>
                                                                            </td>
                                                                            <?php $pos++ ?>
                                                                        @endforeach
                                                                    @endif
                                                                @endforeach
                                                                @foreach ($courseProgram->programLearningOutcomes->where('plo_category_id', null) as $unCategorizedPLO)
                                                                    <td style="height:0;text-align:left;">
                                                                        <span>
                                                                            @if(isset($unCategorizedPLO->plo_shortphrase))
                                                                                <b>{{$pos}}</b><br>
                                                                                {{$unCategorizedPLO->plo_shortphrase}}
                                                                            @else
                                                                                PLO: {{$pos}}
                                                                            @endif
                                                                        </span>
                                                                    </td>
                                                                    <?php $pos++ ?>
                                                                @endforeach
                                                            </tr>

                                                            @foreach($l_outcomes as $clo_index => $l_outcome)
                                                            <tr>
                                                                <td class="w-auto">
                                                                    @if(isset($l_outcome->clo_shortphrase))
                                                                        <b>{{$clo_index+1}}.</b> {{$l_outcome->clo_shortphrase}}
                                                                    @else
                                                                        CLO {{$clo_index+1}}
                                                                    @endif
                                                                </td>
                                                                @foreach($courseProgram->ploCategories as $ploCategory)
                                                                    @if ($ploCategory->plos->count() > 0)
                                                                        @foreach($ploCategory->plos as $pl_outcome)
                                                                            <!-- Check if this PLO has been mapped -->
                                                                            @if (!array_key_exists($pl_outcome->pl_outcome_id, $courseProgramsOutcomeMaps[$courseProgram->program_id]))
                                                                                <td></td>
                                                                            @else 
                                                                                <!-- Check if this PLO has been mapped to this CLO -->
                                                                                @if (!array_key_exists($l_outcome->l_outcome_id, $courseProgramsOutcomeMaps[$courseProgram->program_id][$pl_outcome->pl_outcome_id]))
                                                                                    <td></td>
                                                                                @else 

                                                                                <td 
                                                                                    @foreach($courseProgram->mappingScaleLevels as $programMappingScale) 
                                                                                        @if($programMappingScale->map_scale_id == $courseProgramsOutcomeMaps[$courseProgram->program_id][$pl_outcome->pl_outcome_id][$l_outcome->l_outcome_id]->map_scale_id)             
                                                                                            style="background-color:{{$programMappingScale->colour}}" 
                                                                                        @endif 
                                                                                    @endforeach 
                                                                                    class="text-center align-middle">
                                                                                    <span @if($courseProgramsOutcomeMaps[$courseProgram->program_id][$pl_outcome->pl_outcome_id][$l_outcome->l_outcome_id]->abbreviation == 'A') style="color:white" @endif>
                                                                                        {{$courseProgramsOutcomeMaps[$courseProgram->program_id][$pl_outcome->pl_outcome_id][$l_outcome->l_outcome_id]->abbreviation}}
                                                                                    </span>
                                                                                </td>
                                                                                @endif
                                                                            @endif                                                         
                                                                        @endforeach
                                                                    @endif
                                                                @endforeach
                                                                @foreach ($courseProgram->programLearningOutcomes->where('plo_category_id', null) as $unCategorizedPLO)
                                                                    <!-- Check if this PLO has been mapped -->
                                                                    @if (!array_key_exists($unCategorizedPLO->pl_outcome_id, $courseProgramsOutcomeMaps[$courseProgram->program_id]))
                                                                        <td></td>
                                                                    @else 
                                                                        <!-- Check if this PLO has been mapped to this CLO -->
                                                                        @if (!array_key_exists($l_outcome->l_outcome_id, $courseProgramsOutcomeMaps[$courseProgram->program_id][$unCategorizedPLO->pl_outcome_id]))
                                                                            <td></td>
                                                                        @else 
                                                                            <td 
                                                                                @foreach($courseProgram->mappingScaleLevels as $programMappingScale) 
                                                                                    @if($programMappingScale->map_scale_id == $courseProgramsOutcomeMaps[$courseProgram->program_id][$unCategorizedPLO->pl_outcome_id][$l_outcome->l_outcome_id]->map_scale_id) 
                                                                                        style="background-color:{{$programMappingScale->colour}}" 
                                                                                    @endif 
                                                                                @endforeach 
                                                                                class="text-center align-middle">
                                                                                <span @if($courseProgramsOutcomeMaps[$courseProgram->program_id][$unCategorizedPLO->pl_outcome_id]    [$l_outcome->l_outcome_id]->abbreviation == 'A') style="color:white" @endif>
                                                                                        {{$courseProgramsOutcomeMaps[$courseProgram->program_id][$unCategorizedPLO->pl_outcome_id][$l_outcome->l_outcome_id]->abbreviation}}
                                                                                </span>
                                                                            </td>
                                                                        @endif
                                                                    @endif                                                         
                                                                @endforeach
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>                                         
                                            @endif
                                        </div>
    
                                    </div>
                                </div>                                
                                
                                @endforeach
                            @endif
                        </div>
                    </div>

                    <div class="card mt-4 mb-4">
                        <h5 class="card-header">
                            Standards Outcome Maps
                        </h5>
                        <div class="body m-4 mb-2">
                            <h5 class="card-title">Standards</h5>
                            
                            @if($course->standardOutcomes->count() < 1)
                                <div class="alert alert-warning wizard">
                                    <i class="bi bi-exclamation-circle-fill pr-2 fs-5"></i>Standards have not been set for this course.                    
                                </div>
                            @else
                                <table class="table table-light table-bordered table mb-4" >
                                    <tr class="table-primary">
                                        <th class="text-center">#</th>
                                        <th>BC Degree Standard</th>
                                    </tr>
                                                
                                    @foreach($course->standardOutcomes as $index => $standard_outcome)
                                    <tr>
                                        <td style="width:5%" >{{$index + 1}}</td>
                                        <td>
                                            <b>{{$standard_outcome->s_shortphrase}}</b><br>
                                                {!! $standard_outcome->s_outcome !!}
                                        </td>
                                    </tr>
                                    @endforeach
                                
                                </table>
                            @endif

                            <h5 class="card-title">Standards and Priorities Mapping Scale</h5>
                            <p>The mapping scale indicates the degree to which a ministry standard is addressed by a course learning outcome.</p>

                            @if($course->standardScalesCategory->standardScales->count() < 1)
                                <div class="alert alert-warning wizard">
                                    <i class="bi bi-exclamation-circle-fill"></i>A mapping scale has not been set for this program.                  
                                </div>
                            @else 
                                <div class="container row mt-3 mb-2">
                                    <div class="col">
                                        <table class="table table-bordered table-sm mb-4">
                                            <thead>
                                                <tr>
                                                    <th colspan="2">Mapping Scale</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($course->standardScalesCategory->standardScales as $ms)

                                                    <tr>

                                                        <td>
                                                            <div style="background-color:{{$ms->colour}}; height: 10px; width: 10px;"></div>
                                                            {{$ms->title}}<br>
                                                            ({{$ms->abbreviation}})
                                                        </td>
                                                        <td>
                                                            {{$ms->description}}
                                                        </td>

                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endif

                            <h5 class="card-title">Outcome Map:</h5>
                            <p>This chart shows the alignment of ministry standards to this course.</p>

                            @if(count($standardOutcomeMap) < 1)

                                <div class="alert alert-warning wizard">
                                    <i class="bi bi-exclamation-circle-fill"></i>This course has not been mapped to the ministry standards yet. <a class="alert-link" href="{{route('courseWizard.step6', $course->course_id)}}">Map CLOs to BC Degree Standards.</a>                 
                                </div>

                            @else
                                <div style="overflow: auto;">
                                    <table class="table table-light table-bordered table mb-4" >
                                        <tr class="table-primary">
                                            <th colspan="{{$course->standardOutcomes->count()}}">BC Degree Standard</th>
                                        </tr>
                                        <tr>
                                            @for($i = 0; $i < $course->standardOutcomes->count(); $i++)
                                                <td style="height:0; text-align: left;">
                                                    <span >
                                                        @if(isset($course->standardOutcomes[$i]->s_shortphrase))
                                                            <b>{{$i+1}}.</b><br>
                                                            {{$course->standardOutcomes[$i]->s_shortphrase}}
                                                        @else
                                                            CLO {{$i+1}}
                                                        @endif
                                                    </span>
                                                </td>
                                            @endfor
                                        </tr>
                                        <tr>
                                            @foreach($course->standardCategory->standards as $standard)
                                                <!-- Check if this CLO has been mapped to this standard -->
                                                @if (isset($standardOutcomeMap[$standard->standard_id][$course->course_id]))
                                                    <td style="vertical-align:middle;text-align:center;background-color:{{$standardOutcomeMap[$standard->standard_id][$course->course_id]->colour}}">
                                                        <p @if($standardOutcomeMap[$standard->standard_id][$course->course_id]->abbreviation == 'A') style="color:white;" @endif>
                                                            {{$standardOutcomeMap[$standard->standard_id][$course->course_id]->abbreviation}}
                                                        </p>
                                                    </td>                                    
                                                @else 
                                                    <td></td>
                                                    <!-- <td>&#63;</td> -->
                                                @endif
                                            @endforeach
                                        </tr>
                                    </table>
                                </div>

                            @endif

                        </div>
                    </div>

                    <div class="card mt-4 mb-4">
                        <h5 class="card-header">
                            Optional Alignment to UBC and Ministry Priorities
                        </h5>
                        <div class="body m-4 mb-2">
                            @if(count($course->optionalPriorities)<1)
                                <div class="alert alert-warning wizard">
                                    <i class="bi bi-exclamation-circle-fill"></i>This course has not aligned with any UBC and Ministry Priorities. <a class="alert-link" href="{{route('courseWizard.step6', $course->course_id)}}">Align this course to UBC and Ministry Priorities (Optional).</a>                 
                                </div>

                            @else
                                <table class="table table-light table-bordered table mb-4" >
                                    <tr class="table-primary">                                
                                        <th >#</th>
                                        <th>Aligned UBC and Ministry Priority</th>
                                    </tr>

                                    <?php $pos = 0 ?>
                                    @foreach ($optionalSubcategories as $optionalSubcategory)
                                        <tr>
                                            <th colspan="2" class="table-secondary">{!! $optionalSubcategory->subcat_name !!}</th>
                                        </tr>
                                        @if ($optionalSubcategory->subcat_id == 1)
                                            @foreach ($course->optionalPriorities->where('subcat_id', 1)->pluck('year')->unique()->sortDesc() as $year)
                                                <tr>
                                                    <th colspan="2" class="table-light">{{$year}}</th>
                                                </tr>
                                                @foreach ($course->optionalPriorities->where('subcat_id', 1)->where('year', $year) as $priority)
                                                    <?php $pos++ ?>
                                                    <tr>
                                                        <td style="width:5%" >{{$pos}}</td>
                                                        <td>{!! $priority->optional_priority !!}</td>
                                                    </tr>
                                                @endforeach
                                            @endforeach
                                        @else
                                    
                                            @foreach ($course->optionalPriorities as $index => $optional_Plo)
                                                @if ($optionalSubcategory->subcat_id == $optional_Plo->subcat_id)
                                                    <?php $pos++ ?>
                                                    <tr>
                                                        <td style="width:5%" >{{$pos}}</td>
                                                        <td>{!! $optional_Plo->optional_priority !!}</td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        @endif
                                    @endforeach
                                </table>
                            @endif
                        </div>
                    </div>
                </div>
                @if (!$isViewer)
                <h6 class="card-subtitle wizard mb-4 text-primary fw-bold text-center">
                    If you have finished mapping this course. Click the <b>finish button</b> to save your work.
                </h6>
                @endif
                <div class="card-footer">
                    <div class="card-body mb-4">
                        @if (!$isViewer)
                        <a href="{{route('courseWizard.step6', $course->course_id)}}">
                            <button class="btn btn-sm btn-primary col-3 float-left"><i class="bi bi-arrow-left mr-2"></i> BC Degree Standards Mapping</button>
                        </a>
                        <a href="{{route('courses.submit', $course->course_id)}}">
                            <button class="btn btn-sm btn-success col-3 float-right">Finish <i class="bi bi-check2-circle ml-2 fs-6"></i></button>
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="application/javascript">
    $(document).ready(function () {

        $("form").submit(function () {
            // prevent duplicate form submissions
            $(this).find(":submit").attr('disabled', 'disabled');
            $(this).find(":submit").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');

        });
    });
</script>


@endsection
