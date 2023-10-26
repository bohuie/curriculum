<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class UserTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    /*
    public function test_example()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
    */
    public function test_register_user(){

        $response=$this->post(route('register'), [
            "name" => "Test Register",
            "email" => "test.register@ubc.ca",
            "password" => "password",
            "password_confirmation" => "password"
        ]);

        $this->assertDatabaseHas('users', [
            'name' => 'Test Register',
            'email' => 'test.register@ubc.ca'
        ]);

        User::where('email', 'test.register@ubc.ca')->delete();
    }



}
