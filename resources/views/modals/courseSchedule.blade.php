<div id="createCourseScheduleTblModal" class="modal fade" data-bs-backdrop="static" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create a Course Schedule Table</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="createCourseScheduleTblForm">
                <div class="modal-body">
                    <div class="row g-3 mb-2">
                        <div class="col-6">
                            <label for="courseScheduleTblRowsCount" class="form-label">Number of Rows</label>
                            <input id="courseScheduleTblRowsCount" name="numRows" type="number" min="1" max="42" step="1" class="form-control">
                        </div>
                        <div class="col-6">
                            <label for="courseScheduleTblColsCount" class="form-label">Number of Columns</label>
                            <input id="courseScheduleTblColsCount" name="numCols" type="number" min="1" max="5" step="1" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Course Schedule Table Modal -->
<div id="delCourseScheduleTbl" class="modal fade" data-bs-backdrop="static" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Course Schedule Confirmation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete your course schedule?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary col-3" data-bs-dismiss="modal">Cancel</button>
                <button id="delCourseScheduleBtn" type="button" class="btn btn-danger col-3">Yes</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Course Schedule Table Columns Modal -->
<div id="delColsModal" class="modal fade" data-bs-backdrop="static" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Column(s)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="delColsForm">
                <div class="modal-body">
                    <p>Which columns would you like to delete?</p>
                    <div id="courseScheduleTblColsList"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary col-3" data-bs-dismiss="modal">Cancel</button>
                    <button id="delColsBtn" type="submit" class="btn btn-danger col-3">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Course Schedule Table Row Confirmation Modal -->
<div id="delRowModal" class="modal fade" data-bs-backdrop="static" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete row</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this row?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary col-3" data-bs-dismiss="modal">Cancel</button>
                <button id="delRowBtn" type="button" class="btn btn-danger col-3">Delete</button>
            </div>
        </div>
    </div>
</div>