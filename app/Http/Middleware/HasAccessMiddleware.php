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
        // get current user accessing 
        $user = User::where('id',Auth::id())->first();

        if ($course_id != null) {
            // get all users for the course
            $courseUsers = Course::find($course_id)->users;
            if (!in_array($user->id, $courseUsers->pluck('id')->toArray())) {
                $request->session()->flash('error', 'You do not have access to this course');
                return redirect()->route('home');
            }


        }else if ($program_id != null) {
            // get all users for the program
            $programUsers = Program::find($program_id)->users;
            if (!in_array($user->id, $programUsers->pluck('id')->toArray())) {
                $request->session()->flash('error', 'You do not have access to this program');
                return redirect()->route('home');
            }

        }elseif ($syllabus_id != null) {
            // get all users for the syllabus
            $syllabusUsers = Syllabus::find($syllabus_id)->users;
            if (!in_array($user->id, $syllabusUsers->pluck('id')->toArray())) {
                $request->session()->flash('error', 'You do not have access to this syllabus');
                return redirect()->route('home');
            }
        }

        return $next($request);
    }
}