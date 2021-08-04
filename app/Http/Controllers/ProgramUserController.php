<?php

namespace App\Http\Controllers;

use App\Models\ProgramUser;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use App\Mail\NotifyProgramAdminMail;
use App\Models\Program;
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
        // get user accessing function
        $currentUser = User::find(Auth::id());
        $currentUserPermission = $currentUser->programs->where('program_id', $request->input('program_id'))->first()->pivot->permission;
        if ($currentUserPermission == 1) {
            // Validate request 
            $validator = $this->validate($request, [
                'email'=> 'required',
                'email'=> 'exists:users,email',
                'permission' => 'required'
                ]);

            // get user
            $user = User::where('email', $request->input('email'))->first();
            // get program id
            $program_id = $request->input('program_id');
            // get program
            $program = Program::where('program_id',$program_id)->first();
            //get permission
            $permission = $request->input('permission');
            // create a new collaborator
            ProgramUser::updateOrCreate(
                ['program_id' => $program_id, 'user_id' => $user->id ]
            );
            // find the newly created or updated program user
            $programUser = ProgramUser::where([
                ['program_id', $program_id],
                ['user_id', $user->id]
            ])->first();
            // Set the program users permission level
            switch ($permission) {
                case 'edit':
                    $programUser->permission = 2;
                break;
                case 'view':
                    $programUser->permission = 3;
                break;
            }

            if($programUser->save()){
                Mail::to($user->email)->send(new NotifyProgramAdminMail($program->program, $program->department, $currentUser->name));
                $request->session()->flash('success', $user->email . ' was successfully added to program ' .$program->program);
            }else{
                $request->session()->flash('error', 'There was an error adding the Collaborator');
            }

        } else {
            $request->session()->flash('error', 'You do not have permission to add collaborators to this syllabus');
        }

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProgramUser  $programUser
     * @return \Illuminate\Http\Response
     */
    public function show(ProgramUser $programUser)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ProgramUser  $programUser
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
     * @param  \App\Models\ProgramUser  $programUser
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProgramUser $programUser)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProgramUser  $programUser
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        //
        $program_id = $request->input('program_id');
        $user_id = $request->input('user_id');
        $user = User::find($user_id);
        $programUser = ProgramUser::where('program_id', $program_id)->where('user_id', $user_id);

        if($programUser->delete()){
            $request->session()->flash('success', $user->name. ' has been deleted');
        }else{
            $request->session()->flash('error', 'There was an error deleting the user');
        }

        return redirect()->back();
    }
}
