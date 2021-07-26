<?php

namespace App\Http\Middleware;

use App\Models\Course;
use App\Models\Program;
use App\Models\syllabus\Syllabus;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HasAccessMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $course_id = $request->route()->parameter('course');
        $program_id = $request->route()->parameter('program');
        $syllabus_id = $request->route()->parameter('syllabusId');
        // get user accessing 

        if ($course_id != null) {
            // get all users for the course
            $allCourseUsers = Course::join('course_users','courses.course_id',"=","course_users.course_id")
                                    ->join('users','course_users.user_id',"=","users.id")
                                    ->select('course_users.user_id')
                                    ->where('courses.course_id','=',$course_id)->get();
            
            $usersArray = array();
            $usersArray = $this->populateUsersArray($allCourseUsers, $usersArray);

            if ($this->denyAccess($usersArray)) {
                $request->session()->flash('error', 'You do not have access to this course');
                return redirect()->route('home');
            }

        }else if ($program_id != null) {
            // get all users for the program
            $allProgramUsers = Program::join('program_users', 'programs.program_id', '=', 'program_users.program_id')
                                    ->join('users','program_users.user_id', '=', 'users.id')
                                    ->select('program_users.user_id')
                                    ->where('programs.program_id', '=', $program_id)->get();
            
            $usersArray = array();
            $usersArray = $this->populateUsersArray($allProgramUsers, $usersArray);

            if ($this->denyAccess($usersArray)) {
                $request->session()->flash('error', 'You do not have access to this Program');
                return redirect()->route('home');
            }

        }elseif ($syllabus_id != null) {
            // get all users for the syllabus
            $allSyllabusUsers = Syllabus::join('syllabi_users', 'syllabi.id', '=', 'syllabi_users.syllabus_id')
                                    ->join('users', 'syllabi_users.user_id', '=', 'users.id')
                                    ->select('syllabi_users.user_id')
                                    ->where('syllabi.id', '=', $syllabus_id)->get();
            
            $usersArray = array();
            $usersArray = $this->populateUsersArray($allSyllabusUsers, $usersArray);

            if ($this->denyAccess($usersArray)) {
                $request->session()->flash('error', 'You do not have access to this Syllabus');
                return redirect()->route('home');
            }
        }

        return $next($request);
    }

    public function populateUsersArray($allUsers, $usersArray) {
        foreach ($allUsers as $user) {
            $usersArray[] += $user->user_id; 
        }
        return $usersArray;
    }

    public function denyAccess($usersArray) {
        $currentUser = User::where('id',Auth::id())->first();
        // check if current user belongs to the course 
        if (!in_array($currentUser->id, $usersArray)) {
            return TRUE;
        }
    }
}