@extends('layouts.app')

@section('content')
<!-- Notification -->
@if ($hasUnMappedCourses)
    <div class="toast-container position-fixed bottom-0 end-0 p-3" id="toastPlacement" style="z-index: 11">
        <div id="notification" class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="false">
            <div class="toast-header bg-warning">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <strong class="me-auto pl-2">Alert</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                There are courses that haven't been fully mapped to this program. To see which courses have not been fully mapped go to the <a href="{{route('programWizard.step3', $program->program_id)}}">previous step</a>.
            </div>
        </div>
    </div>
@endif

<div>
    <div class="row justify-content-center">
        <div class="col-md-12">
            @include('programs.wizard.header')

            <!-- TEST CARD FOR NEW LAYOUT -->
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
                <!-- New Content goes here -->
                    <!-- Buttons  -->
                    <div class="card-body">
                        <nav class="mt-2">
                            <div class="nav nav-tabs justify-content-center" id="nav-tab" role="tablist">
                                <button class="nav-link active w-25" id="nav-plo-tab" data-bs-toggle="tab" data-bs-target="#nav-plo" type="button" role="tab" aria-controls="nav-plo" aria-selected="true">Program Learning Outcomes</button>
                                <button class="nav-link w-25" id="nav-mapping-scale-tab" data-bs-toggle="tab" data-bs-target="#nav-mapping-scale" type="button" role="tab" aria-controls="nav-mapping-scale" aria-selected="false">Mapping Scale</button>
                                <button class="nav-link w-25" id="nav-bar-charts-tab" data-bs-toggle="tab" data-bs-target="#nav-bar-charts" type="button" role="tab" aria-controls="nav-bar-charts" aria-selected="false">Bar Charts</button>
                                <!-- <button class="nav-link w-25" id="getData" href="javascript:;" data-bs-toggle="tab" data-bs-target="#nav-plo-map" type="button" role="tab" aria-controls="nav-plo-map" aria-selected="false">Charts</button> -->
                                <button class="nav-link w-25" id="getData" href="javascript:;" data-bs-toggle="tab" data-bs-target="#nav-charts" type="button" role="tab" aria-controls="nav-charts" aria-selected="false">Frequency Distribution Tables</button>
                            </div>
                        </nav>
                        
                        <div class="tab-content" id="nav-tabContent">
                            
                            <!-- Program Learning Outcome Tab -->
                            <div class="tab-pane fade show active" id="nav-plo" role="tabpanel" aria-labelledby="nav-plo-tab">
                                <div class="card-body">
                                    <!-- <h5 class="card-title">
                                        Program Learning Outcomes
                                    </h5> -->
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
                            </div>
                            <!-- End Program Learning Outcomes Tab -->

                            <!-- Mapping Scale Tab -->
                            <div class="tab-pane fade" id="nav-mapping-scale" role="tabpanel" aria-labelledby="nav-mapping-scale-tab">
                                <div class="card-body">
                                    <!-- <h5 class="card-title">
                                        Mapping Scale
                                    </h5> -->
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
                                            </tbody>
                                        </table>
                                    @endif
                                </div>
                            </div>
                            <!-- End Mapping Scale Tab -->

                            <!-- Bar Charts Tab -->
                            <div class="tab-pane fade" id="nav-bar-charts" role="tabpanel" aria-labelledby="nav-bar-charts-tab">
                                <div class="card-body">
                                    <p>This chart shows how many CLOs (course learning outcomes) are aligned with each of the PLOs (program-level learning outcomes)</p>
                                    <!-- Column Chart -->
                                    <div class="mt-3">
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
                                </div>
                            </div>
                            <!-- End Bar Charts Tab -->

                            <!-- Charts Tab -->
                            <div class="tab-pane fade" id="nav-charts" role="tabpanel" aria-labelledby="nav-charts">
                                <div class="card-body">
                                    <p>These tables use frequency distribution to show the alignment between individual courses and each PLO (program learning outcomes).</p>
                                    <table class="table table-bordered table-sm" style="width: 95%; margin: auto; table-layout:auto;">
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
                                    </table>

                                    <!-- Charts Inner Tabs -->
                                    <nav class="mt-4">
                                        <div class="nav nav-tabs justify-content-center" id="nav-inner-tab" role="tablist">
                                            <!-- Change this id name -->
                                            <button class="nav-link active w-15" id="getData" href="javascript:;" data-bs-toggle="tab" data-bs-target="#nav-all-courses" type="button" role="tab" aria-controls="nav-all-courses" aria-selected="true">All Courses</button>
                                            <button class="nav-link w-15" id="nav-required-tab" href="javascript:;" data-bs-toggle="tab" data-bs-target="#nav-required" type="button" role="tab" aria-controls="nav-required" aria-selected="false">Required Courses</button>
                                            <button class="nav-link w-15" id="nav-non-required-tab" href="javascript:;" data-bs-toggle="tab" data-bs-target="#nav-non-required" type="button" role="tab" aria-controls="nav-none-required" aria-selected="false">Non-Required Courses</button>
                                            <button class="nav-link w-15" id="nav-first-tab" href="javascript:;" data-bs-toggle="tab" data-bs-target="#nav-first" type="button" role="tab" aria-controls="nav-first" aria-selected="false">100 Level Courses</button>
                                            <button class="nav-link w-15" id="nav-second-tab" href="javascript:;" data-bs-toggle="tab" data-bs-target="#nav-second" type="button" role="tab" aria-controls="nav-second" aria-selected="false">200 Level Courses</button>
                                            <button class="nav-link w-15" id="nav-third-tab" href="javascript:;" data-bs-toggle="tab" data-bs-target="#nav-third" type="button" role="tab" aria-controls="nav-third" aria-selected="false">300 Level Courses</button>
                                            <button class="nav-link w-15" id="nav-fourth-tab" href="javascript:;" data-bs-toggle="tab" data-bs-target="#nav-fourth" type="button" role="tab" aria-controls="nav-fourth" aria-selected="false">400 Level Courses</button>
                                            <button class="nav-link w-15" id="nav-graduate-tab" href="javascript:;" data-bs-toggle="tab" data-bs-target="#nav-graduate" type="button" role="tab" aria-controls="nav-graduate" aria-selected="false">500/600 Level Courses</button>
                                        </div>
                                    </nav>

                                    <div class="tab-content" id="nav-tabContent-inner">
                                        
                                        <!-- Tab All Courses -->
                                        <div class="tab-pane fade show active" id="nav-all-courses" role="tabpanel" aria-labelledby="nav-all-courses">
                                            <div id='loading-div'>
                                                <h3 class="text-center">
                                                    Loading ...
                                                </h3>
                                                <div class="loader" style="margin: auto;"></div>
                                            </div>
                                            <div id="allCoursesInput"></div>
                                        </div>
                                        <!-- End Tab All Courses -->

                                        <!-- Tab Required Courses -->
                                        <div class="tab-pane fade" id="nav-required" role="tabpanel" aria-labelledby="nav-required">
                                            <div id='loading-div-required'>
                                                <h3 class="text-center">
                                                    Loading ...
                                                </h3>
                                                <div class="loader" style="margin: auto;"></div>
                                            </div>
                                            <div id="requiredCoursesInput"></div>
                                        </div>
                                        <!-- End Tab Required Courses -->

                                        <!-- Tab Non Required Courses -->
                                        <div class="tab-pane fade" id="nav-non-required" role="tabpanel" aria-labelledby="nav-non-required">
                                            <div id='loading-div-non-required'>
                                                <h3 class="text-center">
                                                    Loading ...
                                                </h3>
                                                <div class="loader" style="margin: auto;"></div>
                                            </div>
                                            <div id="nonRequiredCoursesInput"></div>
                                        </div>
                                        <!-- End Non Tab Courses -->

                                        <!-- Tab 100 Level Courses -->
                                        <div class="tab-pane fade" id="nav-first" role="tabpanel" aria-labelledby="nav-first">
                                            <div id='loading-div-first'>
                                                <h3 class="text-center">
                                                    Loading ...
                                                </h3>
                                                <div class="loader" style="margin: auto;"></div>
                                            </div>
                                            <div id="firstCoursesInput"></div>
                                        </div>
                                        <!-- End 100 Level Courses -->

                                        <!-- Tab 200 Level Courses -->
                                        <div class="tab-pane fade" id="nav-second" role="tabpanel" aria-labelledby="nav-second">
                                            <div id='loading-div-second'>
                                                <h3 class="text-center">
                                                    Loading ...
                                                </h3>
                                                <div class="loader" style="margin: auto;"></div>
                                            </div>
                                            <div id="secondCoursesInput"></div>
                                        </div>
                                        <!-- End 200 Level Required Courses -->

                                        <!-- Tab 300 Level Courses -->
                                        <div class="tab-pane fade" id="nav-third" role="tabpanel" aria-labelledby="nav-third">
                                            <div id='loading-div-third'>
                                                <h3 class="text-center">
                                                    Loading ...
                                                </h3>
                                                <div class="loader" style="margin: auto;"></div>
                                            </div>
                                            <div id="thirdCoursesInput"></div>
                                        </div>
                                        <!-- End 300 Level Courses -->

                                        <!-- Tab 400 Level Courses -->
                                        <div class="tab-pane fade" id="nav-fourth" role="tabpanel" aria-labelledby="nav-fourth">
                                            <div id='loading-div-fourth'>
                                                <h3 class="text-center">
                                                    Loading ...
                                                </h3>
                                                <div class="loader" style="margin: auto;"></div>
                                            </div>
                                            <div id="fourthCoursesInput"></div>
                                        </div>
                                        <!-- End 400 Level Courses -->

                                        <!-- Tab 500/600 Level Courses -->
                                        <div class="tab-pane fade" id="nav-graduate" role="tabpanel" aria-labelledby="nav-graduate">
                                            <div id='loading-div-graduate'>
                                                <h3 class="text-center">
                                                    Loading ...
                                                </h3>
                                                <div class="loader" style="margin: auto;"></div>
                                            </div>
                                            <div id="graduateCoursesInput"></div>
                                        </div>
                                        <!-- End 500/600 Level Courses -->

                                    </div>
                                    <!-- End Charts Inner Tabs -->

                                </div>
                            </div>
                            <!-- End Charts Tab -->

                        </div>
                        
                    </div>
                <!-- End New Content -->
            </div>
            <!-- TEST CARD FOR NEW LAYOUT -->
        </div>
    </div>
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

