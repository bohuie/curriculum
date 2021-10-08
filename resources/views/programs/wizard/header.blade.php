<div class="mt-4 mb-5">
    <div class="row">
        <div class="col">
            <h3>Program Project: {{$program->program}}</h3>
            <h5 class="text-muted">{{$program->faculty}}</h5>
            <h5 class="text-muted">{{$program->department}}</h5>
            <h5 class="text-muted">{{$program->level}}</h5>
        </div>
        <div class="col">
        @if (!$isEditor && !$isViewer) 
            <div class="row my-2">
                <div class="col">
                    <button type="button" style="width:200px" class="btn btn-success btn-sm float-right" data-toggle="modal" data-target="#duplicateConfirmation">Duplicate Program</button>
                    <!-- Delete Confirmation Modal -->
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
            <div class="row">
                <div class="col">
                        <!-- Edit button -->
                        <button type="button" style="width:200px" class="btn btn-secondary btn-sm float-right" data-toggle="modal" data-target="#editInfoModal">
                            Edit Program Information
                        </button>
                        <!-- Modal -->
                        <div class="modal fade" id="editInfoModal" tabindex="-1" role="dialog" aria-labelledby="editInfoModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editInfoModalLabel">Edit Program Information</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>

                                        <form method="POST" action="{{ action('ProgramController@update', $program->program_id) }}">
                                            @csrf
                                            {{method_field('PUT')}}
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
                                                    <label for="faculty" class="col-md-2 col-form-label text-md-right">Faculty</label>

                                                    <div class="col-md-8">
                                                        <select id='faculty' class="custom-select" name="faculty" required>
                                                            @for($i =0; $i<count($faculties) ; $i++)
                                                                @if($faculties[$i]==$program->faculty)
                                                                    <option value="{{$program->faculty}}" selected>{{$program->faculty}}</option>
                                                                @else
                                                                    <option value="{{$faculties[$i]}}">{{$faculties[$i]}} </option>
                                                                @endif
                                                            @endfor
                                                        </select>

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
                                                        <select id='department' class="custom-select" name="department" required>
                                                            @for($i =0; $i<count($departments) ; $i++)
                                                                @if($departments[$i]==$program->department)
                                                                    <option value="{{$program->department}}" selected>{{$program->department}}</option>
                                                                @else
                                                                    <option value="{{$departments[$i]}}">{{$departments[$i]}}</option>
                                                                @endif
                                                            @endfor
                                                        </select>

                                                        @error('department')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label for="Level" class="col-md-2 col-form-label text-md-right">Level</label>
                                                    <div class="col-md-6">
                                                        @for($i =0; $i<3 ; $i++)
                                                            @if($levels[$i]==$program->level)
                                                                <div class="form-check ">
                                                                    <label class="form-check-label">
                                                                        <input type="radio" class="form-check-input" name="level" value="{{$levels[$i]}}" checked>
                                                                        {{$levels[$i]}}
                                                                    </label>
                                                                </div>
                                                            @else
                                                                <div class="form-check ">
                                                                    <label class="form-check-label">
                                                                        <input type="radio" class="form-check-input" name="level" value="{{$levels[$i]}}">
                                                                        {{$levels[$i]}}
                                                                    </label>
                                                                </div>
                                                            @endif
                                                        @endfor
                                                    </div>
                                                </div>

                                                <input type="hidden" class="form-check-input" name="user_id" value={{$user->id}}>

                                            </div>

                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary col-2 btn-sm" data-dismiss="modal">Close</button>
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
