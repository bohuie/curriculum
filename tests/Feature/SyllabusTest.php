<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Database\Eloquent\Factories;
use App\Models\User;
use Illuminate\Foundation\Auth\User as Authenticatable;

class SyllabusTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_syllabus_exists()
    {
        $response = $this->get('/syllabusGenerator');

        $response->assertStatus(302);
    }

    public function test_save()
    {
        $this->withoutExceptionHandling();

        $user = Authenticatable::factory()->create();
        $this->actingAs($user);
        $credential = ['course_title' => 'Test Course', 'course_code'=> 'TEST','delivery_modality'=>'B', 'course_year'=>'2022', 'course_term'=>'W2', 'created_at'=>'2023-05-24 13:52:33', 'updated at'=>'2023-05-29 14:16:08','course_instructor'=>'Dr. Testy Testerson','campus'=>'O','faculty'=>'College of Graduate Studies', 'department'=>'Graduate Studies', 'last_modified_user'=>'Michael Ogden','include_alignment'=>0];
        $response = $this->post('/syllabusGenerator',$credential);
        $response->assertSessionHas('error');
    }
}
