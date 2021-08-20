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
                        <div class="col-md-4" style="border-right: 1px solid grey;">
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
                        <div class="update-content col-md-8 p-5">
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
        <div class="modal-content bg-dark">
            <div class="modal-body text-black" style="color: black;">
                <img id="img01" style="width:100%">
            </div>
            <div class="modal-footer text-center" style="display: inline-block;">
                <p class="text-light m-0"><small>Click Anywhere To Minimize The Image</small></p>
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
            <h2 class="mb-4 head"><u>Programs</u></h2>
            <br>
            <h2 class="text-center mb-4">How to Create a Program</h2>
            <p class="help-p my-3" style="font-size:18px;">The programs section displays all programs that you have either created or have been invited to collaborate on. If there are no visible programs you can create a program by selecting the plus button on the right side of this tool tip.</p>
            <img class="img center" src="{{asset('/img/guide/ProgramHeaderAnnotated.png')}}" onclick="onClick(this)">
            <br>
            <p class="help-p my-3" style="font-size:18px;">To create a program, you must fill out the following form shown below after clicking on the plus icon.</p>
            <img class="img center" src="{{asset('/img/guide/CreateProgramModal.PNG')}}" onclick="onClick(this)">
            <br>
            <p class="help-p my-3" style="font-size:18px;">Once you have created you program you can click on the name as shown in the picture below. This link will bring you to the next step in creating your program.</p>
            <img class="img center my-3" src="{{asset('/img/guide/CreatedProgramAnnotated.png')}}" onclick="onClick(this)">
            <br>
            <button class="btn btn-primary float-right" onclick="setPLO()" style="color: white;">Program Learning Outcomes (Step 1) <i class="bi bi-arrow-right mr-2"></i></button>
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
            <h2 class="mb-4 head"><u>Program Learning Outcomes (Step 1)</u></h2>
            <br>
            <h2 class="text-center mb-4">Categories</h2>
            <p class="help-p my-3" style="font-size:18px;">Categories can be used to group program learning outcomes; however, they are not required.</p>
            <img class="img center my-3" src="{{asset('img/guide/plo_categories.png')}}" onclick="onClick(this)">
            <br>
            <h2 class="text-center mb-4">Program Learning Outcomes</h2>
            <p class="help-p my-3" style="font-size:18px;">Program-level learning outcomes (PLOs) are the knowledge, skills and attributes that students are expected to attain by the end of a program of study.</p>
            <img class="img center my-3" src="{{asset('/img/guide/ProgramLearningOutcome.PNG')}}" onclick="onClick(this)">
            <br>
            <div class="row">
                <div class="col"><button class="btn btn-primary float-left w-100" onclick="setProgram()" style="color: white;"><i class="bi bi-arrow-left mr-2"></i> Programs</button></div>
                <div class="col"><button class="btn btn-primary float-right w-100" onclick="setMS()" style="color: white;">Mapping Scales (Step 2) <i class="bi bi-arrow-right mr-2"></i></button></div>
            </div>
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
        max-height:714px; 
        overflow-y: scroll;
        background-color: #fafafa;
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
    .img{
        cursor: pointer;
        box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
        padding-top: 0;
        padding-bottom: 0;
    }

    .modal-xl{
        max-width: 1450px;
    }
    .head{
        color: #002145;
    }

</style>