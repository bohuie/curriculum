<?php

namespace Database\Seeders;

use App\Models\StandardCategory;
use Illuminate\Database\Seeder;

class StandardCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $msc = new StandardCategory;
        $msc->standard_category_id = 1;
        $msc->sc_name = "Bachelor's degree level standards";
        $msc->save();

        $msc2 = new StandardCategory;
        $msc2->standard_category_id = 2;
        $msc2->sc_name = "Master's degree level standards";
        $msc2->save();

        $msc3 = new StandardCategory;
        $msc3->standard_category_id = 3;
        $msc3->sc_name = "Doctoral degree level standards";
        $msc3->save();
    }
}
