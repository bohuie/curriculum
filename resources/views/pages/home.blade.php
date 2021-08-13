@extends('layouts.app')

@section('content')


<div class="container">
    <div class="row">


        <div style="width: 100%;border-bottom: 1px solid #DCDCDC">
        <h2 style="float: left;">My Dashboard</h2>
        </div>

        <div class="col-md-12">

                <div class="card shadow rounded m-4" style="border-style: solid;
                border-color: #1E90FF;">
                    <div class="card-title bg-primary p-3">
                        <h3 style="color: white;">
                        Programs
                        
                        <div style="float:right;">
                            <button id="programHelp" style="border: none; background: none; outline: none;" data-bs-toggle="modal" href="#guideModal">
                                <i class="bi bi-question-circle text-white"></i>
                            </button>
                        </div>
                        @include('layouts.guide')

                        <div style="float:right;">
                            <button style="border: none; background: none; outline: none;" data-toggle="modal" data-target="#createProgramModal">
                                <i class="bi bi-plus-circle text-white"></i>
                            </button>
                        </div>
                        </h3>
                    </div>

                    
                    @if(count($myPrograms)>0)
                        <table class="table table-hover dashBoard">
                            <thead>
                                <tr>
                                    <th scope="col"></th>
                                    <th scope="col">Program</th>
                                    <th scope="col">Faculty and Department/School</th>
                                    <th scope="col">Level</th>
                                    <th scope="col">Modified</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            @if (count($myPrograms->where('userPermission', 1)) > 0)
                                <tr>
                                    <th colspan="6" class="table-secondary">My Programs</th>
                                </tr>
                            @endif
                            <!-- Displays 'My Programs' -->
                            @foreach ($myPrograms->where('userPermission', 1)->values() as $index => $program) 
                            <tbody>
                            <tr>
                                <th scope="row">{{$index + 1}}</th>
                                <td><a href="{{route('programWizard.step1', $program->program_id)}}">{{$program->program}}</a></td>
                                <td>{{$program->faculty}} </td>
                                <td>{{$program->level}}</td>
                                <td>
                                    {{$program->timeSince}}
                                </td>
                                <td>
                                    @if ($program->pivot->permission == 1) 
                                        <a class="pr-2 pl-2" href="{{route('programWizard.step1', $program->program_id)}}">
                                            <i class="bi bi-pencil-fill btn-icon dropdown-item"></i>
                                        </a>
                                        <a class="pr-2 pl-2" data-toggle="modal" data-target="#deleteProgram{{$index}}" href=#>
                                            <i class="bi bi-trash-fill text-danger btn-icon dropdown-item"></i>
                                        </a>
                                        <!-- Collaborators Icon -->
                                        <div class="btn bg-transparent position-relative pr-2 pl-2" data-toggle="tooltip" data-html="true" data-bs-placement="right" title="@foreach($programUsers[$program->program_id] as $counter => $programUser){{$counter + 1}}. {{$programUser->name}}<br>@endforeach">
                                            <div data-toggle="modal" data-target="#addProgramCollaboratorModal{{$program->program_id}}">
                                                <i class="bi bi-person-plus-fill"></i>
                                                <span class="position-absolute top-0 start-85 translate-middle badge rounded-pill badge badge-dark">
                                                    {{ count($programUsers[$program->program_id]) }}
                                                </span>
                                            </div>
                                        </div>
                                    @else
                                        <a class="pr-2 pl-2" href="{{route('programWizard.step1', $program->program_id)}}">
                                            <i class="bi bi-pencil-fill btn-icon dropdown-item"></i>
                                        </a>
                                    @endif
                                    
                                    <!-- Add Program Collaborator Modal -->
                                    <div class="modal fade" id="addProgramCollaboratorModal{{$program->program_id}}" tabindex="-1" role="dialog" aria-labelledby="addProgramCollaboratorModalLabel{{$program->program_id}}" aria-hidden="true">
                                        <div class="modal-dialog modal-lg" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="addProgramCollaboratorModalLabel">Assign Collaborator to Program: {{$program->program}}</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                
                                                <div class="card-body">
                                                    <p class="form-text text-muted mb-4">Collaborators can see and edit the program. Collaborators must first register with this web application to be added to a program.
                                                        By adding a collaborator, a verification email will be sent to their email address.
                                                        If your collaborator is not registered with this website yet,
                                                        use the <a href="{{ url('/invite') }}">'Registration Invite' feature to invite them.</a>
                                                        </p>
                                                        <form method="POST" action="{{ action('ProgramUserController@store') }}">
                                                            @csrf
                                                            <div class="row mb-4">
                                                                <div class="col-6">
                                                                    <input id="email" type="email" name="email" class="form-control" placeholder="Collaborator Email" aria-label="email" required>
                                                                </div>
                                                                <div class="col-3">
                                                                    <select class="form-select" name="permission">
                                                                        <option value="edit" selected>Editor</option>
                                                                        <option value="view">Viewer</option>
                                                                    </select>                                                                    
                                                                </div>
                                                                <div class="col-3">
                                                                    <button type="submit" class="btn btn-primary col"><i class="bi bi-plus"></i> Collaborator</button>
                                                                </div>
                                                            </div>

                                                            <input type="hidden" class="form-check-input" name="program_id" value={{$program->program_id}}>

                                                        </form>
                                                        @if ($programUsers[$program->program_id]->count() < 1)
                                                            <div class="alert alert-warning wizard">
                                                                <i class="bi bi-exclamation-circle-fill"></i>You have not added any collaborators to this program yet.                    
                                                            </div>
                                                        @else
                                                            <table class="table table-light borderless" >
                                                                <tr class="table-primary">
                                                                    <th>Collaborators</th>
                                                                    <th></th>
                                                                    <th class="text-center w-25">Actions</th>
                                                                </tr>
                                                                @foreach($programUsers[$program->program_id] as $programCollaborator)
                                                                        <tr>
                                                                            <td >
                                                                                <b>{{$programCollaborator->name}} @if ($programCollaborator->email == $user->email) (Me) @endif</b>
                                                                                <p>{{$programCollaborator->email}}</p>
                                                                            </td>
                                                                            <td>@switch ($programCollaborator->pivot->permission) 
                                                                                    @case(1)
                                                                                        <b><i>Owner</i></b>
                                                                                        @break
                                                                                    @case(2)
                                                                                        Editor
                                                                                        @break
                                                                                    @case(3)
                                                                                        Viewer
                                                                                        @break
                                                                                @endswitch
                                                                            </td>
                                                                            @if ($programCollaborator->pivot->permission == 1)
                                                                                <td></td>
                                                                            @else
                                                                                <td class="text-center">
                                                                                    <form action="{{route('programUser.destroy') }}" method="POST">
                                                                                        @csrf
                                                                                        {{method_field('DELETE')}}
                                                                                        <input type="hidden" class="form-check-input" name="program_id" value={{$program->program_id}}>
                                                                                        <input type="hidden" class="form-check-input" name="user_id" value="{{$programCollaborator->id}}">
                                                                                        <input type="hidden" class="form-check-input" name="email" value="{{$programCollaborator->email}}">
                                                                                        <button type="submit" class="btn btn-danger btn-sm">Unassign</button>
                                                                                    </form>
                                                                                </td>
                                                                            @endif
                                                                        </tr>
                                                                @endforeach
                                                            </table>
                                                        @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--End Program Collaborators-->

                                    <!-- Delete Confirmation Modal -->
                                    <div class="modal fade" id="deleteProgram{{$index}}" tabindex="-1" role="dialog" aria-labelledby="deleteProgram{{$index}}" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Delete Program Confirmation</h5>
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
                                </td>
                            </tr>
                            </tbody>
                            @endforeach
                            <!-- Displays 'My Programs' -->
                            @if (count($myPrograms->where('userPermission', 2)) > 0)
                            <tr>
                                <th colspan="6" class="table-secondary">Programs I Can Edit</th>
                            </tr>
                            @endif
                            @foreach ($myPrograms->where('userPermission', 2)->values() as $index => $program) 
                            <tbody>
                            <tr>
                                <th scope="row">{{$index + 1}}</th>
                                <td><a href="{{route('programWizard.step1', $program->program_id)}}">{{$program->program}}</a></td>
                                <td>{{$program->faculty}} </td>
                                <td>{{$program->level}}</td>
                                <td>
                                    {{$program->timeSince}}
                                </td>
                                <td class="text-center">
                                    @if ($program->pivot->permission == 1) 
                                        <a class="pr-2 pl-2" href="{{route('programWizard.step1', $program->program_id)}}">
                                            <i class="bi bi-pencil-fill btn-icon dropdown-item"></i>
                                        </a>
                                        <a class="pr-2 pl-2" data-toggle="modal" data-target="#deleteProgram{{$index}}" href=#>
                                            <i class="bi bi-trash-fill text-danger btn-icon dropdown-item"></i>
                                        </a>
                                        <!-- Collaborators Icon -->
                                        <div class="btn bg-transparent position-relative pr-2 pl-2" data-toggle="tooltip" data-html="true" data-bs-placement="right" title="@foreach($programUsers[$program->program_id] as $counter => $programUser){{$counter + 1}}. {{$programUser->name}}<br>@endforeach">
                                            <div data-toggle="modal" data-target="#addProgramCollaboratorModal{{$program->program_id}}">
                                                <i class="bi bi-person-plus-fill"></i>
                                                <span class="position-absolute top-0 start-85 translate-middle badge rounded-pill badge badge-dark">
                                                    {{ count($programUsers[$program->program_id]) }}
                                                </span>
                                            </div>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                            </tbody>
                            @endforeach
                            <!-- Displays 'My Programs' -->
                            @if (count($myPrograms->where('userPermission', 3)) > 0)
                            <tr>
                                <th colspan="6" class="table-secondary">Programs I Can View</th>
                            </tr>
                            @endif
                            @foreach ($myPrograms->where('userPermission', 3)->values() as $index => $program) 
                            <tbody>
                            <tr>
                                <th scope="row">{{$index + 1}}</th>
                                <td><a href="{{route('programWizard.step1', $program->program_id)}}">{{$program->program}}</a></td>
                                <td>{{$program->faculty}} </td>
                                <td>{{$program->level}}</td>
                                <td>
                                    {{$program->timeSince}}
                                </td>
                                <td></td>
                            </tr>
                            </tbody>
                            @endforeach
                        </table>
                    @endif
                </div>

                <div class="card shadow rounded m-4" style="border-style: solid;
                border-color: #1E90FF;">
                    <div class="card-title bg-primary p-3">
                        <h3 style="color: white;">
                        Courses
                        
                        <div style="float:right;">
                            <button id="coursesHelp" style="border: none; background: none; outline: none;" data-bs-toggle="modal" href="#guideModal">
                                <i class="bi bi-question-circle text-white"></i>
                            </button>
                        </div>
                        @include('layouts.guide')

                        <div style="float:right;">
                            <button style="border: none; background: none; outline: none;" data-toggle="modal" data-target="#createCourseModal">
                                <i class="bi bi-plus-circle text-white"></i>
                            </button>
                        </div>
                        </h3>
                    </div>

                    <div class="card-body" style="padding:0%;">
                        @if(count($myCourses)>0)
                            <table class="table table-hover dashBoard">
                                <thead>
                                <tr>
                                    <th scope="col"></th>
                                    <th scope="col">Course Title</th>
                                    <th scope="col">Course Code</th>
                                    <th scope="col">Term</th>
                                    <th scope="col">Status</th>
                                    <th scope="col" class="text-center">Programs </th>
                                    <th scope="col">Modified</th>
                                    <th scope="col">Actions</th>
                                </tr>
                                </thead>
                                
                                <!-- Displays 'My Courses' -->
                                @if (count($myCourses->where('userPermission', 1)) > 0)
                                    <tr>
                                        <th colspan="8" class="table-secondary">My Courses</th>
                                    </tr>
                                @endif
                                @foreach ($myCourses->where('userPermission', 1)->values() as $index => $course)
                                <tbody>
                                <tr>
                                    <!-- Courses That have Not been Completed TODO: THIS IS PROBABLY NOT NEEDED ANYMORE-->
                                    @if($course->status !== 1)
                                        <th scope="row">{{$index + 1}}</th>
                                        <td><a href="{{route('courseWizard.step1', $course->course_id)}}">{{$course->course_title}}</a></td>
                                        <td>{{$course->course_code}} {{$course->course_num}}</td>
                                        <td>{{$course->year}} {{$course->semester}}</td>
                                        <td class="align-middle">
                                            @if ($progressBar[$course->course_id] == 0)
                                                <p class="text-center mb-0">{{$progressBar[$course->course_id]}}%</p>
                                                <div class="progress">
                                                    <div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            @elseif ($progressBar[$course->course_id] == 100)
                                                <p class="text-center mb-0">{{$progressBar[$course->course_id]}}%</p>
                                                <div class="progress">
                                                    <div class="progress-bar bg-success" role="progressbar" style="width:{{$progressBar[$course->course_id]}}%;" aria-valuenow="{{$progressBar[$course->course_id]}}" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            @else
                                                <p class="text-center mb-0">{{$progressBar[$course->course_id]}}%</p>
                                                <div class="progress">
                                                    <div class="progress-bar bg-info" role="progressbar" style="width:{{$progressBar[$course->course_id]}}%;" aria-valuenow="{{$progressBar[$course->course_id]}}" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            @endif
                                        </td>

                                        <td> 
                                            <div class="row">
                                                <div class="d-flex justify-content-center">
                                                    @if(count($coursesPrograms[$course->course_id]) > 0)
                                                        <div class="bg-transparent position-relative pr-2 pl-2" data-toggle="tooltip" data-html="true" title="@foreach($coursesPrograms[$course->course_id] as $i => $courseProgram){{$i + 1}}. {{$courseProgram->program}}<br>@endforeach" data-bs-placement="right">
                                                            <i class="bi bi-map" style="font-size:x-large; text-align:center;"></i>
                                                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill badge badge-dark">
                                                                {{ count($coursesPrograms[$course->course_id]) }}
                                                            </span>
                                                        </div>
                                                    @else
                                                    <p style="text-align: center; display:inline-block; margin-left:-15px;"><i class="bi bi-info-circle-fill" data-toggle="tooltip" data-bs-placement="right" title='To map a course to a program, you must first create a program from the "My Programs" section'> None</i></p>
                                                    @endif
                                                </div>
                                            </div>                                           
                                        </td>
                                    @else
                                        <!-- Courses That have been Completed -->
                                        <th scope="row">{{$index + 1}}</th>
                                        <td><a href="{{route('courseWizard.step1', $course->course_id)}}">{{$course->course_title}}</a></td>
                                        <td>{{$course->course_code}} {{$course->course_num}}</td>
                                        <td>{{$course->year}} {{$course->semester}}</td>
                                        <td class="align-middle">
                                            @if ($progressBar[$course->course_id] == 0)
                                                <p class="text-center mb-0">{{$progressBar[$course->course_id]}}%</p>
                                                <div class="progress">
                                                    <div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            @elseif ($progressBar[$course->course_id] == 100)
                                                <p class="text-center mb-0">{{$progressBar[$course->course_id]}}%</p>
                                                <div class="progress">
                                                    <div class="progress-bar bg-success" role="progressbar" style="width:{{$progressBar[$course->course_id]}}%;" aria-valuenow="{{$progressBar[$course->course_id]}}" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            @else
                                                <p class="text-center mb-0">{{$progressBar[$course->course_id]}}%</p>
                                                <div class="progress">
                                                    <div class="progress-bar bg-info" role="progressbar" style="width:{{$progressBar[$course->course_id]}}%;" aria-valuenow="{{$progressBar[$course->course_id]}}" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            @endif
                                        </td>

                                        <td> 
                                            <div class="row">
                                                <div class="d-flex justify-content-center">
                                                    @if(count($coursesPrograms[$course->course_id]) > 0)
                                                        <div class="bg-transparent position-relative pr-2 pl-2" data-toggle="tooltip" data-html="true" title="@foreach($coursesPrograms[$course->course_id] as $i => $courseProgram){{$i + 1}}. {{$courseProgram->program}}<br>@endforeach" data-bs-placement="right">
                                                            <i class="bi bi-map" style="font-size:x-large; text-align:center;"></i>
                                                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill badge badge-dark">
                                                                {{ count($coursesPrograms[$course->course_id]) }}
                                                            </span>
                                                        </div>
                                                    @else
                                                    <p style="text-align: center; display:inline-block; margin-left:-15px;"><i class="bi bi-info-circle-fill" data-toggle="tooltip" data-bs-placement="right" title='To map a course to a program, you must first create a program from the "My Programs" section'> None</i></p>
                                                    @endif
                                                </div>
                                            </div>                                           
                                        </td>
                                    @endif
                                    <td>
                                        {{$course->timeSince}}
                                    </td>
                                    <td>
                                        @if ($course->pivot->permission == 1) 
                                            <a  class="pr-2" href="{{route('courseWizard.step1', $course->course_id)}}">
                                            <i class="bi bi-pencil-fill btn-icon dropdown-item"></i></a>
                                            <a data-toggle="modal" data-target="#deleteCourseConfirmation{{$index}}" href=#>
                                            <i class="bi bi-trash-fill text-danger btn-icon dropdown-item"></i></a>
                                            <!-- Collaborators Icon for Dashboard -->
                                            <div class="btn bg-transparent position-relative pr-2 pl-2" data-toggle="tooltip" data-html="true" data-bs-placement="right" title="@foreach($courseUsers[$course->course_id] as $c => $courseUser){{$c + 1}}. {{$courseUser->name}}<br>@endforeach">
                                                <div data-toggle="modal" data-target="#addCourseCollaboratorModal{{$course->course_id}}">
                                                    <i class="bi bi-person-plus-fill"></i>
                                                    <span class="position-absolute top-0 start-85 translate-middle badge rounded-pill badge badge-dark">
                                                        {{ count($courseUsers[$course->course_id]) }}
                                                    </span>
                                                </div>
                                            </div>
                                        @endif

                                        <!-- Collaborator Modal -->
                                        <div class="modal fade" id="addCourseCollaboratorModal{{$course->course_id}}" tabindex="-1" role="dialog"
                                            aria-labelledby="addCourseCollaboratorModal{{$course->course_id}}" aria-hidden="true">
                                            <div class="modal-dialog modal-lg" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="addCourseCollaboratorModal">Add Collaborators to
                                                            Course: {{$course->course_title}}</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>

                                                    <div class="card-body">
                                                    <p class="form-text text-muted mb-4">Collaborators can see and edit the course. Collaborators must first register with this web application to be added to a course.
                                                        By adding a collaborator, a verification email will be sent to their email address.
                                                        If your collaborator is not registered with this website yet,
                                                        use the <a href="{{ url('/invite') }}">'Registration Invite' feature to invite them.</a>
                                                        </p>
                                                        <form method="POST" action="{{ action('CourseUserController@store', $course->course_id) }}">
                                                            @csrf
                                                            <div class="row mb-4">
                                                                <div class="col-6">
                                                                    <input id="email" type="email" name="email" class="form-control" placeholder="Collaborator Email" aria-label="email" required>
                                                                </div>
                                                                <div class="col-3">
                                                                    <select class="form-select" name="permission">
                                                                        <option value="edit" selected>Editor</option>
                                                                        <option value="view">Viewer</option>
                                                                    </select>                                                                    
                                                                </div>
                                                                <div class="col-3">
                                                                    <button type="submit" class="btn btn-primary col"><i class="bi bi-plus"></i> Collaborator</button>
                                                                </div>
                                                            </div>

                                                            <input type="hidden" class="form-check-input" name="course_id" value={{$course->course_id}}>

                                                        </form>
                                                        @if ($courseUsers[$course->course_id]->count() < 1)
                                                            <div class="alert alert-warning wizard">
                                                                <i class="bi bi-exclamation-circle-fill"></i>You have not added any collaborators to this course yet.                    
                                                            </div>
                                                        @else
                                                            <table class="table table-light borderless" >
                                                                <tr class="table-primary">
                                                                    <th>Collaborators</th>
                                                                    <th></th>
                                                                    <th class="text-center w-25">Actions</th>
                                                                </tr>
                                                                @foreach($courseUsers[$course->course_id] as $courseCollaborator)
                                                                        <tr>
                                                                            <td >
                                                                                <b>{{$courseCollaborator->name}} @if ($courseCollaborator->email == $user->email) (Me) @endif</b>
                                                                                <p>{{$courseCollaborator->email}}</p>
                                                                            </td>
                                                                            <td>@switch ($courseCollaborator->pivot->permission) 
                                                                                    @case(1)
                                                                                        <b><i>Owner</i></b>
                                                                                        @break
                                                                                    @case(2)
                                                                                        Editor
                                                                                        @break
                                                                                    @case(3)
                                                                                        Viewer
                                                                                        @break
                                                                                @endswitch
                                                                            </td>
                                                                            @if ($courseCollaborator->pivot->permission == 1)
                                                                                <td></td>
                                                                            @else
                                                                                <td class="text-center">
                                                                                    <form action="{{route('courses.unassign', $course->course_id) }}" method="POST">
                                                                                        @csrf
                                                                                        {{method_field('DELETE')}}
                                                                                        <input type="hidden" class="form-check-input" name="course_id" value={{$course->course_id}}>
                                                                                        <input type="hidden" class="form-check-input" name="user_id" value="{{$courseCollaborator->id}}">
                                                                                        <input type="hidden" class="form-check-input" name="email" value="{{$courseCollaborator->email}}">
                                                                                        <button type="submit" class="btn btn-danger btn-sm">Unassign</button>
                                                                                    </form>
                                                                                </td>
                                                                            @endif
                                                                        </tr>
                                                                @endforeach
                                                            </table>
                                                        @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End of course collaborator modal -->
                                        
                                        <!-- Delete Confirmation Modal -->
                                        <div class="modal fade show" id="deleteCourseConfirmation{{$index}}" tabindex="-1" role="dialog" aria-labelledby="deleteCourseConfirmation{{$index}}" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Delete Course Confirmation</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>

                                                    <div class="modal-body">
                                                    Are you sure you want to delete course {{$course->course_code}} {{$course->course_num}} ?
                                                    </div>

                                                    <form action="{{route('courses.destroy', $course->course_id)}}" method="POST">
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
                                        <!-- End of Delete Course Confirmation Modal -->
                                    </td>
                                </tr>

                                </tbody>
                                @endforeach
                                <!--End MyCourses-->
                                <!-- Displays 'My Courses' -->
                                @if (count($myCourses->where('userPermission', 2)) > 0)
                                    <tr>
                                        <th colspan="8" class="table-secondary">Courses I Can Edit</th>
                                    </tr>
                                @endif
                                @foreach ($myCourses->where('userPermission', 2)->values() as $index => $course)
                                <tbody>
                                <tr>
                                    <!-- Courses That have Not been Completed TODO: THIS IS PROBABLY NOT NEEDED ANYMORE-->
                                    @if($course->status !== 1)
                                        <th scope="row">{{$index + 1}}</th>
                                        <td><a href="{{route('courseWizard.step1', $course->course_id)}}">{{$course->course_title}}</a></td>
                                        <td>{{$course->course_code}} {{$course->course_num}}</td>
                                        <td>{{$course->year}} {{$course->semester}}</td>
                                        <td class="align-middle">
                                            @if ($progressBar[$course->course_id] == 0)
                                                <p class="text-center mb-0">{{$progressBar[$course->course_id]}}%</p>
                                                <div class="progress">
                                                    <div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            @elseif ($progressBar[$course->course_id] == 100)
                                                <p class="text-center mb-0">{{$progressBar[$course->course_id]}}%</p>
                                                <div class="progress">
                                                    <div class="progress-bar bg-success" role="progressbar" style="width:{{$progressBar[$course->course_id]}}%;" aria-valuenow="{{$progressBar[$course->course_id]}}" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            @else
                                                <p class="text-center mb-0">{{$progressBar[$course->course_id]}}%</p>
                                                <div class="progress">
                                                    <div class="progress-bar bg-info" role="progressbar" style="width:{{$progressBar[$course->course_id]}}%;" aria-valuenow="{{$progressBar[$course->course_id]}}" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            @endif
                                        </td>

                                        <td> 
                                            <div class="row">
                                                <div class="d-flex justify-content-center">
                                                    @if(count($coursesPrograms[$course->course_id]) > 0)
                                                        <div class="bg-transparent position-relative pr-2 pl-2" data-toggle="tooltip" data-html="true" title="@foreach($coursesPrograms[$course->course_id] as $i => $courseProgram){{$i + 1}}. {{$courseProgram->program}}<br>@endforeach" data-bs-placement="right">
                                                            <i class="bi bi-map" style="font-size:x-large; text-align:center;"></i>
                                                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill badge badge-dark">
                                                                {{ count($coursesPrograms[$course->course_id]) }}
                                                            </span>
                                                        </div>
                                                    @else
                                                    <p style="text-align: center; display:inline-block; margin-left:-15px;"><i class="bi bi-info-circle-fill" data-toggle="tooltip" data-bs-placement="right" title='To map a course to a program, you must first create a program from the "My Programs" section'> None</i></p>
                                                    @endif
                                                </div>
                                            </div>                                           
                                        </td>
                                    @else
                                        <!-- Courses That have been Completed -->
                                        <th scope="row">{{$index + 1}}</th>
                                        <td><a href="{{route('courseWizard.step1', $course->course_id)}}">{{$course->course_title}}</a></td>
                                        <td>{{$course->course_code}} {{$course->course_num}}</td>
                                        <td>{{$course->year}} {{$course->semester}}</td>
                                        <td class="align-middle">
                                            @if ($progressBar[$course->course_id] == 0)
                                                <p class="text-center mb-0">{{$progressBar[$course->course_id]}}%</p>
                                                <div class="progress">
                                                    <div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            @elseif ($progressBar[$course->course_id] == 100)
                                                <p class="text-center mb-0">{{$progressBar[$course->course_id]}}%</p>
                                                <div class="progress">
                                                    <div class="progress-bar bg-success" role="progressbar" style="width:{{$progressBar[$course->course_id]}}%;" aria-valuenow="{{$progressBar[$course->course_id]}}" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            @else
                                                <p class="text-center mb-0">{{$progressBar[$course->course_id]}}%</p>
                                                <div class="progress">
                                                    <div class="progress-bar bg-info" role="progressbar" style="width:{{$progressBar[$course->course_id]}}%;" aria-valuenow="{{$progressBar[$course->course_id]}}" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            @endif
                                        </td>

                                        <td> 
                                            <div class="row">
                                                <div class="d-flex justify-content-center">
                                                    @if(count($coursesPrograms[$course->course_id]) > 0)
                                                        <div class="bg-transparent position-relative pr-2 pl-2" data-toggle="tooltip" data-html="true" title="@foreach($coursesPrograms[$course->course_id] as $i => $courseProgram){{$i + 1}}. {{$courseProgram->program}}<br>@endforeach" data-bs-placement="right">
                                                            <i class="bi bi-map" style="font-size:x-large; text-align:center;"></i>
                                                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill badge badge-dark">
                                                                {{ count($coursesPrograms[$course->course_id]) }}
                                                            </span>
                                                        </div>
                                                    @else
                                                    <p style="text-align: center; display:inline-block; margin-left:-15px;"><i class="bi bi-info-circle-fill" data-toggle="tooltip" data-bs-placement="right" title='To map a course to a program, you must first create a program from the "My Programs" section'> None</i></p>
                                                    @endif
                                                </div>
                                            </div>                                           
                                        </td>
                                    @endif
                                    <td>
                                        {{$course->timeSince}}
                                    </td>
                                    <td></td>
                                </tr>

                                </tbody>
                                @endforeach
                                <!--End MyCourses-->
                                <!-- Displays 'My Courses' -->
                                @if (count($myCourses->where('userPermission', 3)) > 0)
                                    <tr>
                                        <th colspan="8" class="table-secondary">Courses I Can View</th>
                                    </tr>
                                @endif
                                @foreach ($myCourses->where('userPermission', 3)->values() as $index => $course)
                                <tbody>
                                <tr>
                                    <!-- Courses That have Not been Completed TODO: THIS IS PROBABLY NOT NEEDED ANYMORE-->
                                    @if($course->status !== 1)
                                        <th scope="row">{{$index + 1}}</th>
                                        <td><a href="{{route('courseWizard.step1', $course->course_id)}}">{{$course->course_title}}</a></td>
                                        <td>{{$course->course_code}} {{$course->course_num}}</td>
                                        <td>{{$course->year}} {{$course->semester}}</td>
                                        <td class="align-middle">
                                            @if ($progressBar[$course->course_id] == 0)
                                                <p class="text-center mb-0">{{$progressBar[$course->course_id]}}%</p>
                                                <div class="progress">
                                                    <div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            @elseif ($progressBar[$course->course_id] == 100)
                                                <p class="text-center mb-0">{{$progressBar[$course->course_id]}}%</p>
                                                <div class="progress">
                                                    <div class="progress-bar bg-success" role="progressbar" style="width:{{$progressBar[$course->course_id]}}%;" aria-valuenow="{{$progressBar[$course->course_id]}}" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            @else
                                                <p class="text-center mb-0">{{$progressBar[$course->course_id]}}%</p>
                                                <div class="progress">
                                                    <div class="progress-bar bg-info" role="progressbar" style="width:{{$progressBar[$course->course_id]}}%;" aria-valuenow="{{$progressBar[$course->course_id]}}" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            @endif
                                        </td>

                                        <td> 
                                            <div class="row">
                                                <div class="d-flex justify-content-center">
                                                    @if(count($coursesPrograms[$course->course_id]) > 0)
                                                        <div class="bg-transparent position-relative pr-2 pl-2" data-toggle="tooltip" data-html="true" title="@foreach($coursesPrograms[$course->course_id] as $i => $courseProgram){{$i + 1}}. {{$courseProgram->program}}<br>@endforeach" data-bs-placement="right">
                                                            <i class="bi bi-map" style="font-size:x-large; text-align:center;"></i>
                                                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill badge badge-dark">
                                                                {{ count($coursesPrograms[$course->course_id]) }}
                                                            </span>
                                                        </div>
                                                    @else
                                                    <p style="text-align: center; display:inline-block; margin-left:-15px;"><i class="bi bi-info-circle-fill" data-toggle="tooltip" data-bs-placement="right" title='To map a course to a program, you must first create a program from the "My Programs" section'> None</i></p>
                                                    @endif
                                                </div>
                                            </div>                                           
                                        </td>
                                    @else
                                        <!-- Courses That have been Completed -->
                                        <th scope="row">{{$index + 1}}</th>
                                        <td><a href="{{route('courseWizard.step1', $course->course_id)}}">{{$course->course_title}}</a></td>
                                        <td>{{$course->course_code}} {{$course->course_num}}</td>
                                        <td>{{$course->year}} {{$course->semester}}</td>
                                        <td class="align-middle">
                                            @if ($progressBar[$course->course_id] == 0)
                                                <p class="text-center mb-0">{{$progressBar[$course->course_id]}}%</p>
                                                <div class="progress">
                                                    <div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            @elseif ($progressBar[$course->course_id] == 100)
                                                <p class="text-center mb-0">{{$progressBar[$course->course_id]}}%</p>
                                                <div class="progress">
                                                    <div class="progress-bar bg-success" role="progressbar" style="width:{{$progressBar[$course->course_id]}}%;" aria-valuenow="{{$progressBar[$course->course_id]}}" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            @else
                                                <p class="text-center mb-0">{{$progressBar[$course->course_id]}}%</p>
                                                <div class="progress">
                                                    <div class="progress-bar bg-info" role="progressbar" style="width:{{$progressBar[$course->course_id]}}%;" aria-valuenow="{{$progressBar[$course->course_id]}}" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            @endif
                                        </td>

                                        <td> 
                                            <div class="row">
                                                <div class="d-flex justify-content-center">
                                                    @if(count($coursesPrograms[$course->course_id]) > 0)
                                                        <div class="bg-transparent position-relative pr-2 pl-2" data-toggle="tooltip" data-html="true" title="@foreach($coursesPrograms[$course->course_id] as $i => $courseProgram){{$i + 1}}. {{$courseProgram->program}}<br>@endforeach" data-bs-placement="right">
                                                            <i class="bi bi-map" style="font-size:x-large; text-align:center;"></i>
                                                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill badge badge-dark">
                                                                {{ count($coursesPrograms[$course->course_id]) }}
                                                            </span>
                                                        </div>
                                                    @else
                                                    <p style="text-align: center; display:inline-block; margin-left:-15px;"><i class="bi bi-info-circle-fill" data-toggle="tooltip" data-bs-placement="right" title='To map a course to a program, you must first create a program from the "My Programs" section'> None</i></p>
                                                    @endif
                                                </div>
                                            </div>                                           
                                        </td>
                                    @endif
                                    <td>
                                        {{$course->timeSince}}
                                    </td>
                                    <td></td>
                                </tr>

                                </tbody>
                                @endforeach
                                <!--End MyCourses-->
                            </table>
                        @else
                        @endif
                    </div>
                </div>

                <!-- My Syllabi Section -->
                <div class="card shadow rounded m-4" style="border-style: solid;
                border-color: #1E90FF;">
                    <div class="card-title bg-primary p-3">
                        <h3 style="color: white;">
                        Syllabi
                        
                        <div style="float:right;">
                            <button id="syllabiHelp" style="border: none; background: none; outline: none;" data-bs-toggle="modal" href="#guideModal">
                                <i class="bi bi-question-circle text-white"></i>
                            </button>
                        </div>
                        @include('layouts.guide')

                        <div style="float:right;">
                            <a href="{{route('syllabus')}}">
                                <button style="border: none; background: none; outline: none;">
                                    <i class="bi bi-plus-circle text-white"></i>
                                </button>
                            </a>
                        </div>
                        </h3>
                    </div>

                    <div class="card-body" style="padding:0%;">
                        @if(count($mySyllabi)>0)
                            <table class="table table-hover dashBoard">
                                <thead>
                                <tr>
                                    <th scope="col"></th>
                                    <th scope="col">Course Title</th>
                                    <th scope="col">Course Code</th>
                                    <th scope="col">Term</th>
                                    <th scope="col">Modified</th>
                                    <th scope="col">Actions</th>
                                </tr>
                                </thead>
                                <!--Displays MySyllabus-->
                                @if (count($mySyllabi->where('userPermission', 1)) > 0)
                                    <tr>
                                        <th colspan="6" class="table-secondary">My Syllabi</th>
                                    </tr>
                                @endif
                                @foreach ($mySyllabi->where('userPermission', 1)->values() as $index => $syllabus)

                                <!-- Displays 'My Courses' -->
                                <tbody>
                                <tr>
                                    <!-- index -->
                                    <th scope="row">
                                        {{$index + 1}}
                                    </th>
                                    <!-- course title -->
                                    <td>
                                        <a href="{{route('syllabus', $syllabus->id)}}">{{$syllabus->course_title}}</a>
                                    </td>
                                    <!-- course code -->
                                    <td>
                                        {{$syllabus->course_code}} {{$syllabus->course_num}}
                                    </td>
                                    <!-- term -->
                                    <td>
                                        {{$syllabus->course_year}} {{$syllabus->course_term}}
                                    </td>
                                    <td>
                                        {{$syllabus->timeSince}}
                                    </td>
                                    <td>
                                        @if ($syllabus->pivot->permission == 1) 
                                            <a  class="pr-2" href="{{route('syllabus', $syllabus->id)}}">
                                            <i class="bi bi-pencil-fill btn-icon dropdown-item"></i></a>
                                            <a data-toggle="modal" data-target="#deleteSyllabusConfirmation{{$index}}" href=#>
                                            <i class="bi bi-trash-fill text-danger btn-icon dropdown-item"></i></a>
                                            <!-- Syllabus collaborators icon -->
                                            <div class="btn bg-transparent position-relative pr-2 pl-2" data-toggle="tooltip" data-html="true" data-bs-placement="right" title="@foreach($syllabiUsers[$syllabus->id] as $userIndex => $syllabusUser){{$userIndex + 1}}. {{$syllabusUser->name}}<br>@endforeach">
                                                <div data-toggle="modal" data-target="#addSyllabusCollaboratorModal{{$syllabus->id}}">
                                                    <i class="bi bi-person-plus-fill"></i>
                                                    <span class="position-absolute top-0 start-85 translate-middle badge rounded-pill badge badge-dark">
                                                        {{ count($syllabiUsers[$syllabus->id]) }}
                                                    </span>
                                                </div>
                                            </div>
                                            <!-- End of syllabus collaborators icon -->
                                        @else
                                            <a  class="pr-2" href="{{route('syllabus', $syllabus->id)}}">
                                            <i class="bi bi-pencil-fill btn-icon dropdown-item"></i></a>
                                        @endif

                                        <!-- Syllabus collaborator modal -->
                                        <div class="modal fade" id="addSyllabusCollaboratorModal{{$syllabus->id}}" tabindex="-1" role="dialog"
                                            aria-labelledby="addSyllabusCollaboratorModal{{$syllabus->id}}" aria-hidden="true">
                                            <div class="modal-dialog modal-lg" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        
                                                        <h5 class="modal-title" id="addSyllabusCollaboratorModal"><i class="bi bi-person-plus-fill mr-2"></i> Share this syllabus with people</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="card-body">
                                                        <p class="form-text text-muted mb-4">Collaborators can see and edit the syllabus. Collaborators must first register with this web application to be added to a syllabus.
                                                            By adding a collaborator, a verification email will be sent to their email address.
                                                            If your collaborator is not registered with this website yet,
                                                            use the <a href="{{ url('/invite') }}">'Registration Invite' feature to invite them.</a>
                                                            </p>

                                                            <form id="syllabusCollaboratorForm{{$syllabus->id}}" method="POST" action="{{route('syllabus.assign', $syllabus->id)}}">
                                                                @csrf
                                                                <div class="row mb-4">
                                                                    <div class="col-6">
                                                                        <input id="email" type="email" name="email" class="form-control" placeholder="Collaborator Email" aria-label="email" required>
                                                                    </div>
                                                                    <div class="col-3">
                                                                        <select class="form-select" name="permission">
                                                                            <option value="edit" selected>Editor</option>
                                                                            <option value="view">Viewer</option>
                                                                        </select>                                                                    
                                                                    </div>
                                                                    <div class="col-3">
                                                                        <button type="submit" class="btn btn-primary col"><i class="bi bi-plus"></i> Collaborator</button>
                                                                    </div>
                                                                </div>
                                                            </form>

                                                            @if ($syllabiUsers[$syllabus->id]->count() < 1)
                                                                <div class="alert alert-warning wizard">
                                                                    <i class="bi bi-exclamation-circle-fill"></i>You have not added any collaborators to this syllabus yet.                    
                                                                </div>
                                                            @else
                                                                <table class="table table-light borderless" >
                                                                    <tr class="table-primary">
                                                                        <th>Collaborators</th>
                                                                        <th></th>
                                                                        <th class="text-center w-25">Actions</th>
                                                                    </tr>
                                                                    @foreach($syllabiUsers[$syllabus->id] as $syllabusCollaborator)
                                                                            <tr>

                                                                                <td >
                                                                                    <b>{{$syllabusCollaborator->name}} @if ($syllabusCollaborator->email == $user->email) (Me) @endif</b>
                                                                                    <p>{{$syllabusCollaborator->email}}</p>
                                                                                </td>
                                                                                <td>@switch ($syllabusCollaborator->pivot->permission) 
                                                                                        @case(1)
                                                                                            <b><i>Owner</i></b>
                                                                                            @break
                                                                                        @case(2)
                                                                                            Editor
                                                                                            @break
                                                                                        @case(3)
                                                                                            Viewer
                                                                                            @break
                                                                                    @endswitch
                                                                                </td>
                                                                                @if ($syllabusCollaborator->pivot->permission == 1)
                                                                                    <td></td>
                                                                                @else
                                                                                    <td class="text-center">
                                                                                        <form action="{{route('syllabus.unassign', $syllabus->id)}}" method="POST">
                                                                                            @csrf
                                                                                            {{method_field('DELETE')}}
                                                                                            <input type="hidden" class="form-check-input" name="email" value="{{$syllabusCollaborator->email}}">
                                                                                            <button type="submit" class="btn btn-danger btn-sm">Unassign</button>
                                                                                        </form>
                                                                                    </td>
                                                                                @endif
                                                                            </tr>
                                                                    @endforeach
                                                                </table>
                                                            @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- End of syllabus collaborator modal -->

                                        <!-- Delete Syllabus Confirmation Modal -->
                                        <div class="modal fade" id="deleteSyllabusConfirmation{{$index}}" tabindex="-1" role="dialog" aria-labelledby="deleteConfirmation{{$index}}" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="">Delete Syllabus Confirmation</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>

                                                    <div class="modal-body">
                                                    Are you sure you want to delete syllabus {{$syllabus->course_code}} {{$syllabus->course_num}}?
                                                    </div>

                                                    <form action="{{route('syllabus.delete', $syllabus->id)}}" method="POST">
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
                                    </td>
                                </tr>
                                </tbody>
                                @endforeach
                                <!--Displays MySyllabus-->
                                @if (count($mySyllabi->where('userPermission', 2)) > 0)
                                    <tr>
                                        <th colspan="6" class="table-secondary">Syllabi I Can Edit</th>
                                    </tr>
                                @endif
                                @foreach ($mySyllabi->where('userPermission', 2)->values() as $index => $syllabus)

                                <!-- Displays 'My Courses' -->
                                <tbody>
                                <tr>
                                    <!-- index -->
                                    <th scope="row">
                                        {{$index + 1}}
                                    </th>
                                    <!-- course title -->
                                    <td>
                                        <a href="{{route('syllabus', $syllabus->id)}}">{{$syllabus->course_title}}</a>
                                    </td>
                                    <!-- course code -->
                                    <td>
                                        {{$syllabus->course_code}} {{$syllabus->course_num}}
                                    </td>
                                    <!-- term -->
                                    <td>
                                        {{$syllabus->course_year}} {{$syllabus->course_term}}
                                    </td>
                                    <td>
                                        {{$syllabus->timeSince}}
                                    </td>
                                    <td></td>
                                </tr>
                                </tbody>
                                @endforeach
                                <!--Displays MySyllabus-->
                                @if (count($mySyllabi->where('userPermission', 3)) > 0)
                                    <tr>
                                        <th colspan="6" class="table-secondary">Syllabi I Can View</th>
                                    </tr>
                                @endif
                                @foreach ($mySyllabi->where('userPermission', 3)->values() as $index => $syllabus)

                                <!-- Displays 'My Courses' -->
                                <tbody>
                                <tr>
                                    <!-- index -->
                                    <th scope="row">
                                        {{$index + 1}}
                                    </th>
                                    <!-- course title -->
                                    <td>
                                        <a href="{{route('syllabus', $syllabus->id)}}">{{$syllabus->course_title}}</a>
                                    </td>
                                    <!-- course code -->
                                    <td>
                                        {{$syllabus->course_code}} {{$syllabus->course_num}}
                                    </td>
                                    <!-- term -->
                                    <td>
                                        {{$syllabus->course_year}} {{$syllabus->course_term}}
                                    </td>
                                    <td>
                                        {{$syllabus->timeSince}}
                                    </td>
                                    <td></td>
                                </tr>
                                </tbody>
                                @endforeach
                            </table>
                        @else
                        @endif
                    </div>
                </div>
                <!-- End of My Syllabi Section -->
        </div>
    </div>
