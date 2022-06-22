<!-- Delete Syllabus Confirmation Modal -->
<div class="modal fade" id="deleteSyllabusConfirmation" tabindex="-1" role="dialog"
    aria-labelledby="deleteSyllabusConfirmation" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="">Delete Syllabus Confirmation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>            
            </div>

            <div class="modal-body text-start">
                Are sure want to delete syllabus {{$syllabus->course_code}} {{$syllabus->course_num}}?
            </div>

            <form action="{{route('syllabus.delete', $syllabus->id)}}" method="POST">
                @csrf
                {{ method_field("DELETE") }}

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary col-3" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-danger col-3">
                        <i class="bi bi-trash-fill"></i> Delete
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
