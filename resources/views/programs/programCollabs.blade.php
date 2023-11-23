<!-- start of add/edit program collaborators modal -->
<?php $programPermission = $user->programs->where('program_id', $program->program_id)->first(); ?>
<div id="addProgramCollaboratorsModal{{$program->program_id}}" class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="addProgramCollaboratorsModalLabel{{$program->program_id}}" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addProgramCollaboratorsModalLabel{{$program->program_id}}"><i class="bi bi-person-plus-fill"></i> Share this program with others</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="form-text text-muted mb-4">
                    <p>Give others access to this program and assign them roles.</p>
                    <li class="mb-1 mr-4 ml-4"><b>Editors</b> have access to edit and view your program but cannot delete your program or add/remove collaborators.</li>
                    <li class="mb-3 mr-4 ml-4"><b>Viewers</b> can view an overview of your program but cannot edit or delete your program or add/remove collaborators.</li>
                </div>

                @if ($programPermission->pivot->permission == 1)
                    <form class="addProgramCollabForm needs-validation" novalidate data-program_id="{{$program->program_id}}">
                        @csrf
                        <div class="row m-2">
                            <div class="col-6">
                                <input id="program_collab_email{{$program->program_id}}" type="email" name="email" class="form-control" placeholder="john.doe@ubc.ca" aria-label="email" required>
                                <div class="invalid-tooltip">
                                    Please provide a valid email ending with ubc.ca.
                                </div> 
                            </div>
                            <div class="col-3">
                                <select class="form-select" id="program_collab_permission{{$program->program_id}}" name="permission">
                                    <option value="edit" selected>Editor</option>
                                    <option value="view">Viewer</option>
                                </select>
                            </div>
                            <div class="col-3">
                                <button id="addProgramCollabBtn{{$program->program_id}}" type="submit" class="btn btn-primary col"><i class="bi bi-plus"></i> Collaborator</button>
                            </div>
                        </div>
                    </form>
                @endif

                <div class="row justify-content-center">
                    <div class="col-8">
                        <hr>
                    </div>
                </div> 

                @if ($program->users->count() < 1)
                    <div class="alert alert-warning wizard">
                        <i class="bi bi-exclamation-circle-fill"></i>You have not added any collaborators to this program yet.                    
                    </div>
                @else
                    <table id="addProgramCollabsTbl{{$program->program_id}}" class="table table-light borderless" >
                        <thead>
                            <tr class="table-primary">
                                <th>Collaborators</th>
                                <th></th>
                                <th colspan="2" class="text-center w-25">Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($program->users as $programCollaborator)
                                <tr>
                                    <td class="align-middle">
                                        <b>{{$programCollaborator->name}} @if ($programCollaborator->email == $user->email) (Me) @endif</b>
                                        <p>{{$programCollaborator->email}}</p>
                                    </td>
                                    @if ($programCollaborator->pivot->permission == 1)
                                        <td class="text-center align-middle">
                                            <input form="saveProgramCollabChanges{{$program->program_id}}" class="form-control fw-bold" type="text" readonly value="Owner">
                                        </td>
                                        <td colspan="2"></td>
                                    @else
                                        @if ($programPermission->pivot->permission == 1)
                                            <td class="align-middle">
                                                <select id="program_collab_permission{{$program->program_id}}-{{$programCollaborator->id}}" form="saveProgramCollabChanges{{$program->program_id}}" name="program_current_permissions[{{$programCollaborator->id}}]" class="form-select" required>
                                                    <option value="edit" @if ($programCollaborator->pivot->permission == 2) selected @endif>Editor</option>
                                                    <option value="view" @if ($programCollaborator->pivot->permission == 3) selected @endif>Viewer</option>
                                                </select>
                                            </td>
                                        @else
                                            <td class="align-middle">
                                                <select id="program_collab_permission{{$program->program_id}}-{{$programCollaborator->id}}" form="saveProgramCollabChanges{{$program->program_id}}" name="program_current_permissions[{{$programCollaborator->id}}]" class="form-select" disabled required>
                                                    <option value="edit" @if ($programCollaborator->pivot->permission == 2) selected @endif>Editor</option>
                                                    <option value="view" @if ($programCollaborator->pivot->permission == 3) selected @endif>Viewer</option>
                                                </select>
                                            </td>
                                        @endif
                                        @if ($programCollaborator->email == $user->email)
                                            <td class="text-center align-middle" colspan="2">
                                                <button type="button" class="btn btn-danger btn" data-toggle="modal" data-target="#leaveProgramConfirmation{{$program->program_id}}">Leave</button>
                                            </td>

                                            <!-- Leave Confirmation Modal -->
                                            <div class="modal fade" id="leaveProgramConfirmation{{$program->program_id}}" tabindex="-1" role="dialog" aria-labelledby="leaveProgramConfirmation{{$program->program_id}}" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Leave Program Confirmation</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">Are you sure you want to leave {{$program->program}} program?</div>
                                                        <form action="{{ action([\App\Http\Controllers\ProgramUserController::class, 'leave']) }}" class="float-right">
                                                            @csrf

                                                            <input type="hidden" class="form-check-input " name="program_id" value={{$program->program_id}}>
                                                            <input type="hidden" class="form-check-input " name="programCollaboratorId" value={{$programCollaborator->id}}>
                                                            <div class="modal-footer">
                                                                <button style="width:60px" type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
                                                                <button style="width:60px" type="submit" class="btn btn-danger btn-sm">Leave</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            @if ($programPermission->pivot->permission == 1)
                                                <td class="text-center align-middle">
                                                    <button type="input" class="btn btn-danger btn" onclick="deleteProgramCollab(this)">Remove</button>
                                                </td>

                                                <td class="text-center align-middle">
                                                    <button type="input" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#transferProgramConfirmation{{$program->program_id}}">Transfer Ownership</button>
                                                </td>

                                                <!-- Transfer Confirmation Modal -->
                                                <div class="modal fade" id="transferProgramConfirmation{{$program->program_id}}" tabindex="-1" role="dialog" aria-labelledby="transferProgramConfirmation{{$program->program_id}}" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">Transfer Program Confirmation</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">Are you sure you want to give ownership of the program: {{$program->program}} to the user: {{$programCollaborator->name}}?</div>
                                                            <form action="{{ action([\App\Http\Controllers\ProgramUserController::class, 'transferOwnership']) }}">
                                                                @csrf
                                                                <input type="hidden" class="form-check-input " name="program_id" value={{$program->program_id}}>
                                                                <input type="hidden" class="form-check-input " name="newOwnerId" value={{$programCollaborator->id}}>
                                                                <input type="hidden" class="form-check-input " name="oldOwnerId" value={{$user->id}}>

                                                                <div class="modal-footer">
                                                                    <button style="width:60px" type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
                                                                    <button type="input" class="btn btn-primary btn-sm">Transfer Ownership</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>

                                            @else
                                                <td class="text-center" colspan="2"></td>
                                            @endif
                                        @endif
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>

            <form method="POST" id="saveProgramCollabChanges{{$program->program_id}}" action="{{ action([\App\Http\Controllers\ProgramUserController::class, 'store'], ['programId' => $program->program_id]) }}">
                @csrf
                <input type="hidden" class="form-check-input " name="program_id" value={{$program->program_id}}>
                <div class="modal-footer">
                    <button type="button" class="cancelProgramCollabChanges btn btn-secondary col-3" data-bs-dismiss="modal" data-program_id="{{$program->program_id}}">Cancel</button>
                    <button type="submit" class="btn btn-success btn col-3" >Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End of program collaborator modal -->

