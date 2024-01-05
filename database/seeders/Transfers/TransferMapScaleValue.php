<?php

namespace Database\Seeders;

use App\Models\OutcomeMap;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TransferMapScaleValue extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $outcomeMaps = OutcomeMap::all();
        foreach ($outcomeMaps as $map) {
            $abv = $map->map_scale_value;
            $mapScale = DB::table('mapping_scales')->where('abbreviation', $abv)->first();
            if ($mapScale != null) {
                DB::table('outcome_maps')->updateOrInsert(
                    ['l_outcome_id' => $map->l_outcome_id, 'pl_outcome_id' => $map->pl_outcome_id],
                    ['map_scale_id' => $mapScale->map_scale_id]
                );
            }
        }
    }
}
