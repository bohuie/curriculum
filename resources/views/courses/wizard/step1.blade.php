@extends('layouts.app')

@section('content')
@include('courses.wizard.header')
    <div id="app">
        <div class="home">
            <div class="card" style="position:static">
                <div class="card-header wizard">
                    <h3 class="">
                        Course Learning Outcomes (CLOs)
                        <div style="float: right;">
                            <button id="cloHelp" style="border: none; background: none; outline: none;" data-bs-toggle="modal" href="#guideModal">
                                <i class="bi bi-question-circle" style="color:#002145;"></i>
                            </button>
                        </div>
                        <div class="text-left">
                            @include('layouts.guide')
                        </div>
                    </h3>
                    
                    <!-- Add CLO Modal: Bloom’s Taxonomy of Learning Modal -->
                    <div class="modal fade" data-backdrop="static" data-keyboard="false" id="addLearningOutcomeModal" tabindex="-1" role="dialog"
                        aria-labelledby="addLearningOutcomeModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="addLearningOutcomeModalLabel"><i class="bi bi-pencil-fill btn-icon mr-2"></i> Course Learning Outcomes or Competencies
                                    </h5>
                                </div>

                                <div class="modal-body text-left">
                                        <form id="addCLOForm" class="needs-validation" novalidate>
                                            <div class="form-group row align-items-end">
                                                <div class="col-6">
                                                    <label for="l_outcome" class="form-label fs-6">
                                                        <span class="requiredField">* </span>
                                                        <b>Course Learning Outcome (CLO)</b>
                                                        <div><small class="form-text text-muted" style="font-size:12px"><a href="https://tips.uark.edu/using-blooms-taxonomy/" target="_blank" rel="noopener noreferrer"><b><i class="bi bi-box-arrow-up-right"></i> Click here</b></a> for tips to write effective CLOs.</small></div>
                                                    </label>
                
                                                    <textarea id="l_outcome" class="form-control" oninput="validateMaxlength()" onpaste="validateMaxlength()" maxlength="30000" name="l_outcome" required autofocus placeholder="E.g. Develop..." style="resize:none"></textarea>
                                                    <div class="invalid-tooltip">
                                                        You must input a course learning outcome or competency.
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <label for="title" class="form-label fs-6">
                                                        <b>Short Phrase</b>
                                                        <div><small class="form-text text-muted" style="font-size:12px"><b><i class="bi bi-exclamation-circle-fill text-warning" data-bs-toggle="tooltip" data-bs-placement="left" title="Having a short phrase helps with visualizing your course summary at the end of the mapping process"></i> 50 character limit.</b></small></div>
                                                    </label>
                                                    <textarea id="title" class="form-control" name="title" autofocus placeholder="E.g Experimental Design..." maxlength="50" style="resize:none"></textarea> 
                                                </div>
                                                <div class="col-2">
                                                    <button id="addCLOBtn" type="submit" class="btn btn-primary col mb-1">Add</button>
                                                </div>
                                            </div>
                                        </form>

                                        <div class="row justify-content-center">
                                            <div class="col-8">
                                                <hr>
                                            </div>
                                        </div>                

                                        <div class="row m-1">
                                            <table id="addCLOTbl" class="table table-light table-borderless">
                                                <thead>
                                                    <tr class="table-primary">
                                                        <th class="text-left">Course Learning Outcomes or Competencies</th>
                                                        <th class="text-left">Short Phrase</th>
                                                        <th class="text-center">Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($l_outcomes as $index => $l_outcome)
                                                        <tr>
                                                            <td>
                                                                <textarea name="current_l_outcome[{{$l_outcome->l_outcome_id}}]" value="{{$l_outcome->l_outcome}}" id="l_outcome{{$l_outcome->l_outcome_id}}" 
                                                                class="form-control @error('l_outcome') is-invalid @enderror" form="saveCLOChanges" oninput="validateMaxlength()" onpaste="validateMaxlength()" maxlength="30000" required>{{$l_outcome->l_outcome}}</textarea>
                                                            </td>
                                                            <td>
                                                                <textarea type="text" name="current_l_outcome_short_phrase[{{$l_outcome->l_outcome_id}}]" id="l_outcome_short_phrase{{$l_outcome->l_outcome_id}}"
                                                                class="form-control @error('clo_shortphrase') is-invalid @enderror"  form="saveCLOChanges">{{$l_outcome->clo_shortphrase}}</textarea>
                                                            </td>
                                                            <td class="text-center">
                                                                <i class="bi bi-x-circle-fill text-danger fs-4 btn" onclick="deleteCLO(this)"></i>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <form method="POST" id="saveCLOChanges" action="{{ action([\App\Http\Controllers\LearningOutcomeController::class, 'store']) }}">
                                    @csrf
                                        <div class="modal-footer">
                                            <input type="hidden" name="course_id" value="{{$course->course_id}}" form="saveCLOChanges">
                                            <button id="cancel" type="button" class="btn btn-secondary col-3" data-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-success col-3">Save Changes</button>
                                        </div>
                                    </form>

                            </div>
                        </div>
                    </div>
                    <!-- End of Add CLO Modal -->

                </div>

                <div class="card-body">
                    <div class="alert alert-primary d-flex align-items-center" role="alert" style="text-align:justify">
                        <i class="bi bi-info-circle-fill pr-2 fs-3"></i>                        
                        <div>
                            <a href="https://ctl.ok.ubc.ca/teaching-development/classroom-practices/learning-outcomes/" target="_blank" rel="noopener noreferrer" class="alert-link">
                                <i class="bi bi-box-arrow-up-right"></i> Course Learning Outcomes (CLOs)
                            </a> 
                            or 
                            <a href="https://sph.uth.edu/content/uploads/2012/01/Competencies-and-Learning-Objectives.pdf" target="_blank" rel="noopener noreferrer" class="alert-link">
                                <i class="bi bi-box-arrow-up-right"></i> Competencies
                            </a> 
                            are the knowledge, skills and attributes that students are expected to attain by the end of a course. Add, edit and delete CLOs below. 
                            You may use an excel spreadsheet to import multiple CLOs (use row 1 for headers and begin list of CLOs on row 2). Follow the template provided below to save them on your computer first, and then upload them to this page.

                        </div>
                    </div>

                    <div class="row mb-2 align-items-end">
                        <form method="POST" class="col-6" action="{{ action([\App\Http\Controllers\LearningOutcomeController::class, 'import']) }}" enctype="multipart/form-data">
                            <p>
                                <a href="{{asset('import_samples/import-clos-template.xlsx')}}" download><i class="bi bi-download mb-1"></i> import-clos-template.xlsx</a>
                                or
                                <a href="{{asset('import_samples/import-clos-template.csv')}}" download><i class="bi bi-download mb-1"></i> import-clos-template.csv</a>
                            </p>
                            @csrf
                            <div class="input-group">
                                <input type="hidden" name="course_id" value="{{$course->course_id}}">
                                <input type="file" name="upload" class="form-control" aria-label="Upload" required accept=".xlsx, .csv">
                                <button class="btn bg-primary text-white" type="submit" ><b>Import CLOs</b><i class="bi bi-box-arrow-in-down-left pl-2"></i></button>
                            </div>
                        </form>
                        <div class="col-6 text-right">
                            <button type="button" class="btn btn-primary btn-lg col-4 fs-5 bg-primary text-white"  data-toggle="modal" data-target="#addLearningOutcomeModal">
                                <b ><i class="bi bi-plus mr-2"></i>CLO</b>
                            </button>
                        </div>
                    </div>

                    <div id="clo">
                        @if(count($l_outcomes)<1)
                            <div class="alert alert-warning wizard">
                                <i class="bi bi-exclamation-circle-fill"></i>There are no course learning outcomes set for this course.                    
                            </div>
                        @else
                            <form action="{{route('courses.loReorder', $course->course_id)}}" method="POST">
                                @csrf
                                {{method_field('POST')}}
                                <table class="table table-light reorder-tbl-rows" >
                                    <tr class="table-primary">
                                        <th class="text-center">#</th>
                                        <th>Course Learning Outcomes or Competencies</th>
                                        <th class="text-center w-25">Actions</th>
                                    </tr>
                                        @foreach($l_outcomes as $index => $l_outcome)
                                        <tr>
                                            <td class="text-center fw-bold" style="width:5%" >↕</td>                                                
                                            <td>
                                                <b>{{$l_outcome->clo_shortphrase}}</b><br>
                                                {{$l_outcome->l_outcome}}
                                            </td>
                                            <td class="text-center align-middle">
                                                <button type="button" style="width:60px;" class="btn btn-secondary btn-sm m-1" data-toggle="modal" data-target="#addLearningOutcomeModal">
                                                    Edit
                                                </button>
                                                <!-- <button style="width:60px;" type="button" class="btn btn-danger btn-sm btn btn-danger btn-sm m-1"
                                                data-toggle="modal" data-target="#CLOdeleteConfirmation{{$l_outcome->l_outcome_id}}">
                                                    Delete
                                                </button> -->
                                            </td>
                                            <input type="hidden" name="l_outcomes_pos[]" value="{{$l_outcome->l_outcome_id}}">
                                        </tr>
                                        @endforeach
                                </table>

                                <div class="mt-4">
                                    <button type="submit" class="btn btn-success float-right col-2">Save Order</button>
                                </div>
                            </form>

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
                                        <div class="modal-body text-left">
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

                        @endif      
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
        // Enables functionality of tool tips
        $('[data-bs-toggle="tooltip"]').tooltip({html:true});


        $('#addCLOForm').submit(function (event) {
            // prevent default form submission handling
            event.preventDefault();
            event.stopPropagation();
            // check if input fields contain data
            if ($('#l_outcome').val().length != 0) {
                addCLO();
                // reset form 
                $(this).trigger('reset');
                $(this).removeClass('was-validated');
            } else {
                // mark form as validated
                $(this).addClass('was-validated');
            }
            // readjust modal's position 
            document.querySelector('#addLearningOutcomeModal').handleUpdate();
        });

        $('#cancel').click(function(event) {
            $('#addCLOTbl tbody').html(`
                @foreach($l_outcomes as $index => $l_outcome)
                    <tr>
                        <td>
                            <textarea name="current_l_outcome[{{$l_outcome->l_outcome_id}}]" value="{{$l_outcome->l_outcome}}" id="l_outcome{{$l_outcome->l_outcome_id}}" 
                            class="form-control @error('l_outcome') is-invalid @enderror" form="saveCLOChanges" oninput="validateMaxlength()" onpaste="validateMaxlength()" maxlength="30000" required>{{$l_outcome->l_outcome}}</textarea>
                        </td>
                        <td>
                            <textarea type="text" name="current_l_outcome_short_phrase[{{$l_outcome->l_outcome_id}}]" id="l_outcome_short_phrase{{$l_outcome->l_outcome_id}}"
                            class="form-control @error('clo_shortphrase') is-invalid @enderror" form="saveCLOChanges">{{$l_outcome->clo_shortphrase}}</textarea>
                        </td>
                        <td class="text-center">
                            <i class="bi bi-x-circle-fill text-danger fs-4 btn" onclick="deleteCLO(this)"></i>
                        </td>
                    </tr>
                @endforeach 
            `);
        });
    });

    function deleteCLO(submitter) {
            console.log(submitter);
            $(submitter).parents('tr').remove();
    }

    function addCLO() {
        $('#addCLOTbl tbody').append(`
            <tr>
                <td>
                    <textarea name="new_l_outcomes[]" value="${$('#l_outcome').val()}" class="form-control @error('l_outcome') is-invalid @enderror" form="saveCLOChanges" oninput="validateMaxlength()" onpaste="validateMaxlength()" maxlength="30000" required>${$('#l_outcome').val()}</textarea>
                </td>
                <td>
                    <textarea type="text" name="new_short_phrases[]" oninput="validateMaxlength()" onpaste="validateMaxlength()" maxlength="50" class="form-control @error('clo_shortphrase') is-invalid @enderror" form="saveCLOChanges">${$('#title').val()}</textarea>
                </td>
                <td class="text-center">
                    <i class="bi bi-x-circle-fill text-danger fs-4 btn" onclick="deleteCLO(this)"></i>
                </td>
            </tr>        
        `);
    }
</script>

<script src="{{ asset('js/drag_drop_tbl_row.js') }}"></script>
<link rel="stylesheet" href="{{ asset('css/drag_drop_tbl_row.css' ) }}">
@endsection
