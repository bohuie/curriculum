@extends('layouts.app')

@section('content')

<link href=" {{ asset('css/accordions.css') }}" rel="stylesheet" type="text/css" >
<!--Link for FontAwesome Font for the arrows for the accordions.-->
<link href="https://use.fontawesome.com/releases/v5.8.2/css/all.css" integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" crossorigin="anonymous" rel="stylesheet" type="text/css" >


<div class="container">
    <div class="row">
        <div style="width: 100%;border-bottom: 1px solid #DCDCDC">
            <h1 style="text-align:center;">About</h1>
        </div>

        <div class="accordions" style="width:100%">
            <div class="accordion" id="accordionGroup">
                <div class="card">
                    <div class="card-header" id="headingOne">
                        <input class="accordion-input" type="checkbox" id="title1" data-toggle="collapse" data-target="#collapseOne"/>
                            <label for="title1">
                            <h3 class="accordion-title">What is Curriculum Mapping?</h3>
                            </label>									
                    </div>
                    <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordionGroup">
                        <div class="card-body">
                            <p class="lead">Curriculum mapping is an instrument to evaluate the alignment of learning objectives to academic strategies at educational institutions. The mapping process is an effective way to - </p>
                            <ul class="accordion-sublist">
                                <li class="lead">“make learning and teaching more meaningful to students and teachers” (Lam and Tsui, 2016); </li>
                                <li class="lead">“develop, review, improve and perfect an integrated curriculum, including curriculum alignment” (Khoerunnisa et al, 2018); and </li>
                                <li class="lead">“understand curriculum structures and relationships, gain insight in how students experience their discipline, and increase awareness of curricular content” (Archambault and Masunaga, 2015). </li>
                            </ul>
                            <br>
                            <p class="lead"><a href="https://strategicplan.ubc.ca/transformative-learning/" target="_blank" rel="noopener noreferrer">UBC’s Strategic Plan’s Strategy on Transformative Learning</a> focuses on an important cornerstone of the curriculum mapping process. The strategy notes that: </p>
                            <p class="lead"><i>Our work at UBC is focused on enhanced support for program redesign around competencies; the development of problem-solving experiences; technology-enabled learning; and continued growth in work-integrated education. Sustained progress requires leadership across the institution to model, inspire and celebrate excellence in teaching and mentorship. </i></p>
                            <p class="lead">To create an immersive learning experience that fosters competencies identified by faculties and departments as critical to the discipline, a curriculum map can be an <strong>effective way to visualize the program structure, list of courses, teaching and learning activities, and assessment practices.</strong> This “integrative and sustainable” (Kalu & Dyjur, 2018) process is collaborative and <strong>geared towards student success.</strong></p>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header" id="headingTwo">
                    <input class="accordion-input" type="checkbox" id="title2" data-toggle="collapse" data-target="#collapseTwo"/>
                        <label for="title2">
                            <h3 class="accordion-title">Benefits of Curriculum Mapping</h3>
                        </label>
                    </div>
                    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionGroup">
                        <div class="card-body">
                        <ul class="accordion-sublist">
                            <li class="lead"><strong>Improve student learning:</strong> Curriculum mapping helps faculty use evidence-based information, see relationships between course and overall program goals, and learning outcomes; </li>
                            <li class="lead"><strong>Student success:</strong> Curriculum mapping gives students a better understanding of what is expected of them, and what they will accomplish from different courses and program components; </li>
                            <li class="lead"><strong>Quality assurance:</strong> Curriculum mapping allows for identification of gaps in course offerings as well as redundancies;</li>
                            <li class="lead"><strong>Faculty collaboration and collegiality:</strong> Curriculum mapping provides an opportunity for faculty to work together. This can be especially useful to help new faculty entering a department develop professional relationships and a sense of belonging.</li>
                        </ul>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header" id="headingThree">
                        <input class="accordion-input" type="checkbox" id="title3" data-toggle="collapse" data-target="#collapseThree"/>
                            <label for="title3">
                                <h3 class="accordion-title">How to get the best out of this tool?</h3>
                            </label>                
                    </div>
                    <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionGroup">
                        <div class="card-body">
                            <p class="lead">This customizable online tool is a vehicle to curriculum mapping and alignment. The information presented after completing the tool’s wizard should allow instructors and departments make informed decisions to enhance the course/program as well as the overall student experience and learning.</p>
                            <p class="lead">To get the best results from using this tool, it is suggested that: </p>
                            <h4>At the course level</h4>
                            <ul class="accordion-sublist">
                                <li class="lead"><strong>Before using the tool:</strong> Collect all relevant information to navigate the wizard: course learning outcomes (CLOs), assessment methods, and teaching & learning activities.
                                    <ul class="accordion-sublist">
                                        <li class="lead">Note: Identifying CLOs can be a difficult task for some. If you require assistance with articulating CLOs, contact the <a href="https://ctl.ok.ubc.ca/" target="_blank" rel="noopener noreferrer">Centre for Teaching and Learning </a> for support. </li>
                                    </ul>
                                </li>
                                <li class="lead">Once the tool draws its results, <strong>review, reflect,</strong> and take note of the course’s:
                                    <ul class="accordion-sublist">
                                        <li class="lead">Strengths, weaknesses, areas for improvement</li>
                                        <li class="lead"><strong>Share</strong> with other department colleagues to compare and contrast against other courses </li>
                                        <li class="lead">Consider comparing and contrasting with all other courses in the same year-level and/or program to identify students’ experiences:
                                            <ul class="accordion-sublist">
                                                <li class="lead">Is there a wide variety of assessment methods? </li>
                                                <li class="lead">Is there a wide variety of teaching and learning experiences? </li>
                                                <li class="lead">Are all learning outcomes clear and properly assessed? </li>
                                                <li class="lead">Are there gaps in content, skills, experiences that need to be addressed? </li>
                                            </ul>
                                        </li>
                                    </ul>
                                </li>
                                <li class="lead">A <strong>conversation at the department level</strong> with this information can yield excellent results for instructors and students! </li>
                            </ul>
                            <br>
                            <h4>At the program level</h4>
                            <ul class="accordion-sublist">
                                <li class="lead"><strong>Before using the tool:</strong> Collect all relevant information to navigate the wizard: program learning outcomes (PLOs), program required and non-required courses
                                    <ul class="accordion-sublist">
                                        <li class="lead">Each course can also be assigned to a collaborator to complete mapping at the course-level (not mandatory).</li>
                                        <li class="lead">If your program does not have PLOs, you may still use the tool with standard outcomes for an academic program.</li>
                                    </ul>
                                </li>
                                <li class="lead">Once the tool produces a report with results, <strong>review, reflect,</strong> and take note of the program’s:
                                    <ul class="accordion-sublist">
                                        <li class="lead">Strengths, weaknesses, areas for improvement.</li>
                                        <li class="lead">Share with other department colleagues to compare and contrast against other programs.</li>
                                        <li class="lead">Consider students’ learning and experiences throughout this program:
                                            <ul>
                                                <li class="lead">Is there a wide variety of assessment methods? </li>
                                                <li class="lead">Is there a wide variety of teaching and learning experiences? </li>
                                                <li class="lead">Are all learning outcomes clear and properly assessed? </li>
                                                <li class="lead">Are there gaps in content, skills, experiences that need to be addressed? </li>
                                                <li class="lead">Are there signature pedagogies that can be better integrated into the program? </li>
                                            </ul>
                                        </li>
                                    </ul>
                                </li>
                                <li class="lead">A <strong>conversation at the department level</strong> with this information can yield excellent results for instructors and students! </li>
                            </ul>
                            <br>
                            <h4>Alignment with Quality Assurance and Enhancement</h4>
                            <p class="lead">UBC Okanagan is committed to providing the highest quality of education to all students. This means ongoing work for continuous improvement and innovation of teaching and learning practices across disciplines.</p>
                            <p class="lead">This website aims to support this commitment by providing all instructors with a tool to ideate, create, and evaluate new or already existing courses and programs, using backward design. Engaging in this important exercise benefits students, instructors, and our overall communities. </p>
                            <p class="lead">To learn more about UBC Okanagan’s efforts towards quality assurance and enhancement <a href ="https://provost.ok.ubc.ca/initiatives/quality-assurance-and-enhancement/" title="Quality Assurance and Enhancement site"  target="_blank" rel="noopener noreferrer">click here.</a> </p>
                        </div>
                    </div>
                </div>
            
                <div class="card">
                    <div class="card-header" id="headingSix">
                    <input class="accordion-input" type="checkbox" id="title4" data-toggle="collapse" data-target="#collapseFour"/>
                        <label for="title4">
                            <h3 class="accordion-title">References and Literature</h3>                   
                        </label>
                    </div>
                    <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordionGroup">
                        <div class="card-body">
                            <ul class="accordion-sublist">
                                <li class="lead">Al-Eyd, G., et al. (2018). Curriculum mapping as a tool to facilitate curriculum development: a new School of Medicine experience, BMC Med. Educ., vol. 18, no. 1, p. 185 </li>
                                <li class="lead">Bick Har Lam & Kwok Tung Tsui (2016) Curriculum mapping as deliberation – examining the alignment of subject learning outcomes and course curricula, Studies in Higher Education, 41:8, 1371-1388, DOI: 10.1080/03075079.2014.968539 </li>
                                <li class="lead">Erickson, H. L. (2004). Foreword. In H. H. Jacobs (Ed.), Getting results with curriculum mapping (pp. i - ix). Alexandria, VA, USA: Association for Supervision & Curriculum Development. </li>
                                <li class="lead">I Khoerunnisa et al 2018 IOP Conf. Ser.: Mater. Sci. Eng. 434 012303</li>
                                <li class="lead">Kalu, F., & Dyjur, P. (2018). Creating a culture of continuous assessment to improve student learning through curriculum review. New Directions for Teaching and Learning, 2018(155), 47-54. https://doi.org/10.1002/tl.20302</li>
                                <li class="lead">Sumsion, Jennifer & Goodfellow, Joy. (2004).  Identifying generic skills through curriculum mapping: a critical evaluation, Higher Education Research &amp; Development, 23:3, 329-346, DOI: 10.1080/0729436042000235436 </li>
                                <li class="lead">Susan Gardner Archambault & Jennifer Masunaga (2015) Curriculum Mapping as a Strategic Planning Tool, Journal of Library Administration, 55:6, 503-519, DOI: 10.1080/01930826.2015.1054770 </li>
                                <li class="lead">Uchiyama, K.P., & Radin, Jean L. (2008). Curriculum mapping in higher education: A vehicle for collaboration, Innov High Educ (2009), 33:271-280, DOI: 10.1007/s10755-008-9078-8</li>
                                <li class="lead">Wijngaards-de Meij, Leoniek & Merx, Sigrid. (2018) Improving curriculum alignment and achieving learning goals by making the curriculum visible, International Journal for Academic Development, 23:3, 219-231, DOI: 10.1080/1360144X.2018.1462187 </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header" id="headingFive">
                        <input class="accordion-input" type="checkbox" id="title5" data-toggle="collapse" data-target="#collapseFive"/>
                                <label for="title5">
                                    <h3 class="accordion-title">Contributors</h3>                  
                                </label>
                    </div>
                    <div id="collapseFive" class="collapse" aria-labelledby="headingFive" data-parent="#accordionGroup">
                        <div class="card-body">
                            <p class="lead">Funded by the UBC Okanagan Office of the Provost and Vice President Academic and supported by:</p>
                            <ul class="accordion-sublist" style="list-style: none;">
                                <li class="lead"> 
                                <strong>UBC Okanagan Faculty and Staff:</strong>
                                    <ul class="accordion-sublist">
                                    <li class="lead">Dr. Anita Chaudhuri, English and Cultural Studies</li>
                                    <li class="lead">Dr. Bowen Hui, Computer Science</li>
                                    <li class="lead">Janine Hirtz, Centre for Teaching and Learning</li>
                                    <li class="lead">Laura Prada, Office of the Provost and Vice President Academic</li>
                                    </ul>
                                </li>
                                <li class="lead">
                                <strong>UBC Okanagan and Vancouver Undergraduate Students:</strong>
                                <ul class="accordion-sublist">
                                <li class="lead">Abdelmuizz Yusuf (Muizz)</li>
                                <li class="lead">Damyn Filipuzzi</li>
                                <li class="lead">Daulton Baird</li>
                                <li class="lead">Jia Fei LuoZheng (Jeffrey)</li>
                                <li class="lead">Kieran Adams</li>
                                
                                </ul>
                                </li>
                                
                            </ul>
                            <br>
                            <p class="lead">Inspired by and based on UCalgary’s tool <a href="https://taylorinstitute.ucalgary.ca/curriculum-links" target="_blank" rel="noopener noreferrer">Curriculum Links</a>.</p>
                            <p class="lead"> If you have questions about the Curriculum MAP, please contact <a href="mailto:laura.prada@ubc.ca">laura.prada@ubc.ca</a> at the <a href="https://provost.ok.ubc.ca/" target="_blank" rel="noopener noreferrer">Office of the Provost and Vice-President Academic, UBC Okanagan campus</a>. </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- End here -->
@endsection
