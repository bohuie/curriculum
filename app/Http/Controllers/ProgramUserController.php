<?php

namespace App\Http\Controllers;

use App\Mail\NotifyNewProgramUserMail;
use App\Mail\NotifyProgramAdminMail;
use App\Mail\NotifyProgramOwnerMail;
use App\Models\Program;
use App\Models\ProgramUser;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class ProgramUserController extends Controller
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

    public function index(): RedirectResponse
    {
        //
        return redirect()->back();
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
    public function store(Request $request, $programId)
    {
        // get the current user
        $currentUser = User::find(Auth::id());
        // get the current user permission
        $currentUserPermission = $currentUser->programs->where('program_id', $programId)->first()->pivot->permission;
        // get the program
        $program = Program::find($programId);
        // keep track of errors
        $errorMessages = Collection::make();
        $warningMessages = Collection::make();

        // if the current user is the owner, save the collaborators and their permissions
        if ($currentUserPermission == 1) {
            $currentPermissions = ($request->input('program_current_permissions')) ? $request->input('program_current_permissions') : [];
            $newCollabs = $request->input('program_new_collabs');
            $newPermissions = $request->input('program_new_permissions');
            // get the saved collaborators for this program, but not the owner
            $savedProgramUsers = ProgramUser::where([['program_id', '=', $program->program_id], ['permission', '!=', 1]])->get();
            // update current collaborators for this program
            foreach ($savedProgramUsers as $savedProgramUser) {
                if (array_key_exists($savedProgramUser->user_id, $currentPermissions)) {
                    $this->update($savedProgramUser, $currentPermissions);
                } else {
                    // remove old collaborator from program, make sure it's not the owner
                    if ($savedProgramUser->permission != 1) {
                        $this->destroy($savedProgramUser);
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
                        // make sure the new collab user isn't already collaborating on this program
                        if (! in_array($user->email, $program->users->pluck('email')->toArray())) {
                            // get their given permission level
                            $permission = $newPermissions[$index];
                            // create a new collaborator
                            $programUser = ProgramUser::updateOrCreate(
                                ['program_id' => $program->program_id, 'user_id' => $user->id],
                            );
                            $programUser = ProgramUser::where([['program_id', '=', $programUser->program_id], ['user_id', '=', $programUser->user_id]])->first();

                            // set this program user permission level
                            switch ($permission) {
                                case 'edit':
                                    $programUser->permission = 2;
                                    break;
                                case 'view':
                                    $programUser->permission = 3;
                                    break;
                            }
                            if ($programUser->save()) {
                                // update courses 'updated_at' field
                                $program = Program::find($request->input('program_id'));
                                $program->touch();

                                // get users name for last_modified_user
                                $currUser = User::find(Auth::id());
                                $program->last_modified_user = $currUser->name;
                                $program->save();

                                // email user to be added
                                Mail::to($user->email)->send(new NotifyProgramAdminMail($program->program, $currentUser->name));
                                // email the owner letting them know they have added a new collaborator
                                Mail::to($currentUser->email)->send(new NotifyProgramOwnerMail($program->program, $user->name));
                            } else {
                                $errorMessages->add('There was an error adding '.'<b>'.$user->email.'</b>'.' to program '.$program->program);
                            }
                        } else {
                            $warningMessages->add('<b>'.$user->email.'</b>'.' is already collaborating on program '.$program->program);
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
                        $programUser = ProgramUser::updateOrCreate(
                            ['program_id' => $program->program_id, 'user_id' => $newUser->id],
                        );
                        $programUser = ProgramUser::where([['program_id', '=', $programUser->program_id], ['user_id', '=', $programUser->user_id]])->first();

                        // set this program user permission level
                        switch ($permission) {
                            case 'edit':
                                $programUser->permission = 2;
                                break;
                            case 'view':
                                $programUser->permission = 3;
                                break;
                        }
                        if ($programUser->save()) {
                            // update courses 'updated_at' field
                            $program = Program::find($request->input('program_id'));
                            $program->touch();

                            // get users name for last_modified_user
                            $currUser = User::find(Auth::id());
                            $program->last_modified_user = $currUser->name;
                            $program->save();

                            // email user to be added
                            //TODO: SEND EMAIL TO NEW USER WITH THEIR PASSWORD
                            Mail::to($newUser->email)->send(new NotifyNewProgramUserMail($program->program, $currentUser->name, implode($pass), $newUser->email));
                            // email the owner letting them know they have added a new collaborator
                            Mail::to($currentUser->email)->send(new NotifyProgramOwnerMail($program->program, $newUser->name));
                        } else {
                            $errorMessages->add('There was an error adding '.'<b>'.$newUser->email.'</b>'.' to program '.$program->program);
                        }

                        // $errorMessages->add('<b>' . $newCollab . '</b>' . ' has not registered on this site. ' . "<a target='_blank' href=" . route('requestInvitation') . ">Invite $newCollab</a> and add them once they have registered.");
                    }
                }
            }
        // else the current user does not own this program
        } else {
            $errorMessages->add('You do not have permission to add collaborators to this program');
        }

        // if no errors or warnings, flash a success message
        if ($errorMessages->count() == 0 && $warningMessages->count() == 0) {
            $request->session()->flash('success', 'Successfully updated collaborators on program '.$program->program);
        }

        // return to the previous page
        return redirect()->back()->with('errorMessages', $errorMessages)->with('warningMessages', $warningMessages);
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(ProgramUser $programUser)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(ProgramUser $programUser)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(ProgramUser $programUser, $permissions)
    {
        // update permissions for current collaborators
        switch ($permissions[$programUser->user_id]) {
            case 'edit':
                $programUser->permission = 2;
                break;

            case 'view':
                $programUser->permission = 3;
                break;
        }

        $programUser->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProgramUser $programUser)
    {
        // get the current user
        $currentUser = User::find(Auth::id());
        // get the current user permission
        $currentUserPermission = ProgramUser::where([['program_id', $programUser->program_id], ['user_id', $currentUser->id]])->first()->permission;
        // if the current user is the owner, delete the given program collaborator
        if ($currentUserPermission == 1) {
            $programUser->delete();
        }
    }

    public function leave(Request $request): RedirectResponse
    {
        $program = Program::find($request->input('program_id'));
        $programUser = ProgramUser::where('user_id', $request->input('programCollaboratorId'))->where('program_id', $request->input('program_id'))->first();
        if ($programUser->delete()) {
            $request->session()->flash('success', 'Successfully left '.$program->program);
        } else {
            $request->session()->flash('error', 'Failed to leave the program');
        }

        return redirect()->back();
    }

    public function transferOwnership(Request $request): RedirectResponse
    {
        $program = Program::find($request->input('program_id'));
        $oldProgramOwner = ProgramUser::where('user_id', $request->input('oldOwnerId'))->where('program_id', $request->input('program_id'))->first();
        $newProgramOwner = ProgramUser::where('user_id', $request->input('newOwnerId'))->where('program_id', $request->input('program_id'))->first();

        //transfer ownership and set old owner to be an editor
        $newProgramOwner->permission = 1;
        $oldProgramOwner->permission = 2;

        if ($newProgramOwner->save()) {
            if ($oldProgramOwner->save()) {
                $request->session()->flash('success', 'Successfully transferred ownership for the '.$program->program.' program.');
            } else {
                $request->session()->flash('error', 'Failed to transfer ownership of the '.$program->program.' program');
            }
        } else {
            $request->session()->flash('error', 'Failed to transfer ownership of the '.$program->program.' program');
        }

        return redirect()->back();
    }
}
