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

        <div style="display:inline-block;">
            <h3>Program Project: {{$program->program}}</h3>
            <h5 class="text-muted">{{$program->faculty}}</h5>
            <h5 class="text-muted">{{$program->department}}</h5>
            <h5 class="text-muted">{{$program->level}}</h5>
        </div>

        <div class="card">
            <div class="card-header wizard">
                <div class="w-25" style="display: inline-block;"></div>
                <h3 style="margin-bottom:10px; text-align:center;">Program Overview</h3>
            </div>
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
                        <tr class="table-primary" style="background-color: #c6e0f5;">
                            <th class="text-left" colspan="2">Program Learning Outcome</th>
                        </tr>
                        <tbody>
                            <!--Categorized PLOs -->
                            @foreach ($ploCategories as $catIndex => $plo)
                                @if ($plo->plo_category != NULL)
                                    @if ($plo->plos->count() > 0)
                                        <tr class="table-secondary" style="background-color: #d6d8db;">
                                            <th class="text-left" style="font-size:10px;" colspan="2">{{$plo->plo_category}} 
                                            @if ($numCatUsed > 3)    
                                                : (C - {{$catIndex + 1}})</th>
                                            @endif
                                        </tr>
                                    @endif
                                @endif
                                @foreach($ploProgramCategories as $index => $ploCat)
                                    @if ($plo->plo_category_id == $ploCat->plo_category_id)
                                        <tr style="background-color: #fbfcfc;">
                                            <td class="text-center align-middle" style="width:10%; vertical-align:middle; font-size:10px;">{{$index + 1}}</td>
                                            <td style="font-size:10px;">
                                                <span style="font-weight: bold;">{{$ploCat->plo_shortphrase}}</span><br>
                                                {{$ploCat->pl_outcome}}
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            @endforeach
                            <!--UnCategorized PLOs -->
                            @if($hasUncategorized)
                                <tr class="table-secondary" style="background-color: #d6d8db;">
                                    <th class="text-left" style="font-size:10px;" colspan="2">UnCategorized</th>
                                </tr>
                            @endif
                            @foreach($unCategorizedPLOS as $unCatIndex => $unCatplo)
                                <tr style="background-color: #fbfcfc;">
                                    <td class="text-center align-middle" style="width:10%; vertical-align:middle; font-size:10px;">{{count($ploProgramCategories) + $unCatIndex + 1}}</td>
                                    <td style="font-size:10px;">
                                        <span style="font-weight: bold;">{{$unCatplo->plo_shortphrase}}</span><br>
                                        {{$unCatplo->pl_outcome}}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
            <!-- End Program Learning Outcomes -->

            <!-- Mapping Scales -->
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
                        <tr class="table-primary" style="background-color: #c6e0f5;">
                            <th class="text-left" colspan="2">Mapping Scale</th>
                        </tr>
                        <tbody>
                            @foreach($mappingScales as $ms)
                                <tr style="background-color: #fbfcfc;">
                                    <td style="font-size:10px;">
                                        <div style="background-color:{{$ms->colour}}; height: 10px; width: 10px;"></div>
                                        {{$ms->title}}<br>
                                        ({{$ms->abbreviation}})
                                    </td>
                                    <td colspan="1" style="font-size:10px;">
                                        {{$ms->description}}
                                    </td>
                                </tr>
                            @endforeach
                                <!--Legend-->
                                <tr class="table-primary" style="background-color: #c6e0f5;">
                                    <th class="text-left" colspan="2">Additional Denominations</th>
                                </tr>
                                <tr style="background-color: #fbfcfc;">
                                    <td style="background-color:#999; height: 30px; width: 30px;">
                                    </td>
                                    <td style="font-size:10px;">
                                        Occurs when two or more CLOs map to a PLO an equal amount of times.
                                    </td>
                                </tr>
                                <tr style="background-color: #fbfcfc;">
                                    <td class="text-center align-middle" style="font-size:10px; vertical-align:middle;">
                                        Incomplete
                                    </td>
                                    <td style="font-size:10px;">
                                        Occurs when a course has not yet been mapped to the set of PLOs.
                                    </td>
                                </tr>
                                <tr style="background-color: #fbfcfc;">
                                    <td class="text-center align-middle" style="font-size:10px; vertical-align:middle;">
                                        N/A
                                    </td>
                                    <td style="font-size:10px;">
                                        Occurs when a course instructor has listed a program learning outcome as being not applicable for a program learning outcome.
                                    </td>
                                </tr>
                                <!-- End Legend-->
                        </tbody>
                    </table>
                @endif
            </div>
            <!--End Mapping Scales-->

            <!-- Required COURSES frequency distribution table -->
            <div class="card-body">
                <h5 class="card-title">
                    Curriculum Map - Required Courses
                </h5>
                @if( count($requiredProgramCourses) < 1 )
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
                            <th colspan='1' class="w-auto" style="background-color:#c6e0f5;">Courses</th>
                            <th class="text-left" colspan='{{ count($plos) }}' style="background-color:#c6e0f5;">Program-level Learning Outcomes</th>
                        </tr>
                        <tr>
                            <th colspan='1' style="background-color: rgba(0, 0, 0, 0.03);"></th>
                            <!-- Displays Categories -->
                            @foreach($ploCategories as $index =>$plo)
                                @if ($plo->plo_category != NULL)
                                    <!-- Use short name for category if there are more than 3 -->
                                    @if (($numCatUsed > 3) && ($plo->plos->count() > 0))
                                        <th colspan='{{ $plosPerCategory[$plo->plo_category_id] }}' style="font-size:10px; text-align:center; background-color: rgba(0, 0, 0, 0.03);">C - {{$index + 1}}</th>
                                    @elseif ($plo->plos->count() > 0)
                                        <th colspan='{{ $plosPerCategory[$plo->plo_category_id] }}' style="font-size:10px; text-align:center; background-color: rgba(0, 0, 0, 0.03);">{{$plo->plo_category}}</th>
                                    @endif
                                @endif
                            @endforeach
                            <!-- Heading appended at the end, if there are Uncategorized PLOs  -->
                            @if($hasUncategorized)
                                <th colspan="{{$numUncategorizedPLOS}}" style="font-size:10px; text-align:center; background-color: rgba(0, 0, 0, 0.03);">Uncategorized PLOs</th>
                            @endif
                        </tr>
                        <tr>
                            <th colspan='1' style="background-color: rgba(0, 0, 0, 0.03);"></th>
                            <!-- If there are less than 7 PLOs, use the short-phrase, else use PLO at index + 1 -->
                            @if (count($plos) < 7) 
                                <!-- Categorized PLOs -->
                                @foreach($ploProgramCategories as $plo)
                                    @if ($plo->plo_category != NULL)
                                        <th style="font-size:10px; text-align:center; background-color: rgba(0, 0, 0, 0.03);">{{$plo->plo_shortphrase}}</th>
                                    @endif
                                @endforeach
                                <!-- Uncategorized PLOs -->
                                @foreach($plos as $plo)
                                    @if ($plo->plo_category == NULL)
                                        <th style="font-size:10px; text-align:center; background-color: rgba(0, 0, 0, 0.03);">{{$plo->plo_shortphrase}}</th>
                                    @endif
                                @endforeach
                            @else
                                @foreach($plos as $index => $plo)
                                    <th style="font-size:10px; text-align:center; background-color: rgba(0, 0, 0, 0.03);">PLO: {{$index + 1}}</th>
                                @endforeach
                            @endif
                        </tr>
                        <!-- Show all courses associated to the program -->
                        @foreach($requiredProgramCourses as $course)
                            <tr>
                                <th colspan="1" style="font-size:10px; background-color: rgba(0, 0, 0, 0.03);">
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
                                                <td class="text-center align-middle" style="vertical-align:middle; background-color:#999;" data-toggle="tooltip" data-html="true" data-bs-placement="right" title="@foreach($storeRequired[$plo->pl_outcome_id][$course->course_id]['frequencies'] as $index => $freq) {{$index}}: {{$freq}}<br> @endforeach">
                                                    <span style="font-size:10px; color: black;">
                                                        {{$storeRequired[$plo->pl_outcome_id][$course->course_id]['map_scale_abv']}}
                                                    </span>
                                                </td>
                                            @else
                                                <td class="text-center align-middle" style="vertical-align:middle; background-color: {{ $storeRequired[$plo->pl_outcome_id][$course->course_id]['colour'] }};" data-toggle="tooltip" data-html="true" data-bs-placement="right" title="@foreach($storeRequired[$plo->pl_outcome_id][$course->course_id]['frequencies'] as $index => $freq) {{$index}}: {{$freq}}<br> @endforeach">
                                                    <span style="font-size:10px; color: black;">
                                                        {{$storeRequired[$plo->pl_outcome_id][$course->course_id]['map_scale_abv']}}
                                                    </span>
                                                </td>
                                            @endif
                                        @else
                                            <td class="text-center align-middle" style="font-size:10px; vertical-align:middle; background-color: white;">
                                                Incomplete
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
                                                <td class="text-center align-middle" style="vertical-align:middle; background-color:#999;" data-toggle="tooltip" data-html="true" data-bs-placement="right" title="@foreach($storeRequired[$plo->pl_outcome_id][$course->course_id]['frequencies'] as $index => $freq) {{$index}}: {{$freq}}<br> @endforeach">
                                                    <span style="font-size:10px; color: black;">
                                                        {{$storeRequired[$plo->pl_outcome_id][$course->course_id]['map_scale_abv']}}
                                                    </span>
                                                </td>
                                            @else
                                                <td class="text-center align-middle" style="vertical-align:middle; background-color: {{ $storeRequired[$plo->pl_outcome_id][$course->course_id]['colour'] }};" data-toggle="tooltip" data-html="true" data-bs-placement="right" title="@foreach($storeRequired[$plo->pl_outcome_id][$course->course_id]['frequencies'] as $index => $freq) {{$index}}: {{$freq}}<br> @endforeach">
                                                    <span style="font-size:10px; color: black;">
                                                        {{$storeRequired[$plo->pl_outcome_id][$course->course_id]['map_scale_abv']}}
                                                    </span>
                                                </td>
                                            @endif
                                        @else
                                            <td class="text-center align-middle" style="font-size:10px; vertical-align:middle; background-color: white;">
                                                Incomplete
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

            <!-- ALL COURSES frequency distribution table -->
            <div class="card-body">
                <h5 class="card-title">
                    Curriculum Map - All Courses
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
                            <th colspan='1' class="w-auto" style="background-color:#c6e0f5;">Courses</th>
                            <th class="text-left" colspan='{{ count($plos) }}' style="background-color:#c6e0f5;">Program-level Learning Outcomes</th>
                        </tr>
                        <tr>
                            <th colspan='1' style="background-color: rgba(0, 0, 0, 0.03);"></th>
                            <!-- Displays Categories -->
                            @foreach($ploCategories as $index =>$plo)
                                @if ($plo->plo_category != NULL)
                                    <!-- Use short name for category if there are more than 3 -->
                                    @if (($numCatUsed > 3) && ($plo->plos->count() > 0))
                                        <th colspan='{{ $plosPerCategory[$plo->plo_category_id] }}' style="font-size:10px; text-align:center; background-color: rgba(0, 0, 0, 0.03);">C - {{$index + 1}}</th>
                                    @elseif ($plo->plos->count() > 0)
                                        <th colspan='{{ $plosPerCategory[$plo->plo_category_id] }}' style="font-size:10px; text-align:center; background-color: rgba(0, 0, 0, 0.03);">{{$plo->plo_category}}</th>
                                    @endif
                                @endif
                            @endforeach
                            <!-- Heading appended at the end, if there are Uncategorized PLOs  -->
                            @if($hasUncategorized)
                                <th colspan="{{$numUncategorizedPLOS}}" style="font-size:10px; text-align:center; background-color: rgba(0, 0, 0, 0.03);">Uncategorized PLOs</th>
                            @endif
                        </tr>
                        <tr>
                            <th colspan='1' style="background-color: rgba(0, 0, 0, 0.03);"></th>
                            <!-- If there are less than 7 PLOs, use the short-phrase, else use PLO at index + 1 -->
                            @if (count($plos) < 7) 
                                <!-- Categorized PLOs -->
                                @foreach($ploProgramCategories as $plo)
                                    @if ($plo->plo_category != NULL)
                                        <th style="font-size:10px; text-align:center; background-color: rgba(0, 0, 0, 0.03);">{{$plo->plo_shortphrase}}</th>
                                    @endif
                                @endforeach
                                <!-- Uncategorized PLOs -->
                                @foreach($plos as $plo)
                                    @if ($plo->plo_category == NULL)
                                        <th style="font-size:10px; text-align:center; background-color: rgba(0, 0, 0, 0.03);">{{$plo->plo_shortphrase}}</th>
                                    @endif
                                @endforeach
                            @else
                                @foreach($plos as $index => $plo)
                                    <th style="font-size:10px; text-align:center; background-color: rgba(0, 0, 0, 0.03);">PLO: {{$index + 1}}</th>
                                @endforeach
                            @endif
                        </tr>
                        <!-- Show all courses associated to the program -->
                        @foreach($programCourses as $course)
                            <tr>
                                <th colspan="1" style="font-size:10px; background-color: rgba(0, 0, 0, 0.03);">
                                {{$course->course_code}} {{$course->course_num}} {{$course->section}}
                                <br>
                                {{$course->semester}} {{$course->year}}
                                </th>
                                <!-- Frequency distribution from each course -->
                                <!-- For Each Categorized PLO -->
                                @foreach($ploProgramCategories as $index => $plo)
                                    @if ($plo->plo_category != NULL)
                                    <!-- Check if ['pl_outcome_id']['course_id'] are in the array -->
                                        @if(isset($store[$plo->pl_outcome_id][$course->course_id]))
                                            <!-- Check if a Tie is present -->
                                            @if(isset($store[$plo->pl_outcome_id][$course->course_id]['map_scale_id_tie']))
                                                <td class="text-center align-middle" style="vertical-align:middle; background-color:#999;" data-toggle="tooltip" data-html="true" data-bs-placement="right" title="@foreach($store[$plo->pl_outcome_id][$course->course_id]['frequencies'] as $index => $freq) {{$index}}: {{$freq}}<br> @endforeach">
                                                    <span style="font-size:10px; color: black;">
                                                        {{$store[$plo->pl_outcome_id][$course->course_id]['map_scale_abv']}}
                                                    </span>
                                                </td>
                                            @else
                                                <td class="text-center align-middle" style="vertical-align:middle; background-color: {{ $store[$plo->pl_outcome_id][$course->course_id]['colour'] }};" data-toggle="tooltip" data-html="true" data-bs-placement="right" title="@foreach($store[$plo->pl_outcome_id][$course->course_id]['frequencies'] as $index => $freq) {{$index}}: {{$freq}}<br> @endforeach">
                                                    <span style="font-size:10px; color: black;">
                                                        {{$store[$plo->pl_outcome_id][$course->course_id]['map_scale_abv']}}
                                                    </span>
                                                </td>
                                            @endif
                                        @else
                                            <td class="text-center align-middle" style="font-size:10px; vertical-align:middle; background-color: white;">
                                                Incomplete
                                            </td>
                                        @endif
                                    @endif
                                @endforeach
                                <!-- For Each Uncategorized PLO-->
                                @foreach($plos as $plo)
                                    @if ($plo->plo_category == NULL)
                                        <!-- Check if ['pl_outcome_id']['course_id'] are in the array -->
                                        @if(isset($store[$plo->pl_outcome_id][$course->course_id]))
                                            <!-- Check if a Tie is present -->
                                            @if(isset($store[$plo->pl_outcome_id][$course->course_id]['map_scale_id_tie']))
                                                <td class="text-center align-middle" style="vertical-align:middle; background-color:#999;" data-toggle="tooltip" data-html="true" data-bs-placement="right" title="@foreach($store[$plo->pl_outcome_id][$course->course_id]['frequencies'] as $index => $freq) {{$index}}: {{$freq}}<br> @endforeach">
                                                    <span style="font-size:10px; color: black;">
                                                        {{$store[$plo->pl_outcome_id][$course->course_id]['map_scale_abv']}}
                                                    </span>
                                                </td>
                                            @else
                                                <td class="text-center align-middle" style="vertical-align:middle; background-color: {{ $store[$plo->pl_outcome_id][$course->course_id]['colour'] }};" data-toggle="tooltip" data-html="true" data-bs-placement="right" title="@foreach($store[$plo->pl_outcome_id][$course->course_id]['frequencies'] as $index => $freq) {{$index}}: {{$freq}}<br> @endforeach">
                                                    <span style="font-size:10px; color: black;">
                                                        {{$store[$plo->pl_outcome_id][$course->course_id]['map_scale_abv']}}
                                                    </span>
                                                </td>
                                            @endif
                                        @else
                                            <td class="text-center align-middle" style="font-size:10px; vertical-align:middle; background-color: white;">
                                                Incomplete
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

    </body>
</html>