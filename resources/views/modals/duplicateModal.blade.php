<div class="modal fade" id="duplicateConfirmation" tabindex="-1" role="dialog" aria-labelledby="duplicateConfirmation" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Duplicate Syllabus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>            
            </div>
            
            <form action="{{ route('syllabus.duplicate', $syllabus->id) }}" method="GET">
                @csrf
                {{method_field('GET')}}
                <div class="modal-body text-start">
                    <div class="form-group row">
                        <label for="course_code" class="col-md-3 col-form-label">Course Code <span class="requiredField">*</span></label>
                        <div class="col-md-8">
                            <input id="course_code" type="text" pattern="[A-Za-z]+" minlength="1" oninput="validateMaxlength()" onpaste="validateMaxlength()" maxlength="4" class="form-control @error('course_code') is-invalid @enderror" value="{{$syllabus->course_code}}" name="course_code" required autofocus>
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
                        <label for="course_num" class="col-md-3 col-form-label">Course Number <span class="requiredField">*</span></label>
                        <div class="col-md-8">
                            <input id="course_num" type="number" pattern="[0-9]*" class="form-control @error('course_num') is-invalid @enderror" name="course_num" value="{{$syllabus->course_num}}" required autofocus>
                            @error('course_num')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="course_title" class="col-md-3 col-form-label">Course Title <span class="requiredField">*</span></label>
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
                    <button type="button" class="btn btn-secondary col-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success col-3"><i class="bi bi-files"></i> Duplicate</button>
                </div>
            </form>
        </div>
    </div>
</div>
