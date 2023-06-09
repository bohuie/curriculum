<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_register_page_exists()
    {
        $response = $this->get('/admin/register');
        $expectedUrl = (route('register'));
        $response->assertStatus(200);
    }
    public function test_register_successful()
    {
        
        $this->post('/admin/register',[
            'Name' => 'test user',
            'E-Mail Address' => 'test@ubc.ca',
            'Password' => 'password',
            'Confirm Password' => 'password'
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'test@ubc.ca'
        ]);
    }
}
