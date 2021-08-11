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

                    @if (count($l_outcomes) < 1)
                        <div class="alert alert-warning wizard">
                            <i class="bi bi-exclamation-circle-fill"></i>There are no course learning outcomes set for this course. <a class="alert-link" href="{{route('courseWizard.step1', $course->course_id)}}">Add course learning outcomes.</a>                     
                        </div>

                    @else
                    
                        <h6 class="card-subtitle wizard mb-4 lh-lg ">
                            In lieu of specific PLOs, the below are the <a href="https://www2.gov.bc.ca/assets/gov/education/post-secondary-education/institution-resources-administration/degree-authorization/degree-program-criteria.pdf#page=19" target="_blank"><i class="bi bi-box-arrow-up-right"></i> standards provided by the Ministry of Advanced Education in BC</a>. Using the mapping scale provided, identify the alignment between each of the CLOs against the standards.                        
                        </h6>
                        

                                <!-- Ministry Standards mapping scale -->
                                <div class="container row">
                                    <div class="col">
                                        @if(count($mappingScales)>0)
                                            <table class="table table-bordered table-sm">
                                                <thead>
                                                    <tr>
                                                        <th colspan="2">Mapping Scale</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($mappingScales as $ms)

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
                                
                                <div class="jumbotron">
                                    <h6 class="card-subtitle wizard mb-4 text-primary fw-bold">
                                        Note: Remember to click save once you are done.
                                    </h6>

                                    <!-- list of course learning outcome accordions with mapping form -->
                                    <div class="cloAccordions mb-4">
                                        @foreach($l_outcomes as $index => $courseLearningOutcome)
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
                                                                <input type="hidden" name="program_id" value="{{$course->program_id}}">
                                                                <input type="hidden" name="l_outcome_id" value="{{$courseLearningOutcome->l_outcome_id}}">
                                                                <input type="hidden" name="standard_category_id" value="{{$course->standard_category_id}}">
                                                                <input type="hidden" name="course_id" value="{{$course->course_id}}">


                                                                <div class="card border-white">
                                                                    <div class="card-body">
                                                                        <h5 style="margin-bottom:16px;text-align:center;font-weight: bold;">{{$courseLearningOutcome->l_outcome}}</h5>

                                                                            @if ($standard_outcomes->count() > 0) 

                                                                                <table class="table table-bordered table-sm">
                                                                                    <thead class="thead-light">
                                                                                        <tr class="table-active">
                                                                                            <th>Standards</th>
                                                                                            <!-- Mapping Table Levels -->
                                                                                            @foreach($mappingScales as $mappingScaleLevel)
                                                                                                <th data-toggle="tooltip" title="{{$mappingScaleLevel->title}}: {{$mappingScaleLevel->description}}">
                                                                                                    {{$mappingScaleLevel->abbreviation}}
                                                                                                </th>
                                                                                            @endforeach

                                                                                            <th data-toggle="tooltip" title="Not Aligned">N/A</th>
                                                                                        </tr>

                                                                                    </thead>

                                                                                    <tbody>
                                                                                        @foreach($standard_outcomes as $standard_outcome)
                                                                                            <tr>
                                                                                                <td>
                                                                                                    <b>{{$standard_outcome->s_shortphrase}}</b>
                                                                                                    <br>
                                                                                                    {{$standard_outcome->s_outcome}}
                                                                                                </td>

                                                                                                @foreach($mappingScales as $mappingScaleLevel)
                                                                                                    <td>
                                                                                                        <div class="form-check">
                                                                                                            <input class="form-check-input position-static" type="radio" name="map[{{$courseLearningOutcome->l_outcome_id}}][{{$standard_outcome->standard_id}}]" value="{{$mappingScaleLevel->abbreviation}}" @if(isset($courseLearningOutcome->standardOutcomeMap->find($standard_outcome->standard_id)->pivot)) @if($courseLearningOutcome->standardOutcomeMap->find($standard_outcome->standard_id)->pivot->map_scale_value == $mappingScaleLevel->abbreviation) checked=checked @endif @endif>
                                                                                                        </div>
                                                                                                    </td>
                                                                                                @endforeach

                                                                                                <td>
                                                                                                    <div class="form-check">
                                                                                                        <input class="form-check-input position-static" type="radio" name="map[{{$courseLearningOutcome->l_outcome_id}}][{{$standard_outcome->standard_id}}]" value="N/A" @if(isset($courseLearningOutcome->standardOutcomeMap->find($standard_outcome->standard_id)->pivot)) @if($courseLearningOutcome->standardOutcomeMap->find($standard_outcome->standard_id)->pivot->map_scale_value =='N/A') checked=checked @endif @endif required>
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
                            </div>
                        <div>
                
                    @endif
                
            <!--Optional Priorities Card -->
            <div class="card m-3">
                <h5 class="card-header wizard text-start">
                    Alignment with Ministry and UBC Priorities <b>(Optional)</b>
                </h5>

                <div class="card-body">

                    <h6 class="card-subtitle wizard mb-3 lh-lg ">
                        Select, from the below UBC and/or Ministry priorities and strategies, those that align strongly with your course. This is optional.                        
                    </h6>

                    <div class="jumbotron">
                        <h6 class="card-subtitle wizard mb-4 text-primary fw-bold">
                            Note: Remember to click save once you are done.
                        </h6>
                        
                        <form id="optinal" action="{{route('storeOptionalPLOs')}}" method="POST">
                            {{ csrf_field() }}

                            <input type="hidden" name="course_id" value="{{$course->course_id}}">
                            @foreach ($optional_priorities as $op)
                            <div class="accordion" id="PrioritiesAccordions">
                                <div class="accordion-item mb-2">
                                    <h2 class="accordion-header" id="ministryPrioritiesHeader">
                                        <button class="accordion-button white-arrow program collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseMinistryPriorities{{$op['cat_id']}}" aria-expanded="false" aria-controls="collapseMinistryPriorities">
                                            {!! $op['cat_name'] !!}
                                        </button>
                                    </h2>       
                                    <div id="collapseMinistryPriorities{{$op['cat_id']}}" class="accordion-collapse collapse" aria-labelledby="ministryPrioritiesHeader" data-bs-parent="#PrioritiesAccordions">                                      
                                        <div class="accordion-body">
                                            @foreach ($op['sub'] as $subop) 
                                            <!-- Subcats -->
                                            <h6 class="fw-bold mb-3">{!! $subop['subcat_name'] !!}</h6>
                                            <p>{!! $subop['subcat_desc'] !!}</p>
                                            <table class="table table-hover optionalPLO" id="ubcMandate" data-toolbar="#toolbar" data-toggle="table" data-maintain-meta-data="true">
                                                <thead class="thead-light">
                                                    <tr>
                                                    <th data-field="state" data-checkbox="true"></th>
                                                    <th data-field="Description">Description</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($subop['opp'] as $index => $letter)
                                                    <tr>
                                                    <td>
                                                            <input type="checkbox" name = "optionalItem[]" value="{{$letter['op_id']}}" <?php echo (isset($letter['chk'])) ? "checked" : ""?>>                                                       
                                                    </td>
                                                    <td>
                                                        {!! $letter['optional_priority'] !!}                                                        

                                                    </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>

                                            {!! $subop['subcat_postamble'] !!}
                                            @endforeach

                                        </div>
                                    </div>                                                                            
                                </div>
                            </div>
                            @endforeach
                            <button type="submit" class="btn btn-success my-3 btn-sm float-right col-2">Save</button>
                        </form>
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
