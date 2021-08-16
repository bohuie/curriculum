@extends('layouts.app')

@section('content')

    <div class="row p-md-5 justify-content-center text-light bg-primary">
        <div class="col-md-12">
            <div class="container">
                <h1>Curriculum MAP</h1>                                                  
                <p class="lead">A tool to support curriculum mapping, analysis, and planning.</p>
                
                <div class="row">
                    <div class="col-sm"> 
                        <div class="img">
                            
                            <img src=" {{ asset('img/Ideation.png') }}"/>
                        </div>
                        <h4>Ideation</h4>
                        <p class="lead">Allows instructors to map program learning outcomes (PLOs) to course learning outcomes (CLOs) of required and non-required courses for the program.</p>
                    </div>
                    <div class="col-sm">
                
                        <div class="img"> 
                            
                            <img src=" {{ asset('img/Creation.png') }}"/>
                        </div>
                        <h4>Creation</h4>
                        <p class="lead">Allows instructors to create a new course by identifying course learning outcomes, assessment strategies, and teaching and learning methods.</p>
                    </div>
                    <div class="col-sm">
                        <div class="img">
                            
                            <img src=" {{ asset('img/Evaluation.png') }}"/>
                        </div>
                        <h4>Evaluation</h4>
                        <p class="lead">Allows instructors to evaluate existing courses or programs by looking at the alignment across learning outcomes, assessment methods, and teaching and learning activities.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--
    <div class="row p-md-5 justify-content-center text-dark bg-secondary">
        <div class="container">
            <div class="quote">
                <h4><strong>
                “...make learning and teaching more <br>meaningful to students and teachers.” 
                </strong></h4>
                <p class="lead">
                Lam and Tsui, 2016
                </p>
            </div>
            <div class="span4">
                <div class="text-center clearfix">
                        <\!-- Image recoloured from: https://www.flaticon.com/free-icon/conversation-mark-interface-symbol-of-circular-speech-bubble-with-quotes-signs-inside_40341 \-\->
                        <img src=" {{ asset('img/quote4_blue3_400x400.png') }}"/>
                    <div style="display:inline-block">
                        <h4><strong>
                        “develop, review, improve <br>and perfect an integrated <br>curriculum, including <br>curriculum alignment”
                        </strong></h4>
                        <p class="lead">
                        Khoerunnisa et al, 2018
                        </p>
                    </div>
                </div>  
            </div>

            <div class="quote">
                <h4><strong>
                    “...understand curriculum structures and relationships, gain <br>insight in how students experience their discipline, and <br>increase awareness of curricular content.” 
                </strong></h4>
                <p class="lead">
                    Archambault and Masunaga, 2015
                </p>
            </div>
        </div>
    </div>

    
    <div class="row p-md-2 justify-content-center text-light bg-primary">
        <div class="container">
                <div class="col-md font-weight-bold">

                &nbsp;&nbsp;
                
                </div>
        </div>
    </div>
    -->
    <div class="row p-md-5 justify-content-center text-dark bg-secondary">
        <div class="container">
            <h1>Benefits</h1>
            <div class="row pb-sm-5">
                <div class="col-sm">
                    <!-- replace this with img! -->
                    <div class="box-img">
                        <img src=" {{ asset('img/Checkmark.png') }}" />
                    </div>
                    <div class="box-text">
                        <h2>
                            Student Success
                        </h2>
                        <p class="lead">
                            Give students a better <br>understanding of what is expected <br>of them, and what they will <br>accomplish in different courses.
                        </p>
                    </div>
                </div>
                <div class="col-sm">
                    <!-- replace this with img! -->
                    <div class="box-img">
                        <img src=" {{ asset('img/Checkmark.png') }}" />
                    </div>
                    <div class="box-text">
                        <h2>
                            Quality Assurance
                        </h2>
                        <p class="lead">
                            Allow for identification of gaps in <br>course offerings as well as <br>redundancies.
                        </p>
                    </div>
                </div>
            </div>
            <div class="row pb-sm-5">
                <div class="col-sm">
                    <!-- replace this with img! -->
                    <div class="box-img">
                        <img src=" {{ asset('img/Checkmark.png') }}" />
                    </div>
                    <div class="box-text">
                        <h2>
                            Improve Learning
                        </h2>
                        <p class="lead">
                            Help faculty use <br>evidence-based information, <br>see relationships between <br>course and overall program <br>goals, and learning outcomes.
                        </p>
                    </div>
                </div>
                <div class="col-sm">
                    <!-- replace this with img! -->
                    <div class="box-img">
                        <img src=" {{ asset('img/Checkmark.png') }}" />
                    </div>
                    <div class="box-text">
                        <h2>
                            Staff Collaboration
                        </h2>
                        <p class="lead">
                            Provide an opportunity for faculty <br>to work together and help new <br>faculty develop professional <br>relationships and a sense of <br>belonging.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <div class="row p-md-5 justify-content-center text-light bg-primary">
        <div class="container">
                <div class="col-md font-weight-bold">

                    <h1><strong>How to Use Curriculum MAP?</strong></h1>
                    
                    <p class="lead">
                        To use this website, users must:
                    </p>

                    <ol>
                        <li class="lead">Register on the website (registration button can be found on the top banner).</li>
                        <li class="lead">Choose whether to create a course or a program for curriculum mapping.</li>
                        <li class="lead">Follow the prompts and steps shown on the website.</li>
                        <li class="lead">Save or print the results.</li>
                        <li class="lead">Log back in to review or edit your work.</li>
                    </ol>

                    <p class="lead">
                        Be ready to input this information when prompted by the application. The tool will walk you through a series of steps ending with a summary of your curriculum alignment.
                    </p>
                </div>
        </div>
    </div>

    <div class="row p-md-5 justify-content-center text-dark bg-secondary">
        <div class="container">
            <div class="col-md">
                <h1><strong>Questions ?</strong></h1>
                <p class="lead">
                    If you have questions about the Curriculum MAP, please contact <a href="mailto:laura.prada@ubc.ca" target="_blank">laura.prada@ubc.ca</a> at the <a href="https://provost.ok.ubc.ca/" target="_blank"><i class="bi bi-box-arrow-up-right"></i> Office of the Provost and Vice-President Academic, UBC Okanagan campus</a>.
                </p>

            </div>
        </div>
    </div>
@endsection
