<?php

namespace App\Http\Controllers;

use App\Models\AssessmentMethod;
use App\Models\Campus;
use App\Models\Course;
use App\Models\Department;
use App\Models\Faculty;
use App\Models\LearningActivity;
use App\Models\LearningOutcome;
use App\Models\OutcomeActivity;
use App\Models\OutcomeAssessment;
use App\Models\Program;
use App\Models\ProgramLearningOutcome;
use App\Models\ProgramUser;
use App\Models\Standard;
use App\Models\StandardCategory;
use App\Models\StandardsOutcomeMap;
use App\Models\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    /**
     * Show the application dashboard.
     */
    public function index(Request $request): Renderable
    {
        //Artisan::call('route:clear', []);

        $campuses = Campus::all();
        $faculties = Faculty::orderBy('faculty')->get();
        $departments = Department::orderBy('department')->get();
        // get the current authenticated user
        $user = User::find(Auth::id());
        // get my programs
        $myPrograms = $user->programs->map(function ($program) {
            $program['timeSince'] = $this->timeSince(time() - strtotime($program->updated_at));
            $program['userPermission'] = $program->pivot->permission;

            return $program;
        })->sortByDesc('updated_at')->values(); // Values is used to reset the index for sort statement

        // get my courses
        $myCourses = $user->courses->map(function ($course) {
            $course['timeSince'] = $this->timeSince(time() - strtotime($course->updated_at));
            $course['userPermission'] = $course->pivot->permission;

            return $course;
        })->sortByDesc('updated_at')->values(); // Values is used to reset the index for sort statement
        // get my syllabi
        $mySyllabi = $user->syllabi->map(function ($syllabus) {
            $syllabus['timeSince'] = $this->timeSince(time() - strtotime($syllabus->updated_at));
            $syllabus['userPermission'] = $syllabus->pivot->permission;

            return $syllabus;
        })->sortByDesc('updated_at')->values(); // Values is used to reset the index for sort statement

        // returns a collection of programs associated with courses (Programs Icon)
        $coursesPrograms = [];
        foreach ($myCourses as $course) {
            $coursePrograms = $course->programs;
            $coursesPrograms[$course->course_id] = $coursePrograms;
        }
        // returns a collection of programs associated with users (Collaborators Icon)
        $programUsers = [];
        foreach ($myPrograms as $program) {
            $programsUsers = $program->users()->get();
            $programUsers[$program->program_id] = $programsUsers;
        }
        // returns a collection of courses associated with users
        $courseUsers = [];
        foreach ($myCourses as $course) {
            $coursesUsers = $course->users()->get();
            $courseUsers[$course->course_id] = $coursesUsers;
        }
        // get the associated users for every one of this users syllabi
        $syllabiUsers = [];
        foreach ($mySyllabi as $syllabus) {
            $syllabusUsers = $syllabus->users;
            $syllabiUsers[$syllabus->id] = $syllabusUsers;
        }
        // returns a collection of standard_categories, used in the create course modal
        $standard_categories = DB::table('standard_categories')->get();

        //for progress bar
        $progressBar = [];
        $progressBarMsg = [];
        $count = 0;
        foreach ($myCourses as $course) {

            $numClos = LearningOutcome::where('course_id', $course->course_id)->count();
            // get the total number of program outcome maps possible for a course
            $coursePrograms = $course->programs;
            if (count($coursePrograms) <= 1) {
                $expectedProgramOutcomeMapCount = 1;
            } else {
                $expectedProgramOutcomeMapCount = 0;
            }
            // This loop will not run if the course does not have any programs
            foreach ($coursePrograms as $program) {
                // multiple number of CLOs by num of PLOs
                $expectedProgramOutcomeMapCount += $program->programLearningOutcomes->count() * $numClos;
            }
            // checks if all learning outcomes have been aligned to a student assessment method AND a Teaching and Learning Outcome. Breaks and returns true if a clo is not aligned.
            $l_outcomes = LearningOutcome::where('course_id', $course->course_id)->get();
            $hasNonAlignedCLO = false;
            foreach ($l_outcomes as $clo) {
                if ((! OutcomeAssessment::where('l_outcome_id', $clo->l_outcome_id)->exists()) || (! OutcomeActivity::where('l_outcome_id', $clo->l_outcome_id)->exists())) {
                    $hasNonAlignedCLO = true;
                    break;
                }
            }
            // Used for getting the status (progress) for each course displayed on the dashboard
            // get course id for each course
            $courseId = $course->course_id;
            $progressBarMsg[$courseId]['statusMsg'] = '<b>Remaining Tasks</b> <ol>';
            $hasNoStandards = false;
            // gets the count for each step used to check if progress has been made
            if (LearningOutcome::where('course_id', $courseId)->count() > 0) {
                $count++;
            } else {
                $progressBarMsg[$courseId]['statusMsg'] .= '<li>Course Learning Outcomes (Step 1)</li>';
            }
            if (AssessmentMethod::where('course_id', $courseId)->count() > 0) {
                $count++;
            } else {
                $progressBarMsg[$courseId]['statusMsg'] .= '<li>Student Assessment Methods (Step 2)</li>';
            }
            if (LearningActivity::where('course_id', $courseId)->count() > 0) {
                $count++;
            } else {
                $progressBarMsg[$courseId]['statusMsg'] .= '<li>Teaching and Learning Activities (Step 3)</li>';
            }
            if ((! LearningActivity::join('outcome_activities', 'learning_activities.l_activity_id', '=', 'outcome_activities.l_activity_id')->join('learning_outcomes', 'outcome_activities.l_outcome_id', '=', 'learning_outcomes.l_outcome_id')->select('outcome_activities.l_activity_id', 'learning_activities.l_activity', 'outcome_activities.l_outcome_id', 'learning_outcomes.l_outcome')->where('learning_activities.course_id', '=', $courseId)->count() > 0) && (! AssessmentMethod::join('outcome_assessments', 'assessment_methods.a_method_id', '=', 'outcome_assessments.a_method_id')->join('learning_outcomes', 'outcome_assessments.l_outcome_id', '=', 'learning_outcomes.l_outcome_id')->select('assessment_methods.a_method_id', 'assessment_methods.a_method', 'outcome_assessments.l_outcome_id', 'learning_outcomes.l_outcome')->where('assessment_methods.course_id', '=', $courseId)->count() > 0)) {
                if (LearningActivity::join('outcome_activities', 'learning_activities.l_activity_id', '=', 'outcome_activities.l_activity_id')->join('learning_outcomes', 'outcome_activities.l_outcome_id', '=', 'learning_outcomes.l_outcome_id')->select('outcome_activities.l_activity_id', 'learning_activities.l_activity', 'outcome_activities.l_outcome_id', 'learning_outcomes.l_outcome')->where('learning_activities.course_id', '=', $courseId)->count() > 0) {
                    $count++;
                } else {
                    $progressBarMsg[$courseId]['statusMsg'] .= '<li>Assessment Methods - Course Alignment (Step 4)</li>';
                }
                if (AssessmentMethod::join('outcome_assessments', 'assessment_methods.a_method_id', '=', 'outcome_assessments.a_method_id')->join('learning_outcomes', 'outcome_assessments.l_outcome_id', '=', 'learning_outcomes.l_outcome_id')->select('assessment_methods.a_method_id', 'assessment_methods.a_method', 'outcome_assessments.l_outcome_id', 'learning_outcomes.l_outcome')->where('assessment_methods.course_id', '=', $courseId)->count() > 0) {
                    $count++;
                } else {
                    $progressBarMsg[$courseId]['statusMsg'] .= '<li>Learning Activities - Course Alignment (Step 4)</li>';
                }
            } elseif ($hasNonAlignedCLO) {
                $progressBarMsg[$courseId]['statusMsg'] .= '<li>Course Alignment (Step 4)</li>';
                $count++;
            } else {
                $count = $count + 2;
            }
            if (ProgramLearningOutcome::join('outcome_maps', 'program_learning_outcomes.pl_outcome_id', '=', 'outcome_maps.pl_outcome_id')->join('learning_outcomes', 'outcome_maps.l_outcome_id', '=', 'learning_outcomes.l_outcome_id')->select('outcome_maps.map_scale_value', 'outcome_maps.pl_outcome_id', 'program_learning_outcomes.pl_outcome', 'outcome_maps.l_outcome_id', 'learning_outcomes.l_outcome')->where('learning_outcomes.course_id', '=', $courseId)->count() >= ($expectedProgramOutcomeMapCount == 1 ? $expectedProgramOutcomeMapCount : $expectedProgramOutcomeMapCount - 1)) {
                $count++;
            } else {
                $progressBarMsg[$courseId]['statusMsg'] .= '<li>Program Outcome Mapping (Step 5)</li>';
            }
            $course = Course::find($courseId);
            if ($course->standard_category_id == 0) {
                $hasNoStandards = true;
            } elseif (StandardsOutcomeMap::where('course_id', $courseId)->count() == StandardCategory::find($course->standard_category_id)->standards->count()) {
                $count++;
            } else {
                $progressBarMsg[$courseId]['statusMsg'] .= '<li>Standards (Step 6)</li>';
            }

            // calculate the progress bar
            // if course has no standards, then the total count is 6 otherwise it is 7.
            if ($hasNoStandards) {
                $progressBar[$courseId] = intval(round(($count / 6) * 100));
            } else {
                $progressBar[$courseId] = intval(round(($count / 7) * 100));
            }
            $count = 0;
            $progressBarMsg[$courseId]['statusMsg'] .= '</ol>';
        }

        // return dashboard view
        return view('pages.home')->with('myCourses', $myCourses)->with('myPrograms', $myPrograms)->with('user', $user)->with('coursesPrograms', $coursesPrograms)->with('standard_categories', $standard_categories)->with('programUsers', $programUsers)
            ->with('courseUsers', $courseUsers)->with('mySyllabi', $mySyllabi)->with('syllabiUsers', $syllabiUsers)->with('progressBar', $progressBar)->with('progressBarMsg', $progressBarMsg)->with('campuses', $campuses)->with('faculties', $faculties)
            ->with('departments', $departments);
    }

    public function getProgramUsers($program_id): View
    {

        $programUsers = ProgramUser::join('users', 'program_users.user_id', '=', 'users.id')
            ->select('users.email', 'program_users.user_id', 'program_users.program_id')
            ->where('program_users.program_id', '=', $program_id)->get();

        return view('pages.home')->with('ProgramUsers', $programUsers);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     */
    public function destroy(Request $request, $course_id): RedirectResponse
    {
        //
        $c = Course::where('course_id', $course_id)->first();
        $type = $c->type;

        if ($c->delete()) {
            $request->session()->flash('success', 'Course has been deleted');
        } else {
            $request->session()->flash('error', 'There was an error deleting the course');
        }

        if ($type == 'assigned') {
            return redirect()->route('programWizard.step3', $request->input('program_id'));
        } else {
            return redirect()->route('home');
        }

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

    /*
        Helper function that returns a human readable format of the time since
        @param Number $sinceSeconds is the current time minus a datetime
        @return String
    */
    public function timeSince($sinceSeconds)
    {
        $chunks = [
            [60 * 60 * 24 * 365, 'year'],
            [60 * 60 * 24 * 30, 'month'],
            [60 * 60 * 24 * 7, 'week'],
            [60 * 60 * 24, 'day'],
            [60 * 60, 'hour'],
            [60, 'min'],
            [1, 'second'],
        ];

        for ($i = 0, $j = count($chunks); $i < $j; $i++) {
            $seconds = $chunks[$i][0];
            $name = $chunks[$i][1];
            if (($count = floor($sinceSeconds / $seconds)) != 0) {
                break;
            }
        }

        return ($count == 1) ? '1 '.$name.' ago' : "$count {$name}s ago";
    }
}
