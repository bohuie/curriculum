<!-- start of add/edit syllabus collaborators modal -->
<div id="addSyllabusCollaboratorsModal{{$syllabus->id}}" class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="addSyllabusCollaboratorsModalLabel{{$syllabus->id}}" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addSyllabusCollaboratorsModalLabel{{$syllabus->id}}"><i class="bi bi-person-plus-fill"></i> Share this syllabus with others</h5>
            </div>

            <div class="modal-body">
                <div class="form-text text-muted mb-4">
                    <p>Give others access to this syllabus and assign them roles.</p>
                    <li class="mb-1 mr-4 ml-4"><b>Editors</b> have access to edit and view your syllabus but cannot delete your syllabus or add/remove collaborators.</li>
                    <li class="mb-3 mr-4 ml-4"><b>Viewers</b> have access to view your syllabus but cannot edit or delete your syllabus or add/remove collaborators.</li>
                    <p class=" text-center form-text text-primary font-weight-bold">Note: Your collaborator must have registered on this site before you can add them. <a target="_blank" href="{{ url('/invite') }}">Invite others to register<i class=" pb-3 pl-1 bi bi-box-arrow-up-right"></i></a></p>                    
                </div>

                <form id="addCollabForm{{$syllabus->id}}" class="needs-validation" novalidate method="POST" action="">
                    @csrf
                    <div class="row m-2">
                        <div class="col-6">
                            <input id="collab_email{{$syllabus->id}}" type="email" pattern=".+@ubc.ca" name="email" class="form-control" placeholder="john.doe@ubc.ca" aria-label="email" required>
                            <div class="invalid-tooltip">
                                Please provide a valid email ending with ubc.ca.
                            </div> 
                        </div>
                        <div class="col-3">
                        <select class="form-select" id="collab_permission{{$syllabus->id}}" name="permission">
                            <option value="edit" selected>Editor</option>
                            <option value="view">Viewer</option>
                        </select>                                                                    
                        </div>
                        <div class="col-3">
                            <button id="addCollabBtn{{$syllabus->id}}" type="submit" class="btn btn-primary col"><i class="bi bi-plus"></i> Collaborator</button>
                        </div>
                    </div>
                </form>

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
                    <table id="addCollabsTbl{{$syllabus->id}}" class="table table-light borderless" >
                        <thead>
                            <tr class="table-primary">
                                <th>Collaborators</th>
                                <th></th>
                                <th class="text-center w-25">Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($syllabus->users as $syllabusCollaborator)
                            <tr>
                                <td>
                                    <b>{{$syllabusCollaborator->name}} @if ($syllabusCollaborator->email == $user->email) (Me) @endif</b>
                                    <p>{{$syllabusCollaborator->email}}</p>
                                </td>
                                @if ($syllabusCollaborator->pivot->permission == 1)
                                <td class="text-center">
                                    <input form="saveCollabChanges{{$syllabus->id}}" class="form-control fw-bold" type="text" readonly value="Owner">
                                </td>
                                <td></td>
                                @else
                                <td >
                                    <select id="collab_permission{{$syllabus->id}}-{{$syllabusCollaborator->id}}" form="saveCollabChanges{{$syllabus->id}}" name="current_permissions[{{$syllabusCollaborator->id}}]" class="form-select" required>
                                        <option value="edit" @if ($syllabusCollaborator->pivot->permission == 2) selected @endif>Editor</option>
                                        <option value="view" @if ($syllabusCollaborator->pivot->permission == 3) selected @endif>Viewer</option>
                                    </select>
                                </td>
                                <td class="text-center">
                                    <button type="input" class="btn btn-danger btn" onclick="deleteCollab(this)">Remove</button>
                                </td>
                                @endif
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>

            <form method="POST" id="saveCollabChanges{{$syllabus->id}}" action="{{ action('SyllabusUserController@store', ['syllabusId' => $syllabus->id]) }}">
                @csrf
                <div class="modal-footer">
                    <button id="cancel{{$syllabus->id}}" type="button" class="btn btn-secondary col-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success btn col-3" >Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End of syllabus collaborator modal -->

<script>

    $(document).ready(function () {

        var syllabus = <?php echo json_encode($syllabus);?>;

        $('#addCollabForm' + syllabus['id']).submit(function (event) {
            // prevent default form submission handling
            event.preventDefault();
            event.stopPropagation();
            // check if input fields contain data
            var email = $('#collab_email' + syllabus['id']);
            if (email.valid() 
                // && email.val().endsWith('@ubc.ca')
            ) {
                addCollab(syllabus);
                // reset form 
                $(this).trigger('reset');
                $(this).removeClass('was-validated');
            } else {
                // mark form as validated
                $(this).addClass('was-validated');
            }
            // readjust modal's position 
            document.querySelector('#addSyllabusCollaboratorsModal' + syllabus['id']).handleUpdate();

        });

        $('#cancel' + syllabus['id']).click(function(event) {
            $('#addCollabsTbl' + syllabus['id'] + ' tbody').html(`
                @foreach($syllabus->users as $syllabusCollaborator)
                    <tr>
                        <td>
                            <b>{{$syllabusCollaborator->name}} @if ($syllabusCollaborator->email == $user->email) (Me) @endif</b>
                            <p>{{$syllabusCollaborator->email}}</p>                        
                        </td>
                        
                        @if ($syllabusCollaborator->pivot->permission == 1)
                            <td class="text-center">
                                <input form="saveCollabChanges{{$syllabus->id}}" class="form-control fw-bold" type="text" readonly>
                            </td>
                            <td></td>
                        @else
                            <td >
                                <select id="collab_permission{{$syllabus->id}}-{{$syllabusCollaborator->id}}" form="saveCollabChanges{{$syllabus->id}}" name="current_permissions[{{$syllabusCollaborator->id}}]" class="form-select" required>
                                    <option value="Edit" @if ($syllabusCollaborator->pivot->permission == 2) selected @endif>Editor</option>
                                    <option value="View" @if ($syllabusCollaborator->pivot->permission == 3) selected @endif>Viewer</option>
                                </select>
                            </td>
                            <td class="text-center">
                                <button type="input" class="btn btn-danger btn" onclick="deleteCollab(this)">Remove</button>
                            </td>
                        @endif
                    </tr>
                @endforeach
            `);
        });

        
    });

    function deleteCollab(submitter) {
        console.log($(submitter).parents('tr'));
        $(submitter).parents('tr')[0].remove();
    }

    function addCollab(syllabus) {
        // prepend assessment method to the table
        $('#addCollabsTbl' + syllabus['id'] + ' tbody').prepend(`
            <tr>
                <td>
                    <input type="text" class="form-control " name="new_collabs[]" value = "${$('#collab_email' + syllabus['id']).val()}" placeholder="E.g. john.doe@ubc.ca" form="saveCollabChanges{{$syllabus->id}}" required>
                </td>
                <td>
                    <select form="saveCollabChanges{{$syllabus->id}}" name="new_permissions[]" class="form-select" required>
                        <option value="edit" ${($('#collab_permission' + syllabus['id']).val() == 'edit') ? 'selected' : ''}>Editor</option>
                        <option value="view" ${($('#collab_permission' + syllabus['id']).val() == 'view') ? 'selected' : ''}>Viewer</option>
                    </select>
                </td> 
            
                <td class="text-center">
                    <button type="input" class="btn btn-danger" onclick="deleteCollab(this)">Remove</button>
                </td>
            </tr>
        `);
    }
  </script>
