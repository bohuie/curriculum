<!--Modal 1-->
<div class="modal fade" id="guideModal" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
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
                        <div class="col-md-4">
                            <nav>
                                <ol style="list-style-type: none; font-size:medium; list-style: none; padding-left: 0;">
                                    <li>
                                        <a class="programs-guide">Programs</a>
                                        <!--Sub-Categories For Programs-->
                                        <ol class="ol-inner" style="list-style-type: none;">
                                            <li>
                                                <a class="plo-guide">Program Learning Outcomes (Step 1)</a>
                                            </li>
                                            <li>
                                                <a class="ms-guide">Mapping Scale (Step 2)</a>
                                            </li>
                                            <li>
                                                <a class="programCourses-guide">Courses (Step 3)</a>
                                            </li>
                                            <li>
                                                <a class="programOverview-guide">Program Overview (Step 4)</a>
                                            </li>
                                        </ol>
                                    </li>
                                    <li>
                                        <a class="courses-guide">Courses</a>
                                        <!--Sub-Categories For Courses-->
                                        <ol class="ol-inner" style="list-style-type: none;">
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
                        <div class="update-content col-md-8">
                            <!--Loads Content here-->
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
                <button class="btn btn-primary" data-bs-target="#guideModal" data-bs-toggle="modal" data-bs-dismiss="modal" style="color: white;">Back to Guide</button>
            </div>
        </div>
    </div>
</div>

<!--W3 Modal-->
<div id="modal01" class="modal modal-zoom" onclick="this.style.display='none'">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-black" id="exampleModalToggleLabel2" style="color: black;">Picture</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-black" style="color: black;">
                <img id="img01" style="width:100%">
            </div>
        </div>
    </div>
</div>

