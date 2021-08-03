<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Program;
use App\Models\ProgramUser;
use App\Models\CourseUser;
use App\Models\User;
use App\Models\PLOCategory;
use App\Models\ProgramLearningOutcome;
use App\Models\Course;
use App\Models\CourseProgram;
use App\Models\MappingScale;
use App\Models\LearningOutcome;
use App\Models\MappingScaleCategory;
use App\Models\MappingScaleProgram;
use App\Models\OutcomeMap;
use Doctrine\DBAL\Schema\Index;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

use function PHPUnit\Framework\isNull;

class ProgramWizardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
        $this->middleware('hasAccess');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function step0($program_id)
    // {
    //     //header
    //     $faculties = array("Faculty of Arts and Social Sciences", "Faculty of Creative and Critical Studies", "Okangan School of Education", "School of Engineering", "School of Health and Exercise Sciences", "Faculty of Management", "Faculty of Science", "Faculty of Medicine", "College of Graduate Studies", "School of Nursing", "School of Social Work", "Other");
    //     $departments = array("Community, Culture and Global Studies", "Economics, Philosophy and Political Science", "History and Sociology", "Psychology", "Creative Studies", "Languages and World Literature", "English and Cultural Studies", "Biology", "Chemistry", "Computer Science, Mathematics, Physics and Statistics", "Earth, Environmental and Geographic Sciences", "Other" );
    //     $levels = array("Undergraduate", "Graduate", "Other");
    //     $program = Program::where('program_id', $program_id)->first();
    //     $user = User::where('id',Auth::id())->first();
    //     $programUsers = ProgramUser::join('users','program_users.user_id',"=","users.id")
    //                             ->select('users.email','program_users.user_id','program_users.program_id')
    //                             ->where('program_users.program_id','=',$program_id)->get();

    //     return view('programs.wizard.step1')->with('program', $program)->with("faculties", $faculties)->with("departments", $departments)->with("levels",$levels)->with('user', $user)->with('programUsers',$programUsers);
    // }

    public function step1($program_id, Request $request)
    {
        $isEditor = false;
        if ($request->isEditor) {
            $isEditor = true;
        }
        $isViewer = false;
        if ($request->isViewer) {
            return redirect()->route('programWizard.step4', $program_id);
        }

        //header
        $faculties = array("Faculty of Arts and Social Sciences", "Faculty of Creative and Critical Studies", "Okanagan School of Education", "School of Engineering", "School of Health and Exercise Sciences", "Faculty of Management", "Faculty of Science", "Faculty of Medicine", "College of Graduate Studies", "School of Nursing", "School of Social Work", "Other");
        $departments = array("Community, Culture and Global Studies", "Economics, Philosophy and Political Science", "History and Sociology", "Psychology", "Creative Studies", "Languages and World Literature", "English and Cultural Studies", "Biology", "Chemistry", "Computer Science, Mathematics, Physics and Statistics", "Earth, Environmental and Geographic Sciences", "Other" );
        $levels = array("Undergraduate", "Graduate", "Other");
        $user = User::where('id',Auth::id())->first();
        $programUsers = ProgramUser::join('users','program_users.user_id',"=","users.id")
                                ->select('users.email','program_users.user_id','program_users.program_id')
                                ->where('program_users.program_id','=',$program_id)->get();

        //
        //$plos = ProgramLearningOutcome::join('p_l_o_categories', 'program_learning_outcomes.plo_category_id', '=', 'p_l_o_categories.plo_category_id')->where('program_learning_outcomes.program_id', $program_id)->get();
        $plos = DB::table('program_learning_outcomes')->leftJoin('p_l_o_categories', 'program_learning_outcomes.plo_category_id', '=', 'p_l_o_categories.plo_category_id')->where('program_learning_outcomes.program_id', $program_id)->get();

        $program = Program::where('program_id', $program_id)->first();
        $ploCategories = PLOCategory::where('program_id', $program_id)->get();

        $ploProgramCategories = PLOCategory::where('p_l_o_categories.program_id', $program_id)->join('program_learning_outcomes', 'p_l_o_categories.plo_category_id', '=', 'program_learning_outcomes.plo_category_id')->get();

        //progress bar
        $ploCount = count($plos);
        $msCount = MappingScale::join('mapping_scale_programs', 'mapping_scales.map_scale_id', "=", 'mapping_scale_programs.map_scale_id')
                                    ->where('mapping_scale_programs.program_id', $program_id)->count();
        $courseCount = CourseProgram::where('program_id', $program_id)->count();

        // returns true if there exists a plo without a category
        $hasUncategorized = false;
        foreach ($plos as $plo) {
            if ($plo->plo_category == NULL) {
                $hasUncategorized = true;
            }
        }
        
        //dd($ploCategories);
        // get UnCategorized PLO's
        $unCategorizedPLOS = DB::table('program_learning_outcomes')->leftJoin('p_l_o_categories', 'program_learning_outcomes.plo_category_id', '=', 'p_l_o_categories.plo_category_id')->where('program_learning_outcomes.program_id', $program_id)->where('program_learning_outcomes.plo_category_id', null)->get();

        return view('programs.wizard.step1')->with('plos', $plos)->with('program', $program)->with('ploCategories', $ploCategories)
                                            ->with("faculties", $faculties)->with("departments", $departments)->with("levels",$levels)->with('user', $user)->with('programUsers',$programUsers)
                                            ->with('ploCount',$ploCount)->with('msCount', $msCount)->with('courseCount', $courseCount)->with('ploProgramCategories', $ploProgramCategories)
                                            ->with('hasUncategorized', $hasUncategorized)->with('unCategorizedPLOS', $unCategorizedPLOS)->with('isEditor', $isEditor)->with('isViewer', $isViewer);
    }

    public function step2($program_id, Request $request)
    {
        $isEditor = false;
        if ($request->isEditor) {
            $isEditor = true;
        }
        $isViewer = false;
        if ($request->isViewer) {
            return redirect()->route('programWizard.step4', $program_id);
        }
        //header
        $faculties = array("Faculty of Arts and Social Sciences", "Faculty of Creative and Critical Studies", "Okanagan School of Education", "School of Engineering", "School of Health and Exercise Sciences", "Faculty of Management", "Faculty of Science", "Faculty of Medicine", "College of Graduate Studies", "School of Nursing", "School of Social Work", "Other");
        $departments = array("Community, Culture and Global Studies", "Economics, Philosophy and Political Science", "History and Sociology", "Psychology", "Creative Studies", "Languages and World Literature", "English and Cultural Studies", "Biology", "Chemistry", "Computer Science, Mathematics, Physics and Statistics", "Earth, Environmental and Geographic Sciences", "Other" );
        $levels = array("Undergraduate", "Graduate", "Other");
        $user = User::where('id',Auth::id())->first();
        $programUsers = ProgramUser::join('users','program_users.user_id',"=","users.id")
                                ->select('users.email','program_users.user_id','program_users.program_id')
                                ->where('program_users.program_id','=',$program_id)->get();

        //
        $mappingScales = MappingScale::join('mapping_scale_programs', 'mapping_scales.map_scale_id', "=", 'mapping_scale_programs.map_scale_id')
                                    ->where('mapping_scale_programs.program_id', $program_id)->get();

        // checks if user has created a custom mapping scale
        $hasCustomMS = false;
        foreach ($mappingScales as $ms) {
            if ($ms->mapping_scale_categories_id == null) {
                $hasCustomMS = true;
            }
        }
        // checks if user has created a custom mapping scale
        $hasImportedMS = false;
        foreach ($mappingScales as $ms) {
            if ($ms->mapping_scale_categories_id != null) {
                $hasImportedMS = true;
            }
        }

        // Returns all mapping scale categories 
        $msCategories = DB::table('mapping_scale_categories')->get();
        // Returns all mapping scales
        $mscScale = DB::table('mapping_scales')->get();

        $program = Program::where('program_id', $program_id)->first();

        //progress bar
        $ploCount = ProgramLearningOutcome::where('program_id', $program_id)->count();
        $msCount = count($mappingScales);
        $courseCount = CourseProgram::where('program_id', $program_id)->count();


        return view('programs.wizard.step2')->with('mappingScales', $mappingScales)->with('program', $program)
                                            ->with("faculties", $faculties)->with("departments", $departments)->with("levels",$levels)->with('user', $user)->with('programUsers',$programUsers)
                                            ->with('ploCount',$ploCount)->with('msCount', $msCount)->with('courseCount', $courseCount)->with('msCategories', $msCategories)->with('mscScale', $mscScale)
                                            ->with('hasCustomMS', $hasCustomMS)->with('hasImportedMS', $hasImportedMS)->with('isEditor', $isEditor)->with('isViewer', $isViewer);
    }

    public function step3($program_id, Request $request)
    {
        $isEditor = false;
        if ($request->isEditor) {
            $isEditor = true;
        }
        $isViewer = false;
        if ($request->isViewer) {
            return redirect()->route('programWizard.step4', $program_id);
        }
        //header
        $faculties = array("Faculty of Arts and Social Sciences", "Faculty of Creative and Critical Studies", "Okanagan School of Education", "School of Engineering", "School of Health and Exercise Sciences", "Faculty of Management", "Faculty of Science", "Faculty of Medicine", "College of Graduate Studies", "School of Nursing", "School of Social Work", "Other");
        $departments = array("Community, Culture and Global Studies", "Economics, Philosophy and Political Science", "History and Sociology", "Psychology", "Creative Studies", "Languages and World Literature", "English and Cultural Studies", "Biology", "Chemistry", "Computer Science, Mathematics, Physics and Statistics", "Earth, Environmental and Geographic Sciences", "Other" );
        $levels = array("Undergraduate", "Graduate", "Other");
        $programUsers = ProgramUser::join('users','program_users.user_id',"=","users.id")
                                ->select('users.email','program_users.user_id','program_users.program_id')
                                ->where('program_users.program_id','=',$program_id)->get();

        // get the current user
        $user = User::where('id',Auth::id())->first();
        // get the program
        $program = Program::where('program_id', $program_id)->first();
        // get all the users that belong to this program (using relationship defined in Program model)
        //$programUsers = $program->users()->where('program_id', $program_id)->get();
        $programUsers = ProgramUser::join('users','program_users.user_id',"=","users.id")
                                ->select('users.email','program_users.user_id','program_users.program_id')
                                ->where('program_users.program_id','=',$program_id)->get();
        // get all the courses that belong to this program
        $programCourses = $program->courses()->get();
        // get ids of all the courses that belong to this program
        $programCourseIds = $programCourses->map(function ($programCourse) {
            return $programCourse->course_id;
        });

        // get all courses that belong to this user that don't yet belong to this program
        $userCoursesNotInProgram = $user->courses()->whereNotIn('courses.course_id', $programCourseIds)->get();

        $programCoursesUsers = array();
        foreach ($programCourses as $programCourse) {
            $programCoursesUsers[$programCourse->course_id] = $programCourse->users()->get();
        }

        // progress bar
        $ploCount = ProgramLearningOutcome::where('program_id', $program_id)->count();
        $msCount = MappingScale::join('mapping_scale_programs', 'mapping_scales.map_scale_id', "=", 'mapping_scale_programs.map_scale_id')
                                    ->where('mapping_scale_programs.program_id', $program_id)->count();

        // Returns all standard categories in the DB
        $standard_categories = DB::table('standard_categories')->get();

        // All Learning Outcomes for program courses
        $LearningOutcomesForProgramCourses = array();
        foreach ($programCourses as $programCourse) {
            $LearningOutcomesForProgramCourses[$programCourse->course_id] = LearningOutcome::where('course_id', $programCourse->course_id)->pluck('l_outcome_id')->toArray();
        }

        // ploCount * cloCount = number of outcome map results for course and program
        $expectedTotalOutcomes = array();
        foreach ($programCourses as $programCourse) {
            $expectedTotalOutcomes[$programCourse->course_id] = count(LearningOutcome::where('course_id', $programCourse->course_id)->pluck('l_outcome_id')->toArray()) * $ploCount;
        }

        // Get all PLO Id's
        $arrayPLOutcomeIds = ProgramLearningOutcome::where('program_id', $program_id)->pluck('pl_outcome_id')->toArray();

        // Loop through All Learning Outcomes for program courses
        $actualTotalOutcomes = array();
        foreach($LearningOutcomesForProgramCourses as $courseId => $courseLOs) {
            // Loop through each of the CLO IDs
            $count = 0;
            foreach ($courseLOs as $lo_Id) {
                // loop through all of the PLO ID's
                foreach ($arrayPLOutcomeIds as $pl_id) {
                    // If entry for an Outcome map [l_outcome_id, pl_outcome_id] exists increment counter
                    if (OutcomeMap::where('l_outcome_id', $lo_Id)->where('pl_outcome_id', $pl_id)->exists()) {
                        $count++;
                    }
                }
            }
            // stores total count 
            $actualTotalOutcomes[$courseId] = $count;
        }

        return view('programs.wizard.step3')->with('program', $program)->with('programCoursesUsers', $programCoursesUsers)
                                            ->with("faculties", $faculties)->with("departments", $departments)->with("levels",$levels)->with('user', $user)->with('programUsers',$programUsers)
                                            ->with('ploCount',$ploCount)->with('msCount', $msCount)->with('programCourses', $programCourses)->with('userCoursesNotInProgram', $userCoursesNotInProgram)->with('standard_categories', $standard_categories)
                                            ->with('actualTotalOutcomes', $actualTotalOutcomes)->with('expectedTotalOutcomes', $expectedTotalOutcomes)->with('isEditor', $isEditor)->with('isViewer', $isViewer);
    }

    public function step4($program_id, Request $request)
    {
        $isEditor = false;
        $isViewer = false;
        if ($request->isEditor) {
            $isEditor = true;
        }else if ($request->isViewer) {
            $isViewer = true;
        }
        //header
        $faculties = array("Faculty of Arts and Social Sciences", "Faculty of Creative and Critical Studies", "Okanagan School of Education", "School of Engineering", "School of Health and Exercise Sciences", "Faculty of Management", "Faculty of Science", "Faculty of Medicine", "College of Graduate Studies", "School of Nursing", "School of Social Work", "Other");
        $departments = array("Community, Culture and Global Studies", "Economics, Philosophy and Political Science", "History and Sociology", "Psychology", "Creative Studies", "Languages and World Literature", "English and Cultural Studies", "Biology", "Chemistry", "Computer Science, Mathematics, Physics and Statistics", "Earth, Environmental and Geographic Sciences", "Other" );
        $levels = array("Undergraduate", "Graduate", "Other");
        $user = User::where('id',Auth::id())->first();
        $programUsers = ProgramUser::join('users','program_users.user_id',"=","users.id")
                                ->select('users.email','program_users.user_id','program_users.program_id')
                                ->where('program_users.program_id','=',$program_id)->get();

        //
        $program = Program::where('program_id', $program_id)->first();

        //progress bar
        $ploCount = ProgramLearningOutcome::where('program_id', $program_id)->count();
        $msCount = MappingScale::join('mapping_scale_programs', 'mapping_scales.map_scale_id', "=", 'mapping_scale_programs.map_scale_id')
                                    ->where('mapping_scale_programs.program_id', $program_id)->count();
        //
        $courseCount = CourseProgram::where('program_id', $program_id)->count();
        //
        $mappingScales = MappingScale::join('mapping_scale_programs', 'mapping_scales.map_scale_id', "=", 'mapping_scale_programs.map_scale_id')
                                    ->where('mapping_scale_programs.program_id', $program_id)->get();

        // get all the courses this program belongs to
        $programCourses = $program->courses;

        // get all of the required courses this program belongs to
        $requiredProgramCourses = Course::join('course_programs', 'courses.course_id', '=', 'course_programs.course_id')->where('course_programs.program_id', $program_id)->where('course_programs.course_required', 1)->get();

        // get all categories for program
        $ploCategories = PLOCategory::where('program_id', $program_id)->get();
        // get plo categories for program
        $ploProgramCategories = PLOCategory::where('p_l_o_categories.program_id', $program_id)->join('program_learning_outcomes', 'p_l_o_categories.plo_category_id', '=', 'program_learning_outcomes.plo_category_id')->get();
        // get all plo's
        $allPLO = ProgramLearningOutcome::where('program_id', $program_id)->get();
        // get plo's for the program 
        $plos = DB::table('program_learning_outcomes')->leftJoin('p_l_o_categories', 'program_learning_outcomes.plo_category_id', '=', 'p_l_o_categories.plo_category_id')->where('program_learning_outcomes.program_id', $program_id)->get();
        // get UnCategorized PLO's
        $unCategorizedPLOS = DB::table('program_learning_outcomes')->leftJoin('p_l_o_categories', 'program_learning_outcomes.plo_category_id', '=', 'p_l_o_categories.plo_category_id')->where('program_learning_outcomes.program_id', $program_id)->where('program_learning_outcomes.plo_category_id', null)->get();

        // returns the number of Categories that contain at least one PLO
        $numCatUsed = 0;
        $uniqueCategories = array();
        foreach ($ploProgramCategories as $ploInCategory) {
            if (!in_array($ploInCategory->plo_category_id, $uniqueCategories)) {
                $uniqueCategories[] += $ploInCategory->plo_category_id;
                $numCatUsed++;
            }
        }
        
        // plosPerCategory returns the number of plo's belonging to each category
        // used for setting the colspan in the view
        $plosPerCategory = array();
        foreach($ploProgramCategories as $ploCategory) {
            $plosPerCategory[$ploCategory->plo_category_id] = 0;
        }
        foreach($ploProgramCategories as $ploCategory) {
            $plosPerCategory[$ploCategory->plo_category_id] += 1;
        }
        
        // Used for setting colspan in view
        $numUncategorizedPLOS = 0;
        foreach ($allPLO as $plo) {
            if ($plo->plo_category_id == null){
                $numUncategorizedPLOS ++;
            }
        }

        // returns true if there exists a plo without a category
        $hasUncategorized = false;
        foreach ($plos as $plo) {
            if ($plo->plo_category == NULL) {
                $hasUncategorized = true;
            }
        }

        // All Courses Frequency Distribution
        $coursesOutcomes = array();
        $coursesOutcomes = $this->getCoursesOutcomes($coursesOutcomes, $programCourses);
        $arr = array();
        $arr = $this->getOutcomeMaps($allPLO, $coursesOutcomes, $arr);
        $store = array();
        $store = $this->createCDFArray($arr, $store);
        $store = $this->frequencyDistribution($arr, $store);
        $store = $this->replaceIdsWithAbv($store, $arr);
        $store = $this->assignColours($store);

        // Required Courses Frequency Distribution
        $coursesOutcomes = array();
        $coursesOutcomes = $this->getCoursesOutcomes($coursesOutcomes, $requiredProgramCourses);
        $arrRequired = array();
        $arrRequired = $this->getOutcomeMaps($allPLO, $coursesOutcomes, $arrRequired);
        $storeRequired = array();
        $storeRequired = $this->createCDFArray($arrRequired, $storeRequired);
        $storeRequired = $this->frequencyDistribution($arrRequired, $storeRequired);
        $storeRequired = $this->replaceIdsWithAbv($storeRequired, $arrRequired);
        $storeRequired = $this->assignColours($storeRequired);

        return view('programs.wizard.step4')->with('program', $program)
                                            ->with("faculties", $faculties)->with("departments", $departments)->with("levels",$levels)->with('user', $user)->with('programUsers',$programUsers)
                                            ->with('ploCount',$ploCount)->with('msCount', $msCount)->with('courseCount', $courseCount)->with('programCourses', $programCourses)->with('coursesOutcomes', $coursesOutcomes)
                                            ->with('ploCategories', $ploCategories)->with('plos', $plos)->with('hasUncategorized', $hasUncategorized)->with('ploProgramCategories', $ploProgramCategories)->with('plosPerCategory', $plosPerCategory)
                                            ->with('numUncategorizedPLOS', $numUncategorizedPLOS)->with('mappingScales', $mappingScales)->with('testArr', $store)->with('unCategorizedPLOS', $unCategorizedPLOS)->with('numCatUsed', $numCatUsed)
                                            ->with('storeRequired', $storeRequired)->with('requiredProgramCourses', $requiredProgramCourses)->with('isEditor', $isEditor)->with('isViewer', $isViewer);
    }

    public function getCoursesOutcomes($coursesOutcomes, $programCourses) {
        // get all CLO's for each course in the program
        foreach ($programCourses as $programCourse) {
            $learningOutcomes = $programCourse->learningOutcomes;
            $coursesOutcomes[$programCourse->course_id] = $learningOutcomes;
        }
        return $coursesOutcomes;
    }

    public function getOutcomeMaps ($allPLO, $coursesOutcomes, $arr) {
        // retrieves all the outcome mapping values for every clo and plo
        $count = 0;
        foreach ($allPLO as $plo) {
            // loop through CLOs to get map scale value
            foreach ($coursesOutcomes as $clos) {
                foreach ($clos as $clo) {
                    // Check if record exists in the db
                    if (!OutcomeMap::where(['l_outcome_id' => $clo->l_outcome_id, 'pl_outcome_id' => $plo->pl_outcome_id])->exists()) {
                        // if nothing is found then do nothing
                        // else if record (mapping_scale_id) is found then store it in the array
                    } else {
                        $count++;
                        $mapScaleValue = OutcomeMap::where(['l_outcome_id' => $clo->l_outcome_id, 'pl_outcome_id' => $plo->pl_outcome_id])->value('map_scale_id');
                        $arr[$count] = array(
                            'pl_outcome_id' => $plo->pl_outcome_id,
                            'course_id' => $clo->course_id,
                            'map_scale_id' => $mapScaleValue,
                            'l_outcome_id' => $clo->l_outcome_id,
                        );
                    }
                }
            }
        }
        return $arr;
    }

    public function createCDFArray($arr, $store) {
        // Initialize array for each pl_outcome_id with the value of null
        foreach ($arr as $ar) {
            $store[$ar['pl_outcome_id']] = null;
        }
        // Initialize Array for Storing 
        foreach ($arr as $ar) {
            if ($store[$ar['pl_outcome_id']] == null || $store[$ar['pl_outcome_id']] == $ar['pl_outcome_id']) {
                $store[$ar['pl_outcome_id']] = array(
                    $ar['course_id'] => array(
                    ),
                );
            } else {
                $store[$ar['pl_outcome_id']][$ar['course_id']] = array();
                $store[$ar['pl_outcome_id']][$ar['course_id']]['frequencies'] = array();
            }
        }
        return $store;
    }

    public function frequencyDistribution($arr, $store) {
        //Initialize Array for Frequency Distribution
        $freq = array();
        foreach ($arr as $map) {
            $pl_outcome_id = $map['pl_outcome_id'];
            $course_id = $map['course_id'];
            $map_scale_id = $map['map_scale_id'];
            //Initialize Array with the value of zero
            $freq[$pl_outcome_id][$course_id][$map_scale_id] = 0;
        }
        // Store values in the frequency distribution array that was initialized to zero above
        foreach ($arr as $map) {
            $pl_outcome_id = $map['pl_outcome_id'];
            $course_id = $map['course_id'];
            $map_scale_id = $map['map_scale_id'];
            // check if map_scale_value is in the frequency array and give it the value of 1
            if ($freq[$pl_outcome_id][$course_id][$map_scale_id] == 0) {
                $freq[$pl_outcome_id][$course_id][$map_scale_id] = 1;
            // if the value is found again, and is not zero, increment
            } else {
                $freq[$pl_outcome_id][$course_id][$map_scale_id] += 1;
            }
        }
        // loop through the frequencies of the mapping values
        foreach($freq as $plOutcomeId => $dist) {
            foreach($dist as $courseId => $d) {
                $weight = 0;
                $tieResults = array();
                //count the number of times a mapping scales appears for a program learning outcome 
                foreach($d as $mapScaleWeight) {
                    //check if the current ($mapScaleWeight) > than the previously stored value
                    if ($weight < $mapScaleWeight) {
                        $weight = $mapScaleWeight;
                    } else if ($weight == $mapScaleWeight) {    // if a tie is found store the mapping scale values (I.e: I, A, D) in and array
                        $tieResults = array_keys($d, $weight);
                    }
                }
                // if A tie is found.. 
                if ($tieResults != null) {
                    $stringResults = '';
                    $numItems = count($tieResults);
                    $i = 0;
                    // for each tie value append to a string
                    foreach ($tieResults as $tieResult) {
                        // appends '/' only if it's not at the last index in the array
                        if (++$i !== $numItems) {
                            $stringResults .= "" .MappingScale::where('map_scale_id', $tieResult)->value('abbreviation'). " / "; 
                        } else {
                            $stringResults .= "" .MappingScale::where('map_scale_id', $tieResult)->value('abbreviation');
                        }
                    }
                    // Store the results array as the map_scale_value key
                    $store[$plOutcomeId][$courseId] += array(
                        'map_scale_abv' => $stringResults 
                    );
                    // Store a new array to be able to determine if the mapping scale value comes from the result of a tie
                    $store[$plOutcomeId][$courseId] += array(
                        'map_scale_id_tie' => True
                    );
                    // Store the frequencies
                    $store[$plOutcomeId][$courseId]['frequencies'] = $freq[$plOutcomeId][$courseId];
                } else {
                    // If no tie is present, store the strongest weighted map_scale_value 
                    $store[$plOutcomeId][$courseId] = array(
                        'map_scale_id' => array_search($weight, $d)
                    );
                    $store[$plOutcomeId][$courseId] += array(
                        'map_scale_abv' => MappingScale::where('map_scale_id', array_search($weight, $d))->value('abbreviation')
                    );
                    // Store the frequencies
                    $store[$plOutcomeId][$courseId]['frequencies'] = $freq[$plOutcomeId][$courseId];
                }
            }
        }
        return $store;
    }

    public function replaceIdsWithAbv($store, $arr) {
        //Initialize Array for Frequency Distribution
        $freq = array();
        foreach ($arr as $map) {
            $pl_outcome_id = $map['pl_outcome_id'];
            $course_id = $map['course_id'];
            $map_scale_id = MappingScale::where('map_scale_id', $map['map_scale_id'])->value('abbreviation');
            //Initialize Array with the value of zero
            $freq[$pl_outcome_id][$course_id][$map_scale_id] = 0;
        }
        // Store values in the frequency distribution array that was initialized to zero above
        foreach ($arr as $map) {
            $pl_outcome_id = $map['pl_outcome_id'];
            $course_id = $map['course_id'];
            $map_scale_id = MappingScale::where('map_scale_id', $map['map_scale_id'])->value('abbreviation');
            // check if map_scale_value is in the frequency array and give it the value of 1
            if ($freq[$pl_outcome_id][$course_id][$map_scale_id] == 0) {
                $freq[$pl_outcome_id][$course_id][$map_scale_id] = 1;
            // if the value is found again, and is not zero, increment
            } else {
                $freq[$pl_outcome_id][$course_id][$map_scale_id] += 1;
            }
        }
        foreach($freq as $plOutcomeId => $dist) {
            foreach($dist as $courseId => $d) {
                // Store the frequencies
                $store[$plOutcomeId][$courseId]['frequencies'] = $freq[$plOutcomeId][$courseId];
            }
        }
        return $store;
    }

    public function assignColours($store){
        // Assign a colour to store based
        foreach ($store as $plOutcomeId => $s) {
            foreach ($s as $courseId => $msv) {
                // If a tie exists assign it the colour white
                if (array_key_exists("map_scale_id_tie",$msv)) {
                    $mapScaleColour = '#FFFFFF';
                    $store[$plOutcomeId][$courseId] += array(
                        'colour' => $mapScaleColour
                    );
                } else {
                    // Search for the mapping scale colour in the db, then assign it to the array
                    $mapScaleColour = MappingScale::where('map_scale_id', $msv['map_scale_id'])->value('colour');
                
                if ($mapScaleColour == null) {
                    $mapScaleColour = '#FFFFFF';
                }
                    $store[$plOutcomeId][$courseId] += array(
                        'colour' => $mapScaleColour
                    );
                }
            }
        }
        return $store;
    }

}
