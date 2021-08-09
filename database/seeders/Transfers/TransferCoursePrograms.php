<?php

namespace Database\Seeders;

use App\Models\CourseProgram;
use App\Models\Course;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TransferCoursePrograms extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $courses = DB::table('courses')->get();
        foreach ($courses as $index => $course) {
            if ($course->program_id == 1 || $course->program_id == 2 || $course->program_id == 3) {
                $course = Course::find($course->course_id);
                $course->standard_category_id = $course->program_id;
                $course->save();
            } else {
                CourseProgram::create([
                    'course_id' => $course->course_id,
                    'program_id' => $course->program_id,       
                ]);
            }
        }
    }
}
