<!--Modal 1-->
<div class="modal fade" id="exampleModalToggle" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalToggleLabel" style="color: black;">Guide</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="color: black;">
                <div class="container-fluid">
                    <div class="row">
                        <!--Nav Bar-->
                        <div class="col-md-4 border" style="margin:auto;">
                            <nav>
                                <ol style="list-style-type: none; font-size:medium;">
                                    <li>
                                        <a class="programs-guide">Programs</a>
                                        <!--Sub-Categories For Programs-->
                                        <ol style="list-style-type: none;">
                                            <li>
                                                <a class="courses-guide">Program Learning Outcomes (Step 1)</a>
                                            </li>
                                            <li>
                                                <a class="syllabi-guide">Mapping Scale (Step 2)</a>
                                            </li>
                                            <li>
                                                <a class="courses-guide">Courses (Step 3)</a>
                                            </li>
                                            <li>
                                                <a class="syllabi-guide">Program Overview (Step 4)</a>
                                            </li>
                                        </ol>
                                    </li>
                                    <li>
                                        <a class="courses-guide">Courses</a>
                                        <!--Sub-Categories For Courses-->
                                        <ol style="list-style-type: none;">
                                            <li>
                                                <a class="courses-guide">Course Learning Outcomes (Step 1)</a>
                                            </li>
                                            <li>
                                                <a class="syllabi-guide">Student Assessment Methods (Step 2)</a>
                                            </li>
                                            <li>
                                                <a class="courses-guide">Teaching and Learning Activities (Step 3)</a>
                                            </li>
                                            <li>
                                                <a class="syllabi-guide">Course Alignment (Step 4)</a>
                                            </li>
                                            <li>
                                                <a class="syllabi-guide">Program Outcome Mapping (Step 5)</a>
                                            </li>
                                            <li>
                                                <a class="courses-guide">Standards and Strategic Priorities (Step 6)</a>
                                            </li>
                                            <li>
                                                <a class="syllabi-guide">Course Summary (Step 7)</a>
                                            </li>
                                        </ol>
                                    </li>
                                    <li>
                                        <a class="syllabi-guide">Syllabi</a>
                                    </li>
                                </ol>
                            </nav>
                        </div>
                        <!--End Nav Bar-->
                        <!--Content Area-->
                        <div class="update-content col-md-8 border">
                        </div>

                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" data-bs-target="#exampleModalToggle2" data-bs-toggle="modal" data-bs-dismiss="modal" style="color: white;">Open second modal</button>
            </div>
        </div>
    </div>
</div>

<!--Modal 2-->
<div class="modal fade" id="exampleModalToggle2" aria-hidden="true" aria-labelledby="exampleModalToggleLabel2" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-black" id="exampleModalToggleLabel2" style="color: black;">Modal 2</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-black" style="color: black;">
                <div class="update-content">
                    <!--Loads Content here-->
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" data-bs-target="#exampleModalToggle" data-bs-toggle="modal" data-bs-dismiss="modal" style="color: white;">Back to Guide</button>
            </div>
        </div>
    </div>
</div>

<script>
    // functions that load in the user guides
    function setProgram() {
        // Header
        $("#exampleModalToggle .modal-title").text('Programs Guide');
        // Body
        $("#exampleModalToggle .update-content").html(`
            <p>info for programers</p>
            <br>
            <p>info for programers</p>
            <br>
            <p>info for programers</p>
            <img src="img/team/kieran-200x200.png">
        `);
    }

    function setCourses() {
        // Header
        $("#exampleModalToggle .modal-title").text('Courses Guide');
        // Body
        $("#exampleModalToggle .update-content").html(`
            <p>info for courses</p>
            <img src="/img/Creation.png">
        `);
    }

    function setSyllabi() {
        // Header
        $("#exampleModalToggle .modal-title").text('Syllabi Guide');
        // Body
        $("#exampleModalToggle .update-content").html(`
            <p>info for syllabi</p>
            <img src="img/team/Daulton-200x200.png">
        `);
    }

    // calls for on click methods 
    $('.programs-guide').on('click', setProgram);
    $('.courses-guide').on('click', setCourses);
    $('.syllabi-guide').on('click', setSyllabi);

    $('#programHelp').on('click', setProgram);
    $('#coursesHelp').on('click', setCourses);

</script>
<style>
    a{
        cursor: pointer;
    }
</style>