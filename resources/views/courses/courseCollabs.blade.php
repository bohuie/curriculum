<!-- start of add/edit course collaborators modal -->
<?php $coursePermission = $user->courses->where('course_id', $course->course_id)->first(); ?>
<div id="addCourseCollaboratorsModal{{$course->course_id}}" class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="addCourseCollaboratorsModalLabel{{$course->course_id}}" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCourseCollaboratorsModalLabel{{$course->course_id}}"><i class="bi bi-person-plus-fill"></i> Share this course with others</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="form-text text-muted mb-4">
                    <p>Give others access to this course and assign them roles.</p>
                    <li class="mb-1 mr-4 ml-4"><b>Editors</b> have access to edit and view your course but cannot delete your course or add/remove collaborators.</li>
                    <li class="mb-3 mr-4 ml-4"><b>Viewers</b> can view an overview of your course but cannot edit or delete your course or add/remove collaborators.</li>
                </div>

                @if ($coursePermission->pivot->permission == 1)
                    <form class="addCourseCollabForm needs-validation" novalidate data-course_id="{{$course->course_id}}">
                        @csrf
                        <div class="row m-2 position-relative">
                            <div class="col-6">
                                <input id="course_collab_email{{$course->course_id}}" type="email" name="email" class="form-control" placeholder="john.doe@ubc.ca" aria-label="email" required>
                                <div class="invalid-tooltip">
                                    Please provide a valid email ending with ubc.ca.
                                </div> 
                            </div>
                            <div class="col-3">
                                <select class="form-select" id="course_collab_permission{{$course->course_id}}" name="permission">
                                    <option value="edit" selected>Editor</option>
                                    <option value="view">Viewer</option>
                                </select>                   
                            </div>
                            <div class="col-3">
                                <button id="addCourseCollabBtn{{$course->course_id}}" type="submit" class="btn btn-primary col"><i class="bi bi-plus"></i> Collaborator</button>
                            </div>
                        </div>
                    </form>
                @endif

                <div class="row justify-content-center">
                    <div class="col-8">
                        <hr>
                    </div>
                </div> 

                @if ($course->users->count() < 1)
                    <div class="alert alert-warning wizard">
                        <i class="bi bi-exclamation-circle-fill"></i>You have not added any collaborators to this course yet.                    
                    </div>
                @else
                    <table id="addCourseCollabsTbl{{$course->course_id}}" class="table table-light borderless" >
                        <thead>
                            <tr class="table-primary">
                                <th>Collaborators</th>
                                <th></th>
                                <th colspan="2" class="text-center w-25">Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($course->users as $courseCollaborator)
                            <tr>
                                <td class="align-middle">
                                    <b>{{$courseCollaborator->name}} @if ($courseCollaborator->email == $user->email) (Me) @endif</b>
                                    <p>{{$courseCollaborator->email}}</p>
                                </td>
                                @if ($courseCollaborator->pivot->permission == 1)
                                <td class="text-center">
                                    <input form="saveCourseCollabChanges{{$course->course_id}}" class="form-control fw-bold" type="text" readonly value="Owner">
                                </td>
                                <td colspan="2"></td>
                                @else
                                    @if ($coursePermission->pivot->permission == 1)
                                        <td class="align-middle">
                                            <select id="course_collab_permission{{$course->course_id}}-{{$courseCollaborator->id}}" form="saveCourseCollabChanges{{$course->course_id}}" name="course_current_permissions[{{$courseCollaborator->id}}]" class="form-select" required>
                                                <option value="edit" @if ($courseCollaborator->pivot->permission == 2) selected @endif>Editor</option>
                                                <option value="view" @if ($courseCollaborator->pivot->permission == 3) selected @endif>Viewer</option>
                                            </select>
                                        </td>
                                    @else
                                        <td class="align-middle">
                                            <select id="course_collab_permission{{$course->course_id}}-{{$courseCollaborator->id}}" form="saveCourseCollabChanges{{$course->course_id}}" name="course_current_permissions[{{$courseCollaborator->id}}]" class="form-select" disabled required>
                                                <option value="edit" @if ($courseCollaborator->pivot->permission == 2) selected @endif>Editor</option>
                                                <option value="view" @if ($courseCollaborator->pivot->permission == 3) selected @endif>Viewer</option>
                                            </select>
                                        </td>
                                    @endif
                                    @if ($courseCollaborator->email == $user->email)
                                        <td class="text-center align-middle" colspan="2">
                                            <button type="button" class="btn btn-danger btn" data-toggle="modal" data-target="#leaveCourseConfirmation{{$course->course_id}}">Leave</button>
                                        </td>

                                        <!-- Leave Confirmation Modal -->
                                        <div class="modal fade" id="leaveCourseConfirmation{{$course->course_id}}" tabindex="-1" role="dialog" aria-labelledby="leaveCourseConfirmation{{$course->course_id}}" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Leave Course Confirmation</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">Are you sure you want to leave {{$course->course_title}} course?</div>
                                                    <form action="{{ action([\App\Http\Controllers\CourseUserController::class, 'leave']) }}" class="float-right">
                                                        @csrf

                                                        <input type="hidden" class="form-check-input " name="course_id" value={{$course->course_id}}>
                                                        <input type="hidden" class="form-check-input " name="courseCollaboratorId" value={{$courseCollaborator->id}}>
                                                        <div class="modal-footer">
                                                            <button style="width:60px" type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
                                                            <button style="width:60px" type="submit" class="btn btn-danger btn-sm">Leave</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        @if ($coursePermission->pivot->permission == 1)
                                            <td class="text-center align-middle">
                                                <button type="input" class="btn btn-danger btn" onclick="deleteCourseCollab(this)">Remove</button>
                                            </td>

                                            <td class="text-center align-middle">
                                                <button type="input" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#transferCourseConfirmation{{$course->course_id}}">Transfer Ownership</button>
                                            </td>

                                            <!-- Transfer Confirmation Modal -->
                                            <div class="modal fade" id="transferCourseConfirmation{{$course->course_id}}" tabindex="-1" role="dialog" aria-labelledby="transferCourseConfirmation{{$course->course_id}}" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Transfer Course Confirmation</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">Are you sure you want to give ownership of the course: {{$course->course_title}} to the user: {{$courseCollaborator->name}}?</div>
                                                        <form method="POST" action="{{ action([\App\Http\Controllers\CourseUserController::class, 'transferOwnership']) }}">
                                                            @csrf
                                                            <input type="hidden" class="form-check-input " name="course_id" value={{$course->course_id}}>
                                                            <input type="hidden" class="form-check-input " name="newOwnerId" value={{$courseCollaborator->id}}>
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

            <form method="POST" id="saveCourseCollabChanges{{$course->course_id}}" action="{{ action([\App\Http\Controllers\CourseUserController::class, 'store'], ['course' => $course->course_id]) }}">
                @csrf
                <div class="modal-footer">
                    <button type="button" class="cancelCourseCollabChanges btn btn-secondary col-3" data-bs-dismiss="modal" data-course_id="{{$course->course_id}}">Cancel</button>
                    <button type="submit" class="btn btn-success btn col-3" >Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End of course collaborator modal -->

