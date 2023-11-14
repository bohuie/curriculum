<!doctype html>
<html lang="en">

    <head>
        <!-- CSS only -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <style>
            html {
                margin-left: 10%;
                margin-right:10%;
                font-family: 'Open Sans', sans-serif;
                line-height: 1.15;
            }

            .panel, p {
                font-size: 80%;
            }

            .panel p {
                font-size: 100%;
            }

            .table>thead>tr>td.info,
            .table>tbody>tr>td.info,
            .table>tfoot>tr>td.info,
            .table>thead>tr>th.info,
            .table>tbody>tr>th.info,
            .table>tfoot>tr>th.info,
            .table>thead>tr.info>td,
            .table>tbody>tr.info>td,
            .table>tfoot>tr.info>td,
            .table>thead>tr.info>th,
            .table>tbody>tr.info>th,
            .table>tfoot>tr.info>th {
            background-color: #cfe2ff;
            }

            .table>thead>tr>td.active,
            .table>tbody>tr>td.active,
            .table>tfoot>tr>td.active,
            .table>thead>tr>th.active,
            .table>tbody>tr>th.active,
            .table>tfoot>tr>th.active,
            .table>thead>tr.active>td,
            .table>tbody>tr.active>td,
            .table>tfoot>tr.active>td,
            .table>thead>tr.active>th,
            .table>tbody>tr.active>th,
            .table>tfoot>tr.active>th {
            background-color: #e2e3e5;
            }

            .alert-warning {
                color: #664d03;
                background-color: #fff3cd;
                border-color: #ffecb5;
            }



        </style>
    </head>
    <body>

        <!-- Course Info -->
        <div style="margin-bottom:16px">
            <p class="text-right">{{date("Y-m-d")}}</p>
            <h2>{{$course->course_code}}{{$course->course_num}}: Course Summary</h2>
            <p><b>Course:</b> {{$course->course_code}}{{$course->course_num}} {{$course->section}} {{$course->course_title}}</p>
            <p><b>Term:</b> {{$course->year}} {{$course->semester}}</p>
            <p><b>Mode of Delivery:</b>
            @switch($course->delivery_modality)
                @case('O')
                    Online
                    @break
                @case('B')
                    Hybrid
                    @break
                @case('M')
                    Multi-Access
                    @break
                @default
                    In-person
            @endswitch
            </p>
            <p><b>Level: </b>{{$course->standardCategory->sc_name}}</p>
        </div>
        <!-- End of Course Info -->

        <!-- CLOs -->
        <div class="panel panel-default">
            <div class="panel-heading"><h4>Course Learning Outcomes</h4></div>
                @if($courseLearningOutcomes->count() <1)
                    <div class="alert alert-warning text-center">
                        There are no course learning outcomes set for this course.                     
                    </div>
                @else
                    <table class="table" >
                        <tr class="info">
                            <th class="text-center">#</th>
                            <th>Course Learning Outcome</th>
                        </tr>

                        @foreach($courseLearningOutcomes as $index => $l_outcome)
                        <tr>
                            <td class="text-center" style="width:5%" ><strong>{{$index+1}}</strong></td>
                            <td>
                                <strong>{{$l_outcome->clo_shortphrase}}</strong><br>
                                    {{$l_outcome->l_outcome}}
                            </td>
                        </tr>
                        @endforeach


                    </table>
                @endif
        </div>
        <!-- End of CLOs -->

        <!-- Student Assessment Methods -->
        <div class="panel panel-default">
            <div class="panel-heading"><h4>Student Assessment Methods</h4></div>
                @if($course->assessmentMethods->count() < 1)
                    <div class="alert alert-warning text-center">
                        There are no student assessment methods set for this course.                     
                    </div>
                @else
                    <table class="table">
                        <tr class="info">
                            <th class="text-center">#</th>
                            <th>Student Assessment Method</th>
                            <th>Weight</th>
                        </tr>

                        @foreach($course->assessmentMethods as $index => $a_method)
                        <tr>
                            <td class="text-center" style="width:5%" ><strong>{{$index+1}}</strong></td>
                            <td>{{$a_method->a_method}}</td>
                            <td>{{$a_method->weight}}%</td>
                        </tr>
                        @endforeach

                        <tr class="active" style="font-weight:bold">
                            <td></td>
                            <td>Total</td>
                            <td>{{$assessmentMethodsTotal}}%</td>
                        </tr>


                    </table>
                @endif
        </div>
        <!-- End of Student Assessment Methods -->

        <!-- Teaching and Learning Activities -->
        <div class="panel panel-default">
            <div class="panel-heading"><h4>Teaching and Learning Activities</h4></div>
                @if($course->learningActivities->count() < 1)
                    <div class="alert alert-warning text-center">
                        There are no teaching and learning activities set for this course.                     
                    </div>
                @else
                    <table class="table">
                        <tr class="info">
                            <th class="text-center">#</th>
                            <th>Teaching and Learning Activity</th>
                        </tr>

                        @foreach($course->learningActivities as $index => $l_activity)
                        <tr>
                            <td class="text-center" style="width:5%" ><strong>{{$index+1}}</strong></td>
                            <td>{{$l_activity->l_activity}}</td>
                        </tr>
                        @endforeach
                    </table>
                @endif
        </div>
        <!-- End of Teaching and Learning Activities -->

        <!-- Course Alignment -->
        <div class="panel panel-default">
            <div class="panel-heading"><h4>Course Alignment</h4></div>
                @if($courseLearningOutcomes->count() < 1)
                    <div class="alert alert-warning text-center">
                        There are no course learning outcomes set for this course.                     
                    </div>
                @else
                    @if ($outcomeActivities->count() < 1 && $outcomeAssessments->count() < 1)
                        <div class="alert alert-warning text-center">
                            Course learning outcomes have not been constructively aligned with student assessment methods and teaching and learning activities for this course.                     
                        </div>
                    @else 
                        <table class="table">
                            <tr class="info">
                                <th class="text-center">#</th>
                                <th>Course Learning Outcome</th>
                                <th>Student Assessment Method</th>
                                <th>Teaching and Learning Activity</th>
                            </tr>

                            @foreach($courseLearningOutcomes as $index => $l_outcome)
                            <tr>
                                <th class="text-center" style="width:5%">{{$index+1}}</th>
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
                        </table>
                    @endif
                @endif
            
        </div>
        <!-- End of Course Alignment -->

        <!-- Program Outcome Maps -->
        @if($course->programs->count() <1 )
            <div class="alert alert-warning text-center">
                This course does not belong to any programs yet.                     
            </div>
        @else
            @foreach ($course->programs as $index => $courseProgram) 
            <!-- Beginning of panel/card for program-->
            <div class="panel panel-default">
                <!-- Program panel/card Heading -->
                <div class="panel-heading"><h4>{{ $courseProgram->program }}</h4></div>

                <!-- Program Learning Outcomes of Program -->
                @if ($programsLearningOutcomes[$courseProgram->program_id]->count() < 1)
                    <div class="alert alert-warning text-center">
                        Program learning outcomes have not been set for this program.                     
                    </div>
                @else 
                    <div class="panel-body">
                        <h5 class="font-weight:bold">Program Learning Outcomes (PLOs)</h5>
                        <p>PLOs are the knowledge, skills and attributes that students are expected to attain by the end of a program of study.</p>
                    </div>
                    <table class="table">
                        <tr class="info">
                            <th class="text-center">#</th>
                            <th>Program Learning Outcome</th>
                        </tr>
                                
                        @if ($courseProgram->ploCategories->count() > 0)
                            <?php $pos = 0 ?>
                            @foreach ($courseProgram->ploCategories as $ploCategory) 
                                @if ($ploCategory->plos->count() > 0)
                                    <tr>
                                        <td colspan="2" class="active">{{$ploCategory->plo_category}}</td>
                                    </tr>
                                    @foreach ($ploCategory->plos as $index => $plo)
                                        <?php $pos++ ?>
                                        <tr>
                                            <td style="width:5%" >{{$pos}}</td>
                                            <td>
                                                <strong>{{$plo->plo_shortphrase}}</strong><br>
                                                {{$plo->pl_outcome}}
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            @endforeach
                            <tr>
                                <td class="active" colspan="2">Uncategorized PLOs</td>
                            </tr>
                            @foreach ($programsLearningOutcomes[$courseProgram->program_id] as $plo) 
                                @if (!isset($plo->category))
                                    <tr>
                                        <td>{{($pos++) + 1}}</td>
                                        <td>
                                            <strong>{{$plo->plo_shortphrase}}</strong><br>
                                            {{$plo->pl_outcome}}
                                        </td>
                                    </tr>
                                @endif
                            @endforeach   
                        @else                                                 
                            @foreach($programsLearningOutcomes[$courseProgram->program_id] as $index => $pl_outcome)
                                <tr>
                                    <td style="width:5%" >{{$index + 1}}</td>
                                    <td>
                                        <strong>{{$pl_outcome->plo_shortphrase}}</strong><br>
                                        {{$pl_outcome->pl_outcome}}
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </table>
                @endif
                <!-- End of Program Learning Outcomes of Program -->

                <!-- Beginning of Mapping Scale Levels for Program -->
                @if ($courseProgram->mappingScaleLevels->count() < 1) 
                    <div class="alert alert-warning text-center">
                        A mapping scale has not been set for this program.                      
                    </div>
                @else 
                    <div class="panel-body">
                        <h5 class="font-weight:bold">Mapping Scales</h5>
                        <p>The mapping scale indicates the degree to which a program learning outcome is addressed by a course learning outcome.</p>
                    </div>
                    <table class="table">
                        <tr class="info">
                            <th colspan="2">Mapping Scale</th>
                        </tr>
    
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
                    </table> 
                @endif
                <!-- End of Mapping Scale Levels for Program -->
                
                <!-- Beginning of PLO to CLO outcome MAP table for program -->
                @if (!array_key_exists($courseProgram->program_id, $courseProgramsOutcomeMaps))
                    <div class="alert alert-warning text-center">
                        Course learning outcomes have not been mapped to this programs learning outcomes.                            
                    </div>
                @else 
                    <div class="panel-body">
                        <h5 class="font-weight:bold">Program Outcome Map: Course Learning Outcomes to Program Learning Outcomes</h5>
                        <p>This table shows the alignment of course learning outcomes to program learning outcomes for this program.</p>
                    </div>

                    <?php $pos = 0 ?>
                    @foreach ($courseProgram->ploCategories as $category)
                        @if ($category->plos->count() <= 0)
                            <div class="alert alert-warning" style="margin:0px" role="alert"><b>Warning! </b>No program learning outcomes belong to this category.</div>
                        @else
                            <table class="table table-bordered table-sm table-condensed" style="width:100%;">                            
                                <tr style="font-size:14px">
                                    <td class="active"></td>
                                    <th class="active" colspan="{{ $courseProgram->programLearningOutcomes->count()}}">{{$category->plo_category}}</th>
                                </tr>
                                <tr class="info" style="font-size:12px">
                                    <th style="width:5%">CLOs</th>
                                    <th class="text-left" colspan="{{ $courseProgram->programLearningOutcomes->count() }}">Program Learning Outcomes</th>
                                </tr> 
                                <tr style="font-size:10px" style="vertical-align:middle;text-align:center">
                                    <td></td>
                                    @foreach ($category->plos as $index => $plo)
                                        @if ($plo->plo_shortphrase && $category->plos->count() < 5)     
                                            @if ($index < $category->plos->count() - ($courseProgram->programLearningOutcomes->count() % $category->plos->count()))   
                                                <th style="vertical-align:middle;text-align:center;" colspan="{{floor($courseProgram->programLearningOutcomes->count() / $category->plos->count())}}">{{$pos + 1}}. {{$plo->plo_shortphrase}}</th>
                                            @else 
                                                <th style="vertical-align:middle;text-align:center;" colspan="{{floor($courseProgram->programLearningOutcomes->count() / $category->plos->count()) + 1}}">{{$pos + 1}}. {{$plo->plo_shortphrase}}</th>
                                            @endif
                                            <?php $pos++ ?>
                                        @else 
                                            @if ($index < $category->plos->count() - ($courseProgram->programLearningOutcomes->count() % $category->plos->count()))   
                                                <th style="vertical-align:middle;text-align:center;" colspan="{{floor($courseProgram->programLearningOutcomes->count() / $category->plos->count())}}">#{{$pos + 1}}</th>
                                            @else 
                                                <th style="vertical-align:middle;text-align:center;" colspan="{{floor($courseProgram->programLearningOutcomes->count() / $category->plos->count()) + 1}}">#{{$pos + 1}}</th>
                                            @endif
                                            <?php $pos++ ?>
                                        @endif
                                    @endforeach
                                </tr>
                                @foreach($courseLearningOutcomes as $clo_index => $l_outcome)
                                    <tr style="font-size:10px">
                                        <th colspan="1">
                                            @if(isset($l_outcome->clo_shortphrase))
                                                {{$clo_index + 1}}. {{$l_outcome->clo_shortphrase}}
                                            @else
                                                #{{$clo_index + 1}}
                                            @endif
                                        </th>

                                        @foreach ($category->plos as $index => $plo)
                                            <!-- Check if this PLO has been mapped -->
                                            @if (!array_key_exists($plo->pl_outcome_id, $courseProgramsOutcomeMaps[$courseProgram->program_id]))
                                                @if ($index < $category->plos->count() - ($courseProgram->programLearningOutcomes->count() % $category->plos->count()))   
                                                    <td colspan="{{floor($courseProgram->programLearningOutcomes->count() / $category->plos->count())}}" style="vertical-align:middle; background-color: white;text-align:center">
                                                        &#63;
                                                    </td>
                                                @else 
                                                    <td colspan="{{floor($courseProgram->programLearningOutcomes->count() / $category->plos->count()) + 1}}" style="vertical-align:middle; background-color: white;text-align:center">
                                                        &#63;
                                                    </td>
                                                @endif
                                            @else 
                                                <!-- Check if this PLO has been mapped to this CLO -->
                                                @if (!array_key_exists($l_outcome->l_outcome_id, $courseProgramsOutcomeMaps[$courseProgram->program_id][$plo->pl_outcome_id]))
                                                    @if ($index < $category->plos->count() - ($courseProgram->programLearningOutcomes->count() % $category->plos->count()))   
                                                        <td colspan="{{floor($courseProgram->programLearningOutcomes->count() / $category->plos->count())}}" style="vertical-align:middle; background-color: white;text-align:center">
                                                            &#63;
                                                        </td>
                                                    @else 
                                                        <td colspan="{{floor($courseProgram->programLearningOutcomes->count() / $category->plos->count()) + 1}}" style="vertical-align:middle; background-color: white;text-align:center">
                                                            &#63;
                                                        </td>
                                                    @endif
                                                @else 
                                                    @if ($index < $category->plos->count() - ($courseProgram->programLearningOutcomes->count() % $category->plos->count()))   
                                                        <td colspan="{{floor($courseProgram->programLearningOutcomes->count() / $category->plos->count())}}" @foreach($programsMappingScales[$courseProgram->program_id] as $programMappingScale) @if($programMappingScale->map_scale_id == $courseProgramsOutcomeMaps[$courseProgram->program_id][$plo->pl_outcome_id][$l_outcome->l_outcome_id]->map_scale_id) style="vertical-align:middle;text-align:center;background-color:{{$programMappingScale->colour}}"@endif @endforeach>
                                                            <p @if($courseProgramsOutcomeMaps[$courseProgram->program_id][$plo->pl_outcome_id][$l_outcome->l_outcome_id]->abbreviation == 'A') style="color:white;" @endif>
                                                                {{$courseProgramsOutcomeMaps[$courseProgram->program_id][$plo->pl_outcome_id][$l_outcome->l_outcome_id]->abbreviation}}
                                                            </p>
                                                        </td>
                                                    @else 
                                                        <td colspan="{{floor($courseProgram->programLearningOutcomes->count() / $category->plos->count()) + 1}}" @foreach($programsMappingScales[$courseProgram->program_id] as $programMappingScale) @if($programMappingScale->map_scale_id == $courseProgramsOutcomeMaps[$courseProgram->program_id][$plo->pl_outcome_id][$l_outcome->l_outcome_id]->map_scale_id) style="vertical-align:middle;text-align:center;background-color:{{$programMappingScale->colour}}"@endif @endforeach>
                                                            <p @if($courseProgramsOutcomeMaps[$courseProgram->program_id][$plo->pl_outcome_id][$l_outcome->l_outcome_id]->abbreviation == 'A') style="color:white;" @endif>
                                                                {{$courseProgramsOutcomeMaps[$courseProgram->program_id][$plo->pl_outcome_id][$l_outcome->l_outcome_id]->abbreviation}}
                                                            </p>
                                                        </td>
                                                    @endif                                                
                                                @endif
                                            @endif
                                        @endforeach
                                    </tr>
                                @endforeach
                            </table>
                        @endif
                    @endforeach
                    <!-- End of categorized PLOs -->
                    
                    <!-- Start of un-categorized PLOs -->
                    @if ($unCategorizedProgramsLearningOutcomes[$courseProgram->program_id]->count() <= 0) 
                        <div class="alert alert-warning" style="margin:0px" role="alert"><b>Warning! </b>No program learning outcomes are un-categorized.</div>
                    @else                    
                        <table class="table table-bordered table-sm table-condensed" style="width:100%;">                            
                                <tr style="font-size:14px">
                                    <!-- Note: not sure why + 2 works here for the colspan. + 1 seems correct to me. Alas -->
                                    <td class="active"></td>
                                    <th class="active" colspan="{{ $courseProgram->programLearningOutcomes->count()}}">Un-categorized</th>
                                </tr>
                                <tr class="info" style="font-size:12px">
                                    <th style="width:5%">CLOs</th>
                                    <!-- Note: Again, not sure why + 1 works here for the colspan. count of plos seems correct to me. Alas -->
                                    <th class="text-left" colspan="{{ $courseProgram->programLearningOutcomes->count()}}">Program Learning Outcomes</th>
                                </tr> 
                                <tr style="font-size:10px">
                                    <td></td>
                                    <?php $cnt = 0 ?>
                                    @foreach ($unCategorizedProgramsLearningOutcomes[$courseProgram->program_id] as $plo)
                                        @if ($plo->plo_shortphrase && $unCategorizedProgramsLearningOutcomes[$courseProgram->program_id]->count() < 5)     
                                            @if ($cnt < $unCategorizedProgramsLearningOutcomes[$courseProgram->program_id]->count() - ($courseProgram->programLearningOutcomes->count() % $unCategorizedProgramsLearningOutcomes[$courseProgram->program_id]->count()))   
                                                <th style="vertical-align:middle;text-align:center;" colspan="{{floor($courseProgram->programLearningOutcomes->count() / $unCategorizedProgramsLearningOutcomes[$courseProgram->program_id]->count()) }}">{{$pos + 1}}. {{$plo->plo_shortphrase}}</th>
                                            @else 
                                                <th style="vertical-align:middle;text-align:center;" colspan="{{floor($courseProgram->programLearningOutcomes->count() / $unCategorizedProgramsLearningOutcomes[$courseProgram->program_id]->count()) + 1}}">{{$pos + 1}}. {{$plo->plo_shortphrase}}</th>
                                            @endif
                                            <?php $pos++ ?>
                                        @else 
                                            @if ($cnt < $unCategorizedProgramsLearningOutcomes[$courseProgram->program_id]->count() - ($courseProgram->programLearningOutcomes->count() % $unCategorizedProgramsLearningOutcomes[$courseProgram->program_id]->count()))   
                                                <th style="vertical-align:middle;text-align:center;" colspan="{{floor($courseProgram->programLearningOutcomes->count() / $unCategorizedProgramsLearningOutcomes[$courseProgram->program_id]->count())}}">#{{$pos + 1}}</th>
                                            @else 
                                                <th style="vertical-align:middle;text-align:center;" colspan="{{floor($courseProgram->programLearningOutcomes->count() / $unCategorizedProgramsLearningOutcomes[$courseProgram->program_id]->count()) + 1}}">#{{$pos + 1}}</th>
                                            @endif
                                            <?php $pos++ ?>
                                        @endif
                                        <?php $cnt++ ?>
                                    @endforeach
                                </tr>
                                @foreach($courseLearningOutcomes as $clo_index => $l_outcome)
                                    <tr style="font-size:10px" >
                                        <th colspan="1">
                                            @if(isset($l_outcome->clo_shortphrase))
                                                {{$clo_index + 1}}. {{$l_outcome->clo_shortphrase}}
                                            @else
                                                #{{$clo_index + 1}}
                                            @endif
                                        </th>
                                        
                                        <?php $cnt = 0 ?>
                                        @foreach ($unCategorizedProgramsLearningOutcomes[$courseProgram->program_id] as $plo)
                                            <!-- Check if this PLO has been mapped -->
                                            @if (!array_key_exists($plo->pl_outcome_id, $courseProgramsOutcomeMaps[$courseProgram->program_id]))
                                                @if ($cnt < $unCategorizedProgramsLearningOutcomes[$courseProgram->program_id]->count() - ($courseProgram->programLearningOutcomes->count() % $unCategorizedProgramsLearningOutcomes[$courseProgram->program_id]->count()))   
                                                    <td colspan="{{floor($courseProgram->programLearningOutcomes->count() / $unCategorizedProgramsLearningOutcomes[$courseProgram->program_id]->count())}}" style="vertical-align:middle; background-color: white;text-align:center">
                                                        &#63;
                                                    </td>
                                                @else 
                                                    <td colspan="{{floor($courseProgram->programLearningOutcomes->count() / $unCategorizedProgramsLearningOutcomes[$courseProgram->program_id]->count()) + 1}}" style="vertical-align:middle; background-color: white;text-align:center">
                                                        &#63;
                                                    </td>
                                                @endif
                                            @else 
                                                <!-- Check if this PLO has been mapped to this CLO -->
                                                @if (!array_key_exists($l_outcome->l_outcome_id, $courseProgramsOutcomeMaps[$courseProgram->program_id][$plo->pl_outcome_id]))
                                                    @if ($cnt < $unCategorizedProgramsLearningOutcomes[$courseProgram->program_id]->count() - ($courseProgram->programLearningOutcomes->count() % $unCategorizedProgramsLearningOutcomes[$courseProgram->program_id]->count()))   
                                                        <td colspan="{{floor($courseProgram->programLearningOutcomes->count() / $unCategorizedProgramsLearningOutcomes[$courseProgram->program_id]->count())}}" style="vertical-align:middle; background-color: white;text-align:center">
                                                            &#63;
                                                        </td>
                                                    @else 
                                                        <td colspan="{{floor($courseProgram->programLearningOutcomes->count() / $unCategorizedProgramsLearningOutcomes[$courseProgram->program_id]->count()) + 1}}" style="vertical-align:middle; background-color: white;text-align:center">
                                                            &#63;
                                                        </td>
                                                    @endif
                                                @else 
                                                    @if ($cnt < $unCategorizedProgramsLearningOutcomes[$courseProgram->program_id]->count() - ($courseProgram->programLearningOutcomes->count() % $unCategorizedProgramsLearningOutcomes[$courseProgram->program_id]->count()))   
                                                        <td colspan="{{floor($courseProgram->programLearningOutcomes->count() / $unCategorizedProgramsLearningOutcomes[$courseProgram->program_id]->count())}}" @foreach($programsMappingScales[$courseProgram->program_id] as $programMappingScale) @if($programMappingScale->map_scale_id == $courseProgramsOutcomeMaps[$courseProgram->program_id][$plo->pl_outcome_id][$l_outcome->l_outcome_id]->map_scale_id) style="vertical-align:middle;text-align:center;background-color:{{$programMappingScale->colour}}"@endif @endforeach>
                                                            <p @if($courseProgramsOutcomeMaps[$courseProgram->program_id][$plo->pl_outcome_id][$l_outcome->l_outcome_id]->abbreviation == 'A') style="color:white;" @endif>
                                                                {{$courseProgramsOutcomeMaps[$courseProgram->program_id][$plo->pl_outcome_id][$l_outcome->l_outcome_id]->abbreviation}}
                                                            </p>
                                                        </td>
                                                    @else 
                                                        <td colspan="{{floor($courseProgram->programLearningOutcomes->count() / $unCategorizedProgramsLearningOutcomes[$courseProgram->program_id]->count()) + 1}}" @foreach($programsMappingScales[$courseProgram->program_id] as $programMappingScale) @if($programMappingScale->map_scale_id == $courseProgramsOutcomeMaps[$courseProgram->program_id][$plo->pl_outcome_id][$l_outcome->l_outcome_id]->map_scale_id) style="vertical-align:middle;text-align:center;background-color:{{$programMappingScale->colour}}"@endif @endforeach>
                                                            <p @if($courseProgramsOutcomeMaps[$courseProgram->program_id][$plo->pl_outcome_id][$l_outcome->l_outcome_id]->abbreviation == 'A') style="color:white;" @endif>
                                                                {{$courseProgramsOutcomeMaps[$courseProgram->program_id][$plo->pl_outcome_id][$l_outcome->l_outcome_id]->abbreviation}}
                                                            </p>
                                                        </td>
                                                    @endif                                                
                                                @endif
                                            @endif
                                            <?php $cnt++ ?>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </table>
                    @endif
                    <!-- End of un-categorized PLOs -->
                @endif 
                <!-- End of PLO to CLO outcome MAP table for program -->
            </div>
            <!-- End of panel/card for program -->
            @endforeach
        @endif
        <!-- End of Program Outcome Maps -->

        <!-- Start of Standards Outcome Maps-->
        <div class="panel panel-default">
            <div class="panel-heading"><h4>BC Degree Standards Outcome Maps</h4></div>
            
            <!-- Start of list of standards -->
            @if ($course->standardCategory->standards->count() < 1)
                    <div class="panel-body">
                        <div class="alert alert-warning text-center">
                            <b>Warning!</b> Standards have not been set for this program.                            
                        </div>
                    </div>
            @else 
                    <div class="panel-body">
                        <h5 class="font-weight:bold">Standards</h5>
                        <p>This table shows the alignment of ministry standards to this course.</p>
                    </div>
                    <table class="table">
                        <tr class="info">
                            <th class="text-center">#</th>
                            <th>Standards</th>            
                        </tr>
                            
                        @foreach($course->standardCategory->standards as $index => $standard)
                            <tr>
                                <th class="text-center" style="width:5%">{{$index+1}}</th>
                                <td>
                                    <strong>{{$standard->s_shortphrase}}</strong><br>
                                    {{$standard->s_outcome}}

                                </td>
                            </tr>
                        @endforeach
                    </table>
            @endif
            <!-- End of list of standards -->

            <!-- Start of list of mapping scales for standards -->
            @if ($course->standardScalesCategory->standardScales->count() < 1) 
                    <div class="panel-body">
                        <div class="alert alert-warning text-center">
                            A mapping scale has not been set for this program.                            
                        </div>
                    </div>  
            @else 
                    <div class="panel-body">
                        <h5 class="font-weight:bold">Standards Mapping Scale</h5>
                        <p>The mapping scale indicates the degree to which a ministry standard is addressed by a course learning outcome.</p>
                    </div>
                    <table class="table">
                        <tr class="info">
                            <th colspan="2">Mapping Scale</th>
                        </tr>
        
                        @foreach($course->standardScalesCategory->standardScales as $standardScale)
                            <tr>
                                <td>
                                    <div style="background-color:{{$standardScale->colour}}; height: 10px; width: 10px;"></div>
                                    {{$standardScale->title}}<br>
                                    ({{$standardScale->abbreviation}})
                                </td>
                                <td>
                                    {{$standardScale->description}}
                                </td>
                            </tr>
                        @endforeach
                    </table> 
            @endif
                <!-- End of list of mapping scales for standards -->
                
                <!-- Start of standard outcome maps -->
            @if (count($standardOutcomeMap) < 1)
                    <div class="panel-body">
                        <div class="alert alert-warning text-center">
                            This course has not been mapped to standards yet.                            
                        </div>
                    </div>
            @else 
                    <div class="panel-body">
                        <h5 class="font-weight:bold">BC Degree Standards Map: {{$course->standardCategory->sc_name}}</h5>
                        <p>This chart shows the alignment of ministry standards to this course.</p>
                    </div>
                    <table class="table table-bordered table-sm table-condensed" style="width:100%;">
                        <tr class="info" style="font-size:14px">
                            <th colspan="{{$course->standardOutcomes->count()}}">BC Degree Standards</th>                                
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
            @endif 
            <!-- End of standard outcome maps -->
        </div>
        <!-- End of Standards Outcome Maps-->

        <!-- Optional Alignment to UBC and BC Degree Standards -->
        <div class="panel panel-default">
            <div class="panel-heading"><h4>Optional Alignment to UBC and BC Degree Standards</h4></div>
                @if($course->optionalPriorities->count() < 1)
                    <div class="alert alert-warning text-center">
                        This course has not aligned with any UBC and Ministry Priorities.                    
                    </div>
                @else
                    <table class="table">
                        <?php $pos = 0 ?>
                        @foreach ($optionalSubcategories as $optionalSubcategory)
                            <tr>
                                <th colspan="2" class="info">{!! $optionalSubcategory->subcat_name !!}</th>
                            </tr>
                            @if ($optionalSubcategory->subcat_id == 1)
                                @foreach ($course->optionalPriorities->where('subcat_id', 1)->pluck('year')->unique()->sortDesc() as $year)
                                    <tr>
                                        <th colspan="2" class="active">{{$year}}</th>
                                    </tr>
                                    @foreach ($course->optionalPriorities->where('subcat_id', 1)->where('year', $year) as $priority)
                                        <?php $pos++ ?>
                                        <tr>
                                            <td style="width:5%">{{$pos}}</td>
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
    </body>
</html>

