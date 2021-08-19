<?php

namespace Database\Seeders;

use App\Models\StandardsScaleCategory;
use Illuminate\Database\Seeder;

class StandardScaleCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sc = new StandardsScaleCategory;
        $sc->scale_category_id = 1;
        $sc->name = 'Default Scale Category';
        $sc->save();

        $sc2 = new StandardsScaleCategory;
        $sc2->scale_category_id = 2;
        $sc2->name = 'Secondary Scale Category';
        $sc2->save();
    }
}
