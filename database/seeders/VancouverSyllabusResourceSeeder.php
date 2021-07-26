<?php

namespace Database\Seeders;

use App\Models\syllabus\VancouverSyllabusResource;
use Illuminate\Database\Seeder;

class VancouverSyllabusResourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        VancouverSyllabusResource::create([
            'id_name' => 'land',
            'title' => 'Land Acknowledgement',
            // 'description' => 'We acknowledge that the UBC Vancouver campus is situated within the traditional, ancestral and unceded territory of the Musqueam.'
        ]);

        VancouverSyllabusResource::create([
            'id_name' => 'academic',
            'title' => 'Academic Integrity Statement',
            // 'description' => 'The academic enterprise is founded on honesty, civility, and integrity. As members of this enterprise, all students are expected to know, understand, and follow the codes of conduct regarding academic integrity. At the most basic level, this means submitting only original work done by you and acknowledging all sources of information or ideas and attributing them to others as required. This also means you should not cheat, copy, or mislead others about what is your work. Violations of academic integrity (i.e., misconduct) lead to the breakdown of the academic enterprise, and therefore serious consequences arise and harsh sanctions are imposed. For example, incidences of plagiarism or cheating may result in a mark of zero on the assignment or exam and more serious consequences may apply if the matter is referred to the President’s Advisory Committee on Student Discipline. Careful records are kept in order to monitor and prevent recurrences. A more detailed description of academic integrity, including the University’s policies and procedures, may be found in the Academic Calendar.'
        ]);

        VancouverSyllabusResource::create([
            'id_name' => 'disability',
            'title' => 'Accomodations for students with disabilities',
            // 'description' => 'https://students.ubc.ca/about-student-services/centre-for-accessibility'
        ]);

        VancouverSyllabusResource::create([
            'id_name' => 'copyright',
            'title' => '© Copyright Statement',
            // 'description' => 'All materials of this course (course handouts, lecture slides, assessments, course readings, etc.) are the intellectual property of the Course Instructor or licensed to be used in this course by the copyright owner. Redistribution of these materials by any means without permission of the copyright holder(s) constitutes a breach of copyright and may lead to academic discipline.'
        ]);

        
    }
}
