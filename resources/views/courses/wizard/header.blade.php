
<div class="mt-4 mb-5">
    <div class="row">
        <div class="col">
            <h3>Course: {{$course->year}} {{$course->semester}} {{$course->course_code}} {{$course->course_num}} {{$course->section}}</h3>
            <h5 class="text-muted">{{$course->course_title}}</h5>
            <h5>Mode of Delivery:
            @switch($course->delivery_modality)
                @case('O')
                    Online
                    @break
                @case('B')
                    Hybrid
                    @break
                @case('M')
                    Multi-Access
                @break
                @default
                    In-person
            @endswitch
            </h5>
        </div>
        <div class="col">
        @if (!$isViewer)
            <div class="row my-2">
                <div class="col">
                <button type="button" style="width:200px" class="btn btn-success btn-sm float-right" data-toggle="modal" data-target="#duplicateCourse" >Duplicate Course</button>
                    <!-- Duplicate Course Confirmation Modal -->
                    <div class="modal fade" id="duplicateCourse" tabindex="-1" role="dialog" aria-labelledby="duplicateCourse" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="duplicateCourse">Duplicate Course</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form action="{{ route('courses.duplicate', $course->course_id) }}" method="POST">
                                    @csrf
                                    {{method_field('POST')}}
                                    
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
                </div>
            </div>
        @endif
        @if (!$isEditor && !$isViewer) 
                <div class="row">
                    <div class="col">
                        <!-- Edit button -->
                        <button type="button" class="btn btn-secondary btn-sm float-right" style="width:200px" data-toggle="modal" data-target="#editCourseModal{{$course->course_id}}">
                            Edit Course Information
                        </button>
                        <!-- Edit Course Modal -->
                        <div class="modal fade" id="editCourseModal{{$course->course_id}}" tabindex="-1" role="dialog" aria-labelledby="editCourseModalLabel"aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editCourseModalLabel">Edit Course information</h5>
                                        <button type="button" class="close"data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>

                                    <form method="POST" action="{{ action([\App\Http\Controllers\CourseController::class, 'update'], $course->course_id) }}">
                                        @csrf
                                        {{method_field('PUT')}}

                                        <div class="modal-body">
                                            <div class="form-group row">
                                                <label for="course_code" class="col-md-3 col-form-label text-md-right"><span class="requiredField">*</span>Course Code</label>

                                                <div class="col-md-8">
                                                    <input id="course_code" type="text" pattern="[A-Za-z]+" minlength="1" maxlength="4" class="form-control @error('course_code') is-invalid @enderror" value="{{$course->course_code}}"
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
                                                <label for="course_num" class="col-md-3 col-form-label text-md-right">Course Number</label>

                                                <div class="col-md-8">
                                                    <input id="course_num" type="text" class="form-control @error('course_num') is-invalid @enderror" name="course_num" value="{{$course->course_num}}" autofocus>

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
                                                    <input id="course_title" type="text" class="form-control @error('course_title') is-invalid @enderror" name="course_title" value="{{$course->course_title}}" required autofocus>

                                                    @error('course_title')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label for="course_semester" class="col-md-3 col-form-label text-md-right"><span class="requiredField">*</span>Term and Year</label>

                                                <div class="col-md-3">
                                                    <select id="course_semester" class="form-control @error('course_semester') is-invalid @enderror"
                                                        name="course_semester" required autofocus>
                                                        <option @if($course->semester === "W1") selected @endif value="W1">Winter Term 1</option>
                                                        <option @if($course->semester === "W2") selected @endif value="W2">Winter Term 2</option>
                                                        <option @if($course->semester === "S1") selected @endif value="S1">Summer Term 1</option>
                                                        <option @if($course->semester === "S2") selected @endif value="S2">Summer Term 2</option>

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
                                                        <option @if($course->year === 2030) selected @endif value="2030">2030</option>
                                                        <option @if($course->year === 2029) selected @endif value="2029">2029</option>
                                                        <option @if($course->year === 2028) selected @endif value="2028">2028</option>
                                                        <option @if($course->year === 2027) selected @endif value="2027">2027</option>
                                                        <option @if($course->year === 2026) selected @endif value="2026">2026</option>
                                                        <option @if($course->year === 2025) selected @endif value="2025">2025</option>
                                                        <option @if($course->year === 2024) selected @endif value="2024">2024</option>
                                                        <option @if($course->year === 2023) selected @endif value="2023">2023</option>
                                                        <option @if($course->year === 2022) selected @endif value="2022">2022</option>
                                                        <option @if($course->year === 2021) selected @endif value="2021">2021</option>
                                                        <option @if($course->year === 2020) selected @endif value="2020">2020</option>
                                                        <option @if($course->year === 2019) selected @endif value="2019">2019</option>
                                                        <option @if($course->year === 2018) selected @endif value="2018">2018</option>
                                                        <option @if($course->year === 2017) selected @endif value="2017">2017</option>

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
                                                name="course_section" autofocus value= {{$course->section}}>

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
                                                        <option @if($course->delivery_modality === 'O') selected @endif value="O">Online</option>
                                                        <option @if($course->delivery_modality === 'I') selected @endif value="I">In-person</option>
                                                        <option @if($course->delivery_modality === 'B') selected @endif value="B">Hybrid</option>
                                                        <option @if($course->delivery_modality === 'M') selected @endif value="M">Multi-Access</option>

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
                                                        <option value="{{$course->standard_category_id}}" selected hidden>{{$course->standardCategory->sc_name}}</option>
                                                        @foreach($standard_categories as $standard_category)
                                                            <option value="{{ $standard_category->standard_category_id }}">{{$standard_category->sc_name}}</option>
                                                        @endforeach
                                                    </select>
                                                    <small id="helpBlock" class="form-text text-danger">
                                                        Warning: Changing the standards will overwrite any previously saved standard mapping outcomes.
                                                    </small>
                                                    <small id="helpBlock" class="form-text text-muted">
                                                        These are the standards from the Ministry of Post-Secondary Education and Future Skills in BC.
                                                    </small>
                                                </div>
                                            </div>

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
                        <!-- Assign instructor button  -->
                        <button type="button" class="btn btn-outline-primary btn-sm float-right" style="width:200px"
                            data-bs-toggle="modal" data-bs-target="#addCourseCollaboratorsModal{{$course->course_id}}">Add Collaborators</button>
                    </div>
                    @include('courses.courseCollabs')
                </div>

                <div class="row">
                    <div class="col">
                            <button type="button" style="width:200px" class="btn btn-danger btn-sm float-right"
                            data-toggle="modal" data-target="#deleteConfirmation" >Delete Course</button>

                        <!-- Delete Confirmation Modal -->
                        <div class="modal fade" id="deleteConfirmation" tabindex="-1" role="dialog" aria-labelledby="deleteConfirmation" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="deleteConfirmation">Delete Confirmation</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>

                                    <div class="modal-body">
                                    Are you sure you want to delete {{$course->course_code}} {{$course->course_num}} ?
                                    </div>

                                    <form action="{{route('courses.destroy', $course->course_id)}}" method="POST">
                                        @csrf
                                        {{method_field('DELETE')}}
                                        <input type="hidden" class="form-check-input " name="program_id"
                                            value={{$course->program_id}}>

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

            </div>
        @endif

    </div>
    @if (!$isViewer)
        <!-- progress bar -->
        <div class="mt-5">
            <table class="table table-borderless text-center table-sm" style="table-layout: fixed; width: 100%">
                <tbody>
                    <tr>

                        <td><a class="btn @if (Route::current()->getName() == 'courseWizard.step1') btn-primary @else @if ($course->learningOutcomes->count() < 1) btn-secondary @else btn-success @endif @endif" href="{{route('courseWizard.step1', $course->course_id)}}" style="width: 30px; height: 30px; padding: 6px 0px; border-radius: 15px; text-align: center; font-size: 12px; line-height: 1.42857;">
                                <b>1</b> </a></td>
                        <td><a class="btn @if (Route::current()->getName() == 'courseWizard.step2') btn-primary @else @if ($course->assessmentMethods->count() < 1) btn-secondary @else btn-success @endif @endif" href="{{route('courseWizard.step2', $course->course_id)}}" style="width: 30px; height: 30px; padding: 6px 0px; border-radius: 15px; text-align: center; font-size: 12px; line-height: 1.42857;">
                                <b>2</b> </a></td>
                        <td><a class="btn @if (Route::current()->getName() == 'courseWizard.step3') btn-primary @else @if ($course->learningActivities->count() < 1) btn-secondary @else btn-success @endif @endif" href="{{route('courseWizard.step3', $course->course_id)}}" style="width: 30px; height: 30px; padding: 6px 0px; border-radius: 15px; text-align: center; font-size: 12px; line-height: 1.42857;">
                                <b>3</b> </a></td>
                        <td><a class="btn @if (Route::current()->getName() == 'courseWizard.step4') btn-primary @else @if ($oAct < 1 && $oAss < 1) btn-secondary @elseif (! $hasNonAlignedCLO) btn-success @else btn-warning @endif @endif" href="{{route('courseWizard.step4', $course->course_id)}}"
                                style="width: 30px; height: 30px; padding: 6px 0px; border-radius: 15px; text-align: center; font-size: 12px; line-height: 1.42857;">
                                <b>4</b> </a></td>
                        <td><a class="btn @if (Route::current()->getName() == 'courseWizard.step5') btn-primary @else @if ($outcomeMapsCount < 1) btn-secondary @elseif ($outcomeMapsCount >= $expectedProgramOutcomeMapCount) btn-success @else btn-warning @endif @endif" href="{{route('courseWizard.step5', $course->course_id)}}"
                                style="width: 30px; height: 30px; padding: 6px 0px; border-radius: 15px; text-align: center; font-size: 12px; line-height: 1.42857;">
                                <b>5</b> </a></td>
                        <td><a class="btn @if (Route::current()->getName() == 'courseWizard.step6') btn-primary @else @if ($standardsOutcomeMapCount < 1) btn-secondary @elseif ($standardsOutcomeMapCount == $expectedStandardOutcomeMapCount) btn-success @else btn-warning @endif @endif" href="{{route('courseWizard.step6', $course->course_id)}}"
                                style="width: 30px; height: 30px; padding: 6px 0px; border-radius: 15px; text-align: center; font-size: 12px; line-height: 1.42857;">
                                <b>6</b> </a></td>
                        <td><a class="btn @if (Route::current()->getName() == 'courseWizard.step7') btn-primary @else btn-secondary @endif" href="{{route('courseWizard.step7', $course->course_id)}}"
                                style="width: 30px; height: 30px; padding: 6px 0px; border-radius: 15px; text-align: center; font-size: 12px; line-height: 1.42857;">
                                <b>7</b> </a></td>
                    </tr>
                    <tr>
                        <td>Course Learning Outcomes</td>
                        <td>Student Assessment Methods</td>
                        <td>Teaching and Learning Activities</td>
                        <td>Course Alignment</td>
                        <td>Program Outcome Mapping</td>
                        <td>BC Degree Standards and Strategic Priorities</td>
                        <td>Course Summary</td>
                    </tr>
                </tbody>
            </table>
        </div>
    @endif
</div>
<script type="application/javascript">
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
</script>
