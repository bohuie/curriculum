@extends('layouts.app')

@section('content')

<div>
    @include('courses.wizard.header')
    <div id="app">
        <div class="home">
            <div class="card" style="position:static">
                <div class="card-header wizard" >
                    <h3>
                        Student Assessment Methods
                        <div style="float: right;">
                            <button id="samHelp" style="border: none; background: none; outline: none;" data-bs-toggle="modal" href="#guideModal">
                                <i class="bi bi-question-circle" style="color:#002145;"></i>
                            </button>
                        </div>
                        <div class="text-left">
                            @include('layouts.guide')
                        </div>
                    </h3>
                </div>

                <div class="card-body">
                    <div class="alert alert-primary d-flex align-items-center" role="alert" style="text-align:justify">
                        <i class="bi bi-info-circle-fill pr-2 fs-3"></i>                        
                        <div>
                            Input all <a href="https://ctlt.ubc.ca/resources/webliography/assessmentevaluation/" target="_blank" rel="noopener noreferrer" class="alert-link">
                                <i class="bi bi-box-arrow-up-right"></i> assessment methods
                            </a> 
                            of the course individually. You may also choose to use the 
                            <a href="https://ubcoapps.elearning.ubc.ca/" target="_blank" rel="noopener noreferrer" class="alert-link"><i class="bi bi-box-arrow-up-right">
                                </i> UBCO's Workload Calculator
                            </a> 
                            to estimate the student time commitment in this course based on the chosen assignments.              
                        </div>
                    </div>

                    
                    <div class="row">
                        <div class="col">
                            <h6 class="card-subtitle mb-4 lh-lg">
                            </h6>
                        </div>
                    </div>

                    <div class="row mb-1">
                        <div class="col">
                            <button type="button" class="btn btn-primary col-4 float-right bg-primary text-white fs-5"  data-bs-toggle="modal" data-bs-target="#addAssessmentMethodModal">
                                <i class="bi bi-plus mr-2"></i>Student Assessment Methods
                            </button>
                        </div>
                    </div>

                    <form action="{{route('courses.amReorder', $course->course_id)}}" method="POST">
                        @csrf
                        <!-- Table -->
                        <table class="table table-light reorder-tbl-rows mb-0" id="a_method_table">
                            <thead>
                                <tr class="table-primary">
                                    <th class="text-center"></th>
                                    <th>Student Assesment Methods</th>
                                    <th>Weight</th>
                                    <th class="text-center w-25">Actions</th>                                    
                                </tr>
                            </thead>
                            @if(count($a_methods)<1)
                                <tr>
                                    <td colspan="4">
                                        <div class="alert alert-warning wizard">
                                            <i class="bi bi-exclamation-circle-fill"></i>There are no student assessment methods set for this course.                    
                                        </div>
                                    </td>
                                </tr>
                            @else
                                @foreach($a_methods as $index=>$a_method)
                                    <tr>
                                        <td class="text-center fw-bold" style="width:5%">↕</td>                                                
                                        <td>
                                            {{$a_method->a_method}}                                                    
                                        </td>
                                        <td >
                                            {{$a_method->weight}}%
                                        </td>
                                        <td class="text-center align-middle">
                                            <button type="button" style="width:60px;" class="btn btn-secondary btn-sm m-1" data-bs-toggle="modal" data-bs-target="#addAssessmentMethodModal">
                                                Edit
                                            </button>
                                            <!-- Removed Delete Button Modal Not Working -->
                                            <!-- <button style="width:60px;" type="button" class="btn btn-danger btn-sm btn btn-danger btn-sm m-1" data-toggle="modal" data-target="#deleteAMethod{{$a_method->a_method_id}}">
                                                Delete
                                            </button> -->
                                            <input type="hidden" name="course_id" value="{{$course->course_id}}">
                                        </td>
                                        <input type="hidden" name="a_method_pos[]" value="{{ $a_method->a_method_id }}">
                                    </tr>
                                @endforeach
                            @endif
                        </table>
                        <table class="table table-light mt-0">
                            <tr class="table-secondary footer">
                                <td colspan="2"><b>TOTAL</b></td>
                                <td></td>
                                <td><b id="sum">{{$totalWeight}}%</b></td>
                            </tr>
                        </table>
                        <!-- End Table -->
                        <div class="mt-4">
                            <button type="submit" class="btn btn-success float-right col-2">Save Order</button>
                        </div>
                    </form>

                    <!-- start of add student assessment methods modal -->
                    <div id="addAssessmentMethodModal" class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="addAssessmentMethodModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="addAssessmentMethodModalLabel"><i class="bi bi-pencil-fill btn-icon mr-2"></i> Student Assessment Methods</h5>
                                </div>

                                <div class="modal-body">
                                    <form id="addAssessmentMethodForm" class="needs-validation" novalidate>
                                        <div class="row align-items-end m-2">
                                            <div class="col-6">
                                                <label for="assessmentMethod" class="form-label fs-6"><b>Assessment Method</b></label>
                                                <input id="assessmentMethod" class="form-control" list="assessmentMethodOptions" oninput="validateMaxlength()" onpaste="validateMaxlength()" maxlength="191" placeholder="Type to search or add your own..." required>
                                                <div class="invalid-tooltip">
                                                    Please provide an assessment method.
                                                </div>                                            
                                                <datalist id="assessmentMethodOptions">
                                                    <option value="Annotated bibliography">
                                                    <option value="Assignment">
                                                    <option value="Attendance">
                                                    <option value="Brochure, poster">
                                                    <option value="Case analysis">
                                                    <option value="Debate">
                                                    <option value="Diagram/chart">
                                                    <option value="Dialogue">
                                                    <option value="Essay">
                                                    <option value="Exam">
                                                    <option value="Fill in the blank test">
                                                    <option value="Final Exam">
                                                    <option value="Group discussion">
                                                    <option value="Lab/field notes">
                                                    <option value="Letter">
                                                    <option value="Literature review">
                                                    <option value="Mathematical problem">
                                                    <option value="Materials and methods plan">
                                                    <option value="Mid-term Exam">
                                                    <option value="Multimedia or slide presentation">
                                                    <option value="Multiple-choice test">
                                                    <option value="News or feature story">
                                                    <option value="Oral report">
                                                    <option value="Outline">
                                                    <option value="Participation">
                                                    <option value="Project">
                                                    <option value="Project plan">
                                                    <option value="Presentation">
                                                    <option value="Poem">
                                                    <option value="Play">
                                                    <option value="Quiz">
                                                    <option value="Research proposal">
                                                    <option value="Review of book, play, exhibit">
                                                    <option value="Rough draft or freewrite">
                                                    <option value="Social media post">
                                                    <option value="Summary">
                                                    <option value="Technical or scientific report">
                                                    <option value="Term/research paper">
                                                    <option value="Thesis statement">

                                                    @if(isset($custom_methods))
                                                        @foreach($custom_methods as $method)
                                                        <option value={{$method->custom_methods}}>
                                                        @endforeach
                                                    @endif                                            
                                                </datalist>
                                            </div>
                                            <div class="col-4">
                                                <label for="weight" class="form-label fs-6"><b>Weight</b></label>
                                                <input id="weight" type="number" step="0.1" class="form-control " min="0" max="100" oninput="validateMaxlength()" onpaste="validateMaxlength()" maxlength="3" required>
                                                <div class="invalid-tooltip">
                                                    Please provide a valid weight.
                                                </div>
                                            </div>
                                            <div class="col-2">
                                                <button id="addAssessmentMethodBtn" type="submit" class="btn btn-primary col">Add</button>
                                            </div>
                                        </div>
                                    </form>
                                    <div class="row justify-content-center">
                                        <div class="col-8">
                                            <hr>
                                        </div>
                                    </div> 
                                    <div class="row m-1">
                                        <table id="addAssessmentMethodsTbl" class="table table-light table-borderless">
                                            <thead>
                                                <tr class="table-primary">
                                                    <th>Student Assessment Method</th>
                                                    <th>Weight</th>
                                                    <th class="text-center">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($a_methods as $index => $a_method)
                                                <tr>

                                                    <td>
                                                        <input list="assessmentMethodOptions" id="a_method{{$a_method->a_method_id}}" type="text" class="form-control @error('a_method') is-invalid @enderror" oninput="validateMaxlength()" onpaste="validateMaxlength()" maxlength="191"
                                                        name="current_a_methods[{{$a_method->a_method_id}}]" value = "{{$a_method->a_method}}" placeholder="Choose from the dropdown list or type your own" form="saveAssessmentMethodChanges" required>
                                                    </td>
                                                    <td>                                                 
                                                        <input class="p-1" id="a_method_weight{{$a_method->a_method_id}}" type="number" step="0.1" form="saveAssessmentMethodChanges" class="form-control @error('weight') is-invalid @enderror" value="{{$a_method->weight}}" name="current_weights[{{$a_method->a_method_id}}]" min="0" max="100" oninput="validateMaxlength()" onpaste="validateMaxlength()" maxlength="3" required>
                                                        <label for="a_method_weight{{$a_method->a_method_id}}" style="font-size: medium; margin-top:5px;margin-left:5px"><strong>%</strong></label>
                                                    </td>
                                                    <td class="text-center">
                                                        <i class="bi bi-x-circle-fill text-danger fs-4 btn" onclick="deleteAssessmentMethod(this)"></i>
                                                    </td>
                                                    
                                                </tr>
                                                @endforeach                                        
                                            </tbody>
                                        </table>                                    
                                    </div>
                                </div>
                                <form method="POST" id="saveAssessmentMethodChanges" action="{{ action([\App\Http\Controllers\AssessmentMethodController::class, 'store']) }}">
                                    @csrf
                                    <div class="modal-footer">
                                        <input type="hidden" name="course_id" value="{{$course->course_id}}" form="saveAssessmentMethodChanges">
                                        <button id="cancel" type="button" class="btn btn-secondary col-3" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-success btn col-3" >Save Changes</button>
                                    </div>
                                </form>    
                            </div>
                        </div>
                    </div>
                    <!-- End of add student assessment methods modal -->
                </div>

                <!-- card footer -->
                <div class="card-footer">
                    <div class="card-body mb-4">
                        <a href="{{route('courseWizard.step1', $course->course_id)}}">
                            <button class="btn btn-sm btn-primary col-3 float-left"><i class="bi bi-arrow-left mr-2"></i> Course Learning Outcomes</button>
                        </a>
                        <a href="{{route('courseWizard.step3', $course->course_id)}}">
                            <button class="btn btn-sm btn-primary col-3 float-right">Teaching and Learning Activities <i class="bi bi-arrow-right ml-2"></i></button>
                        </a>
                    </div>
                </div>            
            </div>
        </div>
    </div>
