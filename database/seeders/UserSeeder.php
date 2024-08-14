<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        //User::delete();
        DB::table('role_user')->truncate();

        $adminRole = Role::where('role', 'administrator')->first();
        $userRole = Role::where('role', 'user')->first();
        /* add you information here. Notice there is an ADMIN account and USER account. Make sure your email is different.*/
        $admin = User::create([
            'name' => 'Sahil',
            'email' => 'sahil.chawla@ubc.ca',
            'password' => Hash::make('password'), /*default local password is "password" */
        ]);

        $user = User::create([
            'name' => 'Sahil Chawla',
            'email' => 'sahil971chawla@gmail.com',
            'password' => Hash::make('password'), /*default local password is "password" */
        ]);

        $admin->roles()->attach($adminRole);
        $admin->roles()->attach($userRole);
        $user->roles()->attach($userRole);

    }
}
