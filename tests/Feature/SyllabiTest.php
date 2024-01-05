<?php

namespace Tests\Feature;

use App\Models\Program;
use App\Models\syllabus\Syllabus;
use App\Models\syllabus\SyllabusUser;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class SyllabiTest extends TestCase
{
    public function test_syllabus_save(): void
    {
        DB::table('users')->insert([
            'name' => 'Test Syllabi',
            'email' => 'test-syllabi@ubc.ca',
            'email_verified_at' => Carbon::now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        ]);

        $user = User::where('email', 'test-syllabi@ubc.ca')->first();

        $response = $this->actingAs($user)->post(route('syllabus.save'), [

            'courseTitle' => 'Intro to Greatness',
            'courseCode' => 'CPSC',
            'courseNumber' => '111',
            'campus' => 'O',
            'faculty' => null,
            'startTime' => null,
            'endTime' => null,
            'courseLocation' => null,
            'courseYear' => '2023',
            'courseSemester' => 'W1',
            'deliveryModality' => 'B',
            'officeHour' => null,
            'courseInstructor' => [
                0 => 'Dr. Sahil',
            ],
            'courseInstructorEmail' => [
                0 => 'rockstar.chawla971@gmail.com',
            ],
            'otherCourseStaff' => null,
            'courseDesc' => null,
            'courseFormat' => null,
            'courseOverview' => null,
            'learningOutcome' => null,
            'learningActivities' => null,
            'learningMaterials' => null,
            'learningResources' => null,
            'learningAssessments' => null,
            'latePolicy' => null,
            'missingExam' => null,
            'missingActivity' => null,
            'passingCriteria' => null,
            'customResourceTitle' => null,
            'customResource' => null,
            'okanaganSyllabusResources' => [
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
            ],
            'copyright' => '2',
        ]
        );

        $syllabus = Syllabus::where('course_title', 'Intro to Greatness')->orderBy('id', 'DESC')->first();

        $this->assertDatabaseHas('syllabi', [
            'id' => $syllabus->id,
        ]);

    }

    public function test_syllabus_add_collab(): void
    {
        $user = User::where('email', 'test-syllabi@ubc.ca')->first();
        $syllabus = Syllabus::where('course_title', 'Intro to Greatness')->orderBy('id', 'DESC')->first();

        DB::table('users')->insert([
            'name' => 'Test Syllabus Collab',
            'email' => 'test-syllabi-collab@ubc.ca',
            'email_verified_at' => Carbon::now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        ]);

        DB::table('users')->insert([
            'name' => 'Test Syllabus Collab Leave',
            'email' => 'test-syllabi-collab-leave@ubc.ca',
            'email_verified_at' => Carbon::now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        ]);

        $user2 = User::where('email', 'test-syllabi-collab@ubc.ca')->first();
        $user3 = User::where('email', 'test-syllabi-collab-leave@ubc.ca')->first();

        $response = $this->actingAs($user)->post(route('syllabus.assign', $syllabus->id), [
            'syllabus_new_collabs' => [
                0 => 'test-syllabi-collab@ubc.ca', 1 => 'test-syllabi-collab-leave@ubc.ca',
            ],
            'syllabus_new_permissions' => [
                0 => 'edit', 1 => 'edit',
            ],
        ]);

        $this->assertDatabaseHas('syllabi_users', [
            'syllabus_id' => $syllabus->id,
            'user_id' => $user2->id,
        ]);

    }

    public function test_syllabus_transfer(): void
    {
        $user = User::where('email', 'test-syllabi@ubc.ca')->first();
        $syllabus = Syllabus::where('course_title', 'Intro to Greatness')->orderBy('id', 'DESC')->first();
        $user2 = User::where('email', 'test-syllabi-collab@ubc.ca')->first();

        $response = $this->actingAs($user)->post(route('syllabusUser.transferOwnership'), [
            'syllabus_id' => $syllabus->id,
            'oldOwnerId' => $user->id,
            'newOwnerId' => $user2->id,
        ]);

        $this->assertDatabaseHas('syllabi_users', [
            'syllabus_id' => $syllabus->id,
            'user_id' => $user->id,
            'permission' => 2,
        ]);

        $this->assertDatabaseHas('syllabi_users', [
            'syllabus_id' => $syllabus->id,
            'user_id' => $user2->id,
            'permission' => 1,
        ]);

    }

    public function test_syllabus_remove_collab(): void
    {
        $user = User::where('email', 'test-syllabi@ubc.ca')->first();
        $syllabus = Syllabus::where('course_title', 'Intro to Greatness')->orderBy('id', 'DESC')->first();
        $user2 = User::where('email', 'test-syllabi-collab@ubc.ca')->first();
        $syllabusUser = SyllabusUser::where('user_id', $user->id)->first();
        // $syllabusUser2 = SyllabusUser::where('user_id',$user->id)->first();

        $response = $this->actingAs($user2)->post(route('syllabus.assign', $syllabus->id), []);

        $this->assertDatabaseMissing('syllabi_users', [
            'syllabus_id' => $syllabus->id,
            'user_id' => $user->id,
        ]);
    }

    //commenting out unfinished tests
    /*
    public function test_syllabus_download()
    {
        $user = User::where('email', 'test-syllabi@ubc.ca')->first();
        $syllabus = Syllabus::where('course_title', 'Intro to Greatness')->orderBy('id', 'DESC')->first();
        $response = $this->actingAs($user)->get(route('syllabus.download', $syllabus->id, 'word'))->assertStatus(200);

    }

    public function test_syllabus_duplicate()
    {
        $user = User::where('email', 'test-syllabi@ubc.ca')->first();
        $syllabus = Syllabus::where('course_title', 'Intro to Greatness')->orderBy('id', 'DESC')->first();

        $response = $this->actingAs($user)->post(route('syllabus.duplicate', $syllabus->id), [
            '_method' => 'GET',
            'course_code' => 'CPSC',
            'course_num' => '111',
            'course_title' => 'Intro to Greatness - Copy',
            // "program_id" => $program->program_id
        ]);

        $this->assertDatabaseHas('syllabi', [
            'course_title' => 'Intro to Greatness - Copy',
        ]);

    }
    */

    public function test_syllabus_leave(): void
    {
        $user = User::where('email', 'test-syllabi@ubc.ca')->first();
        $syllabus = Syllabus::where('course_title', 'Intro to Greatness')->orderBy('id', 'DESC')->first();
        $user3 = User::where('email', 'test-syllabi-collab-leave@ubc.ca')->first();

        $response = $this->actingAs($user)->post(route('syllabusUser.leave'), [
            'syllabus_id' => $syllabus->id,
            'syllabusCollaboratorId' => $user3->id,
        ]);

        $this->assertDatabaseMissing('syllabi_users', [
            'user_id' => $user3->id,
        ]);

    }

    public function test_syllabus_delete(): void
    {
        $user = User::where('email', 'test-syllabi@ubc.ca')->first();
        $syllabus = Syllabus::where('course_title', 'Intro to Greatness')->orderBy('id', 'DESC')->first();

        $response = $this->actingAs($user)->delete(route('syllabus.delete', $syllabus->id));

        User::where('email', 'test-syllabi@ubc.ca')->delete();
        Syllabus::where('course_title', 'Intro to Greatness')->delete();
        User::where('email', 'test-syllabi-collab@ubc.ca')->delete();
        User::where('email', 'test-syllabi-collab-leave@ubc.ca')->delete();

        $this->assertDatabaseMissing('syllabi', [
            'id' => $syllabus->id,
        ]);

    }
}
