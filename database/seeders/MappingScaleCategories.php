<?php

namespace Database\Seeders;

use App\Models\MappingScaleCategory;
use Illuminate\Database\Seeder;

class MappingScaleCategories extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $mc = new MappingScaleCategory;
        $mc->mapping_scale_categories_id = 1;
        $mc->msc_title = 'Example 1';
        $mc->description = 'This is a standard scale that has been useful for a variety of different programs.';
        $mc->save();

        $mc2 = new MappingScaleCategory;
        $mc2->mapping_scale_categories_id = 2;
        $mc2->msc_title = 'Example 2';
        $mc2->description = 'This scale might be useful for programs that focus on skills demonstration.';
        $mc2->save();

        $mc3 = new MappingScaleCategory;
        $mc3->mapping_scale_categories_id = 3;
        $mc3->msc_title = 'Example 3';
        $mc3->description = 'Use a one-point scale to indicate where an alignment exists but the level is not specified, such as in curriculum development.';
        $mc3->save();

        $mc4 = new MappingScaleCategory;
        $mc4->mapping_scale_categories_id = 4;
        $mc4->msc_title = 'Example 4';
        $mc4->description = 'Consider using a 2-point scale such as this one for non-credit learning opportunities.';
        $mc4->save();
    }
}
