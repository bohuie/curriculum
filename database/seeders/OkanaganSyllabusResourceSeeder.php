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
        ]);

        OkanaganSyllabusResource::create([
            'id_name' => 'academic',
            'title' => 'Academic Integrity',
        ]);

        OkanaganSyllabusResource::create([
            'id_name' => 'finals',
            'title' => 'Final Examinations',
        ]);

        OkanaganSyllabusResource::create([
            'id_name' => 'grading',
            'title' => 'Grading Practices',
        ]);

        OkanaganSyllabusResource::create([
            'id_name' => 'disability',
            'title' => 'UBC Okanagan Disability Resource Centre',
        ]);

        OkanaganSyllabusResource::create([
            'id_name' => 'equity',
            'title' => 'UBC Okanagan Equity and Inclusion Office',
        ]);

        OkanaganSyllabusResource::create([
            'id_name' => 'health',
            'title' => 'Health and Wellness',
        ]);

        OkanaganSyllabusResource::create([
            'id_name' => 'student',
            'title' => 'Student Learning Hub',
        ]);

        OkanaganSyllabusResource::create([
            'id_name' => 'global',
            'title' => 'The Global Engagement Office'
        ]);

        OkanaganSyllabusResource::create([
            'id_name' => 'copyright',
            'title' => '© Copyright Statement',
        ]);

        OkanaganSyllabusResource::create([
            'id_name' => 'safewalk',
            'title' => 'Safewalk',
        ]);

    }
}
