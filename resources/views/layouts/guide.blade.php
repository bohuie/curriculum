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
                                    <li class="li">
                                        <a class="programs-guide">Programs</a>
                                        <!--Sub-Categories For Programs-->
                                        <ol class="ol-inner" style="list-style-type: none;">
                                            <li class="li">
                                                <a class="plo-guide">Program Learning Outcomes (Step 1)</a>
                                            </li>
                                            <li class="li">
                                                <a class="ms-guide">Mapping Scale (Step 2)</a>
                                            </li>
                                            <li class="li">
                                                <a class="programCourses-guide">Courses (Step 3)</a>
                                            </li>
                                            <li class="li">
                                                <a class="programOverview-guide">Program Overview (Step 4)</a>
                                            </li>
                                        </ol>
                                    </li>
                                    <li class="li">
                                        <a class="courses-guide">Courses</a>
                                        <!--Sub-Categories For Courses-->
                                        <ol class="ol-inner" style="list-style-type: none;">
                                            <li class="li">
                                                <a class="clo-guide">Course Learning Outcomes (Step 1)</a>
                                            </li>
                                            <li class="li">
                                                <a class="sam-guide">Student Assessment Methods (Step 2)</a>
                                            </li>
                                            <li class="li">
                                                <a class="tla-guide">Teaching and Learning Activities (Step 3)</a>
                                            </li>
                                            <li class="li">
                                                <a class="courseAlignment-guide">Course Alignment (Step 4)</a>
                                            </li>
                                            <li class="li">
                                                <a class="programOutcomeMapping-guide">Program Outcome Mapping (Step 5)</a>
                                            </li>
                                            <li class="li">
                                                <a class="standards-guide">Standards and Strategic Priorities (Step 6)</a>
                                            </li>
                                            <li class="li">
                                                <a class="coursesSummary-guide">Course Summary (Step 7)</a>
                                            </li>
                                        </ol>
                                    </li>
                                    <li class="li">
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
            </div>
        </div>
    </div>
</div>

