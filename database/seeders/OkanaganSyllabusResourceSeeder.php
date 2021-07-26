<?php

namespace Database\Seeders;

use App\Models\syllabus\OkanaganSyllabusResource;
use Illuminate\Database\Seeder;

class OkanaganSyllabusResourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        OkanaganSyllabusResource::create([
            'id_name' => 'land',
            'title' => 'Land Acknowledgement',
            // 'description' => 'We respectfully acknowledge the Syilx Okanagan Nation and their peoples, in whose traditional, ancestral, unceded territory UBC Okanagan is situated.'
        ]);

        OkanaganSyllabusResource::create([
            'id_name' => 'academic',
            'title' => 'Academic Integrity',
            // 'description' => 'The academic enterprise is founded on honesty, civility, and integrity.  As members of this enterprise, all students are expected to know, understand, and follow the codes of conduct regarding academic integrity.  At the most basic level, this means submitting only original work done by you and acknowledging all sources of information or ideas and attributing them to others as required.  This also means you should not cheat, copy, or mislead others about what is your work.  Violations of academic integrity (i.e., misconduct) lead to the breakdown of the academic enterprise, and therefore serious consequences arise and harsh sanctions are imposed.  For example, incidences of plagiarism or cheating usually result in a failing grade or mark of zero on the assignment or in the course.  Careful records are kept to monitor and prevent recidivism.
            // A more detailed description of academic integrity, including the University’s policies and procedures, may be found in the Academic Calendar at: http://www.calendar.ubc.ca/okanagan/index.cfm?tree=3,54,111,0'
        ]);

        OkanaganSyllabusResource::create([
            'id_name' => 'finals',
            'title' => 'Final Examinations',
            // 'description' => 'The examination period for Term X of Fall 201X is XXXX.  Except in the case of examination clashes and hardships (three or more formal examinations scheduled within a 24-hour period) or unforeseen events, students will be permitted to apply for out-of-time final examinations only if they are representing the University, the province, or the country in a competition or performance; serving in the Canadian military; observing a religious rite; working to support themselves or their family; or caring for a family member.  Unforeseen events include (but may not be limited to) the following: ill health or other personal challenges that arise during a term and changes in the requirements of an ongoing job.  
            // Further information on Academic Concession can be found under Policies and Regulation in the Okanagan Academic Calendar http://www.calendar.ubc.ca/okanagan/index.cfm?tree=3,48,0,0
            // '
        ]);

        OkanaganSyllabusResource::create([
            'id_name' => 'grading',
            'title' => 'Grading Practices',
            // 'description' => "Faculties, departments, and schools reserve the right to scale grades in order to maintain equity among sections and conformity to University, faculty, department, or school norms. Students should therefore note that an unofficial grade given by an instructor might be changed by the faculty, department, or school. Grades are not official until they appear on a student's academic record.
            // http://www.calendar.ubc.ca/okanagan/index.cfm?tree=3,41,90,1014
            // "
        ]);

        OkanaganSyllabusResource::create([
            'id_name' => 'health',
            'title' => 'Health and Wellness',
            // 'description' => 'At UBC Okanagan health services to students are provided by Health and Wellness.  Nurses, physicians and counsellors provide health care and counselling related to physical health, emotional/mental health and sexual/reproductive health concerns. As well, health promotion, education and research activities are provided to the campus community.  If you require assistance with your health, please contact Health and Wellness for more information or to book an appointment.

            // UNC 337 	250.807.9270
            // email: healthwellness.okanagan@ubc.ca
            // Web: www.students.ok.ubc.ca/health-wellness
            // ',
        ]);

        OkanaganSyllabusResource::create([
            'id_name' => 'safewalk',
            'title' => 'Safewalk',
            // 'description' => "Don't want to walk alone at night?  Not too sure how to get somewhere on campus?  Call Safewalk at 250-807-8076. 
            // For more information, see: www.security.ok.ubc.ca
            // ",
        ]);

        OkanaganSyllabusResource::create([
            'id_name' => 'student',
            'title' => 'Student Learning Hub',
            // 'description' => 'The Student Learning Hub (LIB 237) is your go-to resource for free math, science, writing, and language learning support. The Hub welcomes undergraduate students from all disciplines and year levels to access a range of supports that include tutoring in math, sciences, languages, and writing, as well as help with study skills and learning strategies. For more information, please visit the Hub’s website (https://students.ok.ubc.ca/student-learning-hub/) or call 250-807-9185.',
        ]);

        OkanaganSyllabusResource::create([
            'id_name' => 'disability',
            'title' => 'UBC Okanagan Disability Resource Centre',
            // 'description' => 'The Disability Resource Centre ensures educational equity for students with disabilities and chronic medical conditions. If you are disabled, have an injury or illness and require academic accommodations to meet the course objectives, please contact Earllene Roberts, the Manager for the Disability Resource Centre located in the University Centre building (UNC 214).

            // UNC 214 	250.807.9263
            // email: earllene.roberts@ubc.ca 
            // Web: www.students.ok.ubc.ca/drc  
            // ',
        ]);

        OkanaganSyllabusResource::create([
            'id_name' => 'equity',
            'title' => 'UBC Okanagan Equity and Inclusion Office',
            // 'description' => 'Through leadership, vision, and collaborative action, the Equity & Inclusion Office (EIO) develops action strategies in support of efforts to embed equity and inclusion in the daily operations across the campus. The EIO provides education and training from cultivating respectful, inclusive spaces and communities to understanding unconscious/implicit bias and its operation within in campus environments. UBC Policy 3 prohibits discrimination and harassment on the basis of BC’s Human Rights Code. If you require assistance related to an issue of equity, educational programs, discrimination or harassment please contact the EIO.

            // UNC 216 	250.807.9291
            // email: equity.ubco@ubc.ca
            // Web: www.equity.ok.ubc.ca 
            // ',
        ]);

        OkanaganSyllabusResource::create([
            'id_name' => 'copyright',
            'title' => '© Copyright Statement',
            // 'description' => 'All materials of this course (course handouts, lecture slides, assessments, course readings, etc.) are the intellectual property of the Course Instructor or licensed to be used in this course by the copyright owner. Redistribution of these materials by any means without permission of the copyright holder(s) constitutes a breach of copyright and may lead to academic discipline.',
        ]);

    }
}
