<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Program;
use App\Models\CourseProgram;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class CourseProgramController extends Controller
{
    //

    /**
     * Add courses to a program
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addCoursesToProgram(Request $request){
        $this->validate($request, [
            'program_id' => 'required',
            ]);
        
        $programId = $request->input('program_id');
        // if courseIds is null, use an empty array 
        if (!$courseIds = $request->input('selectedCourses'))
            $courseIds = array();

        $numCoursesAddedSuccessfully = 0; 

        // update or create a programCourse for each course
        foreach ($courseIds as $index => $courseId) {
            $isCourseRequired = $request->input('require'.$courseId);
            // if a courseProgram with course_id and program_id exists then update course_required field else create a new courseProgram record
            CourseProgram::updateOrCreate(
                ['course_id' => $courseId, 'program_id' => $programId], 
                ['course_required' => ($isCourseRequired) ? 1 : 0]
            );
            $numCoursesAddedSuccessfully++;
        }

        if ($numCoursesAddedSuccessfully == count($courseIds)) {
            // update courses 'updated_at' field
            $program = Program::find($request->input('program_id'));
            $program->touch();

            // get users name for last_modified_user
            $user = User::find(Auth::id());
            $program->last_modified_user = $user->name;
            $program->save();
            
            $request->session()->flash('success', 'Successfully added ' . strval(count($courseIds)) . ' course(s) to this program.');
        } else {   
            $request->session()->flash('error', "There was an error adding " . strval(count($courseIds) - $numCoursesAddedSuccessfully));
        }
        
        return redirect()->route('programWizard.step3', $request->input('program_id'));
    }

    public function editCourseRequired(Request $request) {
        $courseId = $request->input('course_id');
        $programId = $request->input('program_id');
        $required = $request->input('required');
        $note = $request->input('note');
        
        $course = Course::where('course_id', $courseId)->first();

        if ($courseId != null && $programId != null && $required != null) {
            CourseProgram::updateOrCreate(
                ['course_id' => $courseId, 'program_id' => $programId], 
                ['course_required' => $required]
            );

            CourseProgram::where(['course_id' => $courseId, 'program_id' => $programId])->update(['note' => $note]);
            // update courses 'updated_at' field
            $program = Program::find($request->input('program_id'));
            $program->touch();

            // get users name for last_modified_user
            $user = User::find(Auth::id());
            $program->last_modified_user = $user->name;
            $program->save();

            $request->session()->flash('success', 'Successfully updated: ' .strval($course->course_title));
        } else {
            $request->session()->flash('error', 'There was an error updating the course');
        }

        return redirect()->route('programWizard.step3', $request->input('program_id'));
    }
}
