<!-- start of add/edit syllabus collaborators modal -->
<?php $syllabusPermission = $user->syllabi->where('id', $syllabus->id)->first(); ?>
<div id="addSyllabusCollaboratorsModal{{$syllabus->id}}" class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="addSyllabusCollaboratorsModalLabel{{$syllabus->id}}" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addSyllabusCollaboratorsModalLabel{{$syllabus->id}}"><i class="bi bi-person-plus-fill"></i> Share this syllabus with others</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="form-text text-muted mb-4">
                    <p>Give others access to this syllabus and assign them roles.</p>
                    <li class="mb-1 mr-4 ml-4"><b>Editors</b> have access to edit and view your syllabus but cannot delete your syllabus or add/remove collaborators.</li>
                    <li class="mb-3 mr-4 ml-4"><b>Viewers</b> have access to view your syllabus but cannot edit or delete your syllabus or add/remove collaborators.</li>
                </div>

                @if ($syllabusPermission->pivot->permission == 1)
                    <form class="addSyllabusCollabForm needs-validation" novalidate data-syllabus_id="{{$syllabus->id}}">
                        @csrf
                        <div class="row m-2 position-relative">
                            <div class="col-6">
                                <input id="syllabus_collab_email{{$syllabus->id}}" type="email" name="email" class="form-control" placeholder="john.doe@ubc.ca" aria-label="email" required>
                                <div class="invalid-tooltip">
                                    Please provide a valid email ending with ubc.ca.
                                </div> 
                            </div>
                            <div class="col-3">
                                <select class="form-select" id="syllabus_collab_permission{{$syllabus->id}}" name="permission">
                                    <option value="edit" selected>Editor</option>
                                    <option value="view">Viewer</option>
                                </select>                   
                            </div>
                            <div class="col-3">
                                <button id="addSyllabusCollabBtn{{$syllabus->id}}" type="submit" class="btn btn-primary col"><i class="bi bi-plus"></i> Collaborator</button>
                            </div>
                        </div>
                    </form>
                @endif

                <div class="row justify-content-center">
                    <div class="col-8">
                        <hr>
                    </div>
                </div> 

                @if ($syllabus->users->count() < 1)
                    <div class="alert alert-warning wizard">
                        <i class="bi bi-exclamation-circle-fill"></i>You have not added any collaborators to this syllabus yet.                    
                    </div>
                @else
                    <table id="addSyllabusCollabsTbl{{$syllabus->id}}" class="table table-light borderless" >
                        <thead>
                            <tr class="table-primary">
                                <th>Collaborators</th>
                                <th></th>
                                <th colspan="2" class="text-center w-25">Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($syllabus->users as $syllabusCollaborator)
                            <tr>
                                <td class="align-middle">
                                    <b>{{$syllabusCollaborator->name}} @if ($syllabusCollaborator->email == $user->email) (Me) @endif</b>
                                    <p>{{$syllabusCollaborator->email}}</p>
                                </td>
                                @if ($syllabusCollaborator->pivot->permission == 1)
                                <td class="text-center">
                                    <input form="saveSyllabusCollabChanges{{$syllabus->id}}" class="form-control fw-bold" type="text" readonly value="Owner">
                                </td>
                                <td colspan="2"></td>
                                @else
                                    @if ($syllabusPermission->pivot->permission == 1)
                                        <td class="align-middle">
                                            <select id="syllabus_collab_permission{{$syllabus->id}}-{{$syllabusCollaborator->id}}" form="saveSyllabusCollabChanges{{$syllabus->id}}" name="syllabus_current_permissions[{{$syllabusCollaborator->id}}]" class="form-select" required>
                                                <option value="edit" @if ($syllabusCollaborator->pivot->permission == 2) selected @endif>Editor</option>
                                                <option value="view" @if ($syllabusCollaborator->pivot->permission == 3) selected @endif>Viewer</option>
                                            </select>
                                        </td>
                                    @else
                                        <td class="align-middle">
                                            <select id="syllabus_collab_permission{{$syllabus->id}}-{{$syllabusCollaborator->id}}" form="saveSyllabusCollabChanges{{$syllabus->id}}" name="syllabus_current_permissions[{{$syllabusCollaborator->id}}]" class="form-select" disabled required>
                                                <option value="edit" @if ($syllabusCollaborator->pivot->permission == 2) selected @endif>Editor</option>
                                                <option value="view" @if ($syllabusCollaborator->pivot->permission == 3) selected @endif>Viewer</option>
                                            </select>
                                        </td>
                                    @endif
                                    @if ($syllabusCollaborator->email == $user->email)
                                        <td class="text-center align-middle" colspan="2">
                                            <button type="button" class="btn btn-danger btn" data-toggle="modal" data-target="#leaveSyllabusConfirmation{{$syllabus->id}}">Leave</button>
                                        </td>

                                        <!-- Leave Confirmation Modal -->
                                        <div class="modal fade" id="leaveSyllabusConfirmation{{$syllabus->id}}" tabindex="-1" role="dialog" aria-labelledby="leaveSyllabusConfirmation{{$syllabus->id}}" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Leave Syllabus Confirmation</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">Are you sure you want to leave {{$syllabus->course_title}} syllabus?</div>
                                                    <form action="{{ action([\App\Http\Controllers\SyllabusUserController::class, 'leave']) }}" class="float-right">
                                                        @csrf

                                                        <input type="hidden" class="form-check-input " name="syllabus_id" value={{$syllabus->id}}>
                                                        <input type="hidden" class="form-check-input " name="syllabusCollaboratorId" value={{$syllabusCollaborator->id}}>
                                                        <div class="modal-footer">
                                                            <button style="width:60px" type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
                                                            <button style="width:60px" type="submit" class="btn btn-danger btn-sm">Leave</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        @if ($syllabusPermission->pivot->permission == 1)
                                            <td class="text-center align-middle">
                                                <button type="input" class="btn btn-danger btn" onclick="deleteSyllabusCollab(this)">Remove</button>
                                            </td>

                                            <td class="text-center align-middle">
                                                <button type="input" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#transferSyllabusConfirmation{{$syllabus->id}}">Transfer Ownership</button>
                                            </td>

                                            
                                            <div class="modal fade" id="transferSyllabusConfirmation{{$syllabus->id}}" tabindex="-1" role="dialog" aria-labelledby="transferSyllabusConfirmation{{$syllabus->id}}" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Transfer Syllabus Confirmation</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">Are you sure you want to give ownership of the syllabus: {{$syllabus->course_title}} to the user: {{$syllabusCollaborator->name}}?</div>
                                                        <form action="{{ action([\App\Http\Controllers\SyllabusUserController::class, 'transferOwnership']) }}">
                                                            @csrf
                                                            <input type="hidden" class="form-check-input " name="syllabus_id" value={{$syllabus->id}}>
                                                            <input type="hidden" class="form-check-input " name="newOwnerId" value={{$syllabusCollaborator->id}}>
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

            <form method="POST" id="saveSyllabusCollabChanges{{$syllabus->id}}" action="{{ action([\App\Http\Controllers\SyllabusUserController::class, 'store'], ['syllabusId' => $syllabus->id]) }}">
                @csrf
                <div class="modal-footer">
                    <button type="button" class="cancelSyllabusCollabChanges btn btn-secondary col-3" data-bs-dismiss="modal" data-syllabus_id="{{$syllabus->id}}">Cancel</button>
                    <button type="submit" class="btn btn-success btn col-3" >Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End of syllabus collaborator modal -->

