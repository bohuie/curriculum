<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\Course;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CourseTest extends TestCase
{
    /**
     * 
     *
     * @return void
     */

    public function test_storing_new_course(){

        $delivery_modalities=['O','B','I'];
        $semesters=['W1','W2','S1','S2'];

        //$user = User::factory()->count(1)->make();
        //$user = User::first();

        //Create Verified User
        DB::table('users')->insert([
            'name' => 'Test Course',
            'email' => 'test-course@ubc.ca',
            'email_verified_at' => Carbon::now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'
        ]);

        $user = User::where('email', 'test-course@ubc.ca')->first();

        $response=$this->actingAs($user)->post(route('courses.store'), [
            'course_code' => 'TEST',
            'course_num' => '111',
            'delivery_modality' => $delivery_modalities[array_rand($delivery_modalities)],
            'course_year' => 2022,
            'course_semester' => $semesters[array_rand($semesters)],
            'course_title' => 'Intro to Unit Testing',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'assigned' => 1,
            'type' => 'unassigned',
            'standard_category_id' => 1,
            'scale_category_id' => 1,
            'user_id' => $user->id
        ]);

        $course = Course::where('course_title', 'Intro to Unit Testing')->orderBy('course_id', 'DESC')->first();

        $response->assertRedirect('/courseWizard/'.($course->course_id).'/step1');

        $this->assertDatabaseHas('courses', [
            'course_title' => "Intro to Unit Testing"
        ]);
        
    }
    
    public function test_submit_course(){
        $user = User::where('email', 'test-course@ubc.ca')->first();
        $course = Course::where('course_title', 'Intro to Unit Testing')->orderBy('course_id', 'DESC')->first();
        $response=$this->actingAs($user)->get(route('courses.submit', $course->course_id));
        $response->assertRedirect('/home');

        $this->assertDatabaseHas('courses', [
            'course_title' => "Intro to Unit Testing"
        ]);

    }

    public function test_create_clo(){
        $user = User::where('email', 'test-course@ubc.ca')->first();
        $course = Course::where('course_title', 'Intro to Unit Testing')->orderBy('course_id', 'DESC')->first();

        //LearningOutcomeController@store

        $response=$this->actingAs($user)->post(route('course.outcomes.store'), [
            "current_l_outcome" => [
            
            ],
            "current_l_outcome_short_phrase" => [
            
            ],
            "new_l_outcomes" => [
            0 => "Test Course Learning Outcome 1"
            ],
            "new_short_phrases" => [
            0 => "Test CLO Short 1"
            ],
            "course_id" => $course->course_id
        ]);

        $this->assertDatabaseHas('learning_outcomes', [
            'l_outcome' => 'Test Course Learning Outcome 1',
            'clo_shortphrase' => 'Test CLO Short 1'
        ]);
    }

    public function test_standardsOutcomeMap_store(){
        $user = User::where('email', 'test-course@ubc.ca')->first();
        $course = Course::where('course_title', 'Intro to Unit Testing')->orderBy('course_id', 'DESC')->first();


        //setting this mapping to all "Introduced" for this course, except 1
        $response=$this->actingAs($user)->post(route('outcomeMap.store'), [
            "course_id" => $course->course_id,
            "map" => [
                $course->course_id => [
                    1 => "102",
                    2 => "101",
                    3 => "101",
                    4 => "101",
                    5 => "101",
                    6 => "101",
                ]
            ]
        ]);

        $this->assertDatabaseHas('standards_outcome_maps', [
            'course_id' => $course->course_id,
            'standard_scale_id' => '101',
            'standard_id' => 6
        ]);
        //this is failing when it should be working
        //cannot get out of for loop in StandardOutcomeMapController
    }
    

    public function test_adding_collaborator(){
        $user = User::where('email', 'test-course@ubc.ca')->first();
        $course = Course::where('course_title', 'Intro to Unit Testing')->orderBy('course_id', 'DESC')->first();

        //Create Verified User for Course Collaboration Testing
        DB::table('users')->insert([
            'name' => 'Test Course Collab',
            'email' => 'test-course-collab@ubc.ca',
            'email_verified_at' => Carbon::now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'
        ]);

        $user2 = User::where('email', 'test-course-collab@ubc.ca')->first();

        $response=$this->actingAs($user)->post(route('courses.assign', $course->course_id), [
            "course_new_collabs" => [0 => "test-course-collab@ubc.ca"],
            "course_new_permissions" => [0 => "edit"],
        ]);

        $this->assertDatabaseHas('course_users', [
            'course_id' => $course->course_id,
            'user_id' => $user2->id
        ]);

    }

    
    public function test_transferring_course(){
        
        $user = User::where('email', 'test-course@ubc.ca')->first();
        $course = Course::where('course_title', 'Intro to Unit Testing')->orderBy('course_id', 'DESC')->first();
        $user2 = User::where('email', 'test-course-collab@ubc.ca')->first();

        $response=$this->actingAs($user)->post(route('courseUser.transferOwnership'), [
            'course_id' => $course->course_id,
            'oldOwnerId' => $user->id,
            'newOwnerId' => $user2->id
        ]);

        $this->assertDatabaseHas('course_users', [
            'course_id' => $course->course_id,
            'user_id' => $user->id,
            'permission' => 2
        ]);

        $this->assertDatabaseHas('course_users', [
            'course_id' => $course->course_id,
            'user_id' => $user2->id,
            'permission' => 1
        ]);
    }
    

    public function test_removing_collaborator(){

        $user = User::where('email', 'test-course@ubc.ca')->first();
        $course = Course::where('course_title', 'Intro to Unit Testing')->orderBy('course_id', 'DESC')->first();
        $user2 = User::where('email', 'test-course-collab@ubc.ca')->first();

        //courses.unassign is an unused route, rather within CourseUserController.php in the store() method
        // "$this->destroy($savedCourseUser);" is called when the new list of users is shorter than the current
        //Therefore, we just use the same path courses.assign and pass an empty array

        $response=$this->actingAs($user2)->post(route('courses.assign', $course->course_id), []);

        $this->assertDatabaseMissing('course_users', [
            'course_id' => $course->course_id,
            'user_id' => $user->id
        ]);
    }
    
    public function test_deleting_course(){
        
        $user2 = User::where('email', 'test-course-collab@ubc.ca')->first();
        $course = Course::where('course_title', 'Intro to Unit Testing')->orderBy('course_id', 'DESC')->first();

        $response=$this->actingAs($user2)->delete(route('courses.destroy', $course->course_id));

        $this->assertDatabaseMissing('courses', [
            'course_id' => $course->course_id
        ]);
        
        //Delete Test User(s)
        //We are testing Course and CourseUser routes here, so deleting manually is fine to clean up.
        User::where('email', 'test-course-collab@ubc.ca')->delete();
        User::where('email', 'test-course@ubc.ca')->delete();

        $this->assertDatabaseMissing('users', [
            'email' => 'test-course-collab@ubc.ca',
            'email' => 'test-course@ubc.ca'
        ]);
    }
    
    


    

}
