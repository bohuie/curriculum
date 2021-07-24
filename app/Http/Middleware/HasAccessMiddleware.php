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
            $allCourseUsers = Course::find($course_id)->users;

            $usersArray = array();
            $usersArray = $this->populateUsersArray($allCourseUsers, $usersArray);

            if ($this->denyAccess($usersArray)) {
                $request->session()->flash('error', 'You do not have access to this course');
                return redirect()->route('home');
            }

        }else if ($program_id != null) {
            // get all users for the program
            $allProgramUsers = Program::find($program_id)->users;
            
            $usersArray = array();
            $usersArray = $this->populateUsersArray($allProgramUsers, $usersArray);

            if ($this->denyAccess($usersArray)) {
                $request->session()->flash('error', 'You do not have access to this Program');
                return redirect()->route('home');
            }

        }elseif ($syllabus_id != null) {
            // get all users for the syllabus
            $allSyllabusUsers = Syllabus::find($syllabus_id)->users;
            
            $usersArray = array();
            $usersArray = $this->populateUsersArray($allSyllabusUsers, $usersArray);
            
            if ($this->denyAccess($usersArray)) {
                $request->session()->flash('error', 'You do not have access to this Syllabus');
                return redirect()->route('home');
            } else {
                $userPermission = $allSyllabusUsers->where('id', Auth::id())->first()->pivot->permission;
                switch ($userPermission) {
                    case 1:
                        // Owner
                        break;
                    case 2:
                        // Editor
                        $request['isEditor'] = TRUE;
                        break;
                    case 3:
                        // Viewer
                        $request->session()->flash('success', 'RETURN SUMMARY VIEWER ONLY');
                        return redirect()->route('home');
                        break;
                }
            }
        }

        return $next($request);
    }

    public function populateUsersArray($allUsers, $usersArray) {
        foreach ($allUsers as $user) {
            $usersArray[] += $user->id; 
        }
        return $usersArray;
    }

    public function denyAccess($users) {
        $currentUser = User::where('id',Auth::id())->first();
        // check if current user belongs to the course 
        if (!in_array($currentUser->id, $users)) {
            return TRUE;
        }
    }
}