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
        
        VancouverSyllabusResource::create([
            'id_name' => 'concession',
            'title' => 'Academic Concession',
        ]);

        VancouverSyllabusResource::create([
            'id_name' => 'support',
            'title' => 'Student Health Resources',
        ]);

        VancouverSyllabusResource::create([
            'id_name' => 'harass',
            'title' => 'Harassment and Discrimination',
        ]);

        VancouverSyllabusResource::create([
            'id_name' => 'religious',
            'title' => 'Religious and Cultural Accommodation',
        ]);

        VancouverSyllabusResource::create([
            'id_name' => 'honesty',
            'title' => 'Academic Honesty and Standards',
        ]);
        
    }
}
