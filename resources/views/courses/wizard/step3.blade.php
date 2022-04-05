@extends('layouts.app')

@section('content')
<div>
    <div class="row justify-content-center">
        <div class="col-md-12">
            @include('courses.wizard.header')

            <div class="card">
                <div class="card-header wizard" >
                    <h3>
                        Teaching and Learning Activities
                        <div style="float: right;">
                            <button id="tlaHelp" style="border: none; background: none; outline: none;" data-bs-toggle="modal" href="#guideModal">
                                <i class="bi bi-question-circle" style="color:#002145;"></i>
                            </button>
                        </div>
                        <div class="text-left">
                            @include('layouts.guide')
                        </div>
                    </h3>
                </div>

                <!-- start of add learning activities modal -->
                <div id="addLearningActivitiesModal" class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="addLearningActivitiesModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="addLearningActivitiesModalLabel"><i class="bi bi-pencil-fill btn-icon mr-2"></i> Teaching and Learning Activities</h5>
                            </div>

                            <div class="modal-body">
                                <form id="addLearningActivitiesForm" class="needs-validation" novalidate>
                                    <div class="row justify-content-between align-items-end m-2">
                                        <div class="col-10">
                                            <label for="learningActivity" class="form-label fs-6"><b>Learning Activity</b></label>
                                            <input id="learningActivity" class="form-control" list="learningActivitiesOptions" placeholder="Type to search..." required>
                                            <div class="invalid-tooltip">
                                                Please provide a learning activity.
                                            </div>                                            
                                            <datalist id="learningActivitiesOptions">
                                                <option value="Discussion">
                                                <option value="Gallery walk">
                                                <option value="Group discussion">
                                                <option value="Group work">
                                                <option value="Guest Speaker">
                                                <option value="Independent study">
                                                <option value="Issue-based inquiry">
                                                <option value="Jigsaw">
                                                <option value="Journals and learning logs">
                                                <option value="Lab">
                                                <option value="Lecture">
                                                <option value="Literature response">
                                                <option value="Mind map">
                                                <option value="Poll">
                                                <option value="Portfolio development">
                                                <option value="Problem-solving">
                                                <option value="Reflection piece">
                                                <option value="Role-playing">
                                                <option value="Service learning">
                                                <option value="Seminar">
                                                <option value="Sorting">
                                                <option value="Think-pair-share">
                                                <option value="Tutorial">
                                                <option value="Venn diagram">
                                                @if(isset($custom_activities))
                                                    @foreach($custom_activities as $activity)
                                                    <option value={{$activity->custom_activities}}>
                                                    @endforeach
                                                @endif                                            
                                            </datalist>
                                        </div>
                                        <div class="col-2">
                                            <button id="addLearningActivityBtn" type="submit" class="btn btn-primary col">Add</button>
                                        </div>
                                    </div>
                                </form>
                                <div class="row justify-content-center">
                                    <div class="col-8">
                                        <hr>
                                    </div>
                                </div> 
                                <div class="row m-1">
                                    <table id="addLearningActivitiesTbl" class="table table-light table-borderless">
                                        <thead>
                                            <tr class="table-primary">
                                                <th>Teaching and Learning Activity</th>
                                                <th class="text-center">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($l_activities as $index => $l_activity)
                                            <tr>
                                                <td>
                                                    <input list="learningActivitiesOptions" id="l_activity{{$l_activity->l_activity_id}}" type="text" class="form-control"
                                                    name="current_l_activities[{{$l_activity->l_activity_id}}]" value = "{{$l_activity->l_activity}}" placeholder="Choose from the dropdown list or type your own" form="saveLearningActivityChanges" required spellcheck="true" style="white-space: pre">
                                                </td>
                                                <td class="text-center">
                                                    <i class="bi bi-x-circle-fill text-danger fs-4 btn" onclick="deleteLearningActivity(this)"></i>
                                                </td>
                                            </tr>
                                            @endforeach                                               
                                        </tbody>
                                    </table>                                    
                                </div>
                            </div>
                            <form method="POST" id="saveLearningActivityChanges" action="{{ action('LearningActivityController@store') }}">
                                @csrf
                                <div class="modal-footer">
                                    <input type="hidden" name="course_id" value="{{$course->course_id}}" form="saveLearningActivityChanges">
                                    <button id="cancel" type="button" class="btn btn-secondary col-3" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-success btn col-3">Save Changes</button>
                                </div>
                            </form>    
                        </div>
                    </div>
                </div>
                <!-- End of add student assessment methods modal -->

                <div class="card-body">

                <div class="row">
                    <div class="col">
                        <h6 class="card-subtitle mb-4 lh-lg">
                            Input all teaching and learning activities or <a target="_blank" rel="noopener noreferrer" href="https://teaching.cornell.edu/teaching-resources/teaching-cornell-guide/instructional-strategies"><i class="bi bi-box-arrow-up-right"></i> instructional strategies</a> of the course individually. For increased accessibility and enhanced student participation, while still offering challenging learning opportunities,
                            use there <a target="_blank" rel="noopener noreferrer" href="https://udlguidelines.cast.org/binaries/content/assets/udlguidelines/udlg-v2-2/udlg_graphicorganizer_v2-2_numbers-no.pdf"><i class="bi bi-box-arrow-up-right"></i> Universal Design for Learning Guildlines</a>
                            (Offered by CAST) to design your course. You may also use <a target="_blank" rel="noopener noreferrer" href="https://udlguidelines.cast.org/binaries/content/assets/common/publications/articles/cast-udl-planningq-a11y.pdf"><i class="bi bi-box-arrow-up-right"></i> these key questions to guide</a> you.               
                        </h6>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-1">
                        <button type="button" class="btn btn-primary col-3 float-right bg-primary text-white fs-5"  data-bs-toggle="modal" data-bs-target="#addLearningActivitiesModal">
                            <i class="bi bi-plus mr-2"></i>Learning Activities
                        </button>
                    </div>
                </div>

                    <div id="admins">
                        <div class="row">
                            <div class="col">
                                <table class="table table-light table-bordered" id="l_activity_table">
                                    <tr class="table-primary">
                                        <th class="text-center">#</th>
                                        <th>Teaching and Learning Activities</th>
                                        <th class="text-center w-25">Actions</th>
                                    </tr>

                                    @if(count($l_activities)<1)
                                        <tr>
                                            <td colspan="3">
                                                <div class="alert alert-warning wizard">
                                                    <i class="bi bi-exclamation-circle-fill"></i>There are no teaching and learning activities set for this course.                    
                                                </div>
                                            </td>
                                        </tr>
                                    @else
                                        @foreach($l_activities as $index => $l_activity)
                                            <tr>
                                                <td class="text-center fw-bold" style="width:5%" >{{$index+1}}</td>                                                
                                                <td>
                                                    {{$l_activity->l_activity}}
                                                </td>
                                                <td class="text-center align-middle">
                                                    <form action="{{route('la.destroy', $l_activity->l_activity_id)}}" method="POST" >
                                                        <button type="button" style="width:60px;" class="btn btn-secondary btn-sm m-1" data-bs-toggle="modal" data-bs-target="#addLearningActivitiesModal">
                                                            Edit
                                                        </button>
                                                        @csrf
                                                        {{method_field('DELETE')}}
                                                        <input type="hidden" name="course_id" value="{{$course->course_id}}">
                                                        <button type="submit" style="width:60px;" class="btn btn-danger btn-sm m-1">Delete</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach

                                    @endif
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- card footer -->
                <div class="card-footer">
                    <div class="card-body mb-4">
                        <a href="{{route('courseWizard.step2', $course->course_id)}}">
                            <button class="btn btn-sm btn-primary col-3 float-left"><i class="bi bi-arrow-left mr-2"></i> Student Assessment Methods</button>
                        </a>
                        <a href="{{route('courseWizard.step4', $course->course_id)}}">
                            <button class="btn btn-sm btn-primary col-3 float-right">Course Alignment <i class="bi bi-arrow-right ml-2"></i></button>
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

      $('#addLearningActivitiesForm').submit(function (event) {
            // prevent default form submission handling
            event.preventDefault();
            event.stopPropagation();
            // check if input fields contain data
            if ($('#learningActivity').val().length != 0) {
                addLearningActivity();
                // reset form 
                $(this).trigger('reset');
                $(this).removeClass('was-validated');
            } else {
                // mark form as validated
                $(this).addClass('was-validated');
            }
            // readjust modal's position 
            document.querySelector('#addLearningActivitiesModal').handleUpdate();

        });

        $('#cancel').click(function(event) {
            $('#addLearningActivitiesTbl tbody').html(`
                @foreach($l_activities as $index => $l_activity)
                    <tr>
                        <td>
                            <input list="learningActivitiesOptions" id="l_activity{{$l_activity->l_activity_id}}" type="text" class="form-control" name="current_l_activities[{{$l_activity->l_activity_id}}]" value = "{{$l_activity->l_activity}}" placeholder="Choose from the dropdown list or type your own" form="saveLearningActivityChanges" required spellcheck="true" style="white-space: pre">
                        </td>
                        <td class="text-center">
                            <i class="bi bi-x-circle-fill text-danger fs-4 btn" onclick="deleteLearningActivity(this)"></i>
                        </td>
                    </tr>
                @endforeach                                               
            `);
        });
    });

    function deleteLearningActivity(submitter) {
        $(submitter).parents('tr').remove();
    }

    function addLearningActivity() {
        // prepend assessment method to the table
        $('#addLearningActivitiesTbl tbody').prepend(`
            <tr>
                <td>
                    <input list="learningActivitiesOptions" type="text" class="form-control" name="new_l_activities[]" value = "${$('#learningActivity').val()}" placeholder="Choose from the dropdown list or type your own" form="saveLearningActivityChanges" required spellcheck="true" style="white-space: pre">
                </td>
                <td class="text-center">
                    <i class="bi bi-x-circle-fill text-danger fs-4 btn" onclick="deleteLearningActivity(this)"></i>
                </td>
            </tr>
        `);
    }


    //  Finds all custom user learning activites
    function filterCustom(){
        var custom = [];

        var inputArray = $('input[name^="l_activity[]"]').map(function(idx,elem){
            return $(elem).val();
        }).get();

        var datalist = $('datalist[name^="l_activities"]:first option').map(function(idx,elem){
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
        var datalist = $('datalist[name^="l_activities"]:first option').map(function(idx,elem){
            return $(elem).val();
        }).get();

        var sortedDropdown = [];
        var sortedDatalist = sort(datalist);
        for(var i =0, n = sortedDatalist.length;i<n;i++){
            sortedDropdown.push("<option value='" + sortedDatalist[i] + "'>")
        }

        var rowCount = $('#l_activity_table tr').length - 1;
        sortedDropdown.join();

        for(var i = 0;i<rowCount;i++) {
            var datalist = $("#l_activities" + i);
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
@endsection