</div>

                                <!-- Create Program Modal -->
                                <div class="modal fade" id="createProgramModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Create a Program</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
            </div>
            <form method="POST" action="{{ action('ProgramController@store') }}">
                                            @csrf
                                            <div class="modal-body">
                                                <div class="form-group row">
                                                    <label for="program" class="col-md-3 col-form-label text-md-right"><span class="requiredField">* </span>Program Name</label>
                                                    <div class="col-md-8">
                                                        <input id="program" placeholder="E.g. Bachelor of Sustainability" type="text" class="form-control @error('program') is-invalid @enderror" name="program" required autofocus>
                                                        @error('program')
                                                        <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                        </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="faculty" class="col-md-3 col-form-label text-md-right">Faculty/School</label>
                                                    <div class="col-md-8">
                                                        <select id='faculty' class="custom-select" name="faculty" required>
                                                            <option disabled selected hidden>Open this select menu</option>
                                                            <option value="School of Engineering">School of Engineering</option>
                                                            <option value="Okanagan School of Education">Okanagan School of Education </option>
                                                            <option value="Faculty of Arts and Social Sciences">Faculty of Arts and Social Sciences </option>
                                                            <option value="Faculty of Creative and Critical Studies">Faculty of Creative and Critical Studies</option>
                                                            <option value="Faculty of Science">Faculty of Science </option>
                                                            <option value="School of Health and Exercise Sciences">School of Health and Exercise Sciences</option>
                                                            <option value="School of Nursing">School of Nursing </option>
                                                            <option value="School of Social Work">School of Social Work</option>
                                                            <option value="Faculty of Management">Faculty of Management</option>
                                                            <option value="Faculty of Medicine">Faculty of Medicine</option>
                                                            <option value="College of Graduate studies">College of Graduate studies</option>
                                                            <option value="Other">Other</option>
                                                        </select>
                                                        @error('faculty')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="department" class="col-md-3 col-form-label text-md-right">Department</label>
                                                    <div class="col-md-8">
                                                        <select id="department" class="custom-select" name="department">
                                                            <option disabled selected hidden>Open this select menu</option>
                                                            <optgroup label="Faculty of Arts and Social Sciences ">
                                                                <option value="Community, Culture and Global Studies">Community, Culture and Global Studies</option>
                                                                <option value="Economics, Philosophy and Political Science">Economics, Philosophy and Political
                                                                Science</option>
                                                                <option value="History and Sociology">History and Sociology</option>
                                                                <option value="Psychology">Psychology</option>
                                                            </optgroup>
                                                            <optgroup label="Faculty of Creative and Critical Studies ">
                                                                <option value="Creative Studies">Creative Studies</option>
                                                                <option value="Languages and World Literature">Languages and World Literature</option>
                                                                <option value="English and Cultural Studies">English and Cultural Studies</option>
                                                            </optgroup>
                                                            <optgroup label="Faculty of Science">
                                                                <option value="Biology">Biology</option>
                                                                <option value="Chemistry">Chemistry</option>
                                                                <option value="Computer Science, Mathematics, Physics and Statistics">Computer Science,
                                                                Mathematics, Physics and Statistics</option>
                                                                <option value="Earth, Environmental and Geographic Sciences">Earth, Environmental and Geographic
                                                                Sciences</option>
                                                            </optgroup>
                                                                <option value="Other">Other</option>
                                                        </select>
                                                        @error('department')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="level" class="col-md-3 col-form-label text-md-right"><span class="requiredField">* </span>Level</label>
                                                    <div class="col-md-6">
                                                        <div class="form-check ">
                                                            <label class="form-check-label">
                                                                <input type="radio" class="form-check-input" name="level" value="Undergraduate" required>
                                                                Undergraduate
                                                            </label>
                                </div>
                                <div class="form-check">
                                                            <label class="form-check-label">
                                                                <input type="radio" class="form-check-input" name="level" value="Graduate">
                                                                Graduate
                                                            </label>
                                </div>
                                <div class="form-check">
                                                            <label class="form-check-label">
                                                                <input type="radio" class="form-check-input" name="level" value="Other">
                                                                Other
                                                            </label>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" class="form-check-input" name="user_id" value={{$user->id}}>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary col-2 btn-sm" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary col-2 btn-sm">Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<!-- End Create Program Modal -->

