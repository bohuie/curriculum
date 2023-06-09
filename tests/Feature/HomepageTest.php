<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class HomepageTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_homepage_exists()
    {
        $response = $this->get('/');

        $response->assertStatus(200);

        $credential = ['email' => 'admin@ubc.ca', 'password'=>'password'];
        $response = $this->post('admin/login',$credential);
        $response->assertSessionMissing('error');
        //$response1->assertSessionHasErrors();
        //$response1->assertSessionMissing('error');
    }
    public function testDatabase()
{
    // Make call to application...
 
    $this->assertDatabaseHas('users', [
        'email' => 'admin@ubc.ca'
    ]);
}
    public function testSuccessfulLogin()
    {
        Post::where('name', 'someone')->delete();
        $user = User::factory()->create([
            'name' => 'someone',
            'email' => 'admin2@ubc.ca',
            'password' => Hash::make('password'),
        ]);
        $response = $this->post('admin/login', [
            'email' => 'admin2@ubc.ca',
            'password' => 'password',
        ]);

        //$response->assertRedirect('admin/login'); // Adjust the redirect URL as per your application
        //$this->assertGuest();
        //$this->assertAuthenticatedAs($user);
        Post::where('name', 'someone')->delete();
    }
    public function testFailedLogin()
    {
        $response = $this->post('admin/login', [
            'email' => 'wrong@example.com',
            'password' => 'wrongpassword',
        ]);

        //$response->assertSessionHas('These credentials do not match our records.');
        $this->assertGuest();
    }
    // public function test_a_visitor_can_able_to_login()
    // {
    //     //$user = new UserFactory.definition();
    //     $user = ['email' => 'admin@ubc.ca', 'password'=>'password'];
    //     $hasUser = $user ? true : false;

    //     $this->assertTrue($hasUser);

    //     $response = $this->actingAs($user)->get('/home');

    //     $response->assertStatus(200);
    // }
    //public function test_headers(): void
    //{
    //    $response = $this->withHeaders([
    //        'X-Header' => 'Value',
    //    ])->post('/user', ['name' => 'Sally']);
 
    //    $response->assertStatus(201);
    //}
    public function test_interacting_with_the_session(): void
    {
        $response = $this->withSession(['banned' => false])->get('/');
        $response->assertStatus(200);
    }
}
