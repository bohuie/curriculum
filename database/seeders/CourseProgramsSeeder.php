<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Program;
use App\Models\CourseProgram;
use Illuminate\Database\Seeder;


class CourseProgramsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Course 1
        $course = new Course;
        $course->program_id = 1;
        $course->course_title = 'Course Programs test';
        $course->course_num = '101';
        $course->course_code = 'TEST';
        $course->status = -1;
        $course->required = 1;
        $course->type = 'unassigned';
        $course->assigned = -1;
        $course->delivery_modality = 'O';
        $course->year = 2021;
        $course->semester = 'W1';
        $course->save();

        $course_id = $course->course_id;

        // Program 1
        $program = new Program;
        $program->program = 'program';
        $program->level = 'level';
        $program->department = null;
        $program->faculty = 'faculty';
        $program->status = -1;
        $program->save();

        $program_id = $program->program_id;

        // Program 2
        $program2 = new Program;
        $program2->program = 'program 2';
        $program2->level = 'level 2';
        $program2->department = null;
        $program2->faculty = 'faculty 2';
        $program2->status = -1;
        $program2->save();

        $program_id2 = $program2->program_id;

        // Store course into multiple programs
        $cp = new CourseProgram;
        $cp->course_id = $course_id;
        $cp->program_id = $program_id;
        $cp->save();

        $cp2 = new CourseProgram;
        $cp2->course_id = $course_id;
        $cp2->program_id = $program_id2;
        $cp2->save();
        
    }
}
