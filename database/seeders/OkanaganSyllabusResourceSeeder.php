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
    public function run(): void
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
            'title' => 'Okanagan Disability Resource Centre',
        ]);

        OkanaganSyllabusResource::create([
            'id_name' => 'equity',
            'title' => 'Equity and Inclusion Office',
        ]);

        OkanaganSyllabusResource::create([
            'id_name' => 'health',
            'title' => 'Wellbeing and Accessibility Services (WAS)',
        ]);

        OkanaganSyllabusResource::create([
            'id_name' => 'student',
            'title' => 'Student Learning Hub',
        ]);

        OkanaganSyllabusResource::create([
            'id_name' => 'copyright',
            'title' => '© Copyright Statement',
        ]);

        OkanaganSyllabusResource::create([
            'id_name' => 'safewalk',
            'title' => 'Safewalk',
        ]);

        OkanaganSyllabusResource::create([
            'id_name' => 'ombud',
            'title' => 'Office of the Ombudperson',
        ]);

        OkanaganSyllabusResource::create([
            'id_name' => 'svpro',
            'title' => 'Sexual Violence Prevention and Response Office (SVPRO)',
        ]);

        OkanaganSyllabusResource::create([
            'id_name' => 'wellbeing',
            'title' => 'Walk-In Well-Being Clinic',
        ]);

    }
}
