<?php

namespace Tests\Feature;

use App\Models\Invite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * A basic feature test example.
     */

    /*
    public function test_example()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
    */
    public function test_register_user(): void
    {

        $response = $this->post(route('register'), [
            'name' => 'Test Register',
            'email' => 'test.register@ubc.ca',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertDatabaseHas('users', [
            'name' => 'Test Register',
            'email' => 'test.register@ubc.ca',
        ]);

    }
    /*
        public function test_recover_password()
        {
            $response=$this->post(route('password.email'), [
                "email" => "test.register@ubc.ca"
            ]);
        }

        public function test_login_user(){
            $response=$this->post(route('login'), [
                "email" => "test.register@ubc.ca",
                "password" => "password",
            ]);

            $user= User::where('email', 'test.register@ubc.ca')->first();

            $response->assertStatus(302);
            $response->assertRedirect('home');

            if(Auth::id() == $user->id){
                $this->assertTrue(true);
            }else $this->assertTrue(false);

          //  User::where('email', 'test.register@ubc.ca')->delete();
            //$this->followRedirects($response)->assertSee('.success-message');
        }
        public function test_user_invite()
        {
            $response=$this->post(route('storeInvitation'), [
                "email" => "test.register-invite@ubc.ca"
            ]);

            $user= User::where('email', 'test.register@ubc.ca')->first();

            $this->assertDatabaseHas('invites', [
                'email' => 'test.register-invite@ubc.ca'
            ]);

        }
    */
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
