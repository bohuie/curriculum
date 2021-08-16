<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\MappingScale;
use App\Models\MappingScaleProgram;

class MappingScaleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        // Default map_scale_id set: 1- 3 
        $ms1 = new MappingScale;
        $ms1->map_scale_id = 1;
        $ms1->mapping_scale_categories_id = 1;
        $ms1->title = "Introduced";
        $ms1->abbreviation = "I";
        $ms1->description = "Key ideas, concepts or skills related to the learning outcome are demonstrated at an introductory level. Learning activities focus on basic knowledge, skills, and/or competencies and entry-level complexity.";
        $ms1->colour = "#80bdff" ;
        $ms1->save();

        $ms2 = new MappingScale;
        $ms2->map_scale_id = 2;
        $ms2->mapping_scale_categories_id = 1;
        $ms2->title = "Developing";
        $ms2->abbreviation = "D";
        $ms2->description = "Learning outcome is reinforced with feedback; students demonstrate the outcome at an increasing level of proficiency. Learning activities concentrate on enhancing and strengthening existing knowledge and skills as well as expanding complexity.";
        $ms2->colour = "#1aa7ff";
        $ms2->save();

        $ms3 = new MappingScale;
        $ms3->map_scale_id = 3;
        $ms3->mapping_scale_categories_id = 1;
        $ms3->title = "Advanced";
        $ms3->abbreviation = "A";
        $ms3->description = "Students demonstrate the learning outcomes with a high level of independence, expertise and sophistication expected upon graduation. Learning activities focus on and integrate the use of content or skills in multiple.";
        $ms3->colour = "#0065bd";
        $ms3->save();

        // New map_scale_id set: 4 - 7

        $ms4 = new MappingScale;
        $ms4->mapping_scale_categories_id = 2;
        $ms4->title = "Principle";
        $ms4->abbreviation = "P";
        $ms4->description = "Makes a significant contribution to the degree";
        $ms4->colour = "#80bdff";
        $ms4->save();

        $ms5 = new MappingScale;
        $ms5->mapping_scale_categories_id = 2;
        $ms5->title = "Secondary";
        $ms5->abbreviation = "S";
        $ms5->description = "Contributes less significantly towards the degree requirements";
        $ms5->colour = "#1aa7ff";
        $ms5->save();

        $ms6 = new MappingScale;
        $ms6->mapping_scale_categories_id = 2;
        $ms6->title = "Major Contributor";
        $ms6->abbreviation = "Ma";
        $ms6->description = "Major contribution towards the degree requirement";
        $ms6->colour = "#0065bd";
        $ms6->save();
        
        $ms7 = new MappingScale;
        $ms7->mapping_scale_categories_id = 2;
        $ms7->title = "Minor Contributor";
        $ms7->abbreviation = "Mi";
        $ms7->description = "Minor contribution towards the degree requirement";
        $ms7->colour = "#843976";
        $ms7->save();

        // New map_scale_id set: 8

        $ms8 = new MappingScale;
        $ms8->mapping_scale_categories_id = 3;
        $ms8->title = "Yes";
        $ms8->abbreviation = "Y";
        $ms8->description = "The course outcome is aligned with the program-level learning outcome.";
        $ms8->colour = "#80bdff";
        $ms8->save();

        // New map_scale_id set: 9 - 10

        $ms9 = new MappingScale;
        $ms9->mapping_scale_categories_id =4;
        $ms9->title = "Foundations";
        $ms9->abbreviation = "F";
        $ms9->description = "Foundational knowledge is emphasized, including information, discrete facts, concepts, or basic skills. There may or may not be evidence of learning from participants.";
        $ms9->colour = "#80bdff";
        $ms9->save();

        $ms10 = new MappingScale;
        $ms10->mapping_scale_categories_id = 4;
        $ms10->title = "Extensions";
        $ms10->abbreviation = "E";
        $ms10->description = "Learning goes beyond the foundational level to make connections between facts or ideas, relating knowledge to personal experience, understanding multiple perspectives, and/or analyzing information. Participants evidence their learning in one or more ways.";
        $ms10->colour = "#1aa7ff";
        $ms10->save();

    }
}