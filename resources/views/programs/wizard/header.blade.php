
<div class="mt-4 mb-5">
    <div class="row">
        <div class="col">
            <h3>Program Project: {{$program->program}}</h3>
            <h5 class="text-muted">{{$program->faculty}}</h5>
            <h5 class="text-muted">{{$program->department}}</h5>
            <h5 class="text-muted">{{$program->level}}</h5>
        </div>
        <div class="col">
        @if (!$isViewer)
            <div class="row my-2">
                <div class="col">
                    <button type="button" style="width:200px" class="btn btn-success btn-sm float-right" data-toggle="modal" data-target="#duplicateConfirmation">Duplicate Program</button>
                    <!-- Duplicate Confirmation Modal -->
                    <div class="modal fade" id="duplicateConfirmation" tabindex="-1" role="dialog" aria-labelledby="duplicateConfirmation" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Duplicate Program</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>

                                <form action="{{ route('programs.duplicate', $program->program_id) }}" method="GET">
                                    @csrf
                                    {{method_field('GET')}}

                                    <div class="modal-body">

                                        <div class="form-group row">
                                            <label for="program" class="col-md-2 col-form-label text-md-right">Program Name</label>
                                            <div class="col-md-8">
                                                <input id="program" type="text" class="form-control @error('program') is-invalid @enderror" name="program" value="{{$program->program}} - Copy" required autofocus>
                                                @error('program')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        
                                    </div>

                                    <div class="modal-footer">
                                        <button style="width:60px" type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
                                        <button style="width:80px" type="submit" class="btn btn-success btn-sm">Duplicate</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        @if (!$isEditor && !$isViewer) 
            <div class="row">
                <div class="col">
                        <!-- Edit button -->
                        <button type="button" style="width:200px" class="btn btn-secondary btn-sm float-right" data-toggle="modal" data-target="#editInfoModal" onclick="fillInformation()">
                            Edit Program Information
                        </button>
                        <!-- Modal -->
                        <div class="modal fade" data-keyboard="false" data-backdrop="static" id="editInfoModal" tabindex="-1" role="dialog" aria-labelledby="editInfoModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editInfoModalLabel">Edit Program Information</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        
                                        <form method="POST" action="{{route('programs.update', $program->program_id)}}">
                                            @csrf
                                            {{method_field('POST')}}
                                            <div class="modal-body">
                                                <div class="form-group row">
                                                    <label for="program" class="col-md-2 col-form-label text-md-right">Program Name</label>

                                                    <div class="col-md-8">
                                                        <input id="program" type="text" class="form-control @error('program') is-invalid @enderror" name="program" value="{{$program->program}}" required autofocus>

                                                        @error('program')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label for="campus" class="col-md-2 col-form-label text-md-right">Campus</label>

                                                    <div class="col-md-8">
                                                        <select id='campus' class="custom-select" name="campus">
                                                            
                                                        </select>
                                                        <input id='campus-text' class="form-control" name="campus" type="text" placeholder="(Optional) Enter the Campus" disabled hidden></input>
                                                        @error('faculty')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label for="faculty" class="col-md-2 col-form-label text-md-right">Faculty/School</label>
                                                    <div class="col-md-8">
                                                        <select id='faculty' class="custom-select" name="faculty">
                                                            <!-- @for($i =0; $i<count($faculties) ; $i++)
                                                                @if($faculties[$i]==$program->faculty)
                                                                    <option value="{{$program->faculty}}" selected>{{$program->faculty}}</option>
                                                                @else
                                                                    <option value="{{$faculties[$i]}}">{{$faculties[$i]}} </option>
                                                                @endif
                                                            @endfor -->
                                                        </select>
                                                        <input id='faculty-text' class="form-control" name="faculty" type="text" placeholder="(Optional) Enter the faculty/School" disabled hidden></input>
                                                        @error('faculty')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label for="department" class="col-md-2 col-form-label text-md-right">Department</label>
                                                    <div class="col-md-8">
                                                        <select id='department' class="custom-select" name="department">
                                                        </select>
                                                        <input id='department-text' class="form-control" name="department" type="text" placeholder="(Optional) Enter the department" disabled hidden></input>
                                                        @error('department')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label for="level" class="col-md-2 col-form-label text-md-right"><span class="requiredField">* </span>Level</label>
                                                    <div class="col-md-8">
                                                        <div class="form-check ">
                                                            <label class="form-check-label">
                                                            @if ($program->level == "Undergraduate" || $program->level == "Bachelors") 
                                                            <input type="radio" class="form-check-input" name="level" value="Bachelors" checked>
                                                                Bachelors
                                                            @else
                                                                <input type="radio" class="form-check-input" name="level" value="Bachelors">
                                                                    Bachelors
                                                            @endif
                                                            </label>
                                                        </div>
                                                        <div class="form-check">
                                                            <label class="form-check-label">
                                                            @if ($program->level == "Masters") 
                                                                <input type="radio" class="form-check-input" name="level" value="Masters" checked>
                                                                Masters
                                                            @else
                                                                <input type="radio" class="form-check-input" name="level" value="Masters">
                                                                    Masters
                                                            @endif
                                                            </label>
                                                        </div>
                                                        <div class="form-check">
                                                            <label class="form-check-label">
                                                                @if ($program->level == "Doctoral")
                                                                    <input type="radio" class="form-check-input" name="level" value="Doctoral" checked>
                                                                    Doctoral
                                                                @else
                                                                    <input type="radio" class="form-check-input" name="level" value="Doctoral">
                                                                        Doctoral
                                                                @endif
                                                            </label>
                                                        </div>
                                                        <div class="form-check">
                                                            <label class="form-check-label">
                                                                @if ($program->level == "Other") 
                                                                    <input type="radio" class="form-check-input" name="level" value="Other" checked>
                                                                    Other
                                                                @else
                                                                <input type="radio" class="form-check-input" name="level" value="Other">
                                                                    Other
                                                                @endif
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>

                                                <input type="hidden" class="form-check-input" name="user_id" value={{$user->id}}>

                                            </div>

                                            <div class="modal-footer">
                                                <button type="button" id="cancel" class="btn btn-secondary col-2 btn-sm" data-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-primary col-2 btn-sm">Save</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                <div class="row my-2">
                    <div class="col">
                        <!-- Assign Collaborator button  -->
                        <button type="button" class="btn btn-outline-primary btn-sm float-right" style="width:200px" data-bs-toggle="modal" data-bs-target="#addProgramCollaboratorsModal{{$program->program_id}}">Add Collaborators</button>
                        <!-- Program Collaborators Modal -->  
                        @include('programs.programCollabs')
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <button type="button" style="width:200px" class="btn btn-danger btn-sm float-right"
                        data-toggle="modal" data-target="#deleteConfirmation">Delete Entire Program</button>
                        <!-- Delete Confirmation Modal -->
                        <div class="modal fade" id="deleteConfirmation" tabindex="-1" role="dialog" aria-labelledby="deleteConfirmation" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Delete Confirmation</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                    Are you sure you want to delete {{$program->program}} program ?
                                    </div>
                                    <form action="{{route('programs.destroy', $program->program_id)}}" method="POST" class="float-right">
                                        @csrf
                                        {{method_field('DELETE')}}
                                        <div class="modal-footer">
                                        <button style="width:60px" type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
                                        <button style="width:60px" type="submit" class="btn btn-danger btn-sm">Delete</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

    </div>
    @if (! $isViewer) 
    <!-- progress bar -->
    <div class="mt-5">
        <table class="table table-borderless text-center table-sm" style="table-layout: fixed; width: 100%">
            <tr>
                <td><a class="btn @if (Route::current()->getName() == 'programWizard.step1') btn-primary @else @if ($ploCount < 1) btn-secondary @else btn-success @endif @endif" href="{{route('programWizard.step1', $program->program_id)}}" style="width: 30px; height: 30px; padding: 6px 0px; border-radius: 15px; text-align: center; font-size: 12px; line-height: 1.42857;"> <b>1</b> </a></td>
                <td><a class="btn @if (Route::current()->getName() == 'programWizard.step2') btn-primary @else @if ($msCount < 1) btn-secondary @else btn-success @endif @endif" href="{{route('programWizard.step2', $program->program_id)}}" style="width: 30px; height: 30px; padding: 6px 0px; border-radius: 15px; text-align: center; font-size: 12px; line-height: 1.42857;"> <b>2</b> </a></td>
                <td><a class="btn @if (Route::current()->getName() == 'programWizard.step3') btn-primary @else @if ($courseCount < 1) btn-secondary @else btn-success @endif @endif" href="{{route('programWizard.step3', $program->program_id)}}" style="width: 30px; height: 30px; padding: 6px 0px; border-radius: 15px; text-align: center; font-size: 12px; line-height: 1.42857;"> <b>3</b> </a></td>
                <td><a class="btn @if (Route::current()->getName() == 'programWizard.step4') btn-primary @else btn-secondary @endif" href="{{route('programWizard.step4', $program->program_id)}}" style="width: 30px; height: 30px; padding: 6px 0px; border-radius: 15px; text-align: center; font-size: 12px; line-height: 1.42857;"> <b>4</b> </a></td>
            </tr>
            <tr>
                <td>Program Learning Outcomes</td>
                <td>Mapping Scale</td>
                <td>Courses</td>
                <td>Program Overview</td>
            </tr>
        </table>
    </div>
    @endif

