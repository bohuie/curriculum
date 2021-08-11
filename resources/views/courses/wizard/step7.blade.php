@extends('layouts.app')

@section('content')
<div>
    <div class="row justify-content-center">
        <div class="col-md-12">
            @include('courses.wizard.header')

            <div class="card ">

                <h3 class="card-header wizard" >
                {{$course->course_code}}{{$course->course_num}}: Course Summary
                </h3>

                <div class="card-body m-2">
                    <div class="row justify-content-between">
                        <div class="col-8">
                            <h6 class="card-subtitle wizard mb-4 lh-lg text-center">
                                You can review	and	download the mapped course here. To edit information, select from the numbered tabs above. Click finish only when you have completed the mapping process.                    
                            </h6>
                        </div>

                        <div class="col-4 align-self-start">
                            <a href="{{route('courses.pdf', $course->course_id)}}">
                                <button class="btn btn-primary col mr-5" onclick="{{route('courses.pdf', $course->course_id)}}">
                                    Download PDF<i class="bi bi-download pl-2"></i>
                                </button>
                            </a>
                        </div>
                    </div>

                    <div class="card mt-4 mb-4">
                        <h5 class="card-header">
                            Course Learning Outcomes
                        </h5>
                        <div class="body m-4 mb-2">
                            @if(count($course->learningOutcomes)<1)
                                <div class="alert alert-warning wizard">
                                    <i class="bi bi-exclamation-circle-fill"></i>There are no course learning outcomes set for this course. <a class="alert-link" href="{{route('courseWizard.step1', $course->course_id)}}">Add course learning outcomes.</a>                     
                                </div>
                            @else
                                <table class="table table-light table-bordered table" >
                                    <tr class="table-primary">
                                        <th class="text-center">#</th>
                                        <th>Course Learning Outcome</th>
                                    </tr>

                                    @foreach($course->learningOutcomes as $index => $l_outcome)
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

                                    @foreach($course->assessmentMethods as $index => $a_method)
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

                                    @foreach($course->learningActivities as $index => $l_activity)
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

                            @if(count($course->learningOutcomes)<1)
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
                                    
                                        @foreach($course->learningOutcomes as $index => $l_outcome)
                                        <tr>
                                            <td style="width:5%" >{{$index+1}}</td>
                                            <td>{{$l_outcome->l_outcome}}</td>
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
                                                        <tr>
                                                            <td class="table-active" colspan="2">Uncategorized PLOs</td>
                                                        </tr>
                                                        @foreach ($courseProgram->programLearningOutcomes as $plo) 
                                                            @if (!isset($plo->category))
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
                                                                    ({{$programMappingScale->map_scale_id}})
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
                                            <p>This chart shows the alignment of course learning outcomes to program learning outcomes for this program.</p>
                                            
                                            @if (!array_key_exists($courseProgram->program_id, $courseProgramsOutcomeMaps)) 
                                                <div class="alert alert-warning wizard">
                                                    <i class="bi bi-exclamation-circle-fill"></i>Course learning outcomes have not been mapped to this programs learning outcomes. <a class="alert-link" href="{{route('courseWizard.step5', $course->course_id)}}">Map CLOs to PLOs.</a>                
                                                </div>

                                            @else 
                                                <table class="table table-light table-bordered table" >
                                                    <tr class="table-primary">
                                                        <th>Course Learning Outcome</th>
                                                        <th colspan="{{$courseProgram->programLearningOutcomes->count()}}">Program Learning Outcome</th>
                                                    </tr>
                                                    @if ($courseProgram->ploCategories->count() > 0)
                                                        <tr>
                                                            <td></td>
                                                            @foreach ($courseProgram->ploCategories as $ploCategory)
                                                                @if ($ploCategory->plos->count() > 0)
                                                                    <td class="table-active w-auto" colspan="{{$ploCategory->plos->count()}}" style="min-width:5%; white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{$ploCategory->plo_category}}</td>
                                                                @endif
                                                            @endforeach
                                                            <td colspan="{{$courseProgram->programLearningOutcomes->count() - $courseProgram->numPlosCategorized}}"></td>
                                                        </tr>
                                                    @endif
                                                    <tr>
                                                        <td></td>
                                                        @if ($courseProgram->programLearningOutcomes->count() > 7)
                                                            @foreach ($courseProgram->programLearningOutcomes as $index => $programLearningOutcome)
                                                                <th class="text-center">{{$index + 1}}</th>
                                                            @endforeach
                                                        @else
                                                            @foreach ($courseProgram->programLearningOutcomes as $index => $programLearningOutcome)
                                                                <td style="height:0; vertical-align: bottom; text-align: left;">
                                                                    <span style="writing-mode: vertical-rl; transform: rotate(180deg);">
                                                                        @if(isset($programLearningOutcome->plo_shortphrase))
                                                                            {{$index+1}}.<br>
                                                                            {{$programLearningOutcome->plo_shortphrase}}
                                                                        @else
                                                                            PLO {{$index+1}}
                                                                        @endif

                                                                    </span>
                                                                </td>
                                                            @endforeach
                                                        @endif
                                                    </tr>

                                                    @foreach($course->learningOutcomes as $clo_index => $l_outcome)
                                                    <tr>
                                                        <td class="w-25" style="max-width:0; height: 50px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" >
                                                            @if(isset($l_outcome->clo_shortphrase))
                                                                {{$clo_index+1}}. {{$l_outcome->clo_shortphrase}}
                                                            @else
                                                                CLO {{$clo_index+1}}
                                                            @endif
                                                        </td>
                                                        @foreach($courseProgram->programLearningOutcomes as $pl_outcome)
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
                                                    </tr>
                                                    @endforeach
                                                </table>                                           
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
                                        <th>Ministry Standard</th>
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
                            <p>This chart shows the alignment of course learning outcomes to ministry standards.</p>

                            @if(count($standardsOutcomeMap)<1)

                                <div class="alert alert-warning wizard">
                                    <i class="bi bi-exclamation-circle-fill"></i>Course learning outcomes have not been mapped to this programs learning outcomes. <a class="alert-link" href="{{route('courseWizard.step6', $course->course_id)}}">Map CLOs to Ministry Standards.</a>                 
                                </div>

                            @else
                                <table class="table table-light table-bordered table mb-4" >
                                    <tr class="table-primary">
                                        <th >Course Learning Outcome</th>
                                        <th colspan="{{$course->standardOutcomes->count()}}">Ministry Standard</th>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        @for($i = 0; $i < $course->standardOutcomes->count(); $i++)

                                            <td style="height:0; vertical-align: bottom; text-align: left;">
                                                <span style="writing-mode: vertical-rl; transform: rotate(180deg);">
                                                    @if(isset($course->standardOutcomes[$i]->s_shortphrase))
                                                        {{$i+1}}.<br>
                                                        {{$course->standardOutcomes[$i]->s_shortphrase}}
                                                    @else
                                                        PLO {{$i+1}}
                                                    @endif

                                                </span>
                                            </td>

                                        @endfor
                                    </tr>

                                    @for($i = 0; $i < count($course->learningOutcomes); $i++)

                                        <tr>
                                            <td style="max-width:0; height: 50px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" >
                                                @if(isset($course->learningOutcomes[$i]->clo_shortphrase))
                                                    {{$i+1}}. {{$course->learningOutcomes[$i]->clo_shortphrase}}
                                                @else
                                                    CLO {{$i+1}}
                                                @endif
                                            </td>

                                            @for($j = 0; $j < $course->standardOutcomes->count(); $j++)
                                                @foreach ($standardsOutcomeMap as $om)
                                                    @if( $om->standard_id == $course->standardOutcomes[$j]->standard_id && $om->l_outcome_id == $course->learningOutcomes[$i]->l_outcome_id )
                                                        <td @foreach($course->standardScalesCategory->standardScales as $ms) @if($ms->standard_scale_id == $om->standard_scale_id) style="background-color:{{$ms->colour}}" @endif @endforeach class="text-center align-middle" >
                                                            <span @if($om->abbreviation == 'A') style="color:white" @endif>
                                                                {{$om->abbreviation}}
                                                            </span>
                                                        </td>
                                                    @endif
                                                @endforeach

                                            @endfor
                                        </tr>
                                    @endfor
                                </table>

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
                                    @foreach ($optionalSubcategories as $optionalSubcategory)
                                        <tr>
                                            <th colspan="2" class="table-secondary">{!! $optionalSubcategory->subcat_name !!}</th>
                                        </tr>
                                        @foreach ($course->optionalPriorities as $index => $optional_Plo)
                                            @if ($optionalSubcategory->subcat_id == $optional_Plo->subcat_id)
                                                <tr>
                                                    <td style="width:5%" >{{$index + 1}}</td>
                                                    <td>{!! $optional_Plo->optional_priority !!}</td>
                                                </tr>
                                            @endif
                                        @endforeach
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
                            <button class="btn btn-sm btn-primary col-3 float-left"><i class="bi bi-arrow-left mr-2"></i> Ministry Standards Mapping</button>
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
