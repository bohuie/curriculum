<?php

namespace App\Http\Middleware;

use App\Models\Course;
use App\Models\Program;
use App\Models\syllabus\Syllabus;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use phpDocumentor\Reflection\PseudoTypes\True_;

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
        // get current user  
        $user = User::where('id',Auth::id())->first();

        if ($course_id != null) {
            // get all users for the course
            $courseUsers = Course::find($course_id)->users;
            // check if the current user belongs to this course
            if (!in_array($user->id, $courseUsers->pluck('id')->toArray())) {
                // user does not belong to this course
                $request->session()->flash('error', 'You do not have access to this course');
                return redirect()->route('home');
            }
        } else if ($program_id != null) {
            // get all users for the program
            $programUsers = Program::find($program_id)->users;
            // check if the current user belongs to this program
            if (!in_array($user->id, $programUsers->pluck('id')->toArray())) {
                // user does not belong to this program
                $request->session()->flash('error', 'You do not have access to this program');
                return redirect()->route('home');
            } else {
                // get users permission level for this syllabus
                $userPermission = $programUsers->where('id', Auth::id())->first()->pivot->permission;
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
                        //$request->session()->flash('success', 'RETURN SUMMARY VIEWER ONLY');
                        // return view only syllabus 
                        $request['isViewer'] = TRUE;
                        break;
                    default: 
                        // default 
                }
            }

        } elseif ($syllabus_id != null) {
            // get all users for the syllabus
            $syllabusUsers = Syllabus::find($syllabus_id)->users;
            // check if the current user belongs to this syllabus
            if (!in_array($user->id, $syllabusUsers->pluck('id')->toArray())) {
                // user does not belong to this syllabus
                $request->session()->flash('error', 'You do not have access to this syllabus');
                return redirect()->route('home'); 
            } else {
                // get users permission level for this syllabus
                $userPermission = $syllabusUsers->where('id', Auth::id())->first()->pivot->permission;
                switch ($userPermission) {
                    case 1:
                        // Owner
                        break;
                    case 2:
                        // Editor
                        break;
                    case 3:
                        // Viewer
                        $request->session()->flash('success', 'RETURN SUMMARY VIEWER ONLY');
                        // return view only syllabus 
                        // return redirect()->route('home');
                        break;
                    default: 
                        // default 
                }
            }
        }

        return $next($request);
    }
}