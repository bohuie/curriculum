<?php

namespace App\Http\Controllers;

use App\Mail\NotifyInstructorForMappingMail;
use App\Mail\NotifyNewCourseInstructorMail;
use App\Mail\NotifyNewUserAndInstructorMail;
use App\Models\AssessmentMethod;
use App\Models\Course;
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
}