<script>

    $(document).ready(function () {

        $('.addCourseCollabForm').submit(function (event) {
            // get the course ID
            var courseId = event.currentTarget.dataset.course_id;
            // prevent default form submission handling
            event.preventDefault();
            event.stopPropagation();
            // check if input fields contain data
            var email = $('#course_collab_email' + courseId);
            if (isEmailValid(email.val())
                // && email.val().endsWith('@ubc.ca')
            ) {
                addCourseCollab(courseId);
                // reset form 
                $(this).trigger('reset');
                $(this).removeClass('was-validated');
            } else {
                // mark form as validated
                $(this).addClass('was-validated');
            }
            // readjust modal's position/height
            document.querySelector('#addCourseCollaboratorsModal' + courseId).handleUpdate();

        });

        $('.cancelCourseCollabChanges').click(function(event) {
            location.reload();
            // var courseId = event.currentTarget.dataset.course_id;
            // $('#addCourseCollabsTbl' + courseId + ' tbody').html(`
            //     @foreach($course->users as $courseCollaborator)
            //         <tr>
            //             <td>
            //                 <b>{{$courseCollaborator->name}} @if ($courseCollaborator->email == $user->email) (Me) @endif</b>
            //                 <p>{{$courseCollaborator->email}}</p>                        
            //             </td>
                        
            //             @if ($courseCollaborator->pivot->permission == 1)
            //                 <td class="text-center">
            //                     <input value="Owner" form="saveCourseCollabChanges{{$course->course_id}}" class="form-control fw-bold" type="text" readonly>
            //                 </td>
            //                 <td></td>
            //             @else
            //                 <td >
            //                     <select id="course_collab_permission{{$course->course_id}}-{{$courseCollaborator->id}}" form="saveCourseCollabChanges{{$course->course_id}}" name="course_current_permissions[{{$courseCollaborator->id}}]" class="form-select" required>
            //                         <option value="Edit" @if ($courseCollaborator->pivot->permission == 2) selected @endif>Editor</option>
            //                         <option value="View" @if ($courseCollaborator->pivot->permission == 3) selected @endif>Viewer</option>
            //                     </select>
            //                 </td>
            //                 <td class="text-center">
            //                     <button type="input" class="btn btn-danger btn" onclick="deleteCourseCollab(this)">Remove</button>
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

    function deleteCourseCollab(submitter) {
        $(submitter).parents('tr')[0].remove();
    }

    function addCourseCollab(courseId) {
        // prepend assessment method to the table
        $('#addCourseCollabsTbl' + courseId + ' tbody').prepend(`
            <tr>
                <td>
                    <input type="text" class="form-control " name="course_new_collabs[]" value = "${$('#course_collab_email' + courseId).val()}" placeholder="E.g. john.doe@ubc.ca" form="saveCourseCollabChanges${courseId}" required>
                </td>
                <td>
                    <select form="saveCourseCollabChanges${courseId}" name="course_new_permissions[]" class="form-select" required>
                        <option value="edit" ${($('#course_collab_permission' + courseId).val() == 'edit') ? 'selected' : ''}>Editor</option>
                        <option value="view" ${($('#course_collab_permission' + courseId).val() == 'view') ? 'selected' : ''}>Viewer</option>
                    </select>
                </td> 
            
                <td class="text-center">
                    <button type="input" class="btn btn-danger" onclick="deleteCourseCollab(this)">Remove</button>
                </td>
                <td></td>
            </tr>
        `);
    }
</script>
