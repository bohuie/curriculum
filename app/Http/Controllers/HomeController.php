<?php

namespace App\Http\Controllers;

use App\Models\AssessmentMethod;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\CourseProgram;
use App\Models\Program;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\CourseUser;
use App\Models\LearningActivity;
use App\Models\LearningOutcome;
use App\Models\ProgramUser;
use App\Models\OutcomeMap;
use App\Models\ProgramLearningOutcome;
use Attribute;
use Illuminate\Support\Facades\DB;

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
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {   
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
        $coursesPrograms = array();
        foreach ($myCourses as $course) {
            $coursePrograms = $course->programs;
            $coursesPrograms[$course->course_id] = $coursePrograms;
        }
        // returns a collection of programs associated with users (Collaborators Icon) 
        $programUsers = array();
        foreach ($myPrograms as $program) {
            $programsUsers = $program->users()->get();
            $programUsers[$program->program_id] = $programsUsers;
        }
        // returns a collection of courses associated with users 
        $courseUsers = array();
        foreach ($myCourses as $course) {
            $coursesUsers = $course->users()->get();
            $courseUsers[$course->course_id] = $coursesUsers;
        }
        // get the associated users for every one of this users syllabi
        $syllabiUsers = array();
        foreach ($mySyllabi as $syllabus) {
            $syllabusUsers = $syllabus->users;
            $syllabiUsers[$syllabus->id] = $syllabusUsers;
        }
        // returns a collection of standard_categories, used in the create course modal
        $standard_categories = DB::table('standard_categories')->get();

        //for progress bar
        $progressBar = array();
        $count = 0;
        foreach($myCourses as $course) {
            // get course id for each course
            $courseId = $course->course_id;
            // gets the count for each step used to check if progress has been made
            if (LearningOutcome::where('course_id', $courseId)->count() > 0) {
                $count++;
            }
            if (AssessmentMethod::where('course_id', $courseId)->count() > 0) {
                $count++;
            }
            if (LearningActivity::where('course_id', $courseId)->count() > 0) {
                $count++;
            }
            if (LearningActivity::join('outcome_activities','learning_activities.l_activity_id','=','outcome_activities.l_activity_id')->join('learning_outcomes', 'outcome_activities.l_outcome_id', '=', 'learning_outcomes.l_outcome_id' )->select('outcome_activities.l_activity_id','learning_activities.l_activity','outcome_activities.l_outcome_id', 'learning_outcomes.l_outcome')->where('learning_activities.course_id','=',$courseId)->count() > 0) {
                $count++;
            }
            if (AssessmentMethod::join('outcome_assessments','assessment_methods.a_method_id','=','outcome_assessments.a_method_id')->join('learning_outcomes', 'outcome_assessments.l_outcome_id', '=', 'learning_outcomes.l_outcome_id' )->select('assessment_methods.a_method_id','assessment_methods.a_method','outcome_assessments.l_outcome_id', 'learning_outcomes.l_outcome')->where('assessment_methods.course_id','=',$courseId)->count() > 0) {
                $count++;
            }
            if (ProgramLearningOutcome::join('outcome_maps','program_learning_outcomes.pl_outcome_id','=','outcome_maps.pl_outcome_id')->join('learning_outcomes', 'outcome_maps.l_outcome_id', '=', 'learning_outcomes.l_outcome_id' )->select('outcome_maps.map_scale_value','outcome_maps.pl_outcome_id','program_learning_outcomes.pl_outcome','outcome_maps.l_outcome_id', 'learning_outcomes.l_outcome')->where('learning_outcomes.course_id','=',$courseId)->count() > 0) {
                $count++;
            }
            $progressBar[$courseId] = intval(round(($count / 6) * 100));
            $count = 0;
        }

        // return dashboard view
        return view('pages.home')->with("myCourses",$myCourses)->with("myPrograms", $myPrograms)->with('user', $user)->with('coursesPrograms', $coursesPrograms)->with('standard_categories', $standard_categories)->with('programUsers', $programUsers)->with('courseUsers', $courseUsers)->with('mySyllabi', $mySyllabi)->with('syllabiUsers', $syllabiUsers)->with('progressBar', $progressBar);
    }


    public function getProgramUsers($program_id) {
        
        $programUsers = ProgramUser::join('users','program_users.user_id',"=","users.id")
                                ->select('users.email','program_users.user_id','program_users.program_id')
                                ->where('program_users.program_id','=',$program_id)->get();
        
        return view('pages.home')->with('ProgramUsers', $programUsers);
    }

        /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $course_id)
    {
        //
        $c = Course::where('course_id', $course_id)->first();
        $type = $c->type;

        if($c->delete()){
            $request->session()->flash('success','Course has been deleted');
        }else{
            $request->session()->flash('error', 'There was an error deleting the course');
        }

        if($type == 'assigned'){
            return redirect()->route('programWizard.step3', $request->input('program_id'));
        }else{
            return redirect()->route('home');
        }

    }

    public function submit(Request $request, $course_id)
    {
        //
        $c = Course::where('course_id', $course_id)->first();
        $c->status = 1;

        if($c->save()){
            $request->session()->flash('success','Your answers have	been submitted successfully');
        }else{
            $request->session()->flash('error', 'There was an error submitting your answers');
        }

        return redirect()->route('home');
    }

    /*
        Helper function that returns a human readable format of the time since 
        @param Number $sinceSeconds is the current time minus a datetime
        @return String 
    */    
    function timeSince($sinceSeconds) {
        $chunks = array(
            array(60 * 60 * 24 * 365 , 'year'),
            array(60 * 60 * 24 * 30 , 'month'),
            array(60 * 60 * 24 * 7, 'week'),
            array(60 * 60 * 24 , 'day'),
            array(60 * 60 , 'hour'),
            array(60 , 'min'),
            array(1 , 'second')
        );
    
        for ($i = 0, $j = count($chunks); $i < $j; $i++) {
            $seconds = $chunks[$i][0];
            $name = $chunks[$i][1];
            if (($count = floor($sinceSeconds / $seconds)) != 0) {
                break;
            }
        }    
        return ($count == 1) ? '1 '. $name . ' ago' : "$count {$name}s ago";
    }
}
