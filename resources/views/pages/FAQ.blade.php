@extends('layouts.app')

@section('content')

<link href=" {{ asset('css/accordions.css') }}" rel="stylesheet" type="text/css" >
<!--Link for FontAwesome Font for the arrows for the accordions.-->
<link href="https://use.fontawesome.com/releases/v5.8.2/css/all.css" integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" crossorigin="anonymous" rel="stylesheet" type="text/css" >


<div class="row p-md-5 justify-content-center text-dark bg-secondary">
    <div class="container">
        <div class="row">
            <div style="width: 100%;">
                <h1 style="text-align:center;">FAQ</h1>
            </div>

            <div class="accordion" id="FAQAccordion1">
                <div class="accordion-item mb-2">
                    <!-- FAQ accordion header -->
                    <h2 class="accordion-header fs-2" id="FAQAccordionHeader1">
                        <button class="accordion-button collapsed program white-arrow" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFAQAccordion1" aria-expanded="false" aria-controls="collapseFAQAccordion1">
                            <h5>Can I use this mapping website if I don’t have all course details?</h5>                        
                        </button>
                    </h2>
                                                        
                    <!-- FAQ Accordion body -->
                    <div id="collapseFAQAccordion1" class="accordion-collapse collapse" aria-labelledby="FAQAccordionHeader1" data-bs-parent="FAQAccordion1">
                        <div class="accordion-body lh-lg">
                            <p>Yes, the minimum requirement to use this tool is a set of course learning outcomes or competencies. All other requested information is optional.</p>
                        </div>
                    </div>
                </div>
            </div> 

            <div class="accordion" id="FAQAccordion2">
                <div class="accordion-item mb-2">
                    <!-- FAQ accordion header -->
                    <h2 class="accordion-header fs-2" id="FAQAccordionHeader2">
                        <button class="accordion-button collapsed program white-arrow" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFAQAccordion2" aria-expanded="false" aria-controls="collapseFAQAccordion2">
                            <h5>Can I <b>view</b> how different courses map to different program learning outcomes?</h5>                        
                        </button>
                    </h2>
                                                        
                    <!-- FAQ Accordion body -->
                    <div id="collapseFAQAccordion2" class="accordion-collapse collapse" aria-labelledby="FAQAccordionHeader2" data-bs-parent="FAQAccordion2">
                        <div class="accordion-body lh-lg">
                            <p>Yes, you may map one course to as many sets of program-learning outcomes or competencies as you like.</p>
                        </div>
                    </div>
                </div>
            </div> 

            <div class="accordion" id="FAQAccordion3">
                <div class="accordion-item mb-2">
                    <!-- FAQ accordion header -->
                    <h2 class="accordion-header fs-2" id="FAQAccordionHeader3">
                        <button class="accordion-button collapsed program white-arrow" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFAQAccordion3" aria-expanded="false" aria-controls="collapseFAQAccordion3">
                            <h5>Can I retrieve a course or program that I <b>deleted</b> in the past?</h5>                         
                        </button>
                    </h2>
                                                        
                    <!-- FAQ Accordion body -->
                    <div id="collapseFAQAccordion3" class="accordion-collapse collapse" aria-labelledby="FAQAccordionHeader3" data-bs-parent="FAQAccordion3">
                        <div class="accordion-body lh-lg">
                            <p>Once you have deleted a course or a program, you are not able to retrieve it.</p>
                        </div>
                    </div>
                </div>
            </div> 

            <div class="accordion" id="FAQAccordion4">
                <div class="accordion-item mb-2">
                    <!-- FAQ accordion header -->
                    <h2 class="accordion-header fs-2" id="FAQAccordionHeader4">
                        <button class="accordion-button collapsed program white-arrow" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFAQAccordion4" aria-expanded="false" aria-controls="collapseFAQAccordion4">
                            <h5>Can somebody <b>help</b> me use this tool?</h5>                
                        </button>
                    </h2>
                                                        
                    <!-- FAQ Accordion body -->
                    <div id="collapseFAQAccordion4" class="accordion-collapse collapse" aria-labelledby="FAQAccordionHeader4" data-bs-parent="FAQAccordion4">
                        <div class="accordion-body lh-lg">
                            <p>Yes, you may request support for course and program mapping from the Centre for Teaching and Learning or the Provost Office.</p>
                        </div>
                    </div>
                </div>
            </div> 

            <div class="accordion" id="FAQAccordion5">
                <div class="accordion-item mb-2">
                    <!-- FAQ accordion header -->
                    <h2 class="accordion-header fs-2" id="FAQAccordionHeader5">
                        <button class="accordion-button collapsed program white-arrow" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFAQAccordion5" aria-expanded="false" aria-controls="collapseFAQAccordion5">
                            <h5>How do I <b>generate a syllabus</b>?</h5>                
                        </button>
                    </h2>
                                                        
                    <!-- FAQ Accordion body -->
                    <div id="collapseFAQAccordion5" class="accordion-collapse collapse" aria-labelledby="FAQAccordionHeader5" data-bs-parent="FAQAccordion5">
                        <div class="accordion-body lh-lg">
                            <p>The <a href="{{ url('/') }}" target="_BLANK">UBC Curriculum MAP</a> Syllabus Generator aims to assist faculty in preparing their syllabi. The generator follows the policies, guidelines and templates provided by the <a href="https://senate.ubc.ca/okanagan/curriculum/forms" target="_BLANK">UBC Okanagan</a> and <a href="https://senate.ubc.ca/policies-resources-support-student-success" target="_BLANK">UBC Vancouver senates</a>.</p>
                            <p class="fw-bold">How do I use the Curriculum MAP Syllabus Generator?</p>
                            <ol>
                                <li>Create a Curriculum MAP account by <a href="{{ route('register') }}" target="_BLANK">registering</a> with the site. After you register or if you already have an account, sign in to your Curriculum MAP account using the <a href="{{ route('login') }}" target="_BLANK">login</a> page.</li>
                                <li>Navigate to the syllabus generator by clicking on the `Syllabus Generator` tab on the navigation bar in the top right.</li>
                                <br>
                                <p style="font-size:small" ><b>Note</b>: The “import an existing course” button on the top of the syllabus generator allows users to import course information that has been inputted in the site already, for curriculum mapping purposes. These courses appear in the “my courses” section of the dashboard. Select the course you would like to import. </p>
                                <li>Select your campus.</li>
                                <li>Fill in the required course information marked with a <span class="requiredField">*</span>.</li>
                                <li>Fill in all the other fields that are relevant to your course.
                                    <ol type="a">
                                        <li>For Vancouver syllabi, some fields are required by <a href="https://senate.ubc.ca/policies-resources-support-student-success" target="_BLANK">Vancouver Senate policy V-130</a>. These fields are marked with a red label reading <span class="d-inline-block has-tooltip" tabindex="0" data-toggle="tooltip" data-bs-placement="top" title="This section is required in your syllabus by Vancouver Senate policy V-130"><button type="button" class="btn btn-danger btn-sm mb-2 disabled" style="font-size:10px;">Required by policy</button></span>.</li>
                            </ol>
                                </li>
                                <li>At the bottom of the page, select optional but recommended campus-specific resources you wish to include in your syllabus.</li>
                                <li>To download your syllabus as a Word document, click the `Save and Download` button at the bottom of the page. Otherwise, click the `Save` button to save your progress/changes. This will not generate a Word document.</li>
                                <li>Review the design and content of your generated syllabus Word document and update it accordingly.</li>
                                <li>You can find your saved syllabi on your <a href="{{ route('home')}}" target="_BLANK">Curriculum MAP Dashboard</a> (Upon log in).  </li>
                                <li>From your dashboard you can also share your syllabus with other users. To share your syllabus with others:
                                    <ol type="a">
                                        <li>Click the <i class="bi bi-person-plus-fill pr-1 pl-1"></i> button for your syllabus in the syllabus section of your dashboard.</li>
                                        <li>In the pop-up window, input your collaborators email and select their role (Editor or Viewer).</li>
                                        <li>Click '<i class="bi bi-plus"></i> Collaborator' and 'Save Changes'</li>
                                        <br>
                                    </ol>
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div> 

            <div class="accordion" id="FAQAccordion6">
                <div class="accordion-item mb-2">
                    <!-- FAQ accordion header -->
                    <h2 class="accordion-header fs-2" id="FAQAccordionHeader6">
                        <button class="accordion-button collapsed program white-arrow" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFAQAccordion6" aria-expanded="false" aria-controls="collapseFAQAccordion6">
                            <h5>How do I <b>create</b> a program?</h5>                
                        </button>
                    </h2>
                                                        
                    <!-- FAQ Accordion body -->
                    <div id="collapseFAQAccordion6" class="accordion-collapse collapse" aria-labelledby="FAQAccordionHeader6" data-bs-parent="FAQAccordion6">
                        <div class="accordion-body lh-lg">
                            <p>To create a program, first register a Curriculum MAP account or login. On your dashboard, you will see your "Programs".</p>
                            <p class="fw-bold">From the dashboard</p>
                            <ol style="list-style: none;">
                                <li>Click the “+” sign on the right-hand side to begin creating a program. Fill in the required program information marked with a *.</li>
                                <li>You can find your newly created program on your dashboard in the Programs section.</li>
                                <li>Click on your program name or its edit icon to start building your program.</li>
                            </ol>
                            <p class="mt-3 fw-bold">The site will walk you through the steps needed to build your program, by:</p>
                            <ol>
                                <li>Identifying the program learning outcomes or PLOs</li>
                                <ol type="a">
                                    <li>You may organize your PLOs into "categories" to separate PLOs from discipline standards, skills, etc.</li>
                                </ol>
                                <li>Choosing a mapping scale</li>
                                <ol type="a">
                                    <li>This will be the scale used to map your PLOs to each course in the your program.</li>
                                    <li>Depending on your discipline, you may want to create your own mapping scale or choose one of the default ones</li>
                                </ol>
                                <li>Identify the courses associated with the program. Once these have been identified, <b>they must be mapped individually to the program by the course owner.</b></li>
                                <ol type="a">
                                    <li>If you own one or many of those courses, this requires you to click on “map course” to complete the map between the course and the PLOs you identified.</li>
                                    <li>If you do not own the course, you can let the course owner know that your PLOs are now ready to be mapped against their course by clicking “ask to map course”.</li>
                                </ol>
                                <li><b>Once all courses have been individually mapped to the program you created</b>, you may go to “Program Overview” or step 4. This page will summarize the program mapping done so far. Use the interactive table to find out strengths and weaknesses of your program. Consider printing and sharing this summary with your colleagues to engage in curriculum re-design!</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="accordion" id="FAQAccordion7">
                <div class="accordion-item mb-2">
                    <!-- FAQ accordion header -->
                    <h2 class="accordion-header fs-2" id="FAQAccordionHeader7">
                        <button class="accordion-button collapsed program white-arrow" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFAQAccordion7" aria-expanded="false" aria-controls="collapseFAQAccordion7">
                            <h5>How do I <b>duplicate</b> a course, program, or syllabus?</h5>                
                        </button>
                    </h2>
                                                        
                    <!-- FAQ Accordion body -->
                    <div id="collapseFAQAccordion7" class="accordion-collapse collapse" aria-labelledby="FAQAccordionHeader7" data-bs-parent="FAQAccordion7">
                        <div class="accordion-body lh-lg">
                            <p style="font-size:small" ><b>Note</b>: You must own the course, program, or syllabus in order to duplicate it.</p>
                            <p class="fw-bold">From the dashboard</p>
                            <ol>
                                <li>Click on either the course, program, or syllabus you would like to duplicate.</li>
                                <li>Click on the green 'Duplicate' button, which is located in the top right.</li>
                                <li>You will then be prompted to fill out some information for the item you wish to duplicate, after submitting the form you will see the newly duplicated item on your dashboard.</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <div class="accordion" id="FAQAccordion8">
                <div class="accordion-item mb-2">
                    <!-- FAQ accordion header -->
                    <h2 class="accordion-header fs-2" id="FAQAccordionHeader8">
                        <button class="accordion-button collapsed program white-arrow" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFAQAccordion8" aria-expanded="false" aria-controls="collapseFAQAccordion8">
                            <h5>How do I add <b>collaborators</b> to my course, program, or syllabus?</h5>         
                        </button>
                    </h2>
                                                        
                    <!-- FAQ Accordion body -->
                    <div id="collapseFAQAccordion8" class="accordion-collapse collapse" aria-labelledby="FAQAccordionHeader8" data-bs-parent="FAQAccordion8">
                        <div class="accordion-body lh-lg">
                            <p>The collaborators feature allows an owner of a course, program or syllabus to share their work with other users. The owner can select from two types of collaborators, viewers and editors. A Viewer is restricted to only being able to view the summary information, whereas an editor can make changes and create new elements, but cannot add collaborators or delete the Course, Program, or Syllabus. An example of the collaborators feature would be to add a teaching assistant to your course or syllabus as a viewer, so they can access course information.</p>
                            <p style="font-size:small" ><b>Note</b>: You must own the course, program, or syllabus in order to add collaborators.</p>
                            <p class="fw-bold">From the dashboard</p>
                            <ol>
                                <li>Click on the <div class="collabIcon btn bg-transparent position-relative pr-2 pl-2" data-bs-placement="right" style="cursor: default;"><div><i class="bi bi-person-plus-fill"></i><span class="position-absolute top-0 start-85 translate-middle badge rounded-pill badge badge-dark">?</span></div></div> button under actions.</li>
                                <li>Enter the collaborators email and select either viewer or editor.</li>
                                <ol type="a">
                                    <li><b>Viewers</b>: can view an overview of your program but cannot edit or delete your program or add/remove collaborators.</li>
                                    <li><b>Editors</b>: have access to edit and view your program but cannot delete your program or add/remove collaborators.</li>
                                </ol>
                                <li>Click the <div id="exampleBtn" class="btn btn-primary" style="cursor: default;"><i class="bi bi-plus"></i> Collaborator</div> button, you will then see the collaborator appear in the table below.</li>
                                <li>Finally click the <div class="btn btn-success m-1" style="cursor: default;">Save Changes</div> button to add your collaborators.</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>    
</div>

<!-- End here -->
@endsection