<!--img zoom Modal-->
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
        $("#guideModal .programs-guide").removeClass('selected-attribute');
        $("#guideModal .plo-guide").removeClass('selected-attribute');
        $("#guideModal .ms-guide").removeClass('selected-attribute');
        $("#guideModal .programCourses-guide").removeClass('selected-attribute');
        $("#guideModal .programOverview-guide").removeClass('selected-attribute');
        $("#guideModal .courses-guide").removeClass('selected-attribute');
        $("#guideModal .clo-guide").removeClass('selected-attribute');
        $("#guideModal .sam-guide").removeClass('selected-attribute');
        $("#guideModal .tla-guide").removeClass('selected-attribute');
        $("#guideModal .courseAlignment-guide").removeClass('selected-attribute');
        $("#guideModal .programOutcomeMapping-guide").removeClass('selected-attribute');
        $("#guideModal .standards-guide").removeClass('selected-attribute');
        $("#guideModal .coursesSummary-guide").removeClass('selected-attribute');
        $("#guideModal .syllabi-guide").removeClass('selected-attribute');
        // add attributes
        $("#guideModal .programs-guide").addClass('selected-attribute');
        // Reset Scroll to top
        $('.update-content').scrollTop(0);

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
            <p class="help-p my-3" style="font-size:18px;">Once you have created your program you can click on the name as shown in the picture below. This link will bring you to the next step in creating your program.</p>
            <img class="img center my-3" src="{{asset('/img/guide/CreatedProgramAnnotated.png')}}" onclick="onClick(this)">
            <br>
            <button class="btn btn-primary float-right w-50" onclick="setPLO()" style="color: white;">Program Learning Outcomes (Step 1) <i class="bi bi-arrow-right mr-2"></i></button>
        `);
    }

    function setPLO() {
        // remove attributes
        $("#guideModal .programs-guide").removeClass('selected-attribute');
        $("#guideModal .plo-guide").removeClass('selected-attribute');
        $("#guideModal .ms-guide").removeClass('selected-attribute');
        $("#guideModal .programCourses-guide").removeClass('selected-attribute');
        $("#guideModal .programOverview-guide").removeClass('selected-attribute');
        $("#guideModal .courses-guide").removeClass('selected-attribute');
        $("#guideModal .clo-guide").removeClass('selected-attribute');
        $("#guideModal .sam-guide").removeClass('selected-attribute');
        $("#guideModal .tla-guide").removeClass('selected-attribute');
        $("#guideModal .courseAlignment-guide").removeClass('selected-attribute');
        $("#guideModal .programOutcomeMapping-guide").removeClass('selected-attribute');
        $("#guideModal .standards-guide").removeClass('selected-attribute');
        $("#guideModal .coursesSummary-guide").removeClass('selected-attribute');
        $("#guideModal .syllabi-guide").removeClass('selected-attribute');
        // add attribute
        $("#guideModal .plo-guide").addClass('selected-attribute');
        // Reset Scroll to top
        $('.update-content').scrollTop(0);

        // Header
        $("#guideModal .modal-title").text('Program Learning Outcomes Guide');
        // Body
        $("#guideModal .update-content").html(`
            <h2 class="mb-4 head"><u>Program Learning Outcomes (Step 1)</u></h2>
            <br>
            <h2 class="text-center mb-4">How to Create a Category</h2>
            <p class="help-p my-3" style="font-size:18px;">Categories can be used to group program learning outcomes; however, they are not required. To create a category, click the '+ PLO Category' button as shown below.</p>
            <img class="img center my-3" src="{{asset('img/guide/plo_categories.PNG')}}" onclick="onClick(this)">
            <br>
            <p class="help-p my-3" style="font-size:18px;">After you click on the button to create a category you will be prompted with the following form to fill out.</p>
            <img class="img center my-3" src="{{asset('/img/guide/CreateCategoryModal.PNG')}}" onclick="onClick(this)">
            <br>
            <br>
            <h2 class="text-center mb-4">How to Create a Program Learning Outcomes</h2>
            <p class="help-p my-3" style="font-size:18px;">Program-level learning outcomes (PLOs) are the knowledge, skills and attributes that students are expected to attain by the end of a program of study. To create a Program Learning Outcome, click the '+ PLO' button as shown below.</p>
            <img class="img center my-3" src="{{asset('/img/guide/ProgramLearningOutcome.PNG')}}" onclick="onClick(this)">
            <br>
            <p class="help-p my-3" style="font-size:18px;">After you click on the button to create a program learning outcome you will be prompted with the following form to fill out.</p>
            <img class="img center my-3" src="{{asset('/img/guide/CreatePLOModal.PNG')}}" onclick="onClick(this)">
            <br>
            <p class="help-p my-3" style="font-size:18px;">You can add this program learning outcome to a category by selecting from the dropdown list shown below, or you can choose ‘None’ if you would like to leave it uncategorized. You may change any of the information after you save it by selecting the ‘Edit’ button.</p>
            <img class="img center my-3" src="{{asset('/img/guide/CategoryDropdown.png')}}" onclick="onClick(this)">
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
        $("#guideModal .ms-guide").removeClass('selected-attribute');
        $("#guideModal .programCourses-guide").removeClass('selected-attribute');
        $("#guideModal .programOverview-guide").removeClass('selected-attribute');
        $("#guideModal .courses-guide").removeClass('selected-attribute');
        $("#guideModal .clo-guide").removeClass('selected-attribute');
        $("#guideModal .sam-guide").removeClass('selected-attribute');
        $("#guideModal .tla-guide").removeClass('selected-attribute');
        $("#guideModal .courseAlignment-guide").removeClass('selected-attribute');
        $("#guideModal .programOutcomeMapping-guide").removeClass('selected-attribute');
        $("#guideModal .standards-guide").removeClass('selected-attribute');
        $("#guideModal .coursesSummary-guide").removeClass('selected-attribute');
        $("#guideModal .syllabi-guide").removeClass('selected-attribute');
        // add attribute
        $("#guideModal .ms-guide").addClass('selected-attribute');
        // Reset Scroll to top
        $('.update-content').scrollTop(0);

        // Header
        $("#guideModal .modal-title").text('Mapping Scale Guide');
        // Body
        $("#guideModal .update-content").html(`
            <h2 class="mb-4 head"><u>Mapping Scale (Step 2)</u></h2>
            <br>
            <h2 class="text-center mb-4">How to Add a Predefined Mapping Scale</h2>
            <p class="help-p my-3" style="font-size:18px;">The mapping scale is the scale that will be used to indicate the degree to which a program-level learning outcome is addressed by a course outcome, or the degree of alignment between the course outcome and program-level learning outcome.</p>
            <p class="help-p my-3" style="font-size:18px;">There are two ways to add mapping scales to a program, the first involves importing a set of predefined mapping scales. This can be accomplished by clicking the 'Show Default Mapping Scales' button as shown below.</p>
            <img class="img center my-3" src="{{asset('/img/guide/CreateDefaultMappingScale.png')}}" onclick="onClick(this)">
            <br>
            <p class="help-p my-3" style="font-size:18px;">After you will see the following prompt, as shown below. From the prompt, you can select from a set of Default Mapping Scales to add to your program by clicking the ‘+ Import Mapping Scale' button. As a side note if you have previously imported a set of default mapping scales it will be overwritten by the set you are trying to add.</p>
            <img class="img center my-3" src="{{asset('/img/guide/ImportMappingScaleModal.png')}}" onclick="onClick(this)">
            <br>
            <br>
            <h2 class="text-center mb-4">How to Add a Custom Mapping Scale</h2>
            <p class="help-p my-3" style="font-size:18px;">The second method of adding a mapping scale involves creating a custom scale. This can be accomplished by clicking the ‘+ my own Mapping Scale Level’ button, as shown below.</p>
            <img class="img center my-3" src="{{asset('/img/guide/CreateCustomMappingScale.png')}}" onclick="onClick(this)">
            <br>
            <p class="help-p my-3" style="font-size:18px;">After selecting the button, you will be prompted with the following form to fill out as shown below.</p>
            <img class="img center my-3" src="{{asset('/img/guide/CreateCustomMS.PNG')}}" onclick="onClick(this)">
            <br>
            <div class="row">
                <div class="col"><button class="btn btn-primary float-left w-100" onclick="setPLO()" style="color: white;"><i class="bi bi-arrow-left mr-2"></i> Program Learning Outcomes (Step 1)</button></div>
                <div class="col"><button class="btn btn-primary float-right w-100" onclick="setProgramCourses()" style="color: white;">Courses (Step 3) <i class="bi bi-arrow-right mr-2"></i></button></div>
            </div>
        `);
    }

    function setProgramCourses() {
        // remove attributes
        $("#guideModal .programs-guide").removeClass('selected-attribute');
        $("#guideModal .plo-guide").removeClass('selected-attribute');
        $("#guideModal .ms-guide").removeClass('selected-attribute');
        $("#guideModal .programCourses-guide").removeClass('selected-attribute');
        $("#guideModal .programOverview-guide").removeClass('selected-attribute');
        $("#guideModal .courses-guide").removeClass('selected-attribute');
        $("#guideModal .clo-guide").removeClass('selected-attribute');
        $("#guideModal .sam-guide").removeClass('selected-attribute');
        $("#guideModal .tla-guide").removeClass('selected-attribute');
        $("#guideModal .courseAlignment-guide").removeClass('selected-attribute');
        $("#guideModal .programOutcomeMapping-guide").removeClass('selected-attribute');
        $("#guideModal .standards-guide").removeClass('selected-attribute');
        $("#guideModal .coursesSummary-guide").removeClass('selected-attribute');
        $("#guideModal .syllabi-guide").removeClass('selected-attribute');
        // add attribute
        $("#guideModal .programCourses-guide").addClass('selected-attribute');
        // Reset Scroll to top
        $('.update-content').scrollTop(0);

        // Header
        $("#guideModal .modal-title").text('Courses Guide');
        // Body
        $("#guideModal .update-content").html(`
            <h2 class="mb-4 head"><u>Courses (Step 3)</u></h2>
            <br>
            <p class="help-p my-3" style="font-size:18px;">The courses step allows you to either add an existing course to your program, or create a new course which is then added to your program. This then allows Program Learning Outcomes (PLOs) to be mapped to Course Learning Outcomes (CLOs).</p>
            <br>
            <h2 class="text-center mb-4">How to Add an Existing Course</h2>
            <p class="help-p my-3" style="font-size:18px;">To add an existing course to your program you must either have previously created a course that is not already assigned to the current program, or have been added as a collaborator to a course. If at least one of those two requirements is met then you will be able to add and existing course by clicking the button ‘+ Existing Course’ as shown below. Otherwise, if neither condition is met then you can also create a new course, more information on how to create a new course is provided under this section.</p>
            <img class="img center my-3" src="{{asset('/img/guide/AddExistingCourse.png')}}" onclick="onClick(this)">
            <br>
            <p class="help-p my-3" style="font-size:18px;">From the prompt shown below you will be able to select from a list of available courses to add to your program. You can add multiple courses at a time. As well you can select whether or not the course is required by the program or if it is an elective.</p>
            <img class="img center my-3" src="{{asset('/img/guide/AddExistingCourseGif.gif')}}" onclick="onClick(this)">
            <br>
            <p class="help-p my-3" style="font-size:18px;">After you press the “Add Selected’ you will see the courses added to you program in the courses table.</p>
            <br>
            <h2 class="text-center mb-4">How to Add a New Course</h2>
            <p class="help-p my-3" style="font-size:18px;">To add a new course to your program you will first need to select the ‘+ New Course’ button.</p>
            <img class="img center my-3" src="{{asset('/img/guide/AddNewCourse.png')}}" onclick="onClick(this)">
            <br>
            <p class="help-p my-3" style="font-size:18px;">This will open a prompt that will allow you to enter information regarding your course, as well as if it is required by the program.</p>
            <img class="img center my-3" src="{{asset('/img/guide/CreateProgramCourseModal.PNG')}}" onclick="onClick(this)">
            <br>
            <p class="help-p my-3" style="font-size:18px;">After you finish inputting all the required information the course will then appear on your courses table for the program. You will also be able to access the course from the dashboard as well.</p>
            <br>
            <div class="row">
                <div class="col"><button class="btn btn-primary float-left w-100" onclick="setMS()" style="color: white;"><i class="bi bi-arrow-left mr-2"></i> Mapping Scales (Step 2)</button></div>
                <div class="col"><button class="btn btn-primary float-right w-100" onclick="setProgramOverview()" style="color: white;">Program Overview (Step 4) <i class="bi bi-arrow-right mr-2"></i></button></div>
            </div>
        `);
    }

    function setProgramOverview() {
        // remove attributes
        $("#guideModal .programs-guide").removeClass('selected-attribute');
        $("#guideModal .plo-guide").removeClass('selected-attribute');
        $("#guideModal .ms-guide").removeClass('selected-attribute');
        $("#guideModal .programCourses-guide").removeClass('selected-attribute');
        $("#guideModal .programOverview-guide").removeClass('selected-attribute');
        $("#guideModal .courses-guide").removeClass('selected-attribute');
        $("#guideModal .clo-guide").removeClass('selected-attribute');
        $("#guideModal .sam-guide").removeClass('selected-attribute');
        $("#guideModal .tla-guide").removeClass('selected-attribute');
        $("#guideModal .courseAlignment-guide").removeClass('selected-attribute');
        $("#guideModal .programOutcomeMapping-guide").removeClass('selected-attribute');
        $("#guideModal .standards-guide").removeClass('selected-attribute');
        $("#guideModal .coursesSummary-guide").removeClass('selected-attribute');
        $("#guideModal .syllabi-guide").removeClass('selected-attribute');
        // add attribute
        $("#guideModal .programOverview-guide").addClass('selected-attribute');
        // Reset Scroll to top
        $('.update-content').scrollTop(0);

        // Header
        $("#guideModal .modal-title").text('Program Overview Guide');
        // Body
        $("#guideModal .update-content").html(`
            <h2 class="mb-4 head"><u>Program Overview (Step 4)</u></h2>
            <br>
            <p class="help-p my-3" style="font-size:18px;">The program overview provides a comprehensive view of all of the information regarding the program, as well as a detailed chart that shows the alignment of courses to program learning outcomes for this program. You must fill in the previous steps in order to see some of the fields for the program overview.</p>
            <p class="help-p my-3" style="font-size:18px;">The program overview can be downloaded as a PDF copy.</p>
            <img class="img center my-3" src="{{asset('/img/guide/ProgramsDownloadPDF.png')}}" onclick="onClick(this)">
            <br>
            <button class="btn btn-primary float-left w-50" onclick="setProgramCourses()" style="color: white;"><i class="bi bi-arrow-left mr-2"></i> Courses (Step 3)</button>
        `);
    }
    // Courses
    function setCourses() {
        // remove attributes
        $("#guideModal .programs-guide").removeClass('selected-attribute');
        $("#guideModal .plo-guide").removeClass('selected-attribute');
        $("#guideModal .ms-guide").removeClass('selected-attribute');
        $("#guideModal .programCourses-guide").removeClass('selected-attribute');
        $("#guideModal .programOverview-guide").removeClass('selected-attribute');
        $("#guideModal .courses-guide").removeClass('selected-attribute');
        $("#guideModal .clo-guide").removeClass('selected-attribute');
        $("#guideModal .sam-guide").removeClass('selected-attribute');
        $("#guideModal .tla-guide").removeClass('selected-attribute');
        $("#guideModal .courseAlignment-guide").removeClass('selected-attribute');
        $("#guideModal .programOutcomeMapping-guide").removeClass('selected-attribute');
        $("#guideModal .standards-guide").removeClass('selected-attribute');
        $("#guideModal .coursesSummary-guide").removeClass('selected-attribute');
        $("#guideModal .syllabi-guide").removeClass('selected-attribute');
        // add attribute
        $("#guideModal .courses-guide").addClass('selected-attribute');
        // Reset Scroll to top
        $('.update-content').scrollTop(0);

        // Header
        $("#guideModal .modal-title").text('Courses Guide');
        // Body
        $("#guideModal .update-content").html(`
            <h2 class="mb-4 head"><u>Courses</u></h2>
            <br>
            <h2 class="text-center mb-4">How to Create a Course</h2>
            <p class="help-p my-3" style="font-size:18px;">The courses section displays all courses that you have either created or have been invited to collaborate on. If there are no visible programs you can create a course by selecting the plus button on the right side of this tool tip.</p>
            <img class="img center my-3" src="{{asset('/img/guide/CourseHeaderAnnotated.png')}}" onclick="onClick(this)">
            <br>
            <p class="help-p my-3" style="font-size:18px;">To create a course, you must fill out the following form shown below after clicking on the plus icon.</p>
            <img class="img center my-3" src="{{asset('/img/guide/CreateCourseModal.PNG')}}" onclick="onClick(this)">
            <br>
            <p class="help-p my-3" style="font-size:18px;">Once you have created your course you can click on the name as shown in the picture below. This link will bring you to the next step in creating your course.</p>
            <img class="img center my-3" src="{{asset('/img/guide/CreatedCourseAnnotated.png')}}" onclick="onClick(this)">
            <br>
            <button class="btn btn-primary float-right w-50" onclick="setCLO()" style="color: white;">Course Learning Outcomes (Step 1) <i class="bi bi-arrow-right mr-2"></i></button>
        `);
    }

    function setCLO() {
        // remove attributes
        $("#guideModal .programs-guide").removeClass('selected-attribute');
        $("#guideModal .plo-guide").removeClass('selected-attribute');
        $("#guideModal .ms-guide").removeClass('selected-attribute');
        $("#guideModal .programCourses-guide").removeClass('selected-attribute');
        $("#guideModal .programOverview-guide").removeClass('selected-attribute');
        $("#guideModal .courses-guide").removeClass('selected-attribute');
        $("#guideModal .clo-guide").removeClass('selected-attribute');
        $("#guideModal .sam-guide").removeClass('selected-attribute');
        $("#guideModal .tla-guide").removeClass('selected-attribute');
        $("#guideModal .courseAlignment-guide").removeClass('selected-attribute');
        $("#guideModal .programOutcomeMapping-guide").removeClass('selected-attribute');
        $("#guideModal .standards-guide").removeClass('selected-attribute');
        $("#guideModal .coursesSummary-guide").removeClass('selected-attribute');
        $("#guideModal .syllabi-guide").removeClass('selected-attribute');
        // add attribute
        $("#guideModal .clo-guide").addClass('selected-attribute');
        // Reset Scroll to top
        $('.update-content').scrollTop(0);

        // Header
        $("#guideModal .modal-title").text('Course Learning Outcomes Guide');
        // Body
        $("#guideModal .update-content").html(`
            <h2 class="mb-4 head"><u>Course Learning Outcomes (Step 1)</u></h2>
            <br>
            <p class="help-p my-3 center" style="font-size:18px;">Course Learning outcomes allow you to describe the outcomes that students can expect to take away from completing the course, further instructions can be found <a target=”_blank” href='https://ctl.ok.ubc.ca/teaching-effectively/course-design/'>here</a>.</p>
            <br>
            <h2 class="text-center mb-4">How to Add a New Course Learning Outcome</h2>
            <p class="help-p my-3 center" style="font-size:18px;">To create a Course Learning Outcome, you will need to select the ‘+ CLO’ button.</p>
            <img class="img center my-3" src="{{asset('/img/guide/CreateCLO.png')}}" onclick="onClick(this)">
            <br>
            <p class="help-p my-3 center" style="font-size:18px;">You can then enter your Course Learning outcome as well as a short phrase in the prompt shown below. There is further information provided in the prompt to help you generate ideas for creating Course Learning Outcomes.</p>
            <img class="img center my-3 w-50" src="{{asset('/img/guide/CreateCLOModal.PNG')}}" onclick="onClick(this)">
            <br>
            <p class="help-p my-3 center" style="font-size:18px;">It is recommended that a course has a maximum of 5-7 Course Learning Outcomes.</p>
            <br>
            <div class="row">
                <div class="col"><button class="btn btn-primary float-left w-100" onclick="setCourses()" style="color: white;"><i class="bi bi-arrow-left mr-2"></i> Courses</button></div>
                <div class="col"><button class="btn btn-primary float-right w-100" onclick="setSAM()" style="color: white;">Student Assessment Methods (Step 2) <i class="bi bi-arrow-right mr-2"></i></button></div>
            </div>
        `);
    }

    function setSAM() {
        // remove attributes
        $("#guideModal .programs-guide").removeClass('selected-attribute');
        $("#guideModal .plo-guide").removeClass('selected-attribute');
        $("#guideModal .ms-guide").removeClass('selected-attribute');
        $("#guideModal .programCourses-guide").removeClass('selected-attribute');
        $("#guideModal .programOverview-guide").removeClass('selected-attribute');
        $("#guideModal .courses-guide").removeClass('selected-attribute');
        $("#guideModal .clo-guide").removeClass('selected-attribute');
        $("#guideModal .sam-guide").removeClass('selected-attribute');
        $("#guideModal .tla-guide").removeClass('selected-attribute');
        $("#guideModal .courseAlignment-guide").removeClass('selected-attribute');
        $("#guideModal .programOutcomeMapping-guide").removeClass('selected-attribute');
        $("#guideModal .standards-guide").removeClass('selected-attribute');
        $("#guideModal .coursesSummary-guide").removeClass('selected-attribute');
        $("#guideModal .syllabi-guide").removeClass('selected-attribute');
        // add attribute
        $("#guideModal .sam-guide").addClass('selected-attribute');
        // Reset Scroll to top
        $('.update-content').scrollTop(0);

        // Header
        $("#guideModal .modal-title").text('Student Assessment Methods Guide');
        // Body
        $("#guideModal .update-content").html(`
            <h2 class="mb-4 head"><u>Student Assessment Methods Guide (Step 2)</u></h2>
            <br>
            <p class="help-p my-3 center" style="font-size:18px;">Student Assessment Methods provide a way to list the methods that students' understanding of course concepts and material will be evaluated and measured.</p>
            <br>
            <h2 class="text-center mb-4">How to Add a New Student Assessment Methods</h2>
            <p class="help-p my-3 center" style="font-size:18px;">To create Student Assessment Methods, you will need to select the ‘+ Student Assessment Methods’ button.</p>
            <img class="img center my-3" src="{{asset('/img/guide/CreateStudentAssessmentMethod.png')}}" onclick="onClick(this)">
            <br>
            <p class="help-p my-3 center" style="font-size:18px;">You will then see the prompt shown below, from this menu you can add as many Assessment Methods as you would like. For each Assessment Method you will need to assign a weight, you may also assign the weight of zero if you wish.</p>
            <img class="img center my-3" src="{{asset('/img/guide/CreateStudentAssementMethodsModal.gif')}}" onclick="onClick(this)">
            <br>
            <p class="help-p my-3 center" style="font-size:18px;">After adding your Assessment Methods, you will see them appear in the table as shown below, along with the total combined weight.</p>
            <img class="img center my-3" src="{{asset('/img/guide/StudentAssessmentMethods.png')}}" onclick="onClick(this)">
            <br>
            <div class="row">
                <div class="col"><button class="btn btn-primary float-left w-100" onclick="setCLO()" style="color: white;"><i class="bi bi-arrow-left mr-2"></i> Course Learning Outcomes (Step 1)</button></div>
                <div class="col"><button class="btn btn-primary float-right w-100" onclick="setTLA()" style="color: white;">Teaching and Learning Activities (Step 3) <i class="bi bi-arrow-right mr-2"></i></button></div>
            </div>
        `);
    }

    function setTLA() {
        // remove attributes
        $("#guideModal .programs-guide").removeClass('selected-attribute');
        $("#guideModal .plo-guide").removeClass('selected-attribute');
        $("#guideModal .ms-guide").removeClass('selected-attribute');
        $("#guideModal .programCourses-guide").removeClass('selected-attribute');
        $("#guideModal .programOverview-guide").removeClass('selected-attribute');
        $("#guideModal .courses-guide").removeClass('selected-attribute');
        $("#guideModal .clo-guide").removeClass('selected-attribute');
        $("#guideModal .sam-guide").removeClass('selected-attribute');
        $("#guideModal .tla-guide").removeClass('selected-attribute');
        $("#guideModal .courseAlignment-guide").removeClass('selected-attribute');
        $("#guideModal .programOutcomeMapping-guide").removeClass('selected-attribute');
        $("#guideModal .standards-guide").removeClass('selected-attribute');
        $("#guideModal .coursesSummary-guide").removeClass('selected-attribute');
        $("#guideModal .syllabi-guide").removeClass('selected-attribute');
        // add attribute
        $("#guideModal .tla-guide").addClass('selected-attribute');
        // Reset Scroll to top
        $('.update-content').scrollTop(0);

        // Header
        $("#guideModal .modal-title").text('Teaching and Learning Activities Guide');
        // Body
        $("#guideModal .update-content").html(`
            <h2 class="mb-4 head"><u>Teaching and Learning Activities (Step 3)</u></h2>
            <br>
            <p class="help-p my-3 center" style="font-size:18px;">Teaching and Learning Activities are used to describe the methods the course will be taught.</p>
            <br>
            <h2 class="text-center mb-4">How to Add New Teaching and Learning Activities</h2>
            <p class="help-p my-3 center" style="font-size:18px;">To create Teaching and Learning Activities, you will need to select the ‘+ Learning Activities’ button.</p>
            <img class="img center my-3" src="{{asset('/img/guide/CreateTeachingandLearningActivities.png')}}" onclick="onClick(this)">
            <br>
            <p class="help-p my-3 center" style="font-size:18px;">You will then see the prompt shown below, from this menu you can add as many Learning Activities as you would like.</p>
            <img class="img center my-3" src="{{asset('/img/guide/CreateTeachingandLearningActivitiesModal.gif')}}" onclick="onClick(this)">
            <br>
            <p class="help-p my-3 center" style="font-size:18px;">After adding your Learning Activities, you will see them appear in the table as shown below.</p>
            <img class="img center my-3" src="{{asset('/img/guide/TeachingandLearningActivities.PNG')}}" onclick="onClick(this)">

            <br>
            <p class="help-p my-3 center" style="font-size:18px;">Not sure how to teach/embed career-related outcomes? Request a workshop from The Career Development Team for your classroom (<a href="https://students.ok.ubc.ca/career-experience/faculty-workshops/">click here</a>).</p>
            <br>
            <div class="row">
                <div class="col"><button class="btn btn-primary float-left w-100" onclick="setSAM()" style="color: white;"><i class="bi bi-arrow-left mr-2"></i> Student Assessment Methods (Step 2)</button></div>
                <div class="col"><button class="btn btn-primary float-right w-100" onclick="setCourseAlignment()" style="color: white;">Course Alignment (Step 4) <i class="bi bi-arrow-right mr-2"></i></button></div>
            </div>
        `);
    }

    function setCourseAlignment() {
        // remove attributes
        $("#guideModal .programs-guide").removeClass('selected-attribute');
        $("#guideModal .plo-guide").removeClass('selected-attribute');
        $("#guideModal .ms-guide").removeClass('selected-attribute');
        $("#guideModal .programCourses-guide").removeClass('selected-attribute');
        $("#guideModal .programOverview-guide").removeClass('selected-attribute');
        $("#guideModal .courses-guide").removeClass('selected-attribute');
        $("#guideModal .clo-guide").removeClass('selected-attribute');
        $("#guideModal .sam-guide").removeClass('selected-attribute');
        $("#guideModal .tla-guide").removeClass('selected-attribute');
        $("#guideModal .courseAlignment-guide").removeClass('selected-attribute');
        $("#guideModal .programOutcomeMapping-guide").removeClass('selected-attribute');
        $("#guideModal .standards-guide").removeClass('selected-attribute');
        $("#guideModal .coursesSummary-guide").removeClass('selected-attribute');
        $("#guideModal .syllabi-guide").removeClass('selected-attribute');
        // add attribute
        $("#guideModal .courseAlignment-guide").addClass('selected-attribute');
        // Reset Scroll to top
        $('.update-content').scrollTop(0);

        // Header
        $("#guideModal .modal-title").text('Course Alignment Guide');
        // Body
        $("#guideModal .update-content").html(`
            <h2 class="mb-4 head"><u>Course Alignment (Step 4)</u></h2>
            <br>
            <p class="help-p my-3 center" style="font-size:18px;">This step requires course instructors to align their Student Assessment Methods, and their Teaching and Learning Activities to their Course Learning Outcomes. An example of aligning a course is shown below.</p>
            <img class="img center my-3" src="{{asset('/img/guide/CourseAlignment.gif')}}" onclick="onClick(this)">

            <br>
            <div class="row">
                <div class="col"><button class="btn btn-primary float-left w-100" onclick="setTLA()" style="color: white;"><i class="bi bi-arrow-left mr-2"></i> Teaching and Learning Activities (Step 3)</button></div>
                <div class="col"><button class="btn btn-primary float-right w-100" onclick="setProgramOutcomeMapping()" style="color: white;">Program Outcome Mapping (Step 5) <i class="bi bi-arrow-right mr-2"></i></button></div>
            </div>
        `);
    }

    function setProgramOutcomeMapping() {
        // remove attributes
        $("#guideModal .programs-guide").removeClass('selected-attribute');
        $("#guideModal .plo-guide").removeClass('selected-attribute');
        $("#guideModal .ms-guide").removeClass('selected-attribute');
        $("#guideModal .programCourses-guide").removeClass('selected-attribute');
        $("#guideModal .programOverview-guide").removeClass('selected-attribute');
        $("#guideModal .courses-guide").removeClass('selected-attribute');
        $("#guideModal .clo-guide").removeClass('selected-attribute');
        $("#guideModal .sam-guide").removeClass('selected-attribute');
        $("#guideModal .tla-guide").removeClass('selected-attribute');
        $("#guideModal .courseAlignment-guide").removeClass('selected-attribute');
        $("#guideModal .programOutcomeMapping-guide").removeClass('selected-attribute');
        $("#guideModal .standards-guide").removeClass('selected-attribute');
        $("#guideModal .coursesSummary-guide").removeClass('selected-attribute');
        $("#guideModal .syllabi-guide").removeClass('selected-attribute');
        // add attribute
        $("#guideModal .programOutcomeMapping-guide").addClass('selected-attribute');
        // Reset Scroll to top
        $('.update-content').scrollTop(0);

        // Header
        $("#guideModal .modal-title").text('Program Outcome Mapping Guide');
        // Body
        $("#guideModal .update-content").html(`
            <h2 class="mb-4 head"><u>Program Outcome Mapping (Step 5)</u></h2>
            <br>
            <p class="help-p my-3 center" style="font-size:18px;">This step requires that your course has been added to a program, otherwise you will not see any programs to map your course to. See the <a class="link" onclick="setProgram()">Program guide</a> if you would like to create a program.</p>
            <br>
            <h2 class="text-center mb-4">How to Map your Course to a Program</h2>
            <p class="help-p my-3 center" style="font-size:18px;">To map your course to a program, you will need to select the program dropdown as shown below (You may need to double click on the dropdown box to open it).</p>
            <img class="img center my-3" src="{{asset('/img/guide/ProgramOutcomeMapping.png')}}" onclick="onClick(this)">
            <br>
            <p class="help-p my-3 center" style="font-size:18px;">Once the dropdown has opened you will see the mappings scale for the program, as well as your CLOs. To begin mapping the Program Learning Outcomes to your Course Learning Outcomes click on one of your Course Learning Outcomes as shown below.</p>
            <img class="img center my-3" src="{{asset('/img/guide/ProgramOutcomeMappingCLOS.png')}}" onclick="onClick(this)">
            <br>
            <ol class="m-5">
                <li class="my-2" style="font-size: 18px;">Select the scale value which aligns best for your Course Learning Outcome to the Program Learning Outcome.</li>
                <li class="my-2" style="font-size: 18px;">Make sure you save your selection for each Course Learning Outcome.</li>
            </ol>
            <img class="img center my-3" src="{{asset('/img/guide/ProgramOutcomeMappingCLOScale.png')}}" onclick="onClick(this)">
            <br>
            <p class="help-p my-3 center" style="font-size:18px;">After you have saved your selection, you will see the banner colour change to green to indicate that the CLO has been mapped.</p>
            <img class="img center my-3" src="{{asset('/img/guide/ProgramOutcomeMappingCLOSCompleted.png')}}" onclick="onClick(this)">

            <br>
            <div class="row">
                <div class="col"><button class="btn btn-primary float-left w-100" onclick="setCourseAlignment()" style="color: white;"><i class="bi bi-arrow-left mr-2"></i> Course Alignment (Step 4)</button></div>
                <div class="col"><button class="btn btn-primary float-right w-100" onclick="setStandards()" style="color: white;">Standards and Strategic Priorities (Step 6) <i class="bi bi-arrow-right mr-2"></i></button></div>
            </div>
        `);
    }

    function setStandards() {
        // remove attributes
        $("#guideModal .programs-guide").removeClass('selected-attribute');
        $("#guideModal .plo-guide").removeClass('selected-attribute');
        $("#guideModal .ms-guide").removeClass('selected-attribute');
        $("#guideModal .programCourses-guide").removeClass('selected-attribute');
        $("#guideModal .programOverview-guide").removeClass('selected-attribute');
        $("#guideModal .courses-guide").removeClass('selected-attribute');
        $("#guideModal .clo-guide").removeClass('selected-attribute');
        $("#guideModal .sam-guide").removeClass('selected-attribute');
        $("#guideModal .tla-guide").removeClass('selected-attribute');
        $("#guideModal .courseAlignment-guide").removeClass('selected-attribute');
        $("#guideModal .programOutcomeMapping-guide").removeClass('selected-attribute');
        $("#guideModal .standards-guide").removeClass('selected-attribute');
        $("#guideModal .coursesSummary-guide").removeClass('selected-attribute');
        $("#guideModal .syllabi-guide").removeClass('selected-attribute');
        // add attribute
        $("#guideModal .standards-guide").addClass('selected-attribute');
        // Reset Scroll to top
        $('.update-content').scrollTop(0);

        // Header
        $("#guideModal .modal-title").text('Standards and Strategic Priorities Guide');
        // Body
        $("#guideModal .update-content").html(`
            <h2 class="mb-4 head"><u>Standards and Strategic Priorities (Step 6)</u></h2>
            <br>
            <h2 class="text-center mb-4">How to Map your Course to Standards</h2>
            <p class="help-p my-3 center" style="font-size:18px;">The standards tab is selected by default when you open the page.</p>
            <img class="img center my-3" src="{{asset('/img/guide/StandardsAndStrategicPriorities.PNG')}}" onclick="onClick(this)">
            <br>
            <p class="help-p my-3 center" style="font-size:18px;">You will need to select a CLO to map the Standards to (You may need to double click the CLO in order for the dropdown to appear).</p>
            <img class="img center my-3" src="{{asset('/img/guide/StandardsCLOS.PNG')}}" onclick="onClick(this)">
            <br>
            <ol class="m-5">
                <li class="my-2" style="font-size: 18px;">Select the scale value which aligns best for your Course Learning Outcome to the Standard.</li>
                <li class="my-2" style="font-size: 18px;">Make sure you save your selection for each Course Learning Outcome.</li>
            </ol>
            <img class="img center my-3" src="{{asset('/img/guide/StandardsCLOScale.PNG')}}" onclick="onClick(this)">
            <br>
            <h2 class="text-center mb-4">How to Map your Course to Strategic Priorities</h2>
            <p class="help-p my-3 center" style="font-size:18px;">First you will need to select the Strategic Priorities tab.</p>
            <img class="img center my-3" src="{{asset('/img/guide/StrategicPriorities.png')}}" onclick="onClick(this)">
            <br>
            <p class="help-p my-3 center" style="font-size:18px;">Then you can select the from the drop down as shown below.</p>
            <img class="img center my-3" src="{{asset('/img/guide/StrategicPriority.png')}}" onclick="onClick(this)">
            <br>
            <ol class="m-5">
                <li class="my-2" style="font-size: 18px;">Select the Strategic Priority’s which align best for your course.</li>
                <li class="my-2" style="font-size: 18px;">Make sure you save your selection before leaving the page.</li>
            </ol>
            <img class="img center my-3" src="{{asset('/img/guide/StrategicPrioritySelection.png')}}" onclick="onClick(this)">

            <br>
            <div class="row">
                <div class="col"><button class="btn btn-primary float-left w-100" onclick="setProgramOutcomeMapping()" style="color: white;"><i class="bi bi-arrow-left mr-2"></i> Program Outcome Mapping (Step 5)</button></div>
                <div class="col"><button class="btn btn-primary float-right w-100" onclick="setCoursesSummary()" style="color: white;">Course Summary (Step 7) <i class="bi bi-arrow-right mr-2"></i></button></div>
            </div>
        `);
    }

    function setCoursesSummary() {
        // remove attributes
        $("#guideModal .programs-guide").removeClass('selected-attribute');
        $("#guideModal .plo-guide").removeClass('selected-attribute');
        $("#guideModal .ms-guide").removeClass('selected-attribute');
        $("#guideModal .programCourses-guide").removeClass('selected-attribute');
        $("#guideModal .programOverview-guide").removeClass('selected-attribute');
        $("#guideModal .courses-guide").removeClass('selected-attribute');
        $("#guideModal .clo-guide").removeClass('selected-attribute');
        $("#guideModal .sam-guide").removeClass('selected-attribute');
        $("#guideModal .tla-guide").removeClass('selected-attribute');
        $("#guideModal .courseAlignment-guide").removeClass('selected-attribute');
        $("#guideModal .programOutcomeMapping-guide").removeClass('selected-attribute');
        $("#guideModal .standards-guide").removeClass('selected-attribute');
        $("#guideModal .coursesSummary-guide").removeClass('selected-attribute');
        $("#guideModal .syllabi-guide").removeClass('selected-attribute');
        // add attribute
        $("#guideModal .coursesSummary-guide").addClass('selected-attribute');
        // Reset Scroll to top
        $('.update-content').scrollTop(0);

        // Header
        $("#guideModal .modal-title").text('Course Summary Guide');
        // Body
        $("#guideModal .update-content").html(`
            <h2 class="mb-4 head"><u>Course Summary (Step 7)</u></h2>
            <br>
            <p class="help-p my-3" style="font-size:18px;">The course overview provides a comprehensive view of all of the information regarding the course, as well as detailed charts from the information provided in the previous steps. You must fill in the previous steps in order to see some of the fields for the course overview.</p>
            <p class="help-p my-3" style="font-size:18px;">The course overview can be downloaded as a PDF copy.</p>
            <img class="img center my-3" src="{{asset('/img/guide/CourseOverview.png')}}" onclick="onClick(this)">
            <br>
            <button class="btn btn-primary float-left w-50" onclick="setStandards()" style="color: white;"><i class="bi bi-arrow-left mr-2"></i> Standards and Strategic Priorities (Step 6)</button>
        `);
    }

    function setSyllabi() {
        // remove attributes
        $("#guideModal .programs-guide").removeClass('selected-attribute');
        $("#guideModal .plo-guide").removeClass('selected-attribute');
        $("#guideModal .ms-guide").removeClass('selected-attribute');
        $("#guideModal .programCourses-guide").removeClass('selected-attribute');
        $("#guideModal .programOverview-guide").removeClass('selected-attribute');
        $("#guideModal .courses-guide").removeClass('selected-attribute');
        $("#guideModal .clo-guide").removeClass('selected-attribute');
        $("#guideModal .sam-guide").removeClass('selected-attribute');
        $("#guideModal .tla-guide").removeClass('selected-attribute');
        $("#guideModal .courseAlignment-guide").removeClass('selected-attribute');
        $("#guideModal .programOutcomeMapping-guide").removeClass('selected-attribute');
        $("#guideModal .standards-guide").removeClass('selected-attribute');
        $("#guideModal .coursesSummary-guide").removeClass('selected-attribute');
        $("#guideModal .syllabi-guide").removeClass('selected-attribute');
        // add attribute
        $("#guideModal .syllabi-guide").addClass('selected-attribute');
        // Reset Scroll to top
        $('.update-content').scrollTop(0);

        // Header
        $("#guideModal .modal-title").text('Syllabi Guide');
        // Body
        $("#guideModal .update-content").html(`
            <h2 class="mb-4 head"><u>Syllabi</u></h2>
            <br>
            <p class="help-p my-3 center" style="font-size:18px;">This page is under construction.</p>
        `);
    }

    // calls for on click methods (nav bar) 
    // Programs
    $('.programs-guide').on('click', setProgram);
    $('.plo-guide').on('click', setPLO);
    $('.ms-guide').on('click', setMS);
    $('.programCourses-guide').on('click', setProgramCourses);
    $('.programOverview-guide').on('click', setProgramOverview);
    // Courses
    $('.courses-guide').on('click', setCourses);
    $('.clo-guide').on('click', setCLO);
    $('.sam-guide').on('click', setSAM);
    $('.tla-guide').on('click', setTLA);
    $('.courseAlignment-guide').on('click', setCourseAlignment);
    $('.programOutcomeMapping-guide').on('click', setProgramOutcomeMapping);
    $('.standards-guide').on('click', setStandards);
    $('.coursesSummary-guide').on('click', setCoursesSummary);
    // Syllabi
    $('.syllabi-guide').on('click', setSyllabi);
    
    // Calls from Dashboard
    $('#programHelp').on('click', setProgram);
    $('#coursesHelp').on('click', setCourses);
    $('#syllabiHelp').on('click', setSyllabi);
    // Calls from program wizard
    $('#ploHelp').on('click', setPLO);
    $('#msHelp').on('click', setMS);
    $('#programCoursesHelp').on('click', setProgramCourses);
    $('#programOverviewHelp').on('click', setProgramOverview);
    // Calls from course wizard
    $('#cloHelp').on('click', setCLO);
    $('#samHelp').on('click', setSAM);
    $('#tlaHelp').on('click', setTLA);
    $('#courseAlignmentHelp').on('click', setCourseAlignment);
    $('#programOutcomeMappingHelp').on('click', setProgramOutcomeMapping);
    $('#standardsHelp').on('click', setStandards);
    $('#courseOverviewHelp').on('click', setCoursesSummary);

    function onClick(element) {
        document.getElementById("img01").src = element.src;
        document.getElementById("modal01").style.display = "block";
    }

</script>
<style>
    a{
        cursor: pointer;
    }
    .li{
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