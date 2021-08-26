<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Charts\SampleChart;
use App\Models\MappingScale;
use App\Models\MappingScaleProgram;
use App\Models\OutcomeMap;
use App\Models\Program;
use App\Models\ProgramLearningOutcome;
use Database\Seeders\MappingScaleSeeder;
use Illuminate\Support\Facades\DB;
use Mockery\Undefined;

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
        $mapScales_ids = array();
        $mapScales_ids_perPLO = array();
        foreach($all_plos as $plo){
            /*
            3. Get map scale ids ($ms_ids)
                - Query outcome_maps table -> all map_scale_value where pl_outcome_id = $plo_id
                - here we want a set of map_scale_ids (ex. [1, 1, 2, 3, 3, 1, 2, 2])
            */
            
            $mapScales_ids_perPLO[$plo->pl_outcome_id] = OutcomeMap::where('pl_outcome_id', $plo->pl_outcome_id)->pluck('map_scale_id')->toArray();
            $mapScales_ids_perPID[$plo->pl_outcome_id] = MappingScaleProgram::where('program_id', $program_id)->pluck('map_scale_id')->toArray();
        }
        //dd($mapScales_ids_perPID);
        /*
        4. For loop over the mappings of a PLO
            - foreach map_scale_id as $ms_id in $ms_ids
            - here we will want 3 I's, 4 D's, and 2 A's (ex. [I, I, D, A, A, I, D, D]) - in a array? collection?
        */
        $count_ms_id = array();
        foreach($mapScales_ids_perPLO as $plOutcomeId => $mapScale_ids){
            
            foreach($mapScale_ids as $index => $mapScale_id)
            {
                /*
                5. Get abbreviation ($ms_abbreviation) for current map scale id
                    - Query mapping_scales table -> abbreviation where map_scale_id = $ms_id
                    - this will return I/D/A ... etc.
                
               */
                //query for abbreviations
                $ms_colour = MappingScale::where('map_scale_id', $mapScale_id)->pluck('colour')->first();
                $ms_abbreviation = MappingScale::where('map_scale_id', $mapScale_id)->pluck('abbreviation')->first();
                
                $count_ms = MappingScaleProgram::where('map_scale_id', $mapScale_id)->count();
                //dd($count_ms);
                //initialize to 0, this will hold the counts of the map scales
                $count_ms_id[$plOutcomeId][$mapScale_id]['count'] = 0;
                //store the map scale's chart background colour
                $count_ms_id[$plOutcomeId][$mapScale_id]["bg_colour"] = $this->convertHexToRGBA($ms_colour, "0.5");
                //store the map scale's chart border colour
                $count_ms_id[$plOutcomeId][$mapScale_id]["border_colour"] = $this->convertHexToRGBA($ms_colour, "1");
                //store the map scale's abbreviation
                $count_ms_id[$plOutcomeId][$mapScale_id]["abv"] = $ms_abbreviation;
            }
            //dd($count_ms_id);
            /*
            6. Get count/frequency of each abbreviation ($count_ms_id)
            - Get the count of all map_scale_id in the map_scale_category that the current map_scale_id is in.
            */
            
            foreach($mapScale_ids as $index => $mapScale_id)
            {
                //dd($mapScales_ids_perPLO[$plOutcomeId]);
                //if(!(array_key_exists($mapScale_id, $mapScales_ids_perPLO[$plOutcomeId]))){
                    //$count_ms_id[$plOutcomeId][$mapScale_id] = 0;
                //}else{
                    
                    if($count_ms_id[$plOutcomeId][$mapScale_id]["count"] == 0 ){
                        $count_ms_id[$plOutcomeId][$mapScale_id]["count"] = 1;
                    }else{
                        $count_ms_id[$plOutcomeId][$mapScale_id]["count"] += 1;
                    }
                    
                //}
            }
            
        }
        //dd($count_ms_id);
        $labels = array();
        foreach($all_plos as $plo){
            $labels[] = $plo->plo_shortphrase;
        }
        
        
        $chart = new SampleChart;
        //$chart->labels(['PLO1', 'PLO2', 'PLO3', 'PLO4']);
        $chart->labels($labels);
        
        //stores count at the map scale id index
        //form: [PLO1_count, PLO2_count, PLO3_count, PLO4_count]
        $counts = array();
        $counts[0]= [0,0,1,0]; //N/A
        $counts[1]= [2,0,0,0]; //I
        $counts[2]= [0,2,1,1]; //D
        $counts[3]= [0,0,0,1]; //A

        //stores background colours at the map scale id index
        $bg_colours = array();
        $bg_colours[0]= 'rgb(255,255,255, 0.5)'; //N/A
        $bg_colours[1]= 'rgb(255,0,0, 0.5)'; //I
        $bg_colours[2]= 'rgb(0,255,0, 0.5)'; //D
        $bg_colours[3]= 'rgb(0,0,255, 0.5)'; //A

        //stores border colours at the map scale id index
        $border_colours = array();
        $border_colours[0]= 'rgb(255,255,255, 1)'; //N/A
        $border_colours[1]= 'rgb(255,0,0, 1)'; //I
        $border_colours[2]= 'rgb(0,255,0, 1)'; //D
        $border_colours[3]= 'rgb(0,0,255, 1)'; //A

        //stores abbreviations at the map scale id index
        $abvs = array();
        $abvs[0]= 'N/A'; //N/A
        $abvs[1]= 'I'; //I
        $abvs[2]= 'D'; //D
        $abvs[3]= 'A'; //A

        $mapScaleIDS = [0,1,2,3]; // we need to get this. These are the array indices
        
        $output = array(); //output array with organized data

        foreach($mapScaleIDS as $mapScaleID){
            $output[$mapScaleID] = array(
                'count' => $counts[$mapScaleID],
                'bg_colour' => $bg_colours[$mapScaleID],
                'border_colour' => $border_colours[$mapScaleID],
                'abv' => $abvs[$mapScaleID],
            );
        }
        
        //initialize data array of arrays in form of [PLO1_count, PLO2_count, PLO3_count, PLO4_count, etc.]
        /*
        ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
        This is the form we want
        $data = array(
            //N/A
            0 => array(
                'count' => [0,0,1,0],
                'bg_colour' => 'rgb(255,255,255, 0.5)',
                'border_colour' => 'rgb(0,0,0, 1)', //WHITE
                'abv' => 'N/A',
            ),
            //I
            1 => array(
                'count' => [2,0,0,0],
                'bg_colour' => 'rgb(255,0,0, 0.5)',
                'border_colour' => 'rgb(255,0,0, 1)', //RED
                'abv' => 'I',
            ),
            //D
            2 => array(
                'count' => [0,2,1,1],
                'bg_colour' => 'rgb(0,255,0, 0.5)',
                'border_colour' => 'rgb(0,255,0, 1)', //GREEN
                'abv' => 'D',
            ),
            //A
            3 => array(
                'count' => [0,0,0,1],
                'bg_colour' => 'rgb(0,0,255, 0.5)',
                'border_colour' => 'rgb(0,0,255, 1)', // BLUE
                'abv' => 'A',
            )
        );
        ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
        */
        
        

        // foreach plo (first index in count_ms_id)
        // *** Maybe we do a switch statement here? *** 
        
        /*
        foreach($count_ms_id as $poi => $ms){
            
                //dd($ms[$ms_id]);
                //add dataset 1
                $chart->dataset(
                    $ms['abv'],  //dataset name (map scale abriev)
                    'bar',       //data (chart) type
                    $data[$idx] //data
                    )
                    ->options([
                    'backgroundColor'=>( $ms['bg_colour']),
                    'borderColor'=>( $ms['border_colour']),
                ]);
            $idx += 1;
        }*/
        
        /*
        foreach($output as $dataset){
        
            //dd($dataset);
                //add dataset 1
                $chart->dataset(
                    $dataset['abv'],  //dataset name
                    'bar',       //data (chart) type
                    $dataset['count'] //data
                )
                ->options([
                    'backgroundColor'=>( $dataset['bg_colour']),
                    'borderColor'=>( $dataset['border_colour']),
                ]);
            
        }/**/

        
        $bg1 = $this->convertHexToRGBA('#80bdff', '0.5');
        $bg2 = $this->convertHexToRGBA('#1aa7ff', '0.5');
        $bg3 = $this->convertHexToRGBA('#0065bd', '0.5');

        $bord1 = $this->convertHexToRGBA('#80bdff', '1');
        $bord2 = $this->convertHexToRGBA('#1aa7ff', '1');
        $bord3 = $this->convertHexToRGBA('#0065bd', '1');

        //add dataset 1
        $chart->dataset(
            'I',  //dataset name
            'bar',       //data (chart) type
            [4, 3, 2, 1] //data
            )
            ->options([
            'backgroundColor'=>($bg1),
            'borderColor'=>($bord1),
        ]);
        //add dataset 2
        $chart->dataset(
            'D',  //dataset name
            'bar',       //data (chart) type
            [2, 2, 4, 1] //data
            )
            ->options([
            'backgroundColor'=>($bg2),
            'borderColor'=>($bord2),
        ]);
        // add dataset 3
        $chart->dataset(
            'A', //dataset name
            'bar',      //data (chart) type
            [1, 1, 5, 1] //data
            )
            ->options([
            'backgroundColor'=>($bg3),
            'borderColor'=>($bord3),
        ]);
        
        //make the scale always step size (increment) of 1
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
        $chart->title('PLO Mapping Chart', 20, '#666', true, 'Arial');


        return view('pages.chart', compact('chart'));
    }
    public function convertHexToRGBA($hexColour, $transparency){
        //hex to [r,g,b]
        list($r1, $g1, $b1) = sscanf($hexColour, "#%02x%02x%02x");
        //[r,g,b] to rgba
        $new_rgb = 'rgba('.$r1.', '.$g1.', '.$b1.', '.$transparency.')';

        return $new_rgb;
    }
}
