@extends('layouts.app')

@section('content')
<div>
    <div class="row justify-content-center">
        <div class="col-md-12">
            @include('courses.wizard.header')

            <div class="card">

                <h3 class="card-header wizard" >
                    Course Alignment
                </h3>


                <div class="card-body">

                    <h6 class="card-subtitle mb-2 lh-lg">
                        This step, requires instructors to intentionally evaluate all course elements to achieve <a target="_blank" href="https://centre.cc.umanitoba.ca/development/resources/course-alignment/"><i class="bi bi-box-arrow-up-right"></i> course alignment.</a> This means, ensuring that the targeted learning outcomes are in alignment with the assessment methods and teaching/instructional practices. This can be an iterative process and may take a long time. Intentional evaluation and re-thinking of some course elements is encouraged to achieve better alignment. <b>Review CLOs/competencies you have identified, and map assessment methods to teaching and learning activities to initiate curriculum alignment.</b>      
                    </h6>

                        <div class="row">
                            <div class="col">


                                @if(count($l_outcomes)<1)
                                    <div class="alert alert-warning wizard">
                                        <i class="bi bi-exclamation-circle-fill"></i>There are no course learning outcomes set for this course.                    
                                    </div>

                                @else

                                        @if(count($l_activities)<1 && count($a_methods)<1)
                                            <div class="alert alert-warning wizard">
                                                <i class="bi bi-exclamation-circle-fill"></i>There are no teaching/learning activities and assessment methods set for
                                                    this course.                    
                                            </div>

                                        @else

                                                <div class="card-body">
                                                    <div class="container mb-3">
                                                        <h6 class="card-subtitle wizard mb-4 text-primary fw-bold">
                                                            Note: Remember to click save once you are done.
                                                        </h6>

                                                    </div>

                                                    <form id="outcomeDetails" action="{{route('courses.outcomeDetails', $course->course_id)}}" method="POST">
                                                        @csrf

                                                        <table class="table table-light table-bordered" style="table-layout: fixed; width: 100%">
                                                            <tr class="table-primary"> 
                                                                <th>Course Learning Outcomes or Competencies</th>
                                                                <th>Student Assessment Methods</th>
                                                                <th>Teaching and Learning Activities</th>
                                                            </tr>
                                                            <tbody>
                                                                @for($i = 0; $i < count($l_outcomes); $i++)
                                                                    <tr>

                                                                        <td scope="row"><b>{{$i+1}} .</b> {{$l_outcomes[$i]->l_outcome}}</td>
                                                                        <td>
                                                                            @foreach ($a_methods as $a_method)
                                                                                <div class="form-check form-check-inline">
                                                                                    <label class="form-check-label">
                                                                                    <input type="checkbox" class="form-check-input" name="a_methods[{{$l_outcomes[$i]->l_outcome_id}}][]" value="{{$a_method->a_method_id}}"  @if($l_outcomes[$i]->assessmentMethods->contains($a_method->a_method_id)) checked=checked @endif>
                                                                                    {{$a_method->a_method}}

                                                                                    </label>
                                                                                </div>
                                                                            @endforeach
                                                                        </td>
                                                                        <td>
                                                                            @foreach ($l_activities as $l_activity)
                                                                                <div class="form-check form-check-inline">
                                                                                    <label class="form-check-label">
                                                                                    <input type="checkbox" class="form-check-input" name="l_activities[{{$l_outcomes[$i]->l_outcome_id}}][]" value="{{$l_activity->l_activity_id}}"  @if($l_outcomes[$i]->learningActivities->contains($l_activity->l_activity_id)) checked=checked @endif>
                                                                                    {{$l_activity->l_activity}}

                                                                                    </label>
                                                                                </div>
                                                                                @endforeach
                                                                        </td>

                                                                    </tr>
                                                                @endfor
                                                            </tbody>
                                                        </table>
                                                        <div class="mt-4">
                                                            <button type="submit" class="btn btn-success btn-sm float-right col-2">Save</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            @endif
                                        </div>

                                    </div>
                                @endif
                        </div>
                </div>

                <!-- card footer -->
                <div class="card-footer">
                    <div class="card-body mb-4">
                        <a href="{{route('courseWizard.step3', $course->course_id)}}">
                            <button class="btn btn-sm btn-primary col-3 float-left"><i class="bi bi-arrow-left mr-2"></i> Teaching and Learning Activities</button>
                        </a>
                        <a href="{{route('courseWizard.step5', $course->course_id)}}">
                            <button class="btn btn-sm btn-primary col-3 float-right">Program Outcome Mapping <i class="bi bi-arrow-right ml-2"></i></button>
                        </a>
                    </div>
                </div>            
            </div>
        </div>
    </div>
</div>


<script type="application/javascript">
    $(document).ready(function () {

      $("form").submit(function () {
        // prevent duplicate form submissions
        $(this).find(":submit").attr('disabled', 'disabled');
        $(this).find(":submit").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');

      });
    });
  </script>

@endsection
