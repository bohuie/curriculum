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
                            Can I use this mapping website if I don’t have all course details?                        
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
                            Can I view how different courses map to different program learning outcomes?                        
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
                            How do I retrieve a course or program that I deleted in the past?                         
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
                            Can somebody help me use this tool?                
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
                            How do I generate a syllabus?                
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
                                    <p style="font-size:small"><b>Note</b>: Your collaborator must have registered on this site before you can add them</p>
                                </li>
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
