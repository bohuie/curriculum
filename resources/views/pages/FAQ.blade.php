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
                        <div class="accordion-body">
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
                        <div class="accordion-body">
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
                        <div class="accordion-body">
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
                        <div class="accordion-body">
                            <p>Yes, you may request support for course and program mapping from the Centre for Teaching and Learning or the Provost Office.</p>
                        </div>
                    </div>
                </div>
            </div> 
        </div>
    </div>    
</div>

<!-- End here -->
@endsection
