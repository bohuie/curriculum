<?php

namespace App\Http\Controllers;

use App\Models\syllabus\SyllabusUser;
use App\Models\syllabus\Syllabus;
use App\Models\User;
use Illuminate\Http\Request;
use App\Mail\NotifySyllabusUserMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

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
        $currentUser = User::find(Auth::id());
        $currentUserPermission = $currentUser->syllabi->where('id', $syllabusId)->first()->pivot->permission;
        // if the current user is the owner, try add the given collaborator
        if ($currentUserPermission == 1 ) {
            // validate request 
            $validator = $this->validate($request, [
                'email'=> 'required',
                'email'=> 'exists:users,email',
                'permission' => 'required',
                ]);
            // find the syllabus
            $syllabus = Syllabus::find($syllabusId);
            // find the given collaborator by their email
            $user = User::where('email', $request->input('email'))->first();
            // get their given permission level
            $permission = $request->input('permission');
            // create a new collaborator
            SyllabusUser::updateOrCreate(
                ['syllabus_id' => $syllabus->id, 'user_id' => $user->id],
            );
            // find the newly created or updated syllabus user
            $syllabusUser = SyllabusUser::where([
                ['syllabus_id', $syllabus->id],
                ['user_id', $user->id]
            ])->first();
            // set this syllabus user permission level
            switch ($permission) {
                case 'edit':
                    $syllabusUser->permission = 2;
                break;
                case 'view':
                    $syllabusUser->permission = 3;
                break;
            }
            // save this syllabus user
            if($syllabusUser->save()){
                Mail::to($user->email)->send(new NotifySyllabusUserMail($syllabus->course_code, $syllabus->course_num, $syllabus->course_title, $user->name));
                $request->session()->flash('success', $user->email . ' was successfully added to syllabus ' . $syllabus->course_code . ' ' . $syllabus->course_num);
            }else{
                $request->session()->flash('error', 'There was an error adding ' . $user->email . ' to syllabus ' . $syllabus->course_code . ' ' . $syllabus->course_num);
            }
        // else the current user is not the owner, flash an error
        } else {
            $request->session()->flash('error', 'You do not have permission to add collaborators to this syllabus');
        }
        // return to the previous page
        return redirect()->back();
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
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SyllabusUser  $syllabusUser
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SyllabusUser $syllabusUser)
    {
        //

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SyllabusUser  $syllabusUser
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $syllabusId)
    {
        $user = User::where('email', $request->input('email'))->first();

        $syllabusUser = SyllabusUser::where('syllabus_id', $syllabusId)->where('user_id', $user->id);

        if($syllabusUser->delete()){
            $request->session()->flash('success', $user->email.' removed successfully');
        }else{
            $request->session()->flash('error', 'There was an error removing ' . $user->email);
        }

        return redirect()->back();
    }
}
