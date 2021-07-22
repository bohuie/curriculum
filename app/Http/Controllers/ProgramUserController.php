<?php

namespace App\Http\Controllers;

use App\Models\ProgramUser;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use App\Mail\NotifyProgramAdminMail;
use Illuminate\Support\Facades\Mail;

use Illuminate\Support\Facades\DB;

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
        //
        $this->validate($request, [
            'email'=> 'required',
            'email'=> 'exists:users,email',
            ]);
        
        $user = User::where('email', $request->input('email'))->first();

        //$program_user = new ProgramUser;
        //$program_user->program_id = $request->input('program_id');
        //$program_user->user_id = $user->id;

        $program_id = $request->input('program_id');
        
        $program = DB::table('programs')->where('program_id',$program_id)->first();

        //dd($program);

        $pu = DB::table('program_users')->updateOrInsert(
            ['program_id' => $request->input('program_id'), 'user_id' => $user->id ]
        );

        if($user->save()){
            Mail::to($user->email)->send(new NotifyProgramAdminMail($program->program, $program->department, $user->name));

            $request->session()->flash('success', 'Collaborator added');
        }else{
            $request->session()->flash('error', 'There was an error adding the Collaborator');
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
        $pu = ProgramUser::where('program_id', $program_id)->where('user_id', $user_id);

        if($pu->delete()){
            $request->session()->flash('success','Administrator has been deleted');
        }else{
            $request->session()->flash('error', 'There was an error deleting the user');
        }

        return redirect()->back();
    }
}
