@extends('layouts.app')

@section('content')


<div class="container mt-4">
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
                            <button style="border: none; background: none; outline: none;" data-toggle="modal" data-target="#createProgramModal" onclick="verification()">
                                <i class="bi bi-plus-circle text-white"></i>
                            </button>
                        </div>
                    </h3>
                </div>

                @if(count($myPrograms) > 0)
                <table class="table table-hover dashBoard">
                    <thead>
                        <tr>
                            <th scope="col">Program</th>
                            <th scope="col">Faculty and Department/School</th>
                            <th scope="col">Level</th>
                            <th scope="col">Last Updated</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>

                    @if (count($myPrograms->where('userPermission', 1)) > 0)
                    <tr>
                        <th colspan="5" class="table-secondary">My Programs</th>
                    </tr>
                    @endif
                            
                    <!-- Displays 'My Programs' -->
                    @foreach ($myPrograms->where('userPermission', 1)->values() as $index => $program) 
                    <tbody>
                        <tr>
                            <td><a href="{{route('programWizard.step1', $program->program_id)}}">{{$program->program}}</a></td>
                            <td>{{$program->faculty}} </td>
                            <td>{{$program->level}}</td>
                            @if ($program->last_modified_user != NULL) 
                                <td><p data-toggle="tooltip" data-html="true" data-bs-placement="top" title="Last updated by: {{$program->last_modified_user}}">{{$program->timeSince}}</p></td>
                            @else
                                <td>{{$program->timeSince}}</td>
                            @endif
                            <td>
                                <!-- actions drop down -->
                                <div class="btn-group">
                                <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="bi bi-gear-fill"></i> </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="{{route('programWizard.step1', $program->program_id)}}">Edit</a>
                                        <!-- <a class="dropdown-item" href="#">Collaborators</a> -->
                                        <div class="dropdown-item collabIcon btn bg-transparent position-relative" data-toggle="tooltip" data-html="true" data-bs-placement="right" title="@foreach($programUsers[$program->program_id] as $counter => $programUser){{$counter + 1}}. {{$programUser->name}}<br>@endforeach" data-modal="addProgramCollaboratorsModal{{$program->program_id}}">
                                            <div>
                                                Collaborators 
                                                <!-- <i class="bi bi-person-plus-fill"></i> -->
                                                <span class="badge rounded-pill badge badge-dark">
                                                    {{ count($programUsers[$program->program_id]) }}
                                                </span> 
                                            </div>
                                        </div>
                                        <a class="dropdown-item" data-toggle="modal" data-target="#duplicateProgramConfirmation{{$program->program_id}}">Duplicate</a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item text-danger" data-toggle="modal" data-target="#deleteProgram{{$index}}" href=#>Delete</a>
                                    </div>
                                </div>
                                <!-- end drop down -->

                                <!-- Duplicate Confirmation Modal -->
                                <div class="modal fade" id="duplicateProgramConfirmation{{$program->program_id}}" tabindex="-1" role="dialog" aria-labelledby="duplicateProgramConfirmation" aria-hidden="true">
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

                                <!-- program collaborators modal -->
                                @include('programs.programCollabs', ['program-' . $program->program_id, $program->program_id])

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

                                            <div class="modal-body">Are you sure you want to delete {{$program->program}} program ?</div>

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
                            
                    <!-- Displays 'Programs I can edit' -->
                    @if (count($myPrograms->where('userPermission', 2)) > 0)
                    <tr>
                        <th colspan="6" class="table-secondary">Programs I Can Edit</th>
                    </tr>
                    @endif
                            
                    @foreach ($myPrograms->where('userPermission', 2)->values() as $index => $program) 
                    <tbody>
                        <tr>
                            <td><a href="{{route('programWizard.step1', $program->program_id)}}">{{$program->program}}</a></td>
                            <td>{{$program->faculty}} </td>
                            <td>{{$program->level}}</td>
                            @if ($program->last_modified_user != NULL) 
                                <td><p data-toggle="tooltip" data-html="true" data-bs-placement="top" title="Last updated by: {{$program->last_modified_user}}">{{$program->timeSince}}</p></td>
                            @else
                                <td>{{$program->timeSince}}</td>
                            @endif
                            <td>
                                <!-- actions drop down -->
                                <div class="btn-group">
                                <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="bi bi-gear-fill"></i> </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="{{route('programWizard.step1', $program->program_id)}}">Edit</a>
                                        <!-- <a class="dropdown-item" href="#">Collaborators</a> -->
                                        <div class="dropdown-item collabIcon btn bg-transparent position-relative" data-toggle="tooltip" data-html="true" data-bs-placement="right" title="@foreach($programUsers[$program->program_id] as $counter => $programUser){{$counter + 1}}. {{$programUser->name}}<br>@endforeach" data-modal="addProgramCollaboratorsModal{{$program->program_id}}">
                                            <div>
                                                Collaborators 
                                                <!-- <i class="bi bi-person-plus-fill"></i> -->
                                                <span class="badge rounded-pill badge badge-dark">
                                                    {{ count($programUsers[$program->program_id]) }}
                                                </span> 
                                            </div>
                                        </div>
                                        <a class="dropdown-item" data-toggle="modal" data-target="#duplicateProgramConfirmation{{$program->program_id}}">Duplicate</a>
                                    </div>
                                </div>
                                <!-- end drop down -->

                                <!-- Duplicate Confirmation Modal -->
                                <div class="modal fade" id="duplicateProgramConfirmation{{$program->program_id}}" tabindex="-1" role="dialog" aria-labelledby="duplicateProgramConfirmation" aria-hidden="true">
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

                                <!-- program collaborators modal -->
                                @include('programs.programCollabs', ['program-' . $program->program_id, $program->program_id])
                            </td>
                        </tr>
                    </tbody>
                    @endforeach
                            
                    <!-- Displays Programs I can view -->
                    @if (count($myPrograms->where('userPermission', 3)) > 0)
                    <tr>
                        <th colspan="6" class="table-secondary">Programs I Can View</th>
                    </tr>
                    @endif
                            
                    @foreach ($myPrograms->where('userPermission', 3)->values() as $index => $program) 
                    <tbody>
                        <tr>
                            <td><a href="{{route('programWizard.step1', $program->program_id)}}">{{$program->program}}</a></td>
                            <td>{{$program->faculty}} </td>
                            <td>{{$program->level}}</td>
                            @if ($program->last_modified_user != NULL) 
                                <td><p data-toggle="tooltip" data-html="true" data-bs-placement="top" title="Last updated by: {{$program->last_modified_user}}">{{$program->timeSince}}</p></td>
                            @else
                                <td>{{$program->timeSince}}</td>
                            @endif
                            <td>
                                <!-- actions drop down -->
                                <div class="btn-group">
                                <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="bi bi-gear-fill"></i> </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="{{route('programWizard.step4', $program->program_id)}}">View</a>
                                        <div class="dropdown-item collabIcon btn bg-transparent position-relative" data-toggle="tooltip" data-html="true" data-bs-placement="right" title="@foreach($programUsers[$program->program_id] as $counter => $programUser){{$counter + 1}}. {{$programUser->name}}<br>@endforeach" data-modal="addProgramCollaboratorsModal{{$program->program_id}}">
                                            <div>
                                                Collaborators 
                                                <!-- <i class="bi bi-person-plus-fill"></i> -->
                                                <span class="badge rounded-pill badge badge-dark">
                                                    {{ count($programUsers[$program->program_id]) }}
                                                </span> 
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- end drop down -->

                                <!-- program collaborators modal -->
                                @include('programs.programCollabs', ['program-' . $program->program_id, $program->program_id])
                            </td>
                        </tr>
                    </tbody>
                    @endforeach
                </table>
                @endif
            </div>
            <!-- End of Programs -->
            
            <!-- Start of Courses -->
            <div class="card shadow rounded m-4" style="border-style: solid;border-color: #1E90FF;">
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
                                    <th scope="col">Course Title</th>
                                    <th scope="col">Course Code</th>
                                    <th scope="col">Term</th>
                                    <th scope="col">Status</th>
                                    <th scope="col" class="text-center">Programs </th>
                                    <th scope="col">Last Updated</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                                
                            <!-- Displays 'My Courses' -->
                            @if (count($myCourses->where('userPermission', 1)) > 0)
                                <tr>
                                    <th colspan="7" class="table-secondary">My Courses</th>
                                </tr>
                            @endif

                            @foreach ($myCourses->where('userPermission', 1)->values() as $index => $course)
                            <tbody>
                                <tr>
                                    <!-- Courses That have Not been Completed TODO: THIS IS PROBABLY NOT NEEDED ANYMORE-->
                                    @if($course->status !== 1)
                                        <td style="max-width: 450px;"><a href="{{route('courseWizard.step1', $course->course_id)}}">{{$course->course_title}}</a></td>
                                        <td>{{$course->course_code}} {{$course->course_num}}</td>
                                        <td>{{$course->year}} {{$course->semester}}</td>
                                        <td class="align-middle">
                                            @if ($progressBar[$course->course_id] == 0)
                                                <div class="bg-transparent position-relative" data-toggle="tooltip" data-html="true" data-bs-placement="right" title="{{$progressBarMsg[$course->course_id]['statusMsg']}}">
                                                    <p class="text-center mb-0">{{$progressBar[$course->course_id]}}%</p>
                                                    <div class="progress">
                                                        <div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                </div>
                                            @elseif ($progressBar[$course->course_id] == 100)
                                                <p class="text-center mb-0">{{$progressBar[$course->course_id]}}%</p>
                                                <div class="progress">
                                                    <div class="progress-bar bg-success" role="progressbar" style="width:{{$progressBar[$course->course_id]}}%;" aria-valuenow="{{$progressBar[$course->course_id]}}" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            @else
                                                <div class="bg-transparent position-relative" data-toggle="tooltip" data-html="true" data-bs-placement="right" title="{{$progressBarMsg[$course->course_id]['statusMsg']}}">
                                                    <p class="text-center mb-0">{{$progressBar[$course->course_id]}}%</p>
                                                    <div class="progress">
                                                        <div class="progress-bar bg-info" role="progressbar" style="width:{{$progressBar[$course->course_id]}}%;" aria-valuenow="{{$progressBar[$course->course_id]}}" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
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
                                        <td style="max-width: 450px;"><a href="{{route('courseWizard.step1', $course->course_id)}}">{{$course->course_title}}</a></td>
                                        <td>{{$course->course_code}} {{$course->course_num}}</td>
                                        <td>{{$course->year}} {{$course->semester}}</td>
                                        <td class="align-middle">
                                            @if ($progressBar[$course->course_id] == 0)
                                                <div class="bg-transparent position-relative" data-toggle="tooltip" data-html="true" data-bs-placement="right" title="{{$progressBarMsg[$course->course_id]['statusMsg']}}">
                                                    <p class="text-center mb-0">{{$progressBar[$course->course_id]}}%</p>
                                                    <div class="progress">
                                                        <div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                </div>
                                            @elseif ($progressBar[$course->course_id] == 100)
                                                <p class="text-center mb-0">{{$progressBar[$course->course_id]}}%</p>
                                                <div class="progress">
                                                    <div class="progress-bar bg-success" role="progressbar" style="width:{{$progressBar[$course->course_id]}}%;" aria-valuenow="{{$progressBar[$course->course_id]}}" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            @else
                                                <div class="bg-transparent position-relative" data-toggle="tooltip" data-html="true" data-bs-placement="right" title="{{$progressBarMsg[$course->course_id]['statusMsg']}}">
                                                    <p class="text-center mb-0">{{$progressBar[$course->course_id]}}%</p>
                                                    <div class="progress">
                                                        <div class="progress-bar bg-info" role="progressbar" style="width:{{$progressBar[$course->course_id]}}%;" aria-valuenow="{{$progressBar[$course->course_id]}}" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
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
                                    @if ($course->last_modified_user != NULL) 
                                        <td><p data-toggle="tooltip" data-html="true" data-bs-placement="top" title="Last updated by: {{$course->last_modified_user}}">{{$course->timeSince}}</p></td>
                                    @else
                                        <td>{{$course->timeSince}}</td>
                                    @endif
                                    <td>
                                        <!-- actions drop down -->
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="bi bi-gear-fill"></i> </button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item" href="{{route('courseWizard.step1', $course->course_id)}}">Edit</a>
                                                <!-- <a class="dropdown-item" href="#">Collaborators</a> -->
                                                <div class="dropdown-item collabIcon btn bg-transparent position-relative" data-toggle="tooltip" data-html="true" data-bs-placement="right" title="@foreach($courseUsers[$course->course_id] as $counter => $courseUser){{$counter + 1}}. {{$courseUser->name}}<br>@endforeach" data-modal="addCourseCollaboratorsModal{{$course->course_id}}">
                                                    <div>
                                                        Collaborators 
                                                        <!-- <i class="bi bi-person-plus-fill"></i> -->
                                                        <span class="badge rounded-pill badge badge-dark">
                                                            {{ count($courseUsers[$course->course_id]) }}
                                                        </span> 
                                                    </div>
                                                </div>
                                                <a class="dropdown-item" data-toggle="modal" data-target="#duplicateCourseConfirmation{{$course->course_id}}">Duplicate</a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item text-danger" data-toggle="modal" data-target="#deleteCourseConfirmation{{$course->course_id}}" href=#>Delete</a>
                                            </div>
                                        </div>

                                        @include('courses.courseCollabs')
                                        
                                        <!-- Delete Confirmation Modal -->
                                        <div class="modal fade show" id="deleteCourseConfirmation{{$course->course_id}}" tabindex="-1" role="dialog" aria-labelledby="deleteCourseConfirmation{{$course->course_id}}" aria-hidden="true">
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

                                        <!-- Duplicate Course Confirmation Modal -->
                                        <div class="modal fade" id="duplicateCourseConfirmation{{$course->course_id}}" tabindex="-1" role="dialog" aria-labelledby="duplicateCourseConfirmation{{$course->course_id}}" aria-hidden="true">
                                            <div class="modal-dialog modal-lg" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="duplicateCourseConfirmation{{$course->course_id}}">Duplicate Course</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form action="{{ route('courses.duplicate', $course->course_id) }}" method="GET">
                                                        @csrf
                                                        {{method_field('GET')}}

                                                        <div class="modal-body">

                                                            <div class="form-group row">
                                                                <label for="course_code" class="col-md-3 col-form-label text-md-right"><span class="requiredField">*</span>Course Code</label>
                                                                <div class="col-md-8">
                                                                    <input id="course_code" type="text" pattern="[A-Za-z]+" minlength="1" maxlength="4" class="form-control @error('course_code') is-invalid @enderror" value="{{$course->course_code}}" name="course_code" required autofocus>
                                                                    @error('course_code')
                                                                        <span class="invalid-feedback" role="alert">
                                                                            <strong>{{ $message }}</strong>
                                                                        </span>
                                                                    @enderror
                                                                    <small id="helpBlock" class="form-text text-muted">
                                                                        Maximum of Four letter course code e.g. SUST, ASL, COSC etc.
                                                                    </small>
                                                                </div>
                                                            </div>

                                                            <div class="form-group row">
                                                                <label for="course_num" class="col-md-3 col-form-label text-md-right"><span class="requiredField">*</span>Course Number</label>
                                                                <div class="col-md-8">
                                                                    <input id="course_num" type="number" max="699" min="100" pattern="[0-9]*" class="form-control @error('course_num') is-invalid @enderror" name="course_num" value="{{$course->course_num}}" required autofocus>
                                                                    @error('course_num')
                                                                        <span class="invalid-feedback" role="alert">
                                                                            <strong>{{ $message }}</strong>
                                                                        </span>
                                                                    @enderror
                                                                </div>
                                                            </div>

                                                            <div class="form-group row">
                                                                <label for="course_title" class="col-md-3 col-form-label text-md-right"><span class="requiredField">*</span>Course Title</label>
                                                                <div class="col-md-8">
                                                                    <input id="course_title" type="text" class="form-control @error('course_title') is-invalid @enderror" name="course_title" value="{{$course->course_title}} - Copy" required autofocus>
                                                                    @error('course_title')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                    @enderror
                                                                </div>
                                                            </div>

                                                            <div class="form-group row">
                                                                <label for="course_section" class="col-md-3 col-form-label text-md-right">Course Section</label>
                                                                <div class="col-md-4">
                                                                    <input id="course_section" type="text" class="form-control @error('course_section') is-invalid @enderror" name="course_section" autofocus value= {{$course->section}}>
                                                                    @error('course_section')
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
                                    </td>
                                </tr>

                            </tbody>
                            @endforeach
                            
                            <!-- Displays Courses I can edit -->
                            @if (count($myCourses->where('userPermission', 2)) > 0)
                                <tr>
                                    <th colspan="7" class="table-secondary">Courses I Can Edit</th>
                                </tr>
                            @endif
                                
                            @foreach ($myCourses->where('userPermission', 2)->values() as $index => $course)
                            <tbody>
                                <tr>
                                    <!-- Courses That have Not been Completed TODO: THIS IS PROBABLY NOT NEEDED ANYMORE-->
                                    @if($course->status !== 1)
                                        <td style="max-width: 450px;"><a href="{{route('courseWizard.step1', $course->course_id)}}">{{$course->course_title}}</a></td>
                                        <td>{{$course->course_code}} {{$course->course_num}}</td>
                                        <td>{{$course->year}} {{$course->semester}}</td>
                                        <td class="align-middle">
                                            @if ($progressBar[$course->course_id] == 0)
                                                <div class="bg-transparent position-relative" data-toggle="tooltip" data-html="true" data-bs-placement="right" title="{{$progressBarMsg[$course->course_id]['statusMsg']}}">
                                                    <p class="text-center mb-0">{{$progressBar[$course->course_id]}}%</p>
                                                    <div class="progress">
                                                        <div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                </div>
                                            @elseif ($progressBar[$course->course_id] == 100)
                                                <p class="text-center mb-0">{{$progressBar[$course->course_id]}}%</p>
                                                <div class="progress">
                                                    <div class="progress-bar bg-success" role="progressbar" style="width:{{$progressBar[$course->course_id]}}%;" aria-valuenow="{{$progressBar[$course->course_id]}}" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            @else
                                                <div class="bg-transparent position-relative" data-toggle="tooltip" data-html="true" data-bs-placement="right" title="{{$progressBarMsg[$course->course_id]['statusMsg']}}">
                                                    <p class="text-center mb-0">{{$progressBar[$course->course_id]}}%</p>
                                                    <div class="progress">
                                                        <div class="progress-bar bg-info" role="progressbar" style="width:{{$progressBar[$course->course_id]}}%;" aria-valuenow="{{$progressBar[$course->course_id]}}" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
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
                                        <td style="max-width: 450px;"><a href="{{route('courseWizard.step1', $course->course_id)}}">{{$course->course_title}}</a></td>
                                        <td>{{$course->course_code}} {{$course->course_num}}</td>
                                        <td>{{$course->year}} {{$course->semester}}</td>
                                        <td class="align-middle">
                                            @if ($progressBar[$course->course_id] == 0)
                                                <div class="bg-transparent position-relative" data-toggle="tooltip" data-html="true" data-bs-placement="right" title="{{$progressBarMsg[$course->course_id]['statusMsg']}}">
                                                    <p class="text-center mb-0">{{$progressBar[$course->course_id]}}%</p>
                                                    <div class="progress">
                                                        <div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                </div>
                                            @elseif ($progressBar[$course->course_id] == 100)
                                                <p class="text-center mb-0">{{$progressBar[$course->course_id]}}%</p>
                                                <div class="progress">
                                                    <div class="progress-bar bg-success" role="progressbar" style="width:{{$progressBar[$course->course_id]}}%;" aria-valuenow="{{$progressBar[$course->course_id]}}" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            @else
                                                <div class="bg-transparent position-relative" data-toggle="tooltip" data-html="true" data-bs-placement="right" title="{{$progressBarMsg[$course->course_id]['statusMsg']}}">
                                                    <p class="text-center mb-0">{{$progressBar[$course->course_id]}}%</p>
                                                    <div class="progress">
                                                        <div class="progress-bar bg-info" role="progressbar" style="width:{{$progressBar[$course->course_id]}}%;" aria-valuenow="{{$progressBar[$course->course_id]}}" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
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
                                    @if ($course->last_modified_user != NULL) 
                                        <td><p data-toggle="tooltip" data-html="true" data-bs-placement="top" title="Last updated by: {{$course->last_modified_user}}">{{$course->timeSince}}</p></td>
                                    @else
                                        <td>{{$course->timeSince}}</td>
                                    @endif
                                    <td>
                                        <!-- actions drop down -->
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="bi bi-gear-fill"></i> </button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item" href="{{route('courseWizard.step1', $course->course_id)}}">Edit</a>
                                                <!-- <a class="dropdown-item" href="#">Collaborators</a> -->
                                                <div class="dropdown-item collabIcon btn bg-transparent position-relative" data-toggle="tooltip" data-html="true" data-bs-placement="right" title="@foreach($courseUsers[$course->course_id] as $counter => $courseUser){{$counter + 1}}. {{$courseUser->name}}<br>@endforeach" data-modal="addCourseCollaboratorsModal{{$course->course_id}}">
                                                    <div>
                                                        Collaborators 
                                                        <!-- <i class="bi bi-person-plus-fill"></i> -->
                                                        <span class="badge rounded-pill badge badge-dark">
                                                            {{ count($courseUsers[$course->course_id]) }}
                                                        </span> 
                                                    </div>
                                                </div>
                                                <a class="dropdown-item" data-toggle="modal" data-target="#duplicateCourseConfirmation{{$course->course_id}}">Duplicate</a>
                                            </div>
                                        </div>

                                        @include('courses.courseCollabs')
                                        
                                        <!-- Duplicate Course Confirmation Modal -->
                                        <div class="modal fade" id="duplicateCourseConfirmation{{$course->course_id}}" tabindex="-1" role="dialog" aria-labelledby="duplicateCourseConfirmation{{$course->course_id}}" aria-hidden="true">
                                            <div class="modal-dialog modal-lg" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="duplicateCourseConfirmation{{$course->course_id}}">Duplicate Course</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form action="{{ route('courses.duplicate', $course->course_id) }}" method="GET">
                                                        @csrf
                                                        {{method_field('GET')}}

                                                        <div class="modal-body">
                                                            
                                                            <div class="form-group row">
                                                                <label for="course_code" class="col-md-3 col-form-label text-md-right"><span class="requiredField">*</span>Course Code</label>
                                                                <div class="col-md-8">
                                                                    <input id="course_code" type="text" pattern="[A-Za-z]+" minlength="1" maxlength="4" class="form-control @error('course_code') is-invalid @enderror" value="{{$course->course_code}}" name="course_code" required autofocus>
                                                                    @error('course_code')
                                                                        <span class="invalid-feedback" role="alert">
                                                                            <strong>{{ $message }}</strong>
                                                                        </span>
                                                                    @enderror
                                                                    <small id="helpBlock" class="form-text text-muted">
                                                                        Maximum of Four letter course code e.g. SUST, ASL, COSC etc.
                                                                    </small>
                                                                </div>
                                                            </div>

                                                            <div class="form-group row">
                                                                <label for="course_num" class="col-md-3 col-form-label text-md-right"><span class="requiredField">*</span>Course Number</label>
                                                                <div class="col-md-8">
                                                                    <input id="course_num" type="number" max="699" min="100" pattern="[0-9]*" class="form-control @error('course_num') is-invalid @enderror" name="course_num" value="{{$course->course_num}}" required autofocus>
                                                                    @error('course_num')
                                                                        <span class="invalid-feedback" role="alert">
                                                                            <strong>{{ $message }}</strong>
                                                                        </span>
                                                                    @enderror
                                                                </div>
                                                            </div>

                                                            <div class="form-group row">
                                                                <label for="course_title" class="col-md-3 col-form-label text-md-right"><span class="requiredField">*</span>Course Title</label>
                                                                <div class="col-md-8">
                                                                    <input id="course_title" type="text" class="form-control @error('course_title') is-invalid @enderror" name="course_title" value="{{$course->course_title}} - Copy" required autofocus>
                                                                    @error('course_title')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                    @enderror
                                                                </div>
                                                            </div>

                                                            <div class="form-group row">
                                                                <label for="course_section" class="col-md-3 col-form-label text-md-right">Course Section</label>
                                                                <div class="col-md-4">
                                                                    <input id="course_section" type="text" class="form-control @error('course_section') is-invalid @enderror" name="course_section" autofocus value= {{$course->section}}>
                                                                    @error('course_section')
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
                                    </td>
                                </tr>
                            </tbody>
                            @endforeach

                            <!-- Courses I can view -->
                            @if (count($myCourses->where('userPermission', 3)) > 0)
                                <tr>
                                    <th colspan="7" class="table-secondary">Courses I Can View</th>
                                </tr>
                            @endif
                                
                            @foreach ($myCourses->where('userPermission', 3)->values() as $index => $course)
                            <tbody>
                                <tr>
                                    <!-- Courses That have Not been Completed TODO: THIS IS PROBABLY NOT NEEDED ANYMORE-->
                                    @if($course->status !== 1)
                                        <td style="max-width: 450px;"><a href="{{route('courseWizard.step1', $course->course_id)}}">{{$course->course_title}}</a></td>
                                        <td>{{$course->course_code}} {{$course->course_num}}</td>
                                        <td>{{$course->year}} {{$course->semester}}</td>
                                        <td class="align-middle">
                                            @if ($progressBar[$course->course_id] == 0)
                                                <div class="bg-transparent position-relative" data-toggle="tooltip" data-html="true" data-bs-placement="right" title="{{$progressBarMsg[$course->course_id]['statusMsg']}}">
                                                    <p class="text-center mb-0">{{$progressBar[$course->course_id]}}%</p>
                                                    <div class="progress">
                                                        <div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                </div>
                                            @elseif ($progressBar[$course->course_id] == 100)
                                                <p class="text-center mb-0">{{$progressBar[$course->course_id]}}%</p>
                                                <div class="progress">
                                                    <div class="progress-bar bg-success" role="progressbar" style="width:{{$progressBar[$course->course_id]}}%;" aria-valuenow="{{$progressBar[$course->course_id]}}" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            @else
                                                <div class="bg-transparent position-relative" data-toggle="tooltip" data-html="true" data-bs-placement="right" title="{{$progressBarMsg[$course->course_id]['statusMsg']}}">
                                                    <p class="text-center mb-0">{{$progressBar[$course->course_id]}}%</p>
                                                    <div class="progress">
                                                        <div class="progress-bar bg-info" role="progressbar" style="width:{{$progressBar[$course->course_id]}}%;" aria-valuenow="{{$progressBar[$course->course_id]}}" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
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
                                        <td style="max-width: 450px;"><a href="{{route('courseWizard.step1', $course->course_id)}}">{{$course->course_title}}</a></td>
                                        <td>{{$course->course_code}} {{$course->course_num}}</td>
                                        <td>{{$course->year}} {{$course->semester}}</td>
                                        <td class="align-middle">
                                            @if ($progressBar[$course->course_id] == 0)
                                                <div class="bg-transparent position-relative" data-toggle="tooltip" data-html="true" data-bs-placement="right" title="{{$progressBarMsg[$course->course_id]['statusMsg']}}">
                                                    <p class="text-center mb-0">{{$progressBar[$course->course_id]}}%</p>
                                                    <div class="progress">
                                                        <div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                </div>
                                            @elseif ($progressBar[$course->course_id] == 100)
                                                <p class="text-center mb-0">{{$progressBar[$course->course_id]}}%</p>
                                                <div class="progress">
                                                    <div class="progress-bar bg-success" role="progressbar" style="width:{{$progressBar[$course->course_id]}}%;" aria-valuenow="{{$progressBar[$course->course_id]}}" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            @else
                                                <div class="bg-transparent position-relative" data-toggle="tooltip" data-html="true" data-bs-placement="right" title="{{$progressBarMsg[$course->course_id]['statusMsg']}}">
                                                    <p class="text-center mb-0">{{$progressBar[$course->course_id]}}%</p>
                                                    <div class="progress">
                                                        <div class="progress-bar bg-info" role="progressbar" style="width:{{$progressBar[$course->course_id]}}%;" aria-valuenow="{{$progressBar[$course->course_id]}}" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
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
                                    @if ($course->last_modified_user != NULL) 
                                        <td><p data-toggle="tooltip" data-html="true" data-bs-placement="top" title="Last updated by: {{$course->last_modified_user}}">{{$course->timeSince}}</p></td>
                                    @else
                                        <td>{{$course->timeSince}}</td>
                                    @endif
                                    <td>
                                        <!-- actions drop down -->
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="bi bi-gear-fill"></i> </button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item" href="{{route('courseWizard.step7', $course->course_id)}}">View</a>
                                                <!-- <a class="dropdown-item" href="#">Collaborators</a> -->
                                                <div class="dropdown-item collabIcon btn bg-transparent position-relative" data-toggle="tooltip" data-html="true" data-bs-placement="right" title="@foreach($courseUsers[$course->course_id] as $counter => $courseUser){{$counter + 1}}. {{$courseUser->name}}<br>@endforeach" data-modal="addCourseCollaboratorsModal{{$course->course_id}}">
                                                    <div>
                                                        Collaborators 
                                                        <!-- <i class="bi bi-person-plus-fill"></i> -->
                                                        <span class="badge rounded-pill badge badge-dark">
                                                            {{ count($courseUsers[$course->course_id]) }}
                                                        </span> 
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        @include('courses.courseCollabs')
                                    </td>
                                </tr>
                            </tbody>
                            @endforeach    
                        </table>    
                    @endif
                </div>
            </div>
            <!-- End of Courses -->

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
                                    <th scope="col">Course Title</th>
                                    <th scope="col">Course Code</th>
                                    <th scope="col">Term</th>
                                    <th scope="col">Last Updated</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                                
                            <!--Displays MySyllabus-->
                            @if (count($mySyllabi->where('userPermission', 1)) > 0)
                                <tr>
                                    <th colspan="5" class="table-secondary">My Syllabi</th>
                                </tr>
                            @endif
                                
                            @foreach ($mySyllabi->where('userPermission', 1)->values() as $index => $syllabus)
                            <!-- Displays 'My Syllabi' -->
                            <tbody>
                                <tr>
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
                                    @if ($syllabus->last_modified_user != NULL) 
                                        <td><p data-toggle="tooltip" data-html="true" data-bs-placement="top" title="Last updated by: {{$syllabus->last_modified_user}}">{{$syllabus->timeSince}}</p></td>
                                    @else
                                        <td>{{$syllabus->timeSince}}</td>
                                    @endif
                                    <td>
                                        <!-- actions drop down -->
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="bi bi-gear-fill"></i> </button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item" href="{{route('syllabus', $syllabus->id)}}">Edit</a>
                                                <!-- <a class="dropdown-item" href="#">Collaborators</a> -->
                                                <div class="dropdown-item collabIcon btn bg-transparent position-relative" data-toggle="tooltip" data-html="true" data-bs-placement="right" title="@foreach($syllabiUsers[$syllabus->id] as $userIndex => $syllabusUser){{$userIndex + 1}}. {{$syllabusUser->name}}<br>@endforeach" data-modal="addSyllabusCollaboratorsModal{{$syllabus->id}}">
                                                    <div>
                                                        Collaborators 
                                                        <!-- <i class="bi bi-person-plus-fill"></i> -->
                                                        <span class="badge rounded-pill badge badge-dark">
                                                            {{ count($syllabiUsers[$syllabus->id]) }}
                                                        </span> 
                                                    </div>
                                                </div>
                                                <a class="dropdown-item" data-toggle="modal" data-target="#duplicateSyllabusConfirmation{{$syllabus->id}}">Duplicate</a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item text-danger" data-toggle="modal" data-target="#deleteSyllabusConfirmation{{$syllabus->id}}" href=#>Delete</a>
                                            </div>
                                        </div>


                                        @include('syllabus.syllabusCollabs')

                                        <!-- Delete Syllabus Confirmation Modal -->
                                        <div class="modal fade" id="deleteSyllabusConfirmation{{$syllabus->id}}" tabindex="-1" role="dialog" aria-labelledby="deleteSyllabusConfirmation{{$syllabus->id}}" aria-hidden="true">
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

                                        <!-- Duplicate Confirmation Modal -->
                                        <div class="modal fade" id="duplicateSyllabusConfirmation{{$syllabus->id}}" tabindex="-1" role="dialog" aria-labelledby="duplicateSyllabusConfirmation{{$syllabus->id}}" aria-hidden="true">
                                            <div class="modal-dialog modal-lg" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Duplicate Syllabus</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form action="{{ route('syllabus.duplicate', $syllabus->id) }}" method="GET">
                                                        @csrf
                                                        {{method_field('GET')}}
                                                        <div class="modal-body">

                                                            <div class="form-group row">
                                                                <label for="course_code" class="col-md-3 col-form-label text-md-right"><span class="requiredField">*</span>Course Code</label>
                                                                <div class="col-md-8">
                                                                    <input id="course_code" type="text" pattern="[A-Za-z]+" minlength="1" maxlength="4" class="form-control @error('course_code') is-invalid @enderror" value="{{$syllabus->course_code}}" name="course_code" required autofocus>
                                                                    @error('course_code')
                                                                        <span class="invalid-feedback" role="alert">
                                                                            <strong>{{ $message }}</strong>
                                                                        </span>
                                                                    @enderror
                                                                    <small id="helpBlock" class="form-text text-muted">
                                                                        Maximum of Four letter course code e.g. SUST, ASL, COSC etc.
                                                                    </small>
                                                                </div>
                                                            </div>

                                                            <div class="form-group row">
                                                                <label for="course_num" class="col-md-3 col-form-label text-md-right"><span class="requiredField">*</span>Course Number</label>
                                                                <div class="col-md-8">
                                                                    <input id="course_num" type="number" max="699" min="100" pattern="[0-9]*" class="form-control @error('course_num') is-invalid @enderror" name="course_num" value="{{$syllabus->course_num}}" required autofocus>
                                                                    @error('course_num')
                                                                        <span class="invalid-feedback" role="alert">
                                                                            <strong>{{ $message }}</strong>
                                                                        </span>
                                                                    @enderror
                                                                </div>
                                                            </div>

                                                            <div class="form-group row">
                                                                <label for="course_title" class="col-md-3 col-form-label text-md-right"><span class="requiredField">*</span>Course Title</label>
                                                                <div class="col-md-8">
                                                                    <input id="course_title" type="text" class="form-control @error('course_title') is-invalid @enderror" name="course_title" value="{{$syllabus->course_title}} - Copy" required autofocus>
                                                                    @error('course_title')
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
                                    </td>
                                </tr>
                            </tbody>
                                
                            @endforeach
                            
                            <!--Displays Syllabi I can Edit-->
                            @if (count($mySyllabi->where('userPermission', 2)) > 0)
                                <tr>
                                    <th colspan="6" class="table-secondary">Syllabi I Can Edit</th>
                                </tr>
                            @endif
                                
                            @foreach ($mySyllabi->where('userPermission', 2)->values() as $index => $syllabus)
                            <tbody>
                                <tr>
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
                                    @if ($syllabus->last_modified_user != NULL) 
                                        <td><p data-toggle="tooltip" data-html="true" data-bs-placement="top" title="Last updated by: {{$syllabus->last_modified_user}}">{{$syllabus->timeSince}}</p></td>
                                    @else
                                        <td>{{$syllabus->timeSince}}</td>
                                    @endif
                                    <td>
                                        <!-- actions drop down -->
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="bi bi-gear-fill"></i> </button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item" href="{{route('syllabus', $syllabus->id)}}">Edit</a>
                                                <!-- <a class="dropdown-item" href="#">Collaborators</a> -->
                                                <div class="dropdown-item collabIcon btn bg-transparent position-relative" data-toggle="tooltip" data-html="true" data-bs-placement="right" title="@foreach($syllabiUsers[$syllabus->id] as $userIndex => $syllabusUser){{$userIndex + 1}}. {{$syllabusUser->name}}<br>@endforeach" data-modal="addSyllabusCollaboratorsModal{{$syllabus->id}}">
                                                    <div>
                                                        Collaborators 
                                                        <!-- <i class="bi bi-person-plus-fill"></i> -->
                                                        <span class="badge rounded-pill badge badge-dark">
                                                            {{ count($syllabiUsers[$syllabus->id]) }}
                                                        </span> 
                                                    </div>
                                                </div>
                                                <a class="dropdown-item" data-toggle="modal" data-target="#duplicateSyllabusConfirmation{{$syllabus->id}}">Duplicate</a>
                                            </div>
                                        </div>

                                        @include('syllabus.syllabusCollabs')

                                        <!-- Duplicate Confirmation Modal -->
                                        <div class="modal fade" id="duplicateSyllabusConfirmation{{$syllabus->id}}" tabindex="-1" role="dialog" aria-labelledby="duplicateSyllabusConfirmation{{$syllabus->id}}" aria-hidden="true">
                                            <div class="modal-dialog modal-lg" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Duplicate Syllabus</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form action="{{ route('syllabus.duplicate', $syllabus->id) }}" method="GET">
                                                        @csrf
                                                        {{method_field('GET')}}
                                                        <div class="modal-body">

                                                            <div class="form-group row">
                                                                <label for="course_code" class="col-md-3 col-form-label text-md-right"><span class="requiredField">*</span>Course Code</label>
                                                                <div class="col-md-8">
                                                                    <input id="course_code" type="text" pattern="[A-Za-z]+" minlength="1" maxlength="4" class="form-control @error('course_code') is-invalid @enderror" value="{{$syllabus->course_code}}" name="course_code" required autofocus>
                                                                    @error('course_code')
                                                                        <span class="invalid-feedback" role="alert">
                                                                            <strong>{{ $message }}</strong>
                                                                        </span>
                                                                    @enderror
                                                                    <small id="helpBlock" class="form-text text-muted">
                                                                        Maximum of Four letter course code e.g. SUST, ASL, COSC etc.
                                                                    </small>
                                                                </div>
                                                            </div>

                                                            <div class="form-group row">
                                                                <label for="course_num" class="col-md-3 col-form-label text-md-right"><span class="requiredField">*</span>Course Number</label>
                                                                <div class="col-md-8">
                                                                    <input id="course_num" type="number" max="699" min="100" pattern="[0-9]*" class="form-control @error('course_num') is-invalid @enderror" name="course_num" value="{{$syllabus->course_num}}" required autofocus>
                                                                    @error('course_num')
                                                                        <span class="invalid-feedback" role="alert">
                                                                            <strong>{{ $message }}</strong>
                                                                        </span>
                                                                    @enderror
                                                                </div>
                                                            </div>

                                                            <div class="form-group row">
                                                                <label for="course_title" class="col-md-3 col-form-label text-md-right"><span class="requiredField">*</span>Course Title</label>
                                                                <div class="col-md-8">
                                                                    <input id="course_title" type="text" class="form-control @error('course_title') is-invalid @enderror" name="course_title" value="{{$syllabus->course_title}} - Copy" required autofocus>
                                                                    @error('course_title')
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
                                    </td>
                                </tr>
                            </tbody>
                            @endforeach
                                
                            <!--Displays Syllabi I can View -->
                            @if (count($mySyllabi->where('userPermission', 3)) > 0)
                                <tr>
                                    <th colspan="6" class="table-secondary">Syllabi I Can View</th>
                                </tr>
                            @endif
                            
                            @foreach ($mySyllabi->where('userPermission', 3)->values() as $index => $syllabus)
                            <tbody>
                                <tr>
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
                                    @if ($syllabus->last_modified_user != NULL) 
                                        <td><p data-toggle="tooltip" data-html="true" data-bs-placement="top" title="Last updated by: {{$syllabus->last_modified_user}}">{{$syllabus->timeSince}}</p></td>
                                    @else
                                        <td>{{$syllabus->timeSince}}</td>
                                    @endif
                                    <td>
                                        <!-- actions drop down -->
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="bi bi-gear-fill"></i> </button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item" href="{{route('syllabus', $syllabus->id)}}">View</a>
                                                <!-- <a class="dropdown-item" href="#">Collaborators</a> -->
                                                <div class="dropdown-item collabIcon btn bg-transparent position-relative" data-toggle="tooltip" data-html="true" data-bs-placement="right" title="@foreach($syllabiUsers[$syllabus->id] as $userIndex => $syllabusUser){{$userIndex + 1}}. {{$syllabusUser->name}}<br>@endforeach" data-modal="addSyllabusCollaboratorsModal{{$syllabus->id}}">
                                                    <div>
                                                        Collaborators 
                                                        <!-- <i class="bi bi-person-plus-fill"></i> -->
                                                        <span class="badge rounded-pill badge badge-dark">
                                                            {{ count($syllabiUsers[$syllabus->id]) }}
                                                        </span> 
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        @include('syllabus.syllabusCollabs')
                                    </td>
                                </tr>
                            </tbody>
                            @endforeach
                        </table>
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
                                                
                    <!-- <div class="form-group row">
                        <label for="faculty" class="col-md-3 col-form-label text-md-right"><span class="requiredField">* </span>Faculty/School</label>
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
                                    <option value="Economics, Philosophy and Political Science">Economics, Philosophy and Political Science</option>
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
                                    <option value="Computer Science, Mathematics, Physics and Statistics">Computer Science, Mathematics, Physics and Statistics</option>
                                    <option value="Earth, Environmental and Geographic Sciences">Earth, Environmental and Geographic Sciences</option>
                                </optgroup>
                                <option value="Other">Other</option>
                            </select>
                            @error('department')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div> -->
                                            
                    <!-- Campus -->
                    <div class="form-group row">
                        <label for="campus" class="col-md-3 col-form-label text-md-right">Campus</label>
                        <div class="col-md-8">
                            <select id="campus" class="custom-select" name="campus">
                                <option disabled selected hidden>Open list of campuses</option>
                                @foreach ($campuses as $campus)
                                    <option value="{{$campus->campus}}">{{$campus->campus}}</option>
                                @endforeach
                                <option value="Other">Other</option>
                            </select>
                            <input id='campus-text' class="form-control campus_text" name="campus" type="text" placeholder="(Optional) Enter the campus name" disabled hidden></input>
                            @error('campus')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>

                    <!-- Faculty - dropdown -->
                    <div class="form-group row">
                        <label for="faculty" class="col-md-3 col-form-label text-md-right">Faculty/School</label>
                        <div class="col-md-8">
                            <select id="faculty" class="custom-select" name="faculty" disabled>
                                <option disabled selected hidden>Open list of faculties/schools</option>
                            </select>
                            <input id='faculty-text' class="form-control faculty_text" name="faculty" type="text" placeholder="(Optional) Enter the faculty/school" disabled hidden></input>
                            @error('faculty')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>

                    <!-- Department -->
                    <div class="form-group row">
                        <label for="department" class="col-md-3 col-form-label text-md-right">Department</label>
                        <div class="col-md-8">
                            <select id="department" class="custom-select department_select" name="department" disabled>
                                <option disabled selected hidden>Open list of departments</option>
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
                            <label for="course_code" class="col-md-3 col-form-label text-md-right"><span class="requiredField">* </span>Course Code</label>

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
                                    <input id="course_num" type="text" class="form-control @error('course_num') is-invalid @enderror" name="course_num" required autofocus>

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
    var faculties = {!! json_encode($faculties, JSON_HEX_TAG) !!};
    var vFaculties = faculties.filter(item => {
        return item.campus_id === 1;
    });
    var oFaculties = faculties.filter(item => {
        return item.campus_id === 2;
    });
    var departments = {!! json_encode($departments, JSON_HEX_TAG) !!};

    $(document).ready(function () {
        // Enables functionality of tool tips
        $('[data-toggle="tooltip"]').tooltip({html:true});

        $('.collabIcon').click(function(event) {
            var modalId = event.currentTarget.dataset['modal'];
            var modal = new bootstrap.Modal(document.getElementById(modalId));
            modal.show();
        });

        // var faculties = {!! json_encode($faculties, JSON_HEX_TAG) !!};
        // var vFaculties = faculties.filter(item => {
        //     return item.campus_id === 1;
        // });
        // var oFaculties = faculties.filter(item => {
        //     return item.campus_id === 2;
        // });
        
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
        $('#faculty').text('');
        $('#faculty-text').prop( "hidden", false );
        $('#faculty-text').prop( "disabled", false );
        $('#department').prop( "disabled", true );
        $('#department').text('');
        $('#department-text').prop( "hidden", false );
        $('#department-text').prop( "disabled", false );
    }

    function loadFaculties() {
        
    }

    // function verification() {
    //     if ($('#campus').find(':selected').text() !== 'Open list of campuses') {
            
    //         if ($('#campus').find(':selected').text() !== 'Other') {
    //             alert('campus selected');
    //             // load faculties based on campus

    //             //TODO CHECK WHEN OTHER IS SELECTED FOR FACULTY AND DEPARTMENT
    //             if ($('#faculty').find(':selected').text() !== 'Open list of faculties/schools') {
    //                 alert('faculty selected');

    //                 if ($('#department').find(':selected').text() !== 'Open list of departments') {
    //                     alert('department selected');

    //                 }
    //             }
    //         } else {
    //             campusOtherOption();
    //         }
    
    //     } else {
    //         //
    //         alert('new state'); 
    //     }
    // }
    function verification() {
        console.log('here');
        console.log(vFaculties);
        if ($('#campus').find(':selected').text() !== 'Open list of campuses') {
            
            if ($('#campus').find(':selected').text() !== 'Other') {
                if ($('#faculty').val() == '') {
                    alert('true');
                }
            } else {
                campusOtherOption();
            }
    
        } else {
            //
            alert('new state'); 
        }
    }

</script>

<style> 
.tooltip-inner {
    text-align: left;
    max-width: 600px;
    width: auto;
}
</style>

@endsection