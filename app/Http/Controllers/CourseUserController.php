<?php

namespace App\Http\Controllers;

use App\Models\CourseUser;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;

use App\Mail\NotifyInstructorMail;
use Illuminate\Database\Eloquent\Collection;
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
    public function store(Request $request, $courseId)
    {
        // get the current user
        $currentUser = User::find(Auth::id());
        // get the current user permission
        $currentUserPermission = $currentUser->courses->where('course_id', $courseId)->first()->pivot->permission;
        // get the course
        $course = Course::find($courseId);
        // keep track of errors
        $errorMessages = Collection::make();
        $warningMessages = Collection::make();

        // if the current user is the owner, save the collaborators and their permissions
        if ($currentUserPermission == 1 ) {
            $currentPermissions = ($request->input('course_current_permissions')) ? $request->input('course_current_permissions') : array();
            $newCollabs = $request->input('course_new_collabs');
            $newPermissions = $request->input('course_new_permissions');
            // get the saved collaborators for this course, but not the owner
            $savedCourseUsers = CourseUser::where([['course_id', '=', $course->course_id], ['permission', '!=', 1]])->get();
            // update current collaborators for this course
            foreach ($savedCourseUsers as $savedCourseUser) {
                if (array_key_exists($savedCourseUser->user_id, $currentPermissions)) {
                    $this->update($savedCourseUser, $currentPermissions);
                } else {
                    // remove old collaborator from course, make sure it's not the owner
                    if ($savedCourseUser->permission != 1) {
                        $this->destroy($savedCourseUser);
                    }
                }
            }

            // add new collaborators
            if ($newCollabs) {
                foreach ($newCollabs as $index => $newCollab) {
                    // find the newCollab by their email
                    $user = User::where('email', $newCollab)->first();
                    // if the user has registered with the tool, add the new collab
                    if ($user) {
                        // make sure the new collab user isn't already collaborating on this course 
                        if (!in_array($user->email, $course->users->pluck('email')->toArray())) {
                            // get their given permission level
                            $permission = $newPermissions[$index];
                            // create a new collaborator
                            $courseUser = CourseUser::updateOrCreate(
                                ['course_id' => $course->course_id, 'user_id' => $user->id],
                            );
                            // set this course user permission level
                            switch ($permission) {
                                case 'edit':
                                    $courseUser->permission = 2;
                                break;
                                case 'view':
                                    $courseUser->permission = 3;
                                break;
                            }
                            if($courseUser->save()){
                                Mail::to($user->email)->send(new NotifyInstructorMail($course->course_code, $course->course_num, $course->course_title, $user->name));
                            } else {
                                $errorMessages->add('There was an error adding ' . '<b>' . $user->email . '</b>' . ' to course ' . $course->course_code . ' ' . $course->course_num);
                            }
                        } else {
                            $warningMessages->add('<b>' . $user->email . '</b>' . ' is already collaborating on course ' . $course->course_code . ' ' . $course->course_num);
                        }
                    } else {
                        $errorMessages->add('<b>' . $newCollab . '</b>' . ' has not registered on this site. ' . "<a target='_blank' href=" . route('requestInvitation') . ">Invite $newCollab</a> and add them once they have registered.");
                    }
                }
            }
        // else the current user does not own this course
        } else {
            $errorMessages->add('You do not have permission to add collaborators to this course');
        }

        // if no errors or warnings, flash a success message
        if ($errorMessages->count() == 0 && $warningMessages->count() == 0) {
            $request->session()->flash('success', 'Successfully updated collaborators on course ' . $course->course_code . ' ' . $course->course_num);
        }

        // return to the previous page
        return redirect()->back()->with('errorMessages', $errorMessages)->with('warningMessages', $warningMessages);
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
    public function update(CourseUser $courseUser, $permissions)
    {
        // update permissions for current collaborators
        switch ($permissions[$courseUser->user_id]) {
            case 'edit':
                $courseUser->permission = 2;
            break;
            
            case 'view':
                $courseUser->permission = 3;
            break;
        }
        
        $courseUser->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CourseUser  $courseUser
     * @return \Illuminate\Http\Response
     */
    public function destroy(CourseUser $courseUser)
    {
        // get the current user
        $currentUser = User::find(Auth::id());
        // get the current user permission
        $currentUserPermission = CourseUser::where([['course_id', $courseUser->course_id], ['user_id', $currentUser->id]])->first()->permission;
        // if the current user is the owner, delete the given course collaborator
        if ($currentUserPermission == 1 ) {
            $courseUser->delete();
        }
    }
}
