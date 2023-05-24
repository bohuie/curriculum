<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

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

        $response->assertStatus(302);

        $credential = ['email' => 'admin@ubc.ca', 'password'=>'password'];
        $response = $this->post('admin/login',$credential);
        $response->assertSessionMissing('error');
        $credential1 = ['email' => 'aasdfasf@ubc.ca', 'password'=>'password1'];
        $response1 = $this->post('admin/login',$credential1);
        //$response1->assertSessionHasErrors();
        $response->assertSessionMissing('error');
    }
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
        $response->assertStatus(302);
    }
}
