<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Charts\SampleChart;

use App\Models\MappingScaleProgram;
use App\Models\OutcomeMap;
use App\Models\Program;
use App\Models\ProgramLearningOutcome;
use Illuminate\Support\Facades\DB;

class ChartController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth',['except'=>['index']]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        /*

        Data Viz 1: Bar chart of the count of mapping scales per PLO across all course CLOs in a program (pg.3 U of C report)

        Ex. $course_id = 1
            $program_id = 5

        1. Get all the PLOs ($all_plos)
            - Query program_learning_outcomes table -> all pl_outcome_id WHERE program_id = $program_id
        */
        $program_id = 11; //this we will get in step 4
        $program = Program::where('program_id', $program_id)->first();
        
        // get categorized plo's for the program (ordered by category then outcome id)
        $plos_order = ProgramLearningOutcome::where('program_id', $program_id)->whereNotNull('plo_category_id')->orderBy('plo_category_id', 'ASC')->orderBy('pl_outcome_id', 'ASC')->get();
        // get UnCategorized PLO's
        $uncatPLOS = ProgramLearningOutcome::where('program_id', $program_id)->whereNull('plo_category_id')->get();
        
        $all_plos = $plos_order->toBase()->merge($uncatPLOS);

        //dd($all_plos);
        /*
        2. For loop over the PLOs in program
            - foreach pl_outcome_id as $plo_id in $all_plos
            - here we will have a list of PLOs for a program - in a collection I think
        */
        foreach($all_plos as $plo){
            /*
            3. Get map scale ids ($ms_ids)
                - Query outcome_maps table -> all map_scale_value where pl_outcome_id = $plo_id
                - here we want a set of map_scale_ids (ex. [1, 1, 2, 3, 3, 1, 2, 2])
            */
            //NOTE: map_scale_value changed to map_scale_id
            $ms_ids[$plo->pl_outcome_id] = OutcomeMap::where('pl_outcome_id', $plo->pl_outcome_id)->pluck('map_scale_value');
            
            // PR testing_and_staging -> data-visualization for new map_scale_id change

            /*
            4. For loop over the mappings of a PLO
                - foreach map_scale_id as $ms_id in $ms_ids
                - here we will want 3 I's, 4 D's, and 2 A's (ex. [I, I, D, A, A, I, D, D]) - in a array? collection?

                5. Get abbreviation ($ms_abbreviation) for current map scale id
                    - Query mapping_scales table -> abbreviation where map_scale_id = $ms_id
                    - this will return I/D/A ... etc.

        */

        }
        dd($ms_ids);
        
        
        

        $map_scale_ids = DB::table('mapping_scale_programs')->where('program_id', 1)->pluck('map_scale_id');
        
        //dd($map_scale_ids);

        //$map_scales = array();

        //foreach($map_scale_ids as $ms_ids){
            $map_scales = DB::table('mapping_scales')->where('map_scale_id', $map_scale_ids)->get();
        //}
        
        
        //dd($map_scales);

        $chart = new SampleChart;
        $chart->labels(['I', 'D', 'A', 'N/A']);

        //need to pull these from the categories table but as a test hardcoded.
        $hex1 = '#80bdff';
        $hex2= '#1aa7ff';
        $hex3 = '#0065bd';

        //hex to [r,g,b]
        list($r1, $g1, $b1) = sscanf($hex1, "#%02x%02x%02x");
        list($r2, $g2, $b2) = sscanf($hex2, "#%02x%02x%02x");
        list($r3, $g3, $b3) = sscanf($hex3, "#%02x%02x%02x");

        //[r,g,b] to rgba
        //background
        $bgtransp = '0.5';
        $bg1= 'rgba('.$r1.', '.$g1.', '.$b1.', '.$bgtransp.')';
        $bg2= 'rgba('.$r2.', '.$g2.', '.$b2.', '.$bgtransp.')';
        $bg3= 'rgba('.$r3.', '.$g3.', '.$b3.', '.$bgtransp.')';
        //border
        
        $bord1= 'rgba('.$r1.', '.$g1.', '.$b1.', 1)';
        $bord2= 'rgba('.$r2.', '.$g2.', '.$b2.', 1)';
        $bord3= 'rgba('.$r3.', '.$g3.', '.$b3.', 1)';
        

        $chart->dataset('EX101', 'bar',[1, 2, 3, 4])
            ->options([
                'backgroundColor'=>([
                            $bg1,
                            $bg2,
                            $bg3,
                            'rgba(255,255,255, '.$bgtransp.')']),
            'borderColor'=>([
                            $bord1,
                            $bord2,
                            $bord3,
                            '#000']),
                        ]);
        $chart->options([
            'scales' => [
                'yAxes' => [
                    [
                        'ticks' => [
                            'stepSize' => 1,
                        ],
                    ],
                ],
            ]]);
        $chart->displayLegend(false);
        $chart->title('My Test Chart', 20, '#666',true, 'Arial');


        return view('pages.chart', compact('chart'));
    }
}
