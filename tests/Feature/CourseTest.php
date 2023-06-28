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
        //it turns out that this is just pulling the first user from the database
        //therefore only works with an authenticated user
        //we need to make an authenticated/verified user for this test

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

        $count= DB::table('courses')->count();

        $response->assertRedirect('/courseWizard/'.($count).'/step1');
        
    }

    /*
    public function test_deleting_course(){
        $user = User::factory()->count(1)->make();
        $user = User::first();
        $count= DB::table('courses')->count();

        $response=$this->actingAs($user)->delete(route('courses.unassign', $count));
        //or
        $response=$this->actingAs($user)->delete(route('courses.unassign'), [
            'course' => $count,
        ]);
        

        $this->assertDatabaseMissing('courses', [
            'course_id' => $count,
        ]);




    }
    */

}
