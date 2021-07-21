@extends('layouts.app')

@section('content')
<div id="step1Courses">
    <div class="row justify-content-center">
        <div class="col-md-12">
            @include('courses.wizard.header')

            <div class="card">
                <div class="card-header text-start pt-4 pb-4" >
                    <h3>
                        Course Learning Outcomes (CLOs)
                        <button type="button" class="btn btn-primary col-2 float-right bg-primary text-white fs-5"  data-toggle="modal" data-target="#addLearningOutcomeModal">
                            <i class="bi bi-plus mr-2 "></i>CLO
                        </button>
                    </h3>
                    
                    <!-- Add CLO Modal: Bloom’s Taxonomy of Learning Modal -->
                    <div class="modal fade" id="addLearningOutcomeModal" tabindex="-1" role="dialog"
                        aria-labelledby="addLearningOutcomeModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="addLearningOutcomeModalLabel">Add Course Learning Outcome or Competency
                                    </h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>

                                <form method="POST" action="{{ action('LearningOutcomeController@store') }}">
                                    @csrf
                                    <div class="modal-body">
                                        <div class="form-group row">
                                            <label for="l_outcome" class="col-md-4 col-form-label text-md-center"><span class="requiredField">* </span>Course Learning Outcome (CLO) or Competency</label>

                                            <div class="col-md-8">
                                                <textarea id="l_outcome" class="form-control" @error('l_outcome') is-invalid @enderror
                                                rows="3" name="l_outcome" required autofocus placeholder="E.g. Develop..."></textarea>

                                                @error('l_outcome')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror

                                                <small class="form-text text-muted">
                                                    <a href="https://tips.uark.edu/using-blooms-taxonomy/" target="_blank"><strong><i class="bi bi-box-arrow-up-right"></i> Click here</strong></a>
                                                    for tips to write effective CLOs
                                                </small>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="title" class="col-md-4 col-form-label text-md-right">Short Phrase</label>

                                            <div class="col-md-8">
                                                <input id="title" type="text" class="form-control @error('title') is-invalid @enderror"
                                                name="title" autofocus placeholder="E.g Experiment..." maxlength="50">

                                                @error('title')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror

                                                <small class="form-text text-muted">
                                                    <strong>50 character limit.</strong> Having a short phrase helps with data visualization at the end of this process.
                                                </small>
                                            </div>

                                            <div style="col-md-8; text-align: center; margin-top:20px">

                                                <div>
                                                    <p style="margin-top: 25px;margin-left:4px;margin-right:4px;">A well-written learning outcome states what students are expected to <span style="font-style: italic;">know, be able to do, or care about</span>, after successfully completing the course/program. Such statements begin with one measurable verb.</p>
                                                    <p>The below are examples of verbs associated with different levels of Bloom’s Taxonomy of Learning.</p>
                                                </div>
                                                <img class="img-fluid" src=" {{ asset('img/blooms-taxonomy-diagram.png') }}"/>
                                                <!-- Bloom's Taxonomy Image -->
                                                <!-- <div class="flex-container ">
                                                    <div class="box" style="background-color: #e8f4f8;">
                                                        <strong>REMEMBER</strong>
                                                        <p>Retrieve relevant knowledge from long-term memory</p>
                                                    </div>
                                                    <div class="box" style="background-color: #E6E6FA;">
                                                        <strong>UNDERSTAND</strong>
                                                        <p>Construct meaning from instructional messages</p>
                                                    </div>
                                                    <div class="box" style="background-color: #c1e1ec;">
                                                        <strong>APPLY</strong>
                                                        <p>Carry out or use a procedure in a given situation</p>
                                                    </div>
                                                    <div class="box" style="background-color: #ADD8E6;">
                                                        <strong>ANALYZE</strong>
                                                        <p>Break material into its constituent parts and determine how the parts relate</p>
                                                    </div>
                                                    <div class="box" style="background-color: #87CEEB;">
                                                        <strong>EVALUATE</strong>
                                                        <p>Make judgements basesd on criteria and standards</p>
                                                    </div>
                                                    <div class="box" style="background-color: #6495ED;">
                                                        <strong>CREATE</strong>
                                                        <p>Put elements together to form a coherent or functional whole</p>
                                                    </div>

                                                    <div class="box">
                                                        <p class="CLO_example">Example: define, describe, identify, list, locate, match, memorize, recall, recognize, reproduce, select, state</p>
                                                    </div>
                                                    <div class="box">
                                                        <p class="CLO_example">Example: classify，compare，discuss，distinguish，exemplify，explain，illustrate，inder，interpret，paraphrase，predict，summarize</p>
                                                    </div>
                                                    <div class="box">
                                                        <p class="CLO_example">Example: calculate，construct，demonstrate，dramatize，employ，execute，implement，manipulate，modify，simulate solve</p>
                                                    </div>
                                                    <div class="box">
                                                        <p class="CLO_example">Example: attribute，categorize，classify，compare，correlate，deduce，differentiate，distinguish，organize plan</p>
                                                    </div>
                                                    <div class="box">
                                                        <p class="CLO_example">Example: assess，check，critique，decide，defend，judge，justify，presuade，recommend，support</p>
                                                    </div>
                                                    <div class="box">
                                                        <p class="CLO_example">Example: compile，compose，construct，design，develop，formulate，generate，hypothesize，integrate，modify，plan，produce</p>
                                                    </div>
                                                </div> -->
                                                <!-- End of Bloom's Taxonomy Image -->
                                                <small>
                                                    Source: Anderson, L. W., Krathwohl, D. R., & Bloom, B. S. (2001). A taxonomy for learning, teaching, and assessing: A revision of bloom's taxonomy of educational objectives (Abridged ed.). New York: Longman.
                                                </small>
                                            </div>
                                        </div>

                                        <input type="hidden" name="course_id" value="{{$course->course_id}}">

                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary col-2 btn-sm" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary col-2 btn-sm">Add</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- End of Add CLO Modal -->

                </div>

                <div class="card-body">

                    <h6 class="card-subtitle mb-4 lh-lg">
                        Input the <a href="https://ctl.ok.ubc.ca/teaching-development/classroom-practices/learning-outcomes/" target="_blank"><i class="bi bi-box-arrow-up-right"></i> course learning outcomes (CLOs)</a> or <a href="https://sph.uth.edu/content/uploads/2012/01/Competencies-and-Learning-Objectives.pdf" target="_blank"><i class="bi bi-box-arrow-up-right"></i> competencies</a> of the course individually.
                        <strong>It is recommended that a course has 5-7 CLOs maximum</strong>.                    
                    </h6>

                    <div id="clo">
                        <div class="row">
                            <div class="col">

                                    @if(count($l_outcomes)<1)
                                        <div class="alert alert-warning wizard">
                                            <i class="bi bi-exclamation-circle-fill"></i>There are no course learning outcomes set for this course.                    
                                        </div>
                                    @else
                                        <table class="table table-light table-bordered" >
                                            <tr class="table-primary">
                                                <th class="text-center">#</th>
                                                <th>Course Learning Outcomes or Competencies</th>
                                                <th class="text-center w-25">Actions</th>
                                            </tr>

                                                @foreach($l_outcomes as $index => $l_outcome)
                                                <tr>
                                                    <td class="text-center fw-bold" style="width:5%" >{{$index+1}}</td>                                                
                                                    <td>
                                                        <b>{{$l_outcome->clo_shortphrase}}</b><br>
                                                        {{$l_outcome->l_outcome}}
                                                    </td>
                                                    <td class="text-center align-middle">

                                                        <button type="button" style="width:60px;" class="btn btn-secondary btn-sm m-1" data-toggle="modal" data-target="#editLearningOutcomeModal{{$l_outcome->l_outcome_id}}">
                                                            Edit
                                                        </button>

                                                        <button style="width:60px;" type="button" class="btn btn-danger btn-sm btn btn-danger btn-sm m-1"
                                                        data-toggle="modal" data-target="#CLOdeleteConfirmation{{$l_outcome->l_outcome_id}}">
                                                            Delete
                                                        </button>

                                                        <!-- Bloom’s Taxonomy of Learning Modal -->
                                                        <div class="modal fade" id="editLearningOutcomeModal{{$l_outcome->l_outcome_id}}" tabindex="-1" role="dialog"
                                                            aria-labelledby="editLearningOutcomeModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog modal-lg" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="editLearningOutcomeModalLabel">Edit Course Learning Outcome or Competency
                                                                        </h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>

                                                                    <form method="POST" action="{{ route('lo.update', $l_outcome->l_outcome_id) }}">
                                                                        @csrf
                                                                        {{method_field('PUT')}}
                                                                        <div class="modal-body">
                                                                            <div class="form-group row">
                                                                                <label for="l_outcome" class="col-md-4 col-form-label text-md-center">Course Learning Outcome (CLO) or Competency</label>

                                                                                <div class="col-md-8">
                                                                                    <textarea id="l_outcome" class="form-control" @error('l_outcome') is-invalid @enderror
                                                                                    rows="3" name="l_outcome" required autofocus placeholder="Develop...">{{$l_outcome->l_outcome}}</textarea>

                                                                                    @error('l_outcome')
                                                                                    <span class="invalid-feedback" role="alert">
                                                                                        <strong>{{ $message }}</strong>
                                                                                    </span>
                                                                                    @enderror

                                                                                    <small class="form-text text-muted">
                                                                                        <a href="https://tips.uark.edu/using-blooms-taxonomy/" target="_blank"><strong><i class="bi bi-box-arrow-up-right"></i> Click here</strong></a>
                                                                                        for tips to write effective CLOs
                                                                                    </small>

                                                                                </div>
                                                                            </div>

                                                                            <div class="form-group row">
                                                                                <label for="title" class="col-md-4 col-form-label text-md-right">Short Phrase</label>

                                                                                <div class="col-md-8">
                                                                                    <input id="title" type="text" class="form-control @error('title') is-invalid @enderror"
                                                                                    name="title" autofocus placeholder="Experiment..." value="{{$l_outcome->clo_shortphrase}}">

                                                                                    @error('title')
                                                                                    <span class="invalid-feedback" role="alert">
                                                                                        <strong>{{ $message }}</strong>
                                                                                    </span>
                                                                                    @enderror

                                                                                    <small class="form-text text-muted">
                                                                                        Having a short phrase helps with data visualization at the end of this process <strong>(4 words max)</strong>.
                                                                                    </small>
                                                                                </div>

                                                                                <div style="col-md-8; text-align: center; margin-top:20px">

                                                                                    <div>
                                                                                        <p style="margin-top: 25px;margin-left:4px;margin-right:4px;">A well-written learning outcome states what students are expected to <span style="font-style: italic;">know, be able to do, or care about</span>, after successfully completing the course/program. Such statements begin with one measurable verb.</p>
                                                                                        <p>The below are examples of verbs associated with different levels of Bloom’s Taxonomy of Learning.</p>
                                                                                    </div>

                                                                                    <div class="flex-container">
                                                                                        <div class="box" style="background-color: #e8f4f8;">
                                                                                            <strong>REMEMBER</strong>
                                                                                            <p>Retrieve relevant knowledge from long-term memory</p>
                                                                                        </div>
                                                                                        <div class="box" style="background-color: #E6E6FA;">
                                                                                            <strong>UNDERSTAND</strong>
                                                                                            <p>Construct meaning from instructional messages</p>
                                                                                        </div>
                                                                                        <div class="box" style="background-color: #c1e1ec;">
                                                                                            <strong>APPLY</strong>
                                                                                            <p>Carry out or use a procedure in a given situation</p>
                                                                                        </div>
                                                                                        <div class="box" style="background-color: #ADD8E6;">
                                                                                            <strong>ANALYZE</strong>
                                                                                            <p>Break material into its constituent parts and determine how the parts relate</p>
                                                                                        </div>
                                                                                        <div class="box" style="background-color: #87CEEB;">
                                                                                            <strong>EVALUATE</strong>
                                                                                            <p>Make judgements basesd on criteria and standards</p>
                                                                                        </div>
                                                                                        <div class="box" style="background-color: #6495ED;">
                                                                                            <strong>CREATE</strong>
                                                                                            <p>Put elements together to form a coherent or functional whole</p>
                                                                                        </div>

                                                                                        <div class="box">
                                                                                            <p class="CLO_example">Example: define, describe, identify, list, locate, match, memorize, recall, recognize, reproduce, select, state</p>
                                                                                        </div>
                                                                                        <div class="box">
                                                                                            <p class="CLO_example">Example: classify，compare，discuss，distinguish，exemplify，explain，illustrate，inder，interpret，paraphrase，predict，summarize</p>
                                                                                        </div>
                                                                                        <div class="box">
                                                                                            <p class="CLO_example">Example: calculate，construct，demonstrate，dramatize，employ，execute，implement，manipulate，modify，simulate solve</p>
                                                                                        </div>
                                                                                        <div class="box">
                                                                                            <p class="CLO_example">Example: attribute，categorize，classify，compare，correlate，deduce，differentiate，distinguish，organize plan</p>
                                                                                        </div>
                                                                                        <div class="box">
                                                                                            <p class="CLO_example">Example: assess，check，critique，decide，defend，judge，justify，presuade，recommend，support</p>
                                                                                        </div>
                                                                                        <div class="box">
                                                                                            <p class="CLO_example">Example: compile，compose，construct，design，develop，formulate，generate，hypothesize，integrate，modify，plan，produce</p>
                                                                                        </div>
                                                                                    </div>

                                                                                    <small>
                                                                                        Source: Anderson, L. W., Krathwohl, D. R., & Bloom, B. S. (2001). A taxonomy for learning, teaching, and assessing: A revision of bloom's taxonomy of educational objectives (Abridged ed.). New York: Longman.
                                                                                    </small>
                                                                                </div>

                                                                            </div>

                                                                            <input type="hidden" name="course_id" value="{{$course->course_id}}">

                                                                        </div>

                                                                        <div class="modal-footer">
                                                                            <button type="button" class="btn btn-secondary col-2 btn-sm" data-dismiss="modal">Close</button>
                                                                            <button type="submit" class="btn btn-primary col-2 btn-sm">Save</button>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Delete Confirmation Modal -->
                                                        <div class="modal fade" id="CLOdeleteConfirmation{{$l_outcome->l_outcome_id}}" tabindex="-1" role="dialog" aria-labelledby="CLOdeleteConfirmation" aria-hidden="true">
                                                            <div class="modal-dialog" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="CLOdeleteConfirmation">Delete Confirmation</h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>

                                                                    <div class="modal-body">
                                                                    Are you sure you want to delete {{$l_outcome->l_outcome}}
                                                                    </div>

                                                                    <form class="float-right ml-2" action="{{route('lo.destroy', $l_outcome->l_outcome_id)}}" method="POST">
                                                                        @csrf
                                                                        {{method_field('DELETE')}}
                                                                        <input type="hidden" name="course_id" value="{{$course->course_id}}">

                                                                        <div class="modal-footer">
                                                                            <button style="width:60px" type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
                                                                            <button style="width:60px;" type="submit" class="btn btn-danger btn-sm ">Delete</button>
                                                                        </div>

                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </td>
                                                </tr>
                                                @endforeach
                                        </table>
                                    @endif      
                            </div>
                        </div>
                    </div>
                </div>

                <!-- card footer -->
                <div class="card-footer">
                    <div class="card-body mb-4">

                        <a href="{{route('courseWizard.step2', $course->course_id)}}">
                            <button class="btn btn-sm btn-primary col-3 float-right">Student Assessment Methods <i class="bi bi-arrow-right mr-2"></i></button>
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