</div>


<script>

    $(document).ready(function () {
        sortDropdown();
        //   $("form").submit(function () {
        //     // prevent duplicate form submissions
        //     $(this).find(":submit").attr('disabled', 'disabled');
        //     $(this).find(":submit").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');

        //   });

        
        $('#addAssessmentMethodForm').submit(function (event) {
            // prevent default form submission handling
            event.preventDefault();
            event.stopPropagation();
            // check if input fields contain data
            if ($('#assessmentMethod').val().length != 0 && $('#weight').val() >= 0) {
                addAssessmentMethod();
                // reset form 
                $(this).trigger('reset');
                $(this).removeClass('was-validated');
            } else {
                // mark form as validated
                $(this).addClass('was-validated');
            }
            // readjust modal's position 
            document.querySelector('#addAssessmentMethodModal').handleUpdate();

        });

        $('#cancel').click(function(event) {
            $('#addAssessmentMethodsTbl tbody').html(`
                @foreach($a_methods as $index=>$a_method)
                    <tr>
                        <td>
                            <input list="assessmentMethodOptions" id="a_method{{$a_method->a_method_id}}" type="text" class="form-control @error('a_method') is-invalid @enderror" name="current_a_methods[{{$a_method->a_method_id}}]" value = "{{$a_method->a_method}}" placeholder="Choose from the dropdown list or type your own" form="saveAssessmentMethodChanges" required>
                        </td>
                        <td>                                                 
                            <input class="p-1" id="a_method_weight{{$a_method->a_method_id}}" type="number" step="0.1" form="saveAssessmentMethodChanges" class="form-control @error('weight') is-invalid @enderror" value="{{$a_method->weight}}" name="current_weights[{{$a_method->a_method_id}}]" min="0" max="100" required>
                            <label for="a_method_weight{{$a_method->a_method_id}}" style="font-size: medium; margin-top:5px;margin-left:5px"><strong>%</strong></label>
                        </td>
                        <td class="text-center">
                            <i class="bi bi-x-circle-fill text-danger fs-4 btn" onclick="deleteAssessmentMethod(this)"></i>
                        </td>
                    </tr>
                @endforeach 
            `);
        });
    });

    function deleteAssessmentMethod(submitter) {
        console.log(submitter);
        $(submitter).parents('tr').remove();
    }

    function addAssessmentMethod() {
        // prepend assessment method to the table
        $('#addAssessmentMethodsTbl tbody').append(`
            <tr>
                <td>
                    <input list="assessmentMethodOptions" type="text" class="form-control @error('a_method') is-invalid @enderror" name="new_a_methods[]" value="${$('#assessmentMethod').val()}" placeholder="Choose from the dropdown list or type your own" form="saveAssessmentMethodChanges" required >
                </td>
                <td>                                                 
                    <input class="p-1" type="number" step="0.1" form="saveAssessmentMethodChanges" class="form-control @error('weight') is-invalid @enderror" value="${$('#weight').val()}" name="new_weights[]" min="0" max="100" required >
                    <label style="font-size: medium; margin-top:5px;margin-left:5px"><strong>%</strong></label>
                </td>
                <td class="text-center">
                    <i class="bi bi-x-circle-fill text-danger fs-4 btn" onclick="deleteAssessmentMethod(this)"></i>
                </td>
            </tr>        
        `);
    }

    // Dynamic finds total
    function calculateTotal() {
        var sum = 0;
        $("input[name = 'weight[]']").each(function() {
            sum += Number($(this).val());
        });
        return sum;
    }

    //Calculate the row count
    function calculateRow() {
        var rowCount;
        if(document.getElementById("sum") !== null){
            rowCount = $('#a_method_table tr').length-2;
        }else{
            rowCount = $('#a_method_table tr').length-1;
        }
        return rowCount;
    }

    //  Finds all custom user learning activites
    function filterCustom(){
        var custom = [];
        var inputArray = $('input[name^="a_method[]"]').map(function(idx,elem){
            return $(elem).val();
        }).get();

        var datalist = $('datalist[name^="a_methods"]:first option').map(function(idx,elem){
            return $(elem).val();
        }).get();

        for(var i=0;i<inputArray.length;i++){
            if(!datalist.includes(inputArray[i])){
                custom.push(inputArray[i]);
            }
        }
        return custom;
    }


    // Sort drop alphabeticlly
    function sortDropdown(){
        var datalist = $('datalist[name^="a_methods"]:first option').map(function(idx,elem){
            return $(elem).val();
        }).get();

        var sortedDropdown = [];
        var sortedDatalist = sort(datalist);
        for(var i =0, n = sortedDatalist.length;i<n;i++){
            sortedDropdown.push("<option value='" + sortedDatalist[i] + "'>")
        }

        var rowCount;
        if(document.getElementById("sum") !== null){
            rowCount = $('#a_method_table tr').length-2;
        }else{
            rowCount = $('#a_method_table tr').length-1;
        }

        sortedDropdown.join();

        for(var i = 0;i<rowCount;i++) {
            var datalist = $("#a_methods" + i);
            datalist.empty().append(sortedDropdown);
        }
    }

    // Helper function used to Sorting the datalist
    function sort(datalist) {
        datalist.sort(function(string_1,string_2) {
            if(string_1.toLowerCase() < string_2.toLowerCase()){return -1;}
            if(string_1.toLowerCase() > string_2.toLowerCase()){return 1;}
            return 0;
        });
        return datalist;
    }

</script>

<script src="{{ asset('js/drag_drop_tbl_row.js') }}"></script>
<link rel="stylesheet" href="{{ asset('css/drag_drop_tbl_row.css' ) }}">
@endsection
