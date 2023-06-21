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

        $user = User::factory()->count(1)->make();
        $user = User::first();
        //Need to use real user in DB for this to work

        $count= DB::table('courses')->count();
        

        $response=$this->actingAs($user)->post(route('courses.store'), [
            'course_code' => 'TEST',
            'course_num' => '111',
            'delivery_modality' => $delivery_modalities[array_rand($delivery_modalities)],
            'course_year' => 2022,
            'course_semester' => $semesters[array_rand($semesters)],
            'course_title' => 'Intro to Testing',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'assigned' => 1,
            'type' => 'unassigned',
            'standard_category_id' => 1,
            'scale_category_id' => 1,
            'user_id' => $user->id
        ]);


        $response->assertRedirect('/courseWizard/'.($count+1).'/step1');
        
        /*
        //$this->be($user);
        $course=Course::factory(1)->create();
        //dd($course);
        $response= $this->post(route('courses.store', $course));
        $response->assertRedirectTo(route('courseWizard.step1', $course->course_id));
        //$this->assertTrue();
        */
    }
}
