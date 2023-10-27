<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Invite;

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

    public function test_login_user(){
        $response=$this->post(route('login'), [
            "email" => "test.register@ubc.ca",
            "password" => "password",
        ]);
    }
    /*
    public function testVerifyEmailValidatesUser(): void
    {
        // VerifyEmail extends Illuminate\Auth\Notifications\VerifyEmail in this example
        $notification = new Invite();
        $user = User::where('email', 'test.register@ubc.ca')->first();
    
        // New user should not has verified their email yet
        $this->assertFalse($user->hasVerifiedEmail());
    
        $mail = $notification->toMail($user);
        $uri = $mail->actionUrl;
    
        // Simulate clicking on the validation link
        $this->actingAs($user)
            ->get($uri);
    
        // User should have verified their email
        $this->assertTrue(User::find($user->id)->hasVerifiedEmail());

        User::where('email', 'test.register@ubc.ca')->delete();
    }
    */



}
