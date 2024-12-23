<?php

namespace App\Http\Controllers;

use App\Mail\NotifyInstructorForMappingMail;
use App\Mail\NotifyNewCourseInstructorMail;
use App\Mail\NotifyNewUserAndInstructorMail;
use App\Models\AssessmentMethod;
use App\Models\Course;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Conditional;
use PhpOffice\PhpSpreadsheet\Style\ConditionalFormatting\Wizard;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Style;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Models\CourseOptionalPriorities;
use App\Models\CourseProgram;
use App\Models\CourseUser;
use App\Models\LearningActivity;
use App\Models\LearningOutcome;
use App\Models\MappingScale;
use App\Models\OutcomeActivity;
use App\Models\OutcomeAssessment;
use App\Models\OutcomeMap;
use App\Models\PLOCategory;
use App\Models\Program;
use App\Models\ProgramLearningOutcome;
use App\Models\Standard;
use App\Models\StandardScale;
use App\Models\StandardsOutcomeMap;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use PDF;
use Throwable;

class CourseController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
        $this->middleware('course')->only(['show', 'pdf', 'edit', 'submit', 'outcomeDetails']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): RedirectResponse
    {
        return redirect()->back();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        // validate request input
        $this->validate($request, [
            'course_code' => 'required|max:4',
            'course_title' => 'required',
            'course_num' => 'max:30',
        ]);

        $course = new Course;
        $course->course_title = $request->input('course_title');
        $course->course_num = $request->input('course_num');
        $course->course_code = strtoupper($request->input('course_code'));
        // status of mapping process
        $course->status = -1;
        // course required for program
        $course->required = $request->input('required');
        $course->type = $request->input('type');

        $course->delivery_modality = $request->input('delivery_modality');
        $course->year = $request->input('course_year');
        $course->semester = $request->input('course_semester');
        $course->section = $request->input('course_section');
        $course->standard_category_id = $request->input('standard_category_id');
        $user = User::find(Auth::id());
        $course->last_modified_user = $user->name;

        // course creation triggered by add new course for program
        if ($request->input('type') == 'assigned') {
            $isCourseRequired = $request->input('required');
            // course not yet assigned to an instructor
            $course->assigned = -1;
            $course->save();

            if ($request->input('email') == null) {
                // User Field is Empty
                // The user who created this course will be the owner
                $user = User::where('id', $request->input('user_id'))->first();
            } else {
                // assign the user specified to own this course
                // check if user exists in db
                if (User::where('email', $request->input('email'))->exists()) {
                    $user = User::where('email', $request->input('email'))->first();
                    // TODO: Send email to new course owner
                    $currentUser = User::where('id', $request->input('user_id'))->first();
                    $program = Program::where('program_id', $request->input('program_id'))->first();
                    Mail::to($user->email)->send(new NotifyNewCourseInstructorMail($course->course_code, $course->course_num == null ? ' ' : $course->course_num, $course->course_title, $currentUser->name, $program->program));
                } else {
                    // Create new user and assign them to the new course
                    $name = explode('@', $request->input('email'));
                    $user = new User;
                    $user->name = $name[0];
                    $user->email = $request->input('email');
                    $user->has_temp = 1;
                    // generate random password
                    $comb = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
                    $pass = [];
                    $combLen = strlen($comb) - 1;
                    for ($i = 0; $i < 8; $i++) {
                        $n = rand(0, $combLen);
                        $pass[] = $comb[$n];
                    }
                    // store random password
                    $user->password = Hash::make(implode($pass));
                    $user->email_verified_at = Carbon::now();
                    $user->save();

                    $currentUser = User::where('id', $request->input('user_id'))->first();
                    $program = Program::where('program_id', $request->input('program_id'))->first();
                    // TODO: Send email to new user
                    Mail::to($user->email)->send(new NotifyNewUserAndInstructorMail($course->course_code, $course->course_num == null ? ' ' : $course->course_num, $course->course_title, $currentUser->name, implode($pass), $user->email, $program->program));
                }
            }

            $courseUser = new CourseUser;
            $courseUser->course_id = $course->course_id;
            $courseUser->user_id = $user->id;
            // assign the creator of the course the owner permission
            $courseUser->permission = 1;

            //Store and associate in the course_programs table
            $courseProgram = new CourseProgram;
            $courseProgram->course_id = $course->course_id;
            $courseProgram->program_id = $request->input('program_id');
            $courseProgram->course_required = $isCourseRequired;

            if ($courseUser->save()) {
                if ($courseProgram->save()) {
                    // update courses 'updated_at' field
                    $program = Program::find($request->input('program_id'));
                    $program->touch();

                    // get users name for last_modified_user
                    $user = User::find(Auth::id());
                    $program->last_modified_user = $user->name;
                    $program->save();

                    $request->session()->flash('success', 'New course added');
                }
            } else {
                $request->session()->flash('error', 'There was an error adding the course');
            }

            return redirect()->route('programWizard.step3', $request->input('program_id'));

        // course creation triggered by add new course on dashboard
        } else {
            // course assigned to course creator
            $course->assigned = 1;
            $course->save();

            $user = User::where('id', $request->input('user_id'))->first();
            $courseUser = new CourseUser;
            $courseUser->course_id = $course->course_id;
            $courseUser->user_id = $user->id;
            // assign the creator of the course the owner permission
            $courseUser->permission = 1;
            if ($courseUser->save()) {
                $request->session()->flash('success', 'New course added');
            } else {
                $request->session()->flash('error', 'There was an error adding the course');
            }

            return redirect()->route('courseWizard.step1', $course->course_id);
        }

    }

    /**
     * Copy a existed resource and assign it to the program.
     */
    public function addProgramToCourse(Request $request): RedirectResponse
    {
        $this->validate($request, [
            'course_id' => 'required',
            'program_id' => 'required',
        ]);

        $program_id = $request->input('program_id');
        $course_id = $request->input('course_id');

        $course = Course::where('course_id', $course_id)->first();
        $course->program_id = $program_id;
        $course->status = -1;
        $course->assigned = -1;

        foreach ($course_id as $index => $course_i) {
            $requires = $request->input('require'.$course_i[$index]);
            $course->required = $requires;
        }

        if ($course->save()) {
            $request->session()->flash('success', 'New course added');
        } else {
            $request->session()->flash('error', 'There was an error adding the course');
        }

        return redirect()->route('programWizard.step3', $request->input('program_id'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     */
    public function show($course_id): View
    {
        //
        $course = Course::where('course_id', $course_id)->first();
        $program = Program::where('program_id', $course->program_id)->first();
        $a_methods = AssessmentMethod::where('course_id', $course_id)->get();
        $l_activities = LearningActivity::where('course_id', $course_id)->get();
        $l_outcomes = LearningOutcome::where('course_id', $course_id)->get();
        $pl_outcomes = ProgramLearningOutcome::where('program_id', $course->program_id)->get();
        // $mappingScales = MappingScale::where('program_id', $course->program_id)->get();
        $mappingScales = MappingScale::join('mapping_scale_programs', 'mapping_scales.map_scale_id', '=', 'mapping_scale_programs.map_scale_id')
            ->where('mapping_scale_programs.program_id', $course->program_id)->get();
        $ploCategories = PLOCategory::where('program_id', $course->program_id)->get();

        $outcomeActivities = LearningActivity::join('outcome_activities', 'learning_activities.l_activity_id', '=', 'outcome_activities.l_activity_id')
            ->join('learning_outcomes', 'outcome_activities.l_outcome_id', '=', 'learning_outcomes.l_outcome_id')
            ->select('outcome_activities.l_activity_id', 'learning_activities.l_activity', 'outcome_activities.l_outcome_id', 'learning_outcomes.l_outcome')
            ->where('learning_activities.course_id', '=', $course_id)->get();

        $outcomeAssessments = AssessmentMethod::join('outcome_assessments', 'assessment_methods.a_method_id', '=', 'outcome_assessments.a_method_id')
            ->join('learning_outcomes', 'outcome_assessments.l_outcome_id', '=', 'learning_outcomes.l_outcome_id')
            ->select('assessment_methods.a_method_id', 'assessment_methods.a_method', 'outcome_assessments.l_outcome_id', 'learning_outcomes.l_outcome')
            ->where('assessment_methods.course_id', '=', $course_id)->get();

        $outcomeMaps = ProgramLearningOutcome::join('outcome_maps', 'program_learning_outcomes.pl_outcome_id', '=', 'outcome_maps.pl_outcome_id')
            ->join('learning_outcomes', 'outcome_maps.l_outcome_id', '=', 'learning_outcomes.l_outcome_id')
            ->select('outcome_maps.map_scale_id', 'outcome_maps.pl_outcome_id', 'program_learning_outcomes.pl_outcome', 'outcome_maps.l_outcome_id', 'learning_outcomes.l_outcome')
            ->where('learning_outcomes.course_id', '=', $course_id)->get();

        return view('courses.summary')->with('course', $course)
            ->with('program', $program)
            ->with('l_outcomes', $l_outcomes)
            ->with('pl_outcomes', $pl_outcomes)
            ->with('l_activities', $l_activities)
            ->with('a_methods', $a_methods)
            ->with('outcomeActivities', $outcomeActivities)
            ->with('outcomeAssessments', $outcomeAssessments)
            ->with('outcomeMaps', $outcomeMaps)
            ->with('mappingScales', $mappingScales)
            ->with('ploCategories', $ploCategories);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     */
    public function edit($course_id): RedirectResponse
    {
        //
        $course = Course::where('course_id', $course_id)->first();
        $course->status = -1;
        $course->save();

        return redirect()->route('courseWizard.step1', $course_id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     */
    public function update(Request $request, $course_id): RedirectResponse
    {
        //
        $this->validate($request, [
            'course_code' => 'required',
            'course_title' => 'required',
        ]);

        $course = Course::where('course_id', $course_id)->first();
        $course->course_num = $request->input('course_num');
        $course->course_code = strtoupper($request->input('course_code'));
        $course->course_title = $request->input('course_title');
        $course->required = $request->input('required');

        $course->delivery_modality = $request->input('delivery_modality');
        $course->year = $request->input('course_year');
        $course->semester = $request->input('course_semester');
        $course->section = $request->input('course_section');

        // if standard category id has been updated then, delete all old standard mappings
        if ($course->standard_category_id != $request->input('standard_category_id')) {
            StandardsOutcomeMap::where('course_id', $course->course_id)->delete();
            // assign new standard category id for course.
            $course->standard_category_id = $request->input('standard_category_id');
        }

        if ($course->save()) {
            // update courses 'updated_at' field
            $course = Course::find($course_id);
            $course->touch();

            // get users name for last_modified_user
            $user = User::find(Auth::id());
            $course->last_modified_user = $user->name;
            $course->save();

            $request->session()->flash('success', 'Course updated');
        } else {
            $request->session()->flash('error', 'There was an error updating the course');
        }

        return redirect()->back();

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     */
    public function destroy(Request $request, $course_id): RedirectResponse
    {
        // find the course to delete
        $course = Course::find($course_id);
        // find the current user
        $currentUser = User::find(Auth::id());
        //get the current users permission level for the course delete
        $currentUserPermission = $currentUser->courses->where('course_id', $course_id)->first()->pivot->permission;
        // if the current user own the course, then try to delete it
        if ($currentUserPermission == 1) {
            if ($course->delete()) {
                $request->session()->flash('success', 'Course has been deleted');
            } else {
                $request->session()->flash('error', 'There was an error deleting the course');
            }
        } else {
            $request->session()->flash('error', 'You do not have permission to delete this course');
        }

        return redirect()->route('home');
    }

    public function submit(Request $request, $course_id): RedirectResponse
    {
        //
        $c = Course::where('course_id', $course_id)->first();
        $c->status = 1;

        if ($c->save()) {
            $request->session()->flash('success', 'Your answers have	been submitted successfully');
        } else {
            $request->session()->flash('error', 'There was an error submitting your answers');
        }

        return redirect()->route('home');
    }

    public function outcomeDetails(Request $request, $course_id): RedirectResponse
    {
        //
        $l_outcomes = LearningOutcome::where('course_id', $course_id)->get();

        foreach ($l_outcomes as $l_outcome) {
            $i = $l_outcome->l_outcome_id;

            if ($request->input('l_activities') == null) {

                $l_outcome->learningActivities()->detach();

            } elseif (array_key_exists($i, $request->input('l_activities'))) {
                $arr = $request->input('l_activities');
                $l_outcome->learningActivities()->detach();
                $l_outcome->learningActivities()->sync($arr[$i]);

            } else {

                $l_outcome->learningActivities()->detach();
            }

        }

        foreach ($l_outcomes as $l_outcome) {
            $i = $l_outcome->l_outcome_id;

            if ($request->input('a_methods') == null) {

                $l_outcome->assessmentMethods()->detach();

            } elseif (array_key_exists($i, $request->input('a_methods'))) {
                $arr = $request->input('a_methods');
                $l_outcome->assessmentMethods()->detach();
                $l_outcome->assessmentMethods()->sync($arr[$i]);

            } else {

                $l_outcome->assessmentMethods()->detach();
            }

        }

        return redirect()->route('courseWizard.step4', $course_id)->with('success', 'Changes have been saved successfully.');
    }

    public function amReorder(Request $request, $course_id): RedirectResponse
    {
        $a_method_pos = $request->input('a_method_pos');

        if ($a_method_pos) {
            foreach ($a_method_pos as $pos => $a_method_id) {
                $aMethod = AssessmentMethod::find($a_method_id);
                $aMethod->pos_in_alignment = $pos + 1;
                $aMethod->save();
            }
        }

        // update courses 'updated_at' field
        $course = Course::find($course_id);
        $course->touch();

        // get users name for last_modified_user
        $user = User::find(Auth::id());
        $course->last_modified_user = $user->name;
        $course->save();

        return redirect()->route('courseWizard.step2', $course_id)->with('success', 'Changes have been saved successfully.');
    }

    public function loReorder(Request $request, $course_id): RedirectResponse
    {
        $l_outcomes_pos = $request->input('l_outcomes_pos');

        if ($l_outcomes_pos) {
            foreach ($l_outcomes_pos as $pos => $l_outcome_id) {
                $learningOutcome = LearningOutcome::find($l_outcome_id);
                $learningOutcome->pos_in_alignment = $pos + 1;
                $learningOutcome->save();
            }
        }

        // update courses 'updated_at' field
        $course = Course::find($course_id);
        $course->touch();

        // get users name for last_modified_user
        $user = User::find(Auth::id());
        $course->last_modified_user = $user->name;
        $course->save();

        return redirect()->route('courseWizard.step1', $course_id)->with('success', 'Changes have been saved successfully.');
    }

    public function tlaReorder(Request $request, $course_id): RedirectResponse
    {
        $l_activities_pos = $request->input('l_activities_pos');

        if ($l_activities_pos) {
            foreach ($l_activities_pos as $pos => $l_activity_id) {
                $learningActivity = LearningActivity::find($l_activity_id);
                $learningActivity->l_activities_pos = $pos + 1;
                $learningActivity->save();
            }
        }

        // update courses 'updated_at' field
        $course = Course::find($course_id);
        $course->touch();

        // get users name for last_modified_user
        $user = User::find(Auth::id());
        $course->last_modified_user = $user->name;
        $course->save();

        return redirect()->route('courseWizard.step3', $course_id)->with('success', 'Changes have been saved successfully.');
    }

    public function pdf(Request $request, $course_id)
    {

        // set the max time to generate a pdf summary as 5 mins/300 seconds
        set_time_limit(300);
        try {
            // get the course
            $course = Course::find($course_id);
            // get the course learning outcomes in order specified by user
            $courseLearningOutcomes = $course->learningOutcomes()->orderBy('pos_in_alignment', 'asc')->get();
            // get all the programs this course belongs to
            $coursePrograms = Course::find($course_id)->programs;
            // get the PLOs for each program
            $programsLearningOutcomes = [];
            // get the mapping scale levels for each program
            $programsMappingScales = [];
            // get the uncategorized PLOs for each program
            $unCategorizedProgramsLearningOutcomes = [];
            foreach ($coursePrograms as $courseProgram) {
                // get the plos for this program
                $plos = $courseProgram->programLearningOutcomes;
                $programsLearningOutcomes[$courseProgram->program_id] = $plos;
                // get the mapping scale levels for this program and add N/A scale to the collection
                $programsMappingScales[$courseProgram->program_id] = $courseProgram->mappingScaleLevels->push(MappingScale::find(0));
                $unCategorizedProgramsLearningOutcomes[$courseProgram->program_id] = $plos->filter(function ($plo, $key) {
                    return ! isset($plo->category);
                });
            }
            // courseProgramsOutcomeMaps[$program_id][$plo][$clo] = map_scale_id
            $courseProgramsOutcomeMaps = [];
            foreach ($programsLearningOutcomes as $programId => $programLearningOutcomes) {
                foreach ($programLearningOutcomes as $programLearningOutcome) {
                    $outcomeMaps = $programLearningOutcome->learningOutcomes->where('course_id', $course_id);
                    foreach ($outcomeMaps as $outcomeMap) {
                        $courseProgramsOutcomeMaps[$programId][$programLearningOutcome->pl_outcome_id][$outcomeMap->l_outcome_id] = MappingScale::find($outcomeMap->pivot->map_scale_id);
                    }
                }
            }
            //
            $coursePrograms->map(function ($courseProgram, $key) {
                $courseProgram->push(0, 'num_plos_categorized');
                $courseProgram->programLearningOutcomes->each(function ($plo, $key) use ($courseProgram) {
                    if (isset($plo->category)) {
                        $courseProgram->num_plos_categorized++;
                    }
                });
            });
            //
            $outcomeActivities = LearningActivity::join('outcome_activities', 'learning_activities.l_activity_id', '=', 'outcome_activities.l_activity_id')
                ->join('learning_outcomes', 'outcome_activities.l_outcome_id', '=', 'learning_outcomes.l_outcome_id')
                ->select('outcome_activities.l_activity_id', 'learning_activities.l_activity', 'outcome_activities.l_outcome_id', 'learning_outcomes.l_outcome')
                ->where('learning_activities.course_id', '=', $course_id)->get();
            //
            $outcomeAssessments = AssessmentMethod::join('outcome_assessments', 'assessment_methods.a_method_id', '=', 'outcome_assessments.a_method_id')
                ->join('learning_outcomes', 'outcome_assessments.l_outcome_id', '=', 'learning_outcomes.l_outcome_id')
                ->select('assessment_methods.a_method_id', 'assessment_methods.a_method', 'outcome_assessments.l_outcome_id', 'learning_outcomes.l_outcome')
                ->where('assessment_methods.course_id', '=', $course_id)->get();

            // ministry standards
            $courseStandardCategory = $course->standardCategory;
            $courseStandardOutcomes = $courseStandardCategory->standards;
            $courseStandardScalesCategory = $course->standardScalesCategory;
            $courseStandardScales = $courseStandardScalesCategory->standardScales;

            $standardOutcomeMap = [];
            foreach ($courseStandardOutcomes as $standardOutcome) {
                if (StandardsOutcomeMap::where('standard_id', $standardOutcome->standard_id)->where('course_id', $course->course_id)->exists()) {
                                //dd(StandardsOutcomeMap::firstWhere([['standard_id', $standardOutcome->standard_id], ['course_id', $course->course_id]]));
                                $standardScale=StandardsOutcomeMap::firstWhere([['standard_id', $standardOutcome->standard_id], ['course_id', $course->course_id]]);
                                //dd($standardScale->standard_scale_id);
                                $standardOutcomeMap[$standardOutcome->standard_id][$course->course_id] = StandardScale::where('standard_scale_id',$standardScale->standard_scale_id)->first();
                                //$standardOutcomeMap[$standardOutcome->standard_id][$course->course_id] = StandardScale::find(StandardsOutcomeMap::firstWhere([['standard_id', $standardOutcome->standard_id], ['course_id', $course->course_id]]))->first();
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
            // build pdf objcet
            $pdf = PDF::loadView('courses.downloadSummary', compact('course', 'courseLearningOutcomes', 'programsLearningOutcomes', 'unCategorizedProgramsLearningOutcomes', 'programsMappingScales', 'outcomeActivities', 'outcomeAssessments', 'courseStandardOutcomes', 'courseStandardScales', 'standardOutcomeMap', 'assessmentMethodsTotal', 'courseProgramsOutcomeMaps', 'optionalSubcategories'));
            // get the content of the pdf document
            $content = $pdf->output();
            // store the pdf document in storage/app/public folder
            Storage::put('public/course-'.$course->course_id.'.pdf', $content);
            // get the url of the document
            $url = Storage::url('course-'.$course->course_id.'.pdf');

            // return the location of the pdf document on the server
            return $url;

        } catch (Throwable $exception) {
            $message = 'There was an error downloading your course summary report';
            Log::error($message.' ...\n');
            Log::error('Code - '.$exception->getCode());
            Log::error('File - '.$exception->getFile());
            Log::error('Line - '.$exception->getLine());
            Log::error($exception->getMessage());

            return -1;

        }
    }

    public function deletePDF(Request $request, $course_id)
    {
        Storage::delete('public/course-'.$course_id.'.pdf');
    }

    // Method for generating data excel in course level
    public function dataSpreadsheet(Request $request, $course_id)
    {
        Log::Debug("Course id");
        Log::Debug($course_id);
        // set the max time to generate a pdf summary as 5 mins/300 seconds
        set_time_limit(300);
        try {
            $course = Course::where('course_id',$course_id)->first();
            // create the spreadsheet
            $spreadsheet = new Spreadsheet();
            // create array of column names
            $columns = range('A', 'Z');
            // create array of styles for spreadsheet
            $styles = [
                'primaryHeading' => [
                    'font' => ['bold' => true],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'color' => ['rgb' => 'C6E0F5'],
                    ],
                ],
                'secondaryHeading' => [
                    'font' => ['bold' => true],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'color' => ['rgb' => 'ced4da'],
                    ],
                ],
            ];

            $courseProgram = CourseProgram::where('course_id', $course_id)->first();
            if($courseProgram!=NULL){
                
                $courseProgram = CourseProgram::where('course_id', $course_id)->first();
                $programLearningOutcomes = ProgramLearningOutcome::where('program_id', $courseProgram->program_id)->get();
                if(count($programLearningOutcomes)>0){

                    $courseSheet = $this->makeCourseInfoSheetData($spreadsheet, $course_id, $styles);
                    Log::Debug("Course Data (CourseInfoSheetData) Sheet");
                    $programSheet= $this->makeProgramOutcomeSheetData($spreadsheet, $course_id, $styles);
                    Log::Debug("Course Data (programSheet) Sheet");
                    $mappingScaleSheet=$this->makeMappingScalesSheetData($spreadsheet, $course_id, $styles);
                    Log::Debug("Course Data (mappingScaleSheet) Sheet");
                    $outcomeSheet=$this->makeOutcomeMapSheetData($spreadsheet, $course_id, $styles, $columns);
                    Log::Debug("Course Data (outcomeSheet) Sheet");
                    $assessmentMethodSheet=$this->makeAssessmentMapSheetData($spreadsheet, $course_id, $styles, $columns);
                    Log::Debug("Course Data (AM) Sheet");
                    $learningActivitySheet=$this->makeLearningActivityMapSheetData($spreadsheet, $course_id, $styles, $columns);
                    Log::Debug("Course Data (LA) Sheet");
                    $bcScaleSheet =$this->BcMappingScalesData($spreadsheet,$styles);
                    Log::Debug("Course Data (bcScaleSheet) Sheet");
                    $bcMappedSheet=$this->makeBcStandardMapSheetData($spreadsheet, $course_id, $styles);
                    Log::Debug("Course Data (bcMappedSheet) Sheet");


                    array_walk($columns, function ($letter, $index) use ($courseSheet,$programSheet, $mappingScaleSheet, $bcScaleSheet,$outcomeSheet, $bcMappedSheet,$assessmentMethodSheet, $learningActivitySheet)
                    {
                        $courseSheet->getColumnDimension($letter)->setAutoSize(true);
                        $programSheet->getColumnDimension($letter)->setAutoSize(true);
                        $mappingScaleSheet->getColumnDimension($letter)->setAutoSize(true);
                        $bcScaleSheet->getColumnDimension($letter)->setAutoSize(true);
                        $outcomeSheet->getColumnDimension($letter)->setAutoSize(true);
                        $bcMappedSheet->getColumnDimension($letter)->setAutoSize(true);
                        $assessmentMethodSheet->getColumnDimension($letter)->setAutoSize(true);
                        $learningActivitySheet->getColumnDimension($letter)->setAutoSize(true);
                    });
                }else{
                    $courseSheet = $this->makeCourseInfoSheetData($spreadsheet, $course_id, $styles);
                    Log::Debug("Course Data (CourseInfoSheetData) Sheet");
                    $programSheet= $this->makeProgramOutcomeSheetData($spreadsheet, $course_id, $styles);
                    Log::Debug("Course Data (programSheet) Sheet");
                    $mappingScaleSheet=$this->makeMappingScalesSheetData($spreadsheet, $course_id, $styles);
                    Log::Debug("Course Data (mappingScaleSheet) Sheet");
                    $bcScaleSheet =$this->BcMappingScalesData($spreadsheet,$styles);
                    Log::Debug("Course Data (bcScaleSheet) Sheet");
                    //$outcomeSheet=$this->makeOutcomeMapSheetData($spreadsheet, $course_id, $styles, $columns);
                    Log::Debug("Course Data (outcomeSheet) Sheet");
                    $bcMappedSheet=$this->makeBcStandardMapSheetData($spreadsheet, $course_id, $styles);
                    Log::Debug("Course Data (bcMappedSheet) Sheet");
                    $assessmentMethodSheet=$this->makeAssessmentMapSheetData($spreadsheet, $course_id, $styles, $columns);
                    Log::Debug("Course Data (AM) Sheet");
                    $learningActivitySheet=$this->makeLearningActivityMapSheetData($spreadsheet, $course_id, $styles, $columns);
                    Log::Debug("Course Data (LA) Sheet");
    
    
                    array_walk($columns, function ($letter, $index) use ($courseSheet,$programSheet, $mappingScaleSheet, $bcScaleSheet, $bcMappedSheet,$assessmentMethodSheet, $learningActivitySheet)
                    {
                        $courseSheet->getColumnDimension($letter)->setAutoSize(true);
                        $programSheet->getColumnDimension($letter)->setAutoSize(true);
                        $mappingScaleSheet->getColumnDimension($letter)->setAutoSize(true);
                        $bcScaleSheet->getColumnDimension($letter)->setAutoSize(true);
                        //$outcomeSheet->getColumnDimension($letter)->setAutoSize(true);
                        $bcMappedSheet->getColumnDimension($letter)->setAutoSize(true);
                        $assessmentMethodSheet->getColumnDimension($letter)->setAutoSize(true);
                        $learningActivitySheet->getColumnDimension($letter)->setAutoSize(true);
                    });
                }

            }else{

                $courseSheet = $this->makeCourseInfoSheetData($spreadsheet, $course_id, $styles);
                $bcScaleSheet =$this->BcMappingScalesData($spreadsheet,$styles);
                $bcMappedSheet=$this->makeBcStandardMapSheetData($spreadsheet, $course_id, $styles);
                $assessmentMethodSheet=$this->makeAssessmentMapSheetData($spreadsheet, $course_id, $styles, $columns);
                $learningActivitySheet=$this->makeLearningActivityMapSheetData($spreadsheet, $course_id, $styles, $columns);
    
    
                array_walk($columns, function ($letter, $index) use ($courseSheet, $bcScaleSheet, $bcMappedSheet,$assessmentMethodSheet, $learningActivitySheet)
                {
                    $courseSheet->getColumnDimension($letter)->setAutoSize(true);
                    $bcScaleSheet->getColumnDimension($letter)->setAutoSize(true);
                    $bcMappedSheet->getColumnDimension($letter)->setAutoSize(true);
                    $assessmentMethodSheet->getColumnDimension($letter)->setAutoSize(true);
                    $learningActivitySheet->getColumnDimension($letter)->setAutoSize(true);
                });
    

            }

            // generate the spreadsheet
            $writer = new Xlsx($spreadsheet);
            // set the spreadsheets name
            $spreadsheetName = 'data-summary-'.$course->course_title.'.xlsx';
            // create absolute filename
            $storagePath = storage_path('app'.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.'spreadsheets'.DIRECTORY_SEPARATOR.$spreadsheetName);
            // save the spreadsheet document
            $writer->save($storagePath);
            // get the url of the document
            $url = Storage::url('spreadsheets'.DIRECTORY_SEPARATOR.$spreadsheetName);

            // return the location of the spreadsheet document on the server
            return $url;
        }
        catch (Throwable $exception) {
            $message = 'There was an error downloading the spreadsheet overview for: '.$course->course;
            Log::error($message.' ...\n');
            Log::error('Code - '.$exception->getCode());
            Log::error('File - '.$exception->getFile());
            Log::error('Line - '.$exception->getLine());
            Log::error($exception->getMessage());

            return -1;
        }
    }


    // Removes the program id for a given course (Used In program wizard step 3).
    public function removeFromProgram(Request $request, $course_id): RedirectResponse
    {

        // Delete row from coursePrograms
        if (CourseProgram::where('course_id', $course_id)->where('program_id', $request->input('program_id'))->delete()) {

            // Retreive all plos and clos in an array storing their id's
            $plos = ProgramLearningOutcome::where('program_id', $request->input('program_id'))->pluck('pl_outcome_id')->toArray();
            $clos = LearningOutcome::where('course_id', $course_id)->pluck('l_outcome_id')->toArray();
            // loop through arrays
            foreach ($plos as $plo) {
                foreach ($clos as $clo) {
                    // check if outcome map exists for plo and clo
                    if (OutcomeMap::where('pl_outcome_id', $plo)->where('l_outcome_id', $clo)->exists()) {
                        // delete row
                        OutcomeMap::where('pl_outcome_id', $plo)->where('l_outcome_id', $clo)->delete();
                    }
                }
            }

            // update courses 'updated_at' field
            $program = Program::find($request->input('program_id'));
            $program->touch();

            // get users name for last_modified_user
            $user = User::find(Auth::id());
            $program->last_modified_user = $user->name;
            $program->save();

            $request->session()->flash('success', 'Course updated');
        } else {
            $request->session()->flash('error', 'There was an error removing the course');
        }

        return redirect()->route('programWizard.step3', $request->input('program_id'));
    }

    public function emailCourseInstructor(Request $request, $course_id): RedirectResponse
    {
        $program_owner = User::find($request->input('program_owner_id'));
        $course_owner = User::find($request->input('course_owner_id'));
        $course = Course::find($course_id);
        $program = Program::find($request->input('program_id'));
        $required = (CourseProgram::where('course_id', $course_id)->where('program_id', $request->input('program_id'))->pluck('course_required')->first() == '1' ? 'required' : 'an elective');

        // disables button on the front end to allow user to notify Instructor more then once
        CourseProgram::where('course_id', $course_id)->where('program_id', $request->input('program_id'))->update(['map_status' => 1]);

        Mail::to($course_owner->email)->send(new NotifyInstructorForMappingMail($program->program, $program_owner->name, $course->course_code, $course->course_num, $course->course_title, $required));
        if (! count(Mail::failures()) > 0) {
            $request->session()->flash('success', $course_owner->name.' has been asked to map their course to your program');
        } else {
            $request->session()->flash('error', 'There was an error notifying the course instructor');
        }

        return redirect()->route('programWizard.step3', $request->input('program_id'));
    }

    public function duplicate(Request $request, $course_id): RedirectResponse
    {

        $this->validate($request, [
            'course_code' => 'required',
            'course_num' => 'required',
            'course_title' => 'required',

        ]);

        $course_old = Course::find($course_id);
        $course = new Course;
        $course->course_title = $request->input('course_title');
        $course->section = $request->input('course_section');
        $course->course_code = strtoupper($request->input('course_code'));
        // remove leading zeros from course number
        $CNum = $request->input('course_num');
        for ($i = 0; $i < strlen($CNum); $i++) {
            if ($CNum[$i] == '0') {
                $CNum = ltrim($CNum, $CNum[$i]);
            } else {
                // Found a value that's not '0'
                break;
            }
        }
        $course->course_num = $CNum;
        // status of mapping process
        $course->status = -1;
        // course required for program
        //TODO: Might need to remove these as they are depreciated
        $course->required = null;
        $course->type = 'unassigned';

        $course->delivery_modality = $course_old->delivery_modality;
        $course->year = $course_old->year;
        $course->semester = $course_old->semester;
        $course->standard_category_id = $course_old->standard_category_id;
        $course->scale_category_id = $course_old->scale_category_id;
        // course assigned to user
        $course->assigned = 1;
        $course->save();

        // This array is used to keep track of the id's for each assessment method duplicated
        // This is used for the course alignment step to decide which assessment method will be aligned (checked) for each clo
        $historyAssessmentMethods = [];
        // duplicate student assessment methods if they exist
        $assMeths = $course_old->assessmentMethods;
        foreach ($assMeths as $assMeth) {
            $newAssessmentMethod = new AssessmentMethod;
            $newAssessmentMethod->a_method = $assMeth->a_method;
            $newAssessmentMethod->weight = $assMeth->weight;
            $newAssessmentMethod->course_id = $course->course_id;
            $newAssessmentMethod->save();
            $historyAssessmentMethods[$assMeth->a_method_id] = $newAssessmentMethod->a_method_id;
        }

        // This array is used to keep track of the id's for each learning activity duplicated
        // This is used for the course alignment step to decide which learning activity will be aligned (checked) for each clo
        $historyLearningActivities = [];
        // duplicate Teaching and Learning Activities if they exist
        $tlas = $course_old->learningActivities;
        foreach ($tlas as $tla) {
            $newLearningActivity = new LearningActivity;
            $newLearningActivity->l_activity = $tla->l_activity;
            $newLearningActivity->course_id = $course->course_id;
            $newLearningActivity->save();
            $historyLearningActivities[$tla->l_activity_id] = $newLearningActivity->l_activity_id;
        }

        // duplicate clos and add them to the new course if they exist
        $clos = $course_old->learningOutcomes;
        foreach ($clos as $clo) {
            // CLOS
            $newCLO = new LearningOutcome;
            $newCLO->clo_shortphrase = $clo->clo_shortphrase;
            $newCLO->l_outcome = $clo->l_outcome;
            $newCLO->course_id = $course->course_id;
            $newCLO->save();

            // duplicate course alignment (Outcome Activities and Outcome Assessments) if they exist

            // duplicate outcome activities
            if ($clo->learningActivities()->exists()) {
                $oldLearningActivities = $clo->learningActivities()->get();
                foreach ($oldLearningActivities as $oldLearningActivity) {
                    $newOutcomeActivity = new OutcomeActivity;
                    $newOutcomeActivity->l_outcome_id = $newCLO->l_outcome_id;
                    $newOutcomeActivity->l_activity_id = $historyLearningActivities[$oldLearningActivity->l_activity_id];
                    $newOutcomeActivity->save();
                }
            }
            // duplicate outcome assessments
            if ($clo->assessmentMethods()->exists()) {
                $oldAssessmentMethods = $clo->assessmentMethods()->get();
                foreach ($oldAssessmentMethods as $oldAssessmentMethod) {
                    $newOutcomeAssessment = new OutcomeAssessment;
                    $newOutcomeAssessment->l_outcome_id = $newCLO->l_outcome_id;
                    $newOutcomeAssessment->a_method_id = $historyAssessmentMethods[$oldAssessmentMethod->a_method_id];
                    $newOutcomeAssessment->save();
                }
            }
        }

        $courseStandardCategory = $course->standardCategory;
        $courseStandardOutcomes = $courseStandardCategory->standards;
        // dd($courseStandardOutcomes);
        foreach ($courseStandardOutcomes as $standardOutcome) {
            if (StandardsOutcomeMap::where('standard_id', $standardOutcome->standard_id)->where('course_id', $course->course_id)->exists()) {
                $newStandardOutcomeMap = new StandardsOutcomeMap;
                $newStandardOutcomeMap->course_id = $course->course_id;
                $newStandardOutcomeMap->standard_id = $standardOutcome->standard_id;
                $newStandardOutcomeMap->standard_scale_id = StandardsOutcomeMap::where('standard_id', $standardOutcome->standard_id)->where('course_id', $course_old->course_id)->value('standard_scale_id');
                $newStandardOutcomeMap->save();
            }
        }

        // duplicate strategic (Optional) priorities
        $ops = $course_old->optionalPriorities;
        foreach ($ops as $op) {
            $newOptionalPriority = new CourseOptionalPriorities;
            $newOptionalPriority->op_id = $op->op_id;
            $newOptionalPriority->course_id = $course->course_id;
            $newOptionalPriority->save();
        }

        $user = User::find(Auth::id());
        $courseUser = new CourseUser;
        $courseUser->course_id = $course->course_id;
        $courseUser->user_id = $user->id;
        // assign the creator of the course the owner permission
        $courseUser->permission = 1;
        if ($courseUser->save()) {
            $request->session()->flash('success', 'Course has been duplicated');
        } else {
            $request->session()->flash('error', 'There was an error duplicating the course');
        }

        return redirect()->route('home');
    }

    /*
        Helper function to get this courses programs
    */
    public function getPrograms(Request $request, $courseId)
    {
        $course = Course::find($courseId);

        return $course->programs;
    }

    private function makeCourseInfoSheetData(Spreadsheet $spreadsheet, int $courseId, $styles): Worksheet
{
    try {
        // Find the program by ID
        $course = Course::find($courseId);
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Course Information');

        if ($course !== null) {
            // Update header row with the desired column names
            $sheet->fromArray(['Course Code', 'Course Number', 'Course Title', 'Term', 'Year', 'Course Section', 'Mode of Delivery'], null, 'A1');
            $sheet->getStyle('A1:G1')->applyFromArray($styles['primaryHeading']);

            // Insert the program data into the sheet
            $courseData = [
                $course->course_code,
                $course->course_num,
                $course->course_title,
                $course->semester,
                $course->year,
                $course->section,
                $course->delivery_modality,
            ];
            // Insert the array into the sheet starting from row 2, column A
            $sheet->fromArray($courseData, null, 'A2');
        }

        return $sheet;

    } catch (Throwable $exception) {
        $message = 'There was an error downloading the spreadsheet overview for: ' . ($course ? $course->course : 'Unknown Program');
        Log::error($message . ' ...\n');
        Log::error('Code - ' . $exception->getCode());
        Log::error('File - ' . $exception->getFile());
        Log::error('Line - ' . $exception->getLine());
        Log::error($exception->getMessage());

        return $exception;
    }
}

private function makeProgramOutcomeSheetData(Spreadsheet $spreadsheet, int $courseId, $styles): Worksheet
{
    try {
        
        $course = Course::find($courseId); 
        
        $courseProgram = CourseProgram::where('course_id', $courseId)->get();
        $PLOs=[];
        foreach($courseProgram as $courseP){
            $programId = $courseP->program_id;
            $program= Program::find($programId);
            $plosTemp = ProgramLearningOutcome::where('program_id', $programId)->get();
            foreach($plosTemp as $plo){
                
                $PLOCategory=PLOCategory::where('plo_category_id', $plo->plo_category_id)->value('plo_category');
                if($PLOCategory==NULL){
                    $PLOCategory="Uncategorized";
                }
                array_push($PLOs,                 [
                    $program->program,  
                    $plo->plo_shortphrase,     
                    $PLOCategory 
                ]);
            }
        }
        Log::Debug("PLO Array so Far");
        Log::Debug($PLOs);


        $programId = $courseProgram[0]->program_id;
        $program= Program::find($programId);
        
        $plos = ProgramLearningOutcome::where('program_id', $programId)->get();

        $sheet = $spreadsheet->createSheet();
        $sheet->setTitle('Program Outcomes');

        $sheet->fromArray(['Program', 'PLO', 'PLO Category'], null, 'A1');
        $sheet->getStyle('A1:C1')->applyFromArray($styles['primaryHeading']);

        $row = 2; 

        //Sorting PLOs to group by PLO Category
        usort($PLOs, function($a, $b) {
            return strcmp($a[2], $b[2]);
        });

        foreach ($PLOs as $plo) {


            $sheet->fromArray(
                [
                    $plo[0],  
                    $plo[1],     
                    $plo[2] 
                ],
                null,
                "A{$row}"
            );

            $row++; 
        }

        return $sheet;

    } catch (Throwable $exception) {
        // Handle errors and log them
        $message = "There was an error creating the Program Outcome sheet for Course ID: {$courseId}";
        Log::error($message);
        Log::error('Code: ' . $exception->getCode());
        Log::error('File: ' . $exception->getFile());
        Log::error('Line: ' . $exception->getLine());
        Log::error($exception->getMessage());

        return $exception;
    }
}


private function makeMappingScalesSheetData(Spreadsheet $spreadsheet, int $courseId, $styles): Worksheet
    {
        try {
            $course = Course::find($courseId);
            $courseProgram = CourseProgram::where('course_id',$courseId)->get();
            Log::Debug("CP Count");
            Log::Debug(count($courseProgram));
            $mappingScaleLevels=[];

            if(count($courseProgram)==1){
                $programId = $courseProgram[0]->program_id;
                $program= Program::find($programId);

                $mappingScaleLevels = $program->mappingScaleLevels;
    
            }else{
                foreach($courseProgram as $cProgram){
                $programId = $cProgram->program_id;
                $program= Program::find($programId);
                
                $mappingScaleLevelsTemp = $program->mappingScaleLevels;
                foreach($mappingScaleLevelsTemp as $msLevel){
                    array_push($mappingScaleLevels, $msLevel);
                }
                }
            }

            

            $sheet = $spreadsheet->createSheet();
            $sheet->setTitle('Mapping Scale');
            //bruh
            // create a wizard factory for creating new conditional formatting rules
            $wizardFactory = new Wizard('B2:Z50');
            foreach ($mappingScaleLevels as $level) {
                // create a new conditional formatting rule based on the map scale level
                $wizard = $wizardFactory->newRule(Wizard::CELL_VALUE);
                $levelStyle = new Style(false, true);
                $levelStyle->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setRGB(strtoupper(ltrim($level->colour, '#')));
                $levelStyle->getFill()
                    ->getEndColor()->setRGB(strtoupper(ltrim($level->colour, '#')));
                $wizard->equals($level->abbreviation)->setStyle($levelStyle);
                $conditionalStyles[] = $wizard->getConditional();
                // add conditional formatting rule to the outcome maps sheet
                $sheet->getStyle($wizard->getCellRange())->setConditionalStyles($conditionalStyles);
            }
            
            if (count($mappingScaleLevels) > 0) {
                // Update header row to exclude the 'Colour' column
                $sheet->fromArray(['Mapping Scale', 'Abbreviation', 'Description'], null, 'A1');
                $sheet->getStyle('A1:C1')->applyFromArray($styles['primaryHeading']);
    
                foreach ($mappingScaleLevels as $index => $level) {
                    // Create array of scale values without the colour column
                    $scaleArr = [$level->title, $level->abbreviation, $level->description];
                    // Insert the array into the sheet starting from column A
                    $sheet->fromArray($scaleArr, null, 'A'.strval($index + 2));
                }
            }

            
    
            return $sheet;
    

        } catch (Throwable $exception) {
            $message = 'There was an error downloading the spreadsheet overview for: '.$course->course_title;
            Log::error($message.' ...\n');
            Log::error('Code - '.$exception->getCode());
            Log::error('File - '.$exception->getFile());
            Log::error('Line - '.$exception->getLine());
            Log::error($exception->getMessage());

            return $exception;
        }
    }

    private function BcMappingScalesData(Spreadsheet $spreadsheet, $styles): Worksheet
    {
        $sheet = $spreadsheet->createSheet(); // Create a new sheet
        $sheet->setTitle('BC Standards Map Scale'); // Set the sheet title

        // Set up the header row
        $sheet->fromArray(['Mapping Scale', 'Abbreviation', 'Description'], null, 'A1');

        // Apply styles to the header row (optional)
        $sheet->getStyle('A1:C1')->applyFromArray($styles['primaryHeading']);

        $mappingScales=[];
        for($i=1;$i<=3;$i++){
            $mappingScale=MappingScale::where('map_scale_id', $i)->first();
            array_push($mappingScales, $mappingScale);
        }

        //bruh
        // create a wizard factory for creating new conditional formatting rules
        $wizardFactory = new Wizard('B2:Z50');
        foreach ($mappingScales as $level) {
            // create a new conditional formatting rule based on the map scale level
            $wizard = $wizardFactory->newRule(Wizard::CELL_VALUE);
            $levelStyle = new Style(false, true);
            $levelStyle->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()->setRGB(strtoupper(ltrim($level->colour, '#')));
            $levelStyle->getFill()
                ->getEndColor()->setRGB(strtoupper(ltrim($level->colour, '#')));
            $wizard->equals($level->abbreviation)->setStyle($levelStyle);
            $conditionalStyles[] = $wizard->getConditional();
            // add conditional formatting rule to the outcome maps sheet
            $sheet->getStyle($wizard->getCellRange())->setConditionalStyles($conditionalStyles);
        }

        if (count($mappingScales) > 0) {
            // Update header row to exclude the 'Colour' column
            $sheet->fromArray(['Mapping Scale', 'Abbreviation', 'Description'], null, 'A1');
            $sheet->getStyle('A1:C1')->applyFromArray($styles['primaryHeading']);

            foreach ($mappingScales as $index => $level) {
                // Create array of scale values without the colour column
                $scaleArr = [$level->title, $level->abbreviation, $level->description];
                // Insert the array into the sheet starting from column A
                $sheet->fromArray($scaleArr, null, 'A'.strval($index + 2));
            }
        }

         //$sheet->fromArray($mappingScales, null, 'A2');

         return $sheet;
    }


private function makeBcStandardMapSheetData(Spreadsheet $spreadsheet, int $courseId, $styles): Worksheet
{
    try {
        // Fetch the course details
        $course = Course::find($courseId);

        // Fetch all mappings for this course
        $standardsOutcomeMap = StandardsOutcomeMap::where('course_id', $courseId)->get();

        $sheet = $spreadsheet->createSheet();
        $sheet->setTitle('BC Degree Mapped');

        if ($standardsOutcomeMap->count() > 0) {
            // Add headers
            $sheet->fromArray(['Standard Short Phrase', 'Mapping Scale'], null, 'A1');
            $sheet->getStyle('A1:B1')->applyFromArray($styles['primaryHeading']);

            $rowIndex = 2; // Start from the second row
            foreach ($standardsOutcomeMap as $record) {
                // Fetch the corresponding standard and mapping scale
                $standard = Standard::find($record->standard_id);
               // Log::Debug('Standard');
                //Log::Debug($standard);
                $scale = StandardScale::find($record->standard_scale_id);
                //Log::Debug('Scale');
                //Log::Debug($scale);

                // Prepare row data
                $dataRow = [
                    $standard->s_shortphrase ?? 'N/A', // Short phrase from Standard model
                    $scale->abbreviation ?? 'N/A',     // Scale name from StandardScale model
                ];

                // Insert the row into the spreadsheet
                $sheet->fromArray($dataRow, null, 'A' . $rowIndex);
                $rowIndex++;
            }
        }

        //bruh
        $mappingScales=[];
        for($i=1;$i<=3;$i++){
            $mappingScale=MappingScale::where('map_scale_id', $i)->first();
            array_push($mappingScales, $mappingScale);
        }
        // create a wizard factory for creating new conditional formatting rules
        $wizardFactory = new Wizard('B2:Z50');
        foreach ($mappingScales as $level) {
            // create a new conditional formatting rule based on the map scale level
            $wizard = $wizardFactory->newRule(Wizard::CELL_VALUE);
            $levelStyle = new Style(false, true);
            $levelStyle->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()->setRGB(strtoupper(ltrim($level->colour, '#')));
            $levelStyle->getFill()
                ->getEndColor()->setRGB(strtoupper(ltrim($level->colour, '#')));
            $wizard->equals($level->abbreviation)->setStyle($levelStyle);
            $conditionalStyles[] = $wizard->getConditional();
            // add conditional formatting rule to the outcome maps sheet
            $sheet->getStyle($wizard->getCellRange())->setConditionalStyles($conditionalStyles);
        }

        return $sheet;

    } catch (Throwable $exception) {
        $message = 'There was an error downloading the spreadsheet overview for: ' . ($course->course_title ?? 'Unknown Course');
        Log::error($message . ' ...\n');
        Log::error('Code - ' . $exception->getCode());
        Log::error('File - ' . $exception->getFile());
        Log::error('Line - ' . $exception->getLine());
        Log::error($exception->getMessage());

        return $exception;
    }
}

private function makeAssessmentMapSheetData(Spreadsheet $spreadsheet, int $courseId, $styles, $columns): Worksheet
{
    try {
        // Find the program
        $course = Course::find($courseId);
        $assessmentMethodArray = [];
        $assessmentMethods = AssessmentMethod::where('course_id',$courseId)->get();
            if (count($assessmentMethods)==1 && $assessmentMethods!=NULL){
                array_push($assessmentMethodArray, $assessmentMethods);
            }else{
                if($assessmentMethods!=NULL){
                    foreach($assessmentMethods as $assessmentMethod){
                        array_push($assessmentMethodArray, $assessmentMethod);
                    }
                }
            }
        
        $courseLearningOutcomes = LearningOutcome::where('course_id', $courseId)->get();
        $courseLearningOutcomeShortPhrases = $courseLearningOutcomes->pluck('clo_shortphrase')->toArray();

        // Create a new sheet for Student Assessment Methods
        $sheet = $spreadsheet->createSheet();
        $sheet->setTitle('Assessment Methods');
        
        // Add primary headings (Courses, Student Assessment Method) to the sheet
        $sheet->fromArray(['Course Learning Outcomes', 'Student Assessment Methods'], null, 'A1');
        $sheet->getStyle('A1:B1')->applyFromArray($styles['primaryHeading']);
        if(count($assessmentMethodArray)==0){
            $sheet->mergeCells('B1:'.$columns[count($assessmentMethodArray)+1].'1');
        }else{
            $sheet->mergeCells('B1:'.$columns[count($assessmentMethodArray)].'1');
        }

        
        // Add CLOs to first column
        $sheet->fromArray(array_chunk($courseLearningOutcomeShortPhrases, 1), null, 'A3');
        $sheet->getStyle('A3:A'.strval(3 + count($courseLearningOutcomeShortPhrases) - 1))->applyFromArray($styles['secondaryHeading']);
        $sheet->getStyle('A3:A100')->getFont()->setBold(true);

        // Retrieve and map Student Assessment Methods with their weightages
        $categoryColInSheet = 1;
        foreach ($assessmentMethodArray as $assessmentMethod) {

            // Add assessment method to the sheet under the appropriate column
            if(count($assessmentMethodArray)==1){
                $sheet->setCellValue($columns[$categoryColInSheet].'2', $assessmentMethod[0]->a_method.' ('.$assessmentMethod[0]->weight.'%)');
            }else{
            $sheet->setCellValue($columns[$categoryColInSheet].'2', $assessmentMethod->a_method);
            }
            $sheet->getStyle($columns[$categoryColInSheet].'2')->applyFromArray($styles['secondaryHeading']);
            $sheet->mergeCells($columns[$categoryColInSheet].'2:'.$columns[$categoryColInSheet].'2');

            // Add the weightage for each course
            $assessmentWeightages = [];
            foreach ($courseLearningOutcomes as $CLO) {
                $CLOtoAssessmentMapping = OutcomeAssessment::where('l_outcome_id', $CLO->l_outcome_id)->get();
                $CLOtoAssessmentsIDs = $CLOtoAssessmentMapping->pluck('a_method_id')->toArray();
            
            if(count($assessmentMethodArray)==1){
                if (in_array($assessmentMethod[0]->a_method_id, $CLOtoAssessmentsIDs)){ //check if Assessment Method is mapped to CLO
                
                array_push($assessmentWeightages, '1');
                }else{
                    array_push($assessmentWeightages, '');
                }
            }else{
                if (in_array($assessmentMethod->a_method_id, $CLOtoAssessmentsIDs)){ //check if Assessment Method is mapped to CLO
                    
                    array_push($assessmentWeightages, '1'); // Empty if no weightage
                    }else{
                        array_push($assessmentWeightages, '');
                    }
            }
                
            }

            // Add weightage data to the respective column
            $sheet->fromArray(array_chunk($assessmentWeightages, 1), null, $columns[$categoryColInSheet].'3');

            $categoryColInSheet++;
        }

        return $sheet;
 
    } catch (Throwable $exception) {
        // Log any errors
        $message = 'There was an error downloading the spreadsheet overview for: '.$course->course;
        Log::error($message.' ...\n');
        Log::error('Code - '.$exception->getCode());
        Log::error('File - '.$exception->getFile());
        Log::error('Line - '.$exception->getLine());
        Log::error($exception->getMessage());

        return $exception;
    }
}

private function makeLearningActivityMapSheetData(Spreadsheet $spreadsheet, int $courseId, $styles, $columns): Worksheet
{
    try {
        // Find the program
        $course = Course::find($courseId);
        $learningActivityArray = [];
        $learningActivities = LearningActivity::where('course_id',$courseId)->get();
            if (count($learningActivities)==1 && $learningActivities!=NULL){
                array_push($learningActivityArray, $learningActivities);
            }else{
                if($learningActivities!=NULL){
                    foreach($learningActivities as $learningActivity){
                        array_push($learningActivityArray, $learningActivity);
                    }
                }
            }
        
        $courseLearningOutcomes = LearningOutcome::where('course_id', $courseId)->get();
        $courseLearningOutcomeShortPhrases = $courseLearningOutcomes->pluck('clo_shortphrase')->toArray();

        // Create a new sheet for Student Assessment Methods
        $sheet = $spreadsheet->createSheet();
        $sheet->setTitle('Learning Activities');
        
        // Add primary headings (Courses, Student Assessment Method) to the sheet
        $sheet->fromArray(['Course Learning Outcomes', 'Teaching and Learning Activities'], null, 'A1');
        $sheet->getStyle('A1:B1')->applyFromArray($styles['primaryHeading']);
        if(count($learningActivityArray)==0){
        $sheet->mergeCells('B1:'.$columns[count($learningActivityArray)+1].'1');
        }else{
            $sheet->mergeCells('B1:'.$columns[count($learningActivityArray)].'1');
        }

        
        // Add CLOs to first column
        $sheet->fromArray(array_chunk($courseLearningOutcomeShortPhrases, 1), null, 'A3');
        $sheet->getStyle('A3:A'.strval(3 + count($courseLearningOutcomeShortPhrases) - 1))->applyFromArray($styles['secondaryHeading']);
        $sheet->getStyle('A3:A100')->getFont()->setBold(true);

        // Retrieve and map Student Assessment Methods with their weightages
        $categoryColInSheet = 1;
        foreach ($learningActivities as $learningActivity) {

            // Add assessment method to the sheet under the appropriate column
            
            $sheet->setCellValue($columns[$categoryColInSheet].'2', $learningActivity->l_activity);
            $sheet->getStyle($columns[$categoryColInSheet].'2')->applyFromArray($styles['secondaryHeading']);
            $sheet->mergeCells($columns[$categoryColInSheet].'2:'.$columns[$categoryColInSheet].'2');

            // Add the weightage for each course
            $activityMappings = [];
            foreach ($courseLearningOutcomes as $CLO) {
                $CLOtoLAMapping = OutcomeActivity::where('l_outcome_id', $CLO->l_outcome_id)->get();
                $CLOtoLAIDs = $CLOtoLAMapping->pluck('l_activity_id')->toArray();
                
                if (in_array($learningActivity->l_activity_id, $CLOtoLAIDs)){ //check if learning activity is mapped to CLO

                array_push($activityMappings, '1'); 
                }else{
                    array_push($activityMappings, ' ');
                }
                
            }

            // Add weightage data to the respective column
            $sheet->fromArray(array_chunk($activityMappings, 1), null, $columns[$categoryColInSheet].'3');

            $categoryColInSheet++;
        }

        return $sheet;
 
    } catch (Throwable $exception) {
        // Log any errors
        $message = 'There was an error downloading the spreadsheet overview for: '.$course->course;
        Log::error($message.' ...\n');
        Log::error('Code - '.$exception->getCode());
        Log::error('File - '.$exception->getFile());
        Log::error('Line - '.$exception->getLine());
        Log::error($exception->getMessage());

        return $exception;
    }
}

private function makeOutcomeMapSheetData(Spreadsheet $spreadsheet, int $courseId, $styles, $columns): Worksheet
{
    try {
        // Find the course
        $course = Course::find($courseId);
        //Find all Programs associated with course
        $coursePrograms=CourseProgram::where('course_id',$courseId)->get();
        $courseProgramPIDs = $coursePrograms->pluck('program_id')->toArray();
        //Find all PLOs for each program
        $programLearningOutcomes=[];
        foreach($courseProgramPIDs as $pid){
        
            $PLOs=ProgramLearningOutcome::where('program_id', $pid)->get();
            foreach($PLOs as $PLO){
                array_push($programLearningOutcomes, [$pid, $PLO]); //Storing PLOs in array, with the first entry noting the program ID
            }
        }

        Log::Debug("Successfully made PLO array");
        //Log::Debug($programLearningOutcomes);

        /*
        $courseLearningOutcomes = [];
        $learningOutcomes = LearningOutcome::where('course_id',$courseId)->get();
            if (count($learningOutcomes)==1 && $learningOutcomes!=NULL){
                array_push($courseLearningOutcomes, $learningOutcomes);
            }else{
                if($learningOutcomes!=NULL){
                    foreach($learningOutcomes as $learningOutcome){
                        array_push($courseLearningOutcomes, $learningOutcome);
                    }
                }
        }

        Log::Debug("Successfully made CLO array");
        Log::Debug($courseLearningOutcomes);
        */
        
        $courseLearningOutcomes = LearningOutcome::where('course_id', $courseId)->get();
        $courseLearningOutcomeShortPhrases = $courseLearningOutcomes->pluck('clo_shortphrase')->toArray();

        // Create a new sheet for Student Assessment Methods
        $sheet = $spreadsheet->createSheet();
        $sheet->setTitle('Learning Outcome Mapping');
        
        // Add primary headings (Courses, Student Assessment Method) to the sheet
        $sheet->fromArray(['Course Learning Outcomes', 'Program Learning Outcomes'], null, 'A1');
        $sheet->getStyle('A1:B1')->applyFromArray($styles['primaryHeading']);
        $sheet->mergeCells('B1:'.$columns[count($programLearningOutcomes)].'1');
        

        
        // Add CLOs to first column
        //Changing to A4 to accomodate adding PLO categories
        //Chaning to A5 to accomodate Program
        $sheet->fromArray(array_chunk($courseLearningOutcomeShortPhrases, 1), null, 'A5');
        $sheet->getStyle('A5:A'.strval(count($courseLearningOutcomeShortPhrases) + 4))->applyFromArray($styles['secondaryHeading']);
        $sheet->getStyle('A5:A100')->getFont()->setBold(true);

        //Sort programLearningOutcomes by Category (plo_category_id)

        if(count($programLearningOutcomes)>1){
        usort($programLearningOutcomes, function($a, $b)
        {
            return strcmp($a[1]->plo_category_id, $b[1]->plo_category_id);
        });
        }

        // Retrieve and map Student Assessment Methods with their weightages
        $categoryColInSheet = 1;
        usort($programLearningOutcomes, function($a, $b) {
            return strcmp($a[1]->program, $b[1]->program);
        });

        foreach ($programLearningOutcomes as $PLO) {

            // Adding CLO to PLO mapping to the sheet under the appropriate column

            //Adding Programs
                        
            $program = Program::where('program_id', $PLO[1]->program_id)->first();
            $sheet->setCellValue($columns[$categoryColInSheet].'2', $program->program);
            $sheet->getStyle($columns[$categoryColInSheet].'2')->applyFromArray($styles['secondaryHeading']);
            //$sheet->mergeCells($columns[$categoryColInSheet].'2:'.$columns[$categoryColInSheet].'2');
 
            //Adding PLO Categories
                        
            $ploCategory = PLOCategory::where('plo_category_id', $PLO[1]->plo_category_id)->first();
            if($ploCategory!=NULL){
                $sheet->setCellValue($columns[$categoryColInSheet].'3', $ploCategory->plo_category);
                $sheet->getStyle($columns[$categoryColInSheet].'3')->applyFromArray($styles['secondaryHeading']);
                Log::Debug("Merging PLO Categories");
                //$sheet->mergeCells($columns[$categoryColInSheet].'3:'.$columns[$categoryColInSheet].'3');
            } else {
                $sheet->setCellValue($columns[$categoryColInSheet].'3', "Uncategorized");
                $sheet->getStyle($columns[$categoryColInSheet].'3')->applyFromArray($styles['secondaryHeading']);
               // $sheet->mergeCells($columns[$categoryColInSheet].'3:'.$columns[$categoryColInSheet].'3');
            }
            
            
            //Changing all column headers to start from 3 to accomodate PLO categories
            $sheet->setCellValue($columns[$categoryColInSheet].'4', $PLO[1]->pl_outcome);
            $sheet->getStyle($columns[$categoryColInSheet].'4')->getFont()->setBold(true);
            //$sheet->mergeCells($columns[$categoryColInSheet].'4:'.$columns[$categoryColInSheet].'4');

            
            // Outcome Mapping for each CLO
            $outcomeMappings = [];
            foreach ($courseLearningOutcomes as $CLO) {
                $CLOtoPLOMapping = OutcomeMap::where('l_outcome_id', $CLO->l_outcome_id)->where('pl_outcome_id', $PLO[1]->pl_outcome_id)->first();
                Log::Debug("CLO to PLO Mapping");
                Log::Debug($CLOtoPLOMapping);
                if($CLOtoPLOMapping!=NULL){
                    $mappingScale=MappingScale::where('map_scale_id', $CLOtoPLOMapping->map_scale_id)->first();
                    array_push($outcomeMappings, $mappingScale->abbreviation);
                }else{
                    array_push($outcomeMappings, ' ');
                }
                
            }
            

            // Add weightage data to the respective column
            //Changing all cell values to start from 4 to accomodate PLO categories
            $sheet->fromArray(array_chunk($outcomeMappings, 1), null, $columns[$categoryColInSheet].'5');

            $categoryColInSheet++;
        }

        //Combining Duplicate Cells in Headers
        $headerRows=[2,3];
        foreach($headerRows as $row){
            $row = $sheet->getRowIterator($row)->current();
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false);

            $CurrentColumnCoord=1;
            $firstDuplicateColumnValue="";
            $firstDuplicateColumnCoord="";
            $lastValue="";
            $lastCoord="";
            $duplicateFoundPreviously=false;
            
            $cellValues=[];
            $cellCoords=[];
            foreach ($cellIterator as $cell) {
                array_push($cellValues,$cell->getValue());
                array_push($cellCoords,$cell->getCoordinate());
            }

            $count=0;
            foreach($cellValues as $value){
                if($count<1){ //do nothing until we reach categories
                    
                }else{

                    if ($cellValues[$count]==$lastValue){
                        //Duplicate found, do nothing
                        $duplicateFoundPreviously=true;
                    //If duplicate was found, but the firstDuplicateColumnValue is blank, then set it to mark beginning of merge (whipe after merge)
                        if ($firstDuplicateColumnValue==""){
                            $firstDuplicateColumnValue=$lastValue;
                            $firstDuplicateColumnCoord=$lastCoord;
                        }

                        //If duplicate found and we are at last cell in row
                        if ($count==(count($cellValues)-1)){
                            //Merge from First Duplicate to Current
                            $sheet->mergeCells($firstDuplicateColumnCoord.':'.$cellCoords[$count]);
                            $sheet->getStyle($firstDuplicateColumnCoord)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                            
                            //Reset where we found first dupe
                            $firstDuplicateColumnValue="";
                            $firstDuplicateColumnCoord="";
                            $duplicateFoundPreviously=false;
                            break;
                        }
                        
                    }else{
                        if($duplicateFoundPreviously){
                            //Merge from First Duplicate to Current
                            $sheet->mergeCells($firstDuplicateColumnCoord.':'.$lastCoord);
                            $sheet->getStyle($firstDuplicateColumnCoord)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                            
                            //Reset where we found first dupe
                            $firstDuplicateColumnValue="";
                            $firstDuplicateColumnCoord="";
                            $duplicateFoundPreviously=false;
                        }

                    }
                $CurrentColumnCoord++;
                }

                $lastValue=$cellValues[$count];
                $lastCoord=$cellCoords[$count];

                $count++;
            }
        }   


        
        foreach($courseProgramPIDs as $cPID){
        $program = Program::find($cPID);
        // get this programs mapping scales
        $mappingScaleLevels = $program->mappingScaleLevels;
                    // create a wizard factory for creating new conditional formatting rules
                    $wizardFactory = new Wizard('B4:Z50');
                    foreach ($mappingScaleLevels as $level) {
                        // create a new conditional formatting rule based on the map scale level
                        $wizard = $wizardFactory->newRule(Wizard::CELL_VALUE);
                        $levelStyle = new Style(false, true);
                        $levelStyle->getFill()
                            ->setFillType(Fill::FILL_SOLID)
                            ->getStartColor()->setRGB(strtoupper(ltrim($level->colour, '#')));
                        $levelStyle->getFill()
                            ->getEndColor()->setRGB(strtoupper(ltrim($level->colour, '#')));
                        $wizard->equals($level->abbreviation)->setStyle($levelStyle);
                        $conditionalStyles[] = $wizard->getConditional();
                        // add conditional formatting rule to the outcome maps sheet
                        $sheet->getStyle($wizard->getCellRange())->setConditionalStyles($conditionalStyles);
                    }
        }


        return $sheet;
 
    } catch (Throwable $exception) {
        // Log any errors
        $message = 'There was an error downloading the spreadsheet overview for: '.$course->course;
        Log::error($message.' ...\n');
        Log::error('Code - '.$exception->getCode());
        Log::error('File - '.$exception->getFile());
        Log::error('Line - '.$exception->getLine());
        Log::error($exception->getMessage());

        return $exception;
    }
}
            

}
