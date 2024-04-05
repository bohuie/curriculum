<?php

namespace App\Http\Controllers;

use App\Mail\NotifyNewSyllabusUserMail;
use App\Mail\NotifySyllabusUserMail;
use App\Mail\NotifySyllabusUserOwnerMail;
use App\Models\syllabus\Syllabus;
use App\Models\syllabus\SyllabusUser;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

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
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $syllabusId)
    {
        // get the current user
        $currentUser = User::find(Auth::id());
        // get the current user permission
        $currentUserPermission = $currentUser->syllabi->where('id', $syllabusId)->first()->pivot->permission;
        // get the syllabus
        $syllabus = Syllabus::find($syllabusId);

        // keep track of errors
        $errorMessages = Collection::make();
        $warningMessages = Collection::make();

        // if the current user is the owner, save the collaborators and their permissions
        if ($currentUserPermission == 1) {
            $currentPermissions = ($request->input('syllabus_current_permissions')) ? $request->input('syllabus_current_permissions') : [];
            $newCollabs = $request->input('syllabus_new_collabs');
            $newPermissions = $request->input('syllabus_new_permissions');
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
                        if (! in_array($user->email, $syllabus->users->pluck('email')->toArray())) {
                            // get their given permission level
                            $permission = $newPermissions[$index];
                            // create a new collaborator
                            $syllabusUser = SyllabusUser::updateOrCreate(
                                ['syllabus_id' => $syllabus->id, 'user_id' => $user->id],
                            );
                            $syllabusUser = SyllabusUser::where([['syllabus_id', '=', $syllabusUser->syllabus_id], ['user_id', '=', $syllabusUser->user_id]])->first();

                            // set this syllabus user permission level
                            switch ($permission) {
                                case 'edit':
                                    $syllabusUser->permission = 2;
                                    break;
                                case 'view':
                                    $syllabusUser->permission = 3;
                                    break;
                            }
                            if ($syllabusUser->save()) {
                                // update syllabus 'updated_at' field
                                $syllabus = Syllabus::find($syllabusId);
                                $syllabus->touch();

                                // get users name for last_modified_user
                                $currUser = User::find(Auth::id());
                                $syllabus->last_modified_user = $currUser->name;
                                $syllabus->save();

                                // email user to be added
                                Mail::to($user->email)->send(new NotifySyllabusUserMail($syllabus->course_code, $syllabus->course_num, $syllabus->course_title, $currentUser->name));
                                // email the owner letting them know they have added a new collaborator
                                Mail::to($currentUser->email)->send(new NotifySyllabusUserOwnerMail($syllabus->course_code, $syllabus->course_num, $syllabus->course_title, $user->name));
                            } else {
                                $errorMessages->add('There was an error adding '.'<b>'.$user->email.'</b>'.' to syllabus '.$syllabus->course_code.' '.$syllabus->course_num);
                            }
                        } else {
                            $warningMessages->add('<b>'.$user->email.'</b>'.' is already collaborating on syllabus '.$syllabus->course_code.' '.$syllabus->course_num);
                        }
                    } else {
                        $name = explode('@', $newCollab);
                        $newUser = new User;
                        $newUser->name = $name[0];
                        $newUser->email = $newCollab;
                        $newUser->has_temp = 1;
                        // generate random password
                        $comb = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
                        $pass = [];
                        $combLen = strlen($comb) - 1;
                        for ($i = 0; $i < 8; $i++) {
                            $n = rand(0, $combLen);
                            $pass[] = $comb[$n];
                        }
                        // store random password
                        $newUser->password = Hash::make(implode($pass));
                        $newUser->email_verified_at = Carbon::now();
                        $newUser->save();

                        // get their given permission level
                        $permission = $newPermissions[$index];
                        // create a new collaborator
                        $syllabusUser = SyllabusUser::updateOrCreate(
                            ['syllabus_id' => $syllabus->id, 'user_id' => $newUser->id],
                        );
                        $syllabusUser = SyllabusUser::where([['syllabus_id', '=', $syllabusUser->syllabus_id], ['user_id', '=', $syllabusUser->user_id]])->first();

                        // set this syllabus user permission level
                        switch ($permission) {
                            case 'edit':
                                $syllabusUser->permission = 2;
                                break;
                            case 'view':
                                $syllabusUser->permission = 3;
                                break;
                        }
                        if ($syllabusUser->save()) {
                            // update syllabus 'updated_at' field
                            $syllabus = syllabus::find($syllabusId);
                            $syllabus->touch();

                            // get users name for last_modified_user
                            $currUser = User::find(Auth::id());
                            $syllabus->last_modified_user = $currUser->name;
                            $syllabus->save();

                            // email user to be added
                            Mail::to($newUser->email)->send(new NotifyNewSyllabusUserMail($syllabus->course_code, $syllabus->course_num, $syllabus->course_title, $currentUser->name, implode($pass), $newUser->email));
                            // email the owner letting them know they have added a new collaborator
                            Mail::to($currentUser->email)->send(new NotifySyllabusUserOwnerMail($syllabus->course_code, $syllabus->course_num, $syllabus->course_title, $newUser->name));
                        } else {
                            $errorMessages->add('There was an error adding '.'<b>'.$newUser->email.'</b>'.' to course '.$syllabus->course_title);
                        }
                        // $errorMessages->add('<b>' . $newCollab . '</b>' . ' has not registered on this site. ' . "<a target='_blank' href=" . route('requestInvitation') . ">Invite $newCollab</a> and add them once they have registered.");
                    }
                }
            }
        // else the current user does not own this syllabus
        } else {
            $errorMessages->add('You do not have permission to add collaborators to this syllabus');
        }
        // if no errors or warnings, flash a success message
        if ($errorMessages->count() == 0 && $warningMessages->count() == 0) {
            $request->session()->flash('success', 'Successfully updated collaborators on syllabus '.$syllabus->course_code.' '.$syllabus->course_num);
        }

        // return to the previous page
        return redirect()->back()->with('errorMessages', $errorMessages)->with('warningMessages', $warningMessages);
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(SyllabusUser $syllabusUser)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(SyllabusUser $syllabusUser)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
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
     * @return \Illuminate\Http\Response
     */
    public function destroy(SyllabusUser $syllabusUser)
    {
        // get the current user
        $currentUser = User::find(Auth::id());
        // get the current user permission
        $currentUserPermission = SyllabusUser::where([['syllabus_id', $syllabusUser->syllabus_id], ['user_id', $currentUser->id]])->first()->permission;
        // if the current user is the owner, delete the given syllabus collaborator
        if ($currentUserPermission == 1) {
            $syllabusUser->delete();
        }
    }

    public function leave(Request $request)
    {

        $syllabus = Syllabus::find($request->input('syllabus_id'));
        $syllabusUser = SyllabusUser::where('user_id', $request->input('syllabusCollaboratorId'))->where('syllabus_id', $request->input('syllabus_id'))->first();
        if ($syllabusUser->delete()) {
            $request->session()->flash('success', 'Successfully left '.$syllabus->course_title);
        } else {
            $request->session()->flash('error', 'Failed to leave the syllabus');
        }

        // return to the dashboard
        return redirect()->route('home');
    }

    public function transferOwnership(Request $request): RedirectResponse
    {
        $syllabus = Syllabus::find($request->input('syllabus_id'));
        $oldSyllabusOwner = SyllabusUser::where('user_id', $request->input('oldOwnerId'))->where('syllabus_id', $request->input('syllabus_id'))->first();
        $newSyllabusOwner = SyllabusUser::where('user_id', $request->input('newOwnerId'))->where('syllabus_id', $request->input('syllabus_id'))->first();

        //transfer ownership and set old owner to be an editor
        $newSyllabusOwner->permission = 1;
        $oldSyllabusOwner->permission = 2;

        if ($newSyllabusOwner->save()) {
            if ($oldSyllabusOwner->save()) {
                $request->session()->flash('success', 'Successfully transferred ownership for the '.$syllabus->course_title.' syllabus.');
            } else {
                $request->session()->flash('error', 'Failed to transfer ownership of the '.$syllabus->course_title.' syllabus');
            }
        } else {
            $request->session()->flash('error', 'Failed to transfer ownership of the '.$syllabus->course_title.' syllabus');
        }

        return redirect()->back();
    }
}
