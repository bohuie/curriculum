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

    public function test_duplicate_user(){
        $user1 = User::make([
            'name'=>'Dary',
            'email' => 'dary@gmail.com'
        ]);
        $user2 = User::make([
            'name'=>'John',
            'email' => 'john@gmail.com'
        ]);

        $this->assertTrue($user1->name != $user2->name);
    }

    public function test_delete_user(){
        $user = User::factory()->count(1)->make();
        $user = User::first();
        $flag=false;
        if($user){
            $user->delete();
            $flag=true;
        }

        $this->assertTrue($flag);
        //In the example he just goes assertTrue(true) which just always returns true.
    }

    public function test_it_stores_new_users(){
        $response=$this->followingRedirects()->post('/register', [
            'name' => 'Dary',
            'email' => 'dary@gmail.com',
            'password' => 'dary1234',
            'password_confirmation'=> 'dary1234'
        ])->assertStatus(200);

        /*
        $response->assertRedirect('/');
        $response = $this->followingRedirects()
        ->post('/login', ['email' => 'john@example.com'])
        ->assertStatus(200);
        //In example it should redirect to /home
        */
    }
    /*
    public function test_database(){
        $this->assertDatabaseHas('users', [
            'name'=> 'Dary'
        ]);
        //didn't create in seeder, but this is the same idea
    }

    
    public function test_if_seeders_works(){
        $this->seed(); //Seed all seeders in the seeder folder
    }
    */
    /*
    5 Calls you can make:
    $this->get($uri, $header=[])
    $this->post($uri, $data=[], $header=[])
    $this->put($uri, $data=[], $header=[])
    $this->patch($uri, $data=[], $header=[])
    $this->delete($uri, $data=[], $header=[])
    $uri=endpoint of route
    Not an illuminate response, but an instance of the TestResponse???
    */

}
