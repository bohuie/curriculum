<?php

namespace App\Http\Controllers;

use App\Models\syllabus\SyllabusUser;
use App\Models\syllabus\Syllabus;
use App\Models\User;
use Illuminate\Http\Request;
use App\Mail\NotifySyllabusUserMail;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Throwable;

class SyllabusUserController extends Controller
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
    public function store(Request $request, $syllabusId)
    {   
        // get the current user
        $currentUser = User::find(Auth::id());
        // get the current user permission
        $currentUserPermission = $currentUser->syllabi->where('id', $syllabusId)->first()->pivot->permission;
        // keep track of errors
        $errorMessages = Collection::make();
        $warningMessages = Collection::make();

        // if the current user is the owner, save the collaborators and their permissions
        if ($currentUserPermission == 1 ) {
            $currentPermissions = ($request->input('current_permissions')) ? $request->input('current_permissions') : array();
            $newCollabs = $request->input('new_collabs');
            $newPermissions = $request->input('new_permissions');
            // get the syllabus
            $syllabus = Syllabus::find($syllabusId);
            // get the saved collaborators for this syllabus, but not the owner
            $savedSyllabusUsers = SyllabusUser::where([['syllabus_id', '=', $syllabus->id], ['permission', '!=', 1]])->get();
            // update current collaborators for this syllabus
            foreach ($savedSyllabusUsers as $savedSyllabusUser) {
                if (array_key_exists($savedSyllabusUser->user_id, $currentPermissions)) {
                    $this->update($savedSyllabusUser, $currentPermissions);
                } else {
                    // remove old collaborator from course, make sure it's not the owner
                    if ($savedSyllabusUser->permission != 1) {
                        $this->destroy($savedSyllabusUser);
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
                        // make sure the new collab user isn't already collaborating on this syllabus 
                        if (!in_array($user->email, $syllabus->users->pluck('email')->toArray())) {
                            // get their given permission level
                            $permission = $newPermissions[$index];
                            // create a new collaborator
                            $syllabusUser = SyllabusUser::updateOrCreate(
                                ['syllabus_id' => $syllabus->id, 'user_id' => $user->id],
                            );
                            // set this syllabus user permission level
                            switch ($permission) {
                                case 'edit':
                                    $syllabusUser->permission = 2;
                                break;
                                case 'view':
                                    $syllabusUser->permission = 3;
                                break;
                            }
                            if($syllabusUser->save()){
                                Mail::to($user->email)->send(new NotifySyllabusUserMail($syllabus->course_code, $syllabus->course_num, $syllabus->course_title, $user->name));
                            } else {
                                $errorMessages->add('There was an error adding ' . '<b>' . $user->email . '</b>' . ' to syllabus ' . $syllabus->course_code . ' ' . $syllabus->course_num);
                                // array_push($errorMessages, 'There was an error adding ' . $user->email . ' to syllabus ' . $syllabus->course_code . ' ' . $syllabus->course_num);
                            }
                        } else {
                            $warningMessages->add('<b>' . $user->email . '</b>' . ' is already collaborating on syllabus ' . $syllabus->course_code . ' ' . $syllabus->course_num);

                            // array_push($errorMessages, $user->email . ' is already collaborating on syllabus ' . $syllabus->course_code . ' ' . $syllabus->course_num);
                        }
                    } else {
                        $errorMessages->add('<b>' . $newCollab . '</b>' . ' has not registered on this site. ' . "<a target='_blank' href=" . route('requestInvitation') . ">Invite $newCollab</a> and add them once they have registered.");

                        
                        // array_push($errorMessages, $newCollab . " has not registered on this site. ");
                    }
                }
            }
        // else the current user does not own this syllabus
        } else {
            $request->session()->flash('error', 'You do not have permission to add collaborators to this syllabus');
        }
        // return to the previous page
        return redirect()->back()->with('errorMessages', $errorMessages)->with('warningMessages', $warningMessages);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SyllabusUser  $syllabusUser
     * @return \Illuminate\Http\Response
     */
    public function show(SyllabusUser $syllabusUser)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SyllabusUser  $syllabusUser
     * @return \Illuminate\Http\Response
     */
    public function edit(SyllabusUser $syllabusUser)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Models\SyllabusUser  $syllabusUser
     * @return \Illuminate\Http\Response
     */
    public function update(SyllabusUser $syllabusUser, $permissions)
    {
        // update permissions for current collaborators
        switch ($permissions[$syllabusUser->user_id]) {
            case 'edit':
                $syllabusUser->permission = 2;
            break;
            
            case 'view':
                $syllabusUser->permission = 3;
            break;
        }
        
        $syllabusUser->save();
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SyllabusUser  $syllabusUser
     * @return \Illuminate\Http\Response
     */
    public function destroy(SyllabusUser $syllabusUser)
    {
        // get the current user
        $currentUser = User::find(Auth::id());
        // get the current user permission
        $currentUserPermission = SyllabusUser::where([['syllabus_id', $syllabusUser->syllabus_id], ['user_id', $currentUser->id]])->first()->permission;
        // if the current user is the owner, delete the given syllabus collaborator
        if ($currentUserPermission == 1 ) {
            $syllabusUser->delete();
        }
    }
}
