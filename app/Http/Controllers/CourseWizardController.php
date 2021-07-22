<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Program;
use App\Models\ProgramUser;
use App\Models\CourseUser;
use App\Models\User;
use App\Models\ProgramLearningOutcome;
use App\Models\Course;
use App\Models\LearningOutcome;
use App\Models\AssessmentMethod;
use App\Models\Custom_assessment_methods;
use App\Models\Custom_learning_activities;
use App\Models\OutcomeAssessment;
use App\Models\LearningActivity;
use App\Models\OutcomeActivity;
use App\Models\MappingScale;
use App\Models\PLOCategory;
use Illuminate\Support\Facades\Auth;
use App\Models\Optional_priorities;
use App\Models\Standard;
use App\Models\StandardScale;
use App\Models\StandardsOutcomeMap;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CourseWizardController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
        $this->middleware('hasAccess');
    }

    // public function step0($course_id)
    // {

    //     $course =  Course::where('course_id', $course_id)->first();
    //     $user = User::where('id',Auth::id())->first();
    //     $courseUsers = Course::join('course_users','courses.course_id',"=","course_users.course_id")
    //                             ->join('users','course_users.user_id',"=","users.id")
    //                             ->select('users.email')
    //                             ->where('courses.course_id','=',$course_id)->get();


    //     return view('courses.wizard.step0')->with('course', $course)->with('courseUsers', $courseUsers)->with('user', $user);

    // }


    public function step1(Request $request, $course_id)
    {
        //for header
        $user = User::where('id',Auth::id())->first();
        $courseUsers = Course::join('course_users','courses.course_id',"=","course_users.course_id")
                                ->join('users','course_users.user_id',"=","users.id")
                                ->select('users.email')
                                ->where('courses.course_id','=',$course_id)->get();
        
        //for progress bar
        $lo_count = LearningOutcome::where('course_id', $course_id)->count();
        $am_count = AssessmentMethod::where('course_id', $course_id)->count();
        $la_count = LearningActivity::where('course_id', $course_id)->count();
        $oAct = LearningActivity::join('outcome_activities','learning_activities.l_activity_id','=','outcome_activities.l_activity_id')
                                ->join('learning_outcomes', 'outcome_activities.l_outcome_id', '=', 'learning_outcomes.l_outcome_id' )
                                ->select('outcome_activities.l_activity_id','learning_activities.l_activity','outcome_activities.l_outcome_id', 'learning_outcomes.l_outcome')
                                ->where('learning_activities.course_id','=',$course_id)->count();
        $oAss = AssessmentMethod::join('outcome_assessments','assessment_methods.a_method_id','=','outcome_assessments.a_method_id')
                                ->join('learning_outcomes', 'outcome_assessments.l_outcome_id', '=', 'learning_outcomes.l_outcome_id' )
                                ->select('assessment_methods.a_method_id','assessment_methods.a_method','outcome_assessments.l_outcome_id', 'learning_outcomes.l_outcome')
                                ->where('assessment_methods.course_id','=',$course_id)->count();
        $outcomeMapsCount = ProgramLearningOutcome::join('outcome_maps','program_learning_outcomes.pl_outcome_id','=','outcome_maps.pl_outcome_id')
                                ->join('learning_outcomes', 'outcome_maps.l_outcome_id', '=', 'learning_outcomes.l_outcome_id' )
                                ->select('outcome_maps.map_scale_value','outcome_maps.pl_outcome_id','program_learning_outcomes.pl_outcome','outcome_maps.l_outcome_id', 'learning_outcomes.l_outcome')
                                ->where('learning_outcomes.course_id','=',$course_id)->count();

        //
        $l_outcomes = LearningOutcome::where('course_id', $course_id)->get();
        $course =  Course::where('course_id', $course_id)->first();

        return view('courses.wizard.step1')->with('l_outcomes', $l_outcomes)->with('course', $course)->with('courseUsers', $courseUsers)->with('user', $user)
                                        ->with('lo_count',$lo_count)->with('am_count', $am_count)->with('la_count', $la_count)->with('oAct', $oAct)->with('oAss', $oAss)->with('outcomeMapsCount', $outcomeMapsCount);

    }

    public function step2($course_id, Request $request)
    {
        //for header
        $user = User::where('id',Auth::id())->first();
        $courseUsers = Course::join('course_users','courses.course_id',"=","course_users.course_id")
                                ->join('users','course_users.user_id',"=","users.id")
                                ->select('users.email')
                                ->where('courses.course_id','=',$course_id)->get();

        //for progress bar
        $lo_count = LearningOutcome::where('course_id', $course_id)->count();
        $am_count = AssessmentMethod::where('course_id', $course_id)->count();
        $la_count = LearningActivity::where('course_id', $course_id)->count();
        $oAct = LearningActivity::join('outcome_activities','learning_activities.l_activity_id','=','outcome_activities.l_activity_id')
                                ->join('learning_outcomes', 'outcome_activities.l_outcome_id', '=', 'learning_outcomes.l_outcome_id' )
                                ->select('outcome_activities.l_activity_id','learning_activities.l_activity','outcome_activities.l_outcome_id', 'learning_outcomes.l_outcome')
                                ->where('learning_activities.course_id','=',$course_id)->count();
        $oAss = AssessmentMethod::join('outcome_assessments','assessment_methods.a_method_id','=','outcome_assessments.a_method_id')
                                ->join('learning_outcomes', 'outcome_assessments.l_outcome_id', '=', 'learning_outcomes.l_outcome_id' )
                                ->select('assessment_methods.a_method_id','assessment_methods.a_method','outcome_assessments.l_outcome_id', 'learning_outcomes.l_outcome')
                                ->where('assessment_methods.course_id','=',$course_id)->count();
        $outcomeMapsCount = ProgramLearningOutcome::join('outcome_maps','program_learning_outcomes.pl_outcome_id','=','outcome_maps.pl_outcome_id')
                                ->join('learning_outcomes', 'outcome_maps.l_outcome_id', '=', 'learning_outcomes.l_outcome_id' )
                                ->select('outcome_maps.map_scale_value','outcome_maps.pl_outcome_id','program_learning_outcomes.pl_outcome','outcome_maps.l_outcome_id', 'learning_outcomes.l_outcome')
                                ->where('learning_outcomes.course_id','=',$course_id)->count();

        //
        $a_methods = AssessmentMethod::where('course_id', $course_id)->get();
        $custom_methods = Custom_assessment_methods::select('custom_methods')->get();
        $totalWeight = AssessmentMethod::where('course_id', $course_id)->sum('weight');
        $course =  Course::where('course_id', $course_id)->first();

        return view('courses.wizard.step2')->with('a_methods', $a_methods)->with('course', $course)->with("totalWeight", $totalWeight)->with('courseUsers', $courseUsers)->with('user', $user)->with('custom_methods',$custom_methods)
                                        ->with('lo_count',$lo_count)->with('am_count', $am_count)->with('la_count', $la_count)->with('oAct', $oAct)->with('oAss', $oAss)->with('outcomeMapsCount', $outcomeMapsCount);


    }

    public function step3($course_id, Request $request)
    {
        //for header
        $user = User::where('id',Auth::id())->first();
        $courseUsers = Course::join('course_users','courses.course_id',"=","course_users.course_id")
                                ->join('users','course_users.user_id',"=","users.id")
                                ->select('users.email')
                                ->where('courses.course_id','=',$course_id)->get();

        //for progress bar
        $lo_count = LearningOutcome::where('course_id', $course_id)->count();
        $am_count = AssessmentMethod::where('course_id', $course_id)->count();
        $la_count = LearningActivity::where('course_id', $course_id)->count();
        $oAct = LearningActivity::join('outcome_activities','learning_activities.l_activity_id','=','outcome_activities.l_activity_id')
                                ->join('learning_outcomes', 'outcome_activities.l_outcome_id', '=', 'learning_outcomes.l_outcome_id' )
                                ->select('outcome_activities.l_activity_id','learning_activities.l_activity','outcome_activities.l_outcome_id', 'learning_outcomes.l_outcome')
                                ->where('learning_activities.course_id','=',$course_id)->count();
        $oAss = AssessmentMethod::join('outcome_assessments','assessment_methods.a_method_id','=','outcome_assessments.a_method_id')
                                ->join('learning_outcomes', 'outcome_assessments.l_outcome_id', '=', 'learning_outcomes.l_outcome_id' )
                                ->select('assessment_methods.a_method_id','assessment_methods.a_method','outcome_assessments.l_outcome_id', 'learning_outcomes.l_outcome')
                                ->where('assessment_methods.course_id','=',$course_id)->count();
        $outcomeMapsCount = ProgramLearningOutcome::join('outcome_maps','program_learning_outcomes.pl_outcome_id','=','outcome_maps.pl_outcome_id')
                                ->join('learning_outcomes', 'outcome_maps.l_outcome_id', '=', 'learning_outcomes.l_outcome_id' )
                                ->select('outcome_maps.map_scale_value','outcome_maps.pl_outcome_id','program_learning_outcomes.pl_outcome','outcome_maps.l_outcome_id', 'learning_outcomes.l_outcome')
                                ->where('learning_outcomes.course_id','=',$course_id)->count();
        //

        $l_activities = LearningActivity::where('course_id', $course_id)->get();
        $custom_activities = Custom_learning_activities::select('custom_activities')->get();
        $course =  Course::where('course_id', $course_id)->first();

        return view('courses.wizard.step3')->with('l_activities', $l_activities)->with('course', $course)->with('courseUsers', $courseUsers)->with('user', $user)->with('custom_activities',$custom_activities)
                                        ->with('lo_count',$lo_count)->with('am_count', $am_count)->with('la_count', $la_count)->with('oAct', $oAct)->with('oAss', $oAss)->with('outcomeMapsCount', $outcomeMapsCount);

    }

    public function step4($course_id, Request $request)
    {
        //for header
        $user = User::where('id',Auth::id())->first();
        $courseUsers = Course::join('course_users','courses.course_id',"=","course_users.course_id")
                                ->join('users','course_users.user_id',"=","users.id")
                                ->select('users.email')
                                ->where('courses.course_id','=',$course_id)->get();
        //for progress bar
        $lo_count = LearningOutcome::where('course_id', $course_id)->count();
        $am_count = AssessmentMethod::where('course_id', $course_id)->count();
        $la_count = LearningActivity::where('course_id', $course_id)->count();
        $oAct = LearningActivity::join('outcome_activities','learning_activities.l_activity_id','=','outcome_activities.l_activity_id')
                                ->join('learning_outcomes', 'outcome_activities.l_outcome_id', '=', 'learning_outcomes.l_outcome_id' )
                                ->select('outcome_activities.l_activity_id','learning_activities.l_activity','outcome_activities.l_outcome_id', 'learning_outcomes.l_outcome')
                                ->where('learning_activities.course_id','=',$course_id)->count();
        $oAss = AssessmentMethod::join('outcome_assessments','assessment_methods.a_method_id','=','outcome_assessments.a_method_id')
                                ->join('learning_outcomes', 'outcome_assessments.l_outcome_id', '=', 'learning_outcomes.l_outcome_id' )
                                ->select('assessment_methods.a_method_id','assessment_methods.a_method','outcome_assessments.l_outcome_id', 'learning_outcomes.l_outcome')
                                ->where('assessment_methods.course_id','=',$course_id)->count();
        $outcomeMapsCount = ProgramLearningOutcome::join('outcome_maps','program_learning_outcomes.pl_outcome_id','=','outcome_maps.pl_outcome_id')
                                ->join('learning_outcomes', 'outcome_maps.l_outcome_id', '=', 'learning_outcomes.l_outcome_id' )
                                ->select('outcome_maps.map_scale_value','outcome_maps.pl_outcome_id','program_learning_outcomes.pl_outcome','outcome_maps.l_outcome_id', 'learning_outcomes.l_outcome')
                                ->where('learning_outcomes.course_id','=',$course_id)->count();

        //
        $l_outcomes = LearningOutcome::where('course_id', $course_id)->get();
        $course =  Course::where('course_id', $course_id)->first();
        $l_activities = LearningActivity::where('course_id', $course_id)->get();
        $a_methods = AssessmentMethod::where('course_id', $course_id)->get();

        return view('courses.wizard.step4')->with('l_outcomes', $l_outcomes)->with('course', $course)->with('l_activities', $l_activities)->with('a_methods', $a_methods)->with('courseUsers', $courseUsers)->with('user', $user)
                        ->with('lo_count',$lo_count)->with('am_count', $am_count)->with('la_count', $la_count)->with('oAct', $oAct)->with('oAss', $oAss)->with('outcomeMapsCount', $outcomeMapsCount);
    }

    // Program Outcome Mapping
    public function step5($course_id, Request $request)
    {
        // for header
        $user = User::where('id',Auth::id())->first();
        $course = Course::find($course_id);
        $courseUsers = $course->users()->select('users.email')->get();
        
        //for progress bar
        $lo_count = LearningOutcome::where('course_id', $course_id)->count();
        $am_count = AssessmentMethod::where('course_id', $course_id)->count();
        $la_count = LearningActivity::where('course_id', $course_id)->count();
        $oAct = LearningActivity::join('outcome_activities','learning_activities.l_activity_id','=','outcome_activities.l_activity_id')
                                ->join('learning_outcomes', 'outcome_activities.l_outcome_id', '=', 'learning_outcomes.l_outcome_id' )
                                ->select('outcome_activities.l_activity_id','learning_activities.l_activity','outcome_activities.l_outcome_id', 'learning_outcomes.l_outcome')
                                ->where('learning_activities.course_id','=',$course_id)->count();
        $oAss = AssessmentMethod::join('outcome_assessments','assessment_methods.a_method_id','=','outcome_assessments.a_method_id')
                                ->join('learning_outcomes', 'outcome_assessments.l_outcome_id', '=', 'learning_outcomes.l_outcome_id' )
                                ->select('assessment_methods.a_method_id','assessment_methods.a_method','outcome_assessments.l_outcome_id', 'learning_outcomes.l_outcome')
                                ->where('assessment_methods.course_id','=',$course_id)->count();
        $outcomeMapsCount = ProgramLearningOutcome::join('outcome_maps','program_learning_outcomes.pl_outcome_id','=','outcome_maps.pl_outcome_id')
                                ->join('learning_outcomes', 'outcome_maps.l_outcome_id', '=', 'learning_outcomes.l_outcome_id' )
                                ->select('outcome_maps.map_scale_value','outcome_maps.pl_outcome_id','program_learning_outcomes.pl_outcome','outcome_maps.l_outcome_id', 'learning_outcomes.l_outcome')
                                ->where('learning_outcomes.course_id','=',$course_id)->count();


        // get all the programs this course belongs to
        $coursePrograms = $course->programs;
        // get the mapping scale for each program
        $programsMappingScales = array();
        foreach ($coursePrograms as $courseProgram) {
            $programsMappingScales[$courseProgram->program_id] = $courseProgram->mappingScaleLevels;
        }
        // get the PLOs for each program
        $programsLearningOutcomes = array();
        foreach ($coursePrograms as $courseProgram) {
            $programsLearningOutcomes[$courseProgram->program_id] = $courseProgram->programLearningOutcomes;
        }

        $l_outcomes = LearningOutcome::where('course_id', $course_id)->get();
        $pl_outcomes = ProgramLearningOutcome::where('program_id', $course->program_id)->get();
        $mappingScales = MappingScale::join('mapping_scale_programs', 'mapping_scales.map_scale_id', "=", 'mapping_scale_programs.map_scale_id')
                            ->where('mapping_scale_programs.program_id', $course->program_id)->get();


        return view('courses.wizard.step5')->with('l_outcomes', $l_outcomes)->with('course', $course)->with('pl_outcomes',$pl_outcomes)->with('mappingScales', $mappingScales)->with('courseUsers', $courseUsers)->with('user', $user)
                                        ->with('lo_count',$lo_count)->with('am_count', $am_count)->with('la_count', $la_count)->with('oAct', $oAct)->with('oAss', $oAss)->with('outcomeMapsCount', $outcomeMapsCount)
                                        ->with('coursePrograms', $coursePrograms)->with('programsMappingScales', $programsMappingScales)->with('programsLearningOutcomes', $programsLearningOutcomes);
    }

    public function step6($course_id, Request $request)
    {
        // for header
        $user = User::where('id',Auth::id())->first();
        $course = Course::find($course_id);
        $courseUsers = $course->users()->select('users.email')->get();
        
        //for progress bar
        $lo_count = LearningOutcome::where('course_id', $course_id)->count();
        $am_count = AssessmentMethod::where('course_id', $course_id)->count();
        $la_count = LearningActivity::where('course_id', $course_id)->count();
        $oAct = LearningActivity::join('outcome_activities','learning_activities.l_activity_id','=','outcome_activities.l_activity_id')
                                ->join('learning_outcomes', 'outcome_activities.l_outcome_id', '=', 'learning_outcomes.l_outcome_id' )
                                ->select('outcome_activities.l_activity_id','learning_activities.l_activity','outcome_activities.l_outcome_id', 'learning_outcomes.l_outcome')
                                ->where('learning_activities.course_id','=',$course_id)->count();
        $oAss = AssessmentMethod::join('outcome_assessments','assessment_methods.a_method_id','=','outcome_assessments.a_method_id')
                                ->join('learning_outcomes', 'outcome_assessments.l_outcome_id', '=', 'learning_outcomes.l_outcome_id' )
                                ->select('assessment_methods.a_method_id','assessment_methods.a_method','outcome_assessments.l_outcome_id', 'learning_outcomes.l_outcome')
                                ->where('assessment_methods.course_id','=',$course_id)->count();
        $outcomeMapsCount = ProgramLearningOutcome::join('outcome_maps','program_learning_outcomes.pl_outcome_id','=','outcome_maps.pl_outcome_id')
                                ->join('learning_outcomes', 'outcome_maps.l_outcome_id', '=', 'learning_outcomes.l_outcome_id' )
                                ->select('outcome_maps.map_scale_value','outcome_maps.pl_outcome_id','program_learning_outcomes.pl_outcome','outcome_maps.l_outcome_id', 'learning_outcomes.l_outcome')
                                ->where('learning_outcomes.course_id','=',$course_id)->count();

        // get learning outcomes for a course
        $l_outcomes = LearningOutcome::where('course_id', $course_id)->get();
        // get Standards and strategic outcomes for a course
        $standard_outcomes = Standard::where('standard_category_id', $course->standard_category_id)->get();
        // get mapping scales associated with course
        $mappingScales = StandardScale::where('scale_category_id', $course->scale_category_id)->get();


        $ubc_mandate_letters = array("Incorporation of the Declaration on the Rights of Indigenous Peoples Act and Calls to Action of the Truth and Reconciliation Commission",
        "Align with CleanBC's plan to a protect our communities towards a more sustainable future","Advancing and supporting open learning resources","Offer programming aligned with high opportunity and priority occupations (such as trades, technology, early childhood educators and health)",
        "Embed more co-op and work-integrated learning opportunities","Respond to the reskilling needs of British Columbians to support employment and career transitions","Supporting students' awareness of career planning resources (such as the Labour Market Outlook)");

        $bc_labour_market = array("Active Listening","Speaking","Reading Comprehension","Critical Thinking","Social Perceptiveness","Judgement and Decision Making","Writing","Monitoring"
        ,"Complex Problem Solving","Coordination");

        $shaping_ubc = array("Great People: Attract, engage and retain a diverse global community of outstanding students, faculty and staff.","Inspiring Spaces: Create welcoming physical and virtual spaces to advance collaboration, innovation and community development.",
        "Thriving Communities: Support the ongoing development of sustainable, healthy and connected campuses and communities, consistent with the 20-Year Sustainability Strategy and the developing Wellbeing Strategy.","Inclusive Excellence: Cultivate a diverse community that creates and sustains equitable and inclusive campuses.",
        "Systems Renewal: Transform university-level systems and processes to facilitate collaboration, innovation and agility.","Collaborative Clusters: Enable interdisciplinary clusters of research excellence in pursuit of societal impact.",
        "Research Support: Strengthen shared infrastructure and resources to support research excellence.","Student Research: Broaden access to, and enhance, student research experiences.","Knowledge Exchange: Improve the ecosystem that supports the translation of research into action.",
        "Research Culture: Foster a strong and diverse research culture that embraces the highest standards of integrity, collegiality and service.","Education Renewal: Facilitate sustained program renewal and improvements in teaching effectiveness.",
        "Program Redesign: Reframe undergraduate academic program design in terms of learning outcomes and competencies.","Practical Learning: Expand experiential, work-integrated and extended learning opportunities for students, faculty, staff and alumni.",
        "Interdisciplinary Education: Facilitate the development of integrative, problem-focused learning.","Student Experience: Strengthen undergraduate and graduate student communities and experience.","Public Relevance: Deepen the relevance and public impact of UBC research and education.",
        "Indigenous Engagement: Support the objectives and actions of the renewed Indigenous Strategic Plan.","Alumni Engagement: Reach, inspire and engage alumni through lifelong enrichment, consistent with the alumniUBC strategic plan,","Global Networks: Build and sustain strategic global networks, notably around the Pacific Rim, that enhance impact.",
        "Co-ordinated Engagement: Co-create with communities the principles and effective practices of engagement, and establish supporting infrastructure.");

        $shaping_ubc_link = array("https://strategicplan.ubc.ca/strategy-1-great-people/","https://strategicplan.ubc.ca/strategy-2-inspiring-spaces/","https://strategicplan.ubc.ca/strategy-3-thriving-communities/",
        "https://strategicplan.ubc.ca/strategy-4-inclusive-excellence/","https://strategicplan.ubc.ca/strategy-5-systems-renewal/","https://strategicplan.ubc.ca/strategy-6-collaborative-clusters/","https://strategicplan.ubc.ca/strategy-7-research-support/",
        "https://strategicplan.ubc.ca/strategy-8-student-research/","https://strategicplan.ubc.ca/strategy-9-knowledge-exchange/","https://strategicplan.ubc.ca/strategy-10-research-culture/","https://strategicplan.ubc.ca/strategy-11-education-renewal/",
        "https://strategicplan.ubc.ca/strategy-12-program-redesign/","https://strategicplan.ubc.ca/strategy-13-practical-learning/","https://strategicplan.ubc.ca/strategy-14-interdisciplinary-education/","https://strategicplan.ubc.ca/strategy-15-student-experience/",
        "https://strategicplan.ubc.ca/strategy-16-public-relevance/","https://strategicplan.ubc.ca/strategy-17-indigenous-engagement/","https://strategicplan.ubc.ca/strategy-18-alumni-engagement/","https://strategicplan.ubc.ca/strategy-19-global-networks/","https://strategicplan.ubc.ca/strategy-20-co-ordinated-engagement/");

        $okanagan_2040_outlook  = array("Continuing education programs","Offer hybrid pedagogies","Professional programs in health and technology",
        "Increase graduate student training","Leverage new academic and/or research space", "Increased community engagement");

        $ubc_indigenous_plan = array("Integration of Indigenous histories, experiences, worldviews and knowledge systems", "Inclusion of substantive content that explores histories and identifies how Indigenous issues intersect with the field of study",
        "Inclusion of Indigenous people for the development and offering of the curriculum","Continue to partner with Indigenous communities locally and globally");

        $ubc_climate_priorities = array("Climate justice education","Climate research","Content on Indigenous rights, content, history, and culture","Environmental and sustainability education",
        "Content from Indigenous scholars and communities and/or equity-seeking and marginalized groups","Inclusion of de-colonial approaches to science through Indigenous and community traditional knowledge and 'authorship'","Knowledge, awareness and skills related to the relationship between climate change and food systems",
        "Climate-related mental health content","Applied learning opportunities grounded in the personal, local and regional community (e.g. flood and wildfire impacted communities in BC)");

        $optional_PLOs = Optional_priorities::where('course_id', $course_id)->get();

        $temp = array();
        foreach($optional_PLOs as $plo) {
            $temp[] = $plo->custom_PLO;
        }
        $optional_PLOs = $temp;

        return view('courses.wizard.step6')->with('l_outcomes', $l_outcomes)->with('course', $course)->with('mappingScales', $mappingScales)->with('courseUsers', $courseUsers)->with('user', $user)
                                        ->with('lo_count',$lo_count)->with('am_count', $am_count)->with('la_count', $la_count)->with('oAct', $oAct)->with('oAss', $oAss)->with('outcomeMapsCount', $outcomeMapsCount)
                                        ->with('bc_labour_market',$bc_labour_market)->with('shaping_ubc',$shaping_ubc)->with('ubc_mandate_letters',$ubc_mandate_letters)->with('okanagan_2040_outlook',$okanagan_2040_outlook)
                                        ->with('ubc_indigenous_plan',$ubc_indigenous_plan)->with('ubc_climate_priorities',$ubc_climate_priorities)->with('shaping_ubc_link',$shaping_ubc_link)->with('optional_PLOs',$optional_PLOs)
                                        ->with('standard_outcomes', $standard_outcomes);
    }
    
    public function step7($course_id, Request $request)
    {
        //for header
        $user = User::where('id',Auth::id())->first();
        $courseUsers = Course::join('course_users','courses.course_id',"=","course_users.course_id")
                                ->join('users','course_users.user_id',"=","users.id")
                                ->select('users.email')
                                ->where('courses.course_id','=',$course_id)->get();

        //for progress bar
        $lo_count = LearningOutcome::where('course_id', $course_id)->count();
        $am_count = AssessmentMethod::where('course_id', $course_id)->count();
        $la_count = LearningActivity::where('course_id', $course_id)->count();
        $oAct = LearningActivity::join('outcome_activities','learning_activities.l_activity_id','=','outcome_activities.l_activity_id')
                                ->join('learning_outcomes', 'outcome_activities.l_outcome_id', '=', 'learning_outcomes.l_outcome_id' )
                                ->select('outcome_activities.l_activity_id','learning_activities.l_activity','outcome_activities.l_outcome_id', 'learning_outcomes.l_outcome')
                                ->where('learning_activities.course_id','=',$course_id)->count();
        $oAss = AssessmentMethod::join('outcome_assessments','assessment_methods.a_method_id','=','outcome_assessments.a_method_id')
                                ->join('learning_outcomes', 'outcome_assessments.l_outcome_id', '=', 'learning_outcomes.l_outcome_id' )
                                ->select('assessment_methods.a_method_id','assessment_methods.a_method','outcome_assessments.l_outcome_id', 'learning_outcomes.l_outcome')
                                ->where('assessment_methods.course_id','=',$course_id)->count();
        $outcomeMapsCount = ProgramLearningOutcome::join('outcome_maps','program_learning_outcomes.pl_outcome_id','=','outcome_maps.pl_outcome_id')
                                ->join('learning_outcomes', 'outcome_maps.l_outcome_id', '=', 'learning_outcomes.l_outcome_id' )
                                ->select('outcome_maps.map_scale_value','outcome_maps.pl_outcome_id','program_learning_outcomes.pl_outcome','outcome_maps.l_outcome_id', 'learning_outcomes.l_outcome')
                                ->where('learning_outcomes.course_id','=',$course_id)->count();

        //
        // get all the programs this course belongs to
        $coursePrograms = Course::find($course_id)->programs;
        // get the mapping scale for each program
        $programsMappingScales = array();
        foreach ($coursePrograms as $courseProgram) {
            $programsMappingScales[$courseProgram->program_id] = $courseProgram->mappingScaleLevels;
        }
        // get the PLOs for each program
        $programsLearningOutcomes = array();
        foreach ($coursePrograms as $courseProgram) {
            $programsLearningOutcomes[$courseProgram->program_id] = $courseProgram->programLearningOutcomes;
        }

        // courseProgramsOutcomeMaps[$program_id][$plo][$clo] = map_scale_value
        $courseProgramsOutcomeMaps = array();
        foreach ($programsLearningOutcomes as $programId => $programLearningOutcomes) {
            foreach ($programLearningOutcomes as $programLearningOutcome) {
                $outcomeMaps = $programLearningOutcome->learningOutcomes->where('course_id', $course_id);
                foreach($outcomeMaps as $outcomeMap){
                    $courseProgramsOutcomeMaps[$programId][$programLearningOutcome->pl_outcome_id][$outcomeMap->l_outcome_id] = $outcomeMap->pivot->map_scale_value;
                } 
            }
        }

        $course =  Course::where('course_id', $course_id)->first();
        $program = Program::where('program_id', $course->program_id)->first();
        $a_methods = AssessmentMethod::where('course_id', $course_id)->get();
        $l_activities = LearningActivity::where('course_id', $course_id)->get();

        // get learning outcomes for a course
        $l_outcomes = LearningOutcome::where('course_id', $course_id)->get();
        // get Standards and strategic outcomes for a course
        $standard_outcomes = Standard::where('standard_category_id', $course->standard_category_id)->get();
        // get mapping scales associated with course
        $standardsMappingScales = StandardScale::where('scale_category_id', $course->scale_category_id)->get();
        // get standards outcome map
        $standardsOutcomeMap = Standard::join('standards_outcome_maps', 'standards.standard_id', '=', 'standards_outcome_maps.standard_id')
                                ->join('learning_outcomes', 'standards_outcome_maps.l_outcome_id', '=', 'learning_outcomes.l_outcome_id' )
                                ->select('standards_outcome_maps.map_scale_value','standards_outcome_maps.standard_id','standards.s_outcome','standards_outcome_maps.l_outcome_id', 'learning_outcomes.l_outcome')
                                ->where('learning_outcomes.course_id','=',$course_id)->get();
        // get standards outcome map
        $standardsOutcomeMap = DB::table('standards')
                                ->leftJoin('standards_outcome_maps', 'standards.standard_id', '=', 'standards_outcome_maps.standard_id')
                                ->leftJoin('learning_outcomes', 'standards_outcome_maps.l_outcome_id', '=', 'learning_outcomes.l_outcome_id')
                                ->select('standards_outcome_maps.map_scale_value','standards_outcome_maps.standard_id','standards.s_outcome','standards_outcome_maps.l_outcome_id', 'learning_outcomes.l_outcome')
                                ->where('learning_outcomes.course_id','=',$course_id)->get();

        $pl_outcomes = ProgramLearningOutcome::where('program_id', $course->program_id)->get();

        $mappingScales = MappingScale::join('mapping_scale_programs', 'mapping_scales.map_scale_id', "=", 'mapping_scale_programs.map_scale_id')
                            ->where('mapping_scale_programs.program_id', $course->program_id)->get();

        $ploCategories = PLOCategory::where('program_id', $course->program_id)->get();

        $outcomeActivities = LearningActivity::join('outcome_activities','learning_activities.l_activity_id','=','outcome_activities.l_activity_id')
                                ->join('learning_outcomes', 'outcome_activities.l_outcome_id', '=', 'learning_outcomes.l_outcome_id' )
                                ->select('outcome_activities.l_activity_id','learning_activities.l_activity','outcome_activities.l_outcome_id', 'learning_outcomes.l_outcome')
                                ->where('learning_activities.course_id','=',$course_id)->get();

        $outcomeAssessments = AssessmentMethod::join('outcome_assessments','assessment_methods.a_method_id','=','outcome_assessments.a_method_id')
                                ->join('learning_outcomes', 'outcome_assessments.l_outcome_id', '=', 'learning_outcomes.l_outcome_id' )
                                ->select('assessment_methods.a_method_id','assessment_methods.a_method','outcome_assessments.l_outcome_id', 'learning_outcomes.l_outcome')
                                ->where('assessment_methods.course_id','=',$course_id)->get();

        $outcomeMaps = ProgramLearningOutcome::join('outcome_maps','program_learning_outcomes.pl_outcome_id','=','outcome_maps.pl_outcome_id')
                                ->join('learning_outcomes', 'outcome_maps.l_outcome_id', '=', 'learning_outcomes.l_outcome_id' )
                                ->select('outcome_maps.map_scale_value','outcome_maps.pl_outcome_id','program_learning_outcomes.pl_outcome','outcome_maps.l_outcome_id', 'learning_outcomes.l_outcome')
                                ->where('learning_outcomes.course_id','=',$course_id)->get();
        $optional_PLOs = Optional_priorities::where('course_id', $course_id)->get();

        $assessmentMethodsTotal = 0;
        foreach ($a_methods as $a_method) {
            $assessmentMethodsTotal += $a_method->weight;
        }
        
        return view('courses.wizard.step7')->with('course', $course)
                                        ->with('program', $program)
                                        ->with('l_outcomes', $l_outcomes)
                                        ->with('pl_outcomes',$pl_outcomes)
                                        ->with('l_activities', $l_activities)
                                        ->with('a_methods', $a_methods)
                                        ->with('outcomeActivities', $outcomeActivities)
                                        ->with('outcomeAssessments', $outcomeAssessments)
                                        ->with('outcomeMaps', $outcomeMaps)
                                        ->with('mappingScales', $mappingScales)
                                        ->with('ploCategories', $ploCategories)
                                        ->with('courseUsers', $courseUsers)->with('user', $user)->with('lo_count',$lo_count)->with('am_count', $am_count)->with('la_count', $la_count)->with('oAct', $oAct)->with('oAss', $oAss)->with('outcomeMapsCount', $outcomeMapsCount)
                                        ->with('optional_PLOs',$optional_PLOs)
                                        ->with('coursePrograms', $coursePrograms)
                                        ->with('programsMappingScales', $programsMappingScales)
                                        ->with('programsLearningOutcomes', $programsLearningOutcomes)
                                        ->with('courseProgramsOutcomeMaps', $courseProgramsOutcomeMaps)
                                        ->with('assessmentMethodsTotal', $assessmentMethodsTotal)
                                        ->with('standard_outcomes', $standard_outcomes)
                                        ->with('standardsMappingScales', $standardsMappingScales)
                                        ->with('standardsOutcomeMap', $standardsOutcomeMap);
                                        
    }

}
