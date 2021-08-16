<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Program;
use App\Models\OutcomeMap;
use App\Models\StandardsOutcomeMap;

class TransferSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {   
        // transfer outcome map records for undergraduate degree level
        $ugradProgram = Program::find(1);
        $ugradPLOs = $ugradProgram->programLearningOutcomes;
        $ugradOutcomeMap = array();
        foreach ($ugradPLOs as $index => $ugradPLO){
            $outcomeMaps = OutcomeMap::where('pl_outcome_id', $ugradPLO->pl_outcome_id)->get();
            $ugradOutcomeMap[$ugradPLO->pl_outcome_id] = $outcomeMaps;
        }

        foreach ($ugradOutcomeMap as $ugradPLO_id => $ugrad_plo_clos_pivot) {
            foreach ($ugrad_plo_clos_pivot as $ugrad_plo_clo) {
                StandardsOutcomeMap::create([
                    'standard_id' => $ugradPLO_id,
                    'l_outcome_id'=> $ugrad_plo_clo->l_outcome_id,
                    'map_scale_value' => $ugrad_plo_clo->map_scale_value,
                ]);
                $ugradMapRecord = OutcomeMap::where([
                    ['pl_outcome_id', '=', $ugradPLO_id],
                    ['l_outcome_id', '=', $ugrad_plo_clo->l_outcome_id],
                ]);
                $ugradMapRecord->delete();

            }
        }

        // transfer outcome map records for undergraduate degree level
        $gradProgram = Program::find(2);
        $gradPLOs = $gradProgram->programLearningOutcomes;
        $gradOutcomeMap = array();
        foreach ($gradPLOs as $index => $gradPLO){
            $outcomeMaps = OutcomeMap::where('pl_outcome_id', $gradPLO->pl_outcome_id)->get();
            $gradOutcomeMap[$gradPLO->pl_outcome_id] = $outcomeMaps;
        }

        foreach ($gradOutcomeMap as $gradPLO_id => $grad_plo_clos_pivot) {
            foreach ($grad_plo_clos_pivot as $grad_plo_clo) {
                StandardsOutcomeMap::create([
                    'standard_id' => $gradPLO_id,
                    'l_outcome_id'=> $grad_plo_clo->l_outcome_id,
                    'map_scale_value' => $grad_plo_clo->map_scale_value,
                ]);
                $gradMapRecord = OutcomeMap::where([
                    ['pl_outcome_id', '=', $gradPLO_id],
                    ['l_outcome_id', '=', $grad_plo_clo->l_outcome_id],
                ]);
                $gradMapRecord->delete();

            }
        }

        // transfer outcome map records for doctoral degree level
        $docProgram = Program::find(3);
        $docPLOs = $docProgram->programLearningOutcomes;
        $docOutcomeMap = array();
        foreach ($docPLOs as $index => $docPLO){
            $outcomeMaps = OutcomeMap::where('pl_outcome_id', $docPLO->pl_outcome_id)->get();
            $docOutcomeMap[$gradPLO->pl_outcome_id] = $outcomeMaps;
        }

        foreach ($docOutcomeMap as $docPLO_id => $doc_plo_clos_pivot) {
            foreach ($doc_plo_clos_pivot as $doc_plo_clo) {
                StandardsOutcomeMap::create([
                    'standard_id' => $docPLO_id,
                    'l_outcome_id'=> $doc_plo_clo->l_outcome_id,
                    'map_scale_value' => $doc_plo_clo->map_scale_value,
                ]);
                $docMapRecord = OutcomeMap::where([
                    ['pl_outcome_id', '=', $docPLO_id],
                    ['l_outcome_id', '=', $doc_plo_clo->l_outcome_id],
                ]);
                $docMapRecord->delete();
            }
        }
    }
}
