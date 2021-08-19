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
            'title' => 'Acknowledgement',
        ]);

        VancouverSyllabusResource::create([
            'id_name' => 'academic',
            'title' => 'Academic Integrity Statement',
        ]);

        VancouverSyllabusResource::create([
            'id_name' => 'disability',
            'title' => 'Accomodations for students with disabilities',
        ]);

        VancouverSyllabusResource::create([
            'id_name' => 'copyright',
            'title' => '© Copyright Statement',
        ]);

        
    }
}