<!-- Create Course Modal -->
<div class="modal fade" id="createCourseModal" tabindex="-1" role="dialog" aria-labelledby="createCourseModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createCourseModalLabel">Create a Course</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                <form id="createCourse" method="POST" action="{{ action('CourseController@store') }}">
                        @csrf
                    <div class="modal-body">


                            <div class="form-group row">
                                <label for="course_code" class="col-md-3 col-form-label text-md-right"><span class="requiredField">* </span>Course
                                    Code</label>

                            <div class="col-md-8">
                                    <input id="course_code" type="text"
                                        pattern="[A-Za-z]+"
                                        minlength="1"
                                        maxlength="4"
                                        class="form-control @error('course_code') is-invalid @enderror"
                                        name="course_code" required autofocus>

                                    @error('course_code')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                    <small id="helpBlock" class="form-text text-muted">
                                        Maximum of four letter course code e.g. SUST, ASL, COSC etc.
                                    </small>
                            </div>
                        </div>

                            <div class="form-group row">
                                <label for="course_num" class="col-md-3 col-form-label text-md-right"><span class="requiredField">* </span>Course
                                    Number</label>

                            <div class="col-md-8">
                                <input id="course_num" type="text"
                                        class="form-control @error('course_num') is-invalid @enderror" name="course_num"
                                        required autofocus>

                                @error('course_num')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                            <div class="form-group row">
                                <label for="course_title" class="col-md-3 col-form-label text-md-right"><span class="requiredField">* </span>Course Title</label>

                                <div class="col-md-8">
                                    <input id="course_title" type="text"
                                        class="form-control @error('course_title') is-invalid @enderror"
                                        name="course_title" required autofocus>

                                @error('course_title')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                            <div class="form-group row">
                                <label for="course_title" class="col-md-3 col-form-label text-md-right"><span class="requiredField">* </span>Term and Year</label>

                            <div class="col-md-3">
                                <select id="course_semester" class="form-control @error('course_semester') is-invalid @enderror"
                                        name="course_semester" required autofocus>
                                    <option value="W1">Winter Term 1</option>
                                    <option value="W2">Winter Term 2</option>
                                    <option value="S1">Summer Term 1</option>
                                    <option value="S2">Summer Term 2</option>

                                    @error('course_semester')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </select>
                            </div>

                            <div class="col-md-2 float-right">
                                <select id="course_year" class="form-control @error('course_year') is-invalid @enderror"
                                    name="course_year" required autofocus>
                                    <option value="2023">2023</option>
                                    <option value="2022">2022</option>
                                    <option value="2021">2021</option>
                                    <option value="2020">2020</option>
                                    <option value="2019">2019</option>

                                    @error('course_year')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                </select>
                            </div>

                        </div>

                        <div class="form-group row">
                            <label for="course_section" class="col-md-3 col-form-label text-md-right">Course Section</label>
                            <div class="col-md-4">
                                <input id="course_section" type="text"
                                        class="form-control @error('course_section') is-invalid @enderror"
                                        name="course_section" autofocus>

                                @error('course_section')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                            <div class="form-group row">
                                <label for="delivery_modality" class="col-md-3 col-form-label text-md-right"><span class="requiredField">* </span>Mode of Delivery</label>

                                <div class="col-md-3 float-right">
                                    <select id="delivery_modality" class="form-control @error('delivery_modality') is-invalid @enderror"
                                    name="delivery_modality" required autofocus>
                                        <option value="O">Online</option>
                                        <option value="I">In-person</option>
                                        <option value="B">Hybrid</option>

                                    @error('delivery_modality')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </select>
                            </div>
                        </div>

                            <div class="form-group row">
                                <label for="standard_category_id" class="col-md-3 col-form-label text-md-right"><span class="requiredField">* </span>Map my course against</label>
                                <div class="col-md-8">
                                    <select class="form-control" name="standard_category_id" id="standard_category_id" required>
                                        <option value="" disabled selected hidden>Please Choose...</option>
                                        @foreach($standard_categories as $standard_category)
                                            <option value="{{ $standard_category->standard_category_id }}">{{$standard_category->sc_name}}</option>
                                        @endforeach
                                    </select>
                                    <small id="helpBlock" class="form-text text-muted">
                                        These are the standards from the Ministry of Advanced Education in BC.
                                    </small>
                                </div>
                            </div>
                        </div>
                    
                
                <input type="hidden" class="form-check-input" name="user_id" value={{Auth::id()}}>
                <input type="hidden" class="form-check-input" name="type" value="unassigned">
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary col-2 btn-sm" data-dismiss="modal">Close</button>
                    <button id="submit" type="submit" class="btn btn-primary col-2 btn-sm">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End Create Course Modal -->


<script type="application/javascript">
    $(document).ready(function () {
        // Enables functionality of tool tips
        $('[data-toggle="tooltip"]').tooltip({html:true});
    });

    $('somthingdumb').on('click', function(){
        
    })
</script>

<style> 
.tooltip-inner {
    text-align: left;
}
</style>

@endsection