</div>

<script>
    var program = {!! json_encode($program, JSON_HEX_TAG) !!};
    var campuses = {!! json_encode($campuses, JSON_HEX_TAG) !!};
    var faculties = {!! json_encode($faculties, JSON_HEX_TAG) !!};
    var departments = {!! json_encode($departments, JSON_HEX_TAG) !!};
    var vFaculties = faculties.filter(item => {
        return item.campus_id === 1;
    });
    var oFaculties = faculties.filter(item => {
        return item.campus_id === 2;
    });

    function appendCampuses() {
        // empty options
        $('#campus').empty();
        // append all campuses
        campuses.forEach(function(campus) {
            $('#campus').append('<option name="'+campus.campus_id+'" value="'+campus.campus+'">'+campus.campus+'</option>');
        });
        $('#campus').append('<option name="-1" value="Other">Other</option>');
        // select campus option
        $('#campus').val(program.campus).change();
    }

    function appendFaculties() {
        // empty options
        $('#faculty').empty();
        // get faculties for campus_id
        var campusId = parseInt($('#campus').find(':selected').attr('name'));
        var filteredFaculties =  faculties.filter(item => {
            return item.campus_id === campusId;
        });
        // append all filtered faculties
        filteredFaculties.forEach(function(faculty) {
            $('#faculty').append('<option name="'+faculty.faculty_id+'" value="'+faculty.faculty+'">'+faculty.faculty+'</option>');
        });
        $('#faculty').append('<option name="-1" value="Other">Other</option>');
        // select faculty value
        $('#faculty').val(program.faculty).change();
    }

    function appendDepartments() {
        // empty options
        $('#department').empty();
        // get faculties for campus_id
        var facultyId = parseInt($('#faculty').find(':selected').attr('name'));
        var filteredDepartments =  departments.filter(item => {
            return item.faculty_id === facultyId;
        });
        // append all filtered faculties
        filteredDepartments.forEach(function(department) {
            $('#department').append('<option name="'+department.department_id+'" value="'+department.department+'">'+department.department+'</option>');
        });
        $('#department').append('<option name="-1" value="Other">Other</option>');
        // select faculty value
        $('#department').val(program.department).change();
    }

    appendCampuses();
    appendFaculties();
    appendDepartments();

    function fillInformation() {

        if (!(campuses.every(e => {
            if (e.campus === program.campus) {
                return false;
            } else {
                return true;
            }
        }))) {
            // search for faculty
            if (!(faculties.every(e => {
                if (e.faculty === program.faculty) {
                    return false;
                } else {
                    return true;
                }
            }))) {
                appendFaculties()

                // search for faculty
                if (!(departments.every(e => {
                    if (e.department === program.department) {
                        return false;
                    } else {
                        return true;
                    }
                }))) {
                    appendDepartments();
                } else {
                    // other department selected
                    $('#department').val('Other').change();
                    $('#department-text').prop( "hidden", false );
                    $('#department-text').prop( "disabled", false );
                    $('#department-text').val(program.department);
                }

            } else {
                // other faculty selected
                $('#faculty').val('Other').change();
                $('#faculty-text').prop( "hidden", false );
                $('#faculty-text').prop( "disabled", false );
                $('#faculty-text').val(program.faculty);
                $('#department').prop( "disabled", true );
                $('#department').text('');
                $('#department-text').prop( "hidden", false );
                $('#department-text').prop( "disabled", false );
                $('#department-text').val(program.department);
            }

        } else {
            // other campus selected
            $('#campus').val('Other').change();
            $('#campus-text').prop( "hidden", false );
            $('#campus-text').prop( "disabled", false );
            $('#campus-text').val(program.campus);
            $('#faculty').prop( "disabled", true );
            $('#faculty').text('');
            $('#faculty-text').prop( "hidden", false );
            $('#faculty-text').prop( "disabled", false );
            $('#faculty-text').val(program.faculty);
            $('#department').prop( "disabled", true );
            $('#department').text('');
            $('#department-text').prop( "hidden", false );
            $('#department-text').prop( "disabled", false );
            $('#department-text').val(program.department);
        }
    }

    $('#campus').change( function() {
        // filter faculty based on campus
        if ($('#campus').find(':selected').text() == 'Vancouver') {
            // Hide text / show select
            campusDefaultOption();
            
            //Displays Vancouver Faculties
            // delete drop down items
            $('#faculty').empty();
            // populate drop down
            $('#faculty').append($('<option disabled selected hidden>Open list of faculties/schools</option>'));
            vFaculties.forEach (faculty => $('#faculty').append($('<option name="'+faculty.faculty_id+'" />').val(faculty.faculty).text(faculty.faculty)));
            $('#faculty').append($('<option name="-1" />').val('Other').text('Other'));
            // enable the faculty select field
            if ($('#faculty').is(':disabled')) {
                $('#faculty').prop('disabled', false);
            }
            // disable the department field
            if (!($('#department').is(':disabled'))) {
                $('#department').empty();
                $('#department').append($('<option disabled selected hidden>Open list of departments</option>'));
                $('#department').prop('disabled', true);
            }
        } else if ($('#campus').find(':selected').text() == 'Okanagan') {
            // Hide text / show select
            campusDefaultOption();
            // Display Okangan Faculties
            // delete drop down items
            $('#faculty').empty();
            // populate drop down
            $('#faculty').append($('<option disabled selected hidden>Open list of faculties/schools</option>'));
            oFaculties.forEach (faculty => $('#faculty').append($('<option name="'+faculty.faculty_id+'" />').val(faculty.faculty).text(faculty.faculty)));
            $('#faculty').append($('<option name="-1" />').val('Other').text('Other'));
            // enable the faculty select field
            if ($('#faculty').is(':disabled')) {
                $('#faculty').prop('disabled', false);
            }
            // disable the department field
            if (!($('#department').is(':disabled'))) {
                $('#department').empty();
                $('#department').append($('<option disabled selected hidden>Open list of departments</option>'));
                $('#department').prop('disabled', true);
            }
        } else {
            campusOtherOption();
        }
    });

        // var departments = {!! json_encode($departments, JSON_HEX_TAG) !!};

        $('#faculty').change( function() {
            var facultyId = parseInt($('#faculty').find(':selected').attr('name'));

            // get departments by faculty if they belong to a faculty, else display all departments
            if (facultyId >= 0) {
                // Hide text / show select
                facultyDefaultOption();

                // delete drop down items
                $('#department').empty();
                // populate drop down
                $('#department').append($('<option disabled selected hidden>Open list of departments</option>'));
                var filteredDepartments = departments.filter(item => {
                    return item.faculty_id === facultyId;
                });
                filteredDepartments.forEach(department => $('#department').append($('<option />').val(department.department).text(department.department)));


                $('#department').append($('<option />').val('Other').text('Other'));

                // enable the faculty select field
                if ($('#department').is(':disabled')) {
                    $('#department').prop('disabled', false);
                }

            } else {
                // Hide text / show select
                facultyOtherOption();
            }

        });

        $('#department').change( function() { 
            if ($('#department').find(':selected').val() !== 'Other') {
                departmentDefaultOption();
            } else {
                departmentOtherOption();
            }
        });

    function departmentDefaultOption() {
        // Hide text / show select
        $('#department-text').prop( "hidden", true );
        $('#department-text').prop( "disabled", true );
    }

    function departmentOtherOption() {
        // Hide text / show select
        $('#department-text').prop( "hidden", false );
        $('#department-text').prop( "disabled", false );
    }

    function facultyDefaultOption() {
        // Hide text / show select
        $('#faculty-text').prop( "hidden", true );
        $('#faculty-text').prop( "disabled", true );
        $('#department').prop( "hidden", false );
        $('#department').prop( "disabled", false );
        $('#department-text').prop( "hidden", true );
        $('#department-text').prop( "disabled", true );
    }

    function facultyOtherOption() {
        // Hide text / show select
        $('#faculty-text').prop( "hidden", false );
        $('#faculty-text').prop( "disabled", false );
        $('#department').prop( "disabled", true );
        $('#department').prop( "hidden", true );
        $('#department').text('');
        $('#department-text').prop( "hidden", false );
        $('#department-text').prop( "disabled", false );
    }

    function campusDefaultOption() {
        // Hide text / show select
        $('#campus-text').prop( "hidden", true );
        $('#campus-text').prop( "disabled", true );
        $('#faculty').prop( "hidden", false );
        $('#faculty').prop( "disabled", false );
        $('#faculty-text').prop( "hidden", true );
        $('#faculty-text').prop( "disabled", true );
        $('#department').prop( "hidden", false );
        $('#department').prop( "disabled", false );
        $('#department-text').prop( "hidden", true );
        $('#department-text').prop( "disabled", true );
    }

    function campusOtherOption() {
        // Hide text / show select
        $('#campus-text').prop( "hidden", false );
        $('#campus-text').prop( "disabled", false );
        $('#faculty').prop( "disabled", true );
        $('#faculty').prop( "hidden", true );
        $('#faculty').text('');
        $('#faculty-text').prop( "hidden", false );
        $('#faculty-text').prop( "disabled", false );
        $('#department').prop( "disabled", true );
        $('#department').prop( "hidden", true );
        $('#department').text('');
        $('#department-text').prop( "hidden", false );
        $('#department-text').prop( "disabled", false );
    }

    // refresh page on cancel
    $('#cancel').click(function() {
        location.reload();
    });

    $(document).ready(function () {
	    //This method is used to make sure that the proper amount of characters are entered so it doesn't exceed the max character limits
        function validateMaxlength(e){
            //Whitespaces are counted as 1 but character wise are 2 (\n).
            var MAX_LENGTH = event.target.getAttribute("maxlength");
            var currentLength = event.target.value.length;
            var whiteSpace = event.target.value.split(/\n/).length;
            if((currentLength+(whiteSpace))>MAX_LENGTH)
            { 
                //Goes to MAX_LENGTH-(whiteSpace)+1 because it starts at 1
                event.target.value = event.target.value.substr(0,MAX_LENGTH-(whiteSpace)+1);	        
            }
        }
    });
</script>
