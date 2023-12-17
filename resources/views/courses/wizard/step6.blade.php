@extends('layouts.app')

@section('content')

<link href=" {{ asset('css/accordions.css') }}" rel="stylesheet" type="text/css" >
<!--Link for FontAwesome Font for the arrows for the accordions.-->
<link href="https://use.fontawesome.com/releases/v5.8.2/css/all.css" integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" crossorigin="anonymous" rel="stylesheet" type="text/css" >

<div>
    <div class="row justify-content-center">
        <div class="col-md-12">
            
            @include('courses.wizard.header')
            
            <div class="card">
                <h3 class="card-header wizard" >
                    BC Degree Standards and Strategic Priorities
                    <div style="float: right;">
                            <button id="standardsHelp" style="border: none; background: none; outline: none;" data-bs-toggle="modal" href="#guideModal">
                                <i class="bi bi-question-circle" style="color:#002145;"></i>
                            </button>
                        </div>
                        <div class="text-left">
                            @include('layouts.guide')
                    </div>
                </h3>

                <div class="card-body">
                    <nav class="mt-2">
                        <div class="nav nav-tabs justify-content-center" id="nav-tab" role="tablist">
                            <button class="nav-link active" id="nav-standards-tab" data-bs-toggle="tab" data-bs-target="#nav-standards" type="button" role="tab" aria-controls="nav-standards" aria-selected="true">BC Degree Standards</button>
                            <button class="nav-link" id="nav-priorities-tab" data-bs-toggle="tab" data-bs-target="#nav-priorities" type="button" role="tab" aria-controls="nav-priorities" aria-selected="false">Strategic Priorities</button>
                        </div>
                    </nav>
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="nav-standards" role="tabpanel" aria-labelledby="nav-standards-tab">
                            @if ($course->standard_category_id == 0) 
                                <div class="alert alert-warning wizard">
                                    <i class="bi bi-exclamation-circle-fill"></i>There are no ministry standards for this course to map to.                     
                                </div>
                            @else

                                <div class="alert alert-primary d-flex align-items-center mt-3" role="alert" style="text-align:justify">
                                    <i class="bi bi-info-circle-fill pr-2 fs-3"></i>                        
                                    <div>
                                        In lieu of specific PLOs, the below are the <a class="alert-link" href="https://www2.gov.bc.ca/assets/gov/education/post-secondary-education/institution-resources-administration/degree-authorization/degree-program-criteria.pdf#page=19" target="_blank" rel="noopener noreferrer"><i class="bi bi-box-arrow-up-right"></i> standards provided by the Ministry of Post-Secondary Education and Future Skills</a>. Using the mapping scale provided, identify the alignment between this course against the ministry standards.                        
                                    </div>
                                </div>                            

                                <!-- BC Degree Standards mapping scale -->
                                <div class="container row">
                                    <div class="col">
                                        @if($course->standardScalesCategory->standardScales->count() > 0)
                                            <table class="table table-bordered table-sm">
                                                <thead>
                                                    <tr>
                                                        <th colspan="2">Mapping Scale</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($course->standardScalesCategory->standardScales as $ms)
                                                        <tr>
                                                            <td style="width:20%">
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
                                        @else
                                            <div class="alert alert-warning wizard">
                                                <i class="bi bi-exclamation-circle-fill"></i>There are no mapping scale levels set for this program.                   
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="container row">
                                    <div class="col">
                                        <form action="{{action([\App\Http\Controllers\StandardsOutcomeMapController::class, 'store'])}}" method="POST">
                                            @csrf
                                            <input type="hidden" name="course_id" value="{{$course->course_id}}">
                                            <div class="card border-white">
                                                <div class="card-body">
                                                        @if ($course->standardOutcomes->count() > 0) 
                                                            <table class="table table-bordered table-sm">
                                                                <thead class="thead-light">
                                                                    <tr class="table-active">
                                                                        <th>Standards</th>
                                                                        <!-- Mapping Table Levels -->
                                                                        @foreach($course->standardScalesCategory->standardScales as $mappingScaleLevel)
                                                                            <th data-toggle="tooltip" title="{{$mappingScaleLevel->title}}: {{$mappingScaleLevel->description}}">
                                                                                {{$mappingScaleLevel->abbreviation}}
                                                                            </th>
                                                                        @endforeach
                                                                        <th data-toggle="tooltip" title="Not Aligned">N/A</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach($course->standardOutcomes as $standard_outcome)
                                                                        <tr>
                                                                            <td>
                                                                                <b>{{$standard_outcome->s_shortphrase}}</b>
                                                                                <br>
                                                                                {!! $standard_outcome->s_outcome !!}
                                                                            </td>
                                                                            @foreach($course->standardScalesCategory->standardScales as $mappingScaleLevel)
                                                                                <td>
                                                                                    <div class="form-check">
                                                                                        @if (DB::table('standards_outcome_maps')->where('standard_id', $standard_outcome->standard_id)->where('course_id', $course->course_id)->where('standard_scale_id', $mappingScaleLevel->standard_scale_id)->exists()) 
                                                                                            <input class="form-check-input position-static" type="radio" name="map[{{$course->course_id}}][{{$standard_outcome->standard_id}}]" value="{{$mappingScaleLevel->standard_scale_id}}" checked>
                                                                                        @else
                                                                                        <input class="form-check-input position-static" type="radio" name="map[{{$course->course_id}}][{{$standard_outcome->standard_id}}]" value="{{$mappingScaleLevel->standard_scale_id}}">
                                                                                        @endif
                                                                                    </div>
                                                                                </td>
                                                                            @endforeach
                                                                            <td>
                                                                                <div class="form-check">
                                                                                    @if (DB::table('standards_outcome_maps')->where('standard_id', $standard_outcome->standard_id)->where('course_id', $course->course_id)->where('standard_scale_id', 0)->exists() || (!DB::table('standards_outcome_maps')->where('standard_id', $standard_outcome->standard_id)->where('course_id', $course->course_id)->exists()))
                                                                                        <input class="form-check-input position-static" type="radio" name="map[{{$course->course_id}}][{{$standard_outcome->standard_id}}]" value="0" checked>
                                                                                    @else
                                                                                        <input class="form-check-input position-static" type="radio" name="map[{{$course->course_id}}][{{$standard_outcome->standard_id}}]" value="0">
                                                                                    @endif
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        @else 
                                                            <div class="alert alert-warning text-center">
                                                                <i class="bi bi-exclamation-circle-fill pr-2 fs-5"></i>Program learning outcomes have not been set for this program                    
                                                            </div>
                                                        @endif
                                                    <button type="submit" class="btn btn-success my-3 btn-sm float-right col-2" >Save</button>  
                                                </div>                                                                                           
                                            </div>                                                                         
                                        </form>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="tab-pane fade" id="nav-priorities" role="tabpanel" aria-labelledby="nav-priorities-tab">
                            <!--Optional Priorities -->
                            <div class="card-body">
                                <div class="alert alert-primary d-flex align-items-center" role="alert" style="text-align:justify">
                                    <i class="bi bi-info-circle-fill pr-2 fs-3"></i>                        
                                    <div>
                                        Select, from the below UBC and/or Ministry priorities and strategies, the items that align strongly with your course. This is <b>optional</b>.                                   
                                    </div>
                                </div>                            

                                <div class="jumbotron pt-4">
                                    <h4 class="mb-4">Alignment with UBC/Ministry Priorities</h4>
                                    <h6 class="card-subtitle wizard mb-4 text-primary fw-bold">Note: Remember to click save once you are done.</h6>

                                    <!--Accordions-->
                                    <form id="optinal" action="{{route('storeOptionalPLOs')}}" method="POST">
                                        {{ csrf_field() }}

                                        <input type="hidden" name="course_id" value="{{$course->course_id}}">
                                        <div class="accordion" id="PrioritiesAccordions">
                                            @foreach($optionalPriorityCategories as $optionalPriorityCategory)
                                                <div class="accordion-item mb-2">
                                                    <h2 class="accordion-header" id="ministryPrioritiesHeader">
                                                        <button class="accordion-button white-arrow program collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseMinistryPriorities{{$optionalPriorityCategory->cat_id}}" aria-expanded="false" aria-controls="collapseMinistryPriorities{{$optionalPriorityCategory->cat_id}}">
                                                            {{$optionalPriorityCategory->cat_name}}
                                                        </button>
                                                    </h2>
                                                    <div id="collapseMinistryPriorities{{$optionalPriorityCategory->cat_id}}" class="accordion-collapse collapse" aria-labelledby="ministryPrioritiesHeader" data-bs-parent="#PrioritiesAccordions">
                                                        <div class="accordion-body">
                                                            @foreach ($optionalPriorityCategory->optionalPrioritySubcategories as $optionalPrioritySubcategory)
                                                                <!-- UBC Mandate -->
                                                                @if ($optionalPrioritySubcategory->subcat_id == 1 )
                                                                    <div class="row">
                                                                        <div class="col-10">
                                                                            <!-- <h6 class="fw-bold mb-3">
                                                                                {!! $optionalPrioritySubcategory->subcat_name !!}
                                                                            </h6>
                                                                            <p>{!! $optionalPrioritySubcategory->subcat_desc !!}</p> -->
                                                                        </div>
                                                                        <!-- UBC Mandate Date Filter -->
                                                                        <div class="col">
                                                                            <select id="ubc-mandate" class="form-select col float-right bg-light fs-6" aria-label="UBC Mandate Year">
                                                                                @foreach ($optionalPrioritySubcategory->optionalPriorities->pluck('year')->unique()->sortDesc() as $year)
                                                                                    <option value="{{$year}}-mandate">{{$year}}</option>
                                                                                @endforeach
                                                                            </select>  
                                                                        </div>

                                                                        @foreach ($optionalPrioritySubcategory->optionalPriorities->pluck('year')->unique()->sortDesc() as $year)
                                                                        <div class="collapse mandate show" id="{{$year}}-mandate">
                                                                            <table class="table table-hover optionalPLO" id="{{$optionalPrioritySubcategory->subcat_id}}" data-toolbar="#toolbar" data-toggle="table" data-maintain-meta-data="true">
                                                                                <thead class="thead-light">
                                                                                <tr>
                                                                                    <th data-field="state" data-checkbox="true"></th>
                                                                                    <th data-field="Description">{!! $optionalPrioritySubcategory->subcat_name !!}</th>
                                                                                </tr>
                                                                                @if (($optionalPrioritySubcategory->subcat_desc != NULL) || ($optionalPrioritySubcategory->subcat_desc != ''))
                                                                                    <tr>
                                                                                        <td colspan="2">{!! $optionalPrioritySubcategory->subcat_desc !!}</td>
                                                                                    </tr>
                                                                                @endif
                                                                                </thead>
                                                                                <tbody> 
                                                                                    @foreach ($optionalPrioritySubcategory->optionalPriorities->where('year', $year) as $optionalPriority)
                                                                                        <!-- <tr>
                                                                                            <td>
                                                                                                @if (in_array($optionalPriority->op_id, $opStored))
                                                                                                    <input type="checkbox" name = "optionalItem[]" value="{{$optionalPriority->op_id}}"checked>
                                                                                                @else
                                                                                                    <input type="checkbox" name = "optionalItem[]" value="{{$optionalPriority->op_id}}">
                                                                                                    @endif
                                                                                            </td>
                                                                                            <td>
                                                                                                {!! $optionalPriority->optional_priority !!}
                                                                                            </td>
                                                                                        </tr> -->

                                                                                        <!-- If optional priority has a subdesc then loop through sub description and add description before optional priority -->
                                                                                        @if ($optionalPriority->op_subdesc != NULL)
                                                                                            @foreach ($opSubDesc as $subDesc)
                                                                                                @if ($subDesc->op_subdesc == $optionalPriority->op_subdesc)
                                                                                                    <tr>
                                                                                                        <td></td>
                                                                                                        <td>
                                                                                                            <b>{!! $subDesc->description !!}</b>
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                @endif
                                                                                            @endforeach
                                                                                            <tr>
                                                                                                <td>
                                                                                                    @if (in_array($optionalPriority->op_id, $opStored))
                                                                                                        <input type="checkbox" name = "optionalItem[]" value="{{$optionalPriority->op_id}}"checked>
                                                                                                    @else
                                                                                                        <input type="checkbox" name = "optionalItem[]" value="{{$optionalPriority->op_id}}">
                                                                                                    @endif
                                                                                                </td>
                                                                                                <td>
                                                                                                    {!! $optionalPriority->optional_priority !!}
                                                                                                </td>
                                                                                            </tr>
                                                                                        @else
                                                                                            <tr>
                                                                                                <td>
                                                                                                    @if (in_array($optionalPriority->op_id, $opStored))
                                                                                                        <input type="checkbox" name = "optionalItem[]" value="{{$optionalPriority->op_id}}"checked>
                                                                                                    @else
                                                                                                        <input type="checkbox" name = "optionalItem[]" value="{{$optionalPriority->op_id}}">
                                                                                                    @endif
                                                                                                </td>
                                                                                                <td>
                                                                                                    {!! $optionalPriority->optional_priority !!}
                                                                                                </td>
                                                                                            </tr>
                                                                                        @endif
                                                                                    @endforeach                                                                                
                                                                                </tbody>
                                                                            </table> 
                                                                        </div>
                                                                        @endforeach
                                                                    </div>
                                                                @elseif ($optionalPrioritySubcategory->subcat_id == 2 )
                                                                    <!-- UBC market -->
                                                                        <div class="row">
                                                                            <div class="col-10">
                                                                                <!-- <h6 class="fw-bold mb-3">
                                                                                    {!! $optionalPrioritySubcategory->subcat_name !!}
                                                                                </h6>
                                                                                <p>{!! $optionalPrioritySubcategory->subcat_desc !!}</p> -->
                                                                            </div>
                                                                            <!-- UBC Market Date Filter -->
                                                                            <div class="col">
                                                                                <select id="ubc-market" class="form-select col float-right bg-light fs-6" aria-label="UBC Market Year">
                                                                                    @foreach ($optionalPrioritySubcategory->optionalPriorities->pluck('year')->unique()->sortDesc() as $year)
                                                                                        <option value="{{$year}}-market">{{$year}}</option>
                                                                                    @endforeach
                                                                                </select>  
                                                                            </div>

                                                                        @foreach ($optionalPrioritySubcategory->optionalPriorities->pluck('year')->unique()->sortDesc() as $year)
                                                                        <div class="collapse market show" id="{{$year}}-market">
                                                                            <table class="table table-hover optionalPLO" id="{{$optionalPrioritySubcategory->subcat_id}}" data-toolbar="#toolbar" data-toggle="table" data-maintain-meta-data="true">
                                                                                <thead class="thead-light">
                                                                                <tr>
                                                                                    <th data-field="state" data-checkbox="true"></th>
                                                                                    <th data-field="Description">{!! $optionalPrioritySubcategory->subcat_name !!}</th>
                                                                                </tr>
                                                                                @if (($optionalPrioritySubcategory->subcat_desc != NULL) || ($optionalPrioritySubcategory->subcat_desc != ''))
                                                                                    <tr>
                                                                                        <td colspan="2">{!! $optionalPrioritySubcategory->subcat_desc !!}</td>
                                                                                    </tr>
                                                                                @endif
                                                                                </thead>
                                                                                <tbody> 
                                                                                    @foreach ($optionalPrioritySubcategory->optionalPriorities->where('year', $year) as $optionalPriority)
                                                                                        <!-- <tr>
                                                                                            <td>
                                                                                                @if (in_array($optionalPriority->op_id, $opStored))
                                                                                                    <input type="checkbox" name = "optionalItem[]" value="{{$optionalPriority->op_id}}"checked>
                                                                                                @else
                                                                                                    <input type="checkbox" name = "optionalItem[]" value="{{$optionalPriority->op_id}}">
                                                                                                    @endif
                                                                                            </td>
                                                                                            <td>
                                                                                                {!! $optionalPriority->optional_priority !!}
                                                                                            </td>
                                                                                        </tr> -->

                                                                                        <!-- If optional priority has a subdesc then loop through sub description and add description before optional priority -->
                                                                                        @if ($optionalPriority->op_subdesc != NULL)
                                                                                            @foreach ($opSubDesc as $subDesc)
                                                                                                @if ($subDesc->op_subdesc == $optionalPriority->op_subdesc)
                                                                                                    <tr>
                                                                                                        <td></td>
                                                                                                        <td>
                                                                                                            <b>{!! $subDesc->description !!}</b>
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                @endif
                                                                                            @endforeach
                                                                                            <tr>
                                                                                                <td>
                                                                                                    @if (in_array($optionalPriority->op_id, $opStored))
                                                                                                        <input type="checkbox" name = "optionalItem[]" value="{{$optionalPriority->op_id}}"checked>
                                                                                                    @else
                                                                                                        <input type="checkbox" name = "optionalItem[]" value="{{$optionalPriority->op_id}}">
                                                                                                    @endif
                                                                                                </td>
                                                                                                <td>
                                                                                                    {!! $optionalPriority->optional_priority !!}
                                                                                                </td>
                                                                                            </tr>
                                                                                        @else
                                                                                            <tr>
                                                                                                <td>
                                                                                                    @if (in_array($optionalPriority->op_id, $opStored))
                                                                                                        <input type="checkbox" name = "optionalItem[]" value="{{$optionalPriority->op_id}}"checked>
                                                                                                    @else
                                                                                                        <input type="checkbox" name = "optionalItem[]" value="{{$optionalPriority->op_id}}">
                                                                                                    @endif
                                                                                                </td>
                                                                                                <td>
                                                                                                    {!! $optionalPriority->optional_priority !!}
                                                                                                </td>
                                                                                            </tr>
                                                                                        @endif
                                                                                    @endforeach                                                                                
                                                                                </tbody>
                                                                            </table> 
                                                                        </div>
                                                                        @endforeach
                                                                    </div>
                                                                <!-- End of UBC market -->
                                                                @else
                                                                    <!--optional Priorities for subcategory-->
                                                                    <table class="table table-hover optionalPLO" id="{{$optionalPrioritySubcategory->subcat_id}}" data-toolbar="#toolbar" data-toggle="table" data-maintain-meta-data="true">
                                                                        <thead class="thead-light">
                                                                            <tr>
                                                                                <th data-field="state" data-checkbox="true"></th>
                                                                                <th data-field="Description">{!! $optionalPrioritySubcategory->subcat_name !!}</th>
                                                                            </tr>
                                                                            @if (($optionalPrioritySubcategory->subcat_desc != NULL) || ($optionalPrioritySubcategory->subcat_desc != ''))
                                                                                <tr>
                                                                                    <td colspan="2">{!! $optionalPrioritySubcategory->subcat_desc !!}</td>
                                                                                </tr>
                                                                            @endif
                                                                        </thead>
                                                                        <tbody>
                                                                            @foreach ($optionalPrioritySubcategory->optionalPriorities as $optionalPriority)
                                                                                <!-- If optional priority has a subdesc then loop through sub description and add description before optional priority -->
                                                                                @if ($optionalPriority->op_subdesc != NULL)
                                                                                    @foreach ($opSubDesc as $subDesc)
                                                                                        @if ($subDesc->op_subdesc == $optionalPriority->op_subdesc)
                                                                                            <tr>
                                                                                                <td></td>
                                                                                                <td>
                                                                                                    <b>{!! $subDesc->description !!}</b>
                                                                                                </td>
                                                                                            </tr>
                                                                                        @endif
                                                                                    @endforeach
                                                                                    <tr>
                                                                                        <td>
                                                                                            @if (in_array($optionalPriority->op_id, $opStored))
                                                                                                <input type="checkbox" name = "optionalItem[]" value="{{$optionalPriority->op_id}}"checked>
                                                                                            @else
                                                                                                <input type="checkbox" name = "optionalItem[]" value="{{$optionalPriority->op_id}}">
                                                                                            @endif
                                                                                        </td>
                                                                                        <td>
                                                                                            {!! $optionalPriority->optional_priority !!}
                                                                                        </td>
                                                                                    </tr>
                                                                                @else
                                                                                    <tr>
                                                                                        <td>
                                                                                            @if (in_array($optionalPriority->op_id, $opStored))
                                                                                                <input type="checkbox" name = "optionalItem[]" value="{{$optionalPriority->op_id}}"checked>
                                                                                            @else
                                                                                                <input type="checkbox" name = "optionalItem[]" value="{{$optionalPriority->op_id}}">
                                                                                            @endif
                                                                                        </td>
                                                                                        <td>
                                                                                            {!! $optionalPriority->optional_priority !!}
                                                                                        </td>
                                                                                    </tr>
                                                                                @endif
                                                                            @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                    <!-- <p>{!! $optionalPrioritySubcategory->subcat_postamble !!}</p> -->
                                                                @endif
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        <button type="submit" class="btn btn-success my-3 btn-sm float-right col-2">Save</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="card-body mb-4">
                        <a href="{{route('courseWizard.step5', $course->course_id)}}">
                            <button class="btn btn-sm btn-primary col-3 float-left"><i class="bi bi-arrow-left mr-2"></i> Program Outcome Mapping</button>
                        </a>
                        <a href="{{route('courseWizard.step7', $course->course_id)}}">
                            <button class="btn btn-sm btn-primary col-3 float-right">Course Summary <i class="bi bi-arrow-right ml-2"></i></button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function () {

        var mandateCollapseList = $('.collapse.mandate');
        var marketCollapseList = $('.collapse.market');

        changeMandate($('#ubc-mandate').val(), mandateCollapseList)
        changeMarket($('#ubc-market').val(), marketCollapseList)

        $('[data-toggle="tooltip"]').tooltip();

        $("form").submit(function () {
        // prevent duplicate form submissions
        $(this).find(":submit").attr('disabled', 'disabled');
        $(this).find(":submit").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');
        });

        // Hide and show the optional
        $("#highOpportunity").on('change', function () {
            var value = $("#highOpportunity").val();
            console.log(value);
            if (value == "1" ){
                $('#addedOptions').show();
                $("#addedOptions :input").prop("disabled", false);
            }else{
                $('#addedOptions').hide();
                $("#addedOptions :input").prop("disabled", true);
            }
        });

        $('#btnAdd').click(function() {
            add();
        });

        $('#ubc-mandate').on('change', function (event) {
            // get the value of the select 
            var mandateId = $(this).val();
            changeMandate(mandateId, mandateCollapseList);
            
        })
        $('#ubc-market').on('change', function (event) {
            // get the value of the select 
            var marketId = $(this).val();
            changeMarket(marketId, marketCollapseList);
            
        })
    });

    function changeMandate(mandateId, mandateCollapseList) {
        mandateCollapseList.each(function (index, mandateCollapse) {    
            $(mandateCollapse).removeClass('show');
        });
        $('#' + mandateId).addClass('show');
    }
    function changeMarket(marketId, marketCollapseList) {
        marketCollapseList.each(function (index, marketCollapse) {    
            $(marketCollapse).removeClass('show');
        });
        $('#' + marketId).addClass('show');
    }

    function add() {
        var length = $('#highOpportunityTable tr').length;

        var element = `
            <tr>
                <td>
                    `
                    +length+
                    `
                </td>
                <td>
                    <input class = "form-control" type="text" name="inputItem[]" spellcheck="true" required>
                </td>
            </tr>`;
            var container = $('#highOpportunityTable tbody');
            container.append(element);
    }

</script>

<style>

table, tbody, td, tfoot, th, thead, tr {
    border: none;
}
.table thead th {
    vertical-align: bottom;
    border-bottom: none;
        border-bottom-color: rgb(0, 0, 0);
}

</style>


@endsection