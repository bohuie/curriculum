@extends('layouts.app')

@section('content')
    <div class="row p-md-5 justify-content-center text-dark bg-secondary">
        <div class="col-md-12">
            <div class="container">
                <h1>About</h1>                                                  
                
            </div>
        </div>
    </div>
    <div class="row p-md-5 justify-content-center text-light bg-primary">
        <div class="col-md-12">
            <div class="container">
                <h1>Inspiration</h1>
                <br><br>
                <div class="row">
                    <div class="col-sm"> 
                        <p class="lead" style="display:inline-block; padding: 4% 0;">Our work at UBC is focused on enhanced support for program redesign around competencies; the development of problem-solving experiences; <i> technology-enabled learning; </i> continued growth in work-integrated education. Sustained progress requires leadership across the institution to model, inspire and celebrate excellence in teaching and mentorship.</p>
                    </div>
                    <div class="col-sm"> 
                        <img style= "width:auto; height:auto; display:block; margin-left:auto; margin-right:auto;" src=" {{ asset('img/tech-enabled-learning-2.png') }}"/>
                    </div>
                </div>
                <br><br>
                <div class="row">
                    <div class="col-sm"> 
                        <img style= "width:50%; height:auto; display:block; margin-left:auto; margin-right:auto;" src=" {{ asset('img/course-mapping-4.png') }}"/>
                    </div>
                    <div class="col-sm">
                    <p class="lead" style="display:inline-block; padding: 2% 0;">To create an immersive learning experience that fosters competencies identified by faculties and departments as critical to the discipline, a curriculum map can be an <i>effective way to visualize the program structure, list of courses, teaching and learning activities, and assessment practices.</i> This “integrative and sustainable” (Kalu & Dyjur, 2018) process is collaborative and <i>geared towards student success.</i></p>

                    </div>
                    
                </div>
            </div>
        </div>
    </div>
    <div class="row p-md-5 justify-content-center text-dark bg-secondary">
        <div class="col-md-12">
            <div class="container">
                <div class="row">
                    <div class="col-sm">
                        <div style="float: left;">
                            <h1> Our Solution </h1>
                            <p class="lead">This customizable online tool is a vehicle to curriculum mapping and alignment. The information presented after completing the tool’s wizard should allow instructors and departments make informed decisions to enhance the course/program as well as the overall student experience and learning.</p>
                        </div>
                    </div>
                    <div class="col-sm">
                        <div style="float: right;">
                            <img style="width:auto; height:auto; display:block; margin-left:auto; margin-right:auto;" src=" {{ asset('img/checklist.png') }}"/>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
    <div class="row p-md-5 justify-content-center text-light bg-primary">
        <div class="col-md-12">
            <div class="container">
                <h1> Our Goals </h1>
                <p class="lead">UBC Okanagan is committed to providing the highest quality of education to all students. This means ongoing work for continuous improvement and innovation of teaching and learning practices across disciplines.</p>
                <p class="lead">This website aims to support this commitment by providing all instructors with a tool to ideate, create, and evaluate new or already existing courses and programs, using backward design. Engaging in this important exercise benefits students, instructors, and our overall communities. </p>
                <a href="https://provost.ok.ubc.ca/initiatives/quality-assurance-and-enhancement/" title="Quality Assurance and Enhancement site"  target="_blank" style="color: white;"><i class="bi bi-box-arrow-up-right"></i> Learn more about UBC Okanagan’s efforts towards quality assurance and enhancement</a>
            </div>
        </div>
    </div>
    <div class="row p-md-5 justify-content-center text-dark bg-secondary">
        <div class="col-md-12">
            <div class="container">
                <h1>Meet Our Team</h1>
                <br><br>
                <div class="row mb-3">
                    <div class="col-sm"> 
                        <img class="team-img" src=" {{ asset('img/team/Anita-200x200.png') }}"/>
                        <figcaption style="text-align: center;">
                            <p class="lead" style="margin-bottom:0px;"><strong>Dr. Anita Chaudhuri</strong></p>
                            <p class="lead" style="margin-bottom:0px;">English and Cultural Studies</p>
                            <p class="lead"><i>Faculty Consultant</i></p>
                        </figcaption>

                    </div>
                    <div class="col-sm"> 
                        <img class="team-img" src=" {{ asset('img/team/Bowen-200x200.png') }}"/>
                        <figcaption style="text-align: center;">
                            <p class="lead" style="margin-bottom:0px;"><strong>Dr. Bowen Hui</strong></p>
                            <p class="lead" style="margin-bottom:0px;">Computer Science</p>
                            <p class="lead"><i>Tech Consultant</i></p>
                        </figcaption>
                    </div>
                    <div class="col-sm"> 
                        <img class="team-img" src=" {{ asset('img/team/Janine-200x200.png') }}"/>
                        <figcaption style="text-align: center;">
                            <p class="lead" style="margin-bottom:0px;"><strong>Janine Hirtz</strong></p>
                            <p class="lead" style="margin-bottom:0px;">Centre of Teaching and Learning</p>
                            <p class="lead"><i>Curriculum Consultant</i></p>
                        </figcaption>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm"> 
                        <img class="team-img" src=" {{ asset('img/team/Laura-200x200.png') }}"/>
                        <figcaption style="text-align:center;">
                            <p class="lead" style="margin-bottom:0px;"><strong>Laura Prada</strong></p>
                            <p class="lead" style="margin-bottom:0px;">Office of the Provost and VPA</p>
                            <p class="lead"><i>Project Manager</i></p>
                        </figcaption>

                    </div>
                    <div class="col-sm"> 
                        <img class="team-img" src=" {{ asset('img/team/Abdelmuizz-200x200.png') }}"/>
                        <figcaption style="text-align:center;">
                            <p class="lead" style="margin-bottom:0px;"><strong>Abdelmuizz Yusuf (Muizz)</strong></p>
                            <p class="lead"><i>Undergraduate Developer</i></p>
                        </figcaption>
                    </div>
                    <div class="col-sm"> 
                        <img class="team-img" src=" {{ asset('img/team/Jeffrey-200x200.png') }}"/>
                        <figcaption style="text-align:center;">
                            <p class="lead" style="margin-bottom:0px;"><strong>Jia Fei LuoZheng (Jeffrey)</strong></p>
                            <p class="lead"><i>Undergraduate Developer</i></p>
                        </figcaption>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm"> 
                        <img class="team-img" src=" {{ asset('img/team/Damyn-200x200.png') }}"/>
                        <figcaption style="text-align:center;">
                            <p class="lead" style="margin-bottom:0px;"><strong>Damyn Filipuzzi</strong></p>
                            <p class="lead"><i>Undergraduate Developer</i></p>
                        </figcaption>
                    </div>
                    <div class="col-sm"> 
                        <img class="team-img" src=" {{ asset('img/team/Daulton-200x200.png') }}"/>
                        <figcaption style="text-align: center;">
                            <p class="lead" style="margin-bottom:0px;"><strong>Daulton Baird</strong></p>
                            <p class="lead"><i>Tech Lead/Undergraduate Developer</i></p>
                        </figcaption>
                    </div>
                    <div class="col-sm"> 
                        <img class="team-img" src=" {{ asset('img/team/Kieran-200x200.png') }}"/>
                        <figcaption style="text-align: center;">
                            <p class="lead" style="margin-bottom:0px;"><strong>Kieran Adams</strong></p>
                            <p class="lead"><i>Undergraduate Developer</i></p>
                        </figcaption>
                    </div>
                </div>
                <br>
                <div class="row">
                <p class="lead">Funded by the UBC Okanagan Office of the Provost and Vice President Academic.</p>
                <p class="lead">Inspired by and based on UCalgary’s tool <a href="https://taylorinstitute.ucalgary.ca/curriculum-links" target="_blank"><i class="bi bi-box-arrow-up-right"></i> Curriculum Links</a>.</p>
                </div>
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
</div>
<!-- End here -->
@endsection
