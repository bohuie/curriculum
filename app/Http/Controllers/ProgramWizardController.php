<?php

namespace App\Http\Controllers;

use App\Models\AssessmentMethod;
use App\Models\Campus;
use App\Models\Course;
use App\Models\CourseOptionalPriorities;
use App\Models\CourseProgram;
use App\Models\Department;
use App\Models\Faculty;
use App\Models\LearningActivity;
use App\Models\LearningOutcome;
use App\Models\MappingScale;
use App\Models\OptionalPriorities;
use App\Models\OptionalPrioritySubcategories;
use App\Models\OutcomeMap;
use App\Models\PLOCategory;
use App\Models\Program;
use App\Models\ProgramLearningOutcome;
use App\Models\Standard;
use App\Models\StandardCategory;
use App\Models\StandardScale;
use App\Models\StandardsOutcomeMap;
use App\Models\User;
use Doctrine\DBAL\Schema\Index;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ProgramWizardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
        $this->middleware('hasAccess');
    }

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
        $campuses = Campus::all();
        $faculties = Faculty::all();
        $departments = Department::all();
        $levels = ['Undergraduate', 'Graduate', 'Other'];
        $user = User::where('id', Auth::id())->first();
        // get my programs
        $myPrograms = $user->programs;
        // returns a collection of programs associated with users Collaborators
        $programUsers = [];
        foreach ($myPrograms as $program) {
            $programsUsers = $program->users()->get();
            $programUsers[$program->program_id] = $programsUsers;
        }

        //$plos = ProgramLearningOutcome::join('p_l_o_categories', 'program_learning_outcomes.plo_category_id', '=', 'p_l_o_categories.plo_category_id')->where('program_learning_outcomes.program_id', $program_id)->get();
        $plos = DB::table('program_learning_outcomes')->leftJoin('p_l_o_categories', 'program_learning_outcomes.plo_category_id', '=', 'p_l_o_categories.plo_category_id')->where('program_learning_outcomes.program_id', $program_id)->get();

        $program = Program::where('program_id', $program_id)->first();
        $ploCategories = PLOCategory::where('program_id', $program_id)->get();

        $ploProgramCategories = PLOCategory::where('p_l_o_categories.program_id', $program_id)->join('program_learning_outcomes', 'p_l_o_categories.plo_category_id', '=', 'program_learning_outcomes.plo_category_id')->get();

        //progress bar
        $ploCount = count($plos);
        $msCount = MappingScale::join('mapping_scale_programs', 'mapping_scales.map_scale_id', '=', 'mapping_scale_programs.map_scale_id')
            ->where('mapping_scale_programs.program_id', $program_id)->count();
        $courseCount = CourseProgram::where('program_id', $program_id)->count();

        // returns true if there exists a plo without a category
        $hasUncategorized = false;
        foreach ($plos as $plo) {
            if ($plo->plo_category == null) {
                $hasUncategorized = true;
            }
        }

        //dd($ploCategories);
        // get UnCategorized PLO's
        $unCategorizedPLOS = DB::table('program_learning_outcomes')->leftJoin('p_l_o_categories', 'program_learning_outcomes.plo_category_id', '=', 'p_l_o_categories.plo_category_id')->where('program_learning_outcomes.program_id', $program_id)->where('program_learning_outcomes.plo_category_id', null)->get();

        //get defaultShortForms based on PLO Category, then Creation Order
        $defaultShortForms = [];
        $defaultShortFormsIndex = [];
        $plosInOrderCat = [];

        foreach ($ploCategories as $ploCat) {
            $plosByCat = ProgramLearningOutcome::where('plo_category_id', $ploCat['plo_category_id'])->get();
            array_push($plosInOrderCat, $plosByCat);

        }

        $ploDefaultCount = 0;
        for ($i = 0; $i < count($plosInOrderCat); $i++) {
            for ($j = 0; $j < count($plosInOrderCat[$i]); $j++) {
                $defaultShortForms[$plosInOrderCat[$i][$j]['pl_outcome_id']] = 'PLO #'.($ploDefaultCount + 1);
                $defaultShortFormsIndex[$plosInOrderCat[$i][$j]['pl_outcome_id']] = $ploDefaultCount + 1;
                $ploDefaultCount++;
            }
        }

        foreach ($unCategorizedPLOS as $unCatPLO) {
            $defaultShortForms[$unCatPLO->pl_outcome_id] = 'PLO #'.($ploDefaultCount + 1);
            $defaultShortFormsIndex[$unCatPLO->pl_outcome_id] = $ploDefaultCount + 1;
            $ploDefaultCount++;
        }

        return view('programs.wizard.step1')->with('plos', $plos)->with('program', $program)->with('ploCategories', $ploCategories)
            ->with('faculties', $faculties)->with('departments', $departments)->with('campuses', $campuses)->with('levels', $levels)->with('user', $user)->with('programUsers', $programUsers)
            ->with('ploCount', $ploCount)->with('msCount', $msCount)->with('courseCount', $courseCount)->with('ploProgramCategories', $ploProgramCategories)
            ->with('hasUncategorized', $hasUncategorized)->with('unCategorizedPLOS', $unCategorizedPLOS)->with('isEditor', $isEditor)->with('isViewer', $isViewer)->with('defaultShortForms', $defaultShortForms)->with('defaultShortFormsIndex', $defaultShortFormsIndex);
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
        $campuses = Campus::all();
        $faculties = Faculty::all();
        $departments = Department::all();
        $levels = ['Undergraduate', 'Graduate', 'Other'];
        $user = User::where('id', Auth::id())->first();
        // get my programs
        $myPrograms = $user->programs;
        // returns a collection of programs associated with users Collaborators
        $programUsers = [];
        foreach ($myPrograms as $program) {
            $programsUsers = $program->users()->get();
            $programUsers[$program->program_id] = $programsUsers;
        }

        //
        $mappingScales = MappingScale::join('mapping_scale_programs', 'mapping_scales.map_scale_id', '=', 'mapping_scale_programs.map_scale_id')
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
            ->with('faculties', $faculties)->with('departments', $departments)->with('campuses', $campuses)->with('levels', $levels)->with('user', $user)->with('programUsers', $programUsers)
            ->with('ploCount', $ploCount)->with('msCount', $msCount)->with('courseCount', $courseCount)->with('msCategories', $msCategories)->with('mscScale', $mscScale)
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
        $campuses = Campus::all();
        $faculties = Faculty::all();
        $departments = Department::all();
        $levels = ['Undergraduate', 'Graduate', 'Other'];
        $user = User::where('id', Auth::id())->first();
        // get my programs
        $myPrograms = $user->programs;
        // returns a collection of programs associated with users Collaborators
        $programUsers = [];
        foreach ($myPrograms as $program) {
            $programsUsers = $program->users()->get();
            $programUsers[$program->program_id] = $programsUsers;
        }

        // get the current user
        $user = User::where('id', Auth::id())->first();
        // get the program
        $program = Program::where('program_id', $program_id)->first();
        // get all the courses that belong to this program
        $programCourses = $program->courses()->orderBy('course_code', 'asc')->orderBy('course_num', 'asc')->get();

        // get ids of all the courses that belong to this program
        $programCourseIds = $programCourses->map(function ($programCourse) {
            return $programCourse->course_id;
        });

        // get all courses that belong to this user that don't yet belong to this program
        $userCoursesNotInProgram = $user->courses()->whereNotIn('courses.course_id', $programCourseIds)->orderBy('course_code', 'asc')->orderBy('course_num', 'asc')->get();

        $programCoursesUsers = [];
        foreach ($programCourses as $programCourse) {
            $programCoursesUsers[$programCourse->course_id] = $programCourse->users()->get();
        }

        // progress bar
        $ploCount = ProgramLearningOutcome::where('program_id', $program_id)->count();
        $msCount = MappingScale::join('mapping_scale_programs', 'mapping_scales.map_scale_id', '=', 'mapping_scale_programs.map_scale_id')
            ->where('mapping_scale_programs.program_id', $program_id)->count();

        // Returns all standard categories in the DB
        $standard_categories = DB::table('standard_categories')->get();

        // All Learning Outcomes for program courses
        $LearningOutcomesForProgramCourses = [];
        foreach ($programCourses as $programCourse) {
            $LearningOutcomesForProgramCourses[$programCourse->course_id] = LearningOutcome::where('course_id', $programCourse->course_id)->pluck('l_outcome_id')->toArray();
        }

        // ploCount * cloCount = number of outcome map results for course and program
        $expectedTotalOutcomes = [];
        foreach ($programCourses as $programCourse) {
            $expectedTotalOutcomes[$programCourse->course_id] = (count(LearningOutcome::where('course_id', $programCourse->course_id)->pluck('l_outcome_id')->toArray()) == 0) ? $ploCount : count(LearningOutcome::where('course_id', $programCourse->course_id)->pluck('l_outcome_id')->toArray()) * $ploCount;
        }

        // Get all PLO Id's
        $arrayPLOutcomeIds = ProgramLearningOutcome::where('program_id', $program_id)->pluck('pl_outcome_id')->toArray();

        // Loop through All Learning Outcomes for program courses
        $actualTotalOutcomes = [];
        foreach ($LearningOutcomesForProgramCourses as $courseId => $courseLOs) {
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
            ->with('faculties', $faculties)->with('departments', $departments)->with('campuses', $campuses)->with('levels', $levels)->with('user', $user)->with('programUsers', $programUsers)
            ->with('ploCount', $ploCount)->with('msCount', $msCount)->with('programCourses', $programCourses)->with('userCoursesNotInProgram', $userCoursesNotInProgram)->with('standard_categories', $standard_categories)
            ->with('actualTotalOutcomes', $actualTotalOutcomes)->with('expectedTotalOutcomes', $expectedTotalOutcomes)->with('isEditor', $isEditor)->with('isViewer', $isViewer);
    }

    public function step4($program_id, Request $request): View
    {
        $isEditor = false;
        $isViewer = false;
        if ($request->isEditor) {
            $isEditor = true;
        } elseif ($request->isViewer) {
            $isViewer = true;
        }
        //header
        $campuses = Campus::all();
        $faculties = Faculty::all();
        $departments = Department::all();
        $levels = ['Undergraduate', 'Graduate', 'Other'];
        $user = User::where('id', Auth::id())->first();
        // get my programs
        $myPrograms = $user->programs;
        // returns a collection of programs associated with users Collaborators
        $programUsers = [];
        foreach ($myPrograms as $program) {
            $programsUsers = $program->users()->get();
            $programUsers[$program->program_id] = $programsUsers;
        }

        //
        $program = Program::where('program_id', $program_id)->first();

        //progress bar
        $ploCount = ProgramLearningOutcome::where('program_id', $program_id)->count();
        $msCount = MappingScale::join('mapping_scale_programs', 'mapping_scales.map_scale_id', '=', 'mapping_scale_programs.map_scale_id')
            ->where('mapping_scale_programs.program_id', $program_id)->count();
        //
        $courseCount = CourseProgram::where('program_id', $program_id)->count();
        //
        $mappingScales = MappingScale::join('mapping_scale_programs', 'mapping_scales.map_scale_id', '=', 'mapping_scale_programs.map_scale_id')
            ->where('mapping_scale_programs.program_id', $program_id)->get();

        // get all the courses this program belongs to
        $programCourses = $program->courses()->orderBy('course_code', 'asc')->orderBy('course_num', 'asc')->get();

        // All Learning Outcomes for program courses
        $LearningOutcomesForProgramCourses = [];
        foreach ($programCourses as $programCourse) {
            $LearningOutcomesForProgramCourses[$programCourse->course_id] = LearningOutcome::where('course_id', $programCourse->course_id)->pluck('l_outcome_id')->toArray();
        }

        // ploCount * cloCount = number of outcome map results for course and program
        $expectedTotalOutcomes = [];
        foreach ($programCourses as $programCourse) {
            $expectedTotalOutcomes[$programCourse->course_id] = (count(LearningOutcome::where('course_id', $programCourse->course_id)->pluck('l_outcome_id')->toArray()) == 0) ? $ploCount : count(LearningOutcome::where('course_id', $programCourse->course_id)->pluck('l_outcome_id')->toArray()) * $ploCount;
        }

        // Get all PLO Id's
        $arrayPLOutcomeIds = ProgramLearningOutcome::where('program_id', $program_id)->pluck('pl_outcome_id')->toArray();

        // Loop through All Learning Outcomes for program courses
        $actualTotalOutcomes = [];
        foreach ($LearningOutcomesForProgramCourses as $courseId => $courseLOs) {
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

        $hasUnMappedCourses = false;
        foreach ($expectedTotalOutcomes as $courseID => $expectedTotalOutcome) {
            if ($expectedTotalOutcome != $actualTotalOutcomes[$courseID]) {
                $hasUnMappedCourses = true;
                break;
            }
        }

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
        $uniqueCategories = [];
        foreach ($ploProgramCategories as $ploInCategory) {
            if (! in_array($ploInCategory->plo_category_id, $uniqueCategories)) {
                $uniqueCategories[] += $ploInCategory->plo_category_id;
                $numCatUsed++;
            }
        }

        // returns true if there exists a plo without a category
        $hasUncategorized = false;
        foreach ($plos as $plo) {
            if ($plo->plo_category == null) {
                $hasUncategorized = true;
            }
        }

        // Get Mapping Scales for high-chart
        $programMappingScales = $mappingScales->pluck('abbreviation')->toArray();
        $programMappingScales[count($programMappingScales)] = 'N/A';
        // Get Mapping Scale Colours for high-chart
        $programMappingScalesIds = $mappingScales->pluck('map_scale_id')->toArray();
        $programMappingScalesIds[count($programMappingScalesIds)] = 0;
        $programMappingScalesColours = [];
        $freqOfMSIds = [];          // used in a later step
        for ($i = 0; $i < count($programMappingScalesIds); $i++) {
            $freqOfMSIds[$programMappingScalesIds[$i]] = [];
            $programMappingScalesColours[$i] = (strtolower(MappingScale::where('map_scale_id', $programMappingScalesIds[$i])->pluck('colour')->first()) == '#ffffff' || strtolower(MappingScale::where('map_scale_id', $programMappingScalesIds[$i])->pluck('colour')->first()) == '#fff' ? '#6c757d' : MappingScale::where('map_scale_id', $programMappingScalesIds[$i])->pluck('colour')->first());
        }
        // get categorized plo's for the program (ordered by category then outcome id)
        $plos_order = ProgramLearningOutcome::where('program_id', $program_id)->whereNotNull('plo_category_id')->orderBy('plo_category_id', 'ASC')->orderBy('pl_outcome_id', 'ASC')->get();
        // get UnCategorized PLO's
        $uncatPLOS = ProgramLearningOutcome::where('program_id', $program_id)->whereNull('plo_category_id')->get();
        // Merge Categorized PLOs and Uncategorized PLOs
        $all_plos = $plos_order->toBase()->merge($uncatPLOS);
        $plosInOrder = $all_plos->pluck('plo_shortphrase')->toArray();

        // loop through $freqOfMSIds then
        // loop through PLOs ($ploInOrderIds) and get array [countOfAbvFor(plo1), countOfAbvFor(plo2), ... , countOfAbvFor(plo7)]
        $plosInOrderIds = $all_plos->pluck('pl_outcome_id')->toArray();
        foreach ($freqOfMSIds as $ms_id => $freqOfMSId) {
            foreach ($plosInOrderIds as $plosInOrderId) {
                array_push($freqOfMSIds[$ms_id], OutcomeMap::where('pl_outcome_id', $plosInOrderId)->where('map_scale_id', $ms_id)->count());
            }
        }
        // Change key so that order isn't messed up when data is used in highcharts
        $index = 0;
        $freqForMS = [];
        foreach ($freqOfMSIds as $ms_id => $freqOfMSId) {
            $freqForMS[$index] = $freqOfMSId;
            $index++;
        }

        $i = 0;
        foreach ($plosInOrder as $plo) {
            if ($plo == null) {
                $plosInOrder[$i] = 'PLO #'.($i + 1);
            }
            $i += 1;
        }

        //get defaultShortForms based on PLO Category, then Creation Order
        $defaultShortForms = [];
        $defaultShortFormsIndex = [];
        $plosInOrderCat = [];

        foreach ($ploCategories as $ploCat) {
            $plosByCat = ProgramLearningOutcome::where('plo_category_id', $ploCat['plo_category_id'])->get();
            array_push($plosInOrderCat, $plosByCat);

        }
        $ploDefaultCount = 0;
        for ($i = 0; $i < count($plosInOrderCat); $i++) {
            for ($j = 0; $j < count($plosInOrderCat[$i]); $j++) {
                $defaultShortForms[$plosInOrderCat[$i][$j]['pl_outcome_id']] = 'PLO #'.($ploDefaultCount + 1);
                $defaultShortFormsIndex[$plosInOrderCat[$i][$j]['pl_outcome_id']] = $ploDefaultCount + 1;
                $ploDefaultCount++;
            }
        }

        foreach ($unCategorizedPLOS as $unCatPLO) {
            $defaultShortForms[$unCatPLO->pl_outcome_id] = 'PLO #'.($ploDefaultCount + 1);
            $defaultShortFormsIndex[$unCatPLO->pl_outcome_id] = $ploDefaultCount + 1;
            $ploDefaultCount++;
        }

        return view('programs.wizard.step4')->with('program', $program)
            ->with('faculties', $faculties)->with('departments', $departments)->with('campuses', $campuses)->with('levels', $levels)->with('user', $user)->with('programUsers', $programUsers)
            ->with('ploCount', $ploCount)->with('msCount', $msCount)->with('courseCount', $courseCount)->with('programCourses', $programCourses)->with('numCatUsed', $numCatUsed)->with('unCategorizedPLOS', $unCategorizedPLOS)
            ->with('ploCategories', $ploCategories)->with('plos', $plos)->with('hasUncategorized', $hasUncategorized)->with('ploProgramCategories', $ploProgramCategories)
            ->with('mappingScales', $mappingScales)->with('isEditor', $isEditor)->with('isViewer', $isViewer)
            ->with(compact('programMappingScales'))->with(compact('programMappingScalesColours'))->with(compact('plosInOrder'))->with(compact('freqForMS'))->with('hasUnMappedCourses', $hasUnMappedCourses)->with('defaultShortForms', $defaultShortForms)->with('defaultShortFormsIndex', $defaultShortFormsIndex);
    }

    public function resetKeys($array)
    {
        $newArray = [];
        // Reset Keys for High-charts
        $i = 0;
        foreach ($array as $a) {
            $j = 0;
            foreach ($a as $data) {
                $newArray[$i][$j] = $data;
                $j++;
            }
            $i++;
        }

        return $newArray;
    }

    public function getMinistryStandards($program_id): JsonResponse
    {
        $program = Program::where('program_id', $program_id)->first();

        // Get all Standard Categories for courses in the program
        if ($program->level == 'Undergraduate' || $program->level == 'Bachelors') {
            $standardCategory = StandardCategory::find(1);
        } elseif ($program->level == 'Masters') {
            $standardCategory = StandardCategory::find(2);
        } elseif ($program->level == 'Doctoral') {
            $standardCategory = StandardCategory::find(3);
        } else {
            $standardCategory = StandardCategory::find(0);
        }

        // Get all Standards for courses in the program
        $standards = $standardCategory->standards;

        // Get the names of the standards for the categories (x-axis)
        $namesStandards = [];
        $descriptionsStandards = [];
        for ($i = 0; $i < count($standards); $i++) {
            $namesStandards[$i] = $standards[$i]->s_shortphrase;
            $descriptionsStandards[$i] = $standards[$i]->s_outcome;
        }

        // Get Standards Mapping Scales for high-chart
        $standardsMappingScales = StandardScale::where('scale_category_id', 1)->pluck('abbreviation')->toArray();
        $standardsMappingScales[count($standardsMappingScales)] = 'N/A';
        $standardsMappingScalesTitles = StandardScale::where('scale_category_id', 1)->pluck('title')->toArray();
        $standardsMappingScalesTitles[count($standardsMappingScales)] = StandardScale::find(0)->pluck('title')->first();

        // Get Standards Mapping Scale Colours for high-chart
        $standardMappingScalesIds = StandardScale::where('scale_category_id', 1)->pluck('standard_scale_id')->toArray();
        $standardMappingScalesIds[count($standardMappingScalesIds)] = 0;
        $standardMappingScalesColours = [];
        $freqOfMinistryStandardIds = [];          // used in a later step
        $coursesOfMinistryStandardIds = [];
        for ($i = 0; $i < count($standardMappingScalesIds); $i++) {
            $freqOfMinistryStandardIds[$standardMappingScalesIds[$i]] = [];
            $standardMappingScalesColours[$i] = (strtolower(StandardScale::where('standard_scale_id', $standardMappingScalesIds[$i])->pluck('colour')->first()) == '#ffffff' || strtolower(StandardScale::where('standard_scale_id', $standardMappingScalesIds[$i])->pluck('colour')->first()) == '#fff' ? '#6c757d' : StandardScale::where('standard_scale_id', $standardMappingScalesIds[$i])->pluck('colour')->first());
        }
        foreach ($freqOfMinistryStandardIds as $ms => $freqOfMinistryStandardId) {
            foreach ($standards as $standard) {
                $freqOfMinistryStandardIds[$ms][$standard->standard_id] = 0;
                $coursesOfMinistryStandardIds[$ms][$standard->standard_id] = [];
            }
        }

        $programCoursesFiltered = $program->courses()->where('standard_category_id', $standardCategory->standard_category_id)->get();

        $outputStandardOutcomeMaps = [];
        foreach ($programCoursesFiltered as $course) {
            // check that outcome map exists
            if (StandardsOutcomeMap::where('course_id', $course->course_id)->exists()) {
                foreach ($standards as $standard) {
                    $scale_id = StandardsOutcomeMap::where('course_id', $course->course_id)->where('standard_id', $standard->standard_id)->value('standard_scale_id');
                    $freqOfMinistryStandardIds[$scale_id][$standard->standard_id] += 1;
                    array_push($coursesOfMinistryStandardIds[$scale_id][$standard->standard_id], $course->course_id);
                }
            }
        }
        $frequencyOfMinistryStandardIds = $this->resetKeys($freqOfMinistryStandardIds);
        $coursesOfMinistryStandardResetKeys = $this->resetKeys($coursesOfMinistryStandardIds);

        $tableMS = $this->generateHTMLTableMinistryStandards($namesStandards, $standardsMappingScalesTitles, $frequencyOfMinistryStandardIds, $coursesOfMinistryStandardResetKeys, $standardMappingScalesColours, $descriptionsStandards);

        return response()->json([$programCoursesFiltered, $namesStandards, $outputStandardOutcomeMaps, $standardsMappingScales, $standardMappingScalesColours, $frequencyOfMinistryStandardIds, $tableMS], 200);
    }

    public function generateHTMLTableMinistryStandards($namesStandards, $standardsMappingScalesTitles, $frequencyOfMinistryStandardIds, $coursesOfMinistryStandardResetKeys, $standardMappingScalesColours, $descriptionsStandards)
    {
        $output = '';

        if (! count($namesStandards) < 1) {
            $output .= '<table class="table table-light table-bordered table-sm"><tbody><tr class="table-primary"><th>Ministry Standards</th><th>Courses</th></tr>';
            $i = 0;
            foreach ($namesStandards as $standard) {
                $output .= '<tr><td class="col col-md-5"><b>'.$standard.'</b> - '.$descriptionsStandards[$i].'</td><td>';
                $j = 0;
                foreach ($standardsMappingScalesTitles as $standardsMappingScale) {
                    $output .= '<table class="table table-light table-bordered table-sm"><tr><td>';

                    $output .= '<div class="row d-flex align-items-center justify-content-center"><div class="col col-md-1 text-md-right"><div style="background-color:'.$standardMappingScalesColours[$j].'; height: 12px; width: 12px; border-radius: 6px;"></div></div>';
                    $output .= '<div class="col col-md-3 text-md-left">'.$standardsMappingScale.': '.$frequencyOfMinistryStandardIds[$j][$i].'</div>';

                    $output .= '<div class="col col-md-7 text-md-left">';
                    $k = 0;
                    foreach ($coursesOfMinistryStandardResetKeys[$j][$i] as $course_id) {
                        $code = Course::where('course_id', $course_id)->pluck('course_code')->first();
                        $num = Course::where('course_id', $course_id)->pluck('course_num')->first();
                        if ($k != 0) {
                            $output .= ', '.$code.' '.$num;
                        } else {
                            $output .= ' '.$code.' '.$num;
                        }
                        $k++;
                    }
                    $output .= '</div></div>';
                    $j++;

                    $output .= '</td></tr></table>';
                }
                $output .= '</td></tr>';
                $i++;
            }
            $output .= '</tbody></table>';
        } else {
            $output = '<div class="alert alert-warning wizard"><i class="bi bi-exclamation-circle-fill"></i>There are no ministry standards for the courses belonging to this program, or there are no courses matching the criteria.</div>';
        }

        return $output;
    }

    public function getOptionalPriorities($program_id): JsonResponse
    {
        $program = Program::where('program_id', $program_id)->first();
        // get all the courses this program belongs to
        $programCourses = $program->courses()->orderBy('course_code', 'asc')->orderBy('course_num', 'asc')->get();

        $tempOptionalPriorities = [];
        foreach ($programCourses as $programCourse) {
            $tempOptionalPriorities[$programCourse->course_id] = CourseOptionalPriorities::where('course_id', $programCourse->course_id)->pluck('op_id')->toArray();
        }

        $opFrequencies = [];
        $opFrequenciesSubcategories = [];
        foreach ($tempOptionalPriorities as $courseID => $op_ids) {
            $course_code = Course::where('course_id', $courseID)->pluck('course_code')->first();
            $course_num = Course::where('course_id', $courseID)->pluck('course_num')->first();
            foreach ($op_ids as $op_id) {
                $subcat_id = OptionalPriorities::where('op_id', $op_id)->pluck('subcat_id')->first();
                // add to opFrequencies if not already in array
                if (! array_key_exists($op_id, $opFrequencies)) {
                    $opFrequencies[$op_id]['freq'] = 1;
                    $opFrequencies[$op_id]['title'] = OptionalPriorities::where('op_id', $op_id)->pluck('optional_priority')->first();
                    $opFrequencies[$op_id]['courses'] = [$courseID => $course_code.' '.$course_num];
                    $opFrequencies[$op_id]['subcat_id'] = $subcat_id;
                    $opFrequenciesSubcategories[$subcat_id] = OptionalPrioritySubcategories::where('subcat_id', $subcat_id)->pluck('subcat_name')->first();
                } else {
                    // otherwise increment if key (op_id) in array already
                    $opFrequencies[$op_id]['freq'] += 1;
                    $opFrequencies[$op_id]['courses'] += [$courseID => $course_code.' '.$course_num];
                }
            }
        }
        ksort($opFrequencies);
        ksort($opFrequenciesSubcategories);

        $output = $this->generateHTMLOptionalPriorities($opFrequencies, $opFrequenciesSubcategories);

        return response()->json($output, 200);
    }

    public function getOptionalPrioritiesFirstYear($program_id): JsonResponse
    {
        $firstYearProgramCourses = Course::join('course_programs', 'courses.course_id', '=', 'course_programs.course_id')->where('course_programs.program_id', $program_id)->orderBy('course_code', 'asc')->orderBy('course_num', 'asc')->get();
        $count = 0;
        foreach ($firstYearProgramCourses as $firstYearProgramCourse) {
            if ($firstYearProgramCourse->course_num[0] != '1') {           // if the first number in course_num is not 1 then remove it from the collection
                $firstYearProgramCourses->forget($count);
            }
            $count++;
        }

        $tempOptionalPriorities = [];
        foreach ($firstYearProgramCourses as $programCourse) {
            $tempOptionalPriorities[$programCourse->course_id] = CourseOptionalPriorities::where('course_id', $programCourse->course_id)->pluck('op_id')->toArray();
        }

        $opFrequencies = [];
        $opFrequenciesSubcategories = [];
        foreach ($tempOptionalPriorities as $courseID => $op_ids) {
            $course_code = Course::where('course_id', $courseID)->pluck('course_code')->first();
            $course_num = Course::where('course_id', $courseID)->pluck('course_num')->first();
            foreach ($op_ids as $op_id) {
                $subcat_id = OptionalPriorities::where('op_id', $op_id)->pluck('subcat_id')->first();
                // add to opFrequencies if not already in array
                if (! array_key_exists($op_id, $opFrequencies)) {
                    $opFrequencies[$op_id]['freq'] = 1;
                    $opFrequencies[$op_id]['title'] = OptionalPriorities::where('op_id', $op_id)->pluck('optional_priority')->first();
                    $opFrequencies[$op_id]['courses'] = [$courseID => $course_code.' '.$course_num];
                    $opFrequencies[$op_id]['subcat_id'] = $subcat_id;
                    $opFrequenciesSubcategories[$subcat_id] = OptionalPrioritySubcategories::where('subcat_id', $subcat_id)->pluck('subcat_name')->first();
                } else {
                    // otherwise increment if key (op_id) in array already
                    $opFrequencies[$op_id]['freq'] += 1;
                    $opFrequencies[$op_id]['courses'] += [$courseID => $course_code.' '.$course_num];
                }
            }
        }
        ksort($opFrequencies);
        ksort($opFrequenciesSubcategories);

        $output = $this->generateHTMLOptionalPriorities($opFrequencies, $opFrequenciesSubcategories);

        return response()->json($output, 200);
    }

    public function getOptionalPrioritiesSecondYear($program_id): JsonResponse
    {
        $secondYearProgramCourses = Course::join('course_programs', 'courses.course_id', '=', 'course_programs.course_id')->where('course_programs.program_id', $program_id)->orderBy('course_code', 'asc')->orderBy('course_num', 'asc')->get();
        $count = 0;
        foreach ($secondYearProgramCourses as $secondYearProgramCourse) {
            if ($secondYearProgramCourse->course_num[0] != '2') {           // if the first number in course_num is not 1 then remove it from the collection
                $secondYearProgramCourses->forget($count);
            }
            $count++;
        }

        $tempOptionalPriorities = [];
        foreach ($secondYearProgramCourses as $programCourse) {
            $tempOptionalPriorities[$programCourse->course_id] = CourseOptionalPriorities::where('course_id', $programCourse->course_id)->pluck('op_id')->toArray();
        }

        $opFrequencies = [];
        $opFrequenciesSubcategories = [];
        foreach ($tempOptionalPriorities as $courseID => $op_ids) {
            $course_code = Course::where('course_id', $courseID)->pluck('course_code')->first();
            $course_num = Course::where('course_id', $courseID)->pluck('course_num')->first();
            foreach ($op_ids as $op_id) {
                $subcat_id = OptionalPriorities::where('op_id', $op_id)->pluck('subcat_id')->first();
                // add to opFrequencies if not already in array
                if (! array_key_exists($op_id, $opFrequencies)) {
                    $opFrequencies[$op_id]['freq'] = 1;
                    $opFrequencies[$op_id]['title'] = OptionalPriorities::where('op_id', $op_id)->pluck('optional_priority')->first();
                    $opFrequencies[$op_id]['courses'] = [$courseID => $course_code.' '.$course_num];
                    $opFrequencies[$op_id]['subcat_id'] = $subcat_id;
                    $opFrequenciesSubcategories[$subcat_id] = OptionalPrioritySubcategories::where('subcat_id', $subcat_id)->pluck('subcat_name')->first();
                } else {
                    // otherwise increment if key (op_id) in array already
                    $opFrequencies[$op_id]['freq'] += 1;
                    $opFrequencies[$op_id]['courses'] += [$courseID => $course_code.' '.$course_num];
                }
            }
        }
        ksort($opFrequencies);
        ksort($opFrequenciesSubcategories);

        $output = $this->generateHTMLOptionalPriorities($opFrequencies, $opFrequenciesSubcategories);

        return response()->json($output, 200);
    }

    public function getOptionalPrioritiesThirdYear($program_id): JsonResponse
    {
        $thirdYearProgramCourses = Course::join('course_programs', 'courses.course_id', '=', 'course_programs.course_id')->where('course_programs.program_id', $program_id)->orderBy('course_code', 'asc')->orderBy('course_num', 'asc')->get();
        $count = 0;
        foreach ($thirdYearProgramCourses as $thirdYearProgramCourse) {
            if ($thirdYearProgramCourse->course_num[0] != '3') {           // if the first number in course_num is not 1 then remove it from the collection
                $thirdYearProgramCourses->forget($count);
            }
            $count++;
        }

        $tempOptionalPriorities = [];
        foreach ($thirdYearProgramCourses as $programCourse) {
            $tempOptionalPriorities[$programCourse->course_id] = CourseOptionalPriorities::where('course_id', $programCourse->course_id)->pluck('op_id')->toArray();
        }

        $opFrequencies = [];
        $opFrequenciesSubcategories = [];
        foreach ($tempOptionalPriorities as $courseID => $op_ids) {
            $course_code = Course::where('course_id', $courseID)->pluck('course_code')->first();
            $course_num = Course::where('course_id', $courseID)->pluck('course_num')->first();
            foreach ($op_ids as $op_id) {
                $subcat_id = OptionalPriorities::where('op_id', $op_id)->pluck('subcat_id')->first();
                // add to opFrequencies if not already in array
                if (! array_key_exists($op_id, $opFrequencies)) {
                    $opFrequencies[$op_id]['freq'] = 1;
                    $opFrequencies[$op_id]['title'] = OptionalPriorities::where('op_id', $op_id)->pluck('optional_priority')->first();
                    $opFrequencies[$op_id]['courses'] = [$courseID => $course_code.' '.$course_num];
                    $opFrequencies[$op_id]['subcat_id'] = $subcat_id;
                    $opFrequenciesSubcategories[$subcat_id] = OptionalPrioritySubcategories::where('subcat_id', $subcat_id)->pluck('subcat_name')->first();
                } else {
                    // otherwise increment if key (op_id) in array already
                    $opFrequencies[$op_id]['freq'] += 1;
                    $opFrequencies[$op_id]['courses'] += [$courseID => $course_code.' '.$course_num];
                }
            }
        }
        ksort($opFrequencies);
        ksort($opFrequenciesSubcategories);

        $output = $this->generateHTMLOptionalPriorities($opFrequencies, $opFrequenciesSubcategories);

        return response()->json($output, 200);
    }

    public function getOptionalPrioritiesFourthYear($program_id): JsonResponse
    {
        $fourthYearProgramCourses = Course::join('course_programs', 'courses.course_id', '=', 'course_programs.course_id')->where('course_programs.program_id', $program_id)->orderBy('course_code', 'asc')->orderBy('course_num', 'asc')->get();
        $count = 0;
        foreach ($fourthYearProgramCourses as $fourthYearProgramCourse) {
            if ($fourthYearProgramCourse->course_num[0] != '4') {           // if the first number in course_num is not 1 then remove it from the collection
                $fourthYearProgramCourses->forget($count);
            }
            $count++;
        }

        $tempOptionalPriorities = [];
        foreach ($fourthYearProgramCourses as $programCourse) {
            $tempOptionalPriorities[$programCourse->course_id] = CourseOptionalPriorities::where('course_id', $programCourse->course_id)->pluck('op_id')->toArray();
        }

        $opFrequencies = [];
        $opFrequenciesSubcategories = [];
        foreach ($tempOptionalPriorities as $courseID => $op_ids) {
            $course_code = Course::where('course_id', $courseID)->pluck('course_code')->first();
            $course_num = Course::where('course_id', $courseID)->pluck('course_num')->first();
            foreach ($op_ids as $op_id) {
                $subcat_id = OptionalPriorities::where('op_id', $op_id)->pluck('subcat_id')->first();
                // add to opFrequencies if not already in array
                if (! array_key_exists($op_id, $opFrequencies)) {
                    $opFrequencies[$op_id]['freq'] = 1;
                    $opFrequencies[$op_id]['title'] = OptionalPriorities::where('op_id', $op_id)->pluck('optional_priority')->first();
                    $opFrequencies[$op_id]['courses'] = [$courseID => $course_code.' '.$course_num];
                    $opFrequencies[$op_id]['subcat_id'] = $subcat_id;
                    $opFrequenciesSubcategories[$subcat_id] = OptionalPrioritySubcategories::where('subcat_id', $subcat_id)->pluck('subcat_name')->first();
                } else {
                    // otherwise increment if key (op_id) in array already
                    $opFrequencies[$op_id]['freq'] += 1;
                    $opFrequencies[$op_id]['courses'] += [$courseID => $course_code.' '.$course_num];
                }
            }
        }
        ksort($opFrequencies);
        ksort($opFrequenciesSubcategories);

        $output = $this->generateHTMLOptionalPriorities($opFrequencies, $opFrequenciesSubcategories);

        return response()->json($output, 200);
    }

    public function getOptionalPrioritiesGraduate($program_id): JsonResponse
    {
        $graduateProgramCourses = Course::join('course_programs', 'courses.course_id', '=', 'course_programs.course_id')->where('course_programs.program_id', $program_id)->orderBy('course_code', 'asc')->orderBy('course_num', 'asc')->get();
        $count = 0;
        foreach ($graduateProgramCourses as $graduateProgramCourse) {
            if ($graduateProgramCourse->course_num[0] != '5' || $graduateProgramCourse->course_num[0] != '6') {           // if the first number in course_num is not 1 then remove it from the collection
                $graduateProgramCourses->forget($count);
            }
            $count++;
        }

        $tempOptionalPriorities = [];
        foreach ($graduateProgramCourses as $programCourse) {
            $tempOptionalPriorities[$programCourse->course_id] = CourseOptionalPriorities::where('course_id', $programCourse->course_id)->pluck('op_id')->toArray();
        }

        $opFrequencies = [];
        $opFrequenciesSubcategories = [];
        foreach ($tempOptionalPriorities as $courseID => $op_ids) {
            $course_code = Course::where('course_id', $courseID)->pluck('course_code')->first();
            $course_num = Course::where('course_id', $courseID)->pluck('course_num')->first();
            foreach ($op_ids as $op_id) {
                $subcat_id = OptionalPriorities::where('op_id', $op_id)->pluck('subcat_id')->first();
                // add to opFrequencies if not already in array
                if (! array_key_exists($op_id, $opFrequencies)) {
                    $opFrequencies[$op_id]['freq'] = 1;
                    $opFrequencies[$op_id]['title'] = OptionalPriorities::where('op_id', $op_id)->pluck('optional_priority')->first();
                    $opFrequencies[$op_id]['courses'] = [$courseID => $course_code.' '.$course_num];
                    $opFrequencies[$op_id]['subcat_id'] = $subcat_id;
                    $opFrequenciesSubcategories[$subcat_id] = OptionalPrioritySubcategories::where('subcat_id', $subcat_id)->pluck('subcat_name')->first();
                } else {
                    // otherwise increment if key (op_id) in array already
                    $opFrequencies[$op_id]['freq'] += 1;
                    $opFrequencies[$op_id]['courses'] += [$courseID => $course_code.' '.$course_num];
                }
            }
        }
        ksort($opFrequencies);
        ksort($opFrequenciesSubcategories);

        $output = $this->generateHTMLOptionalPriorities($opFrequencies, $opFrequenciesSubcategories);

        return response()->json($output, 200);
    }

    public function generateHTMLOptionalPriorities($opFrequencies, $opFrequenciesSubcategories)
    {
        $output = '';

        if (! count($opFrequencies) < 1) {
            $output .= '<table class="table table-light table-bordered"><tbody><tr class="table-primary"><th>Strategic Priorities</th><th>Courses</th></tr>';
            // loop through categories and add them to the output
            foreach ($opFrequenciesSubcategories as $subcat_id => $opFrequenciesSubcategory) {
                $output .= '<tr class="table-secondary"><td colspan="2"><b>'.$opFrequenciesSubcategory.'</b></td></tr>';
                // loop through the optional priorities and add them to the output
                foreach ($opFrequencies as $op_id => $opFrequency) {
                    if ($subcat_id == $opFrequency['subcat_id']) {
                        $output .= '<tr><td>'.$opFrequency['title'].'</td><td class="text-center" data-toggle="tooltip" data-html="true" data-bs-placement="right" title="';
                        foreach ($opFrequency['courses'] as $course) {
                            $output .= '<li>'.$course.'</li>';
                        }
                        $output .= '">'.$opFrequency['freq'].'</td></tr>';
                    }
                }
            }
            $output .= '</tbody></table>';
        } else {
            $output = '<div class="alert alert-warning wizard"><i class="bi bi-exclamation-circle-fill"></i>There are no strategic priorities for the courses belonging to this program, or there are no courses matching the criteria.</div>';
        }

        return $output;
    }

    public function getAssessmentMethods($program_id): JsonResponse
    {
        $program = Program::where('program_id', $program_id)->first();
        // get all the courses this program belongs to
        $programCourses = $program->courses;

        $assessmentMethods = [];
        foreach ($programCourses as $programCourse) {
            array_push($assessmentMethods, AssessmentMethod::where('course_id', $programCourse->course_id)->pluck('a_method'));
        }
        $allAM = [];
        foreach ($assessmentMethods as $ams) {
            foreach ($ams as $am) {
                array_push($allAM, ucwords($am));
            }
        }
        // Get frequencies for all assessment methods
        $amFrequencies = [];
        if (count($allAM) >= 1) {
            for ($i = 0; $i < count($allAM); $i++) {
                if (array_key_exists($allAM[$i], $amFrequencies)) {
                    $amFrequencies[$allAM[$i]] += 1;
                } else {
                    $amFrequencies += [$allAM[$i] => 1];
                }
            }

            // Special Case (Might be removed in the future)
            // if there exists 'Final' and 'Final Exam' then combine them into 'Final Exam'
            if (array_key_exists('Final Exam', $amFrequencies) && array_key_exists('Final', $amFrequencies)) {
                $amFrequencies['Final Exam'] += $amFrequencies['Final'];
                unset($amFrequencies['Final']);
            }
        }

        return response()->json($amFrequencies, 200);
    }

    public function getAssessmentMethodsFirstYear($program_id): JsonResponse
    {
        // get all the courses this program belongs to
        $firstYearProgramCourses = Course::join('course_programs', 'courses.course_id', '=', 'course_programs.course_id')->where('course_programs.program_id', $program_id)->get();
        $count = 0;
        foreach ($firstYearProgramCourses as $firstYearProgramCourse) {
            if ($firstYearProgramCourse->course_num[0] != '1') {           // if the first number in course_num is not 1 then remove it from the collection
                $firstYearProgramCourses->forget($count);
            }
            $count++;
        }

        $assessmentMethods = [];
        foreach ($firstYearProgramCourses as $programCourse) {
            array_push($assessmentMethods, AssessmentMethod::where('course_id', $programCourse->course_id)->pluck('a_method'));
        }
        $allAM = [];
        foreach ($assessmentMethods as $ams) {
            foreach ($ams as $am) {
                array_push($allAM, ucwords($am));
            }
        }
        // Get frequencies for all assessment methods
        $amFrequencies = [];
        if (count($allAM) >= 1) {
            for ($i = 0; $i < count($allAM); $i++) {
                if (array_key_exists($allAM[$i], $amFrequencies)) {
                    $amFrequencies[$allAM[$i]] += 1;
                } else {
                    $amFrequencies += [$allAM[$i] => 1];
                }
            }

            // Special Case
            // if there exists 'Final' and 'Final Exam' then combine them into 'Final Exam'
            if (array_key_exists('Final Exam', $amFrequencies) && array_key_exists('Final', $amFrequencies)) {
                $amFrequencies['Final Exam'] += $amFrequencies['Final'];
                unset($amFrequencies['Final']);
            }
        }

        return response()->json($amFrequencies, 200);
    }

    public function getAssessmentMethodsSecondYear($program_id): JsonResponse
    {
        // get all the courses this program belongs to
        $secondYearProgramCourses = Course::join('course_programs', 'courses.course_id', '=', 'course_programs.course_id')->where('course_programs.program_id', $program_id)->get();
        $count = 0;
        foreach ($secondYearProgramCourses as $secondYearProgramCourse) {
            if ($secondYearProgramCourse->course_num[0] != '2') {           // if the first number in course_num is not 1 then remove it from the collection
                $secondYearProgramCourses->forget($count);
            }
            $count++;
        }

        $assessmentMethods = [];
        foreach ($secondYearProgramCourses as $programCourse) {
            array_push($assessmentMethods, AssessmentMethod::where('course_id', $programCourse->course_id)->pluck('a_method'));
        }
        $allAM = [];
        foreach ($assessmentMethods as $ams) {
            foreach ($ams as $am) {
                array_push($allAM, ucwords($am));
            }
        }
        // Get frequencies for all assessment methods
        $amFrequencies = [];
        if (count($allAM) >= 1) {
            for ($i = 0; $i < count($allAM); $i++) {
                if (array_key_exists($allAM[$i], $amFrequencies)) {
                    $amFrequencies[$allAM[$i]] += 1;
                } else {
                    $amFrequencies += [$allAM[$i] => 1];
                }
            }

            // Special Case
            // if there exists 'Final' and 'Final Exam' then combine them into 'Final Exam'
            if (array_key_exists('Final Exam', $amFrequencies) && array_key_exists('Final', $amFrequencies)) {
                $amFrequencies['Final Exam'] += $amFrequencies['Final'];
                unset($amFrequencies['Final']);
            }
        }

        return response()->json($amFrequencies, 200);
    }

    public function getAssessmentMethodsThirdYear($program_id): JsonResponse
    {
        // get all the courses this program belongs to
        $thirdYearProgramCourses = Course::join('course_programs', 'courses.course_id', '=', 'course_programs.course_id')->where('course_programs.program_id', $program_id)->get();
        $count = 0;
        foreach ($thirdYearProgramCourses as $thirdYearProgramCourse) {
            if ($thirdYearProgramCourse->course_num[0] != '3') {           // if the first number in course_num is not 1 then remove it from the collection
                $thirdYearProgramCourses->forget($count);
            }
            $count++;
        }

        $assessmentMethods = [];
        foreach ($thirdYearProgramCourses as $programCourse) {
            array_push($assessmentMethods, AssessmentMethod::where('course_id', $programCourse->course_id)->pluck('a_method'));
        }
        $allAM = [];
        foreach ($assessmentMethods as $ams) {
            foreach ($ams as $am) {
                array_push($allAM, ucwords($am));
            }
        }
        // Get frequencies for all assessment methods
        $amFrequencies = [];
        if (count($allAM) >= 1) {
            for ($i = 0; $i < count($allAM); $i++) {
                if (array_key_exists($allAM[$i], $amFrequencies)) {
                    $amFrequencies[$allAM[$i]] += 1;
                } else {
                    $amFrequencies += [$allAM[$i] => 1];
                }
            }

            // Special Case
            // if there exists 'Final' and 'Final Exam' then combine them into 'Final Exam'
            if (array_key_exists('Final Exam', $amFrequencies) && array_key_exists('Final', $amFrequencies)) {
                $amFrequencies['Final Exam'] += $amFrequencies['Final'];
                unset($amFrequencies['Final']);
            }
        }

        return response()->json($amFrequencies, 200);
    }

    public function getAssessmentMethodsFourthYear($program_id): JsonResponse
    {
        // get all the courses this program belongs to
        $fourthYearProgramCourses = Course::join('course_programs', 'courses.course_id', '=', 'course_programs.course_id')->where('course_programs.program_id', $program_id)->get();
        $count = 0;
        foreach ($fourthYearProgramCourses as $fourthYearProgramCourse) {
            if ($fourthYearProgramCourse->course_num[0] != '4') {           // if the first number in course_num is not 1 then remove it from the collection
                $fourthYearProgramCourses->forget($count);
            }
            $count++;
        }

        $assessmentMethods = [];
        foreach ($fourthYearProgramCourses as $programCourse) {
            array_push($assessmentMethods, AssessmentMethod::where('course_id', $programCourse->course_id)->pluck('a_method'));
        }
        $allAM = [];
        foreach ($assessmentMethods as $ams) {
            foreach ($ams as $am) {
                array_push($allAM, ucwords($am));
            }
        }
        // Get frequencies for all assessment methods
        $amFrequencies = [];
        if (count($allAM) >= 1) {
            for ($i = 0; $i < count($allAM); $i++) {
                if (array_key_exists($allAM[$i], $amFrequencies)) {
                    $amFrequencies[$allAM[$i]] += 1;
                } else {
                    $amFrequencies += [$allAM[$i] => 1];
                }
            }

            // Special Case
            // if there exists 'Final' and 'Final Exam' then combine them into 'Final Exam'
            if (array_key_exists('Final Exam', $amFrequencies) && array_key_exists('Final', $amFrequencies)) {
                $amFrequencies['Final Exam'] += $amFrequencies['Final'];
                unset($amFrequencies['Final']);
            }
        }

        return response()->json($amFrequencies, 200);
    }

    public function getAssessmentMethodsGraduate($program_id): JsonResponse
    {
        // get all the courses this program belongs to
        $GraduateProgramCourses = Course::join('course_programs', 'courses.course_id', '=', 'course_programs.course_id')->where('course_programs.program_id', $program_id)->get();
        $count = 0;
        foreach ($GraduateProgramCourses as $GraduateProgramCourse) {
            if ($GraduateProgramCourse->course_num[0] != '5' || $GraduateProgramCourse->course_num[0] != '6') {           // if the first number in course_num is not 1 then remove it from the collection
                $GraduateProgramCourses->forget($count);
            }
            $count++;
        }

        $assessmentMethods = [];
        foreach ($GraduateProgramCourses as $programCourse) {
            array_push($assessmentMethods, AssessmentMethod::where('course_id', $programCourse->course_id)->pluck('a_method'));
        }
        $allAM = [];
        foreach ($assessmentMethods as $ams) {
            foreach ($ams as $am) {
                array_push($allAM, ucwords($am));
            }
        }
        // Get frequencies for all assessment methods
        $amFrequencies = [];
        if (count($allAM) >= 1) {
            for ($i = 0; $i < count($allAM); $i++) {
                if (array_key_exists($allAM[$i], $amFrequencies)) {
                    $amFrequencies[$allAM[$i]] += 1;
                } else {
                    $amFrequencies += [$allAM[$i] => 1];
                }
            }

            // Special Case
            // if there exists 'Final' and 'Final Exam' then combine them into 'Final Exam'
            if (array_key_exists('Final Exam', $amFrequencies) && array_key_exists('Final', $amFrequencies)) {
                $amFrequencies['Final Exam'] += $amFrequencies['Final'];
                unset($amFrequencies['Final']);
            }
        }

        return response()->json($amFrequencies, 200);
    }

    public function getLearningActivities($program_id): JsonResponse
    {
        $program = Program::where('program_id', $program_id)->first();
        // get all the courses this program belongs to
        $programCourses = $program->courses;
        // Get frequencies for all learning activities
        $learningActivities = [];
        foreach ($programCourses as $programCourse) {
            array_push($learningActivities, LearningActivity::where('course_id', $programCourse->course_id)->pluck('l_activity'));
        }
        $allLA = [];
        foreach ($learningActivities as $lAS) {
            foreach ($lAS as $la) {
                array_push($allLA, ucwords($la));
            }
        }
        // Get frequencies for all Learning Activities
        $laFrequencies = [];
        if (count($allLA) >= 1) {
            for ($i = 0; $i < count($allLA); $i++) {
                if (array_key_exists($allLA[$i], $laFrequencies)) {
                    $laFrequencies[$allLA[$i]] += 1;
                } else {
                    $laFrequencies += [$allLA[$i] => 1];
                }
            }
        }

        return response()->json($laFrequencies, 200);
    }

    public function getFirstYearLearningActivities($program_id): JsonResponse
    {
        $firstYearProgramCourses = Course::join('course_programs', 'courses.course_id', '=', 'course_programs.course_id')->where('course_programs.program_id', $program_id)->get();
        $count = 0;
        foreach ($firstYearProgramCourses as $firstYearProgramCourse) {
            if ($firstYearProgramCourse->course_num[0] != '1') {           // if the first number in course_num is not 1 then remove it from the collection
                $firstYearProgramCourses->forget($count);
            }
            $count++;
        }
        // Get frequencies for all learning activities
        $learningActivities = [];
        foreach ($firstYearProgramCourses as $programCourse) {
            array_push($learningActivities, LearningActivity::where('course_id', $programCourse->course_id)->pluck('l_activity'));
        }
        $allLA = [];
        foreach ($learningActivities as $lAS) {
            foreach ($lAS as $la) {
                array_push($allLA, ucwords($la));
            }
        }
        // Get frequencies for all Learning Activities
        $laFrequencies = [];
        if (count($allLA) >= 1) {
            for ($i = 0; $i < count($allLA); $i++) {
                if (array_key_exists($allLA[$i], $laFrequencies)) {
                    $laFrequencies[$allLA[$i]] += 1;
                } else {
                    $laFrequencies += [$allLA[$i] => 1];
                }
            }
        }

        return response()->json($laFrequencies, 200);
    }

    public function getSecondYearLearningActivities($program_id): JsonResponse
    {
        $secondYearProgramCourses = Course::join('course_programs', 'courses.course_id', '=', 'course_programs.course_id')->where('course_programs.program_id', $program_id)->get();
        $count = 0;
        foreach ($secondYearProgramCourses as $secondYearProgramCourse) {
            if ($secondYearProgramCourse->course_num[0] != '2') {           // if the first number in course_num is not 1 then remove it from the collection
                $secondYearProgramCourses->forget($count);
            }
            $count++;
        }
        // Get frequencies for all learning activities
        $learningActivities = [];
        foreach ($secondYearProgramCourses as $programCourse) {
            array_push($learningActivities, LearningActivity::where('course_id', $programCourse->course_id)->pluck('l_activity'));
        }
        $allLA = [];
        foreach ($learningActivities as $lAS) {
            foreach ($lAS as $la) {
                array_push($allLA, ucwords($la));
            }
        }
        // Get frequencies for all Learning Activities
        $laFrequencies = [];
        if (count($allLA) >= 1) {
            for ($i = 0; $i < count($allLA); $i++) {
                if (array_key_exists($allLA[$i], $laFrequencies)) {
                    $laFrequencies[$allLA[$i]] += 1;
                } else {
                    $laFrequencies += [$allLA[$i] => 1];
                }
            }
        }

        return response()->json($laFrequencies, 200);
    }

    public function getThirdYearLearningActivities($program_id): JsonResponse
    {
        $thirdYearProgramCourses = Course::join('course_programs', 'courses.course_id', '=', 'course_programs.course_id')->where('course_programs.program_id', $program_id)->get();
        $count = 0;
        foreach ($thirdYearProgramCourses as $thirdYearProgramCourse) {
            if ($thirdYearProgramCourse->course_num[0] != '3') {           // if the first number in course_num is not 1 then remove it from the collection
                $thirdYearProgramCourses->forget($count);
            }
            $count++;
        }
        // Get frequencies for all learning activities
        $learningActivities = [];
        foreach ($thirdYearProgramCourses as $programCourse) {
            array_push($learningActivities, LearningActivity::where('course_id', $programCourse->course_id)->pluck('l_activity'));
        }
        $allLA = [];
        foreach ($learningActivities as $lAS) {
            foreach ($lAS as $la) {
                array_push($allLA, ucwords($la));
            }
        }
        // Get frequencies for all Learning Activities
        $laFrequencies = [];
        if (count($allLA) >= 1) {
            for ($i = 0; $i < count($allLA); $i++) {
                if (array_key_exists($allLA[$i], $laFrequencies)) {
                    $laFrequencies[$allLA[$i]] += 1;
                } else {
                    $laFrequencies += [$allLA[$i] => 1];
                }
            }
        }

        return response()->json($laFrequencies, 200);
    }

    public function getFourthYearLearningActivities($program_id): JsonResponse
    {
        $fourthYearProgramCourses = Course::join('course_programs', 'courses.course_id', '=', 'course_programs.course_id')->where('course_programs.program_id', $program_id)->get();
        $count = 0;
        foreach ($fourthYearProgramCourses as $fourthYearProgramCourse) {
            if ($fourthYearProgramCourse->course_num[0] != '4') {           // if the first number in course_num is not 1 then remove it from the collection
                $fourthYearProgramCourses->forget($count);
            }
            $count++;
        }
        // Get frequencies for all learning activities
        $learningActivities = [];
        foreach ($fourthYearProgramCourses as $programCourse) {
            array_push($learningActivities, LearningActivity::where('course_id', $programCourse->course_id)->pluck('l_activity'));
        }
        $allLA = [];
        foreach ($learningActivities as $lAS) {
            foreach ($lAS as $la) {
                array_push($allLA, ucwords($la));
            }
        }
        // Get frequencies for all Learning Activities
        $laFrequencies = [];
        if (count($allLA) >= 1) {
            for ($i = 0; $i < count($allLA); $i++) {
                if (array_key_exists($allLA[$i], $laFrequencies)) {
                    $laFrequencies[$allLA[$i]] += 1;
                } else {
                    $laFrequencies += [$allLA[$i] => 1];
                }
            }
        }

        return response()->json($laFrequencies, 200);
    }

    public function getGraduateLearningActivities($program_id): JsonResponse
    {
        $graduateProgramCourses = Course::join('course_programs', 'courses.course_id', '=', 'course_programs.course_id')->where('course_programs.program_id', $program_id)->get();
        $count = 0;
        foreach ($graduateProgramCourses as $graduateProgramCourse) {
            if ($graduateProgramCourse->course_num[0] != '5' || $graduateProgramCourse->course_num[0] != '6') {           // if the first number in course_num is not 1 then remove it from the collection
                $graduateProgramCourses->forget($count);
            }
            $count++;
        }
        // Get frequencies for all learning activities
        $learningActivities = [];
        foreach ($graduateProgramCourses as $programCourse) {
            array_push($learningActivities, LearningActivity::where('course_id', $programCourse->course_id)->pluck('l_activity'));
        }
        $allLA = [];
        foreach ($learningActivities as $lAS) {
            foreach ($lAS as $la) {
                array_push($allLA, ucwords($la));
            }
        }
        // Get frequencies for all Learning Activities
        $laFrequencies = [];
        if (count($allLA) >= 1) {
            for ($i = 0; $i < count($allLA); $i++) {
                if (array_key_exists($allLA[$i], $laFrequencies)) {
                    $laFrequencies[$allLA[$i]] += 1;
                } else {
                    $laFrequencies += [$allLA[$i] => 1];
                }
            }
        }

        return response()->json($laFrequencies, 200);
    }

    public function getCoursesOutcomes($coursesOutcomes, $programCourses)
    {
        // get all CLO's for each course in the program
        foreach ($programCourses as $programCourse) {
            $learningOutcomes = $programCourse->learningOutcomes;
            $coursesOutcomes[$programCourse->course_id] = $learningOutcomes;
        }

        return $coursesOutcomes;
    }

    public function getOutcomeMaps($allPLO, $coursesOutcomes, $arr)
    {
        // retrieves all the outcome mapping values for every clo and plo
        $count = 0;
        foreach ($allPLO as $plo) {
            // loop through CLOs to get map scale value
            foreach ($coursesOutcomes as $clos) {
                foreach ($clos as $clo) {
                    // Check if record exists in the db
                    if (! OutcomeMap::where(['l_outcome_id' => $clo->l_outcome_id, 'pl_outcome_id' => $plo->pl_outcome_id])->exists()) {
                        // if nothing is found then do nothing
                        // else if record (mapping_scale_id) is found then store it in the array
                    } else {
                        $count++;
                        $mapScaleValue = OutcomeMap::where(['l_outcome_id' => $clo->l_outcome_id, 'pl_outcome_id' => $plo->pl_outcome_id])->value('map_scale_id');
                        $arr[$count] = [
                            'pl_outcome_id' => $plo->pl_outcome_id,
                            'course_id' => $clo->course_id,
                            'map_scale_id' => $mapScaleValue,
                            'l_outcome_id' => $clo->l_outcome_id,
                        ];
                    }
                }
            }
        }

        return $arr;
    }

    public function createCDFArray($arr, $store)
    {
        // Initialize array for each pl_outcome_id with the value of null
        foreach ($arr as $ar) {
            $store[$ar['pl_outcome_id']] = null;
        }
        // Initialize Array for Storing
        foreach ($arr as $ar) {
            if ($store[$ar['pl_outcome_id']] == null || $store[$ar['pl_outcome_id']] == $ar['pl_outcome_id']) {
                $store[$ar['pl_outcome_id']] = [
                    $ar['course_id'] => [
                    ],
                ];
            } else {
                $store[$ar['pl_outcome_id']][$ar['course_id']] = [];
                $store[$ar['pl_outcome_id']][$ar['course_id']]['frequencies'] = [];
            }
        }

        return $store;
    }

    public function frequencyDistribution($arr, $store)
    {
        //Initialize Array for Frequency Distribution
        $freq = [];
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
        foreach ($freq as $plOutcomeId => $dist) {
            foreach ($dist as $courseId => $d) {
                $weight = 0;
                $tieResults = [];
                $id = null;
                //count the number of times a mapping scales appears for a program learning outcome
                foreach ($d as $ms_Id => $mapScaleWeight) {
                    //check if the current ($mapScaleWeight) > than the previously stored value
                    if ($weight < $mapScaleWeight) {
                        $weight = $mapScaleWeight;
                        $id = $ms_Id;
                    }
                }
                // Check if the largest weighted value ties with another value
                foreach ($d as $ms_Id => $mapScaleWeight) {
                    if ($weight == $mapScaleWeight && $id != $ms_Id) {    // if a tie is found store the mapping scale values (I.e: I, A, D) in and array
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
                            $stringResults .= ''.MappingScale::where('map_scale_id', $tieResult)->value('abbreviation').' / ';
                        } else {
                            $stringResults .= ''.MappingScale::where('map_scale_id', $tieResult)->value('abbreviation');
                        }
                    }
                    // Store the results array as the map_scale_value key
                    $store[$plOutcomeId][$courseId] += [
                        'map_scale_abv' => $stringResults,
                    ];
                    // Store a new array to be able to determine if the mapping scale value comes from the result of a tie
                    $store[$plOutcomeId][$courseId] += [
                        'map_scale_id_tie' => true,
                    ];
                    // Store the frequencies
                    $store[$plOutcomeId][$courseId]['frequencies'] = $freq[$plOutcomeId][$courseId];
                } else {
                    // If no tie is present, store the strongest weighted map_scale_value
                    $store[$plOutcomeId][$courseId] = [
                        'map_scale_id' => array_search($weight, $d),
                    ];
                    $store[$plOutcomeId][$courseId] += [
                        'map_scale_abv' => MappingScale::where('map_scale_id', array_search($weight, $d))->value('abbreviation'),
                    ];
                    // Store the frequencies
                    $store[$plOutcomeId][$courseId]['frequencies'] = $freq[$plOutcomeId][$courseId];
                }
            }
        }

        return $store;
    }

    public function replaceIdsWithAbv($store, $arr)
    {
        //Initialize Array for Frequency Distribution
        $freq = [];
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
        foreach ($freq as $plOutcomeId => $dist) {
            foreach ($dist as $courseId => $d) {
                // Store the frequencies
                $store[$plOutcomeId][$courseId]['frequencies'] = $freq[$plOutcomeId][$courseId];
            }
        }

        return $store;
    }

    public function assignColours($store)
    {
        // Assign a colour to store based
        foreach ($store as $plOutcomeId => $s) {
            foreach ($s as $courseId => $msv) {
                // If a tie exists assign it the colour white
                if (array_key_exists('map_scale_id_tie', $msv)) {
                    $mapScaleColour = '#FFFFFF';
                    $store[$plOutcomeId][$courseId] += [
                        'colour' => $mapScaleColour,
                    ];
                } else {
                    // Search for the mapping scale colour in the db, then assign it to the array
                    $mapScaleColour = MappingScale::where('map_scale_id', $msv['map_scale_id'])->value('colour');

                    if ($mapScaleColour == null) {
                        $mapScaleColour = '#FFFFFF';
                    }
                    $store[$plOutcomeId][$courseId] += [
                        'colour' => $mapScaleColour,
                    ];
                }
            }
        }

        return $store;
    }

    private $numCatUsed;

    public function getNumCatUsed($ploProgramCategories)
    {
        // returns the number of Categories that contain at least one PLO
        $numCatUsed = 0;
        $uniqueCategories = [];
        foreach ($ploProgramCategories as $ploInCategory) {
            if (! in_array($ploInCategory->plo_category_id, $uniqueCategories)) {
                $uniqueCategories[] += $ploInCategory->plo_category_id;
                $numCatUsed++;
            }
        }
        $this->numCatUsed = $numCatUsed;
    }

    private $plosPerCategory;

    public function getPlosPerCategory($ploProgramCategories)
    {
        // plosPerCategory returns the number of plo's belonging to each category
        // used for setting the colspan in the view
        $plosPerCategory = [];
        foreach ($ploProgramCategories as $ploCategory) {
            $plosPerCategory[$ploCategory->plo_category_id] = 0;
        }
        foreach ($ploProgramCategories as $ploCategory) {
            $plosPerCategory[$ploCategory->plo_category_id] += 1;
        }
        $this->plosPerCategory = $plosPerCategory;
    }

    private $hasUncategorized;

    public function getHasUncategorized($plos)
    {
        // returns true if there exists a plo without a category
        $hasUncategorized = false;
        foreach ($plos as $plo) {
            if ($plo->plo_category == null) {
                $hasUncategorized = true;
            }
        }
        $this->hasUncategorized = $hasUncategorized;
    }

    private $numUncategorizedPLOS;

    public function getNumUncategorizedPLOS($allPLO)
    {
        // Used for setting colspan in view
        $numUncategorizedPLOS = 0;
        foreach ($allPLO as $plo) {
            if ($plo->plo_category_id == null) {
                $numUncategorizedPLOS++;
            }
        }
        $this->numUncategorizedPLOS = $numUncategorizedPLOS;
    }

    // called when requested by ajax on step 4
    public function getCourses($program_id): JsonResponse
    {
        $program = Program::find($program_id);
        $ploCount = ProgramLearningOutcome::where('program_id', $program_id)->count();
        // get all the courses this program belongs to
        $programCourses = $program->courses()->orderBy('course_code', 'asc')->orderBy('course_num', 'asc')->get();
        // get all categories for program
        $ploCategories = PLOCategory::where('program_id', $program_id)->get();
        // get plo categories for program
        $ploProgramCategories = PLOCategory::where('p_l_o_categories.program_id', $program_id)->join('program_learning_outcomes', 'p_l_o_categories.plo_category_id', '=', 'program_learning_outcomes.plo_category_id')->get();
        // get plo's for the program
        $plos = DB::table('program_learning_outcomes')->leftJoin('p_l_o_categories', 'program_learning_outcomes.plo_category_id', '=', 'p_l_o_categories.plo_category_id')->where('program_learning_outcomes.program_id', $program_id)->get();
        // get all plo's
        $allPLO = ProgramLearningOutcome::where('program_id', $program_id)->get();

        // set global variables
        $this->getHasUncategorized($plos);
        $this->getNumCatUsed($ploProgramCategories);
        $this->getPlosPerCategory($ploProgramCategories);
        $this->getNumUncategorizedPLOS($allPLO);

        // All Courses Frequency Distribution
        $coursesOutcomes = [];
        $coursesOutcomes = $this->getCoursesOutcomes($coursesOutcomes, $programCourses);
        $arr = [];
        $arr = $this->getOutcomeMaps($allPLO, $coursesOutcomes, $arr);
        $store = [];
        $store = $this->createCDFArray($arr, $store);
        $store = $this->frequencyDistribution($arr, $store);
        $store = $this->replaceIdsWithAbv($store, $arr);
        $store = $this->assignColours($store);

        $output = $this->generateHTML($programCourses, $ploCount, $plos, $ploCategories, $ploProgramCategories, $store);

        return response()->json($output, 200);
    }

    public function getRequiredCourses($program_id): JsonResponse
    {
        $ploCount = ProgramLearningOutcome::where('program_id', $program_id)->count();
        // get all of the required courses this program belongs to
        $requiredProgramCourses = Course::join('course_programs', 'courses.course_id', '=', 'course_programs.course_id')->where('course_programs.program_id', $program_id)->where('course_programs.course_required', 1)->orderBy('course_code', 'asc')->orderBy('course_num', 'asc')->get();
        // get all categories for program
        $ploCategories = PLOCategory::where('program_id', $program_id)->get();
        // get plo categories for program
        $ploProgramCategories = PLOCategory::where('p_l_o_categories.program_id', $program_id)->join('program_learning_outcomes', 'p_l_o_categories.plo_category_id', '=', 'program_learning_outcomes.plo_category_id')->get();
        // get plo's for the program
        $plos = DB::table('program_learning_outcomes')->leftJoin('p_l_o_categories', 'program_learning_outcomes.plo_category_id', '=', 'p_l_o_categories.plo_category_id')->where('program_learning_outcomes.program_id', $program_id)->get();
        // get all plo's
        $allPLO = ProgramLearningOutcome::where('program_id', $program_id)->get();

        // set global variables
        $this->getHasUncategorized($plos);
        $this->getNumCatUsed($ploProgramCategories);
        $this->getPlosPerCategory($ploProgramCategories);
        $this->getNumUncategorizedPLOS($allPLO);

        // Required Courses Frequency Distribution
        $coursesOutcomes = [];
        $coursesOutcomes = $this->getCoursesOutcomes($coursesOutcomes, $requiredProgramCourses);
        $arrRequired = [];
        $arrRequired = $this->getOutcomeMaps($allPLO, $coursesOutcomes, $arrRequired);
        $storeRequired = [];
        $storeRequired = $this->createCDFArray($arrRequired, $storeRequired);
        $storeRequired = $this->frequencyDistribution($arrRequired, $storeRequired);
        $storeRequired = $this->replaceIdsWithAbv($storeRequired, $arrRequired);
        $storeRequired = $this->assignColours($storeRequired);

        $output = $this->generateHTML($requiredProgramCourses, $ploCount, $plos, $ploCategories, $ploProgramCategories, $storeRequired);

        return response()->json($output, 200);
    }

    public function getNonRequiredCourses($program_id): JsonResponse
    {
        $ploCount = ProgramLearningOutcome::where('program_id', $program_id)->count();
        // get all of the non-required courses this program belongs to
        $nonRequiredProgramCourses = Course::join('course_programs', 'courses.course_id', '=', 'course_programs.course_id')->where('course_programs.program_id', $program_id)->where('course_programs.course_required', 0)->orderBy('course_code', 'asc')->orderBy('course_num', 'asc')->get();
        // get all categories for program
        $ploCategories = PLOCategory::where('program_id', $program_id)->get();
        // get plo categories for program
        $ploProgramCategories = PLOCategory::where('p_l_o_categories.program_id', $program_id)->join('program_learning_outcomes', 'p_l_o_categories.plo_category_id', '=', 'program_learning_outcomes.plo_category_id')->get();
        // get plo's for the program
        $plos = DB::table('program_learning_outcomes')->leftJoin('p_l_o_categories', 'program_learning_outcomes.plo_category_id', '=', 'p_l_o_categories.plo_category_id')->where('program_learning_outcomes.program_id', $program_id)->get();
        // get all plo's
        $allPLO = ProgramLearningOutcome::where('program_id', $program_id)->get();

        // set global variables
        $this->getHasUncategorized($plos);
        $this->getNumCatUsed($ploProgramCategories);
        $this->getPlosPerCategory($ploProgramCategories);
        $this->getNumUncategorizedPLOS($allPLO);

        // Non Required Courses Frequency Distribution
        $coursesOutcomes = [];
        $coursesOutcomes = $this->getCoursesOutcomes($coursesOutcomes, $nonRequiredProgramCourses);
        $arrNonRequired = [];
        $arrNonRequired = $this->getOutcomeMaps($allPLO, $coursesOutcomes, $arrNonRequired);
        $storeNonRequired = [];
        $storeNonRequired = $this->createCDFArray($arrNonRequired, $storeNonRequired);
        $storeNonRequired = $this->frequencyDistribution($arrNonRequired, $storeNonRequired);
        $storeNonRequired = $this->replaceIdsWithAbv($storeNonRequired, $arrNonRequired);
        $storeNonRequired = $this->assignColours($storeNonRequired);

        $output = $this->generateHTML($nonRequiredProgramCourses, $ploCount, $plos, $ploCategories, $ploProgramCategories, $storeNonRequired);

        return response()->json($output, 200);
    }

    public function getFirstCourses($program_id): JsonResponse
    {
        $ploCount = ProgramLearningOutcome::where('program_id', $program_id)->count();
        $firstYearProgramCourses = Course::join('course_programs', 'courses.course_id', '=', 'course_programs.course_id')->where('course_programs.program_id', $program_id)->orderBy('course_code', 'asc')->orderBy('course_num', 'asc')->get();
        $count = 0;
        foreach ($firstYearProgramCourses as $firstYearProgramCourse) {
            if ($firstYearProgramCourse->course_num[0] != '1') {           // if the first number in course_num is not 1 then remove it from the collection
                $firstYearProgramCourses->forget($count);
            }
            $count++;
        }
        // get all categories for program
        $ploCategories = PLOCategory::where('program_id', $program_id)->get();
        // get plo categories for program
        $ploProgramCategories = PLOCategory::where('p_l_o_categories.program_id', $program_id)->join('program_learning_outcomes', 'p_l_o_categories.plo_category_id', '=', 'program_learning_outcomes.plo_category_id')->get();
        // get plo's for the program
        $plos = DB::table('program_learning_outcomes')->leftJoin('p_l_o_categories', 'program_learning_outcomes.plo_category_id', '=', 'p_l_o_categories.plo_category_id')->where('program_learning_outcomes.program_id', $program_id)->get();
        // get all plo's
        $allPLO = ProgramLearningOutcome::where('program_id', $program_id)->get();

        // set global variables
        $this->getHasUncategorized($plos);
        $this->getNumCatUsed($ploProgramCategories);
        $this->getPlosPerCategory($ploProgramCategories);
        $this->getNumUncategorizedPLOS($allPLO);

        // First Year Courses Frequency Distribution
        $coursesOutcomes = [];
        $coursesOutcomes = $this->getCoursesOutcomes($coursesOutcomes, $firstYearProgramCourses);
        $arrFirst = [];
        $arrFirst = $this->getOutcomeMaps($allPLO, $coursesOutcomes, $arrFirst);
        $storeFirst = [];
        $storeFirst = $this->createCDFArray($arrFirst, $storeFirst);
        $storeFirst = $this->frequencyDistribution($arrFirst, $storeFirst);
        $storeFirst = $this->replaceIdsWithAbv($storeFirst, $arrFirst);
        $storeFirst = $this->assignColours($storeFirst);

        $output = $this->generateHTML($firstYearProgramCourses, $ploCount, $plos, $ploCategories, $ploProgramCategories, $storeFirst);

        return response()->json($output, 200);
    }

    public function getSecondCourses($program_id): JsonResponse
    {
        $ploCount = ProgramLearningOutcome::where('program_id', $program_id)->count();
        $secondYearProgramCourses = Course::join('course_programs', 'courses.course_id', '=', 'course_programs.course_id')->where('course_programs.program_id', $program_id)->orderBy('course_code', 'asc')->orderBy('course_num', 'asc')->get();
        $count = 0;
        foreach ($secondYearProgramCourses as $secondYearProgramCourse) {
            if ($secondYearProgramCourse->course_num[0] != '2') {           // if the first number in course_num is not 2 then remove it from the collection
                $secondYearProgramCourses->forget($count);
            }
            $count++;
        }
        // get all categories for program
        $ploCategories = PLOCategory::where('program_id', $program_id)->get();
        // get plo categories for program
        $ploProgramCategories = PLOCategory::where('p_l_o_categories.program_id', $program_id)->join('program_learning_outcomes', 'p_l_o_categories.plo_category_id', '=', 'program_learning_outcomes.plo_category_id')->get();
        // get plo's for the program
        $plos = DB::table('program_learning_outcomes')->leftJoin('p_l_o_categories', 'program_learning_outcomes.plo_category_id', '=', 'p_l_o_categories.plo_category_id')->where('program_learning_outcomes.program_id', $program_id)->get();
        // get all plo's
        $allPLO = ProgramLearningOutcome::where('program_id', $program_id)->get();

        // set global variables
        $this->getHasUncategorized($plos);
        $this->getNumCatUsed($ploProgramCategories);
        $this->getPlosPerCategory($ploProgramCategories);
        $this->getNumUncategorizedPLOS($allPLO);

        // Second Year Courses Frequency Distribution
        $coursesOutcomes = [];
        $coursesOutcomes = $this->getCoursesOutcomes($coursesOutcomes, $secondYearProgramCourses);
        $arrSecond = [];
        $arrSecond = $this->getOutcomeMaps($allPLO, $coursesOutcomes, $arrSecond);
        $storeSecond = [];
        $storeSecond = $this->createCDFArray($arrSecond, $storeSecond);
        $storeSecond = $this->frequencyDistribution($arrSecond, $storeSecond);
        $storeSecond = $this->replaceIdsWithAbv($storeSecond, $arrSecond);
        $storeSecond = $this->assignColours($storeSecond);

        $output = $this->generateHTML($secondYearProgramCourses, $ploCount, $plos, $ploCategories, $ploProgramCategories, $storeSecond);

        return response()->json($output, 200);
    }

    public function getThirdCourses($program_id): JsonResponse
    {
        $ploCount = ProgramLearningOutcome::where('program_id', $program_id)->count();
        $thirdYearProgramCourses = Course::join('course_programs', 'courses.course_id', '=', 'course_programs.course_id')->where('course_programs.program_id', $program_id)->orderBy('course_code', 'asc')->orderBy('course_num', 'asc')->get();
        $count = 0;
        foreach ($thirdYearProgramCourses as $thirdYearProgramCourse) {
            if ($thirdYearProgramCourse->course_num[0] != '3') {           // if the first number in course_num is not 3 then remove it from the collection
                $thirdYearProgramCourses->forget($count);
            }
            $count++;
        }
        // get all categories for program
        $ploCategories = PLOCategory::where('program_id', $program_id)->get();
        // get plo categories for program
        $ploProgramCategories = PLOCategory::where('p_l_o_categories.program_id', $program_id)->join('program_learning_outcomes', 'p_l_o_categories.plo_category_id', '=', 'program_learning_outcomes.plo_category_id')->get();
        // get plo's for the program
        $plos = DB::table('program_learning_outcomes')->leftJoin('p_l_o_categories', 'program_learning_outcomes.plo_category_id', '=', 'p_l_o_categories.plo_category_id')->where('program_learning_outcomes.program_id', $program_id)->get();
        // get all plo's
        $allPLO = ProgramLearningOutcome::where('program_id', $program_id)->get();

        // set global variables
        $this->getHasUncategorized($plos);
        $this->getNumCatUsed($ploProgramCategories);
        $this->getPlosPerCategory($ploProgramCategories);
        $this->getNumUncategorizedPLOS($allPLO);

        // Third Year Courses Frequency Distribution
        $coursesOutcomes = [];
        $coursesOutcomes = $this->getCoursesOutcomes($coursesOutcomes, $thirdYearProgramCourses);
        $arrThird = [];
        $arrThird = $this->getOutcomeMaps($allPLO, $coursesOutcomes, $arrThird);
        $storeThird = [];
        $storeThird = $this->createCDFArray($arrThird, $storeThird);
        $storeThird = $this->frequencyDistribution($arrThird, $storeThird);
        $storeThird = $this->replaceIdsWithAbv($storeThird, $arrThird);
        $storeThird = $this->assignColours($storeThird);

        $output = $this->generateHTML($thirdYearProgramCourses, $ploCount, $plos, $ploCategories, $ploProgramCategories, $storeThird);

        return response()->json($output, 200);
    }

    public function getFourthCourses($program_id): JsonResponse
    {
        $ploCount = ProgramLearningOutcome::where('program_id', $program_id)->count();
        $fourthYearProgramCourses = Course::join('course_programs', 'courses.course_id', '=', 'course_programs.course_id')->where('course_programs.program_id', $program_id)->orderBy('course_code', 'asc')->orderBy('course_num', 'asc')->get();
        $count = 0;
        foreach ($fourthYearProgramCourses as $fourthYearProgramCourse) {
            if ($fourthYearProgramCourse->course_num[0] != '4') {           // if the first number in course_num is not 3 then remove it from the collection
                $fourthYearProgramCourses->forget($count);
            }
            $count++;
        }
        // get all categories for program
        $ploCategories = PLOCategory::where('program_id', $program_id)->get();
        // get plo categories for program
        $ploProgramCategories = PLOCategory::where('p_l_o_categories.program_id', $program_id)->join('program_learning_outcomes', 'p_l_o_categories.plo_category_id', '=', 'program_learning_outcomes.plo_category_id')->get();
        // get plo's for the program
        $plos = DB::table('program_learning_outcomes')->leftJoin('p_l_o_categories', 'program_learning_outcomes.plo_category_id', '=', 'p_l_o_categories.plo_category_id')->where('program_learning_outcomes.program_id', $program_id)->get();
        // get all plo's
        $allPLO = ProgramLearningOutcome::where('program_id', $program_id)->get();

        // set global variables
        $this->getHasUncategorized($plos);
        $this->getNumCatUsed($ploProgramCategories);
        $this->getPlosPerCategory($ploProgramCategories);
        $this->getNumUncategorizedPLOS($allPLO);

        // fourth Year Courses Frequency Distribution
        $coursesOutcomes = [];
        $coursesOutcomes = $this->getCoursesOutcomes($coursesOutcomes, $fourthYearProgramCourses);
        $arrFourth = [];
        $arrFourth = $this->getOutcomeMaps($allPLO, $coursesOutcomes, $arrFourth);
        $storeFourth = [];
        $storeFourth = $this->createCDFArray($arrFourth, $storeFourth);
        $storeFourth = $this->frequencyDistribution($arrFourth, $storeFourth);
        $storeFourth = $this->replaceIdsWithAbv($storeFourth, $arrFourth);
        $storeFourth = $this->assignColours($storeFourth);

        $output = $this->generateHTML($fourthYearProgramCourses, $ploCount, $plos, $ploCategories, $ploProgramCategories, $storeFourth);

        return response()->json($output, 200);
    }

    public function getGraduateCourses($program_id): JsonResponse
    {
        $ploCount = ProgramLearningOutcome::where('program_id', $program_id)->count();
        $graduateProgramCourses = Course::join('course_programs', 'courses.course_id', '=', 'course_programs.course_id')->where('course_programs.program_id', $program_id)->orderBy('course_code', 'asc')->orderBy('course_num', 'asc')->get();
        $count = 0;
        foreach ($graduateProgramCourses as $graduateProgramCourse) {
            if ($graduateProgramCourse->course_num[0] != '5' && $graduateProgramCourse->course_num[0] != '6') {           // if the first number in course_num is not 5 or 6 then remove it from the collection
                $graduateProgramCourses->forget($count);
            }
            $count++;
        }
        // get all categories for program
        $ploCategories = PLOCategory::where('program_id', $program_id)->get();
        // get plo categories for program
        $ploProgramCategories = PLOCategory::where('p_l_o_categories.program_id', $program_id)->join('program_learning_outcomes', 'p_l_o_categories.plo_category_id', '=', 'program_learning_outcomes.plo_category_id')->get();
        // get plo's for the program
        $plos = DB::table('program_learning_outcomes')->leftJoin('p_l_o_categories', 'program_learning_outcomes.plo_category_id', '=', 'p_l_o_categories.plo_category_id')->where('program_learning_outcomes.program_id', $program_id)->get();
        // get all plo's
        $allPLO = ProgramLearningOutcome::where('program_id', $program_id)->get();

        // set global variables
        $this->getHasUncategorized($plos);
        $this->getNumCatUsed($ploProgramCategories);
        $this->getPlosPerCategory($ploProgramCategories);
        $this->getNumUncategorizedPLOS($allPLO);

        // graduate Courses Frequency Distribution
        $coursesOutcomes = [];
        $coursesOutcomes = $this->getCoursesOutcomes($coursesOutcomes, $graduateProgramCourses);
        $arrGraduate = [];
        $arrGraduate = $this->getOutcomeMaps($allPLO, $coursesOutcomes, $arrGraduate);
        $storeGraduate = [];
        $storeGraduate = $this->createCDFArray($arrGraduate, $storeGraduate);
        $storeGraduate = $this->frequencyDistribution($arrGraduate, $storeGraduate);
        $storeGraduate = $this->replaceIdsWithAbv($storeGraduate, $arrGraduate);
        $storeGraduate = $this->assignColours($storeGraduate);

        $output = $this->generateHTML($graduateProgramCourses, $ploCount, $plos, $ploCategories, $ploProgramCategories, $storeGraduate);

        return response()->json($output, 200);
    }

    public function generateHTML($programCourses, $ploCount, $plos, $ploCategories, $ploProgramCategories, $store)
    {
        $output = '';

        if (count($programCourses) < 1) {
            $output .= '<div class="alert alert-warning wizard">
                            <i class="bi bi-exclamation-circle-fill pr-2 fs-5"></i>There are no courses set for this program yet.                   
                        </div>';
        } elseif ($ploCount < 1) {
            $output .= '<div class="alert alert-warning wizard">
                            <i class="bi bi-exclamation-circle-fill pr-2 fs-5"></i>There are no program learning outcomes for this program.                   
                        </div>';
        } else {
            $output .= '<table class="freq-table table table-bordered table-sm">
                            <tbody class="freq-tbody">
                                <tr class="table-primary">
                                    <th colspan="1" class="w-auto">Courses</th>
                                    <th class="text-left" colspan=" '.count($plos).' ">Program-level Learning Outcomes</th>
                                        </tr>

                                        <tr>
                                            <th colspan="1" style="background-color: rgba(0, 0, 0, 0.03);"></th>
                                            <!-- Displays Categories -->';

            foreach ($ploCategories as $index => $plo) {
                if ($plo->plo_category != null) {
                    // Use short name for category if there are more than 3
                    // if (($this->numCatUsed > 3) && ($plo->plos->count() > 0)) {
                    //     $output .= '<th colspan=" '.$this->plosPerCategory[$plo->plo_category_id].' " style="background-color: rgba(0, 0, 0, 0.03);">C - '.($index + 1).'</th>';
                    // }else
                    if ($plo->plos->count() > 0) {
                        $output .= '<th colspan=" '.$this->plosPerCategory[$plo->plo_category_id].' " style="background-color: rgba(0, 0, 0, 0.03);">'.htmlspecialchars($plo->plo_category).'</th>';
                    }
                }
            }
            if ($this->hasUncategorized) {
                $output .= '<th colspan=" '.$this->numUncategorizedPLOS.' " style="background-color: rgba(0, 0, 0, 0.03);">Uncategorized PLOs</th>';
            }
            $output .= '</tr>
                        <tr>
                            <th colspan="1" style="background-color: rgba(0, 0, 0, 0.03);"></th>';

            if (count($plos) < 7) {
                //Categorized PLOs
                foreach ($ploProgramCategories as $index => $plo) {
                    if ($plo->plo_category != null) {
                        if ($plo->plo_shortphrase == '' || $plo->plo_shortphrase == null) {
                            $output .= '<th style="background-color: rgba(0, 0, 0, 0.03);">PLO #'.($index + 1).'</th>';
                        } else {
                            $output .= '<th style="background-color: rgba(0, 0, 0, 0.03);">'.htmlspecialchars($plo->plo_shortphrase).'</th>';
                        }
                    }
                }
                //Uncategorized PLOs
                $uncatIndex = 0;
                foreach ($plos as $plo) {
                    if ($plo->plo_category == null) {
                        $uncatIndex++;
                        if ($plo->plo_shortphrase == '' || $plo->plo_shortphrase == null) {
                            $output .= '<th style="background-color: rgba(0, 0, 0, 0.03);">PLO #'.(count($ploProgramCategories) + $uncatIndex).'</th>';
                        } else {
                            $output .= '<th style="background-color: rgba(0, 0, 0, 0.03);">'.htmlspecialchars($plo->plo_shortphrase).'</th>';
                        }
                    }
                }
            } else {
                foreach ($plos as $index => $plo) {
                    $output .= '<th style="background-color: rgba(0, 0, 0, 0.03);">PLO #'.($index + 1).'</th>';
                }
            }
            $output .= '</>';
            // Show all courses associated to the program
            foreach ($programCourses as $course) {
                $output .= '<tr>
                                <th colspan="1" style="background-color: rgba(0, 0, 0, 0.03);">
                                '.htmlspecialchars($course->course_code).' '.htmlspecialchars($course->course_num).' '.htmlspecialchars($course->section).'
                                <br>
                                '.htmlspecialchars($course->semester).' '.htmlspecialchars($course->year).'
                                </th>';
                // Frequency distribution from each course
                // For Each Categorized PLO
                foreach ($ploProgramCategories as $index => $plo) {
                    if ($plo->plo_category != null) {
                        // Check if ['pl_outcome_id']['course_id'] are in the array
                        if (isset($store[$plo->pl_outcome_id][$course->course_id])) {
                            // Check if a Tie is present
                            if (isset($store[$plo->pl_outcome_id][$course->course_id]['map_scale_id_tie'])) {
                                $output .= '<td class="text-center align-middle" style="background:repeating-linear-gradient(45deg, transparent, transparent 8px, #ccc 8px, #ccc 16px), linear-gradient( to bottom, #fff, #999);" data-toggle="tooltip" data-html="true" data-bs-placement="right" title="';
                                // this loop is for the tool tip
                                foreach ($store[$plo->pl_outcome_id][$course->course_id]['frequencies'] as $index => $freq) {
                                    $output .= ''.$index.': '.$freq.'<br>';
                                }
                                $output .= '">';

                                $output .= '<span style="color: black;">
                                                                                                    '.$store[$plo->pl_outcome_id][$course->course_id]['map_scale_abv'].'
                                                                                                </span>
                                                                                            </td>';
                            } else {
                                $output .= '<td class="text-center align-middle" style="background-color: '.$store[$plo->pl_outcome_id][$course->course_id]['colour'].';" data-toggle="tooltip" data-html="true" data-bs-placement="right" title="';
                                foreach ($store[$plo->pl_outcome_id][$course->course_id]['frequencies'] as $index => $freq) {
                                    $output .= ''.$index.': '.$freq.'<br>';
                                }
                                $output .= '">';

                                $output .= '<span style="color: black;">
                                                                                                    '.$store[$plo->pl_outcome_id][$course->course_id]['map_scale_abv'].'
                                                                                                </span>
                                                                                            </td>';
                            }
                        } else {
                            $output .= '<td class="text-center align-middle" style="background-color: white;">
                                                            <i class="bi bi-exclamation-circle-fill" data-toggle="tooltip" data-html="true" data-bs-placement="right" title="Incomplete"></i>
                                                        </td>';
                        }
                    }
                }
                // For Each Uncategorized PLO
                foreach ($plos as $plo) {
                    if ($plo->plo_category == null) {
                        // Check if ['pl_outcome_id']['course_id'] are in the array
                        if (isset($store[$plo->pl_outcome_id][$course->course_id])) {
                            // Check if a Tie is present
                            if (isset($store[$plo->pl_outcome_id][$course->course_id]['map_scale_id_tie'])) {
                                $output .= '<td class="text-center align-middle" style="background:repeating-linear-gradient( 45deg, transparent, transparent 8px, #ccc 8px, #ccc 16px), linear-gradient( to bottom, #eee, #999);" data-toggle="tooltip" data-html="true" data-bs-placement="right" title="';
                                // this loop is for the tool tip
                                foreach ($store[$plo->pl_outcome_id][$course->course_id]['frequencies'] as $index => $freq) {
                                    $output .= ''.$index.': '.$freq.'<br>';
                                }
                                $output .= '">';

                                $output .= '<span style="color: black;">
                                                                                                    '.$store[$plo->pl_outcome_id][$course->course_id]['map_scale_abv'].'
                                                                                                </span>
                                                                                            </td>';
                            } else {
                                $output .= '<td class="text-center align-middle" style="background-color: '.$store[$plo->pl_outcome_id][$course->course_id]['colour'].';" data-toggle="tooltip" data-html="true" data-bs-placement="right" title="';
                                foreach ($store[$plo->pl_outcome_id][$course->course_id]['frequencies'] as $index => $freq) {
                                    $output .= ''.$index.': '.$freq.'<br>';
                                }
                                $output .= '">';

                                $output .= '<span style="color: black;">
                                                                                                    '.$store[$plo->pl_outcome_id][$course->course_id]['map_scale_abv'].'
                                                                                                </span>
                                                                                            </td>';
                            }
                        } else {
                            $output .= '<td class="text-center align-middle" style="background-color: white;">
                                                            <i class="bi bi-exclamation-circle-fill" data-toggle="tooltip" data-html="true" data-bs-placement="right" title="Incomplete"></i>
                                                        </td>';
                        }
                    }
                }
                $output .= '</tr>';
            }
            $output .= '
                            </tbody>
                        </table>
                        ';
        }

        return $output;
    }
    // Sample for generating HTML. ( OLD CODE USED BEFORE, now we load this using ajax)
    // <!-- ALL COURSES frequency distribution table -->
    //             <div class="card-body">
    //                 <h5 class="card-title">
    //                     Curriculum Map
    //                 </h5>
    //                 @if( $programCourses < 1 )
    //                     <div class="alert alert-warning wizard">
    //                         <i class="bi bi-exclamation-circle-fill pr-2 fs-5"></i>There are no courses set for this program yet.
    //                     </div>
    //                 @elseif ($ploCount < 1)
    //                     <div class="alert alert-warning wizard">
    //                         <i class="bi bi-exclamation-circle-fill pr-2 fs-5"></i>There are no program learning outcomes for this program.
    //                     </div>
    //                 @else
    //                     <p>This chart shows the alignment of courses to program learning outcomes for this program.</p>

    //                     <table class="table table-bordered table-sm" style="width: 95%; margin:auto; table-layout: fixed; border: 1px solid white; color: black;">
    //                         <tr class="table-primary">
    //                             <th colspan='1' class="w-auto">Courses</th>
    //                             <th class="text-left" colspan='{{ count($plos) }}'>Program-level Learning Outcomes</th>
    //                         </tr>
    //                         <tr>
    //                             <th colspan='1' style="background-color: rgba(0, 0, 0, 0.03);"></th>
    //                             <!-- Displays Categories -->
    //                             @foreach($ploCategories as $index =>$plo)
    //                                 @if ($plo->plo_category != NULL)
    //                                     <!-- Use short name for category if there are more than 3 -->
    //                                     @if (($numCatUsed > 3) && ($plo->plos->count() > 0))
    //                                         <th colspan='{{ $plosPerCategory[$plo->plo_category_id] }}' style="background-color: rgba(0, 0, 0, 0.03);">C - {{$index + 1}}</th>
    //                                     @elseif ($plo->plos->count() > 0)
    //                                         <th colspan='{{ $plosPerCategory[$plo->plo_category_id] }}' style="background-color: rgba(0, 0, 0, 0.03);">{{$plo->plo_category}}</th>
    //                                     @endif
    //                                 @endif
    //                             @endforeach
    //                             <!-- Heading appended at the end, if there are Uncategorized PLOs  -->
    //                             @if($hasUncategorized)
    //                                 <th colspan="{{$numUncategorizedPLOS}}" style="background-color: rgba(0, 0, 0, 0.03);">Uncategorized PLOs</th>
    //                             @endif
    //                         </tr>

    //                         <tr>
    //                             <th colspan='1' style="background-color: rgba(0, 0, 0, 0.03);"></th>
    //                             <!-- If there are less than 7 PLOs, use the short-phrase, else use PLO at index + 1 -->
    //                             @if (count($plos) < 7)
    //                                 <!-- Categorized PLOs -->
    //                                 @foreach($ploProgramCategories as $plo)
    //                                     @if ($plo->plo_category != NULL)
    //                                         <th style="background-color: rgba(0, 0, 0, 0.03);">{{$plo->plo_shortphrase}}</th>
    //                                     @endif
    //                                 @endforeach
    //                                 <!-- Uncategorized PLOs -->
    //                                 @foreach($plos as $plo)
    //                                     @if ($plo->plo_category == NULL)
    //                                         <th style="background-color: rgba(0, 0, 0, 0.03);">{{$plo->plo_shortphrase}}</th>
    //                                     @endif
    //                                 @endforeach
    //                             @else
    //                                 @foreach($plos as $index => $plo)
    //                                     <th style="background-color: rgba(0, 0, 0, 0.03);">PLO: {{$index + 1}}</th>
    //                                 @endforeach
    //                             @endif
    //                         </tr>
    //                         <!-- Show all courses associated to the program -->
    //                         @foreach($programCourses as $course)
    //                             <tr>
    //                                 <th colspan="1" style="background-color: rgba(0, 0, 0, 0.03);">
    //                                 {{$course->course_code}} {{$course->course_num}} {{$course->section}}
    //                                 <br>
    //                                 {{$course->semester}} {{$course->year}}
    //                                 </th>
    //                                 <!-- Frequency distribution from each course -->
    //                                 <!-- For Each Categorized PLO -->
    //                                 @foreach($ploProgramCategories as $index => $plo)
    //                                     @if ($plo->plo_category != NULL)
    //                                     <!-- Check if ['pl_outcome_id']['course_id'] are in the array -->
    //                                         @if(isset($testArr[$plo->pl_outcome_id][$course->course_id]))
    //                                             <!-- Check if a Tie is present -->
    //                                             @if(isset($testArr[$plo->pl_outcome_id][$course->course_id]['map_scale_id_tie']))
    //                                                 <td class="text-center align-middle" style="background:repeating-linear-gradient(45deg, transparent, transparent 8px, #ccc 8px, #ccc 16px), linear-gradient( to bottom, #fff, #999);" data-toggle="tooltip" data-html="true" data-bs-placement="right" title="@foreach($testArr[$plo->pl_outcome_id][$course->course_id]['frequencies'] as $index => $freq) {{$index}}: {{$freq}}<br> @endforeach">
    //                                                     <span style="color: black;">
    //                                                         {{$testArr[$plo->pl_outcome_id][$course->course_id]['map_scale_abv']}}
    //                                                     </span>
    //                                                 </td>
    //                                             @else
    //                                                 <td class="text-center align-middle" style="background-color: {{ $testArr[$plo->pl_outcome_id][$course->course_id]['colour'] }};" data-toggle="tooltip" data-html="true" data-bs-placement="right" title="@foreach($testArr[$plo->pl_outcome_id][$course->course_id]['frequencies'] as $index => $freq) {{$index}}: {{$freq}}<br> @endforeach">
    //                                                     <span style="color: black;">
    //                                                         {{$testArr[$plo->pl_outcome_id][$course->course_id]['map_scale_abv']}}
    //                                                     </span>
    //                                                 </td>
    //                                             @endif

    //                                         @else
    //                                             <td class="text-center align-middle" style="background-color: white;">
    //                                                 <i class="bi bi-exclamation-circle-fill" data-toggle="tooltip" data-html="true" data-bs-placement="right" title="Incomplete"></i>
    //                                             </td>
    //                                         @endif
    //                                     @endif
    //                                 @endforeach
    //                                 <!-- For Each Uncategorized PLO-->
    //                                 @foreach($plos as $plo)
    //                                     @if ($plo->plo_category == NULL)
    //                                         <!-- Check if ['pl_outcome_id']['course_id'] are in the array -->
    //                                         @if(isset($testArr[$plo->pl_outcome_id][$course->course_id]))
    //                                             <!-- Check if a Tie is present -->
    //                                             @if(isset($testArr[$plo->pl_outcome_id][$course->course_id]['map_scale_id_tie']))
    //                                                 <td class="text-center align-middle" style="background:repeating-linear-gradient( 45deg, transparent, transparent 10px, #ccc 10px, #ccc 20px), linear-gradient( to bottom, #eee, #999);" data-toggle="tooltip" data-html="true" data-bs-placement="right" title="@foreach($testArr[$plo->pl_outcome_id][$course->course_id]['frequencies'] as $index => $freq) {{$index}}: {{$freq}}<br> @endforeach">
    //                                                     <span style="color: black;">
    //                                                         {{$testArr[$plo->pl_outcome_id][$course->course_id]['map_scale_abv']}}
    //                                                     </span>
    //                                                 </td>
    //                                             @else
    //                                                 <td class="text-center align-middle" style="background-color: {{ $testArr[$plo->pl_outcome_id][$course->course_id]['colour'] }};" data-toggle="tooltip" data-html="true" data-bs-placement="right" title="@foreach($testArr[$plo->pl_outcome_id][$course->course_id]['frequencies'] as $index => $freq) {{$index}}: {{$freq}}<br> @endforeach">
    //                                                     <span style="color: black;">
    //                                                         {{$testArr[$plo->pl_outcome_id][$course->course_id]['map_scale_abv']}}
    //                                                     </span>
    //                                                 </td>
    //                                             @endif

    //                                         @else
    //                                             <td class="text-center align-middle" style="background-color: white;">
    //                                                 <i class="bi bi-exclamation-circle-fill" data-toggle="tooltip" data-html="true" data-bs-placement="right" title="Incomplete"></i>
    //                                             </td>
    //                                         @endif
    //                                     @endif
    //                                 @endforeach
    //                             </tr>
    //                         @endforeach
    //                     </table>
    //                 @endif
    //             </div>
    //             <!-- end Courses to PLOs frequency Distribution card -->
}
