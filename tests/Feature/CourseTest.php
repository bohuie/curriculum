<?php

namespace Tests\Feature;

use App\Models\AssessmentMethod;
use App\Models\Course;
use App\Models\CourseProgram;
use App\Models\LearningActivity;
use App\Models\LearningOutcome;
use App\Models\MappingScaleProgram;
use App\Models\Program;
use App\Models\ProgramLearningOutcome;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class CourseTest extends TestCase
{
    public function test_storing_new_course(): void
    {

        $delivery_modalities = ['O', 'B', 'I'];
        $semesters = ['W1', 'W2', 'S1', 'S2'];

        //$user = User::factory()->count(1)->make();
        //$user = User::first();

        //Create Verified User
        DB::table('users')->insert([
            'name' => 'Test Course',
            'email' => 'test-course@ubc.ca',
            'email_verified_at' => Carbon::now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        ]);

        $user = User::where('email', 'test-course@ubc.ca')->first();

        $response = $this->actingAs($user)->post(route('courses.store'), [
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
            'user_id' => $user->id,
        ]);

        $course = Course::where('course_title', 'Intro to Unit Testing')->orderBy('course_id', 'DESC')->first();

        $response->assertRedirect('/courseWizard/'.($course->course_id).'/step1');

        $this->assertDatabaseHas('courses', [
            'course_title' => 'Intro to Unit Testing',
        ]);

    }

    public function test_download_pdf(): void
    {

        $user = User::where('email', 'test-course@ubc.ca')->first();
        $course = Course::where('course_title', 'Intro to Unit Testing')->orderBy('course_id', 'DESC')->first();
        $response = $this->actingAs($user)->get(route('courses.pdf', $course->course_id))->assertStatus(200);
    }

    public function test_duplicate_course(): void
    {

        $user = User::where('email', 'test-course@ubc.ca')->first();
        $course = Course::where('course_title', 'Intro to Unit Testing')->orderBy('course_id', 'DESC')->first();

        $response = $this->actingAs($user)->post(route('courses.duplicate', $course->course_id), [
            'course_code' => 'TEST',
            'course_num' => '111',
            'course_title' => 'Intro to Unit Testing - Copy',
        ]);

        $this->assertDatabaseHas('courses', [
            'course_title' => 'Intro to Unit Testing - Copy',
        ]);

    }

    public function test_create_clo(): void
    {
        $user = User::where('email', 'test-course@ubc.ca')->first();
        $course = Course::where('course_title', 'Intro to Unit Testing')->orderBy('course_id', 'DESC')->first();

        //LearningOutcomeController@store

        $response = $this->actingAs($user)->post(route('courses.outcomes.store'), [
            'current_l_outcome' => [

            ],
            'current_l_outcome_short_phrase' => [

            ],
            'new_l_outcomes' => [
                0 => 'Test Course Learning Outcome 1',
                1 => 'Test Course Learning Outcome 2',
            ],
            'new_short_phrases' => [
                0 => 'Test CLO Short 1',
                1 => 'Test CLO Short 2',
            ],
            'course_id' => $course->course_id,
        ]);

        $this->assertDatabaseHas('learning_outcomes', [
            'l_outcome' => 'Test Course Learning Outcome 1',
            'clo_shortphrase' => 'Test CLO Short 1',
        ]);

        $this->assertDatabaseHas('learning_outcomes', [
            'l_outcome' => 'Test Course Learning Outcome 2',
            'clo_shortphrase' => 'Test CLO Short 2',
        ]);
    }

    public function test_create_la(): void
    {
        $user = User::where('email', 'test-course@ubc.ca')->first();
        $course = Course::where('course_title', 'Intro to Unit Testing')->orderBy('course_id', 'DESC')->first();

        //LearningOutcomeController@store

        $response = $this->actingAs($user)->post(route('la.store'), [
            'current_l_activities' => [

            ],

            'new_l_activities' => [
                1 => 'Group discussion',
                2 => 'Issue-based inquiry',
                3 => 'Guest Speaker',
            ],
            'course_id' => $course->course_id,
        ]);

        $this->assertDatabaseHas('learning_activities', [
            'l_activity' => 'Group discussion',
            'course_id' => $course->course_id,
        ]);

        $this->assertDatabaseHas('learning_activities', [
            'l_activity' => 'Issue-based inquiry',
            'course_id' => $course->course_id,
        ]);

        $this->assertDatabaseHas('learning_activities', [
            'l_activity' => 'Guest Speaker',
            'course_id' => $course->course_id,
        ]);
    }

    public function test_create_am(): void
    {
        $user = User::where('email', 'test-course@ubc.ca')->first();
        $course = Course::where('course_title', 'Intro to Unit Testing')->orderBy('course_id', 'DESC')->first();

        //LearningOutcomeController@store

        $response = $this->actingAs($user)->post(route('am.store'), [
            'current_a_methods' => [

            ],
            'current_weights' => [

            ],
            'new_a_methods' => [
                1 => 'Assignment',
                2 => 'Attendance',
                3 => 'Debate',
            ],
            'new_weights' => [
                1 => '50',
                2 => '40',
                3 => '10',
            ],
            'course_id' => $course->course_id,
        ]);

        $this->assertDatabaseHas('assessment_methods', [
            'a_method' => 'Assignment',
            'course_id' => $course->course_id,
            'weight' => 50,
        ]);

        $this->assertDatabaseHas('assessment_methods', [
            'a_method' => 'Attendance',
            'course_id' => $course->course_id,
            'weight' => 40,
        ]);

        $this->assertDatabaseHas('assessment_methods', [
            'a_method' => 'Debate',
            'course_id' => $course->course_id,
            'weight' => 10,
        ]);

    }

    public function test_course_alignment(): void
    {
        $user = User::where('email', 'test-course@ubc.ca')->first();
        $course = Course::where('course_title', 'Intro to Unit Testing')->orderBy('course_id', 'DESC')->first();
        $learningActivities = LearningActivity::where('course_id', $course->course_id)->get();
        $assessmentMethods = AssessmentMethod::where('course_id', $course->course_id)->get();
        $learningOutcome1 = LearningOutcome::where('l_outcome', 'Test Course Learning Outcome 1')->first();
        $learningOutcome2 = LearningOutcome::where('l_outcome', 'Test Course Learning Outcome 2')->first();

        $response = $this->actingAs($user)->post(route('courses.outcomeDetails', $course->course_id), [
            'a_methods' => [
                $learningOutcome1->l_outcome_id => [
                    0 => $assessmentMethods[0]->a_method_id,
                    //This is mapping CLO #1 to the first assessment method
                ],
                $learningOutcome2->l_outcome_id => [
                    0 => $assessmentMethods[0]->a_method_id,
                    1 => $assessmentMethods[1]->a_method_id,
                    //This is mapping CLO #2 to the first and second assessment method
                ],
            ],
            'l_activities' => [
                $learningOutcome1->l_outcome_id => [
                    0 => $learningActivities[0]->l_activity_id,
                    //This is mapping only CLO #1 to the first Learning Activity
                ],
            ],
            'l_outcomes_pos' => [
                0 => $learningOutcome1->l_outcome_id,
                1 => $learningOutcome2->l_outcome_id,
            ],
        ]);

        $this->assertDatabaseHas('outcome_assessments', [
            'l_outcome_id' => $learningOutcome1->l_outcome_id,
            'a_method_id' => $assessmentMethods[0]->a_method_id,
        ]);

    }

    public function test_reorder_am(): void
    {
        $user = User::where('email', 'test-course@ubc.ca')->first();
        $course = Course::where('course_title', 'Intro to Unit Testing')->orderBy('course_id', 'DESC')->first();
        $assessmentMethods = AssessmentMethod::where('course_id', $course->course_id)->get();

        $response = $this->actingAs($user)->post(route('courses.loReorder', $course->course_id), [
            'a_method_pos' => [
                0 => $assessmentMethods[1]->a_method_id,
                1 => $assessmentMethods[0]->a_method_id,
                2 => $assessmentMethods[2]->a_method_id,
            ],
        ]);

        $this->assertDatabaseHas('assessment_methods', [
            'a_method' => $assessmentMethods[1]->a_method,
            'pos_in_alignment' => 0,
            'course_id' => $course->course_id,
        ]);
    }

    public function test_reorder_la(): void
    {
        $user = User::where('email', 'test-course@ubc.ca')->first();
        $course = Course::where('course_title', 'Intro to Unit Testing')->orderBy('course_id', 'DESC')->first();
        $learningActivities = LearningActivity::where('course_id', $course->course_id)->get();

        $response = $this->actingAs($user)->post(route('courses.loReorder', $course->course_id), [
            'l_outcome_pos' => [
                0 => $learningActivities[1]->l_outcome_id,
                1 => $learningActivities[0]->l_outcome_id,
                2 => $learningActivities[2]->l_outcome_id,
            ],
        ]);

        $this->assertDatabaseHas('learning_activities', [
            'l_activity' => $learningActivities[1]->l_activity,
            'l_activities_pos' => 0,
            'course_id' => $course->course_id,
        ]);
    }

    public function test_reorder_clo(): void
    {
        $user = User::where('email', 'test-course@ubc.ca')->first();
        $course = Course::where('course_title', 'Intro to Unit Testing')->orderBy('course_id', 'DESC')->first();
        $clo1 = LearningOutcome::where('l_outcome', 'Test Course Learning Outcome 1')->first();
        $clo2 = LearningOutcome::where('l_outcome', 'Test Course Learning Outcome 2')->first();

        $response = $this->actingAs($user)->post(route('courses.loReorder', $course->course_id), [
            'l_outcome_pos' => [
                0 => $clo2->l_outcome_id,
                1 => $clo1->l_outcome_id,
            ],
        ]);

        $this->assertDatabaseHas('learning_outcomes', [
            'l_outcome' => 'Test Course Learning Outcome 2',
            'pos_in_alignment' => 0,
        ]);
    }

    public function test_program_outcome_mapping(): void
    {
        $user = User::where('email', 'test-course@ubc.ca')->first();
        $course = Course::where('course_title', 'Intro to Unit Testing')->orderBy('course_id', 'DESC')->first();
        $clo = LearningOutcome::where('l_outcome', 'Test Course Learning Outcome 2')->first();

        //create test program
        DB::table('programs')->insert([
            'program' => 'Testing Program for Courses',
            'faculty' => 'Irving K. Barber Faculty of Science',
            'level' => 'Bachelors',
            'campus' => 'O',
            'status' => -1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

        ]);

        $program = Program::where('program', 'Testing Program for Courses')->first();

        //must set mapping scale (we will use 3 point scale I/D/A)
        DB::table('mapping_scale_programs')->insert([
            'map_scale_id' => 1,
            'program_id' => $program->program_id,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('mapping_scale_programs')->insert([
            'map_scale_id' => 2,
            'program_id' => $program->program_id,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('mapping_scale_programs')->insert([
            'map_scale_id' => 3,
            'program_id' => $program->program_id,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        //create test PLOs
        DB::table('program_learning_outcomes')->insert([
            'plo_shortphrase' => 'Short PLO 1',
            'pl_outcome' => 'Course Testing PLO 1',
            'program_id' => $program->program_id,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

        ]);

        $plo = ProgramLearningOutcome::where('pl_outcome', 'Course Testing PLO 1')->first();
        //must add course to program
        DB::table('course_programs')->insert([
            'course_id' => $course->course_id,
            'program_id' => $program->program_id,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('courses')->where('course_id', $course->course_id)->update(['program_id' => $program->program_id]);
        //test mapping CLOs to PLOs

        $response = $this->actingAs($user)->post(route('outcomeMap.store'), [
            'course_id' => $course->course_id,
            'l_outcome_id' => $clo->l_outcome_id,
            'map' => [
                $clo->l_outcome_id => [
                    $plo->pl_outcome_id => 1, //this is setting the mapping scale option to I, since that is the map_scale_id (0=N/A,1=I,2=D,3=A)
                ],
            ],
        ]);

        $this->assertDatabaseHas('outcome_maps', [
            'l_outcome_id' => $clo->l_outcome_id,
            'pl_outcome_id' => $plo->pl_outcome_id,
            'map_scale_id' => 1,
        ]);

    }

    public function test_optional_priorities_store(): void
    {
        $user = User::where('email', 'test-course@ubc.ca')->first();
        $course = Course::where('course_title', 'Intro to Unit Testing')->orderBy('course_id', 'DESC')->first();

        $response = $this->actingAs($user)->post(route('storeOptionalPLOs'), [
            'course_id' => $course->course_id,
            'optionalItem' => [
                0 => '70',
                //Assigning Optional Priority with ID=70 to the course
            ],
        ]);

        $this->assertDatabaseHas('course_optional_priorities', [
            'op_id' => 70,
            'course_id' => $course->course_id,
        ]);

    }

    public function test_delete_clo(): void
    {
        $user = User::where('email', 'test-course@ubc.ca')->first();
        $course = Course::where('course_title', 'Intro to Unit Testing')->orderBy('course_id', 'DESC')->first();
        $clo = LearningOutcome::where('l_outcome', 'Test Course Learning Outcome 1')->first();

        $response = $this->actingAs($user)->post(route('courses.outcomes.store'), [
            'current_l_outcome' => [
                0 => 'Test Course Learning Outcome 2',
            ],
            'current_l_outcome_short_phrase' => [
                0 => 'Test CLO Short 2',
            ],
            'new_l_outcomes' => [

            ],
            'new_short_phrases' => [

            ],
            'course_id' => $course->course_id,
        ]);

        $this->assertDatabaseMissing('learning_outcomes', [
            'l_outcome' => 'Test Course Learning Outcome 1',
            'clo_shortphrase' => 'Test CLO Short 1',
        ]);
    }

    public function test_delete_am(): void
    {

        $user = User::where('email', 'test-course@ubc.ca')->first();
        $course = Course::where('course_title', 'Intro to Unit Testing')->orderBy('course_id', 'DESC')->first();

        //LearningOutcomeController@store

        $response = $this->actingAs($user)->post(route('am.store'), [
            'current_a_methods' => [

            ],

            'current_weights' => [

            ],

            'new_a_methods' => [

            ],
            'course_id' => $course->course_id,
        ]);

        $this->assertDatabaseMissing('assessment_methods', [
            'course_id' => $course->course_id,
        ]);

    }

    public function test_delete_la(): void
    {

        $user = User::where('email', 'test-course@ubc.ca')->first();
        $course = Course::where('course_title', 'Intro to Unit Testing')->orderBy('course_id', 'DESC')->first();

        //LearningOutcomeController@store

        $response = $this->actingAs($user)->post(route('la.store'), [
            'current_l_activities' => [

            ],

            'new_l_activities' => [

            ],
            'course_id' => $course->course_id,
        ]);

        $this->assertDatabaseMissing('learning_activities', [
            'course_id' => $course->course_id,
        ]);

    }

    public function test_standardsOutcomeMap_store(): void
    {
        $user = User::where('email', 'test-course@ubc.ca')->first();
        $course = Course::where('course_title', 'Intro to Unit Testing')->orderBy('course_id', 'DESC')->first();

        //setting this mapping to all "Introduced" for this course, except 1
        $response = $this->actingAs($user)->post(route('standardsOutcomeMap.store'), [
            'course_id' => $course->course_id,
            'map' => [
                $course->course_id => [
                    1 => '101',
                    2 => '101',
                    3 => '101',
                    4 => '101',
                    5 => '101',
                    6 => '101',
                ],
            ],
        ]);

        $this->assertDatabaseHas('standards_outcome_maps', [
            'course_id' => $course->course_id,
            'standard_scale_id' => '101',
            'standard_id' => 6,
        ]);

        //this is failing when it should be working
        //cannot get out of for loop in StandardOutcomeMapController

    }

    public function test_adding_collaborator(): void
    {
        $user = User::where('email', 'test-course@ubc.ca')->first();
        $course = Course::where('course_title', 'Intro to Unit Testing')->orderBy('course_id', 'DESC')->first();

        //Create Verified User for Course Collaboration Testing
        DB::table('users')->insert([
            'name' => 'Test Course Collab',
            'email' => 'test-course-collab@ubc.ca',
            'email_verified_at' => Carbon::now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        ]);

        $user2 = User::where('email', 'test-course-collab@ubc.ca')->first();

        $response = $this->actingAs($user)->post(route('courses.assign', $course->course_id), [
            'course_new_collabs' => [0 => 'test-course-collab@ubc.ca'],
            'course_new_permissions' => [0 => 'edit'],
        ]);

        $this->assertDatabaseHas('course_users', [
            'course_id' => $course->course_id,
            'user_id' => $user2->id,
        ]);

    }

    public function test_transferring_course(): void
    {

        $user = User::where('email', 'test-course@ubc.ca')->first();
        $course = Course::where('course_title', 'Intro to Unit Testing')->orderBy('course_id', 'DESC')->first();
        $user2 = User::where('email', 'test-course-collab@ubc.ca')->first();

        $response = $this->actingAs($user)->post(route('courseUser.transferOwnership'), [
            'course_id' => $course->course_id,
            'oldOwnerId' => $user->id,
            'newOwnerId' => $user2->id,
        ]);

        $this->assertDatabaseHas('course_users', [
            'course_id' => $course->course_id,
            'user_id' => $user->id,
            'permission' => 2,
        ]);

        $this->assertDatabaseHas('course_users', [
            'course_id' => $course->course_id,
            'user_id' => $user2->id,
            'permission' => 1,
        ]);
    }

    public function test_removing_collaborator(): void
    {

        $user = User::where('email', 'test-course@ubc.ca')->first();
        $course = Course::where('course_title', 'Intro to Unit Testing')->orderBy('course_id', 'DESC')->first();
        $user2 = User::where('email', 'test-course-collab@ubc.ca')->first();

        //courses.unassign is an unused route, rather within CourseUserController.php in the store() method
        // "$this->destroy($savedCourseUser);" is called when the new list of users is shorter than the current
        //Therefore, we just use the same path courses.assign and pass an empty array

        $response = $this->actingAs($user2)->post(route('courses.assign', $course->course_id), []);

        $this->assertDatabaseMissing('course_users', [
            'course_id' => $course->course_id,
            'user_id' => $user->id,
        ]);
    }

    public function test_deleting_course(): void
    {

        $user2 = User::where('email', 'test-course-collab@ubc.ca')->first();
        $course = Course::where('course_title', 'Intro to Unit Testing')->orderBy('course_id', 'DESC')->first();
        $program = Program::where('program', 'Testing Program for Courses')->first();

        $response = $this->actingAs($user2)->delete(route('courses.destroy', $course->course_id));

        $this->assertDatabaseMissing('courses', [
            'course_id' => $course->course_id,
        ]);

        //Delete Test User(s)
        //We are testing Course and CourseUser routes here, so deleting manually is fine to clean up.
        User::where('email', 'test-course-collab@ubc.ca')->delete();
        User::where('email', 'test-course@ubc.ca')->delete();
        //Delete Duplicate Course
        Course::where('course_title', 'Intro to Unit Testing')->delete();

        //Will also delete Programs to cleanup
        CourseProgram::where('program_id', $program->program_id)->delete();
        Program::where('program_id', $program->program_id)->delete();
        MappingScaleProgram::where('program_id', $program->program_id)->delete();

        $this->assertDatabaseMissing('users', [
            'email' => 'test-course-collab@ubc.ca',
            'email' => 'test-course@ubc.ca',
        ]);
    }
}
