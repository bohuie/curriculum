@extends('layouts.app')

@section('content')
<div>
    <div class="col">
        <h3>Course: {{$course->year}} {{$course->semester}} {{$course->course_code}}{{$course->course_num}}  {{$course->section}}</h3>
        <h5 class="text-muted">{{$course->course_title}}</h5>
        <h5>Delivery modality:
        @switch($course->delivery_modality)
            @case('O')
                Online
                @break
            @case('B')
                Blended
                @break
            @default
                In-person
        @endswitch
        </h5>
    </div>

    <div class="card-footer">
        <a href="{{route('programWizard.step4', $program->program_id)}}"><button class="btn btn-sm btn-primary mt-3 col-3 float-left">Map Another Course</button></a>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {

    $("form").submit(function () {
        // prevent duplicate form submissions
        $(this).find(":submit").attr('disabled', 'disabled');
        $(this).find(":submit").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');

    });
    });
</script>
@endsection
