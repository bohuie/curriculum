<?php

namespace App\Http\Controllers;

use App\Models\CourseUser;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;

use App\Mail\NotifyInstructorMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

use Illuminate\Support\Facades\DB;

class CourseUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    public function index()
    {
        //
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
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $currentUser = User::find(Auth::id());
        $currentUserPermission = $currentUser->courses->where('course_id', $request->input('course_id'))->first()->pivot->permission;
        if ($currentUserPermission == 1) {
            // Validate request 
            $validator = $this->validate($request, [
                'email'=> 'required',
                'email'=> 'exists:users,email',
                'permission' => 'required',
                ]);

            // get user
            $user = User::where('email', $request->input('email'))->first();
            // get course id
            $course_id = $request->input('course_id');
            // get course
            $course = Course::where('course_id',$course_id)->first();
            // get permission
            $permission = $request->input('permission');
            // create a new collaborator
            CourseUser::updateOrCreate(
                ['course_id' => $course_id, 'user_id' => $user->id ]
            );
            // find the newly created or updated course user
            $courseUser = CourseUser::where([
                ['course_id', $course_id],
                ['user_id', $user->id]
            ])->first();
            // Set the course users permission level
            switch ($permission) {
                case 'edit':
                    $courseUser->permission = 2;
                break;
                case 'view':
                    $courseUser->permission = 3;
                break;
            }

            if($courseUser->save()){
                Mail::to($user->email)->send(new NotifyInstructorMail($course->course_code, $course->course_num, $course->course_title, $currentUser->name));
                $request->session()->flash('success', 'Course '.$course->course_code.''.$course->course_num.' successfully assigned to '.$user->email);
            }else{
                $request->session()->flash('error', 'There was an error assigning the course');
            }

        } else {
            $request->session()->flash('error', 'You do not have permission to add collaborators to this course');
        }
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CourseUser  $courseUser
     * @return \Illuminate\Http\Response
     */
    public function show(CourseUser $courseUser)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CourseUser  $courseUser
     * @return \Illuminate\Http\Response
     */
    public function edit(CourseUser $courseUser)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CourseUser  $courseUser
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CourseUser $courseUser)
    {
        //

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CourseUser  $courseUser
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        // user trying to remove collaborator
        $currentUser = User::find(Auth::id());
        $currentUserPermission = $currentUser->courses->where('course_id', $request->input('course_id'))->first()->pivot->permission;
        // user to be removed from course
        $user_id = $request->input('user_id');

        if ($currentUser->id == (int) $user_id) {
            $course_id = $request->input('course_id');
            $courseUser = CourseUser::where('course_id', $course_id)->where('user_id', $user_id);
            $user = User::find($user_id);

            if ($courseUser->delete()) {
                $request->session()->flash('success', $user->name. ' has been removed');
            } else {
                $request->session()->flash('error', 'There was an error deleting the user');
            }
        
        } else if ($currentUserPermission == 1) {
            $user = User::find($user_id);
            $course_id = $request->input('course_id');
            $courseUser = CourseUser::where('course_id', $course_id)->where('user_id', $user_id);

            if ($courseUser->delete()) {
                $request->session()->flash('success', $user->name. ' has been deleted');
            }else{
                $request->session()->flash('error', 'There was an error deleting the user');
            }

        } else {
            $request->session()->flash('error', 'You do not have permission to remove collaborators to this course');
        }

        return redirect()->back();
    }
}
