<?php

namespace Database\Seeders;

use App\Models\syllabus\OkanaganSyllabusResource;
use Illuminate\Database\Seeder;

class OkanaganSyllabusResourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
    


        OkanaganSyllabusResource::create([
            'id_name' => 'safewalk',
            'title' => 'Safewalk',
        ]);

        OkanaganSyllabusResource::create([
            'id_name' => 'academic',
            'title' => 'Academic Integrity',
        ]);

        OkanaganSyllabusResource::create([
            'id_name' => 'misconduct',
            'title' => 'Academic Misconduct',
        ]);
        

        OkanaganSyllabusResource::create([
            'id_name' => 'genAI',
            'title' => 'Statement to permit use of generative artificial intelligence in the course (Gen AI)',
        ]);

        OkanaganSyllabusResource::create([
            'id_name' => 'genAIprohibit',
            'title' => 'Statement to prohibit the use of generative artificial intelligence in the course (Gen AI)',
        ]);

    }
}