<script>
    // functions that load in the user guides
    function setProgram() {
        // remove attributes
        $("#guideModal .plo-guide").removeClass('selected-attribute');
        $("#guideModal .ms-guide").removeClass('selected-attribute');
        $("#guideModal .programCourses-guide").removeClass('selected-attribute');
        $("#guideModal .programOverview-guide").removeClass('selected-attribute');
        // add attributes
        $("#guideModal .programs-guide").addClass('selected-attribute');

        // Header
        $("#guideModal .modal-title").text('Programs Guide');
        // Body
        $("#guideModal .update-content").html(`
            <h3 style="margin-top:2%;">Programs</h3>
            <br>
            <p class="help-p" style="font-size:18px;">The programs section displays all programs that you have either created or have been invited to collaborate on. If there are no visible programs you can create a program by selecting the plus button on the right side of this tool tip.</p>
            <br>
            <p>info for programers</p>
            <button class="btn btn-primary" data-bs-target="#exampleModalToggle2" data-bs-toggle="modal" style="color: white;">Open second modal</button>

            <img src="{{asset('img/team/kieran-200x200.png')}}" style="border-radius: 50%;" onclick="onClick(this)">

            <p>info for programers</p>
            <br>
            <p>info for programers</p>
            <br>
            <p>info for programers</p>
            <img src="{{asset('img/team/kieran-200x200.png')}}" style="border-radius: 50%;">
            <p>info for programers</p>
            <br>
            <p>info for programers</p>
            <br>
            <p>info for programers</p>
            <img src="{{asset('img/team/kieran-200x200.png')}}" style="border-radius: 50%;">
        `);
    }

    function setPLO() {
        // remove attributes
        $("#guideModal .programs-guide").removeClass('selected-attribute');
        $("#guideModal .ms-guide").removeClass('selected-attribute');
        $("#guideModal .programCourses-guide").removeClass('selected-attribute');
        $("#guideModal .programOverview-guide").removeClass('selected-attribute');
        // add attribute
        $("#guideModal .plo-guide").addClass('selected-attribute');

        // Header
        $("#guideModal .modal-title").text('Program Learning Outcomes Guide');
        // Body
        $("#guideModal .update-content").html(`
            <h3 style="margin-top:2%; margin-bottom: 5%;">Program Learning Outcomes (Step 1)</h3>
            <h3 style="margin-top:2%;">Categories</h3>
            <br>
            <p class="help-p" style="font-size:18px;">Categories can be used to group program learning outcomes; however, they are not required.</p>
            <img class="center" src="{{asset('img/guide/plo_categories.png')}}" onclick="onClick(this)">
        `);
    }

    function setMS() {
        // remove attributes
        $("#guideModal .programs-guide").removeClass('selected-attribute');
        $("#guideModal .plo-guide").removeClass('selected-attribute');
        $("#guideModal .programCourses-guide").removeClass('selected-attribute');
        $("#guideModal .programOverview-guide").removeClass('selected-attribute');
        // add attribute
        $("#guideModal .ms-guide").addClass('selected-attribute');

        // Header
        $("#guideModal .modal-title").text('Mapping Scale Guide');
        // Body
        $("#guideModal .update-content").html(`
            <h3 style="margin-top:2%; margin-bottom: 5%;">Mapping Scale (Step 2)</h3>
            
        `);
    }

    function setProgramCourses() {
        // remove attributes
        $("#guideModal .programs-guide").removeClass('selected-attribute');
        $("#guideModal .programs-guide").removeClass('selected-attribute');
        $("#guideModal .ms-guide").removeClass('selected-attribute');
        $("#guideModal .programOverview-guide").removeClass('selected-attribute');
        // add attribute
        $("#guideModal .programCourses-guide").addClass('selected-attribute');

        // Header
        $("#guideModal .modal-title").text('Courses Guide');
        // Body
        $("#guideModal .update-content").html(`
            <h3 style="margin-top:2%; margin-bottom: 5%;">Courses (Step 3)</h3>
            
        `);
    }

    function setProgramOverview() {
        // remove attributes
        $("#guideModal .programs-guide").removeClass('selected-attribute');
        $("#guideModal .programs-guide").removeClass('selected-attribute');
        $("#guideModal .ms-guide").removeClass('selected-attribute');
        $("#guideModal .programCourses-guide").removeClass('selected-attribute');
        // add attribute
        $("#guideModal .programOverview-guide").addClass('selected-attribute');

        // Header
        $("#guideModal .modal-title").text('Program Overview Guide');
        // Body
        $("#guideModal .update-content").html(`
            <h3 style="margin-top:2%; margin-bottom: 5%;">Program Overview (Step 4)</h3>
            
        `);
    }

    function setCourses() {
        // Header
        $("#guideModal .modal-title").text('Courses Guide');
        // Body
        $("#guideModal .update-content").html(`
            <h3 style="margin-top:2%;">Courses</h3>
            <br>
            <p style="font-size:18px;">The courses section displays all courses that you have either created or have been invited to collaborate on. If there are no visible programs you can create a course by selecting the plus button on the right side of this tool tip.</p>
            <br>
        `);
    }

    function setSyllabi() {
        // Header
        $("#guideModal .modal-title").text('Syllabi Guide');
        // Body
        $("#guideModal .update-content").html(`
            <p>info for syllabi</p>
            <img src="{{asset('img/team/Daulton-200x200.png')}}">
        `);
    }

    // calls for on click methods (nav bar) 
    $('.programs-guide').on('click', setProgram);
    $('.courses-guide').on('click', setCourses);
    $('.syllabi-guide').on('click', setSyllabi);
    $('.plo-guide').on('click', setPLO)
    $('.ms-guide').on('click', setMS);
    $('.programCourses-guide').on('click', setProgramCourses);
    $('.programOverview-guide').on('click', setProgramOverview);
    
    // Calls from Dashboard
    $('#programHelp').on('click', setProgram);
    $('#coursesHelp').on('click', setCourses);
    $('#syllabiHelp').on('click', setSyllabi);
    // Calls from program wizard
    $('#ploHelp').on('click', setPLO);
    $('#msHelp').on('click', setMS);
    $('#programCoursesHelp').on('click', setProgramCourses);
    $('#programOverviewHelp').on('click', setProgramOverview);

    function onClick(element) {
        document.getElementById("img01").src = element.src;
        document.getElementById("modal01").style.display = "block";
    }

</script>
<style>
    a{
        cursor: pointer;
    }
    li{
        margin-top: 5%;
        margin-bottom: 5%;
    }
    .ol-inner{
        margin-bottom: 20%;
    }
    .help-p{
        font-size: 18px;
    }
    .update-content{
        max-height:607px; 
        overflow-y: scroll; 
        border-left: 1px solid grey;
    }
    .center {
        display: block;
        margin-left: auto;
        margin-right: auto;
        width: 100%;
    }
    .selected-attribute{
    border-bottom: solid 2px #0055b7;
    }
    
    .modal-zoom{
        z-index: 1061;
    }

</style>