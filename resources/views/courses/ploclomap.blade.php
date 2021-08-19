@extends('layouts.app')

@section('content')

<link href=" {{ asset('css/accordions.css') }}" rel="stylesheet" type="text/css" >
<!--Link for FontAwesome Font for the arrows for the accordions.-->
<link href="https://use.fontawesome.com/releases/v5.8.2/css/all.css" integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" crossorigin="anonymous" rel="stylesheet" type="text/css" >

<div>
    <div class="col">
        <h3>Course: {{$course->year}} {{$course->semester}} {{$course->course_code}}{{$course->course_num}}  {{$course->section}}</h3>
        <h5 class="text-muted">{{$course->course_title}}</h5>
        <h5>Delivery modality:
        @switch($course->delivery_modality)
            @case('O')
                Online
                @break
            @case('B')
                Blended
                @break
            @default
                In-person
        @endswitch
        </h5>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <p class="container form-text text-muted">Now that you have inputted all your course information, you are ready to map it to the program-level learning outcomes (PLOs).
            @if($course->program_id == 1 || $course->program_id == 2 || $course->program_id == 3 )
                In lieu of specific PLOs, the below are the <a href="https://www2.gov.bc.ca/assets/gov/education/post-secondary-education/institution-resources-administration/degree-authorization/degree-program-criteria.pdf#page=19" target="_blank">standards provided by the Ministry of Advanced Education in BC</a>. Using the mapping scale provided, identify the alignment between each of the CLOs against the standards.
            @else
                Using the mapping scale provided, identify the alignment between each of the CLOs against the PLOs.
            @endif
        </p>
        <p class="form-text text-primary container font-weight-bold ">Note: Remember to click save once you are done.</p>
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
                    <table class="table table-bordered table-sm">
                        <tr>
                            <th class="table-light">There are no mapping scale levels set for this program.</th>
                        </tr>
                    </table>
                @endif
            </div>
        </div>
    </div>
    <div class="jumbotron">
        @if(count($l_outcomes)<1)
            <table class="table table-bordered table-sm">
                <tr>
                    <th class="table-light">There are no course learning outcomes set for this course.</th>
                </tr>
            </table>
        @else
            <p class="container form-text font-weight-bold">
                @if($course->program_id == 1 || $course->program_id == 2 || $course->program_id == 3 )
                    Using the mapping scale provided, identify the alignment between each of the CLOs against the standards.
                @else
                    Using the mapping scale provided, identify the alignment between each of the CLOs against the PLOs.
                @endif
            </p>
            <div class="accordions" style="width:100%">
                @for($i = 0; $i < count($l_outcomes); $i++)
                    <div class="accordion" id="accordionGroup{{$l_outcomes[$i]->l_outcome_id}}">
                        <div class="card">
                            <div class="card-header" id="heading{{$l_outcomes[$i]->l_outcome_id}}">
                                <input class="accordion-input" type="checkbox" id="title{{$l_outcomes[$i]->l_outcome_id}}" data-toggle="collapse" data-target="#collapse{{$l_outcomes[$i]->l_outcome_id}}"/>
                                    <label class="accordion-label" for="title{{$l_outcomes[$i]->l_outcome_id}}">
                                        <h5 class="accordion-title">
                                            <b>CLO #{{$i+1}} </b>: {{$l_outcomes[$i]->clo_shortphrase}}
                                        </h5>
                                    </label>
                            </div>
                            <form id="{{$l_outcomes[$i]->l_outcome_id}}" action="{{action('OutcomeMapController@store')}}" method="POST">
                                @csrf
                                <input type="hidden" name="course_id" value="{{$course->course_id}}">
                                <input type="hidden" name="l_outcome_id" value="{{$l_outcomes[$i]->l_outcome_id}}">
                                <div id="collapse{{$l_outcomes[$i]->l_outcome_id}}" class="collapse" aria-labelledby="heading{{$l_outcomes[$i]->l_outcome_id}}" data-parent="#accordionGroup{{$l_outcomes[$i]->l_outcome_id}}">
                                    <div class="card-body">
                                        <h5>{{$l_outcomes[$i]->l_outcome}}</h5>
                                            <table class="table table-bordered table-sm">                                                        
                                                <thead class="thead-light">
                                                <tr class="table-active">
                                                    @if($course->program_id == 1 ?? $course->program_id == 2 ?? $course->program_id == 3 )
                                                        <th>BC Ministry Standards (in lieu of PLOs)</th>
                                                    @else
                                                        <th>Program Learning Outcomes or Competencies</th>
                                                    @endif                                    
                                                    @foreach($mappingScales as $ms)
                                                    <th data-toggle="tooltip" title="{{$ms->title}}: {{$ms->description}}">
                                                        {{$ms->abbreviation}}
                                                    </th>
                                                    @endforeach
                                                    <th data-toggle="tooltip" title="Not Aligned">N/A</th>
                                                </tr>
                                                </thead>
                                                    <tbody>
                                                        @foreach($pl_outcomes as $pl_outcome)
                                                            <tr>
                                                                <td>
                                                                    <b>{{$pl_outcome->plo_shortphrase}}</b><br>
                                                                        {{$pl_outcome->pl_outcome}}
                                                                </td>
                                                                @foreach($mappingScales as $ms)
                                                                    <td>
                                                                        <div class="form-check">
                                                                            <input class="form-check-input position-static" type="radio" name="map[{{$l_outcomes[$i]->l_outcome_id}}][{{$pl_outcome->pl_outcome_id}}]" value="{{$ms->abbreviation}}"
                                                                            @if(isset($l_outcomes[$i]->programLearningOutcomes->find($pl_outcome->pl_outcome_id)->pivot))
                                                                            @if($l_outcomes[$i]->programLearningOutcomes->find($pl_outcome->pl_outcome_id)->pivot->map_scale_value == $ms->abbreviation) checked=checked @endif @endif>
                                                                        </div>
                                                                    </td>
                                                                @endforeach
                                                                <td>
                                                                    <div class="form-check">
                                                                        <input class="form-check-input position-static" type="radio" name="map[{{$l_outcomes[$i]->l_outcome_id}}][{{$pl_outcome->pl_outcome_id}}]" value="N/A"
                                                                        @if(isset($l_outcomes[$i]->programLearningOutcomes->find($pl_outcome->pl_outcome_id)->pivot))
                                                                        @if($l_outcomes[$i]->programLearningOutcomes->find($pl_outcome->pl_outcome_id)->pivot->map_scale_value =='N/A') checked=checked @endif @endif required>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                                <button type="submit" class="btn btn-primary my-3 btn-sm float-right col-2">Save</button>
                                            </div>
                                        </div>
                            </form>
                        </div>
                    </div>
                @endfor
            </div>
        @endif
    </div>
    <div class="card-footer">
        <a href="{{ route('programWizard.step4', $program->program_id) }}"><button class="btn btn-sm btn-primary mt-3 col-3 float-left">Back To {{$program->program}}</button></a>

        <a href="{{ route('ploclomap.index',[$course->course_id, $program->program_id]) }}"><button class="btn btn-sm btn-primary mt-3 col-3 float-right">Map Another Course</button></a>
    </div>
</div>


<script type="text/javascript">
    $(document).ready(function () {

    $("form").submit(function () {
        // prevent duplicate form submissions
        $(this).find(":submit").attr('disabled', 'disabled');
        $(this).find(":submit").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');

    });
    });
</script>
@endsection