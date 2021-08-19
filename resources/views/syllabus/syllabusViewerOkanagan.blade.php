
@extends('layouts.app')

@section('content')

<div class="card">
    <!-- header -->
    <div class="card-header wizard ">
        <h4>
            <b>Syllabus: </b> <span class="fs-5">{{$syllabus->course_title}}, {{$syllabus->course_code}} {{$syllabus->course_num}}</span>
        </h4>
    </div>
    <!-- body -->
    <div class="card-body">
    </div>
    <!-- footer -->
    <div class="card-footer p-4">
        <button type="submit" name="download" value="1" class="btn btn-primary col-2 btn-sm m-2 float-right" form="sylabusGenerator">Download <i class="bi bi-download"></i></button>
    </div>
</div>

@endsection

