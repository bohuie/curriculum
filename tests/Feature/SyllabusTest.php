<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

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
        $credential = ['course_title' => 'Test Course', 'course_code'=>'TEST', 'campus'=>'O', 'course_year'=>'2022', 'course_term'=>'W2', 'course_instructor'=>'Dr. Testy Testerson'];
        $response = $this->post('syllabusGenerator',$credential);
        $response->assertSessionMissing('error');
    }
}
