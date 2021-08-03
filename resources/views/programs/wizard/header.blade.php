<div class="mt-2 mb-5">
    <div class="row">
        <div class="col">
            <h3>Program Project: {{$program->program}}</h3>
            <h5 class="text-muted">{{$program->faculty}}</h5>
            <h5 class="text-muted">{{$program->department}}</h5>
            <h5 class="text-muted">{{$program->level}}</h5>
        </div>
        <div class="col">
        @if (!$isEditor && !$isViewer) 
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
                        <button type="button" class="btn btn-outline-primary btn-sm float-right" style="width:200px" data-toggle="modal" data-target="#addCollaboratorModal">Add Collaborators</button>
                        <!-- Add Collaborator Modal -->
                        <div class="modal fade" id="addCollaboratorModal" tabindex="-1" role="dialog" aria-labelledby="addCollaboratorModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="addCollaboratorModalLabel">Assign Collaborator to Program: {{$program->program}}</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="card-body">
                                        <p class="form-text text-muted">Collaborators can see and edit the course. Collaborators must first register with this web application to be added to a course.
                                            By adding a collaborator, a verification email will be sent to their email address.
                                            </p>
                                        <table class="table table-borderless;" style="margin: auto;">
                                                @if(count($programUsers)===1)
                                                    <tr class="table-active">
                                                        <th colspan="2">You have not added any collaborators to this course
                                                        </th>
                                                    </tr>
                                                @else
                                                    <tr class="table-active">
                                                        <th class="text-left" colspan="2">Collaborators</th>
                                                    </tr>
                                                    @foreach($programUsers as $admin)
                                                        @if($admin->email != $user->email)
                                                            <tr>
                                                                <td>{{$admin->email}}</td>
                                                                <td style="float: right;">
                                                                    <form action="{{ route('programUser.destroy') }}" method="POST" class="float-left">
                                                                        @csrf
                                                                        {{method_field('DELETE')}}
                                                                        <input type="hidden" class="form-check-input" name="program_id" value="{{$admin->program_id}}">
                                                                        <input type="hidden" class="form-check-input" name="user_id" value="{{$admin->user_id}}">
                                                                        <button type="submit" class="btn btn-danger btn-sm">Unassign</button>
                                                                    </form>
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    @endforeach
                                                @endif
                                        </table>
                                    </div>
                                    <form method="POST" action="{{ action('ProgramUserController@store') }}">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="form-group row">
                                                <label for="email" class="col-md-3 col-form-label text-md-right">Collaborator Email</label>
                                                <div class="col-md-7">
                                                    <input id="email" type="email" class="form-control @error('program') is-invalid @enderror" name="email" required autofocus>
                                                    @error('email')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <input type="hidden" class="form-check-input" name="program_id" value={{$program->program_id}}>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary col-2 btn-sm" data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary col-2 btn-sm">Add</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
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
