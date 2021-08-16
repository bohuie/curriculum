<?php

namespace Database\Seeders;

use App\Models\StandardScale;
use Illuminate\Database\Seeder;

class StandardScaleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    // Default Mapping Scales
    $ms1 = new StandardScale;
    $ms1->scale_category_id = 1;
    $ms1->title = "Introduced";
    $ms1->abbreviation = "I";
    $ms1->description = "Key ideas, concepts or skills related to the learning outcome are demonstrated at an introductory level. Learning activities focus on basic knowledge, skills, and/or competencies and entry-level complexity.";
    $ms1->colour = "#80bdff" ;
    $ms1->save();

    $ms2 = new StandardScale;
    $ms2->scale_category_id = 1;
    $ms2->title = "Developing";
    $ms2->abbreviation = "D";
    $ms2->description = "Learning outcome is reinforced with feedback; students demonstrate the outcome at an increasing level of proficiency. Learning activities concentrate on enhancing and strengthening existing knowledge and skills as well as expanding complexity.";
    $ms2->colour = "#1aa7ff";
    $ms2->save();

    $ms3 = new StandardScale;
    $ms3->scale_category_id = 1;
    $ms3->title = "Advanced";
    $ms3->abbreviation = "A";
    $ms3->description = "Students demonstrate the learning outcomes with a high level of independence, expertise and sophistication expected upon graduation. Learning activities focus on and integrate the use of content or skills in multiple.";
    $ms3->colour = "#0065bd";
    $ms3->save();

    // Secondary Set of Mapping Scales, This is for testing purposes only and should not be rolled out into production
    $ms4 = new StandardScale;
    $ms4->scale_category_id = 2;
    $ms4->title = "Principle";
    $ms4->abbreviation = "P";
    $ms4->description = "Makes a significant contribution to the degree";
    $ms4->colour = "#80bdff";
    $ms4->save();

    $ms5 = new StandardScale;
    $ms5->scale_category_id = 2;
    $ms5->title = "Secondary";
    $ms5->abbreviation = "S";
    $ms5->description = "Contributes less significantly towards the degree requirements";
    $ms5->colour = "#1aa7ff";
    $ms5->save();

    $ms6 = new StandardScale;
    $ms6->scale_category_id = 2;
    $ms6->title = "Major Contributor";
    $ms6->abbreviation = "Ma";
    $ms6->description = "Major contribution towards the degree requirement";
    $ms6->colour = "#0065bd";
    $ms6->save();

    $ms7 = new StandardScale;
    $ms7->scale_category_id = 2;
    $ms7->title = "Minor Contributor";
    $ms7->abbreviation = "Mi";
    $ms7->description = "Minor contribution towards the degree requirement";
    $ms7->colour = "#843976";
    $ms7->save();
    }
}
