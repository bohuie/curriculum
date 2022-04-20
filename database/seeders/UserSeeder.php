<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        //User::delete();
        DB::table('role_user')->truncate();

        $adminRole = Role::where('role','administrator')->first();
        $userRole = Role::where('role','user')->first();
        /* add you information here. Notice there is an ADMIN account and USER account. Make sure your email is different.*/
        $admin = User::create([
            'name' => 'Michael Admin',
            'email' => 'michaelodermatt@outlook.com',
            'password' => Hash::make('password'), /*default local password is "password" */
        ]);

        $user = User::create([
            'name' => 'Michael Users',
            'email' => '---@gmail.com',
            'password' => Hash::make('password'), /*default local password is "password" */
        ]);


        $admin->roles()->attach($adminRole);
        $admin->roles()->attach($userRole);
        $user->roles()->attach($userRole);


    }
}
