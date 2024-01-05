<?php

namespace Database\Seeders;

use App\Models\StandardsOutcomeMap;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TransferStandardScaleValue extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $standardOutcomeMaps = StandardsOutcomeMap::all();
        foreach ($standardOutcomeMaps as $map) {
            $abv = $map->map_scale_value;
            $mapScale = DB::table('standard_scales')->where('abbreviation', $abv)->first();
            if ($mapScale != null) {
                DB::table('standards_outcome_maps')->updateOrInsert(
                    ['l_outcome_id' => $map->l_outcome_id, 'standard_id' => $map->standard_id],
                    ['standard_scale_id' => $mapScale->standard_scale_id]
                );
            }
        }
    }
}
