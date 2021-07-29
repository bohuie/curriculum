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
                    Standards and Strategic Priorities
                </h3>

                <div class="card-body">
                    <nav class="mt-2">
                        <div class="nav nav-tabs justify-content-center" id="nav-tab" role="tablist">
                            <button class="nav-link active" id="nav-standards-tab" data-bs-toggle="tab" data-bs-target="#nav-standards" type="button" role="tab" aria-controls="nav-standards" aria-selected="true">Standards</button>
                            <button class="nav-link" id="nav-priorities-tab" data-bs-toggle="tab" data-bs-target="#nav-priorities" type="button" role="tab" aria-controls="nav-priorities" aria-selected="false">Strategic Priorities</button>
                        </div>
                    </nav>
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="nav-standards" role="tabpanel" aria-labelledby="nav-standards-tab">
                            @if (count($course->learningOutcomes) < 1)
                                <div class="alert alert-warning wizard">
                                    <i class="bi bi-exclamation-circle-fill"></i>There are no course learning outcomes set for this course. <a class="alert-link" href="{{route('courseWizard.step1', $course->course_id)}}">Add course learning outcomes.</a>                     
                                </div>

                            @else
                        
                                <h6 class="card-subtitle wizard mb-3 mt-3 lh-lg text-center">
                                    In lieu of specific PLOs, the below are the <a href="https://www2.gov.bc.ca/assets/gov/education/post-secondary-education/institution-resources-administration/degree-authorization/degree-program-criteria.pdf#page=19" target="_blank"><i class="bi bi-box-arrow-up-right"></i> standards provided by the Ministry of Advanced Education in BC</a>. Using the mapping scale provided, identify the alignment between each of the CLOs against the standards.                        
                                </h6>
                            

                                    <!-- Ministry Standards mapping scale -->
                                    <div class="container row">
                                        <div class="col">
                                            @if(count($course->standardScalesCategory->standardScales)>0)
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
                                    
                                    <div class="jumbotron pt-4">
                                        <h4 class="mb-4">Course Learning Outcomes/Competencies</h4>
                                        <h6 class="card-subtitle wizard mb-4 text-primary fw-bold">
                                            Note: Remember to click save once you are done.
                                        </h6>

                                        <!-- list of course learning outcome accordions with mapping form -->
                                        <div class="cloAccordions mb-4">
                                                                    @foreach($course->learningOutcomes as $index => $courseLearningOutcome)
                                                                        <div class="accordion" id="accordionGroup{{$course->program_id}}-{{$courseLearningOutcome->l_outcome_id}}">
                                                                            <div class="accordion-item mb-2">
                                                                                <h2 class="accordion-header" id="header{{$course->program_id}}-{{$courseLearningOutcome->l_outcome_id}}">
                                                                                    <button class="accordion-button white-arrow clo collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{$course->program_id}}-{{$courseLearningOutcome->l_outcome_id}}" aria-expanded="false" aria-controls="collapse{{$course->program_id}}-{{$courseLearningOutcome->l_outcome_id}}">
                                                                                        <b>CLO {{$index+1}} </b>. {{$courseLearningOutcome->clo_shortphrase}}
                                                                                    </button>
                                                                                </h2>

                                                                                <div id="collapse{{$course->program_id}}-{{$courseLearningOutcome->l_outcome_id}}" class="accordion-collapse collapse" aria-labelledby="header{{$course->program_id}}-{{$courseLearningOutcome->l_outcome_id}}" data-bs-parent="#accordionGroup{{$course->program_id}}-{{$courseLearningOutcome->l_outcome_id}}">
                                                                                    <div class="accordion-body">

                                                                                        <form id="{{$course->program_id}}-{{$courseLearningOutcome->l_outcome_id}}" action="{{action('StandardsOutcomeMapController@store')}}" method="POST">
                                                                                            @csrf

                                                                                            <div class="card border-white">
                                                                                                <div class="card-body">
                                                                                                    <h5 style="margin-bottom:16px;text-align:center;font-weight: bold;">{{$courseLearningOutcome->l_outcome}}</h5>

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
                                                                                                                                        <input class="form-check-input position-static" type="radio" name="map[{{$courseLearningOutcome->l_outcome_id}}][{{$standard_outcome->standard_id}}]" value="{{$mappingScaleLevel->standard_scale_id}}" @if(isset($courseLearningOutcome->standardOutcomeMap->find($standard_outcome->standard_id)->pivot)) @if($courseLearningOutcome->standardOutcomeMap->find($standard_outcome->standard_id)->pivot->standard_scale_id == $mappingScaleLevel->standard_scale_id) checked=checked @endif @endif>
                                                                                                                                    </div>
                                                                                                                                </td>
                                                                                                                            @endforeach

                                                                                                                            <td>
                                                                                                                                <div class="form-check">
                                                                                                                                    <input class="form-check-input position-static" type="radio" name="map[{{$courseLearningOutcome->l_outcome_id}}][{{$standard_outcome->standard_id}}]" value="0" @if(isset($courseLearningOutcome->standardOutcomeMap->find($standard_outcome->standard_id)->pivot)) @if($courseLearningOutcome->standardOutcomeMap->find($standard_outcome->standard_id)->pivot->standard_scale_id == 0) checked=checked @endif @endif required>
                                                                                                                                </div>
                                                                                                                            </td>

                                                                                                                        </tr>
                                                                                                                    @endforeach
                                                                                                                </tbody>
                                                                                                            </table>
                                                                                                            <button type="submit" class="btn btn-success my-3 btn-sm float-right col-2" >Save</button>
                                                                                                        @else 
                                                                                                            <div class="alert alert-warning text-center">
                                                                                                                <i class="bi bi-exclamation-circle-fill pr-2 fs-5"></i>Program learning outcomes have not been set for this program                    
                                                                                                            </div>
                                                                                                        @endif
                                                                                                </div>                                                                                                    
                                                                                            </div>
                                                                                        </form>
                                                                                    </div>
                                                                                </div>                                                                            
                                                                            </div>
                                                                        </div>
                                                                
                                                                    @endforeach
                                        </div>
                                    </div>
                            @endif
                        </div>

                        <div class="tab-pane fade" id="nav-priorities" role="tabpanel" aria-labelledby="nav-priorities-tab">
                            <!--Optional Priorities -->
                            <div class="card-body">
                                
                                <h6 class="card-subtitle wizard mb-3 mt-3 lh-lg text-center">
                                    Select, from the below UBC and/or Ministry priorities and strategies, the items that align strongly with your course. This is <b>optional</b>.                                   
                                </h6>

                                <div class="jumbotron pt-4">
                                    <h4 class="mb-4">Alignment with UBC/Ministry Priorities</h4>

                                    <h6 class="card-subtitle wizard mb-4 text-primary fw-bold">
                                        Note: Remember to click save once you are done.
                                    </h6>
                                                                
                                    <form id="optinal" action="{{route('storeOptionalPLOs')}}" method="POST">
                                                                    {{ csrf_field() }}

                                                                    <input type="hidden" name="course_id" value="{{$course->course_id}}">

                                                                    <div class="accordion" id="PrioritiesAccordions">
                                                                        <div class="accordion-item mb-2">
                                                                            <h2 class="accordion-header" id="ministryPrioritiesHeader">
                                                                                <button class="accordion-button white-arrow program collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseMinistryPriorities" aria-expanded="false" aria-controls="collapseMinistryPriorities">
                                                                                    Ministry of Advanced Education and Skills Training
                                                                                </button>
                                                                            </h2>

                                                                            <div id="collapseMinistryPriorities" class="accordion-collapse collapse" aria-labelledby="ministryPrioritiesHeader" data-bs-parent="#PrioritiesAccordions">
                                                                                <div class="accordion-body">
                                                                                    <!-- UBCs mandate by the ministry -->
                                                                                    <h6 class="fw-bold mb-3">UBC's Mandate by the Ministry</h6>
                                                                                    <p>UBC's mandate letter (see <a href="https://www2.gov.bc.ca/gov/content/education-training/post-secondary-education/institution-resources-administration/mandate-letters" target="_blank"><i class="bi bi-box-arrow-up-right"></i> mandate letter here </a>)
                                                                                        calls for the below, as they relate to curriculum:</p>
                                                                                    <table class="table table-hover optionalPLO" id="ubcMandate" data-toolbar="#toolbar" data-toggle="table" data-maintain-meta-data="true">
                                                                                        <thead class="thead-light">
                                                                                            <tr>
                                                                                            <th data-field="state" data-checkbox="true"></th>
                                                                                            <th data-field="Description">Description</th>
                                                                                            </tr>
                                                                                        </thead>
                                                                                        <tbody>
                                                                                            @foreach ($ubc_mandate_letters as $index => $letter)
                                                                                            <tr>
                                                                                            <td>

                                                                                                @if (in_array($letter, $course->optionalPriorities->pluck('custom_PLO')->toArray()))
                                                                                                    <input type="checkbox" name = "optionalItem[]" value="{{$letter}}" checked>
                                                                                                @else
                                                                                                    <input type="checkbox" name = "optionalItem[]" value="{{$letter}}">
                                                                                                @endif

                                                                                            </td>
                                                                                            <td>
                                                                                                {{$letter}}
                                                                                                @if($index == 0)
                                                                                                    <a href="http://trc.ca/assets/pdf/Calls_to_Action_English2.pdf" target="_blank">( <i class="bi bi-box-arrow-up-right"></i> More Information can be found here)</a>
                                                                                                @elseif($index == 1)
                                                                                                    <a href="https://cleanbc.gov.bc.ca/" target="_blank">( <i class="bi bi-box-arrow-up-right"></i> More Information can be found here)</a>
                                                                                                @elseif($index == 6)
                                                                                                    <a href="https://www.workbc.ca/getmedia/18214b5d-b338-4bbd-80bf-b04e48a11386/BC_Labour_Market_Outlook_2019.pdf.aspx" target="_blank">( <i class="bi bi-box-arrow-up-right"></i> More Information can be found here)</a>
                                                                                                @endif
                                                                                            </td>
                                                                                            </tr>
                                                                                            @endforeach
                                                                                        </tbody>
                                                                                    </table>
                                                                                    <!-- BC Labour Market -->
                                                                                    <h6 class="fw-bold mb-3">
                                                                                        BC's Labour Market: Top skills in Demand
                                                                                    </h6>
                                                                                    <p>BC's tops skills in demand,as forecasted to the year 2029 by the <a href="https://www.workbc.ca/getmedia/18214b5d-b338-4bbd-80bf-b04e48a11386/BC_Labour_Market_Outlook_2019.pdf.aspx" target="_blank"><i class="bi bi-box-arrow-up-right"></i> BC Labour Market Outlook (page 46)</a>
                                                                                        , are the following:
                                                                                    </p>
                                                                                    
                                                                                    <table class="table table-hover optionalPLO" id="LabourMarket" data-toolbar="#toolbar" data-toggle="table" data-maintain-meta-data="true">
                                                                                        <thead class="thead-light">
                                                                                            <tr>
                                                                                            <th data-field="state" data-checkbox="true"></th>
                                                                                            <th data-field="Description">Description</th>
                                                                                            </tr>
                                                                                        </thead>
                                                                                        <tbody>
                                                                                            @foreach ($bc_labour_market as $index => $skill)
                                                                                            <tr>
                                                                                                <td>
                                                                                                    @if (in_array($skill,$course->optionalPriorities->pluck('custom_PLO')->toArray()))
                                                                                                        <input type="checkbox" name = "optionalItem[]" value="{{$skill}}" checked>
                                                                                                    @else
                                                                                                        <input type="checkbox" name = "optionalItem[]" value="{{$skill}}">
                                                                                                    @endif
                                                                                                </td>
                                                                                                <td>
                                                                                                    
                                                                                                    {{$skill}}
                                                                                                </td>
                                                                                            </tr>
                                                                                            @endforeach
                                                                                        </tbody>
                                                                                    </table>

                                                                                    <p>Additionally, BC expects <a href="https://www.workbc.ca/Labour-Market-Industry/Jobs-in-Demand/High-Demand-Occupations.aspx" target="_blank"><i class="bi bi-box-arrow-up-right"></i> these occupations to be of "High Opportunity"</a> in the province. Does your course/program align with a High Opportunity Occupation in BC ?
                                                                                    <select id="highOpportunity" class="highOpportunity">
                                                                                        <option value="1">Yes</option>
                                                                                        <option value="0">No</option>
                                                                                    </select>
                                                                                </div>
                                                                            </div>                                                                            
                                                                        </div>

                                                                        <div class="accordion-item mb-2">
                                                                            <h2 class="accordion-header" id="UBCPrioritiesHeader">
                                                                                <button class="accordion-button white-arrow program collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseUBCPriorities" aria-expanded="false" aria-controls="collapseUBCPriorities">
                                                                                    UBC Strategic Priorities
                                                                                </button>
                                                                            </h2>

                                                                            <div id="collapseUBCPriorities" class="accordion-collapse collapse" aria-labelledby="UBCPrioritiesHeader" data-bs-parent="#PrioritiesAccordions">
                                                                                <div class="accordion-body">
                                                                                    <h6 class="fw-bold mt-4 mb-4"><a href="https://strategicplan.ubc.ca/" target="_blank">
                                                                                        <i class="bi bi-box-arrow-up-right"></i> Shaping UBCs next Century</a>
                                                                                    </h6>

                                                                                        <table class="table table-hover optionalPLO" id="ubcStrategy" data-toolbar="#toolbar" data-toggle="table" data-maintain-meta-data="true">
                                                                                            <thead class="thead-light">
                                                                                                <tr>
                                                                                                <th data-field="state" data-checkbox="true"></th>
                                                                                                <th data-field="Description">Description</th>
                                                                                                </tr>
                                                                                            </thead>
                                                                                            <tbody>
                                                                                                @foreach ($shaping_ubc as $index => $strategy)
                                                                                                <tr>
                                                                                                <td>
                                                                                                    @if (in_array($strategy, $course->optionalPriorities->pluck('custom_PLO')->toArray()))
                                                                                                        <input type="checkbox" name= "optionalItem[]" value="{{$strategy}}" checked>
                                                                                                    @else
                                                                                                        <input type="checkbox" name= "optionalItem[]" value="{{$strategy}}">
                                                                                                    @endif
                                                                                                </td>
                                                                                                <td>
                                                                                                    <a href ="{{$shaping_ubc_link[$index]}}" target="_blank">Strategy {{$index + 1}}: </a>
                                                                                                    {{$strategy}}
                                                                                                    @if($index == 17)
                                                                                                        <a href="https://www.alumni.ubc.ca/about/strategic-plan/" target="_blank"><i class="bi bi-box-arrow-up-right"></i> Connecting Forward.</a>
                                                                                                    @endif
                                                                                                </td>
                                                                                                </tr>
                                                                                                @endforeach
                                                                                            </tbody>
                                                                                        </table>

                                                                                        <h6 class="fw-bold mt-4 mb-4">
                                                                                            <a href="https://okmain.cms.ok.ubc.ca/wp-content/uploads/sites/26/2019/02/UBCO-Outlook-2040.pdf" target="_blank"><i class="bi bi-box-arrow-up-right"></i>
                                                                                            UBC Okanagan 2040 Outlook
                                                                                            </a>
                                                                                        </h6>

                                                                                        <table class="table table-hover optionalPLO" id="ubc_2024Outlook" data-toolbar="#toolbar" data-toggle="table" data-maintain-meta-data="true">
                                                                                            <thead class="thead-light">
                                                                                                <tr>
                                                                                                <th data-field="state" data-checkbox="true"></th>
                                                                                                <th data-field="Description">Description</th>
                                                                                                </tr>
                                                                                            </thead>
                                                                                            <tbody>
                                                                                                @foreach ($okanagan_2040_outlook as $index => $outlook)
                                                                                                <tr>
                                                                                                <td>
                                                                                                    @if (in_array($outlook, $course->optionalPriorities->pluck('custom_PLO')->toArray()))
                                                                                                        <input type="checkbox" name= "optionalItem[]" value="{{$outlook}}" checked>
                                                                                                    @else
                                                                                                        <input type="checkbox" name= "optionalItem[]" value="{{$outlook}}">
                                                                                                    @endif
                                                                                                </td>
                                                                                                <td>
                                                                                                    {{$outlook}}
                                                                                                </td>
                                                                                                </tr>
                                                                                                @endforeach
                                                                                            </tbody>
                                                                                        </table>

                                                            <h6 class="fw-bold mt-4 mb-4">
                                                                <a href="https://indigenous.ubc.ca/indigenous-engagement/indigenous-strategic-plan/" target="_blank"><i class="bi bi-box-arrow-up-right"></i>
                                                                    UBC's Indigenous Strategic Plan (2020)
                                                                </a>
                                                            </h6>

                                                            <table class="table table-hover optionalPLO" id="IndigenousPlan" data-toolbar="#toolbar" data-toggle="table" data-maintain-meta-data="true">
                                                                <thead class="thead-light">
                                                                    <tr>
                                                                        <th data-field="state" data-checkbox="true"></th>
                                                                        <th data-field="Description">Description</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach ($ubc_indigenous_plan as $index => $plan)
                                                                    <tr>
                                                                        <td>
                                                                            @if (in_array($plan, $course->optionalPriorities->pluck('custom_PLO')->toArray()))
                                                                                <input type="checkbox" name= "optionalItem[]" value="{{$plan}}" checked>
                                                                            @else
                                                                                <input type="checkbox" name= "optionalItem[]" value="{{$plan}}">
                                                                            @endif
                                                                        </td>
                                                                        <td>
                                                                            {{$plan}}
                                                                        </td>
                                                                    </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>

                                                            <h6 class="fw-bold mt-4 mb-4">
                                                                <a href="https://bog3.sites.olt.ubc.ca/files/2021/01/4_2021.02_Climate-Emergency-Engagement.pdf" target="_blank"><i class="bi bi-box-arrow-up-right"></i> UBC's Climate Priorities</a>
                                                            </h6>
                                                            <p>The <a href="https://bog3.sites.olt.ubc.ca/files/2021/01/4_2021.02_Climate-Emergency-Engagement.pdf" target="_blank"><i class="bi bi-box-arrow-up-right"></i> UBC's Climate Emergency Engagement Report and Recommendations (2021)</a> set out the below curricular examples.
                                                            Programs are encouraged to take these and/or other initiatives that align with the report:
                                                            </p>

                                                            <table class="table table-hover optionalPLO" id="climate_priorities" data-toolbar="#toolbar" data-toggle="table" data-maintain-meta-data="true">
                                                                <thead class="thead-light">
                                                                    <tr>
                                                                        <th data-field="state" data-checkbox="true"></th>
                                                                        <th data-field="Description">Description</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach ($ubc_climate_priorities as $index => $climate)
                                                                    <tr>
                                                                        <td>
                                                                            @if (in_array($climate, $course->optionalPriorities->pluck('custom_PLO')->toArray()))
                                                                                <input type="checkbox" name= "optionalItem[]" value="{{$climate}}" checked>
                                                                            @else
                                                                                <input type="checkbox" name= "optionalItem[]" value="{{$climate}}">
                                                                            @endif
                                                                        </td>
                                                                        <td>
                                                                            {{$climate}}
                                                                        </td>
                                                                    </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>                                               
                                                        </div>
                                                    </div>                                                                            
                                                </div>
                                            </div>

                                        <button type="submit" class="btn btn-success my-3 btn-sm float-right col-2">Save</button>
                                    </form>
                                </div>                            
                            </div>
                            <!-- End of optional priorities -->
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

        // $("form").submit(function (e) {
        //     // prevent duplicate form submissions
        //     e.preventDefault();

        //     var id = $(this).attr('id');

        //     $(this).find(":submit").attr('disabled', 'disabled');
        //     $(this).find(":submit").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');

        //     var form_action = $(this).attr("action");

        //     $.ajax({
        //         data: $(this).serialize(),
        //         url: form_action,
        //         type: "POST",
        //         dataType: 'json',
        //         success: function (data) {
        //             $('form[id='+id+']').find(":submit").removeAttr('disabled');
        //             $('form[id='+id+']').find(":submit").html('Save');


        //             $('form[id='+id+']').find("#alert").html("Your answers have been saved successfully");
        //             $('form[id='+id+']').find("#alert").toggleClass("alert alert-success");
        //             $('form[id='+id+']').find("#alert").delay(2000).slideUp(200, function() {
        //                 $(this).alert('close');
        //             });

        //         },
        //         error: function (data) {
        //             $('form[id='+id+']').find(":submit").removeAttr('disabled');
        //             $('form[id='+id+']').find(":submit").html('Save');


        //             $('form[id='+id+']').find("#alert").html("There was an error saving your answers");
        //             $('form[id='+id+']').find("#alert").toggleClass("alert alert-danger");
        //             $('form[id='+id+']').find("#alert").delay(2000).slideUp(200, function() {
        //                 $(this).alert('close');
        //             });
        //         }
        //     });




        // });
    });

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


@endsection