<script type=text/javascript>
    $(document).ready(function() {

        $("#getData").click(function() { 
            $.ajax({
                type: "GET",
                url: "get-something/",       
                success: function (data) {
                    $("#loading-div").fadeOut("fast");
                    $("#allCoursesInput").html(data);
                    // Enables functionality of tool tips
                    $('[data-toggle="tooltip"]').tooltip({html:true});
                }
            });
        });

        $("#nav-required-tab").click(function() { 
            $.ajax({
                type: "GET",
                url: "get-required/",       
                success: function (data) {
                    $("#loading-div-required").fadeOut("fast");
                    $("#requiredCoursesInput").html(data);
                    // Enables functionality of tool tips
                    $('[data-toggle="tooltip"]').tooltip({html:true});
                }
            });
        });

        $("#nav-non-required-tab").click(function() { 
            $.ajax({
                type: "GET",
                url: "get-non-required/",       
                success: function (data) {
                    $("#loading-div-non-required").fadeOut("fast");
                    $("#nonRequiredCoursesInput").html(data);
                    // Enables functionality of tool tips
                    $('[data-toggle="tooltip"]').tooltip({html:true});
                }
            });
        });

        $("#nav-first-tab").click(function() { 
            $.ajax({
                type: "GET",
                url: "get-first/",       
                success: function (data) {
                    $("#loading-div-first").fadeOut("fast");
                    $("#firstCoursesInput").html(data);
                    // Enables functionality of tool tips
                    $('[data-toggle="tooltip"]').tooltip({html:true});
                }
            });
        });

        $("#nav-second-tab").click(function() { 
            $.ajax({
                type: "GET",
                url: "get-second/",       
                success: function (data) {
                    $("#loading-div-second").fadeOut("fast");
                    $("#secondCoursesInput").html(data);
                    // Enables functionality of tool tips
                    $('[data-toggle="tooltip"]').tooltip({html:true});
                }
            });
        });

        $("#nav-third-tab").click(function() { 
            $.ajax({
                type: "GET",
                url: "get-third/",       
                success: function (data) {
                    $("#loading-div-third").fadeOut("fast");
                    $("#thirdCoursesInput").html(data);
                    // Enables functionality of tool tips
                    $('[data-toggle="tooltip"]').tooltip({html:true});
                }
            });
        });

        $("#nav-fourth-tab").click(function() { 
            $.ajax({
                type: "GET",
                url: "get-fourth/",       
                success: function (data) {
                    $("#loading-div-fourth").fadeOut("fast");
                    $("#fourthCoursesInput").html(data);
                    // Enables functionality of tool tips
                    $('[data-toggle="tooltip"]').tooltip({html:true});
                }
            });
        });

        $("#nav-graduate-tab").click(function() { 
            $.ajax({
                type: "GET",
                url: "get-graduate/",       
                success: function (data) {
                    $("#loading-div-graduate").fadeOut("fast");
                    $("#graduateCoursesInput").html(data);
                    // Enables functionality of tool tips
                    $('[data-toggle="tooltip"]').tooltip({html:true});
                }
            });
        });
    });
</script>

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
            text: 'Number of Course Outcomes per Program Learning Outcomes'
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

.loader {
    border: 6px solid #f3f3f3; /* Light grey */
    border-top: 6px solid #3498db; /* Blue */
    border-radius: 50%;
    width: 40px;
    height: 40px;
    animation: spin 2s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

</style>

@endsection
