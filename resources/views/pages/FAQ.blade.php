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
                            <h5>I want to map a course or program, or generate a syllabus for my course. Can somebody <b>help</b> me use this tool?</h5>                
                        </button>
                    </h2>
                                                        
                    <!-- FAQ Accordion body -->
                    <div id="collapseFAQAccordion4" class="accordion-collapse collapse" aria-labelledby="FAQAccordionHeader4" data-bs-parent="FAQAccordion4">
                        <div class="accordion-body lh-lg">
                            <p>
                                Yes, you may request support for course and program mapping from the Centre for Teaching and Learning by emailing <a href="mailto:ctl.helpdesk@ubc.ca">ctl.helpdesk@ubc.ca</a>. If you are in UBC's Vancouver campus, you can request support from the Centre for Teaching, Learning and Technology by emailing <a href="mailto:curriculum.support@ubc.ca">curriculum.support@ubc.ca</a>.
                            </p>
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
                            <p>The <a href="{{ url('/') }}" target="_BLANK" rel="noopener noreferrer">UBC Curriculum MAP</a> Syllabus Generator aims to assist faculty in preparing their syllabi. The generator follows the policies, guidelines and templates provided by the <a href="https://senate.ubc.ca/okanagan/consultations-surveys/policy-v-130-syllabus-policy/" target="_BLANK" rel="noopener noreferrer">UBC Okanagan</a> and <a href="https://senate.ubc.ca/policies-resources-support-student-success" target="_BLANK" rel="noopener noreferrer">UBC Vancouver senates</a>.</p>
                            <p class="fw-bold">How do I use the Curriculum MAP Syllabus Generator?</p>
                            <ol>
                                <li>Create a Curriculum MAP account by <a href="{{ route('register') }}" target="_BLANK" rel="noopener noreferrer">registering</a> with the site. After you register or if you already have an account, sign in to your Curriculum MAP account using the <a href="{{ route('login') }}" target="_BLANK" rel="noopener noreferrer">login</a> page.</li>
                                <li>Navigate to the syllabus generator by clicking on the `Syllabus Generator` tab on the navigation bar in the top right.</li>
                                <li>Fill in all other fields that are relevant to your course (or click the <i class="text-secondary bi bi-box-arrow-in-down-left"></i> icon to "Import" an existing course from your Dashboard)
                                    <ol type="a">
                                        <li>Required fields are marked with a <span class="requiredField">*</span>.</li>
                                        <li>Fields required by Senate policy are marked with a red label reading <span class="d-inline-block has-tooltip" tabindex="0" data-toggle="tooltip" data-bs-placement="top" title="This section is required in your syllabus by Vancouver Senate policy V-130"><button type="button" class="btn btn-danger btn-sm mb-2 disabled" style="font-size:10px;">Required by policy</button></span>.</li>
                                    </ol>
                                </li>
                                <li>Select your campus.</li>
                                <li>At the bottom of the page, select optional but recommended campus-specific resources you wish to include in your syllabus.</li>
                                <li>Syllabus can be downloaded as Word document and PDF. Click the Word or PDF icon that can be found at both the top and bottom of the screen and the syllabus will be downloaded in the respective format.</li>
                                <li>Review the design and content of your generated syllabus Word document and update it accordingly.</li>
                                <li>You can find your saved syllabi on your <a href="{{ route('home')}}" target="_BLANK" rel="noopener noreferrer">Curriculum MAP Dashboard</a> (Upon log in).  </li>
                                <li>From your dashboard you can also share your syllabus with other users. To share your syllabus with others:
                                    <ol type="a">
                                        <li>Click on the <i class="bi bi-gear-fill"></i> icon for the syllabus on your Dashboard. A drop down menu will appear and select Collaborators.</li>
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
                            <h5>What are Creative Commons open copyright licenses?</h5>                        
                        </button>
                    </h2>
                                                        
                    <!-- FAQ Accordion body -->
                    <div id="collapseFAQAccordion6" class="accordion-collapse collapse" aria-labelledby="FAQAccordionHeader6" data-bs-parent="FAQAccordion6">
                        <div class="accordion-body lh-lg">
                            <p>Creative Commons is a non-profit organization whose mandate is to make it easier for creators to share their work and/or build upon the works of others consistent with the rules of copyright. They have created standard, easy to use and understand copyright licenses that anybody can apply to their work to allow others to share, remix, or use the work without having to contact the copyright owner to ask for permission. There are several Creative Commons licenses, each with a different level of use restrictions.</p>
                            <p>Creative Commons licenses are not an alternative or exception to copyright, they are one way for copyright owners to distribute their work within the copyright framework.</p>
                            <p>For more information please visit <a href="https://copyright.ubc.ca/creative-commons/">https://copyright.ubc.ca/creative-commons/</a>.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="accordion" id="FAQAccordion7">
                <div class="accordion-item mb-2">
                    <!-- FAQ accordion header -->
                    <h2 class="accordion-header fs-2" id="FAQAccordionHeader7">
                        <button class="accordion-button collapsed program white-arrow" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFAQAccordion7" aria-expanded="false" aria-controls="collapseFAQAccordion7">
                            <h5>I have applied Creative Commons open copyright license to my syllabus. How do I share it?</h5>                        
                        </button>
                    </h2>
                                                        
                    <!-- FAQ Accordion body -->
                    <div id="collapseFAQAccordion7" class="accordion-collapse collapse" aria-labelledby="FAQAccordionHeader7" data-bs-parent="FAQAccordion7">
                        <div class="accordion-body lh-lg">
                            <p>Applying a Creative Commons open copyright license to your syllabus is only the first step. The next step is making sure that it is discoverable and usable by other instructors. You can do this by submitting your syllabus to an open educational resource repository (OERR). An OERR is an online storage system that allows educators to share, manage and use education resources. You can find a list of OERR at <a href="https://guides.library.ubc.ca/open-education/sharing">https://guides.library.ubc.ca/open-education/sharing</a>.</p>
                            <p>You may also want to submit your syllabus to the UBC OER Collection. The goal of the <a href="https://oer.open.ubc.ca/">UBC OER Collection</a> is to showcase UBC OER content in a searchable interface to support both UBC faculty and the general community in incorporating open educational resources and practices into their curriculum. Learn more and submit your syllabus at <a href="https://oer.open.ubc.ca/">https://oer.open.ubc.ca/<a>.</p>
                            <p>For any questions related to open education resources (OER) at UBC please contact open@ubc.ca.</p>
                            </div>
                    </div>
                </div>
            </div> 

            <div class="accordion" id="FAQAccordion8">
                <div class="accordion-item mb-2">
                    <!-- FAQ accordion header -->
                    <h2 class="accordion-header fs-2" id="FAQAccordionHeader8">
                        <button class="accordion-button collapsed program white-arrow" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFAQAccordion8" aria-expanded="false" aria-controls="collapseFAQAccordion8">
                            <h5>How do I <b>create</b> a program?</h5>                
                        </button>
                    </h2>
                                                        
                    <!-- FAQ Accordion body -->
                    <div id="collapseFAQAccordion8" class="accordion-collapse collapse" aria-labelledby="FAQAccordionHeader8" data-bs-parent="FAQAccordion8">
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
            
            <div class="accordion" id="FAQAccordion9">
                <div class="accordion-item mb-2">
                    <!-- FAQ accordion header -->
                    <h2 class="accordion-header fs-2" id="FAQAccordionHeader9">
                        <button class="accordion-button collapsed program white-arrow" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFAQAccordion9" aria-expanded="false" aria-controls="collapseFAQAccordion9">
                            <h5>How do I register an account with this website?</h5>                
                        </button>
                    </h2>
                                                        
                    <!-- FAQ Accordion body -->
                    <div id="collapseFAQAccordion9" class="accordion-collapse collapse" aria-labelledby="FAQAccordionHeader9" data-bs-parent="FAQAccordion9">
                        <div class="accordion-body lh-lg">
                            <p><a href="https://curriculum.ok.ubc.ca/">Curriculum MAP<a> is a tool to support curriculum mapping, analysis and planning. Curriculum MAP also has a feature to generate syllabi according to the policies, guidelines and templates provided by <a href="https://senate.ubc.ca/okanagan/">UBC Okanagan<a> and <a href="https://senate.ubc.ca/vancouver/">UBC Vancouver<a> senates.</p>
                            <p class="fw-bold">How do I register?</p>
                            <ol>
                                <li>Click on "Register" at the top of the <a href="https://curriculum.ok.ubc.ca/">Curriculum MAP Website<a>.</li>
                                <li>Create an account by entering your name, email address, and a password for the account.</li>
                                <li>Click the <button type="submit" class="btn btn-primary">{{ __('Register') }}</button> button. A verification email will be sent to the email you have entered.</li>
                                <li>Navigate to your email and open the verification notification.</li>
                                <li>Click on the <img src=" {{ asset('img/verify.png') }}"/> button in the email.</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <div class="accordion" id="FAQAccordion10">
                <div class="accordion-item mb-2">
                    <!-- FAQ accordion header -->
                    <h2 class="accordion-header fs-2" id="FAQAccordionHeader10">
                        <button class="accordion-button collapsed program white-arrow" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFAQAccordion10" aria-expanded="false" aria-controls="collapseFAQAccordion10">
                            <h5>I registered my email account, but I cannot log in. Why?</h5>                
                        </button>
                    </h2>
                                                        
                    <!-- FAQ Accordion body -->
                    <div id="collapseFAQAccordion10" class="accordion-collapse collapse" aria-labelledby="FAQAccordionHeader10" data-bs-parent="FAQAccordion10">
                        <div class="accordion-body lh-lg">
                            <p class="fw-bold">There are one of two reasons why you are unable to log in:</p>
                            <ol>
                                <li>You are using a different email address than the one you used to create the account. Try to log in with another email address. If this does not work then you will have to register again. </li>
                                <li>It has been too long since you registered the account and failed to activate it. You need to register again and activate your account by logging into Curriculum MAP immediately. </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <div class="accordion" id="FAQAccordion11">
                <div class="accordion-item mb-2">
                    <!-- FAQ accordion header -->
                    <h2 class="accordion-header fs-2" id="FAQAccordionHeader11">
                        <button class="accordion-button collapsed program white-arrow" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFAQAccordion11" aria-expanded="false" aria-controls="collapseFAQAccordion11">
                            <h5>How do I <b>duplicate</b> a course, program, or syllabus?</h5>                
                        </button>
                    </h2>
                                                        
                    <!-- FAQ Accordion body -->
                    <div id="collapseFAQAccordion11" class="accordion-collapse collapse" aria-labelledby="FAQAccordionHeader11" data-bs-parent="FAQAccordion11">
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

            <div class="accordion" id="FAQAccordion12">
                <div class="accordion-item mb-2">
                    <!-- FAQ accordion header -->
                    <h2 class="accordion-header fs-2" id="FAQAccordionHeader12">
                        <button class="accordion-button collapsed program white-arrow" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFAQAccordion12" aria-expanded="false" aria-controls="collapseFAQAccordion12">
                            <h5>How do I add <b>collaborators</b> to my course, program, or syllabus?</h5>         
                        </button>
                    </h2>
                                                        
                    <!-- FAQ Accordion body -->
                    <div id="collapseFAQAccordion12" class="accordion-collapse collapse" aria-labelledby="FAQAccordionHeader12" data-bs-parent="FAQAccordion12">
                        <div class="accordion-body lh-lg">
                            <p>The collaborators feature allows an owner of a course, program or syllabus to share their work with other users. The owner can select from two types of collaborators, viewers and editors. A Viewer is restricted to only being able to view the summary information, whereas an editor can make changes and create new elements, but cannot add collaborators or delete the Course, Program, or Syllabus. An example of the collaborators feature would be to add a teaching assistant to your course or syllabus as a viewer, so they can access course information.</p>
                            <p style="font-size:small" ><b>Note</b>: You must own the course, program, or syllabus in order to add collaborators.</p>
                            <p class="fw-bold">From the dashboard</p>
                            <ol>
                                <li>Click on the <i class="bi bi-gear-fill"></i> icon for the syllabus on your Dashboard. A drop down menu will appear and select Collaborators. </li>
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

            <div class="accordion" id="FAQAccordion13">
                <div class="accordion-item mb-2">
                    <!-- FAQ accordion header -->
                    <h2 class="accordion-header fs-2" id="FAQAccordionHeader13">
                        <button class="accordion-button collapsed program white-arrow" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFAQAccordion13" aria-expanded="false" aria-controls="collapseFAQAccordion13">
                            <h5>I ran into an error message or bug. What do I do?</h5>                
                        </button>
                    </h2>
                                                        
                    <!-- FAQ Accordion body -->
                    <div id="collapseFAQAccordion13" class="accordion-collapse collapse" aria-labelledby="FAQAccordionHeader13" data-bs-parent="FAQAccordion13">
                        <div class="accordion-body lh-lg">
                            <p>
                                Please let us know right away so we can fix it and support you by emailing <a href="mailto:ctl.helpdesk@ubc.ca">ctl.helpdesk@ubc.ca</a>.                            
                            </p>
                        </div>
                    </div>
                </div>
            </div> 
            <div class="accordion" id="FAQAccordion14">
                <div class="accordion-item mb-2">
                    <!-- FAQ accordion header -->
                    <h2 class="accordion-header fs-2" id="FAQAccordionHeader14">
                        <button class="accordion-button collapsed program white-arrow" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFAQAccordion14" aria-expanded="false" aria-controls="collapseFAQAccordion14">
                            <h5>Can non-UBC users create an account and use this web application?</h5>                
                        </button>
                    </h2>
                                                        
                    <!-- FAQ Accordion body -->
                    <div id="collapseFAQAccordion14" class="accordion-collapse collapse" aria-labelledby="FAQAccordionHeader14" data-bs-parent="FAQAccordion14">
                        <div class="accordion-body lh-lg">
                            <p>
                            While this tool was created to serve the UBC community, anyone can register and use the site. However, since information such as BC’s Ministry-related standards and UBC strategic plans are presented, not all users may benefit from every feature.                            
                            </p>
                        </div>
                    </div>
                </div>
            </div> 
            <div class="accordion" id="FAQAccordion15">
                <div class="accordion-item mb-2">
                    <!-- FAQ accordion header -->
                    <h2 class="accordion-header fs-2" id="FAQAccordionHeader15">
                        <button class="accordion-button collapsed program white-arrow" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFAQAccordion15" aria-expanded="false" aria-controls="collapseFAQAccordion15">
                            <h5>Where is my information stored?</h5>                
                        </button>
                    </h2>
                                                        
                    <!-- FAQ Accordion body -->
                    <div id="collapseFAQAccordion15" class="accordion-collapse collapse" aria-labelledby="FAQAccordionHeader15" data-bs-parent="FAQAccordion15">
                        <div class="accordion-body lh-lg">
                            <p>
                            The information gathered on the UBC Curriculum MAP website is collected under the authority of section 26(e) of the British Columbia <a href="https://www.bclaws.gov.bc.ca/civix/document/id/complete/statreg/96165_02">Freedom of Information and Protection of Privacy Act</a>.
                            <br>
                            <br>
                            Information entered by website users will only be used for the intended purposes of allowing instructors and departments to make informed decisions about course/program design and enhance the overall student learning experience. Website users retain authorship of information entered, and as such have ownership and responsibility for content. Specific permission for access to content may be sought for the purpose of evaluating and improving the UBC Curriculum MAP tool. Any data entered into the UBC Curriculum MAP website is stored on secure UBC Servers.
                            <br>
                            <br>
                            UBC reserves the right to amend the website’s terms of use at any time and will notify registered users of changes. Use of this website is subject to UBC’s Information Systems Policy.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="accordion" id="FAQAccordion16">
                <div class="accordion-item mb-2">
                    <!-- FAQ accordion header -->
                    <h2 class="accordion-header fs-2" id="FAQAccordionHeader16">
                        <button class="accordion-button collapsed program white-arrow" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFAQAccordion16" aria-expanded="false" aria-controls="collapseFAQAccordion16">
                            <h5>Which web browser is recommended?</h5>                        
                        </button>
                    </h2>
                                                        
                    <!-- FAQ Accordion body -->
                    <div id="collapseFAQAccordion16" class="accordion-collapse collapse" aria-labelledby="FAQAccordionHeader16" data-bs-parent="FAQAccordion16">
                        <div class="accordion-body lh-lg">
                            <p>Google Chrome and Mozilla Firefox are strongly recommended. This tool is not optimized for use with other web browsers and may perform unexpectedly.</p>
                        </div>
                    </div>
                </div>
            </div> 

            <div class="accordion" id="FAQAccordion17">
                <div class="accordion-item mb-2">
                    <!-- FAQ accordion header -->
                    <h2 class="accordion-header fs-2" id="FAQAccordionHeader17">
                        <button class="accordion-button collapsed program white-arrow" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFAQAccordion17" aria-expanded="false" aria-controls="collapseFAQAccordion17">
                            <h5>Can I collaborate with someone outside my organization?</h5>                
                        </button>
                    </h2>
                                                        
                    <!-- FAQ Accordion body -->
                    <div id="collapseFAQAccordion17" class="accordion-collapse collapse" aria-labelledby="FAQAccordionHeader17" data-bs-parent="FAQAccordion17">
                        <div class="accordion-body lh-lg">
                            <p>
                            Yes, the “collaboration” feature allows all users to collaborate with anybody via a valid email address.                              
                        </p>
                        </div>
                    </div>
                </div>
            </div> 
            
            <div class="accordion" id="FAQAccordion18">
                <div class="accordion-item mb-2">
                    <!-- FAQ accordion header -->
                    <h2 class="accordion-header fs-2" id="FAQAccordionHeader18">
                        <button class="accordion-button collapsed program white-arrow" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFAQAccordion18" aria-expanded="false" aria-controls="collapseFAQAccordion18">
                            <h5>How do completed curriculum maps look like? Can I see an example? </h5>                
                        </button>
                    </h2>
                                                        
                    <!-- FAQ Accordion body -->
                    <div id="collapseFAQAccordion18" class="accordion-collapse collapse" aria-labelledby="FAQAccordionHeader18" data-bs-parent="FAQAccordion18">
                        <div class="accordion-body lh-lg">
                            <p>
                            Yes, following are two examples of curriculum maps using this site:
                            <br>
                            <a href="https://ctl.ok.ubc.ca/wp-content/uploads/sites/186/2024/08/Curriculum-map-for-Psychology-major.pdf">Curriculum Map for Psychology Major (PDF)</a> or <a href="https://ctl.ok.ubc.ca/wp-content/uploads/sites/186/2024/08/CurriculumMAP_Psych_XC_2024.xlsx">Curriculum Map for Psychology Major (Excel)</a>
                            <br>
                            <a href="https://ctl.ok.ubc.ca/wp-content/uploads/sites/186/2024/08/Curriculum-map-for-CORH-Certificate.pdf">Curriculum Map for CORH Certificate (PDF)</a> or <a href="https://ctl.ok.ubc.ca/wp-content/uploads/sites/186/2024/08/CurriculumMAP_COHR_XC_2024.xlsx">Curriculum Map for CORH Certificate (Excel)</a></a>
                            <br>
                            <br>
                            The Certificate used three categories for the program's learning outcomes (10 learning outcomes), while the B.A. in Psychology used five categories for the 19 program learning outcomes. Information on assessment methods and Ministry standards follow the curriculum map tables when information is made available. 
                        </p>
                        </div>
                    </div>
                </div>
            </div> 
        </div>
    </div>    
</div>

<!-- End here -->
@endsection
