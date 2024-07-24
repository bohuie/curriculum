<?php

namespace App\Http\Controllers;

use App\Models\AssessmentMethod;
use App\Models\Course;
use App\Models\CourseProgram;
use App\Models\Custom_assessment_methods;
use App\Models\Custom_learning_activities;
use App\Models\LearningActivity;
use App\Models\LearningOutcome;
use App\Models\MappingScale;
use App\Models\OptionalPrioritiesSubdescription;
use App\Models\OptionalPriorityCategories;
use App\Models\OutcomeActivity;
use App\Models\OutcomeAssessment;
use App\Models\OutcomeMap;
use App\Models\Program;
use App\Models\ProgramLearningOutcome;
use App\Models\Standard;
use App\Models\StandardCategory;
use App\Models\StandardScale;
use App\Models\StandardsOutcomeMap;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CourseWizardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
        $this->middleware('hasAccess');
    }

    public function step1($course_id, Request $request)
    {
        $isEditor = false;
        if ($request->isEditor) {
            $isEditor = true;
        }
        $isViewer = false;
        if ($request->isViewer) {
            return redirect()->route('courseWizard.step7', $course_id);
        }
        //for header
        $user = User::where('id', Auth::id())->first();
        // returns a collection of courses associated with users
        $myCourses = $user->courses;
        $courseUsers = [];
        foreach ($myCourses as $course) {
            $coursesUsers = $course->users()->get();
            $courseUsers[$course->course_id] = $coursesUsers;
        }
        $course = Course::find($course_id);
        $oAct = LearningActivity::join('outcome_activities', 'learning_activities.l_activity_id', '=', 'outcome_activities.l_activity_id')
            ->join('learning_outcomes', 'outcome_activities.l_outcome_id', '=', 'learning_outcomes.l_outcome_id')
            ->select('outcome_activities.l_activity_id', 'learning_activities.l_activity', 'outcome_activities.l_outcome_id', 'learning_outcomes.l_outcome')
            ->where('learning_activities.course_id', '=', $course_id)->count();
        $oAss = AssessmentMethod::join('outcome_assessments', 'assessment_methods.a_method_id', '=', 'outcome_assessments.a_method_id')
            ->join('learning_outcomes', 'outcome_assessments.l_outcome_id', '=', 'learning_outcomes.l_outcome_id')
            ->select('assessment_methods.a_method_id', 'assessment_methods.a_method', 'outcome_assessments.l_outcome_id', 'learning_outcomes.l_outcome')
            ->where('assessment_methods.course_id', '=', $course_id)->count();
        $outcomeMapsCount = ProgramLearningOutcome::join('outcome_maps', 'program_learning_outcomes.pl_outcome_id', '=', 'outcome_maps.pl_outcome_id')
            ->join('learning_outcomes', 'outcome_maps.l_outcome_id', '=', 'learning_outcomes.l_outcome_id')
            ->select('outcome_maps.map_scale_id', 'outcome_maps.pl_outcome_id', 'program_learning_outcomes.pl_outcome', 'outcome_maps.l_outcome_id', 'learning_outcomes.l_outcome')
            ->where('learning_outcomes.course_id', '=', $course_id)->count();
        $standardsOutcomeMapCount = StandardsOutcomeMap::where('course_id', $course_id)->count();
        $expectedStandardOutcomeMapCount = StandardCategory::find($course->standard_category_id)->standards->count();
        $numClos = LearningOutcome::where('course_id', $course_id)->count();
        $numStandards = Standard::where('standard_category_id', $course->standard_category_id)->count();
        // get the total number of program outcome maps possible for a course
        $coursePrograms = $course->programs;
        $expectedProgramOutcomeMapCount = 0;
        foreach ($coursePrograms as $program) {
            // multiple number of CLOs by num of PLOs
            $expectedProgramOutcomeMapCount += $program->programLearningOutcomes->count() * $numClos;
        }
        // checks if all learning outcomes have been aligned to a student assessment method AND a Teaching and Learning Outcome. Breaks and returns true if a clo is not aligned.
        $l_outcomes = LearningOutcome::where('course_id', $course_id)->get();
        $hasNonAlignedCLO = false;
        foreach ($l_outcomes as $clo) {
            if ((! OutcomeAssessment::where('l_outcome_id', $clo->l_outcome_id)->exists()) || (! OutcomeActivity::where('l_outcome_id', $clo->l_outcome_id)->exists())) {
                $hasNonAlignedCLO = true;
                break;
            }
        }
        //
        $l_outcomes = $course->learningOutcomes->sortBy('pos_in_alignment')->values();
        $course = Course::where('course_id', $course_id)->first();
        // returns a collection of standard_categories, used in the create course modal
        $standard_categories = DB::table('standard_categories')->get();

        return view('courses.wizard.step1')->with('l_outcomes', $l_outcomes)->with('course', $course)->with('courseUsers', $courseUsers)->with('user', $user)->with('oAct', $oAct)->with('oAss', $oAss)->with('outcomeMapsCount', $outcomeMapsCount)
            ->with('isEditor', $isEditor)->with('isViewer', $isViewer)->with('standard_categories', $standard_categories)->with('standardsOutcomeMapCount', $standardsOutcomeMapCount)
            ->with('expectedStandardOutcomeMapCount', $expectedStandardOutcomeMapCount)->with('expectedProgramOutcomeMapCount', $expectedProgramOutcomeMapCount)->with('hasNonAlignedCLO', $hasNonAlignedCLO);

    }

    public function step2($course_id, Request $request)
    {
        $isEditor = false;
        if ($request->isEditor) {
            $isEditor = true;
        }
        $isViewer = false;
        if ($request->isViewer) {
            return redirect()->route('courseWizard.step7', $course_id);
        }

        //for header
        $user = User::where('id', Auth::id())->first();
        // returns a collection of courses associated with users
        $myCourses = $user->courses;
        $courseUsers = [];
        foreach ($myCourses as $course) {
            $coursesUsers = $course->users()->get();
            $courseUsers[$course->course_id] = $coursesUsers;
        }
        $course = Course::find($course_id);
        $oAct = LearningActivity::join('outcome_activities', 'learning_activities.l_activity_id', '=', 'outcome_activities.l_activity_id')
            ->join('learning_outcomes', 'outcome_activities.l_outcome_id', '=', 'learning_outcomes.l_outcome_id')
            ->select('outcome_activities.l_activity_id', 'learning_activities.l_activity', 'outcome_activities.l_outcome_id', 'learning_outcomes.l_outcome')
            ->where('learning_activities.course_id', '=', $course_id)->count();
        $oAss = AssessmentMethod::join('outcome_assessments', 'assessment_methods.a_method_id', '=', 'outcome_assessments.a_method_id')
            ->join('learning_outcomes', 'outcome_assessments.l_outcome_id', '=', 'learning_outcomes.l_outcome_id')
            ->select('assessment_methods.a_method_id', 'assessment_methods.a_method', 'outcome_assessments.l_outcome_id', 'learning_outcomes.l_outcome')
            ->where('assessment_methods.course_id', '=', $course_id)->count();
        $outcomeMapsCount = ProgramLearningOutcome::join('outcome_maps', 'program_learning_outcomes.pl_outcome_id', '=', 'outcome_maps.pl_outcome_id')
            ->join('learning_outcomes', 'outcome_maps.l_outcome_id', '=', 'learning_outcomes.l_outcome_id')
            ->select('outcome_maps.map_scale_value', 'outcome_maps.pl_outcome_id', 'program_learning_outcomes.pl_outcome', 'outcome_maps.l_outcome_id', 'learning_outcomes.l_outcome')
            ->where('learning_outcomes.course_id', '=', $course_id)->count();
        $standardsOutcomeMapCount = StandardsOutcomeMap::where('course_id', $course_id)->count();
        $expectedStandardOutcomeMapCount = StandardCategory::find($course->standard_category_id)->standards->count();
        $numClos = LearningOutcome::where('course_id', $course_id)->count();
        $numStandards = Standard::where('standard_category_id', $course->standard_category_id)->count();
        // get the total number of program outcome maps possible for a course
        $coursePrograms = $course->programs;
        $expectedProgramOutcomeMapCount = 0;
        foreach ($coursePrograms as $program) {
            // multiple number of CLOs by num of PLOs
            $expectedProgramOutcomeMapCount += $program->programLearningOutcomes->count() * $numClos;
        }
        //
        // checks if all learning outcomes have been aligned to a student assessment method AND a Teaching and Learning Outcome. Breaks and returns true if a clo is not aligned.
        $l_outcomes = LearningOutcome::where('course_id', $course_id)->get();
        $hasNonAlignedCLO = false;
        foreach ($l_outcomes as $clo) {
            if ((! OutcomeAssessment::where('l_outcome_id', $clo->l_outcome_id)->exists()) || (! OutcomeActivity::where('l_outcome_id', $clo->l_outcome_id)->exists())) {
                $hasNonAlignedCLO = true;
                break;
            }
        }

        $a_methods = $course->assessmentMethods->sortBy('pos_in_alignment')->values();

        $custom_methods = Custom_assessment_methods::select('custom_methods')->get();
        $totalWeight = number_format(AssessmentMethod::where('course_id', $course_id)->sum('weight'), 1);
        $course = Course::where('course_id', $course_id)->first();
        // returns a collection of standard_categories, used in the create course modal
        $standard_categories = DB::table('standard_categories')->get();

        return view('courses.wizard.step2')->with('a_methods', $a_methods)->with('course', $course)->with('totalWeight', $totalWeight)->with('courseUsers', $courseUsers)
            ->with('user', $user)->with('custom_methods', $custom_methods)->with('oAct', $oAct)->with('oAss', $oAss)->with('outcomeMapsCount', $outcomeMapsCount)
            ->with('isEditor', $isEditor)->with('isViewer', $isViewer)->with('standardsOutcomeMapCount', $standardsOutcomeMapCount)->with('standard_categories', $standard_categories)
            ->with('expectedStandardOutcomeMapCount', $expectedStandardOutcomeMapCount)->with('expectedProgramOutcomeMapCount', $expectedProgramOutcomeMapCount)->with('hasNonAlignedCLO', $hasNonAlignedCLO);

    }

    public function step3($course_id, Request $request)
    {
        $isEditor = false;
        if ($request->isEditor) {
            $isEditor = true;
        }
        $isViewer = false;
        if ($request->isViewer) {
            return redirect()->route('courseWizard.step7', $course_id);
        }
        //for header
        $user = User::where('id', Auth::id())->first();
        // returns a collection of courses associated with users
        $myCourses = $user->courses;
        $courseUsers = [];
        foreach ($myCourses as $course) {
            $coursesUsers = $course->users()->get();
            $courseUsers[$course->course_id] = $coursesUsers;
        }
        $course = Course::find($course_id);
        $oAct = LearningActivity::join('outcome_activities', 'learning_activities.l_activity_id', '=', 'outcome_activities.l_activity_id')
            ->join('learning_outcomes', 'outcome_activities.l_outcome_id', '=', 'learning_outcomes.l_outcome_id')
            ->select('outcome_activities.l_activity_id', 'learning_activities.l_activity', 'outcome_activities.l_outcome_id', 'learning_outcomes.l_outcome')
            ->where('learning_activities.course_id', '=', $course_id)->count();
        $oAss = AssessmentMethod::join('outcome_assessments', 'assessment_methods.a_method_id', '=', 'outcome_assessments.a_method_id')
            ->join('learning_outcomes', 'outcome_assessments.l_outcome_id', '=', 'learning_outcomes.l_outcome_id')
            ->select('assessment_methods.a_method_id', 'assessment_methods.a_method', 'outcome_assessments.l_outcome_id', 'learning_outcomes.l_outcome')
            ->where('assessment_methods.course_id', '=', $course_id)->count();
        $outcomeMapsCount = ProgramLearningOutcome::join('outcome_maps', 'program_learning_outcomes.pl_outcome_id', '=', 'outcome_maps.pl_outcome_id')
            ->join('learning_outcomes', 'outcome_maps.l_outcome_id', '=', 'learning_outcomes.l_outcome_id')
            ->select('outcome_maps.map_scale_value', 'outcome_maps.pl_outcome_id', 'program_learning_outcomes.pl_outcome', 'outcome_maps.l_outcome_id', 'learning_outcomes.l_outcome')
            ->where('learning_outcomes.course_id', '=', $course_id)->count();
        $standardsOutcomeMapCount = StandardsOutcomeMap::where('course_id', $course_id)->count();
        $expectedStandardOutcomeMapCount = StandardCategory::find($course->standard_category_id)->standards->count();
        $numClos = LearningOutcome::where('course_id', $course_id)->count();
        $numStandards = Standard::where('standard_category_id', $course->standard_category_id)->count();
        // get the total number of program outcome maps possible for a course
        $coursePrograms = $course->programs;
        $expectedProgramOutcomeMapCount = 0;
        foreach ($coursePrograms as $program) {
            // multiple number of CLOs by num of PLOs
            $expectedProgramOutcomeMapCount += $program->programLearningOutcomes->count() * $numClos;
        }
        // checks if all learning outcomes have been aligned to a student assessment method AND a Teaching and Learning Outcome. Breaks and returns true if a clo is not aligned.
        $l_outcomes = LearningOutcome::where('course_id', $course_id)->get();
        $hasNonAlignedCLO = false;
        foreach ($l_outcomes as $clo) {
            if ((! OutcomeAssessment::where('l_outcome_id', $clo->l_outcome_id)->exists()) || (! OutcomeActivity::where('l_outcome_id', $clo->l_outcome_id)->exists())) {
                $hasNonAlignedCLO = true;
                break;
            }
        }

        $l_activities = $course->learningActivities->sortBy('l_activities_pos')->values();

        $custom_activities = Custom_learning_activities::select('custom_activities')->get();
        $course = Course::where('course_id', $course_id)->first();
        // returns a collection of standard_categories, used in the create course modal
        $standard_categories = DB::table('standard_categories')->get();

        return view('courses.wizard.step3')->with('l_activities', $l_activities)->with('course', $course)->with('courseUsers', $courseUsers)->with('user', $user)
            ->with('custom_activities', $custom_activities)->with('oAct', $oAct)->with('oAss', $oAss)->with('outcomeMapsCount', $outcomeMapsCount)
            ->with('isEditor', $isEditor)->with('isViewer', $isViewer)->with('standardsOutcomeMapCount', $standardsOutcomeMapCount)->with('standard_categories', $standard_categories)
            ->with('expectedStandardOutcomeMapCount', $expectedStandardOutcomeMapCount)->with('expectedProgramOutcomeMapCount', $expectedProgramOutcomeMapCount)->with('hasNonAlignedCLO', $hasNonAlignedCLO);

    }

    public function step4($course_id, Request $request)
    {
        $isEditor = false;
        if ($request->isEditor) {
            $isEditor = true;
        }
        $isViewer = false;
        if ($request->isViewer) {
            return redirect()->route('courseWizard.step7', $course_id);
        }
        //for header
        $user = User::where('id', Auth::id())->first();
        // returns a collection of courses associated with users
        $myCourses = $user->courses;
        $courseUsers = [];
        foreach ($myCourses as $course) {
            $coursesUsers = $course->users()->get();
            $courseUsers[$course->course_id] = $coursesUsers;
        }
        $course = Course::find($course_id);
        $oAct = LearningActivity::join('outcome_activities', 'learning_activities.l_activity_id', '=', 'outcome_activities.l_activity_id')
            ->join('learning_outcomes', 'outcome_activities.l_outcome_id', '=', 'learning_outcomes.l_outcome_id')
            ->select('outcome_activities.l_activity_id', 'learning_activities.l_activity', 'outcome_activities.l_outcome_id', 'learning_outcomes.l_outcome')
            ->where('learning_activities.course_id', '=', $course_id)->count();
        $oAss = AssessmentMethod::join('outcome_assessments', 'assessment_methods.a_method_id', '=', 'outcome_assessments.a_method_id')
            ->join('learning_outcomes', 'outcome_assessments.l_outcome_id', '=', 'learning_outcomes.l_outcome_id')
            ->select('assessment_methods.a_method_id', 'assessment_methods.a_method', 'outcome_assessments.l_outcome_id', 'learning_outcomes.l_outcome')
            ->where('assessment_methods.course_id', '=', $course_id)->count();
        $outcomeMapsCount = ProgramLearningOutcome::join('outcome_maps', 'program_learning_outcomes.pl_outcome_id', '=', 'outcome_maps.pl_outcome_id')
            ->join('learning_outcomes', 'outcome_maps.l_outcome_id', '=', 'learning_outcomes.l_outcome_id')
            ->select('outcome_maps.map_scale_value', 'outcome_maps.pl_outcome_id', 'program_learning_outcomes.pl_outcome', 'outcome_maps.l_outcome_id', 'learning_outcomes.l_outcome')
            ->where('learning_outcomes.course_id', '=', $course_id)->count();
        $standardsOutcomeMapCount = StandardsOutcomeMap::where('course_id', $course_id)->count();
        $expectedStandardOutcomeMapCount = StandardCategory::find($course->standard_category_id)->standards->count();
        $numClos = LearningOutcome::where('course_id', $course_id)->count();
        $numStandards = Standard::where('standard_category_id', $course->standard_category_id)->count();
        // get the total number of program outcome maps possible for a course
        $coursePrograms = $course->programs;
        $expectedProgramOutcomeMapCount = 0;
        foreach ($coursePrograms as $program) {
            // multiple number of CLOs by num of PLOs
            $expectedProgramOutcomeMapCount += $program->programLearningOutcomes->count() * $numClos;
        }
        // checks if all learning outcomes have been aligned to a student assessment method AND a Teaching and Learning Outcome. Breaks and returns true if a clo is not aligned.
        $l_outcomes = $course->learningOutcomes->sortBy('pos_in_alignment')->values();
        $hasNonAlignedCLO = false;
        foreach ($l_outcomes as $clo) {
            if ((! OutcomeAssessment::where('l_outcome_id', $clo->l_outcome_id)->exists()) || (! OutcomeActivity::where('l_outcome_id', $clo->l_outcome_id)->exists())) {
                $hasNonAlignedCLO = true;
                break;
            }
        }

        $course = Course::where('course_id', $course_id)->first();
        $l_activities = $course->learningActivities->sortBy('l_activities_pos')->values();
        $a_methods = $course->assessmentMethods->sortBy('pos_in_alignment')->values();
        // returns a collection of standard_categories, used in the create course modal
        $standard_categories = DB::table('standard_categories')->get();

        return view('courses.wizard.step4')->with('l_outcomes', $l_outcomes)->with('course', $course)->with('l_activities', $l_activities)->with('a_methods', $a_methods)
            ->with('courseUsers', $courseUsers)->with('user', $user)->with('oAct', $oAct)->with('oAss', $oAss)->with('outcomeMapsCount', $outcomeMapsCount)
            ->with('isEditor', $isEditor)->with('isViewer', $isViewer)->with('standardsOutcomeMapCount', $standardsOutcomeMapCount)->with('standard_categories', $standard_categories)
            ->with('expectedStandardOutcomeMapCount', $expectedStandardOutcomeMapCount)->with('expectedProgramOutcomeMapCount', $expectedProgramOutcomeMapCount)->with('hasNonAlignedCLO', $hasNonAlignedCLO);
    }

    // Program Outcome Mapping
    public function step5($course_id, Request $request)
    {
        $isEditor = false;
        if ($request->isEditor) {
            $isEditor = true;
        }
        $isViewer = false;
        if ($request->isViewer) {
            return redirect()->route('courseWizard.step7', $course_id);
        }
        // for header
        $user = User::where('id', Auth::id())->first();
        // returns a collection of courses associated with users
        $myCourses = $user->courses;
        $courseUsers = [];
        foreach ($myCourses as $course) {
            $coursesUsers = $course->users()->get();
            $courseUsers[$course->course_id] = $coursesUsers;
        }
        $course = Course::find($course_id);
        $oAct = LearningActivity::join('outcome_activities', 'learning_activities.l_activity_id', '=', 'outcome_activities.l_activity_id')
            ->join('learning_outcomes', 'outcome_activities.l_outcome_id', '=', 'learning_outcomes.l_outcome_id')
            ->select('outcome_activities.l_activity_id', 'learning_activities.l_activity', 'outcome_activities.l_outcome_id', 'learning_outcomes.l_outcome')
            ->where('learning_activities.course_id', '=', $course_id)->count();
        $oAss = AssessmentMethod::join('outcome_assessments', 'assessment_methods.a_method_id', '=', 'outcome_assessments.a_method_id')
            ->join('learning_outcomes', 'outcome_assessments.l_outcome_id', '=', 'learning_outcomes.l_outcome_id')
            ->select('assessment_methods.a_method_id', 'assessment_methods.a_method', 'outcome_assessments.l_outcome_id', 'learning_outcomes.l_outcome')
            ->where('assessment_methods.course_id', '=', $course_id)->count();
        $outcomeMapsCount = ProgramLearningOutcome::join('outcome_maps', 'program_learning_outcomes.pl_outcome_id', '=', 'outcome_maps.pl_outcome_id')
            ->join('learning_outcomes', 'outcome_maps.l_outcome_id', '=', 'learning_outcomes.l_outcome_id')
            ->select('outcome_maps.map_scale_id', 'outcome_maps.pl_outcome_id', 'program_learning_outcomes.pl_outcome', 'outcome_maps.l_outcome_id', 'learning_outcomes.l_outcome')
            ->where('learning_outcomes.course_id', '=', $course_id)->count();
        $standardsOutcomeMapCount = StandardsOutcomeMap::where('course_id', $course_id)->count();
        $expectedStandardOutcomeMapCount = StandardCategory::find($course->standard_category_id)->standards->count();
        $numClos = LearningOutcome::where('course_id', $course_id)->count();
        $numStandards = Standard::where('standard_category_id', $course->standard_category_id)->count();
        // get the total number of program outcome maps possible for a course
        $coursePrograms = $course->programs;
        $expectedProgramOutcomeMapCount = 0;
        foreach ($coursePrograms as $program) {
            // multiple number of CLOs by num of PLOs
            $expectedProgramOutcomeMapCount += $program->programLearningOutcomes->count() * $numClos;
        }
        // checks if all learning outcomes have been aligned to a student assessment method AND a Teaching and Learning Outcome. Breaks and returns true if a clo is not aligned.
        $l_outcomes = $course->learningOutcomes->sortBy('pos_in_alignment')->values();
        $hasNonAlignedCLO = false;
        foreach ($l_outcomes as $clo) {
            if ((! OutcomeAssessment::where('l_outcome_id', $clo->l_outcome_id)->exists()) || (! OutcomeActivity::where('l_outcome_id', $clo->l_outcome_id)->exists())) {
                $hasNonAlignedCLO = true;
                break;
            }
        }

        // returns a collection of standard_categories, used in the create course modal
        $standard_categories = DB::table('standard_categories')->get();
        // Returns the count of clos to plos for a courseProgram
        $clos = $course->learningOutcomes->pluck('l_outcome_id')->toArray();
        $pl_outcomes = [];
        $outcomeMapsCountPerProgram = [];
        $outcomeMapsCountPerProgramCLO = [];
        foreach ($coursePrograms as $courseProgram) {
            $pl_outcomes[$courseProgram->program_id] = $courseProgram->programLearningOutcomes->pluck('pl_outcome_id')->toArray();
            $outcomeMapsCountPerProgram[$courseProgram->program_id] = 0;
            $outcomeMapsCountPerProgramCLO[$courseProgram->program_id] = [];
        }
        foreach ($clos as $clo) {
            foreach ($pl_outcomes as $programId => $pl_outcome) {
                $outcomeMapsCountPerProgramCLO[$programId][$clo] = 0;
                foreach ($pl_outcome as $pl_outcome_id) {
                    if (OutcomeMap::where('l_outcome_id', $clo)->where('pl_outcome_id', $pl_outcome_id)->exists()) {
                        // increment for program (Outer Accordion)
                        $outcomeMapsCountPerProgram[$programId] += 1;
                        // increment for clos (Inner Accordion)
                        $outcomeMapsCountPerProgramCLO[$programId][$clo] += 1;
                    }
                }
            }
        }

        return view('courses.wizard.step5')->with('course', $course)->with('user', $user)->with('oAct', $oAct)->with('oAss', $oAss)->with('outcomeMapsCount', $outcomeMapsCount)
            ->with('isEditor', $isEditor)->with('isViewer', $isViewer)->with('courseUsers', $courseUsers)->with('standardsOutcomeMapCount', $standardsOutcomeMapCount)
            ->with('outcomeMapsCountPerProgram', $outcomeMapsCountPerProgram)->with('outcomeMapsCountPerProgramCLO', $outcomeMapsCountPerProgramCLO)->with('standard_categories', $standard_categories)
            ->with('expectedStandardOutcomeMapCount', $expectedStandardOutcomeMapCount)->with('expectedProgramOutcomeMapCount', $expectedProgramOutcomeMapCount)->with('hasNonAlignedCLO', $hasNonAlignedCLO)->with('l_outcomes', $l_outcomes);
    }

    public function step6($course_id, Request $request)
    {
        $isEditor = false;
        if ($request->isEditor) {
            $isEditor = true;
        }
        $isViewer = false;
        if ($request->isViewer) {
            return redirect()->route('courseWizard.step7', $course_id);
        }
        // for header
        $user = User::where('id', Auth::id())->first();
        // returns a collection of courses associated with users
        $myCourses = $user->courses;
        $courseUsers = [];
        foreach ($myCourses as $course) {
            $coursesUsers = $course->users()->get();
            $courseUsers[$course->course_id] = $coursesUsers;
        }
        $course = Course::find($course_id);
        $oAct = LearningActivity::join('outcome_activities', 'learning_activities.l_activity_id', '=', 'outcome_activities.l_activity_id')
            ->join('learning_outcomes', 'outcome_activities.l_outcome_id', '=', 'learning_outcomes.l_outcome_id')
            ->select('outcome_activities.l_activity_id', 'learning_activities.l_activity', 'outcome_activities.l_outcome_id', 'learning_outcomes.l_outcome')
            ->where('learning_activities.course_id', '=', $course_id)->count();
        $oAss = AssessmentMethod::join('outcome_assessments', 'assessment_methods.a_method_id', '=', 'outcome_assessments.a_method_id')
            ->join('learning_outcomes', 'outcome_assessments.l_outcome_id', '=', 'learning_outcomes.l_outcome_id')
            ->select('assessment_methods.a_method_id', 'assessment_methods.a_method', 'outcome_assessments.l_outcome_id', 'learning_outcomes.l_outcome')
            ->where('assessment_methods.course_id', '=', $course_id)->count();
        $outcomeMapsCount = ProgramLearningOutcome::join('outcome_maps', 'program_learning_outcomes.pl_outcome_id', '=', 'outcome_maps.pl_outcome_id')
            ->join('learning_outcomes', 'outcome_maps.l_outcome_id', '=', 'learning_outcomes.l_outcome_id')
            ->select('outcome_maps.map_scale_value', 'outcome_maps.pl_outcome_id', 'program_learning_outcomes.pl_outcome', 'outcome_maps.l_outcome_id', 'learning_outcomes.l_outcome')
            ->where('learning_outcomes.course_id', '=', $course_id)->count();
        $standardsOutcomeMapCount = StandardsOutcomeMap::where('course_id', $course_id)->count();
        $expectedStandardOutcomeMapCount = StandardCategory::find($course->standard_category_id)->standards->count();
        $numClos = LearningOutcome::where('course_id', $course_id)->count();
        $numStandards = Standard::where('standard_category_id', $course->standard_category_id)->count();
        // get the total number of program outcome maps possible for a course
        $coursePrograms = $course->programs;
        $expectedProgramOutcomeMapCount = 0;
        foreach ($coursePrograms as $program) {
            // multiple number of CLOs by num of PLOs
            $expectedProgramOutcomeMapCount += $program->programLearningOutcomes->count() * $numClos;
        }
        // checks if all learning outcomes have been aligned to a student assessment method AND a Teaching and Learning Outcome. Breaks and returns true if a clo is not aligned.
        $l_outcomes = LearningOutcome::where('course_id', $course_id)->get();
        $hasNonAlignedCLO = false;
        foreach ($l_outcomes as $clo) {
            if ((! OutcomeAssessment::where('l_outcome_id', $clo->l_outcome_id)->exists()) || (! OutcomeActivity::where('l_outcome_id', $clo->l_outcome_id)->exists())) {
                $hasNonAlignedCLO = true;
                break;
            }
        }

        // returns a collection of standard_categories, used in the create course modal
        $standard_categories = DB::table('standard_categories')->get();
        // get learning outcomes for a course
        $l_outcomes = $course->learningOutcomes->sortBy('pos_in_alignment')->values();
        // get Standards and strategic outcomes for a course
        $standard_outcomes = Standard::where('standard_category_id', $course->standard_category_id)->get();
        // get mapping scales associated with course
        $mappingScales = StandardScale::where('scale_category_id', $course->scale_category_id)->get();
        // get all the optional priority categories
        $optionalPriorityCategories = OptionalPriorityCategories::all();
        // get the saved optional priorities
        $opStored = $course->optionalPriorities->pluck('op_id')->toArray();

        // get all optional priority subdescriptions
        $opSubDesc = OptionalPrioritiesSubdescription::all();

        return view('courses.wizard.step6')->with('l_outcomes', $l_outcomes)->with('course', $course)->with('mappingScales', $mappingScales)
            ->with('courseUsers', $courseUsers)->with('user', $user)->with('oAct', $oAct)->with('oAss', $oAss)->with('outcomeMapsCount', $outcomeMapsCount)
            ->with('standard_outcomes', $standard_outcomes)->with('isEditor', $isEditor)->with('isViewer', $isViewer)->with('courseUsers', $courseUsers)
            ->with('optionalPriorityCategories', $optionalPriorityCategories)->with('opStored', $opStored)->with('standardsOutcomeMapCount', $standardsOutcomeMapCount)
            ->with('standard_categories', $standard_categories)->with('expectedStandardOutcomeMapCount', $expectedStandardOutcomeMapCount)
            ->with('expectedProgramOutcomeMapCount', $expectedProgramOutcomeMapCount)->with('hasNonAlignedCLO', $hasNonAlignedCLO)->with('opSubDesc', $opSubDesc);
    }

    public function step7($course_id, Request $request): View
    {
        $isEditor = false;
        if ($request->isEditor) {
            $isEditor = true;
        }
        $isViewer = false;
        if ($request->isViewer) {
            $isViewer = true;
        }
        //for header
        $user = User::where('id', Auth::id())->first();
        // returns a collection of courses associated with users
        $myCourses = $user->courses;
        $courseUsers = [];
        foreach ($myCourses as $course) {
            $coursesUsers = $course->users()->get();
            $courseUsers[$course->course_id] = $coursesUsers;
        }
        $course = Course::find($course_id);
        $oActCount = LearningActivity::join('outcome_activities', 'learning_activities.l_activity_id', '=', 'outcome_activities.l_activity_id')
            ->join('learning_outcomes', 'outcome_activities.l_outcome_id', '=', 'learning_outcomes.l_outcome_id')
            ->select('outcome_activities.l_activity_id', 'learning_activities.l_activity', 'outcome_activities.l_outcome_id', 'learning_outcomes.l_outcome')
            ->where('learning_activities.course_id', '=', $course_id)->count();
        $oAssCount = AssessmentMethod::join('outcome_assessments', 'assessment_methods.a_method_id', '=', 'outcome_assessments.a_method_id')
            ->join('learning_outcomes', 'outcome_assessments.l_outcome_id', '=', 'learning_outcomes.l_outcome_id')
            ->select('assessment_methods.a_method_id', 'assessment_methods.a_method', 'outcome_assessments.l_outcome_id', 'learning_outcomes.l_outcome')
            ->where('assessment_methods.course_id', '=', $course_id)->count();
        $outcomeMapsCount = ProgramLearningOutcome::join('outcome_maps', 'program_learning_outcomes.pl_outcome_id', '=', 'outcome_maps.pl_outcome_id')
            ->join('learning_outcomes', 'outcome_maps.l_outcome_id', '=', 'learning_outcomes.l_outcome_id')
            ->select('outcome_maps.map_scale_value', 'outcome_maps.pl_outcome_id', 'program_learning_outcomes.pl_outcome', 'outcome_maps.l_outcome_id', 'learning_outcomes.l_outcome')
            ->where('learning_outcomes.course_id', '=', $course_id)->count();
        $standardsOutcomeMapCount = StandardsOutcomeMap::where('course_id', $course_id)->count();
        $expectedStandardOutcomeMapCount = StandardCategory::find($course->standard_category_id)->standards->count();
        $numClos = LearningOutcome::where('course_id', $course_id)->count();
        $numStandards = Standard::where('standard_category_id', $course->standard_category_id)->count();
        // get the total number of program outcome maps possible for a course
        $coursePrograms = $course->programs;
        $tempProgram = new Program;
        $tempProgram->program_id = 1;
        $expectedProgramOutcomeMapCount = 0;
        foreach ($coursePrograms as $program) {
            // multiple number of CLOs by num of PLOs
            $expectedProgramOutcomeMapCount += $program->programLearningOutcomes->count() * $numClos;
        }
        // checks if all learning outcomes have been aligned to a student assessment method AND a Teaching and Learning Outcome. Breaks and returns true if a clo is not aligned.
        $l_outcomes = $course->learningOutcomes->sortBy('pos_in_alignment')->values();
        $hasNonAlignedCLO = false;
        foreach ($l_outcomes as $clo) {
            if ((! OutcomeAssessment::where('l_outcome_id', $clo->l_outcome_id)->exists()) || (! OutcomeActivity::where('l_outcome_id', $clo->l_outcome_id)->exists())) {
                $hasNonAlignedCLO = true;
                break;
            }
        }

        // returns a collection of standard_categories, used in the create course modal
        $standard_categories = DB::table('standard_categories')->get();
        // get all the programs this course belongs to
        $coursePrograms = $course->programs;
        // ddd($coursePrograms[0]->programLearningOutcomes->where('plo_category_id', null));
        // get the PLOs for each program
        $programsLearningOutcomes = [];

        $coursePrograms->map(function ($courseProgram, $key) {
            $courseProgram->push(0, 'num_plos_categorized');
            $courseProgram->programLearningOutcomes->each(function ($plo, $key) use ($courseProgram) {
                if (isset($plo->category)) {
                    $courseProgram->num_plos_categorized++;
                }
            });
        });

        foreach ($coursePrograms as $courseProgram) {
            $programsLearningOutcomes[$courseProgram->program_id] = $courseProgram->programLearningOutcomes;
        }
        // courseProgramsOutcomeMaps[$program_id][$plo][$clo] = mapping scale
        $courseProgramsOutcomeMaps = [];
        foreach ($programsLearningOutcomes as $programId => $programLearningOutcomes) {
            foreach ($programLearningOutcomes as $programLearningOutcome) {
                $outcomeMaps = $programLearningOutcome->learningOutcomes->where('course_id', $course_id);
                foreach ($outcomeMaps as $outcomeMap) {
                    $courseProgramsOutcomeMaps[$programId][$programLearningOutcome->pl_outcome_id][$outcomeMap->l_outcome_id] = MappingScale::find($outcomeMap->pivot->map_scale_id);
                }
            }
        }

        // get standards outcome map
        $standardsOutcomeMap = StandardsOutcomeMap::where('course_id', $course_id)->get();

        $outcomeActivities = LearningActivity::join('outcome_activities', 'learning_activities.l_activity_id', '=', 'outcome_activities.l_activity_id')
            ->join('learning_outcomes', 'outcome_activities.l_outcome_id', '=', 'learning_outcomes.l_outcome_id')
            ->select('outcome_activities.l_activity_id', 'learning_activities.l_activity', 'outcome_activities.l_outcome_id', 'learning_outcomes.l_outcome')
            ->where('learning_activities.course_id', '=', $course_id)->get();

        $outcomeAssessments = AssessmentMethod::join('outcome_assessments', 'assessment_methods.a_method_id', '=', 'outcome_assessments.a_method_id')
            ->join('learning_outcomes', 'outcome_assessments.l_outcome_id', '=', 'learning_outcomes.l_outcome_id')
            ->select('assessment_methods.a_method_id', 'assessment_methods.a_method', 'outcome_assessments.l_outcome_id', 'learning_outcomes.l_outcome')
            ->where('assessment_methods.course_id', '=', $course_id)->get();

        $courseStandardCategory = $course->standardCategory;
        $courseStandardOutcomes = $courseStandardCategory->standards;
        $courseStandardScalesCategory = $course->standardScalesCategory;
        $courseStandardScales = $courseStandardScalesCategory->standardScales;

        $standardOutcomeMap = [];
        foreach ($courseStandardOutcomes as $standardOutcome) {
            if (StandardsOutcomeMap::where('standard_id', $standardOutcome->standard_id)->where('course_id', $course->course_id)->exists()) {
                $standardScale=StandardsOutcomeMap::firstWhere([['standard_id', $standardOutcome->standard_id], ['course_id', $course->course_id]]);
                
                $standardOutcomeMap[$standardOutcome->standard_id][$course->course_id] = StandardScale::where('standard_scale_id',$standardScale->standard_scale_id)->first();
                
            }
        }

        $assessmentMethodsTotal = 0;
        foreach ($course->assessmentMethods as $a_method) {
            $assessmentMethodsTotal += $a_method->weight;
        }

        // get subcategories for optional priorities
        $optionalPriorities = $course->optionalPriorities;
        $optionalSubcategories = [];
        foreach ($optionalPriorities as $optionalPriority) {
            $optionalSubcategories[$optionalPriority->subcat_id] = $optionalPriority->optionalPrioritySubcategory;
        }

        return view('courses.wizard.step7')->with('program', $tempProgram)->with('course', $course)->with('outcomeActivities', $outcomeActivities)->with('outcomeAssessments', $outcomeAssessments)->with('user', $user)->with('oAct', $oActCount)
            ->with('oAss', $oAssCount)->with('outcomeMapsCount', $outcomeMapsCount)->with('courseProgramsOutcomeMaps', $courseProgramsOutcomeMaps)->with('assessmentMethodsTotal', $assessmentMethodsTotal)
            ->with('standardsOutcomeMap', $standardsOutcomeMap)->with('isEditor', $isEditor)->with('isViewer', $isViewer)->with('courseUsers', $courseUsers)->with('optionalSubcategories', $optionalSubcategories)
            ->with('standardsOutcomeMapCount', $standardsOutcomeMapCount)->with('standard_categories', $standard_categories)->with('expectedStandardOutcomeMapCount', $expectedStandardOutcomeMapCount)
            ->with('expectedProgramOutcomeMapCount', $expectedProgramOutcomeMapCount)->with('hasNonAlignedCLO', $hasNonAlignedCLO)->with('l_outcomes', $l_outcomes)->with('standardOutcomeMap', $standardOutcomeMap);
    }
}
