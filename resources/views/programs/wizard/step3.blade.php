@extends('layouts.app')

@section('content')

<div>
    <div class="row justify-content-center">
        <div class="col-md-12">
            @include('programs.wizard.header')

            <div class="card">
                <h3 class="card-header wizard" >
                    Courses
                </h3>

                <div class="card-body">
                    <h6 class="card-subtitle text-center mb-4 lh-lg">
                        Add required and non-required courses to this program. After adding courses to this program, each course can be mapped to the Program Learning Outcomes (PLOs) of this Program (identified in step 1). Once all courses have been individually mapped to this program’s PLOs, you can visit the “Program Summary/Step 4” to see the learning outcomes map of the program.

                    </h6>
                    <h6 class="card-subtitle wizard text-primary fw-bold">
                        Note: Only course owners or editors can map the course to these program’s PLOs.
                    </h6>
                    <ul>
                        <li>If you are the owner or an editor of a course, you will need go to your dashboard, select the course, identify the course learning outcomes in step 1 (if not already done) and map them to this program’s PLOs (step 5).</li>
                        <li>Otherwise, you can let the course owner know that this program has been created and that their course can now be mapped to the PLOs by clicking the ‘Ask Instructor to Map Course’ button.</li>
                    </ul>
                    
                    <div class="row mb-2">
                        <div class="col">
                            <button type="button" class="btn btn-primary btn-sm col-2 mt-2 float-right" data-toggle="modal" data-target="#createCourseModal" style="background-color:#002145;color:white;"><i class="bi bi-plus pr-2"></i>New Course</button>
                            <button type="button" class="btn btn-primary btn-sm col-2 mt-2 float-right" data-toggle="modal" data-target="#addCourseModal" style="margin-right: 10px; background-color:#002145;color:white;"><i class="bi bi-plus pr-2"></i>Existing Course</button>
                        </div>
                    </div>

                    <div id="courses">
                        <div class="row">
                            <div class="col">
                                @if ($programCourses->count() < 1)
                                    <div class="alert alert-warning wizard">
                                        <div class="notes"><i class="bi bi-exclamation-circle-fill pr-2 fs-5"></i>There are no courses set for this program yet.</div>                    
                                    </div>
                                @else 
                                    <table class="table table-light table-bordered" >
                                        <tr class="table-primary">
                                            <th class="w-25">Course Title</th>
                                            <th>Course Code</th>
                                            <th>Term</th>
                                            <th><i class="bi bi-exclamation-circle-fill" style="font-style:normal;" data-toggle="tooltip" data-html="true" data-bs-placement="right" title="<ol><li><b>Not Mapped:</b> The course instructor has <b>not</b> mapped their course learning outcomes to the program learning outcomes.</li><li><b>Partially Mapped:</b> The course instructor has mapped <b>some</b> of their course learning outcomes to the program learning outcomes.</li><li><b>Mapped:</b> The course instructor has mapped <b>all</b> of their course learning outcomes to the program learning outcomes.</li></ol>"> Mapped to Program</i></th>
                                            <th class="text-center">Actions</th>
                                        </tr>

                                        @foreach($programCourses as $programCourse)
                                        <tr>
                                            @if($programCourse->pivot->note != NULL)
                                                <td>
                                                    {{$programCourse->course_title}}
                                                    <br>
                                                    <p class="mb-0 form-text text-muted">
                                                        @if($programCourse->pivot->course_required == 1)
                                                            Required 
                                                        @elseif($programCourse->pivot->course_required == 0)
                                                            Not Required 
                                                        @endif
                                                    </p>
                                                    <p class="form-text text-muted">
                                                        <b>Note: </b>{{$programCourse->pivot->note}}   
                                                    </p>                                    
                                                </td>
                                            @else
                                                <td>
                                                    {{$programCourse->course_title}}
                                                    <br>
                                                    <p class="form-text text-muted">
                                                        @if($programCourse->pivot->course_required == 1)
                                                            Required 
                                                        @elseif($programCourse->pivot->course_required == 0)
                                                            Not Required 
                                                        @endif
                                                    </p>                                   
                                                </td>
                                            @endif
                                            <td>
                                                {{$programCourse->course_code}} {{$programCourse->course_num}}
                                            </td>
                                            <td>
                                                {{$programCourse->year}} {{$programCourse->semester}}
                                            </td>
                                            <td class="text-center">
                                                @if($actualTotalOutcomes[$programCourse->course_id] == 0)
                                                    <i class="bi bi-exclamation-circle-fill text-danger pr-2"></i>Not Mapped
                                                @elseif ($actualTotalOutcomes[$programCourse->course_id] < $expectedTotalOutcomes[$programCourse->course_id])
                                                    <i class="bi bi-exclamation-circle-fill text-warning pr-2"></i>Partially Mapped
                                                @else
                                                    <i class="bi bi-check-circle-fill text-success pr-2"></i>Completed
                                                @endif
                                            </td>
                                            <td>
                                                <!-- Delete button -->
                                                <button style="width:70px" type="submit" class="btn btn-danger btn-sm float-right ml-2" data-toggle="modal" data-target="#deleteConfirmationCourse{{$programCourse->course_id}}">
                                                    Remove
                                                </button>

                                                <!-- Edit button -->
                                                <button type="button" style="width:60px" class="btn btn-secondary btn-sm float-right ml-2" data-toggle="modal" data-target="#editCourseModal{{$programCourse->course_id}}">
                                                    Edit
                                                </button>

                                                @if($actualTotalOutcomes[$programCourse->course_id] != $expectedTotalOutcomes[$programCourse->course_id])
                                                    <!-- If the User has been notified previously -->
                                                    @if($programCourse->pivot->map_status == 1)
                                                        <button type="button" class="btn btn-success btn-sm ml-2 float-right" disabled>
                                                            <i class="bi bi-check2-circle"></i> Notified
                                                        </button>
                                                    @elseif($programCourse->owners[0]->id == $user->id)
                                                        <!-- Allow owner to be redirected to the course to map it -->
                                                        <a type="button" class="btn btn-outline-primary btn-sm ml-2 float-right" href="{{ route('courseWizard.step1', $programCourse->course_id) }}">
                                                            Map Course
                                                        </a>
                                                    @endif
                                                    @foreach($programCourse->editors as $editor)
                                                        @if($editor->id == $user->id && $programCourse->pivot->map_status != 1)
                                                            <!-- Show Only If the User is not the Owner and if they haven't previously notified the instructor -->
                                                            <a type="button" class="btn btn-outline-primary btn-sm ml-2 float-right" href="{{ route('courseWizard.step1', $programCourse->course_id) }}">
                                                                Map Course
                                                            </a>
                                                            <button type="button" class="btn btn-outline-primary btn-sm ml-2 float-right" data-toggle="modal" data-target="#emailInstructorToMapCourse{{$programCourse->course_id}}">
                                                                Ask to Map Course
                                                            </button>
                                                        @endif
                                                    @endforeach
                                                    @foreach($programCourse->viewers as $viewer)
                                                        @if($viewer->id == $user->id && $programCourse->pivot->map_status != 1)
                                                            <!-- Show Only If the User is not the Owner and if they haven't previously notified the instructor -->
                                                            <button type="button" class="btn btn-outline-primary btn-sm ml-2 float-right" data-toggle="modal" data-target="#emailInstructorToMapCourse{{$programCourse->course_id}}">
                                                                Ask to Map Course
                                                            </button>
                                                        @endif
                                                    @endforeach
                                                @endif
                                                
                                                <!-- Delete Confirmation Modal -->
                                                <div class="modal fade" id="deleteConfirmationCourse{{$programCourse->course_id}}" tabindex="-1" role="dialog" aria-labelledby="deleteConfirmationCourse" aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">Remove Confirmation</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>

                                                            <div class="modal-body">
                                                            Are you sure you want to remove {{$programCourse->course_code . ' ' . $programCourse->course_num}} ?
                                                            </div>

                                                            <form action="{{route('courses.remove', $programCourse->course_id)}}" method="POST" class="float-right ml-2">
                                                                @csrf
                                                                {{method_field('GET')}}
                                                                <input type="hidden" class="form-check-input " name="program_id" value={{$program->program_id}}>
                                                                <div class="modal-footer">
                                                                <button style="width:60px" type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
                                                                <button style="width:70px" type="submit" class="btn btn-danger btn-sm">Remove</button>
                                                                </div>
                                                            </form>

                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <!-- Edit Course Required Modal -->
                                                <div class="modal fade" id="editCourseModal{{$programCourse->course_id}}" tabindex="-1" role="dialog" aria-labelledby="editCourseModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-lg" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="editCourseModalLabel">Edit Course</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                            </div>
                                                            <form method="POST" action="{{ route('courseProgram.editCourseRequired', $program->program_id) }}">
                                                                @csrf

                                                                <div class="modal-body">

                                                                    <div class="form-group row">
                                                                        <label for="required"
                                                                            class="col-md-3 col-form-label text-md-right">Required</label>
                                                                        <div class="col-md-6">

                                                                                @if($programCourse->pivot->course_required == 0)
                                                                                    <div class="form-check ">
                                                                                        <label class="form-check-label">
                                                                                            <input type="radio" class="form-check-input" name="required" value="1">
                                                                                            Required
                                                                                        </label>
                                                                                    </div>
                                                                                    <div class="form-check">
                                                                                        <label class="form-check-label">
                                                                                            <input type="radio" class="form-check-input" name="required" value="0" checked>
                                                                                            Not Required
                                                                                        </label>
                                                                                    </div>
                                                                                @else
                                                                                    <div class="form-check ">
                                                                                        <label class="form-check-label">
                                                                                            <input type="radio" class="form-check-input" name="required" value="1" checked>
                                                                                            Required
                                                                                        </label>
                                                                                    </div>
                                                                                    <div class="form-check">
                                                                                        <label class="form-check-label">
                                                                                            <input type="radio" class="form-check-input" name="required" value="0" >
                                                                                            Not Required
                                                                                        </label>
                                                                                    </div>
                                                                                @endif
                                                                                <small class="form-text text-muted">
                                                                                    Is this course required by the program?
                                                                                </small>
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group row">
                                                                        <label for="required" class="col-md-3 col-form-label text-md-right">Note</label>
                                                                        <div class="col-md-6">

                                                                            <div class="form">
                                                                                @if ($programCourse->pivot->note != NULL)
                                                                                    <textarea name="note" class="form-textarea w-100" rows="2" maxlength="40">{{$programCourse->pivot->note}}</textarea>
                                                                                @else
                                                                                    <textarea name="note" class="form-textarea w-100" rows="2" maxlength="40"></textarea>
                                                                                @endif
                                                                                <small class="form-text text-muted">
                                                                                    You may add a note to further categorize courses (E.g. Chemistry Specialization). The note can not be greater than <b>40 characters.</b>
                                                                                </small>
                                                                            </div>

                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <input type="hidden" class="form-input" name="course_id" value="{{$programCourse->course_id}}">
                                                                    <input type="hidden" class="form-input" name="program_id" value="{{$program->program_id}}">
                                                                    <input type="hidden" class="form-check-input" name="user_id" value="{{Auth::id()}}">

                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary col-2 btn-sm" data-dismiss="modal">Close</button>
                                                                    <button type="submit" class="btn btn-primary col-2 btn-sm">Save</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Ask to Map Course Modal -->
                                                <div class="modal fade" id="emailInstructorToMapCourse{{$programCourse->course_id}}" tabindex="-1" role="dialog" aria-labelledby="emailInstructorToMapCourse" aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">Email Course Instructor to Map this Course</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                            Are you sure you want to email the instructor of {{$programCourse->course_code . ' ' . $programCourse->course_num}} to ask them to map their course to your program?
                                                            </div>
                                                            <form action="{{route('courses.emailCourseInstructor', $programCourse->course_id)}}" method="POST" class="float-right ml-2">
                                                                @csrf
                                                                {{method_field('GET')}}
                                                                <input type="hidden" class="form-check-input " name="program_owner_id" value={{$user->id}}>
                                                                <input type="hidden" class="form-check-input " name="course_owner_id" value={{$programCourse->owners[0]->id}}>
                                                                <input type="hidden" class="form-check-input " name="program_id" value={{$program->program_id}}>
                                                                <div class="modal-footer">
                                                                    <button style="width:60px" type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
                                                                    <button style="width:100px" type="submit" class="btn btn-primary btn-sm">Yes, Email</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>                                        
                                        </tr>
                                        @endforeach
                                    </table>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Create Course Modal -->
                    <div class="modal fade" id="createCourseModal" tabindex="-1" role="dialog" aria-labelledby="createCourseModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="createCourseModalLabel">Create Course</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form method="POST" action="{{ action('CourseController@store') }}">
                                    @csrf
                                    <div class="modal-body">

                                        <div class="form-group row">
                                            <label for="course_code"
                                                class="col-md-3 col-form-label text-md-right"><span class="requiredField">*</span>Course Code</label>

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
                                                        Maximum of Four letter course code e.g. SUST, ASL, COSC etc.
                                                    </small>
                                                </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="course_num" class="col-md-3 col-form-label text-md-right"><span class="requiredField">*</span>Course
                                                Number</label>

                                            <div class="col-md-8">
                                                <input id="course_num" type="text"
                                                    class="form-control @error('course_num') is-invalid @enderror"
                                                    name="course_num" required autofocus>

                                                @error('course_num')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="course_title"
                                                class="col-md-3 col-form-label text-md-right"><span class="requiredField">*</span>Course Title</label>

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
                                            <label for="course_title" class="col-md-3 col-form-label text-md-right"><span class="requiredField">*</span>Year and Semester</label>

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
                                                    <option value="2021">2021</option>
                                                    <option value="2020">2020</option>
                                                    <option value="2019">2019</option>
                                                    <option value="2018">2018</option>
                                                    <option value="2017">2017</option>
                                                    <option value="2016">2016</option>

                                                @error('course_year')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                                </select>
                                            </div>

                                        </div>

                                        <div class="form-group row">
                                            <label for="course_section" class="col-md-3 col-form-label text-md-right">Course
                                                Section</label>

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
                                            <label for="delivery_modality" class="col-md-3 col-form-label text-md-right"><span class="requiredField">*</span>Mode of Delivery</label>

                                            <div class="col-md-3 float-right">
                                                <select id="delivery_modality" class="form-control @error('delivery_modality') is-invalid @enderror"
                                                name="delivery_modality" required autofocus>
                                                    <option value="O">online</option>
                                                    <option value="I">in-person</option>
                                                    <option value="B">hybrid</option>

                                                @error('delivery_modality')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                                </select>
                                            </div>
                                        </div>

                                        <!-- Passes Information for Ministry Standards -->
                                        <div class="form-group row">
                                            <label for="standard_category_id" class="col-md-3 col-form-label text-md-right"><span class="requiredField">*</span>Map this course against</label>
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

                                        <div class="form-group row">
                                            <label for="required" class="col-md-3 col-form-label text-md-right">Required</label>
                                            <div class="col-md-6">

                                            <div class="form-check">
                                                <label class="form-check-label">
                                                <input type="radio" class="form-check-input" name="required" value="1" >
                                                Required
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                <input type="radio" class="form-check-input" name="required" value="0">
                                                Not Required
                                                </label>
                                            </div>
                                            <small class="form-text text-muted">
                                                Is this course required by the program?
                                            </small>
                                            </div>
                                        </div>
                                        <!-- Passes 'program_id', type='assigned', and 'user_id' to be used by the CourseController store method -->
                                        <input type="hidden" class="form-check-input" name="program_id" value={{$program->program_id}}>
                                        <input type="hidden" class="form-check-input" name="type" value="assigned">
                                        <input type="hidden" class="form-check-input" name="user_id" value={{Auth::id()}}>

                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary col-2 btn-sm"
                                            data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary col-2 btn-sm">Add</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- End Create Course Modal -->

                    <!-- Add existing course Modal ( Drag and drop effect)-->
                    <div class="modal fade" id="addCourseModal" tabindex="-1" role="dialog" aria-labelledby="createCourseModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document" style="width:1250px;">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="createCourseModalLabel">Add Existing Courses to {{$program->program}}</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                @if (count($userCoursesNotInProgram) < 1)
                                    <div class="alert alert-warning wizard">
                                        <i class="bi bi-exclamation-circle-fill pr-2 fs-5"></i>There are no courses to assign.                    
                                    </div>
                                @else
                                    <div class="modal-body">
                                        <p>Select the courses you want to add to this program.</p>
                                        <form method="POST" id="addExistCourse" action="{{route('courseProgram.addCoursesToProgram', $program->program_id)}}">
                                            @csrf
                                            <input type="hidden" name="program_id" value="{{$program->program_id}}">
                                            <table class="table table-light table-bordered">
                                                <tr class="table-primary">
                                                    <td></td>
                                                    <th>Course Title</th>
                                                    <th>Course Code</th>
                                                    <th>Term</th>
                                                    <th>Required </i></th>
                                                </tr>
                                                @foreach($userCoursesNotInProgram as $index => $course)
                                                <tr>
                                                    <td>
                                                        <input class="form-check-input ml-0" type="checkbox" name="selectedCourses[]" value={{$course->course_id}} id="flexCheck{{$course->course_id}}">
                                                    </td>
                                                    <td>
                                                        {{$course->course_title}}
                                                    </td>
                                                    <td>
                                                        {{$course->course_code}} {{$course->course_num}}
                                                    </td>
                                                    <td>
                                                        {{$course->year}} {{$course->semester}}
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-switch">
                                                            <input class="form-check-input ml-0" name="require{{$course->course_id}}" type="checkbox" id="flexSwitchCheck{{$course->course_id}}">
                                                        </div>                                           
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </table>
                                        </form>
                                    </div> 

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary col-2 btn-sm" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary col-2 btn-sm" form="addExistCourse">Add Selected</button>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <div class="card-body mb-4">
                        <a href="{{route('programWizard.step2', $program->program_id)}}"><button class="btn btn-sm btn-primary col-3  float-left"><i class="bi bi-arrow-left ml-2"></i> Mapping Scale</button></a>
                        <a href="{{route('programWizard.step4', $program->program_id)}}"><button class="btn btn-sm btn-primary col-3 float-right">Program Overview <i class="bi bi-arrow-right ml-2"></i></button></a>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>

<script type="application/javascript">
    $(document).ready(function () {

        // Enables functionality of tool tips
        $('[data-toggle="tooltip"]').tooltip({html:true});


        $("form").submit(function () {
            // prevent duplicate form submissions
            $(this).find(":submit").attr('disabled', 'disabled');
            $(this).find(":submit").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');

            });
    });
</script>

<style> 
.tooltip-inner {
    text-align: left;
    max-width: 600px;
    width: auto; 
}
</style>
@endsection
