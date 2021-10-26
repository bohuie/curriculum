@extends('layouts.app')

@section('content')
<div>
    <div class="row justify-content-center">
        <div class="col-md-12">
            @include('programs.wizard.header')
            
            <div class="card">
                <h3 class="card-header wizard">
                    <div class="row">
                        <div class="col text-left">
                            <a class="w-25" href="{{route('programs.pdf', $program->program_id)}}">
                                <button class="btn btn-primary" onclick="{{route('programs.pdf', $program->program_id)}}">
                                    Download PDF <i class="bi bi-download"></i>
                                </button>
                            </a>
                        </div>

                        <div class="col">
                            Program Overview
                        </div>

                        <div class="col text-right">
                            <button id="programOverviewHelp" style="border: none; background: none; outline: none;" data-bs-toggle="modal" href="#guideModal">
                                <i class="bi bi-question-circle" style="color:#002145;"></i>
                            </button>
                        </div>
                        <div class="text-left">
                            @include('layouts.guide')
                        </div>
                    </div>
                </h3>
                        @if ($hasUnMappedCourses)
                            <div class="toast-container position-fixed bottom-0 end-0 p-3" id="toastPlacement" style="z-index: 11">
                                <div id="notification" class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="false">
                                    <div class="toast-header bg-warning">
                                        <i class="bi bi-exclamation-triangle-fill"></i>
                                        <strong class="me-auto pl-2">Alert</strong>
                                        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                                    </div>
                                    <div class="toast-body">
                                        There are courses that haven't been fully mapped to this program.
                                    </div>
                                </div>
                            </div>
                        @endif
                        <!-- Program Learning Outcomes -->
                        <div class="card-body">
                            <h5 class="card-title">
                                Program Learning Outcomes
                            </h5>

                            @if ( count($plos) < 1)
                                <div class="alert alert-warning wizard">
                                    <i class="bi bi-exclamation-circle-fill"></i>There are no program learning outcomes for this program.                  
                                </div>
                            @else
                                <p>Program-level learning outcomes (PLOs) are the knowledge, skills and attributes that students are expected to attain by the end of a program of study.</p>
                                <table class="table table-light table-bordered table" style="width: 95%; margin: auto; table-layout:auto;">
                                    <tr class="table-primary">
                                        <th class="text-left" colspan="2">Program Learning Outcome</th>
                                    </tr>
                                    <tbody>
                                        <!--Categorized PLOs -->
                                        @foreach ($ploCategories as $catIndex => $plo)
                                            @if ($plo->plo_category != NULL)
                                                @if ($plo->plos->count() > 0)
                                                    <tr class="table-secondary">
                                                        <th class="text-left" colspan="2">{{$plo->plo_category}} 
                                                        @if ($numCatUsed > 3)    
                                                            : (C - {{$catIndex + 1}})</th>
                                                        @endif
                                                    </tr>
                                                @endif
                                            @endif
                                            @foreach($ploProgramCategories as $index => $ploCat)
                                                @if ($plo->plo_category_id == $ploCat->plo_category_id)
                                                    <tr>
                                                        <td class="text-center align-middle">{{$index + 1}}</td>
                                                        <td>
                                                            <span style="font-weight: bold;">{{$ploCat->plo_shortphrase}}</span><br>
                                                            {{$ploCat->pl_outcome}}
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        @endforeach
                                        <!--UnCategorized PLOs -->
                                        @if($hasUncategorized)
                                            <tr class="table-secondary">
                                                <th class="text-left" colspan="2">Uncategorized</th>
                                            </tr>
                                        @endif
                                        @foreach($unCategorizedPLOS as $unCatIndex => $unCatplo)
                                            <tr>
                                                <td class="text-center align-middle">{{count($ploProgramCategories) + $unCatIndex + 1}}</td>
                                                <td>
                                                    <span style="font-weight: bold;">{{$unCatplo->plo_shortphrase}}</span><br>
                                                    {{$unCatplo->pl_outcome}}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @endif
                        </div>

                        <!-- Mapping scales -->
                            <div class="card-body">
                                <h5 class="card-title">
                                    Mapping Scale
                                </h5>
                                @if ( count($mappingScales) < 1) 
                                    <div class="alert alert-warning wizard">
                                        <i class="bi bi-exclamation-circle-fill"></i>A mapping scale has not been set for this program.                  
                                    </div>
                                @else 
                                    <p>The mapping scale indicates the degree to which a program learning outcome is addressed by a course learning outcome.</p>
                                    <table class="table table-bordered table-sm" style="width: 95%; margin: auto; table-layout:auto;">
                                        <tr class="table-primary">
                                            <th class="text-left" colspan="2">Mapping Scale</th>
                                        </tr>
                                        <tbody>
                                            @foreach($mappingScales as $ms)
                                                <tr>
                                                    <td>
                                                        <div style="background-color:{{$ms->colour}}; height: 10px; width: 10px;"></div>
                                                        {{$ms->title}}<br>
                                                        ({{$ms->abbreviation}})
                                                    </td>
                                                    <td colspan="1">
                                                        {{$ms->description}}
                                                    </td>
                                                </tr>
                                            @endforeach
                                                <!--Legend-->
                                                <tr class="table-primary">
                                                    <th class="text-left" colspan="2">Additional Denominations</th>
                                                </tr>
                                                <tr>
                                                    <td style="background:repeating-linear-gradient(45deg, transparent, transparent 4px, #ccc 4px, #ccc 8px), linear-gradient( to bottom, #fff, #999); height: 50px; width: 50px;">
                                                    </td>
                                                    <td>
                                                        Occurs when two or more CLOs map to a PLO an equal number of times.
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-center align-middle">
                                                        <i class="bi bi-exclamation-circle-fill" data-toggle="tooltip" data-html="true" data-bs-placement="right" title="Incomplete"></i>
                                                    </td>
                                                    <td>
                                                        Occurs when a course has not yet been mapped to the set of PLOs.
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-center align-middle">
                                                        N/A
                                                    </td>
                                                    <td>
                                                        Occurs when a course instructor has listed a program learning outcome as being not applicable for a program learning outcome.
                                                    </td>
                                                </tr>
                                        </tbody>
                                    </table>
                                @endif
                            </div>
                        
                            <!-- Column Chart -->
                            <div class="mt-5">
                                <form action="">
                                    <div class=" mx-5 mt-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="chart_select" id="Cluster" checked>
                                            <label class="form-check-label" for="Cluster"><b>Cluster Chart</b></label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="chart_select" id="Stacked">
                                            <label class="form-check-label" for="Stacked"><b>Stacked Chart</b></label>
                                        </div>
                                    </div>
                                </form>
                                <div class="container mt-0">
                                    <div id="high-chart"></div>
                                </div>
                            </div>

                        <div class="card-body">
                            <nav class="mt-2">
                                <div class="nav nav-tabs justify-content-center" id="nav-tab" role="tablist">
                                    <button class="nav-link active w-25" id="nav-all-tab" data-bs-toggle="tab" data-bs-target="#nav-all" type="button" role="tab" aria-controls="nav-all" aria-selected="true">All Courses</button>
                                    <button class="nav-link w-25" id="nav-required-tab" data-bs-toggle="tab" data-bs-target="#nav-required" type="button" role="tab" aria-controls="nav-required" aria-selected="false">Required Courses</button>
                                    <button class="nav-link w-25" id="nav-first-tab" data-bs-toggle="tab" data-bs-target="#nav-first" type="button" role="tab" aria-controls="nav-first" aria-selected="false">100 Level Courses</button>
                                    <button class="nav-link w-25" id="nav-second-tab" data-bs-toggle="tab" data-bs-target="#nav-second" type="button" role="tab" aria-controls="nav-second" aria-selected="false">200 Level Courses</button>
                                    <button class="nav-link w-25" id="nav-third-tab" data-bs-toggle="tab" data-bs-target="#nav-third" type="button" role="tab" aria-controls="nav-third" aria-selected="false">300 Level Courses</button>
                                    <button class="nav-link w-25" id="nav-fourth-tab" data-bs-toggle="tab" data-bs-target="#nav-fourth" type="button" role="tab" aria-controls="nav-fourth" aria-selected="false">400 Level Courses</button>
                                    <button class="nav-link w-25" id="nav-graduate-tab" data-bs-toggle="tab" data-bs-target="#nav-graduate" type="button" role="tab" aria-controls="nav-graduate" aria-selected="false">500/600 Level Courses</button>
                                </div>
                            </nav>
                            <div class="tab-content" id="nav-tabContent">
                                
                                <div class="tab-pane fade show active" id="nav-all" role="tabpanel" aria-labelledby="nav-all-tab">
                                    <!-- ALL COURSES frequency distribution table -->
                                    <div class="card-body">
                                        <h5 class="card-title">
                                            Curriculum Map
                                        </h5>
                                        @if( count($programCourses) < 1 )
                                            <div class="alert alert-warning wizard">
                                                <i class="bi bi-exclamation-circle-fill pr-2 fs-5"></i>There are no courses set for this program yet.                   
                                            </div>
                                        @elseif ($ploCount < 1) 
                                            <div class="alert alert-warning wizard">
                                                <i class="bi bi-exclamation-circle-fill pr-2 fs-5"></i>There are no program learning outcomes for this program.                   
                                            </div>
                                        @else
                                            <p>This chart shows the alignment of courses to program learning outcomes for this program.</p>

                                            <table class="table table-bordered table-sm" style="width: 95%; margin:auto; table-layout: fixed; border: 1px solid white; color: black;">
                                                <tr class="table-primary">
                                                    <th colspan='1' class="w-auto">Courses</th>
                                                    <th class="text-left" colspan='{{ count($plos) }}'>Program-level Learning Outcomes</th>
                                                </tr>
                                                <tr>
                                                    <th colspan='1' style="background-color: rgba(0, 0, 0, 0.03);"></th>
                                                    <!-- Displays Categories -->
                                                    @foreach($ploCategories as $index =>$plo)
                                                        @if ($plo->plo_category != NULL)
                                                            <!-- Use short name for category if there are more than 3 -->
                                                            @if (($numCatUsed > 3) && ($plo->plos->count() > 0))
                                                                <th colspan='{{ $plosPerCategory[$plo->plo_category_id] }}' style="background-color: rgba(0, 0, 0, 0.03);">C - {{$index + 1}}</th>
                                                            @elseif ($plo->plos->count() > 0)
                                                                <th colspan='{{ $plosPerCategory[$plo->plo_category_id] }}' style="background-color: rgba(0, 0, 0, 0.03);">{{$plo->plo_category}}</th>
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                    <!-- Heading appended at the end, if there are Uncategorized PLOs  -->
                                                    @if($hasUncategorized)
                                                        <th colspan="{{$numUncategorizedPLOS}}" style="background-color: rgba(0, 0, 0, 0.03);">Uncategorized PLOs</th>
                                                    @endif
                                                </tr>

                                                <tr>
                                                    <th colspan='1' style="background-color: rgba(0, 0, 0, 0.03);"></th>
                                                    <!-- If there are less than 7 PLOs, use the short-phrase, else use PLO at index + 1 -->
                                                    @if (count($plos) < 7) 
                                                        <!-- Categorized PLOs -->
                                                        @foreach($ploProgramCategories as $plo)
                                                            @if ($plo->plo_category != NULL)
                                                                <th style="background-color: rgba(0, 0, 0, 0.03);">{{$plo->plo_shortphrase}}</th>
                                                            @endif
                                                        @endforeach
                                                        <!-- Uncategorized PLOs -->
                                                        @foreach($plos as $plo)
                                                            @if ($plo->plo_category == NULL)
                                                                <th style="background-color: rgba(0, 0, 0, 0.03);">{{$plo->plo_shortphrase}}</th>
                                                            @endif
                                                        @endforeach
                                                    @else
                                                        @foreach($plos as $index => $plo)
                                                            <th style="background-color: rgba(0, 0, 0, 0.03);">PLO: {{$index + 1}}</th>
                                                        @endforeach
                                                    @endif
                                                </tr>
                                                <!-- Show all courses associated to the program -->
                                                @foreach($programCourses as $course)
                                                    <tr>
                                                        <th colspan="1" style="background-color: rgba(0, 0, 0, 0.03);">
                                                        {{$course->course_code}} {{$course->course_num}} {{$course->section}}
                                                        <br>
                                                        {{$course->semester}} {{$course->year}}
                                                        </th>
                                                        <!-- Frequency distribution from each course -->
                                                        <!-- For Each Categorized PLO -->
                                                        @foreach($ploProgramCategories as $index => $plo)
                                                            @if ($plo->plo_category != NULL)
                                                            <!-- Check if ['pl_outcome_id']['course_id'] are in the array -->
                                                                @if(isset($testArr[$plo->pl_outcome_id][$course->course_id]))
                                                                    <!-- Check if a Tie is present -->
                                                                    @if(isset($testArr[$plo->pl_outcome_id][$course->course_id]['map_scale_id_tie']))
                                                                        <td class="text-center align-middle" style="background:repeating-linear-gradient(45deg, transparent, transparent 8px, #ccc 8px, #ccc 16px), linear-gradient( to bottom, #fff, #999);" data-toggle="tooltip" data-html="true" data-bs-placement="right" title="@foreach($testArr[$plo->pl_outcome_id][$course->course_id]['frequencies'] as $index => $freq) {{$index}}: {{$freq}}<br> @endforeach">
                                                                            <span style="color: black;">
                                                                                {{$testArr[$plo->pl_outcome_id][$course->course_id]['map_scale_abv']}}
                                                                            </span>
                                                                        </td>
                                                                    @else
                                                                        <td class="text-center align-middle" style="background-color: {{ $testArr[$plo->pl_outcome_id][$course->course_id]['colour'] }};" data-toggle="tooltip" data-html="true" data-bs-placement="right" title="@foreach($testArr[$plo->pl_outcome_id][$course->course_id]['frequencies'] as $index => $freq) {{$index}}: {{$freq}}<br> @endforeach">
                                                                            <span style="color: black;">
                                                                                {{$testArr[$plo->pl_outcome_id][$course->course_id]['map_scale_abv']}}
                                                                            </span>
                                                                        </td>
                                                                    @endif

                                                                @else
                                                                    <td class="text-center align-middle" style="background-color: white;">
                                                                        <i class="bi bi-exclamation-circle-fill" data-toggle="tooltip" data-html="true" data-bs-placement="right" title="Incomplete"></i>
                                                                    </td>
                                                                @endif
                                                            @endif
                                                        @endforeach
                                                        <!-- For Each Uncategorized PLO-->
                                                        @foreach($plos as $plo)
                                                            @if ($plo->plo_category == NULL)
                                                                <!-- Check if ['pl_outcome_id']['course_id'] are in the array -->
                                                                @if(isset($testArr[$plo->pl_outcome_id][$course->course_id]))
                                                                    <!-- Check if a Tie is present -->
                                                                    @if(isset($testArr[$plo->pl_outcome_id][$course->course_id]['map_scale_id_tie']))
                                                                        <td class="text-center align-middle" style="background:repeating-linear-gradient( 45deg, transparent, transparent 10px, #ccc 10px, #ccc 20px), linear-gradient( to bottom, #eee, #999);" data-toggle="tooltip" data-html="true" data-bs-placement="right" title="@foreach($testArr[$plo->pl_outcome_id][$course->course_id]['frequencies'] as $index => $freq) {{$index}}: {{$freq}}<br> @endforeach">
                                                                            <span style="color: black;">
                                                                                {{$testArr[$plo->pl_outcome_id][$course->course_id]['map_scale_abv']}}
                                                                            </span>
                                                                        </td>
                                                                    @else
                                                                        <td class="text-center align-middle" style="background-color: {{ $testArr[$plo->pl_outcome_id][$course->course_id]['colour'] }};" data-toggle="tooltip" data-html="true" data-bs-placement="right" title="@foreach($testArr[$plo->pl_outcome_id][$course->course_id]['frequencies'] as $index => $freq) {{$index}}: {{$freq}}<br> @endforeach">
                                                                            <span style="color: black;">
                                                                                {{$testArr[$plo->pl_outcome_id][$course->course_id]['map_scale_abv']}}
                                                                            </span>
                                                                        </td>
                                                                    @endif

                                                                @else
                                                                    <td class="text-center align-middle" style="background-color: white;">
                                                                        <i class="bi bi-exclamation-circle-fill" data-toggle="tooltip" data-html="true" data-bs-placement="right" title="Incomplete"></i>
                                                                    </td>
                                                                @endif
                                                            @endif
                                                        @endforeach
                                                    </tr>
                                                @endforeach
                                            </table>
                                        @endif
                                    </div>  
                                <!-- end Courses to PLOs frequency Distribution card -->
                                </div>
                                <!--End of tab-pane-->

                                <div class="tab-pane fade" id="nav-first" role="tabpanel" aria-labelledby="nav-first-tab">
                                    <!-- First Year Courses frequency distribution table -->
                                    <div class="card-body">
                                        <h5 class="card-title">
                                            Curriculum Map
                                        </h5>
                                        @if( count($firstYearProgramCourses) < 1 )
                                            <div class="alert alert-warning wizard">
                                                <i class="bi bi-exclamation-circle-fill pr-2 fs-5"></i>There are no first year courses set for this program yet.                   
                                            </div>
                                        @elseif ($ploCount < 1) 
                                            <div class="alert alert-warning wizard">
                                                <i class="bi bi-exclamation-circle-fill pr-2 fs-5"></i>There are no program learning outcomes for this program.                   
                                            </div>
                                        @else
                                            <p>This chart shows the alignment of courses to program learning outcomes for this program.</p>

                                            <table class="table table-bordered table-sm" style="width: 95%; margin:auto; table-layout: fixed; border: 1px solid white; color: black;">
                                                <tr class="table-primary">
                                                    <th colspan='1' class="w-auto">Courses</th>
                                                    <th class="text-left" colspan='{{ count($plos) }}'>Program-level Learning Outcomes</th>
                                                </tr>
                                                <tr>
                                                    <th colspan='1' style="background-color: rgba(0, 0, 0, 0.03);"></th>
                                                    <!-- Displays Categories -->
                                                    @foreach($ploCategories as $index =>$plo)
                                                        @if ($plo->plo_category != NULL)
                                                            <!-- Use short name for category if there are more than 3 -->
                                                            @if (($numCatUsed > 3) && ($plo->plos->count() > 0))
                                                                <th colspan='{{ $plosPerCategory[$plo->plo_category_id] }}' style="background-color: rgba(0, 0, 0, 0.03);">C - {{$index + 1}}</th>
                                                            @elseif ($plo->plos->count() > 0)
                                                                <th colspan='{{ $plosPerCategory[$plo->plo_category_id] }}' style="background-color: rgba(0, 0, 0, 0.03);">{{$plo->plo_category}}</th>
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                    <!-- Heading appended at the end, if there are Uncategorized PLOs  -->
                                                    @if($hasUncategorized)
                                                        <th colspan="{{$numUncategorizedPLOS}}" style="background-color: rgba(0, 0, 0, 0.03);">Uncategorized PLOs</th>
                                                    @endif
                                                </tr>

                                                <tr>
                                                    <th colspan='1' style="background-color: rgba(0, 0, 0, 0.03);"></th>
                                                    <!-- If there are less than 7 PLOs, use the short-phrase, else use PLO at index + 1 -->
                                                    @if (count($plos) < 7) 
                                                        <!-- Categorized PLOs -->
                                                        @foreach($ploProgramCategories as $plo)
                                                            @if ($plo->plo_category != NULL)
                                                                <th style="background-color: rgba(0, 0, 0, 0.03);">{{$plo->plo_shortphrase}}</th>
                                                            @endif
                                                        @endforeach
                                                        <!-- Uncategorized PLOs -->
                                                        @foreach($plos as $plo)
                                                            @if ($plo->plo_category == NULL)
                                                                <th style="background-color: rgba(0, 0, 0, 0.03);">{{$plo->plo_shortphrase}}</th>
                                                            @endif
                                                        @endforeach
                                                    @else
                                                        @foreach($plos as $index => $plo)
                                                            <th style="background-color: rgba(0, 0, 0, 0.03);">PLO: {{$index + 1}}</th>
                                                        @endforeach
                                                    @endif
                                                </tr>
                                                <!-- Show all courses associated to the program -->
                                                @foreach($firstYearProgramCourses as $course)
                                                    <tr>
                                                        <th colspan="1" style="background-color: rgba(0, 0, 0, 0.03);">
                                                        {{$course->course_code}} {{$course->course_num}} {{$course->section}}
                                                        <br>
                                                        {{$course->semester}} {{$course->year}}
                                                        </th>
                                                        <!-- Frequency distribution from each course -->
                                                        <!-- For Each Categorized PLO -->
                                                        @foreach($ploProgramCategories as $index => $plo)
                                                            @if ($plo->plo_category != NULL)
                                                            <!-- Check if ['pl_outcome_id']['course_id'] are in the array -->
                                                                @if(isset($storeFirst[$plo->pl_outcome_id][$course->course_id]))
                                                                    <!-- Check if a Tie is present -->
                                                                    @if(isset($storeFirst[$plo->pl_outcome_id][$course->course_id]['map_scale_id_tie']))
                                                                        <td class="text-center align-middle" style="background:repeating-linear-gradient(45deg, transparent, transparent 8px, #ccc 8px, #ccc 16px), linear-gradient( to bottom, #fff, #999);" data-toggle="tooltip" data-html="true" data-bs-placement="right" title="@foreach($storeFirst[$plo->pl_outcome_id][$course->course_id]['frequencies'] as $index => $freq) {{$index}}: {{$freq}}<br> @endforeach">
                                                                            <span style="color: black;">
                                                                                {{$storeFirst[$plo->pl_outcome_id][$course->course_id]['map_scale_abv']}}
                                                                            </span>
                                                                        </td>
                                                                    @else
                                                                        <td class="text-center align-middle" style="background-color: {{ $storeFirst[$plo->pl_outcome_id][$course->course_id]['colour'] }};" data-toggle="tooltip" data-html="true" data-bs-placement="right" title="@foreach($storeFirst[$plo->pl_outcome_id][$course->course_id]['frequencies'] as $index => $freq) {{$index}}: {{$freq}}<br> @endforeach">
                                                                            <span style="color: black;">
                                                                                {{$storeFirst[$plo->pl_outcome_id][$course->course_id]['map_scale_abv']}}
                                                                            </span>
                                                                        </td>
                                                                    @endif

                                                                @else
                                                                    <td class="text-center align-middle" style="background-color: white;">
                                                                        <i class="bi bi-exclamation-circle-fill" data-toggle="tooltip" data-html="true" data-bs-placement="right" title="Incomplete"></i>
                                                                    </td>
                                                                @endif
                                                            @endif
                                                        @endforeach
                                                        <!-- For Each Uncategorized PLO-->
                                                        @foreach($plos as $plo)
                                                            @if ($plo->plo_category == NULL)
                                                                <!-- Check if ['pl_outcome_id']['course_id'] are in the array -->
                                                                @if(isset($storeFirst[$plo->pl_outcome_id][$course->course_id]))
                                                                    <!-- Check if a Tie is present -->
                                                                    @if(isset($storeFirst[$plo->pl_outcome_id][$course->course_id]['map_scale_id_tie']))
                                                                        <td class="text-center align-middle" style="background:repeating-linear-gradient( 45deg, transparent, transparent 10px, #ccc 10px, #ccc 20px), linear-gradient( to bottom, #eee, #999);" data-toggle="tooltip" data-html="true" data-bs-placement="right" title="@foreach($storeFirst[$plo->pl_outcome_id][$course->course_id]['frequencies'] as $index => $freq) {{$index}}: {{$freq}}<br> @endforeach">
                                                                            <span style="color: black;">
                                                                                {{$storeFirst[$plo->pl_outcome_id][$course->course_id]['map_scale_abv']}}
                                                                            </span>
                                                                        </td>
                                                                    @else
                                                                        <td class="text-center align-middle" style="background-color: {{ $storeFirst[$plo->pl_outcome_id][$course->course_id]['colour'] }};" data-toggle="tooltip" data-html="true" data-bs-placement="right" title="@foreach($storeFirst[$plo->pl_outcome_id][$course->course_id]['frequencies'] as $index => $freq) {{$index}}: {{$freq}}<br> @endforeach">
                                                                            <span style="color: black;">
                                                                                {{$storeFirst[$plo->pl_outcome_id][$course->course_id]['map_scale_abv']}}
                                                                            </span>
                                                                        </td>
                                                                    @endif

                                                                @else
                                                                    <td class="text-center align-middle" style="background-color: white;">
                                                                        <i class="bi bi-exclamation-circle-fill" data-toggle="tooltip" data-html="true" data-bs-placement="right" title="Incomplete"></i>
                                                                    </td>
                                                                @endif
                                                            @endif
                                                        @endforeach
                                                    </tr>
                                                @endforeach
                                            </table>
                                        @endif
                                    </div>  
                                <!-- end Courses to PLOs frequency Distribution card -->
                                </div>
                                <!--End of tab-pane-->

                                <div class="tab-pane fade" id="nav-second" role="tabpanel" aria-labelledby="nav-second-tab">
                                    <!-- Second Year Courses frequency distribution table -->
                                    <div class="card-body">
                                        <h5 class="card-title">
                                            Curriculum Map
                                        </h5>
                                        @if( count($secondYearProgramCourses) < 1 )
                                            <div class="alert alert-warning wizard">
                                                <i class="bi bi-exclamation-circle-fill pr-2 fs-5"></i>There are no second year courses set for this program yet.                   
                                            </div>
                                        @elseif ($ploCount < 1) 
                                            <div class="alert alert-warning wizard">
                                                <i class="bi bi-exclamation-circle-fill pr-2 fs-5"></i>There are no program learning outcomes for this program.                   
                                            </div>
                                        @else
                                            <p>This chart shows the alignment of courses to program learning outcomes for this program.</p>

                                            <table class="table table-bordered table-sm" style="width: 95%; margin:auto; table-layout: fixed; border: 1px solid white; color: black;">
                                                <tr class="table-primary">
                                                    <th colspan='1' class="w-auto">Courses</th>
                                                    <th class="text-left" colspan='{{ count($plos) }}'>Program-level Learning Outcomes</th>
                                                </tr>
                                                <tr>
                                                    <th colspan='1' style="background-color: rgba(0, 0, 0, 0.03);"></th>
                                                    <!-- Displays Categories -->
                                                    @foreach($ploCategories as $index =>$plo)
                                                        @if ($plo->plo_category != NULL)
                                                            <!-- Use short name for category if there are more than 3 -->
                                                            @if (($numCatUsed > 3) && ($plo->plos->count() > 0))
                                                                <th colspan='{{ $plosPerCategory[$plo->plo_category_id] }}' style="background-color: rgba(0, 0, 0, 0.03);">C - {{$index + 1}}</th>
                                                            @elseif ($plo->plos->count() > 0)
                                                                <th colspan='{{ $plosPerCategory[$plo->plo_category_id] }}' style="background-color: rgba(0, 0, 0, 0.03);">{{$plo->plo_category}}</th>
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                    <!-- Heading appended at the end, if there are Uncategorized PLOs  -->
                                                    @if($hasUncategorized)
                                                        <th colspan="{{$numUncategorizedPLOS}}" style="background-color: rgba(0, 0, 0, 0.03);">Uncategorized PLOs</th>
                                                    @endif
                                                </tr>

                                                <tr>
                                                    <th colspan='1' style="background-color: rgba(0, 0, 0, 0.03);"></th>
                                                    <!-- If there are less than 7 PLOs, use the short-phrase, else use PLO at index + 1 -->
                                                    @if (count($plos) < 7) 
                                                        <!-- Categorized PLOs -->
                                                        @foreach($ploProgramCategories as $plo)
                                                            @if ($plo->plo_category != NULL)
                                                                <th style="background-color: rgba(0, 0, 0, 0.03);">{{$plo->plo_shortphrase}}</th>
                                                            @endif
                                                        @endforeach
                                                        <!-- Uncategorized PLOs -->
                                                        @foreach($plos as $plo)
                                                            @if ($plo->plo_category == NULL)
                                                                <th style="background-color: rgba(0, 0, 0, 0.03);">{{$plo->plo_shortphrase}}</th>
                                                            @endif
                                                        @endforeach
                                                    @else
                                                        @foreach($plos as $index => $plo)
                                                            <th style="background-color: rgba(0, 0, 0, 0.03);">PLO: {{$index + 1}}</th>
                                                        @endforeach
                                                    @endif
                                                </tr>
                                                <!-- Show all courses associated to the program -->
                                                @foreach($secondYearProgramCourses as $course)
                                                    <tr>
                                                        <th colspan="1" style="background-color: rgba(0, 0, 0, 0.03);">
                                                        {{$course->course_code}} {{$course->course_num}} {{$course->section}}
                                                        <br>
                                                        {{$course->semester}} {{$course->year}}
                                                        </th>
                                                        <!-- Frequency distribution from each course -->
                                                        <!-- For Each Categorized PLO -->
                                                        @foreach($ploProgramCategories as $index => $plo)
                                                            @if ($plo->plo_category != NULL)
                                                            <!-- Check if ['pl_outcome_id']['course_id'] are in the array -->
                                                                @if(isset($storeSecond[$plo->pl_outcome_id][$course->course_id]))
                                                                    <!-- Check if a Tie is present -->
                                                                    @if(isset($storeSecond[$plo->pl_outcome_id][$course->course_id]['map_scale_id_tie']))
                                                                        <td class="text-center align-middle" style="background:repeating-linear-gradient(45deg, transparent, transparent 8px, #ccc 8px, #ccc 16px), linear-gradient( to bottom, #fff, #999);" data-toggle="tooltip" data-html="true" data-bs-placement="right" title="@foreach($storeSecond[$plo->pl_outcome_id][$course->course_id]['frequencies'] as $index => $freq) {{$index}}: {{$freq}}<br> @endforeach">
                                                                            <span style="color: black;">
                                                                                {{$storeSecond[$plo->pl_outcome_id][$course->course_id]['map_scale_abv']}}
                                                                            </span>
                                                                        </td>
                                                                    @else
                                                                        <td class="text-center align-middle" style="background-color: {{ $storeSecond[$plo->pl_outcome_id][$course->course_id]['colour'] }};" data-toggle="tooltip" data-html="true" data-bs-placement="right" title="@foreach($storeSecond[$plo->pl_outcome_id][$course->course_id]['frequencies'] as $index => $freq) {{$index}}: {{$freq}}<br> @endforeach">
                                                                            <span style="color: black;">
                                                                                {{$storeSecond[$plo->pl_outcome_id][$course->course_id]['map_scale_abv']}}
                                                                            </span>
                                                                        </td>
                                                                    @endif

                                                                @else
                                                                    <td class="text-center align-middle" style="background-color: white;">
                                                                        <i class="bi bi-exclamation-circle-fill" data-toggle="tooltip" data-html="true" data-bs-placement="right" title="Incomplete"></i>
                                                                    </td>
                                                                @endif
                                                            @endif
                                                        @endforeach
                                                        <!-- For Each Uncategorized PLO-->
                                                        @foreach($plos as $plo)
                                                            @if ($plo->plo_category == NULL)
                                                                <!-- Check if ['pl_outcome_id']['course_id'] are in the array -->
                                                                @if(isset($storeSecond[$plo->pl_outcome_id][$course->course_id]))
                                                                    <!-- Check if a Tie is present -->
                                                                    @if(isset($storeSecond[$plo->pl_outcome_id][$course->course_id]['map_scale_id_tie']))
                                                                        <td class="text-center align-middle" style="background:repeating-linear-gradient( 45deg, transparent, transparent 10px, #ccc 10px, #ccc 20px), linear-gradient( to bottom, #eee, #999);" data-toggle="tooltip" data-html="true" data-bs-placement="right" title="@foreach($storeSecond[$plo->pl_outcome_id][$course->course_id]['frequencies'] as $index => $freq) {{$index}}: {{$freq}}<br> @endforeach">
                                                                            <span style="color: black;">
                                                                                {{$storeSecond[$plo->pl_outcome_id][$course->course_id]['map_scale_abv']}}
                                                                            </span>
                                                                        </td>
                                                                    @else
                                                                        <td class="text-center align-middle" style="background-color: {{ $storeSecond[$plo->pl_outcome_id][$course->course_id]['colour'] }};" data-toggle="tooltip" data-html="true" data-bs-placement="right" title="@foreach($storeSecond[$plo->pl_outcome_id][$course->course_id]['frequencies'] as $index => $freq) {{$index}}: {{$freq}}<br> @endforeach">
                                                                            <span style="color: black;">
                                                                                {{$storeSecond[$plo->pl_outcome_id][$course->course_id]['map_scale_abv']}}
                                                                            </span>
                                                                        </td>
                                                                    @endif

                                                                @else
                                                                    <td class="text-center align-middle" style="background-color: white;">
                                                                        <i class="bi bi-exclamation-circle-fill" data-toggle="tooltip" data-html="true" data-bs-placement="right" title="Incomplete"></i>
                                                                    </td>
                                                                @endif
                                                            @endif
                                                        @endforeach
                                                    </tr>
                                                @endforeach
                                            </table>
                                        @endif
                                    </div>  
                                <!-- end Courses to PLOs frequency Distribution card -->
                                </div>
                                <!--End of tab-pane-->

                                <div class="tab-pane fade" id="nav-third" role="tabpanel" aria-labelledby="nav-third-tab">
                                    <!-- Third Year Courses frequency distribution table -->
                                    <div class="card-body">
                                        <h5 class="card-title">
                                            Curriculum Map
                                        </h5>
                                        @if( count($thirdYearProgramCourses) < 1 )
                                            <div class="alert alert-warning wizard">
                                                <i class="bi bi-exclamation-circle-fill pr-2 fs-5"></i>There are no third year courses set for this program yet.                   
                                            </div>
                                        @elseif ($ploCount < 1) 
                                            <div class="alert alert-warning wizard">
                                                <i class="bi bi-exclamation-circle-fill pr-2 fs-5"></i>There are no program learning outcomes for this program.                   
                                            </div>
                                        @else
                                            <p>This chart shows the alignment of courses to program learning outcomes for this program.</p>

                                            <table class="table table-bordered table-sm" style="width: 95%; margin:auto; table-layout: fixed; border: 1px solid white; color: black;">
                                                <tr class="table-primary">
                                                    <th colspan='1' class="w-auto">Courses</th>
                                                    <th class="text-left" colspan='{{ count($plos) }}'>Program-level Learning Outcomes</th>
                                                </tr>
                                                <tr>
                                                    <th colspan='1' style="background-color: rgba(0, 0, 0, 0.03);"></th>
                                                    <!-- Displays Categories -->
                                                    @foreach($ploCategories as $index =>$plo)
                                                        @if ($plo->plo_category != NULL)
                                                            <!-- Use short name for category if there are more than 3 -->
                                                            @if (($numCatUsed > 3) && ($plo->plos->count() > 0))
                                                                <th colspan='{{ $plosPerCategory[$plo->plo_category_id] }}' style="background-color: rgba(0, 0, 0, 0.03);">C - {{$index + 1}}</th>
                                                            @elseif ($plo->plos->count() > 0)
                                                                <th colspan='{{ $plosPerCategory[$plo->plo_category_id] }}' style="background-color: rgba(0, 0, 0, 0.03);">{{$plo->plo_category}}</th>
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                    <!-- Heading appended at the end, if there are Uncategorized PLOs  -->
                                                    @if($hasUncategorized)
                                                        <th colspan="{{$numUncategorizedPLOS}}" style="background-color: rgba(0, 0, 0, 0.03);">Uncategorized PLOs</th>
                                                    @endif
                                                </tr>

                                                <tr>
                                                    <th colspan='1' style="background-color: rgba(0, 0, 0, 0.03);"></th>
                                                    <!-- If there are less than 7 PLOs, use the short-phrase, else use PLO at index + 1 -->
                                                    @if (count($plos) < 7) 
                                                        <!-- Categorized PLOs -->
                                                        @foreach($ploProgramCategories as $plo)
                                                            @if ($plo->plo_category != NULL)
                                                                <th style="background-color: rgba(0, 0, 0, 0.03);">{{$plo->plo_shortphrase}}</th>
                                                            @endif
                                                        @endforeach
                                                        <!-- Uncategorized PLOs -->
                                                        @foreach($plos as $plo)
                                                            @if ($plo->plo_category == NULL)
                                                                <th style="background-color: rgba(0, 0, 0, 0.03);">{{$plo->plo_shortphrase}}</th>
                                                            @endif
                                                        @endforeach
                                                    @else
                                                        @foreach($plos as $index => $plo)
                                                            <th style="background-color: rgba(0, 0, 0, 0.03);">PLO: {{$index + 1}}</th>
                                                        @endforeach
                                                    @endif
                                                </tr>
                                                <!-- Show all courses associated to the program -->
                                                @foreach($thirdYearProgramCourses as $course)
                                                    <tr>
                                                        <th colspan="1" style="background-color: rgba(0, 0, 0, 0.03);">
                                                        {{$course->course_code}} {{$course->course_num}} {{$course->section}}
                                                        <br>
                                                        {{$course->semester}} {{$course->year}}
                                                        </th>
                                                        <!-- Frequency distribution from each course -->
                                                        <!-- For Each Categorized PLO -->
                                                        @foreach($ploProgramCategories as $index => $plo)
                                                            @if ($plo->plo_category != NULL)
                                                            <!-- Check if ['pl_outcome_id']['course_id'] are in the array -->
                                                                @if(isset($storeThird[$plo->pl_outcome_id][$course->course_id]))
                                                                    <!-- Check if a Tie is present -->
                                                                    @if(isset($storeThird[$plo->pl_outcome_id][$course->course_id]['map_scale_id_tie']))
                                                                        <td class="text-center align-middle" style="background:repeating-linear-gradient(45deg, transparent, transparent 8px, #ccc 8px, #ccc 16px), linear-gradient( to bottom, #fff, #999);" data-toggle="tooltip" data-html="true" data-bs-placement="right" title="@foreach($storeThird[$plo->pl_outcome_id][$course->course_id]['frequencies'] as $index => $freq) {{$index}}: {{$freq}}<br> @endforeach">
                                                                            <span style="color: black;">
                                                                                {{$storeThird[$plo->pl_outcome_id][$course->course_id]['map_scale_abv']}}
                                                                            </span>
                                                                        </td>
                                                                    @else
                                                                        <td class="text-center align-middle" style="background-color: {{ $storeThird[$plo->pl_outcome_id][$course->course_id]['colour'] }};" data-toggle="tooltip" data-html="true" data-bs-placement="right" title="@foreach($storeThird[$plo->pl_outcome_id][$course->course_id]['frequencies'] as $index => $freq) {{$index}}: {{$freq}}<br> @endforeach">
                                                                            <span style="color: black;">
                                                                                {{$storeThird[$plo->pl_outcome_id][$course->course_id]['map_scale_abv']}}
                                                                            </span>
                                                                        </td>
                                                                    @endif

                                                                @else
                                                                    <td class="text-center align-middle" style="background-color: white;">
                                                                        <i class="bi bi-exclamation-circle-fill" data-toggle="tooltip" data-html="true" data-bs-placement="right" title="Incomplete"></i>
                                                                    </td>
                                                                @endif
                                                            @endif
                                                        @endforeach
                                                        <!-- For Each Uncategorized PLO-->
                                                        @foreach($plos as $plo)
                                                            @if ($plo->plo_category == NULL)
                                                                <!-- Check if ['pl_outcome_id']['course_id'] are in the array -->
                                                                @if(isset($storeThird[$plo->pl_outcome_id][$course->course_id]))
                                                                    <!-- Check if a Tie is present -->
                                                                    @if(isset($storeThird[$plo->pl_outcome_id][$course->course_id]['map_scale_id_tie']))
                                                                        <td class="text-center align-middle" style="background:repeating-linear-gradient( 45deg, transparent, transparent 10px, #ccc 10px, #ccc 20px), linear-gradient( to bottom, #eee, #999);" data-toggle="tooltip" data-html="true" data-bs-placement="right" title="@foreach($storeThird[$plo->pl_outcome_id][$course->course_id]['frequencies'] as $index => $freq) {{$index}}: {{$freq}}<br> @endforeach">
                                                                            <span style="color: black;">
                                                                                {{$storeThird[$plo->pl_outcome_id][$course->course_id]['map_scale_abv']}}
                                                                            </span>
                                                                        </td>
                                                                    @else
                                                                        <td class="text-center align-middle" style="background-color: {{ $storeThird[$plo->pl_outcome_id][$course->course_id]['colour'] }};" data-toggle="tooltip" data-html="true" data-bs-placement="right" title="@foreach($storeThird[$plo->pl_outcome_id][$course->course_id]['frequencies'] as $index => $freq) {{$index}}: {{$freq}}<br> @endforeach">
                                                                            <span style="color: black;">
                                                                                {{$storeThird[$plo->pl_outcome_id][$course->course_id]['map_scale_abv']}}
                                                                            </span>
                                                                        </td>
                                                                    @endif

                                                                @else
                                                                    <td class="text-center align-middle" style="background-color: white;">
                                                                        <i class="bi bi-exclamation-circle-fill" data-toggle="tooltip" data-html="true" data-bs-placement="right" title="Incomplete"></i>
                                                                    </td>
                                                                @endif
                                                            @endif
                                                        @endforeach
                                                    </tr>
                                                @endforeach
                                            </table>
                                        @endif
                                    </div>  
                                <!-- end Courses to PLOs frequency Distribution card -->
                                </div>
                                <!--End of tab-pane-->

                                <div class="tab-pane fade" id="nav-fourth" role="tabpanel" aria-labelledby="nav-fourth-tab">
                                    <!-- Fourth Year Courses frequency distribution table -->
                                    <div class="card-body">
                                        <h5 class="card-title">
                                            Curriculum Map
                                        </h5>
                                        @if( count($fourthYearProgramCourses) < 1 )
                                            <div class="alert alert-warning wizard">
                                                <i class="bi bi-exclamation-circle-fill pr-2 fs-5"></i>There are no fourth year courses set for this program yet.                   
                                            </div>
                                        @elseif ($ploCount < 1) 
                                            <div class="alert alert-warning wizard">
                                                <i class="bi bi-exclamation-circle-fill pr-2 fs-5"></i>There are no program learning outcomes for this program.                   
                                            </div>
                                        @else
                                            <p>This chart shows the alignment of courses to program learning outcomes for this program.</p>

                                            <table class="table table-bordered table-sm" style="width: 95%; margin:auto; table-layout: fixed; border: 1px solid white; color: black;">
                                                <tr class="table-primary">
                                                    <th colspan='1' class="w-auto">Courses</th>
                                                    <th class="text-left" colspan='{{ count($plos) }}'>Program-level Learning Outcomes</th>
                                                </tr>
                                                <tr>
                                                    <th colspan='1' style="background-color: rgba(0, 0, 0, 0.03);"></th>
                                                    <!-- Displays Categories -->
                                                    @foreach($ploCategories as $index =>$plo)
                                                        @if ($plo->plo_category != NULL)
                                                            <!-- Use short name for category if there are more than 3 -->
                                                            @if (($numCatUsed > 3) && ($plo->plos->count() > 0))
                                                                <th colspan='{{ $plosPerCategory[$plo->plo_category_id] }}' style="background-color: rgba(0, 0, 0, 0.03);">C - {{$index + 1}}</th>
                                                            @elseif ($plo->plos->count() > 0)
                                                                <th colspan='{{ $plosPerCategory[$plo->plo_category_id] }}' style="background-color: rgba(0, 0, 0, 0.03);">{{$plo->plo_category}}</th>
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                    <!-- Heading appended at the end, if there are Uncategorized PLOs  -->
                                                    @if($hasUncategorized)
                                                        <th colspan="{{$numUncategorizedPLOS}}" style="background-color: rgba(0, 0, 0, 0.03);">Uncategorized PLOs</th>
                                                    @endif
                                                </tr>

                                                <tr>
                                                    <th colspan='1' style="background-color: rgba(0, 0, 0, 0.03);"></th>
                                                    <!-- If there are less than 7 PLOs, use the short-phrase, else use PLO at index + 1 -->
                                                    @if (count($plos) < 7) 
                                                        <!-- Categorized PLOs -->
                                                        @foreach($ploProgramCategories as $plo)
                                                            @if ($plo->plo_category != NULL)
                                                                <th style="background-color: rgba(0, 0, 0, 0.03);">{{$plo->plo_shortphrase}}</th>
                                                            @endif
                                                        @endforeach
                                                        <!-- Uncategorized PLOs -->
                                                        @foreach($plos as $plo)
                                                            @if ($plo->plo_category == NULL)
                                                                <th style="background-color: rgba(0, 0, 0, 0.03);">{{$plo->plo_shortphrase}}</th>
                                                            @endif
                                                        @endforeach
                                                    @else
                                                        @foreach($plos as $index => $plo)
                                                            <th style="background-color: rgba(0, 0, 0, 0.03);">PLO: {{$index + 1}}</th>
                                                        @endforeach
                                                    @endif
                                                </tr>
                                                <!-- Show all courses associated to the program -->
                                                @foreach($fourthYearProgramCourses as $course)
                                                    <tr>
                                                        <th colspan="1" style="background-color: rgba(0, 0, 0, 0.03);">
                                                        {{$course->course_code}} {{$course->course_num}} {{$course->section}}
                                                        <br>
                                                        {{$course->semester}} {{$course->year}}
                                                        </th>
                                                        <!-- Frequency distribution from each course -->
                                                        <!-- For Each Categorized PLO -->
                                                        @foreach($ploProgramCategories as $index => $plo)
                                                            @if ($plo->plo_category != NULL)
                                                            <!-- Check if ['pl_outcome_id']['course_id'] are in the array -->
                                                                @if(isset($storeFourth[$plo->pl_outcome_id][$course->course_id]))
                                                                    <!-- Check if a Tie is present -->
                                                                    @if(isset($storeFourth[$plo->pl_outcome_id][$course->course_id]['map_scale_id_tie']))
                                                                        <td class="text-center align-middle" style="background:repeating-linear-gradient(45deg, transparent, transparent 8px, #ccc 8px, #ccc 16px), linear-gradient( to bottom, #fff, #999);" data-toggle="tooltip" data-html="true" data-bs-placement="right" title="@foreach($storeFourth[$plo->pl_outcome_id][$course->course_id]['frequencies'] as $index => $freq) {{$index}}: {{$freq}}<br> @endforeach">
                                                                            <span style="color: black;">
                                                                                {{$storeFourth[$plo->pl_outcome_id][$course->course_id]['map_scale_abv']}}
                                                                            </span>
                                                                        </td>
                                                                    @else
                                                                        <td class="text-center align-middle" style="background-color: {{ $storeFourth[$plo->pl_outcome_id][$course->course_id]['colour'] }};" data-toggle="tooltip" data-html="true" data-bs-placement="right" title="@foreach($storeFourth[$plo->pl_outcome_id][$course->course_id]['frequencies'] as $index => $freq) {{$index}}: {{$freq}}<br> @endforeach">
                                                                            <span style="color: black;">
                                                                                {{$storeFourth[$plo->pl_outcome_id][$course->course_id]['map_scale_abv']}}
                                                                            </span>
                                                                        </td>
                                                                    @endif

                                                                @else
                                                                    <td class="text-center align-middle" style="background-color: white;">
                                                                        <i class="bi bi-exclamation-circle-fill" data-toggle="tooltip" data-html="true" data-bs-placement="right" title="Incomplete"></i>
                                                                    </td>
                                                                @endif
                                                            @endif
                                                        @endforeach
                                                        <!-- For Each Uncategorized PLO-->
                                                        @foreach($plos as $plo)
                                                            @if ($plo->plo_category == NULL)
                                                                <!-- Check if ['pl_outcome_id']['course_id'] are in the array -->
                                                                @if(isset($storeFourth[$plo->pl_outcome_id][$course->course_id]))
                                                                    <!-- Check if a Tie is present -->
                                                                    @if(isset($storeFourth[$plo->pl_outcome_id][$course->course_id]['map_scale_id_tie']))
                                                                        <td class="text-center align-middle" style="background:repeating-linear-gradient( 45deg, transparent, transparent 10px, #ccc 10px, #ccc 20px), linear-gradient( to bottom, #eee, #999);" data-toggle="tooltip" data-html="true" data-bs-placement="right" title="@foreach($storeFourth[$plo->pl_outcome_id][$course->course_id]['frequencies'] as $index => $freq) {{$index}}: {{$freq}}<br> @endforeach">
                                                                            <span style="color: black;">
                                                                                {{$storeFourth[$plo->pl_outcome_id][$course->course_id]['map_scale_abv']}}
                                                                            </span>
                                                                        </td>
                                                                    @else
                                                                        <td class="text-center align-middle" style="background-color: {{ $storeFourth[$plo->pl_outcome_id][$course->course_id]['colour'] }};" data-toggle="tooltip" data-html="true" data-bs-placement="right" title="@foreach($storeFourth[$plo->pl_outcome_id][$course->course_id]['frequencies'] as $index => $freq) {{$index}}: {{$freq}}<br> @endforeach">
                                                                            <span style="color: black;">
                                                                                {{$storeFourth[$plo->pl_outcome_id][$course->course_id]['map_scale_abv']}}
                                                                            </span>
                                                                        </td>
                                                                    @endif

                                                                @else
                                                                    <td class="text-center align-middle" style="background-color: white;">
                                                                        <i class="bi bi-exclamation-circle-fill" data-toggle="tooltip" data-html="true" data-bs-placement="right" title="Incomplete"></i>
                                                                    </td>
                                                                @endif
                                                            @endif
                                                        @endforeach
                                                    </tr>
                                                @endforeach
                                            </table>
                                        @endif
                                    </div>  
                                <!-- end Courses to PLOs frequency Distribution card -->
                                </div>
                                <!--End of tab-pane-->

                                <div class="tab-pane fade" id="nav-graduate" role="tabpanel" aria-labelledby="nav-graduate-tab">
                                    <!-- Graduate Courses frequency distribution table -->
                                    <div class="card-body">
                                        <h5 class="card-title">
                                            Curriculum Map
                                        </h5>
                                        @if( count($graduateProgramCourses) < 1 )
                                            <div class="alert alert-warning wizard">
                                                <i class="bi bi-exclamation-circle-fill pr-2 fs-5"></i>There are no graduate courses set for this program yet.                   
                                            </div>
                                        @elseif ($ploCount < 1) 
                                            <div class="alert alert-warning wizard">
                                                <i class="bi bi-exclamation-circle-fill pr-2 fs-5"></i>There are no program learning outcomes for this program.                   
                                            </div>
                                        @else
                                            <p>This chart shows the alignment of courses to program learning outcomes for this program.</p>

                                            <table class="table table-bordered table-sm" style="width: 95%; margin:auto; table-layout: fixed; border: 1px solid white; color: black;">
                                                <tr class="table-primary">
                                                    <th colspan='1' class="w-auto">Courses</th>
                                                    <th class="text-left" colspan='{{ count($plos) }}'>Program-level Learning Outcomes</th>
                                                </tr>
                                                <tr>
                                                    <th colspan='1' style="background-color: rgba(0, 0, 0, 0.03);"></th>
                                                    <!-- Displays Categories -->
                                                    @foreach($ploCategories as $index =>$plo)
                                                        @if ($plo->plo_category != NULL)
                                                            <!-- Use short name for category if there are more than 3 -->
                                                            @if (($numCatUsed > 3) && ($plo->plos->count() > 0))
                                                                <th colspan='{{ $plosPerCategory[$plo->plo_category_id] }}' style="background-color: rgba(0, 0, 0, 0.03);">C - {{$index + 1}}</th>
                                                            @elseif ($plo->plos->count() > 0)
                                                                <th colspan='{{ $plosPerCategory[$plo->plo_category_id] }}' style="background-color: rgba(0, 0, 0, 0.03);">{{$plo->plo_category}}</th>
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                    <!-- Heading appended at the end, if there are Uncategorized PLOs  -->
                                                    @if($hasUncategorized)
                                                        <th colspan="{{$numUncategorizedPLOS}}" style="background-color: rgba(0, 0, 0, 0.03);">Uncategorized PLOs</th>
                                                    @endif
                                                </tr>

                                                <tr>
                                                    <th colspan='1' style="background-color: rgba(0, 0, 0, 0.03);"></th>
                                                    <!-- If there are less than 7 PLOs, use the short-phrase, else use PLO at index + 1 -->
                                                    @if (count($plos) < 7) 
                                                        <!-- Categorized PLOs -->
                                                        @foreach($ploProgramCategories as $plo)
                                                            @if ($plo->plo_category != NULL)
                                                                <th style="background-color: rgba(0, 0, 0, 0.03);">{{$plo->plo_shortphrase}}</th>
                                                            @endif
                                                        @endforeach
                                                        <!-- Uncategorized PLOs -->
                                                        @foreach($plos as $plo)
                                                            @if ($plo->plo_category == NULL)
                                                                <th style="background-color: rgba(0, 0, 0, 0.03);">{{$plo->plo_shortphrase}}</th>
                                                            @endif
                                                        @endforeach
                                                    @else
                                                        @foreach($plos as $index => $plo)
                                                            <th style="background-color: rgba(0, 0, 0, 0.03);">PLO: {{$index + 1}}</th>
                                                        @endforeach
                                                    @endif
                                                </tr>
                                                <!-- Show all courses associated to the program -->
                                                @foreach($graduateProgramCourses as $course)
                                                    <tr>
                                                        <th colspan="1" style="background-color: rgba(0, 0, 0, 0.03);">
                                                        {{$course->course_code}} {{$course->course_num}} {{$course->section}}
                                                        <br>
                                                        {{$course->semester}} {{$course->year}}
                                                        </th>
                                                        <!-- Frequency distribution from each course -->
                                                        <!-- For Each Categorized PLO -->
                                                        @foreach($ploProgramCategories as $index => $plo)
                                                            @if ($plo->plo_category != NULL)
                                                            <!-- Check if ['pl_outcome_id']['course_id'] are in the array -->
                                                                @if(isset($storeGraduate[$plo->pl_outcome_id][$course->course_id]))
                                                                    <!-- Check if a Tie is present -->
                                                                    @if(isset($storeGraduate[$plo->pl_outcome_id][$course->course_id]['map_scale_id_tie']))
                                                                        <td class="text-center align-middle" style="background:repeating-linear-gradient(45deg, transparent, transparent 8px, #ccc 8px, #ccc 16px), linear-gradient( to bottom, #fff, #999);" data-toggle="tooltip" data-html="true" data-bs-placement="right" title="@foreach($storeGraduate[$plo->pl_outcome_id][$course->course_id]['frequencies'] as $index => $freq) {{$index}}: {{$freq}}<br> @endforeach">
                                                                            <span style="color: black;">
                                                                                {{$storeGraduate[$plo->pl_outcome_id][$course->course_id]['map_scale_abv']}}
                                                                            </span>
                                                                        </td>
                                                                    @else
                                                                        <td class="text-center align-middle" style="background-color: {{ $storeGraduate[$plo->pl_outcome_id][$course->course_id]['colour'] }};" data-toggle="tooltip" data-html="true" data-bs-placement="right" title="@foreach($storeGraduate[$plo->pl_outcome_id][$course->course_id]['frequencies'] as $index => $freq) {{$index}}: {{$freq}}<br> @endforeach">
                                                                            <span style="color: black;">
                                                                                {{$storeGraduate[$plo->pl_outcome_id][$course->course_id]['map_scale_abv']}}
                                                                            </span>
                                                                        </td>
                                                                    @endif

                                                                @else
                                                                    <td class="text-center align-middle" style="background-color: white;">
                                                                        <i class="bi bi-exclamation-circle-fill" data-toggle="tooltip" data-html="true" data-bs-placement="right" title="Incomplete"></i>
                                                                    </td>
                                                                @endif
                                                            @endif
                                                        @endforeach
                                                        <!-- For Each Uncategorized PLO-->
                                                        @foreach($plos as $plo)
                                                            @if ($plo->plo_category == NULL)
                                                                <!-- Check if ['pl_outcome_id']['course_id'] are in the array -->
                                                                @if(isset($storeGraduate[$plo->pl_outcome_id][$course->course_id]))
                                                                    <!-- Check if a Tie is present -->
                                                                    @if(isset($storeGraduate[$plo->pl_outcome_id][$course->course_id]['map_scale_id_tie']))
                                                                        <td class="text-center align-middle" style="background:repeating-linear-gradient( 45deg, transparent, transparent 10px, #ccc 10px, #ccc 20px), linear-gradient( to bottom, #eee, #999);" data-toggle="tooltip" data-html="true" data-bs-placement="right" title="@foreach($storeGraduate[$plo->pl_outcome_id][$course->course_id]['frequencies'] as $index => $freq) {{$index}}: {{$freq}}<br> @endforeach">
                                                                            <span style="color: black;">
                                                                                {{$storeGraduate[$plo->pl_outcome_id][$course->course_id]['map_scale_abv']}}
                                                                            </span>
                                                                        </td>
                                                                    @else
                                                                        <td class="text-center align-middle" style="background-color: {{ $storeGraduate[$plo->pl_outcome_id][$course->course_id]['colour'] }};" data-toggle="tooltip" data-html="true" data-bs-placement="right" title="@foreach($storeGraduate[$plo->pl_outcome_id][$course->course_id]['frequencies'] as $index => $freq) {{$index}}: {{$freq}}<br> @endforeach">
                                                                            <span style="color: black;">
                                                                                {{$storeGraduate[$plo->pl_outcome_id][$course->course_id]['map_scale_abv']}}
                                                                            </span>
                                                                        </td>
                                                                    @endif

                                                                @else
                                                                    <td class="text-center align-middle" style="background-color: white;">
                                                                        <i class="bi bi-exclamation-circle-fill" data-toggle="tooltip" data-html="true" data-bs-placement="right" title="Incomplete"></i>
                                                                    </td>
                                                                @endif
                                                            @endif
                                                        @endforeach
                                                    </tr>
                                                @endforeach
                                            </table>
                                        @endif
                                    </div>  
                                <!-- end Courses to PLOs frequency Distribution card -->
                                </div>
                                <!--End of tab-pane-->
                                
                                <!--Begin tab-pane for Required Courses-->
                                <div class="tab-pane fade" id="nav-required" role="tabpanel" aria-labelledby="nav-required-tab">
                                    <!-- REQUIRED COURSES frequency distribution table -->
                                    <div class="card-body">
                                        <h5 class="card-title">
                                            Curriculum Map
                                        </h5>
                                        @if( count($requiredProgramCourses) < 1)
                                            <div class="alert alert-warning wizard">
                                                <i class="bi bi-exclamation-circle-fill pr-2 fs-5"></i>There are no required courses set for this program yet.                   
                                            </div>
                                        @elseif ( $ploCount < 1 ) 
                                            <div class="alert alert-warning wizard">
                                                <i class="bi bi-exclamation-circle-fill pr-2 fs-5"></i>There are no program learning outcomes for this program.                   
                                            </div>
                                        @else
                                            <p>This chart shows the alignment of courses to program learning outcomes for this program.</p>

                                            <table class="table table-bordered table-sm" style="width: 95%; margin:auto; table-layout: fixed; border: 1px solid white; color: black;">
                                                <tr class="table-primary">
                                                    <th colspan='1' class="w-auto">Courses</th>
                                                    <th class="text-left" colspan='{{ count($plos) }}'>Program-level Learning Outcomes</th>
                                                </tr>
                                                <tr>
                                                    <th colspan='1' style="background-color: rgba(0, 0, 0, 0.03);"></th>
                                                    <!-- Displays Categories -->
                                                    @foreach($ploCategories as $index =>$plo)
                                                        @if ($plo->plo_category != NULL)
                                                            <!-- Use short name for category if there are more than 3 -->
                                                            @if (($numCatUsed > 3) && ($plo->plos->count() > 0))
                                                                <th colspan='{{ $plosPerCategory[$plo->plo_category_id] }}' style="background-color: rgba(0, 0, 0, 0.03);">C - {{$index + 1}}</th>
                                                            @elseif ($plo->plos->count() > 0)
                                                                <th colspan='{{ $plosPerCategory[$plo->plo_category_id] }}' style="background-color: rgba(0, 0, 0, 0.03);">{{$plo->plo_category}}</th>
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                    <!-- Heading appended at the end, if there are Uncategorized PLOs  -->
                                                    @if($hasUncategorized)
                                                        <th colspan="{{$numUncategorizedPLOS}}" style="background-color: rgba(0, 0, 0, 0.03);">Uncategorized PLOs</th>
                                                    @endif
                                                </tr>

                                                <tr>
                                                    <th colspan='1' style="background-color: rgba(0, 0, 0, 0.03);"></th>
                                                    <!-- If there are less than 7 PLOs, use the short-phrase, else use PLO at index + 1 -->
                                                    @if (count($plos) < 7) 
                                                        <!-- Categorized PLOs -->
                                                        @foreach($ploProgramCategories as $plo)
                                                            @if ($plo->plo_category != NULL)
                                                                <th style="background-color: rgba(0, 0, 0, 0.03);">{{$plo->plo_shortphrase}}</th>
                                                            @endif
                                                        @endforeach
                                                        <!-- Uncategorized PLOs -->
                                                        @foreach($plos as $plo)
                                                            @if ($plo->plo_category == NULL)
                                                                <th style="background-color: rgba(0, 0, 0, 0.03);">{{$plo->plo_shortphrase}}</th>
                                                            @endif
                                                        @endforeach
                                                    @else
                                                        @foreach($plos as $index => $plo)
                                                            <th style="background-color: rgba(0, 0, 0, 0.03);">PLO: {{$index + 1}}</th>
                                                        @endforeach
                                                    @endif
                                                </tr>
                                                <!-- Show all courses associated to the program -->
                                                @foreach($requiredProgramCourses as $course)
                                                    <tr>
                                                        <th colspan="1" style="background-color: rgba(0, 0, 0, 0.03);">
                                                        {{$course->course_code}} {{$course->course_num}} {{$course->section}}
                                                        <br>
                                                        {{$course->semester}} {{$course->year}}
                                                        </th>
                                                        <!-- Frequency distribution from each course -->
                                                        <!-- For Each Categorized PLO -->
                                                        @foreach($ploProgramCategories as $index => $plo)
                                                            @if ($plo->plo_category != NULL)
                                                            <!-- Check if ['pl_outcome_id']['course_id'] are in the array -->
                                                                @if(isset($storeRequired[$plo->pl_outcome_id][$course->course_id]))
                                                                    <!-- Check if a Tie is present -->
                                                                    @if(isset($storeRequired[$plo->pl_outcome_id][$course->course_id]['map_scale_id_tie']))
                                                                        <td class="text-center align-middle" style="background:repeating-linear-gradient(45deg, transparent, transparent 8px, #ccc 8px, #ccc 16px), linear-gradient( to bottom, #fff, #999);" data-toggle="tooltip" data-html="true" data-bs-placement="right" title="@foreach($storeRequired[$plo->pl_outcome_id][$course->course_id]['frequencies'] as $index => $freq) {{$index}}: {{$freq}}<br> @endforeach">
                                                                            <span style="color: black;">
                                                                                {{$storeRequired[$plo->pl_outcome_id][$course->course_id]['map_scale_abv']}}
                                                                            </span>
                                                                        </td>
                                                                    @else
                                                                        <td class="text-center align-middle" style="background-color: {{ $storeRequired[$plo->pl_outcome_id][$course->course_id]['colour'] }};" data-toggle="tooltip" data-html="true" data-bs-placement="right" title="@foreach($storeRequired[$plo->pl_outcome_id][$course->course_id]['frequencies'] as $index => $freq) {{$index}}: {{$freq}}<br> @endforeach">
                                                                            <span style="color: black;">
                                                                                {{$storeRequired[$plo->pl_outcome_id][$course->course_id]['map_scale_abv']}}
                                                                            </span>
                                                                        </td>
                                                                    @endif

                                                                @else
                                                                    <td class="text-center align-middle" style="background-color: white;">
                                                                        <i class="bi bi-exclamation-circle-fill" data-toggle="tooltip" data-html="true" data-bs-placement="right" title="Incomplete"></i>
                                                                    </td>
                                                                @endif
                                                            @endif
                                                        @endforeach
                                                        <!-- For Each Uncategorized PLO-->
                                                        @foreach($plos as $plo)
                                                            @if ($plo->plo_category == NULL)
                                                                <!-- Check if ['pl_outcome_id']['course_id'] are in the array -->
                                                                @if(isset($storeRequired[$plo->pl_outcome_id][$course->course_id]))
                                                                    <!-- Check if a Tie is present -->
                                                                    @if(isset($storeRequired[$plo->pl_outcome_id][$course->course_id]['map_scale_id_tie']))
                                                                        <td class="text-center align-middle" style="background:repeating-linear-gradient( 45deg, transparent, transparent 10px, #ccc 10px, #ccc 20px), linear-gradient( to bottom, #eee, #999);" data-toggle="tooltip" data-html="true" data-bs-placement="right" title="@foreach($storeRequired[$plo->pl_outcome_id][$course->course_id]['frequencies'] as $index => $freq) {{$index}}: {{$freq}}<br> @endforeach">
                                                                            <span style="color: black;">
                                                                                {{$storeRequired[$plo->pl_outcome_id][$course->course_id]['map_scale_abv']}}
                                                                            </span>
                                                                        </td>
                                                                    @else
                                                                        <td class="text-center align-middle" style="background-color: {{ $storeRequired[$plo->pl_outcome_id][$course->course_id]['colour'] }};" data-toggle="tooltip" data-html="true" data-bs-placement="right" title="@foreach($storeRequired[$plo->pl_outcome_id][$course->course_id]['frequencies'] as $index => $freq) {{$index}}: {{$freq}}<br> @endforeach">
                                                                            <span style="color: black;">
                                                                                {{$storeRequired[$plo->pl_outcome_id][$course->course_id]['map_scale_abv']}}
                                                                            </span>
                                                                        </td>
                                                                    @endif

                                                                @else
                                                                    <td class="text-center align-middle" style="background-color: white;">
                                                                        <i class="bi bi-exclamation-circle-fill" data-toggle="tooltip" data-html="true" data-bs-placement="right" title="Incomplete"></i>
                                                                    </td>
                                                                @endif
                                                            @endif
                                                        @endforeach
                                                    </tr>
                                                @endforeach
                                            </table>
                                        @endif
                                    </div>
                                    <!-- end Courses to PLOs frequency Distribution card -->
                                </div>
                                <!--End tab-pane-->
                            </div>
                            <!--End tab-content-->

                        </div>
                        <!--End card-body-->
                        <div class="card-footer">
                            <div class="card-body mb-4">
                                @if (! $isViewer)
                                    <a href="{{route('programWizard.step3', $program->program_id)}}">
                                        <button class="btn btn-sm btn-primary col-3 float-left"><i class="bi bi-arrow-left mr-2"></i> Courses</button>
                                    </a>
                                @endif
                            </div>
                        </div> 
            </div>
            <!--End card-->
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#notification").toast("show");

        // Enables functionality of tool tips
        $('[data-toggle="tooltip"]').tooltip({html:true});

        $("form").submit(function () {
            // prevent duplicate form submissions
            $(this).find(":submit").attr('disabled', 'disabled');
            $(this).find(":submit").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');
        });
    });