<script>

    $(document).ready(function () {

        $('.addProgramCollabForm').submit(function (event) {
            // get program Id
            var programId = event.currentTarget.dataset.program_id;
            // prevent default form submission handling
            event.preventDefault();
            event.stopPropagation();
            // check if input fields contain data
            var email = $('#program_collab_email' + programId);
            if (isEmailValid(email.val())
                // && email.val().endsWith('@ubc.ca')
            ) {
                addProgramCollab(programId);
                // reset form 
                $(this).trigger('reset');
                $(this).removeClass('was-validated');
            } else {
                // mark form as validated
                $(this).addClass('was-validated');
            }
            // readjust modal's position/height
            document.querySelector('#addProgramCollaboratorsModal' + programId).handleUpdate();

        });

        $('.cancelProgramCollabChanges').click(function(event) {
            location.reload();
            // var programId = event.currentTarget.dataset.program_id;
            
            // $('#addProgramCollabsTbl' + programId + ' tbody').html(`
            //     @foreach($program->users as $programCollaborator)
            //         <tr>
            //             <td>
            //                 <b>{{$programCollaborator->name}} @if ($programCollaborator->email == $user->email) (Me) @endif</b>
            //                 <p>{{$programCollaborator->email}}</p>                        
            //             </td>
                        
            //             @if ($programCollaborator->pivot->permission == 1)
            //                 <td class="text-center">
            //                     <input value="Owner" form="saveProgramCollabChanges{{$program->program_id}}" class="form-control fw-bold" type="text" readonly>
            //                 </td>
            //                 <td></td>
            //             @else
            //                 <td >
            //                     <select id="program_collab_permission{{$program->program_id}}-{{$programCollaborator->id}}" form="saveProgramCollabChanges{{$program->program_id}}" name="program_current_permissions[{{$programCollaborator->id}}]" class="form-select" required>
            //                         <option value="Edit" @if ($programCollaborator->pivot->permission == 2) selected @endif>Editor</option>
            //                         <option value="View" @if ($programCollaborator->pivot->permission == 3) selected @endif>Viewer</option>
            //                     </select>
            //                 </td>
            //                 <td class="text-center">
            //                     <button type="input" class="btn btn-danger btn" onclick="deleteProgramCollab(this)">Remove</button>
            //                 </td>
            //             @endif
            //         </tr>
            //     @endforeach
            // `);
        });
    });

    function isEmailValid(email) 
    {
        // return (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/).test(email) && email.endsWith('ubc.ca');
        return (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/).test(email);
    }

    function deleteProgramCollab(submitter) {
        $(submitter).parents('tr')[0].remove();
    }

    function addProgramCollab(programId) {
        // prepend assessment method to the table
        $('#addProgramCollabsTbl' + programId + ' tbody').prepend(`
            <tr>
                <td>
                    <input type="text" class="form-control " name="program_new_collabs[]" value = "${$('#program_collab_email' + programId).val()}" placeholder="E.g. john.doe@ubc.ca" form="saveProgramCollabChanges${programId}" required>
                </td>
                <td>
                    <select form="saveProgramCollabChanges${programId}" name="program_new_permissions[]" class="form-select" required>
                        <option value="edit" ${($('#program_collab_permission' + programId).val() == 'edit') ? 'selected' : ''}>Editor</option>
                        <option value="view" ${($('#program_collab_permission' + programId).val() == 'view') ? 'selected' : ''}>Viewer</option>
                    </select>
                </td> 
            
                <td class="text-center">
                    <button type="input" class="btn btn-danger" onclick="deleteProgramCollab(this)">Remove</button>
                </td>
                <td></td>
            </tr>
        `);
    }
</script>
