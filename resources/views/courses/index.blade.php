@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-12">
        <h1>My Courses</h1>
    <p class="form-text text-muted">See below the courses you have mapped using this tool (under Completed Courses) and those you are still working on (In Progress Courses).
        </p>
    <p class="form-text text-primary font-weight-bold"><i>Note:</i>  If you are ideating/evaluating a program, go to <a href="/construction">"My Programs"</a>.
        This section should only be used for courses that you are not associating with a specific program.</p>


        <div class="row">
            <div class="col">
                <button type="button" class="btn btn-primary btn-sm col-2 mt-2 float-right" data-toggle="modal"
                    data-target="#createCourseModal">
                    ＋ Create Course
                </button>
            </div>
        </div>

        <!-- Create Course Modal -->
        <div class="modal fade" id="createCourseModal" tabindex="-1" role="dialog" aria-labelledby="createCourseModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createCourseModalLabel">Add Course</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <form id="createCourse" method="POST" action="{{ action('CourseController@store') }}">
                        @csrf
                        <div class="modal-body">


                            <div class="form-group row">
                                <label for="course_code" class="col-md-3 col-form-label text-md-right">Course
                                    Code</label>

                                <div class="col-md-8">
                                    <input id="course_code" type="text"
                                        pattern="[A-Za-z]{4}"
                                        class="form-control @error('course_code') is-invalid @enderror"
                                        name="course_code" required autofocus>

                                    @error('course_code')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                    <small id="helpBlock" class="form-text text-muted">
                                        Four letter course code e.g. SUST, COSC etc.
                                    </small>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="course_num" class="col-md-3 col-form-label text-md-right">Course
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
                                <label for="course_title" class="col-md-3 col-form-label text-md-right">
                                    Course Title</label>

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
                                <label for="course_title" class="col-md-3 col-form-label text-md-right">Term and Year</label>

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
                                <label for="delivery_modality" class="col-md-3 col-form-label text-md-right">
                                    Delivery Modality
                                </label>

                                <div class="col-md-3 float-right">
                                    <select id="delivery_modality" class="form-control @error('delivery_modality') is-invalid @enderror"
                                    name="delivery_modality" required autofocus>
                                        <option value="O">online</option>
                                        <option value="I">in-person</option>
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
                                <label for="program_id" class="col-md-3 col-form-label text-md-right"> Map this course against</label>
                                <div class="col-md-8">
                                    <select class="form-control" name="program_id" id="program_id" required>
                                        <option value="" disabled selected hidden>Please Choose...</option>
                                        <option value="1">Bachelor's degree level standards</option>
                                        <option value="2">Master's degree level standards</option>
                                        <option value="3">Doctoral degeree level standards</option>
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
                            <button type="button" class="btn btn-secondary col-2 btn-sm"
                                data-dismiss="modal">Close</button>
                            <button id="submit" type="submit" class="btn btn-primary col-2 btn-sm">Add</button>
                        </div>
                    </form>
                </div>

            </div>

        </div>
    </div>
</div>


<div class="card mb-5 mt-5">
    <div class="card-header">
        <b>In Progress Courses:</b> <span class="form-text text-muted"> Courses you are working on.</span>
    </div>
    @if(count($activeCourses)>0)


    <div class="card-body">
        <p class="form-text text-muted">To edit a course from this list, click on its title.</p>

        <div class="card">
            <div class="card-header">
                Courses Not Associated With a Program
            </div>
            <div class="card-body">
                <table class="table">
                    <tbody>
                        @foreach($activeCourses as $course)
                           
                                <tr class="border">
                                    <td><a href="{{route('courseWizard.step1', $course->course_id)}}">{{$course->year}} {{$course->semester}}
                                        {{$course->course_code}}{{$course->course_num}} {{$course->section}}
                                        - {{$course->course_title}} </a></td>

                                    <td>❗In Progress</td>

                                </tr>
                            @endif
                        @endforeach

                    </tbody>
                </table>

            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                Courses Associated With a Program
            </div>
            <div class="card-body">
                <table class="table">
                    <tbody>
                        @foreach($activeCourses as $course)
                            @if($course->program_id !== 1 ?? $course->program_id !== 2 ?? $course->program_id !== 3 )
                                <tr class="border">
                                    <td><a href="{{route('courseWizard.step1', $course->course_id)}}">{{$course->year}} {{$course->semester}}
                                        {{$course->course_code}}{{$course->course_num}} {{$course->section}}
                                        - {{$course->course_title}} </a></td>
                                    <td>{{$course->program}} <br>{{$course->faculty}} <br>{{$course->department}} <br> {{$course->level}} </td>
                                    <td>❗In Progress</td>

                                </tr>
                            @endif
                        @endforeach

                    </tbody>
                </table>

            </div>
        </div>
    </div>
    @else

    <div class="card-body">
        <div class="row">
            <div class="col">
                You have no active courses.
            </div>
        </div>
    </div>




    @endif

</div>

<div class="card mb-5">
    <div class="card-header">
        <b>Completed Courses</b> <span class="form-text text-muted"> Courses already mapped.</span>
    </div>
    @if(count($archivedCourses)>0)


    <div class="card-body">
        <p class="form-text text-muted"> This list shows courses that you have mapped previously. From this list, you can choose the
            course you want to review.</p>

        <table class="table">
            <tbody>
                @foreach($archivedCourses as $course)
                <tr class="border">
                    <td>{{$course->year}} {{$course->semester}}
                        {{$course->course_code}}{{$course->course_num}} {{$course->section}} - {{$course->course_title}}</td>

                    @if($course->program_id !== 1 ?? $course->program_id !== 2 ?? $course->program_id !== 3 )
                        <td>{{$course->program}} <br>{{$course->faculty}} <br> {{$course->level}} </td>
                    @else
                        <td></td>
                    @endif

                    <td>
                        <a class="btn btn-secondary btn-sm" style="width:60px"
                            href="{{route('courses.edit', $course->course_id)}}" role="button">Edit</a>
                        <a class="btn btn-outline-secondary btn-sm" style="width:60px"
                            href="{{route('courses.summary', $course->course_id)}}" role="button">View</a>
                    </td>
                    <td>✔️Completed</td>



                </tr>
                @endforeach

            </tbody>
        </table>
    </div>
    @else

    <div class="card-body">
        <div class="row">
            <div class="col">
                You have no completed courses.
            </div>
        </div>
    </div>




    @endif



</div>
</div>
</div>

<script type="text/javascript">
    $(document).ready(function() {

        $("form").submit(function () {
    // prevent duplicate form submissions
        $(this).find(":submit").attr('disabled', 'disabled');
        $(this).find(":submit").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');

    });
});
</script>
@endsection
