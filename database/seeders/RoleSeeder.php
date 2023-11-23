<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        //Role::delete();
        Role::create(['role' => 'administrator']);
        Role::create(['role' => 'user']);

    }
}