<script>

    $(document).ready(function () {
        
        $('.addSyllabusCollabForm').submit(function (event) {
            // get the syllabus ID
            var syllabusId = event.currentTarget.dataset.syllabus_id;
            // prevent default form submission handling
            event.preventDefault();
            event.stopPropagation();
            // check if input fields contain data
            var email = $('#syllabus_collab_email' + syllabusId);
            if (isEmailValid(email.val())
                // && email.val().endsWith('@ubc.ca')
            ) {
                addSyllabusCollab(syllabusId);
                // reset form 
                $(this).trigger('reset');
                $(this).removeClass('was-validated');
            } else {
                // mark form as validated
                $(this).addClass('was-validated');
            }
            // readjust modal's position/height
            document.querySelector('#addSyllabusCollaboratorsModal' + syllabusId).handleUpdate();

        });

        $('.cancelSyllabusCollabChanges').click(function(event) {
            location.reload();
            // var syllabusId = event.currentTarget.dataset.syllabus_id;

            // $('#addSyllabusCollabsTbl' + syllabusId + ' tbody').html(`
            //     @foreach($syllabus->users as $syllabusCollaborator)
            //         <tr>
            //             <td>
            //                 <b>{{$syllabusCollaborator->name}} @if ($syllabusCollaborator->email == $user->email) (Me) @endif</b>
            //                 <p>{{$syllabusCollaborator->email}}</p>                        
            //             </td>
                        
            //             @if ($syllabusCollaborator->pivot->permission == 1)
            //                 <td class="text-center">
            //                     <input value="Owner" form="saveSyllabusCollabChanges{{$syllabus->id}}" class="form-control fw-bold" type="text" readonly>
            //                 </td>
            //                 <td></td>
            //             @else
            //                 <td >
            //                     <select id="syllabus_collab_permission{{$syllabus->id}}-{{$syllabusCollaborator->id}}" form="saveSyllabusCollabChanges{{$syllabus->id}}" name="syllabus_current_permissions[{{$syllabusCollaborator->id}}]" class="form-select" required>
            //                         <option value="Edit" @if ($syllabusCollaborator->pivot->permission == 2) selected @endif>Editor</option>
            //                         <option value="View" @if ($syllabusCollaborator->pivot->permission == 3) selected @endif>Viewer</option>
            //                     </select>
            //                 </td>
            //                 <td class="text-center">
            //                     <button type="input" class="btn btn-danger btn" onclick="deleteSyllabusCollab(this)">Remove</button>
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

    function deleteSyllabusCollab(submitter) {
        $(submitter).parents('tr')[0].remove();
    }

    function addSyllabusCollab(syllabusId) {
        // prepend assessment method to the table
        $('#addSyllabusCollabsTbl' + syllabusId + ' tbody').prepend(`
            <tr>
                <td>
                    <input type="text" class="form-control " name="syllabus_new_collabs[]" value = "${$('#syllabus_collab_email' + syllabusId).val()}" placeholder="E.g. john.doe@ubc.ca" form="saveSyllabusCollabChanges${syllabusId}" required>
                </td>
                <td>
                    <select form="saveSyllabusCollabChanges${syllabusId}" name="syllabus_new_permissions[]" class="form-select" required>
                        <option value="edit" ${($('#syllabus_collab_permission' + syllabusId).val() == 'edit') ? 'selected' : ''}>Editor</option>
                        <option value="view" ${($('#syllabus_collab_permission' + syllabusId).val() == 'view') ? 'selected' : ''}>Viewer</option>
                    </select>
                </td> 
            
                <td class="text-center">
                    <button type="input" class="btn btn-danger" onclick="deleteSyllabusCollab(this)">Remove</button>
                </td>
            </tr>
        `);
    }
</script>
