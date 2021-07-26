@extends('layouts.app')

@section('content')
<div>
    <div class="row justify-content-center">
        <div class="col-md-12">
            @include('programs.wizard.header')

            <div class="card">
                <h3 class="card-header wizard" >
                    Program Overview
                </h3>

                        
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
                                                <th class="text-left" colspan="2">UnCategorized</th>
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
                                                    <td>
                                                        {{$ms->description}}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @endif
                            </div>
                    
            
                        <!-- frequency distribution table -->
                        <div class="card-body">
                            <h5 class="card-title">
                                Curriculum Map
                            </h5>
                            @if( count($programCourses) < 1 )
                                <div class="alert alert-warning wizard">
                                    <i class="bi bi-exclamation-circle-fill pr-2 fs-5"></i>There are no Courses set for this program yet.                    
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
                                                        <i class="bi bi-exclamation-circle-fill"></i><br>
                                                            Incomplete
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
                                                        <i class="bi bi-exclamation-circle-fill"></i><br>
                                                            Incomplete
                                                        </td>
                                                    @endif
                                                @endif
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </table>

                                <table class="table table-bordered table-sm" style="width: 95%; margin:auto; table-layout: fixed; border: 1px solid white; color: black; table-layout:auto;">
                                    <tr class="table-primary" style="background-color: rgba(0, 0, 0, 0.03);">
                                        <th colspan="2" class="text-left">Legend</th>
                                    </tr>
                                    <tr>
                                        <td>
                                            <span class="mr-5" style="font-weight: bold;">Tie</span>
                                            <div class="float-right" style="background:repeating-linear-gradient(45deg, transparent, transparent 4px, #ccc 4px, #ccc 8px), linear-gradient( to bottom, #fff, #999); height: 50px; width: 50px;"></div>
                                        </td>
                                        <td>
                                            Occurs when two or more CLO's map to a PLO an equal amount of times.
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <span class="mr-5" style="font-weight: bold;">Incomplete</span>
                                            <div class="float-right p-2 text-center" style="background-color:#FFFFFF; height: 60px; border:0.25px solid grey;">
                                            <i class="bi bi-exclamation-circle-fill"></i><br>
                                                Incomplete
                                            </div>
                                        </td>
                                        <td>
                                            Occurs when a course has not yet been mapped to the set of PLO's.
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <span class="mr-5" style="font-weight: bold;">N/A</span><br>
                                            <small>(Not Applicable)</small>
                                            <div class="float-right text-center align-middle p-2" style="background-color:#FFFFFF; height: 50px; width: 50px; border:0.25px solid grey;">N/A</div>
                                        </td>
                                        <td>
                                            Occurs when a course instructor has listed a program learning outcome as being not applicable for a program learning outcome.
                                        </td>
                                    </tr>
                                </table>
                            @endif
                        </div>  
                    </div>
        </div>
    </div>
        <!-- end Courses to PLOs frequency Distribution card -->

            <div class="card-footer">
                <div class="card-body mb-4">
                    <a href="{{route('programWizard.step3', $program->program_id)}}">
                        <button class="btn btn-sm btn-primary col-3 float-left"><i class="bi bi-arrow-left mr-2"></i> Courses</button>
                    </a>
                </div>
            </div> 
    </div>
</div>


<script type="text/javascript">
    $(document).ready(function () {

        // Enables functionality of tool tips
        $('[data-toggle="tooltip"]').tooltip({html:true});

    $("form").submit(function () {
        // prevent duplicate form submissions
        $(this).find(":submit").attr('disabled', 'disabled');
        $(this).find(":submit").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');

    });
    });
</script>

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
