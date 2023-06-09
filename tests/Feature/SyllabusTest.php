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
        $response1 = $this->get('/syllabusGenerator');

        $response1->assertStatus(302);
        $response1->dumpHeaders();
        $response1->dumpSession();
        $response1->dump();
    }

    public function test_save()
    {
        /*
        $this->withoutExceptionHandling();

        $user = Authenticatable::factory()->create();
        $this->actingAs($user);
        $credential = ['course_title' => 'Test Course', 'course_code'=> 'TEST','delivery_modality'=>'B', 'course_year'=>'2022', 'course_term'=>'W2', 'created_at'=>'2023-05-24 13:52:33', 'updated at'=>'2023-05-29 14:16:08','course_instructor'=>'Dr. Testy Testerson','campus'=>'O','faculty'=>'College of Graduate Studies', 'department'=>'Graduate Studies', 'last_modified_user'=>'Michael Ogden','include_alignment'=>0];
        $response = $this->post('/syllabusGenerator',$credential);
        $response->assertSessionHas('error');
        */
        
        $response2 = $this->post(route('syllabus.save', array (
            'import_course_settings' => 
            array (
              'courseId' => '1',
            ),
            '_token' => 'oA70S8R21QemC9F1M2E2YR2gyMtP8K6kKYHfxjjP',
            'courseTitle' => 'Intro to Cross Listing',
            'courseCode' => 'NEWS',
            'courseNumber' => '310',
            'campus' => 'O',
            'faculty' => NULL,
            'startTime' => NULL,
            'endTime' => NULL,
            'courseLocation' => NULL,
            'courseYear' => '2022',
            'courseSemester' => 'W2',
            'deliveryModality' => 'I',
            'officeHour' => NULL,
            'courseInstructor' => 
            array (
              0 => 'Dr. John Cena',
            ),
            'courseInstructorEmail' => 
            array (
              0 => 'john.cena@ubc.ca',
            ),
            'otherCourseStaff' => NULL,
            'courseDesc' => NULL,
            'courseFormat' => NULL,
            'courseOverview' => NULL,
            'learningOutcome' => NULL,
            'learningActivities' => NULL,
            'learningMaterials' => NULL,
            'learningResources' => NULL,
            'learningAssessments' => NULL,
            'latePolicy' => NULL,
            'missingExam' => NULL,
            'missingActivity' => NULL,
            'passingCriteria' => NULL,
            'customResourceTitle' => NULL,
            'customResource' => NULL,
            'okanaganSyllabusResources' => 
            array (
              1 => 'land',
              2 => 'academic',
              3 => 'finals',
              4 => 'grading',
              5 => 'disability',
              6 => 'equity',
              7 => 'health',
              8 => 'student',
              9 => 'global',
              10 => 'copyright',
              11 => 'safewalk',
              12 => 'ombud',
            ),
            'copyright' => '2',
            )));


        
        $response2->assertStatus(200);
        
        $response2->dumpHeaders();
        $response2->dumpSession();
        $response2->dump();
    }
}