</script>

<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/offline-exporting.js"></script>
<script type="text/javascript">
    // This is required to set the radio button to checked, this is a known firefox bug.
    window.onload=check;
    function check() {
        document.getElementById("Cluster").checked = true;
    }

    var ms = <?php echo json_encode($programMappingScales)?>;
    var colours = <?php echo json_encode($programMappingScalesColours)?>;
    var plosInOrder = <?php echo json_encode($plosInOrder)?>;
    var freq = <?php echo json_encode($freqForMS)?>;
    var series = [];

    series = generateData(ms, colours);

    function generateData(ms, colours) {
        var series = [];

        for (var i = 0; i < ms.length; i++) {
            series.push({
                name: ms[i],
                data: freq[i],
                color: colours[i]
            });
        }
        return series;
    }

    $('#high-chart').highcharts({
        chart: {
            type: 'column'
        },
        title: {
            text: 'Number of Course Outcomes'
        },
        subtitle: {
            text: 'per Program Learning Outcomes'
        },
        xAxis: {
            title: {
                text: 'Program Learning Outcomes'
            },
            categories: plosInOrder
        },
        yAxis: {
            title: {
                text: '# of Outcomes'
            }
        },
        series: series
    });

    function StackedColumn() {
        $('#high-chart').highcharts({
            chart: {
                type: 'column'
            },
            title: {
                text: 'Number of Course Outcomes'
            },
            subtitle: {
                text: 'per Program Learning Outcomes'
            },
            plotOptions: {
                series: {
                    stacking: 'normal'
                }
            },
            xAxis: {
                title: {
                    text: 'Program Learning Outcomes'
                },
                categories: plosInOrder
            },
            yAxis: {
                title: {
                    text: '# of Outcomes'
                }
            },
            series: series
        });
    }

    function ClusterColumn() {
        $('#high-chart').highcharts({
            chart: {
                type: 'column'
            },
            title: {
                text: 'Number of Course Outcomes'
            },
            subtitle: {
                text: 'per Program Learning Outcomes'
            },
            xAxis: {
                title: {
                    text: 'Program Learning Outcomes'
                },
                categories: plosInOrder
            },
            yAxis: {
                title: {
                    text: '# of Outcomes'
                }
            },
            series: series
        });
    }

    $('input[type=radio][name=chart_select]').change(function() {
        if (this.id == 'Cluster'){
            ClusterColumn();
        } else if (this.id == 'Stacked') {
            StackedColumn();
        }
    });

</script>
<style>
    .highcharts-credits {
        display: none;
    }
</style>

<style>

.tooltip-inner {
    text-align: left;
}
th, td {
    border: 1px solid white;
    color: black;
    
}
th {
        text-align: center;
}
.table-primary th, .table-primary td, .table-primary thead th, .table-primary tbody + tbody {
    border-color: white;
}

</style>

@endsection
