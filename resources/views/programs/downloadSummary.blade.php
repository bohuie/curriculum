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
        <div style="margin-bottom:16px">
            <p class="text-right">{{date("Y-m-d")}}</p>
            <h2>Program Overview: {{$program->program}}</h2>
            <p><b>Faculty:</b> {{$program->faculty}}</p>
            <p><b>Department:</b> {{$program->department}}</p>
            <p><b>Level:</b> {{$program->level}}</p>
        </div>
        
        @if($programContent[0]==1) <!-- programContent[0] = PLO flag -->

        <!-- Start of PLOs -->
        <div class="panel panel-default">
            <div class="panel-heading"><h4>Program Learning Outcomes</h4></div>
            @if ($program->programLearningOutcomes->count() < 1)
                <div class="panel-body">
                    <div class="alert alert-warning" role="alert">
                        There are no program learning outcomes set for this course. <a class="alert-link" href="{{route('programWizard.step1', $program->program_id)}}">Add program learning outcomes</a>               
                    </div>
                </div>                
            @else
                <div class="panel-body">
                    <div class="alert alert-info" role="alert" style="margin:0px">
                        <p>Program learning outcomes (PLOs) are the knowledge, skills and attributes that students are expected to attain by the end of a program of study.</p>
                    </div>
                </div>

                <table class="table">
                    <tr class="info" style="font-size:14px;">
                        <th colspan="2">Program Learning Outcome</th>
                    </tr>
                    <tbody>
                        <!--Categorized PLOs -->
                        @foreach ($program->ploCategories as $catIndex => $category)
                            @if ($category->plo_category != NULL)
                                @if ($category->plos->count() > 0)
                                    <tr class="active" style="font-size:12px;">
                                        <th colspan="2">{{$category->plo_category}}</th>
                                    </tr>
                                @endif
                            @endif
                            @foreach($ploProgramCategories as $index => $ploCat)
                                @if ($category->plo_category_id == $ploCat->plo_category_id)
                                        <tr style="font-size:10px">
                                            <td class="text-center active" style="width:5%;vertical-align:middle;"><b>{{$defaultShortFormsIndex[$ploCat->pl_outcome_id]}}</b></td>
                                            <td>
                                                <span><b>{{$ploCat->plo_shortphrase}}</b></span><br>
                                                {{$ploCat->pl_outcome}}
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            @endforeach
                            <!--UnCategorized PLOs -->

                            @if($unCategorizedPLOS->count() > 0)
                                <tr class="active" style="font-size:10px">
                                    <th colspan="2">Uncategorized PLOs</th>
                                </tr>
                                @foreach($unCategorizedPLOS as $unCatIndex => $unCatplo)
                                <tr style="font-size:10px">
                                    <td class="text-center active" style="width:5%;vertical-align:middle;"><b>{{$defaultShortFormsIndex[$unCatplo->pl_outcome_id]}}</b></td>
                                    <td style="font-size:10px;">
                                        <span ><b>{{$unCatplo->plo_shortphrase}}</b></span><br>
                                        {{$unCatplo->pl_outcome}}
                                    </td>
                                </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
        <!-- End of PLOs -->
        @endif

        @if($programContent[1]==1) <!-- programContent[1] = Mapping Scales -->
        <!-- Mapping Scales -->

        <div class="panel panel-default">
            <div class="panel-heading"><h4>Mapping Scales</h4></div>
            @if ( count($mappingScales) < 1) 
                <div class="alert alert-warning">
                    A mapping scale has not been set for this program. <a class="alert-link" href="{{route('programWizard.step2', $program->program_id)}}">Set Mapping Scale</a>                 
                </div>
            @else 
                <div class="panel-body">
                    <div class="alert alert-info" role="alert" style="margin:0px">
                        <p>The mapping scale indicates the degree to which a program learning outcome is addressed by a course learning outcome.</p>
                    </div>
                </div>

                <table class="table">
                    <tr class="info" style="font-size:14px" >
                        <th colspan="2">Mapping Scale</th>
                    </tr>
                    @foreach($program->mappingScaleLevels as $programMappingScale)
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
                    <!--Legend-->
                    <tr class="info">
                        <th colspan="2">Additional Denominations</th>
                    </tr>
                    <tr>
                        <td style="background-color:#999;"></td>
                        <td style="font-size:10px;">Occurs when two or more CLOs map to a PLO an equal amount of times.</td>
                    </tr>
                    <tr style="background-color: #fbfcfc;">
                        <td class="text-center" style="font-size:10px; vertical-align:middle;">&#63;</td>
                        <td style="font-size:10px;">Occurs when a course has not yet been mapped to the set of PLOs.</td>
                    </tr>
                    <tr style="background-color: #fbfcfc;">
                        <td class="text-center" style="font-size:10px; vertical-align:middle;">N/A</td>
                        <td style="font-size:10px;">Occurs when a course instructor has listed a program learning outcome as being not applicable for a program learning outcome.</td>
                    </tr>
                    <!-- End Legend-->    
                </table>
            @endif
        </div>
        <!--End Mapping Scales-->
        @endif

        @if($programContent[2]==1) <!-- programContent[2] = Frequency Distribution Tables flag -->
        <!-- START of frequency distribution tables -->
        <div class="panel panel-default">
            <div class="panel-heading"><h4>Curriculum MAP: Frequency Distribution Tables</h4></div>
                @if($program->courses->count() < 1 )
                    <div class="panel-body">
                        <div class="alert alert-warning" role="alert">
                            There are no courses assigned to this program yet. <a class="alert-link" href="{{route('programWizard.step3', $program->program_id)}}">Add courses</a>               
                        </div>
                    </div>
                @elseif ($program->programLearningOutcomes->count() < 1) 
                    <div class="panel-body">
                        <div class="alert alert-warning" role="alert">
                            No program learning outcomes have been added to this program yet. <a class="alert-link" href="{{route('programWizard.step1', $program->program_id)}}">Add program learning outcomes</a>                  
                        </div>
                    </div>
                @else
                    <div class="panel-body">
                        <div class="alert alert-info" role="alert" style="margin:0px">
                            <p>This table shows the alignment of all courses to program learning outcomes for this program.</p>
                        </div>
                    </div>
                    @foreach ($coursesByLevels as $courseLevelTitle => $coursesByLevel) 
                        @if ($coursesByLevel->count() > 0)
                            <h5 class="alert text-center" style="margin-bottom: 0px"><b><u>{{ $courseLevelTitle }}</u></b></h5>

                            <table class="table table-bordered table-sm table-condensed" style="width:100%;">
                                <tr class="info" style="font-size:14px">
                                    <th>Courses</th>
                                    <th class="text-left" colspan="{{ $program->programLearningOutcomes->count() }}">Program Learning Outcomes</th>
                                </tr>
                                @foreach ($program->ploCategories as $category) 
                                    <tr style="font-size:12px">
                                        <th class="active"></th>
                                        <th class="active" colspan="{{ $program->programLearningOutcomes->count() }}">{{$category->plo_category}}</th>
                                    </tr>
                                    @if ($category->plos->count() <= 0)
                                        <tr style="font-size:10px">
                                            <td class="active"></td>
                                            <td colspan="{{ $program->programLearningOutcomes->count() }}"><div class="alert alert-warning" style="margin:0px" role="alert"><b>Warning! </b>No program learning outcomes belong to this category.</div></td>
                                        </tr>
                                    @else 
                                        <tr style="font-size:10px">
                                            <td class="active"></td>
                                            @foreach ($category->plos as $index => $plo)
                                                @if ($plo->plo_shortphrase && $category->plos->count() < 5)     
                                                    @if ($index < $category->plos->count() - ($program->programLearningOutcomes->count() % $category->plos->count()))   
                                                        <td colspan="{{floor($program->programLearningOutcomes->count() / $category->plos->count())}}"><b>{{$plo->plo_shortphrase}}</b></td>
                                                    @else 
                                                        <td colspan="{{floor($program->programLearningOutcomes->count() / $category->plos->count()) + 1}}"><b>{{$plo->plo_shortphrase}}</b></td>
                                                    @endif
                                                @else 
                                                    @if ($index < $category->plos->count() - ($program->programLearningOutcomes->count() % $category->plos->count()))   
                                                        <td colspan="{{floor($program->programLearningOutcomes->count() / $category->plos->count())}}"><b>{{$defaultShortForms[$plo->pl_outcome_id]}}</b></td>
                                                    @else 
                                                        <td colspan="{{floor($program->programLearningOutcomes->count() / $category->plos->count()) + 1}}"><b>{{$defaultShortForms[$plo->pl_outcome_id]}}</b></td>
                                                    @endif
                                                @endif
                                            @endforeach
                                        </tr>
                                        @foreach($coursesByLevel->sortBy([ ['course_code', 'asc'], ['course_num', 'asc'], ]) as $index => $course)
                                            <tr style="font-size:10px" style="vertical-align:middle;text-align:center">
                                                <td colspan="1">
                                                    <b>
                                                        {{$course->course_code}} {{$course->course_num}} {{$course->section}}
                                                        <br>
                                                        {{$course->semester}} {{$course->year}}
                                                        <br>
                                                    </b>
                                                    @if($course->pivot->course_required == 1)
                                                        <i style="color:red">Required</i>
                                                    @elseif($course->pivot->course_required == 0)
                                                        <i>Not Required</i>
                                                    @endif
                                                </td>
                                                @foreach ($category->plos as $index => $plo)
                                                    @if(isset($store[$plo->pl_outcome_id][$course->course_id]))
                                                        <!-- Check if a Tie is present -->
                                                        @if(isset($store[$plo->pl_outcome_id][$course->course_id]['map_scale_id_tie']))
                                                            @if ($index < $category->plos->count() - ($program->programLearningOutcomes->count() % $category->plos->count()))   
                                                                <td colspan="{{floor($program->programLearningOutcomes->count() / $category->plos->count())}}" style="vertical-align:middle; background-color:#999;text-align:center">
                                                                    <span>
                                                                        {{$store[$plo->pl_outcome_id][$course->course_id]['map_scale_abv']}}
                                                                    </span>
                                                                </td>
                                                            @else 
                                                                <td colspan="{{floor($program->programLearningOutcomes->count() / $category->plos->count()) + 1}}" style="vertical-align:middle; background-color:#999;text-align:center">
                                                                    <span>
                                                                        {{$store[$plo->pl_outcome_id][$course->course_id]['map_scale_abv']}}
                                                                    </span>
                                                                </td>
                                                            @endif
                                                        @else
                                                            @if ($index < $category->plos->count() - ($program->programLearningOutcomes->count() % $category->plos->count()))   
                                                                <td colspan="{{floor($program->programLearningOutcomes->count() / $category->plos->count())}}" style="vertical-align:middle; background-color:{{ $store[$plo->pl_outcome_id][$course->course_id]['colour'] }};text-align:center">
                                                                    <span>
                                                                        {{$store[$plo->pl_outcome_id][$course->course_id]['map_scale_abv']}}
                                                                    </span>
                                                                </td>
                                                            @else 
                                                                <td colspan="{{floor($program->programLearningOutcomes->count() / $category->plos->count()) + 1}}" style="vertical-align:middle; background-color:{{ $store[$plo->pl_outcome_id][$course->course_id]['colour'] }};text-align:center">
                                                                    <span>
                                                                        {{$store[$plo->pl_outcome_id][$course->course_id]['map_scale_abv']}}
                                                                    </span>
                                                                </td>
                                                            @endif
                                                        @endif
                                                    @else
                                                        @if ($index < $category->plos->count() - ($program->programLearningOutcomes->count() % $category->plos->count()))   
                                                            <td colspan="{{floor($program->programLearningOutcomes->count() / $category->plos->count())}}" style="vertical-align:middle; background-color: white;text-align:center">
                                                                &#63;
                                                            </td>
                                                        @else 
                                                            <td colspan="{{floor($program->programLearningOutcomes->count() / $category->plos->count()) + 1}}" style="vertical-align:middle; background-color: white;text-align:center">
                                                                &#63;
                                                            </td>
                                                        @endif
                                                    @endif                            
                                                @endforeach
                                            </tr>
                                        @endforeach
                                    @endif
                                @endforeach
                                <tr style="font-size:12px">
                                    <th class="active"></th>
                                    <th class="active" colspan="{{ $program->programLearningOutcomes->count() }}">Non-categorized program learning outcomes</th>
                                </tr>
                                @if ($unCategorizedPLOS->count() <= 0)
                                    <tr style="font-size:10px">
                                        <td class="active"></td>
                                        <td colspan="{{ $program->programLearningOutcomes->count() }}"><div class="alert alert-warning" style="margin:0px" role="alert"><b>Warning! </b>No program learning outcomes belong to this category.</div></td>
                                    </tr>
                                @else
                                    <tr style="font-size:10px">
                                        <td class="active"></td>
                                        
                                        @foreach ($unCategorizedPLOS as $index => $unCatPLO)
                                            @if ($unCatPLO->plo_shortphrase && $unCategorizedPLOS->count() < 5)     
                                                @if ($index < $unCategorizedPLOS->count() - ($program->programLearningOutcomes->count() % $unCategorizedPLOS->count()))   
                                                    <td colspan="{{floor($program->programLearningOutcomes->count() / $unCategorizedPLOS->count())}}"><b>{{$unCatPLO->plo_shortphrase}}</b></td>
                                                @else 
                                                    <td colspan="{{floor($program->programLearningOutcomes->count() / $unCategorizedPLOS->count()) + 1}}"><b>{{$unCatPLO->plo_shortphrase}}</b></td>
                                                @endif
                                            @else 
                                                @if ($index < $unCategorizedPLOS->count() - ($program->programLearningOutcomes->count() % $unCategorizedPLOS->count()))   
                                                    <td colspan="{{floor($program->programLearningOutcomes->count() / $unCategorizedPLOS->count())}}"><b>{{$defaultShortForms[$unCatPLO->pl_outcome_id]}}</b></td>
                                                @else 
                                                    <td colspan="{{floor($program->programLearningOutcomes->count() / $unCategorizedPLOS->count()) + 1}}"><b>{{$defaultShortForms[$unCatPLO->pl_outcome_id]}}</b></td>
                                                @endif
                                            @endif
                                        @endforeach
                                    </tr>
                                    @foreach($coursesByLevel->sortBy([ ['course_code', 'asc'], ['course_num', 'asc'], ]) as $course)
                                        <tr style="font-size:10px">
                                            <td colspan="1">
                                                <b>
                                                    {{$course->course_code}} {{$course->course_num}} {{$course->section}}
                                                    <br>
                                                    {{$course->semester}} {{$course->year}}
                                                </b>
                                            </td>
                                            
                                            @foreach ($unCategorizedPLOS as $index => $unCatPLO)
                                                @if ($unCatPLO->plo_category == NULL)
                                                    <!-- Check if ['pl_outcome_id']['course_id'] are in the array -->
                                                    @if(isset($store[$unCatPLO->pl_outcome_id][$course->course_id]))
                                                        <!-- Check if a Tie is present -->
                                                        @if(isset($store[$unCatPLO->pl_outcome_id][$course->course_id]['map_scale_id_tie']))
                                                            @if ($index < $unCategorizedPLOS->count() - ($program->programLearningOutcomes->count() % $unCategorizedPLOS->count()))   
                                                                <td colspan="{{floor($program->programLearningOutcomes->count() / $unCategorizedPLOS->count())}}" class="text-center align-middle" style="vertical-align:middle;">
                                                                    <span>
                                                                        {{$store[$unCatPLO->pl_outcome_id][$course->course_id]['map_scale_abv']}}
                                                                    </span>
                                                                </td>
                                                            @else 
                                                                <td colspan="{{floor($program->programLearningOutcomes->count() / $unCategorizedPLOS->count()) + 1}}" class="text-center align-middle" style="vertical-align:middle;">
                                                                    <span>
                                                                        {{$store[$unCatPLO->pl_outcome_id][$course->course_id]['map_scale_abv']}}
                                                                    </span>
                                                                </td>
                                                            @endif
                                                        @else
                                                            @if ($index < $unCategorizedPLOS->count() - ($program->programLearningOutcomes->count() % $unCategorizedPLOS->count()))   
                                                                <td colspan="{{floor($program->programLearningOutcomes->count() / $unCategorizedPLOS->count())}}" class="text-center align-middle" style="vertical-align:middle; background-color: {{ $store[$unCatPLO->pl_outcome_id][$course->course_id]['colour'] }};">
                                                                    <span>
                                                                        {{$store[$unCatPLO->pl_outcome_id][$course->course_id]['map_scale_abv']}}
                                                                    </span>
                                                                </td>

                                                            @else
                                                                <td colspan="{{floor($program->programLearningOutcomes->count() / $unCategorizedPLOS->count()) + 1}}" class="text-center align-middle" style="vertical-align:middle; background-color: {{ $store[$unCatPLO->pl_outcome_id][$course->course_id]['colour'] }};" >
                                                                    <span>
                                                                        {{$store[$unCatPLO->pl_outcome_id][$course->course_id]['map_scale_abv']}}
                                                                    </span>
                                                                </td>
                                                            @endif
                                                        @endif
                                                    @else
                                                        @if ($index < $unCategorizedPLOS->count() - ($program->programLearningOutcomes->count() % $unCategorizedPLOS->count()))   
                                                            <td colspan="{{floor($program->programLearningOutcomes->count() / $unCategorizedPLOS->count())}}" class="text-center align-middle" style="vertical-align:middle; background-color: white;">
                                                                &#63;
                                                            </td>    
                                                        @else
                                                            <td colspan="{{floor($program->programLearningOutcomes->count() / $unCategorizedPLOS->count()) + 1}}" class="text-center align-middle" style="vertical-align:middle; background-color: white;">
                                                                &#63;
                                                            </td>    
                                                        @endif
                                                    @endif
                                                @endif
                                            @endforeach
                                        </tr>
                                    @endforeach
                                @endif
                            </table>
                        @endif
                    @endforeach
                @endif
            </div>  
        </div>
        <!-- END of frequency distribution tables -->
        @endif
        
        <!-- START of Bar Charts -->
        @if($programContent[3]==1 || $programContent[4]==1 || $programContent[5]==1 || $programContent[6]==1 )
        <div class="panel panel-default">
            <div class="panel-heading"><h4>Curriculum MAP: Bar Charts</h4></div>
                @if($program->courses->count() < 1 )
                    <div class="panel-body">
                        <div class="alert alert-warning" role="alert">
                            There are no courses assigned to this program yet. <a class="alert-link" href="{{route('programWizard.step3', $program->program_id)}}">Add courses</a>               
                        </div>
                    </div>
                @elseif ($program->programLearningOutcomes->count() < 1) 
                    <div class="panel-body">
                        <div class="alert alert-warning" role="alert">
                            No program learning outcomes have been added to this program yet. <a class="alert-link" href="{{route('programWizard.step1', $program->program_id)}}">Add program learning outcomes</a>                  
                        </div>
                    </div>
                @elseif ($program->mappingScaleLevels->count() < 1)
                    <div class="panel-body">
                        <div class="alert alert-warning" role="alert">
                            There are no mapping scales for this program. <a class="alert-link" href="{{route('programWizard.step2', $program->program_id)}}">Add a mapping scale</a>                  
                        </div>
                    </div>
                @else
                    @if($programContent[3]==1) <!-- programContent[3] = CLO Bar Chart flag -->
                    <div class="panel-body">
                        <div class="alert alert-info" role="alert" style="margin:0px">
                            <p>This chart shows how many course learning outcomes (CLOs) are aligned to each program learning outcome (PLO).</p>
                        </div>
                    </div>
                    <img src={{$charts["Program MAP Chart"]}} width="600">
                    @endif
                    @if($programContent[4]==1) <!-- programContent[0] = Assessment Methods Chart flag -->
                        @if ($charts["Assessment Methods Chart"])
                            <div class="panel-body">
                                <div class="alert alert-info" role="alert" style="margin:0px">
                                    <p>This chart shows the frequencies of the assessment methods for courses belonging to this program.</p>
                                </div>
                            </div>
                            <img src={{$charts["Assessment Methods Chart"]}} width="600">
                        @else 
                            <div class="panel-body">
                                <div class="alert alert-warning" role="alert">
                                    There are no assessment methods for the courses belonging to this program.                  
                                </div>
                            </div>
                        @endif
                    @endif
                    @if($programContent[5]==1) <!-- programContent[5] = Learning Activities Chart flag -->
                        @if ($charts["Learning Activities Chart"])
                            <div class="panel-body">
                                <div class="alert alert-info" role="alert" style="margin:0px">
                                    <p>This chart shows the frequencies of the learning activities for courses belonging to this program.</p>
                                </div>
                            </div>
                            <img src={{$charts["Learning Activities Chart"]}} width="600">
                        @else 
                            <div class="panel-body">
                                <div class="alert alert-warning" role="alert">
                                    There are no learning activities for the courses belonging to this program.                  
                                </div>
                            </div>
                        @endif
                    @endif
                    @if($programContent[6]==1) <!-- programContent[6] = Ministry Standards Chart flag -->
                        @if ($charts["Ministry Standards Chart"])
                            <div class="panel-body">
                                <div class="alert alert-info" role="alert" style="margin:0px">
                                    <p>This chart shows how the ministry standards are aligned with each course belonging to this program.</p>
                                </div>
                            </div>
                            <img src={{$charts["Ministry Standards Chart"]}} width="600">
                            <div class="panel-body mt-2">
                                {!! $tableMS !!}
                            </div>
                        @else 
                            <div class="panel-body">
                                <div class="alert alert-warning" role="alert">
                                    There are no standards for the courses belonging to this program.                  
                                </div>
                            </div>
                        @endif
                    @endif
                @endif
            </div>  
        </div>
        @endif
        <!-- END of frequency distribution tables -->
    </body>
</html>