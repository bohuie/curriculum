<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Gate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        if (Gate::denies('admin-privilege')) {
            return redirect(route('home'));
        }

        $users = User::all();

        return view('admin.users.index')->with('users', $users);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {

        if (Gate::denies('admin-privilege')) {
            return redirect(route('admin.users.index'));
        }

        $roles = Role::all();

        return view('admin.users.edit')->with('user', $user)->with('roles', $roles);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        //
        $user->roles()->sync($request->roles);
        $user->name = $request->name;
        $user->email = $request->email;

        if ($user->save()) {
            $request->session()->flash('success', $user->name.' has been updated');
        } else {
            $request->session()->flash('error', 'There was an error updating the user');
        }

        return redirect()->route('admin.users.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user, Request $request): RedirectResponse
    {

        if (Gate::denies('admin-privilege')) {
            return redirect(route('admin.users.index'));
        }

        if ($user->delete()) {
            $request->session()->flash('success', $user->name.' has been deleted');
        } else {
            $request->session()->flash('error', 'There was an error deleting the user');
        }

        return redirect()->route('admin.users.index');
    }
}
