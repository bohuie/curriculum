<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CourseRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Facades\DB;

use App\Models\Course;

/**
 * Class CourseCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class CourseCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Course::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/course');
        CRUD::setEntityNameStrings('course', 'courses');

        // Hide the preview button 
        $this->crud->denyAccess('show');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        
        $this->crud->addColumn([
            'name' => 'course_code', // The db column name
            'label' => "Course Code", // Table column heading
            'type' => 'Text',
            'searchLogic' => function($query, $column, $searchTerm){
                $query ->orWhere('course_code', 'like', '%'.$searchTerm.'%');
            }
        ]);

        $this->crud->addColumn([
            'name' => 'course_num', // The db column name
            'label' => "Course Number", // Table column heading
            'type' => 'number',
            'searchLogic' => function($query, $column, $searchTerm){
                $query ->orWhere('course_num', 'like', '%'.$searchTerm.'%');
            }
        ]);
        
        $this->crud->addColumn([
            'name' => 'course_title', // The db column name
            'label' => "Course Title", // Table column heading
            'type' => 'Text',
            'searchLogic' => function($query, $column, $searchTerm){
                $query ->orWhere('course_title', 'like', '%'.$searchTerm.'%');
            }
        ]);

        /*$this->crud->addColumn([
            // 1-n relationship
            'label'     => 'Program', // Table column heading
            'type'      => 'select',
            'name'      => 'program_id', // the column that contains the ID of that connected entity;
            'entity'    => 'programs', // the method that defines the relationship in your Model
            'attribute' => 'program', // foreign key attribute that is shown to user
            'model'     => "App\Models\Program", // foreign key model
        ]);*/

        $this->crud->addColumn([   // radio
            'name'        => 'status', // the name of the db column
            'label'       => 'Status', // the input label
            'type'        => 'radio',
            'options'     => [
                // the key will be stored in the db, the value will be shown as label; 
                -1 => "❗In Progress",
                1 => "✔️Completed"
            ],
            // optional
            //'inline'      => false, // show the radios all on the same line?
        ]);

        $this->crud->addColumn([
            'name'      => 'row_number',
            'type'      => 'row_number',
            'label'     => '#',
            'orderable' => false,
        ])->makeFirstColumn();
        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']); 
         */
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(CourseRequest::class);

        $this->crud->addField([
            'name' => 'course_code', // The db column name
            'label' => "Course<br>Code&nbsp;&nbsp;<span style=\"color:red\">*</span>", // Table column heading
            'type' => 'Text',
            'attributes' => [ 'req' => 'true',
                            'size' => '4',
                            'maxlength' => '4'],            
            'wrapper' => ['class' => 'form-group col-md-3'],
        ]);

        $this->crud->addField([
            'name' => 'course_num', // The db column name
            'label' => "Course<br>Number&nbsp;&nbsp;<span style=\"color:red\">*</span>", // Table column heading
            'type' => 'number',
            'attributes' => [ 'req' => 'true',
                            'size' => '3',
                            'maxlength' => '3'],
            'wrapper' => ['class' => 'form-group col-md-3'],
        ]);
        
        $this->crud->addField([
            'name'        => 'section',
            'label'       => 'Section<br>ID',
            'type'        => 'text', 
            'attributes' => [ 'size' => '3',
                            'maxlength' => '3'],
            'wrapper' => ['class' => 'form-group col-md-3'],
        ]); 
        
        $this->crud->addField([
            'name' => 'course_title', // The db column name
            'label' => "Course Title&nbsp;&nbsp;<span style=\"color:red\">*</span>", // Table column heading
            'type' => 'valid_text',
            'attributes' => [ 'req' => 'true' ],
            'wrapper' => ['class' => 'form-group col-md-12'],
            
        ]);
        
        $this->crud->addField([
            // n-n relationship //this is necesary to accommodate the new course_program relationship
            'label'     => 'Program', // Table column heading
            'type'      => 'select2_multiple',
            'name'      => 'programs', // the column that contains the ID of that connected entity;
            'entity'    => 'programs', // the method that defines the relationship in your Model
            'attribute' => 'program', // foreign key attribute that is shown to user
            'model'     => "App\Models\Program", // foreign key model
            'placeholder' => "Select a program", // placeholder for the select2 input
            'pivot'     => true,
        ]);    
        
        $this->crud->addField([   // radio
            'name'        => 'status', // the name of the db column
            'label'       => 'Status', // the input label
            'type'        => 'radio',
            'options'     => [
                // the key will be stored in the db, the value will be shown as label; 
                -1 => "In Progress",
                1 => "Completed"
            ],
            'default'     => '1',
            // optional
            'inline'      => true, // show the radios all on the same line?
        ]);
        
        $this->crud->addField([   // relationship
            'label' => "Assigned Instructors",
            'type' => "select2_multiple",
            'name' => 'users', // the method on your model that defines the relationship

            // OPTIONALS:
            'entity' => 'users', // the method that defines the relationship in your Model
            'attribute' => "email", // foreign key attribute that is shown to user (identifiable attribute)
            'model' => "App\Models\User", // foreign key Eloquent model
            'placeholder' => "Select a user", // placeholder for the select2 input
            'pivot'     => true,
            'select_all' => true,

        ]);       
        

         $this->crud->addField([   // radio
            'name'        => 'assigned', // the name of the db column
            'label'       => 'Assigned to instructor', // the input label
            'type'        => 'radio',
            'options'     => [
                // the key will be stored in the db, the value will be shown as label; 
                -1 => "No",
                1 => "Yes"
            ],
            'default'     => '-1',
            // optional
            'inline'      => true, // show the radios all on the same line?
        ]);

        $this->crud->addField([   // CustomHTML
            'name'  => 'helper',
            'type'  => 'custom_html',
            'value' => '<small class="form-text text-muted">If instructors have been assigned to this course please select yes else select no</small>'
        ]);
        $this->crud->addField([   // radio
            'name'        => 'delivery_modality', // the name of the db column
            'label'       => 'Mode of Delivery', // the input label
            'type'        => 'radio',
            'options'     => [
                // the key will be stored in the db, the value will be shown as label; 
                'O' => "Online",
                'I' => "In-Person",
                'B' => "Hybrid"
            ],
            'default'     => 'I',
            // optional
            'inline'      => true, // show the radios all on the same line?
        ]);
        
         $this->crud->addField([   // Hidden
            'name'  => 'type',
            'type'  => 'hidden',
            'value' => 'assigned',
        ]);
        
        $this->crud->addField([
            'name' => 'standard_category_id', // The db column name
            'label' => "Ministry Standards<br>Category", // Table column heading
            'type' => 'select',
            'entity' => 'ministryStandardCategory', // the method that defines the relationship in your Model
            'attribute' => "sc_name", // foreign key attribute that is shown to user (identifiable attribute)
            'model' => "App\Models\StandardCategory", // foreign key Eloquent model         
            'wrapper' => ['class' => 'form-group col-md-5'],
            ]);
        
        $this->crud->addField([
            'name' => 'scale_category_id', // The db column name
            'label' => "Standards Scale<br>Category", // Table column heading
            'type' => 'select',
            'entity' => 'scalesCategory', // the method that defines the relationship in your Model
            'attribute' => "name", // foreign key attribute that is shown to user (identifiable attribute)
            'model' => "App\Models\StandardsScaleCategory", // foreign key Eloquent model         
            'wrapper' => ['class' => 'form-group col-md-5'],
            ]);
        
          //added this block as fix for bug03 (MD)
        $this->crud->addField([   // radio
            'name'        => 'year', // the name of the db column
            'label'       => 'Course Year (Level)', // the input label
            'type'        => 'radio',
            'options'     => [
                // the key will be stored in the db, the value will be shown as label; 
                1 => "1",
                2 => "2",
                3 => "3",
                4 => "4"
            ],
            'default'     => 1,
            // optional
            'inline'      => true, // show the radios all on the same line?
        ]);
        
        $this->crud->addField([
            'name'        => 'semester',
            'label'       => 'Semester',
            'type'        => 'radio',
            'options'     => [
                'W1' => "Winter",
                'W2' => "Spring",
                'S1' => "Summer I",
                "S2" => "Summer II"
            ],
            'default'     => 'W1',
            'inline'       => true,
        ]);
    }

    /**
     * Define what happens when the Update operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {   
        $this->setupCreateOperation();
        
      //following part is only available once the course record is created        

        $crsID = filter_input(INPUT_SERVER,'PATH_INFO');
        
            $crsID = explode("/",$crsID)[3];
            $crsData = Course::where('course_id', '=', $crsID)->get()[0];
            $CLOs =  \App\Models\LearningOutcome::where('course_id', '=', $crsID)->get();
            $AMs = DB::table('assessment_methods')->where('course_id', '=', $crsID)->get();
            $LAs = DB::table('learning_activities')->where('course_id', '=', $crsID)->get();
            $setOfCLO = [];
            foreach($CLOs as $clo)array_push($setOfCLO,$clo->l_outcome_id);
            $setOfAM = [];
            foreach($AMs as $am)array_push($setOfAM,$am->a_method_id);
            $setOfLA = [];
            foreach($LAs as $la)array_push($setOfLA,$la->l_activity_id); 
            
            $this->crud->addField([   // relationship
             'label' => "Course Learning Outcomes",
             'type' => "match_table",
             //'name' => 'learningOutcomes', // the method on your model that defines the relationship   
              'name' => 'CLOtable', //name of the getter/setter in course model 
             'columns' => [     
                 'l_outcome_id' => 'id-hidden',
                 'l_outcome'    => 'Learning Outcome (Shortphrase)-text-req-30',   //4th arguement sets the columnspan of the column
                 'clo_shortphrase'    => 'Course Learning Outcome-text-req-70',
             ],
             'max'     => 20,
             'min'     => 0,
             
           ]);
           
          
           /*$this->crud->addField([   // relationship
             'label' => "Assessment Methods",

             'type' => "assess_table",

             //'name' => 'assessmentMethods', // the method on your model that defines the relationship
             'name' => 'AMtable', //name of the getter/setter in course model 
             'default' => 'testing default string',
             
             'ajax' => true,  
             'columns' => [   
                 'a_method_id' => 'id-hidden',
                 'a_method'        => 'Assessment Method-text-req',
                 'weight'          => 'Weight (%)-number-req',
             ],
             
             'max'     => 20,
             
             'min'     => 0,
             
           ]);*/
            
            $this->crud->addField([   // relationship
             'label' => "Assessment Methods",
             'type' => "assess_repeatable",
             //'name' => 'assessmentMethods', // the method on your model that defines the relationship
             'name' => 'AMtable', //name of the getter/setter in course model 
             'default' => 'testing default string',
             
             'ajax' => true,  
             'fields' => [  
                 [
                   'name' => 'a_method_id',
                   'label' => 'ID',
                   'type' => 'hidden',
                   
                 ],
                 [
                   'name' => 'a_method',
                   'label' => 'Assessment Method&nbsp;&nbsp;<span style=color:red>*</span>',
                   'type' => 'list_select', 
                   'model' => 'App\Models\Custom_assessment_methods',
                   'attribute' => 'custom_methods',
                   'foreign-ref' => 'custom_methods', //the column from which the strings are pulled
                   'attributes' => [ 'req' => 'true' ],  //this is actually an input, not a select. 
                   'wrapper' => ['class' => 'hidden-label form-group col-sm-9'],
                   'lclass' => 'form-group col-sm-9', //label class
                 ],
                 [
                   'name' => 'weight',
                   'label' => 'Weight (%)&nbsp;&nbsp;<span style=color:red>*</span>',
                   'type' => 'number',
                   'attributes' => [ 'req' => 'true' ],
                   'wrapper' => ['class' => 'totaled_weight_ hidden-label form-group col-sm-3'],
                     'lclass' => 'form-group col-sm-3', //label class
                 ],                 
             ],
             
             'max'     => 5,
             
             'min'     => 0,
             
           ]);
           $total = DB::table('assessment_methods')->select('weight')->where('course_id',$crsID)->sum('weight');
           
            $this->crud->addField([   // the total for the assessment methods field. required for the script in the assess_repeatable
             'label' => "Total Weight",

             'type' => "number",
             
             'name' => 'totalweight', //name of the getter/setter in course model 
             'default' => $total,
             'wrapper' => ['class' => 'total_weight_ form-group col-sm-3']
            ]);
            
            
             
            $this->crud->addField([   // relationship
                'label' => "Teaching and Learning Activities",
                'type' => "match_table",
             //'name' => 'learningActivities', // the method on your model that defines the relationship
             'name' => 'LAtable', //name of the getter/setter in course model  
             'ajax' => true,  
             'columns' => [     
                 'l_activity_id' => 'id-hidden',
                 'l_activity'        => 'Teaching and Learning Activity-text-req',                
             ],
             
             'max'     => 20,
             
             'min'     => 0,
             
           ]);
                      
           $req =  $this->crud->getRequest()->request->all();
           //course alignment
           //create a table, fill the fields using custom code. each checkbox has a name with (oas|oac)-cID-aID (where aID is the other ID type)
           ///start with a row for each CLO
            $custHTML = "<fieldset name=\"alignfields\"><label>Course Alignment</label>"
                    . "<br><label style=\"font-size:.8em;\">Changes made to the Course Learning Outcomes, Learning Activities and Assessment Methods"
                    . " will be be reflected in this table only after saving and reloading the form</label>"                   
                    . "<table class=\"table table-sm table-striped m-b-0\" id=\"align-t\">"
                    . "<tr><th>Course Learning Outcomes or Competencies</th><th>Student Assessment Methods</th><th>Teaching and Learning Activities</th></tr>";
                        
           //by getting the set of ids for la and am, i can query only those oas and oac that belong to this course (not referenced directly by courseID)
           $chkStyle = "style=\"outline:1px double black;\"";
           $OAS = DB::table('outcome_assessments')->whereIn('a_method_id',  $setOfAM)->get();
           $OAC = DB::table('outcome_activities')->whereIn('l_activity_id', $setOfLA)->get();
           foreach($CLOs as $clo){
               $cID = $clo->l_outcome_id;
               $tRow = "<tr class=\"alignrow\" id=\"align-$cID\"><td id=\clo-$cID\">".$clo->clo_shortphrase."</td>";
               //now a column with checkboxes for the outcome assessments and for the outcome activities
               //each row in the db on either of those tables is a box that is ticked on this html table
               //outcome_assessments
               $amStr = "<ul>";
               foreach($AMs as $am){                   
                   $amID = $am->a_method_id;
                   $oas = $OAS->where('a_method_id', '=', $amID)->where('l_outcome_id', '=', $cID);
                   $ckd = (count($oas)> 0)?"checked":"";
                   $amStr .= "<li>". $am->a_method ."&nbsp;&nbsp;<input type=\"checkbox\" $chkStyle name=\"outcomea-oas-$cID-$amID\" $ckd></li>";
               }
               $tRow .= "<td id=\am-$cID\">$amStr</td>";
               //now the outcome_activities
               $laStr = "<ul>";
               foreach($LAs as $la){                   
                   $laID = $la->l_activity_id;
                   $oac = $OAC->where('l_activity_id', '=', $laID)->where('l_outcome_id', '=', $cID);
                   $ckd = (count($oac)> 0)?"checked":"";
                   $laStr .= "<li>". $la->l_activity ."&nbsp;&nbsp;<input type=\"checkbox\" $chkStyle name=\"outcomea-oac-$cID-$laID\" $ckd></li>";
               }
               $tRow .= "<td id=\am-$cID\">$laStr</ul></td></tr>";
               $custHTML .= $tRow;  
           }
           $custHTML .= "</table></fieldset>";
           
           $this->crud->addField([   // relationship
            'label' => 'Course Alignment',
            'type' => 'custom_html',
            'name' => 'align_table',
            'value' => $custHTML,
            ]);           
            
            if($req && count($req)){
                $r = DB::table('outcome_assessments')->whereIn('l_outcome_id',  $setOfCLO)->delete();
                $r = DB::table('outcome_activities')->whereIn('l_outcome_id',  $setOfCLO)->delete();
                $searchFor = "outcomea";               
                $chk = array_filter($_POST, function($key) use($searchFor){
                    return (strpos($key, $searchFor)!== false);
                },ARRAY_FILTER_USE_KEY);
                $newOAS = [];
                $newOAC = [];
                foreach($chk as $bx => $val){
                    $ids = explode("-",$bx);
                    ($ids[1] == "oas")? array_push($newOAS,['l_outcome_id' => $ids[2], 'a_method_id' => $ids[3]]) 
                                        : array_push($newOAC,['l_outcome_id' => $ids[2], 'l_activity_id' => $ids[3]]);
                }
                if(count($newOAS))$r = DB::table('outcome_assessments')->insert($newOAS);
                if(count($newOAC))$r = DB::table('outcome_activities')->insert($newOAC);
            }     
            
            //create custom html for mapping table: data collected as an array of radio buttons for each mapping with name code: map_CLOid_PLOid
            $buttonClass1 = "margin:0;padding:.5em;border:2px outset rgba(255, 255, 255,1.0);background-color:rgba(12, 35, 68,1.0);"; //dark ubc blue
            $buttonClass2 = "margin:5px 15px;padding:.5em;border:2px outset rgba(12, 35, 68,1.0);background-color:rgba(0, 85, 183,1.0);"; //deep blue
            $textClass2 = "color:rgb(151, 212, 233,1.0)"; //light blue
           // $textClass1 = "color:rgb(255, 255, 255,1.0)"; //white
            
            $custHTML = "<div><label>Program Outcome Mapping</label>";           
            //standards are roughly analogous to program outcomes, but there is one standard category per course. 
            //the scales are categorized in the standards versus select any from list with PLOs
            $Progs = DB::table('course_programs')->where('course_id', $crsID)
                            ->join('programs', 'programs.program_id', '=', 'course_programs.program_id')
                            ->select('programs.program_id','programs.program')
                            ->get();
            //this all needs to happen per program as these can be related optionally many to one with the course
            //function for opening and closing accordions
            $accFoo1 = "onClick=\"(function(){\n"
                            . "let ch = document.getElementById(&quot;";
            $accFoo2 = "&quot;);\n"
                            . "ch.hidden = !(ch.hidden);\n"
                          . "})();\"";      
            $delFoo1 = "onClick=\"(function(){\n"
                            . "$(&quot;tr[id=cp_";
            $delFoo2 = "_";
            $delFoo3 = "] td input&quot;).prop(&quot;checked&quot;, false)\n"
                          . "})();\"";
            
            foreach ($Progs as $program){ 
                $PFunc = $accFoo1."plomap_".$program->program_id.$accFoo2;
                $custHTML .= "<div $PFunc><div style=\"$buttonClass1\"><h3 class=\"crudribbon\">$program->program</h3></div></div>"
                            . "<table id=\"plomap_$program->program_id\" class=\"table table-sm table-striped m-b-0\" hidden>";
                $PLOs = DB::table('program_learning_outcomes')->where('program_id', $program->program_id)->get();
                $OCmaps = DB::table('outcome_maps')->whereIn('l_outcome_id', $setOfCLO)->get();
                $MScP = DB::table('mapping_scale_programs')->where('program_id',$program->program_id)->get();
                $setOfMScP = [];
                foreach($MScP as $msp)array_push($setOfMScP,$msp->map_scale_id);
                $MScales = DB::table('mapping_scales')->whereIn('map_scale_id', $setOfMScP)->get();
                $msStr = "";
                foreach($MScales as $scale)$msStr .= "<th width=\"6%\" title=\"".$scale->description."\">". $scale->abbreviation."</th>";
                $msStr .= "<th width=\"6%\" title=\"Not Applicable\">N/A</th>";
                foreach($CLOs as $clo){
                    $custHTML .= "<tr><th colspan=\"5\">$clo->clo_shortphrase</th></tr><tr><th>" . 
                            $program->program .
                            "</th>" . $msStr . "</tr>";
                    foreach($PLOs as $plo){
                        $custHTML .= "<tr id=\"cp_".$clo->l_outcome_id."_".$plo->pl_outcome_id."\"><td title=\"" . $plo->pl_outcome. "\">". $plo->plo_shortphrase."</td>";
                        for($i = 1; $i <= $MScales->count()+1; $i++){
                            $map = $OCmaps->where('l_outcome_id', $clo->l_outcome_id)->where('pl_outcome_id', $plo->pl_outcome_id);                        
                            $chk = (($i <= $MScales->count() && $map->count() != 0 && $map->first()->map_scale_id == $MScales[$i-1]->map_scale_id) || 
                                    ($i == $MScales->count()+1 && $map->count() != 0 && $map->first()->map_scale_id == 0))?"checked" : "";
                            $val = "value=\"0\"";
                            if($i <= $MScales->count())$val = "value=\"".$MScales[$i-1]->map_scale_id."\"";
                            $custHTML.="<td width=\"6%\"><input type=\"radio\" name=\"map_".$clo->l_outcome_id."_".$plo->pl_outcome_id."[]\" $chk $val></td>";
                        }
                        $delBstr = $delFoo1.$clo->l_outcome_id.$delFoo2.$plo->pl_outcome_id.$delFoo3;
                        $custHTML.="<td width=\"4%\">"
                                . "<button class=\"btn btn-sm btn-light removeItem\" type=\"button\" $delBstr>"
                                . "<span class=\"sr-only\">delete item</span>"
                                . "<i class=\"la la-trash\" role=\"presentation\" aria-hidden=\"true\"></i>"
                                . "</button></td>";
                    }
                }
                $custHTML .= "</table>";
            }            
            $custHTML .= "</div>";
            $this->crud->addField([
                'label' => 'Outcome Mappings',
                'name' => 'outcome_mapping',
                'type' => 'custom_html',   
                'value' => $custHTML,
            ]);
            //code to CrUD the program lo mapping . should still work with multiple prgrams
            if($req && count($req)){                
                $chk = array_filter($_POST, function($element) {
                    return  !(false===strpos($element, "map"));
                },ARRAY_FILTER_USE_KEY);
                $setDelcp = DB::table("outcome_maps")->whereIn("l_outcome_id",$setOfCLO)->get()->toArray();
                foreach($chk as $key => $val){
                    $exKey = explode('_',$key);
                    $map = $OCmaps->where('l_outcome_id', $exKey[1])->where('pl_outcome_id', $exKey[2]);
                    //if not entry already exists in DB, enter it. otherwise update it, as the value may have changed
                    if(!($map->count() > 0))
                        DB::table('outcome_maps')->insert(['l_outcome_id'=>$exKey[1],'pl_outcome_id'=>$exKey[2],'map_scale_value'=>$val[0]]);
                    else{
                        \App\Models\OutcomeMap::where('l_outcome_id', $exKey[1])
                                                ->where('pl_outcome_id', $exKey[2])
                                                ->update(['map_scale_value'=>$val[0]]);
                    foreach($setDelcp as $key => $val)
                        if($val->l_outcome_id == $exKey[1] && $val->pl_outcome_id == $exKey[2])
                            unset ($setDelcp[$key]); //this list is then deleted after those buttons checked are updated 
                        
                    }
                }                
                foreach($setDelcp as $val)DB::table("outcome_maps")->where("l_outcome_id", $val->l_outcome_id)->where("pl_outcome_id", $val->pl_outcome_id)->delete();
            }
            
            //Ministry Standard Mapping
            //***************
            $custHTML = "<div><label>Standards Outcome Mapping</label>";           
            //standards are roughly analogous to program outcomes, but there is one standard category per course. 
            //the scales are categorized in the standards versus select any from list with PLOs
                
                $SOCmaps = DB::table('standards_outcome_maps')->whereIn('l_outcome_id', $setOfCLO)->get();   
                $crs = Course::where('course_id',$crsID)->get();
                $scaleId = $crs->first()->scale_category_id;
                $standId = $crs->first()->standard_category_id;
                $scaleCat = DB::table('standards_scale_categories')->where('scale_category_id', $scaleId)->get()->first();
                $standardsCat = DB::table('standard_categories')->where('standard_category_id', $standId)->get()->first();               
                $MScales = DB::table('standard_scales')->where('scale_category_id', $scaleId)->orderBy('standard_scale_id', 'asc')->get();
                $standards =  DB::table('standards')->where('standard_category_id',$standId)->get();
                $PFunc = $accFoo1."minmap"."".$accFoo2;
                $custHTML .= "<div $PFunc><div class=\"accBar\" style=\"$buttonClass1\"><h3 class=\"crudribbon\">$standardsCat->sc_name (using $scaleCat->name)</h3></div></div>"
                            . "<table id=\"minmap\" class=\"table table-sm table-striped m-b-0\" hidden>";
                $msStr = "";
                foreach($MScales as $scale)$msStr .= "<th width=\"6%\" title=\"".$scale->description."\">". $scale->abbreviation."</th>";
                $msStr .= "<th width=\"6%\" title=\"Not Applicable\">N/A</th>";
                
                foreach($CLOs as $clo){
                    $custHTML .= "<tr><th colspan=\"5\">$clo->clo_shortphrase</th></tr><tr><th>" . 
                            $standardsCat->sc_name .
                            "</th>" . $msStr . "</tr>";
                    foreach($standards as $std){
                        $custHTML .= "<tr><td title=\"" . $std->s_outcome. "\">". $std->s_shortphrase."</td>";
                        for($i = 1; $i <= $MScales->count()+1; $i++){
                            $map = $SOCmaps->where('l_outcome_id', $clo->l_outcome_id)->where('standard_id', $std->standard_id);      
                            //dd($map);                  
                            $chk = (($i <= $MScales->count() && $map->count() != 0 && $map->first()->standard_scale_id == $MScales[$i-1]->standard_scale_id) || 
                                    ($i == $MScales->count()+1 && $map->count() != 0 && $map->first()->standard_scale_id == 0))?"checked" : "";
                            $val = "value=\"0\"";
                            if($i <= $MScales->count())$val = "value=\"".$MScales[$i-1]->standard_scale_id."\"";
                            $custHTML.="<td width=\"6%\"><input type=\"radio\" name=\"min_".$clo->l_outcome_id."_".$std->standard_id."[]\" $chk $val></td>";
                        }
                    }
                }
                $custHTML .= "</table>";                       
                $custHTML .= "</div>";
            $this->crud->addField([
                'label' => 'Ministry Standard Mappings',
                'name' => 'std_outcome_mapping',
                'type' => 'custom_html',   
                'value' => $custHTML,
            ]);
            //Ministry Standards CRUD
            //****************
            if($req && count($req)){                
                $chk = array_filter($_POST, function($element) {
                    return  !(false===strpos($element, "min"));
                },ARRAY_FILTER_USE_KEY);
                foreach($chk as $key => $val){
                    $exKey = explode('_',$key);
                    $map = $SOCmaps->where('l_outcome_id', $exKey[1])->where('standard_id', $exKey[2]);
                    //if not entry already exists in DB, enter it. otherwise update it, as the value may have changed
                    if(!($map->count() > 0))
                        DB::table('standards_outcome_maps')->insert(['l_outcome_id'=>$exKey[1],'standard_id'=>$exKey[2],'standard_scale_id'=>$val[0]]);
                    else
                        \App\Models\StandardsOutcomeMap::where('l_outcome_id', $exKey[1])
                                                ->where('standard_id', $exKey[2])
                                                ->update(['standard_scale_id'=>$val[0]]);
                }
            }
            
            //Code for Optional Priorities
            //***********************
            $setOpPr = DB::table('course_optional_priorities')->where('course_id', $crsID)->get();                         
            $setOfOpPr = DB::table('optional_priorities')
                            ->join('optional_priority_subcategories', 'optional_priorities.subcat_id', '=', 'optional_priority_subcategories.subcat_id')
                            ->join('optional_priority_categories', 'optional_priority_categories.cat_id', '=', 'optional_priority_subcategories.cat_id')
                            ->select('optional_priorities.op_id as id','optional_priorities.optional_priority as op', 'optional_priority_categories.cat_id as cat_id','optional_priority_subcategories.subcat_id as subcat_id',
                                    'optional_priority_categories.cat_name as cat_name', 'optional_priority_subcategories.subcat_name as subcat_name', 'optional_priority_subcategories.subcat_desc as subcat_desc')
                            ->get();
            $setCat = DB::table('optional_priority_categories')->get();
            //loop x3 to create hierarchical html
            $custHTML = "<div><label>Optional Priorities</label>";  
            foreach($setCat as $cat){
                $catFunc = $accFoo1."category_".$cat->cat_id.$accFoo2;
                $custHTML .= "<div $catFunc><div style=\"$buttonClass1\"><h3 class=\"crudribbon\">$cat->cat_name</h3></div></div><div id=\"category_".$cat->cat_id."\"  hidden>";
                //create header for cat
                $setSubCat = DB::table('optional_priority_subcategories')->where('cat_id', $cat->cat_id)->get();
                foreach($setSubCat as $subcat){
                    $subcatFunc = $accFoo1."wrap_subcategory_".$subcat->subcat_id.$accFoo2;                            
                    $custHTML .= "<div $subcatFunc><div style=\"$buttonClass2\"><h4 class=\"crudribbon\" >$subcat->subcat_name</h4></div></div>"
                            . "<div id=\"wrap_subcategory_".$subcat->subcat_id."\" hidden>"
                            . "<table id=\"subcategory_".$subcat->subcat_id."\" class=\"table table-sm table-striped m-b-0\" >";                              
                    //create header for subcat
                    $scop = DB::table('optional_priorities')->where('subcat_id',$subcat->subcat_id)->get();
                    foreach($scop as $op){
                        //create row with checkbox for each OP
                        $opid = $op->op_id;
                        $chk = (count($setOpPr->where('op_id', $opid)) > 0) ? "checked" : "";
                        $custHTML .= "<tr><td><input type=\"checkbox\" name=\"opp_$opid\" $chk></td><td >". $op->optional_priority."</td></tr>";                        
                    }                    
                    $custHTML .= "</table>";
                    $custHTML .= (property_exists($subcat, "subcat_postamble")) ? $subcat->subcat_postamble : "";
                    $custHTML .= "</div>";
                }                
                $custHTML .= "</div>";
            }
            $custHTML .= "</div>";
            $this->crud->addField([
                'label' => 'Optional Priorities',
                'name' => 'optional_priorities',
                'type' => 'custom_html',   
                'value' => $custHTML,
            ]);
             //code to CrUD the Optional Priorities. 
            if($req && count($req)){           
                $chkOP = [];
                $chk = array_filter($_POST, function($element) {
                    return  !(false===strpos($element, "opp"));
                },ARRAY_FILTER_USE_KEY);
                foreach($chk as $key => $val){
                    $exKey = explode('_',$key);
                    array_push($chkOP, $exKey[1]);
                    $map = $setOpPr->where('op_id', $exKey[1]);
                    //if no entry already exists in DB, enter it. 
                    if(!($map->count() > 0))
                        DB::table('course_optional_priorities')->insert(['op_id'=>$exKey[1],'course_id'=>$crsID]);  
                }//have to delete those in database not checked here.
                DB::table('course_optional_priorities')->where('course_id',$crsID)->whereNotIn('op_id', $chkOP)->delete();
            }
    }

    protected function setupShowOperation()
    {
        //CRUD::setValidation(CourseRequest::class);
        $this->crud->set('show.setFromDb', false);

        $this->crud->addColumn([
            'name' => 'course_code', // The db column name
            'label' => "Course Code", // Table column heading
            'type' => 'Text'
        ]);

        $this->crud->addColumn([
            'name' => 'course_num', // The db column name
            'label' => "Course Number", // Table column heading
            'type' => 'number'
        ]);
        
        $this->crud->addColumn([
            'name' => 'course_title', // The db column name
            'label' => "Course Title", // Table column heading
            'type' => 'Text'
        ]);

        $this->crud->addColumn([
            // 1-n relationship
            'label'     => 'Program', // Table column heading
            'type'      => 'select',
            'name'      => 'program_id', // the column that contains the ID of that connected entity;
            'entity'    => 'programs', // the method that defines the relationship in your Model
            'attribute' => 'program', // foreign key attribute that is shown to user
            'model'     => App\Models\Program::class, // foreign key model
        ]);
        
        $this->crud->addColumn([   // radio
            'name'        => 'status', // the name of the db column
            'label'       => 'Status', // the input label
            'type'        => 'radio',
            'options'     => [
                // the key will be stored in the db, the value will be shown as label; 
                -1 => "In Progress",
                1 => "Completed"
            ],            
        ]);

        $this->crud->addColumn([   // radio
            'name'        => 'assigned', // the name of the db column
            'label'       => 'Assigned to instructor', // the input label
            'type'        => 'radio',
            'options'     => [
                // the key will be stored in the db, the value will be shown as label; 
                -1 => "No",
                1 => "Yes"
            ],            
        ]);
        
        
        
        $this->crud->addColumn([  
            // any type of relationship
            'name'         => 'users', // name of relationship method in the model
            'type'         => 'select_multiple',
            'label'        => 'Assigned Instructors', // Table column heading
            // OPTIONAL
            'entity'       => 'users', // the method that defines the relationship in your Model
            'attribute'    => 'email', // foreign key attribute that is shown to user
            'model'        => App\Models\User::class, // foreign key model
        ]);        
    }
    
    //use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation { destroy as traitDestroy; }

    public function destroy($id)
    {
        $this->crud->hasAccessOrFail('delete');
        //delete all children starting with the leafmost objects. they have to be accessed using the id's of their parent records however (either the cloID or the courseID in this case)
        $crsID = filter_input(INPUT_SERVER,'PATH_INFO');        
        $crsID = explode("/",$crsID)[3];
        //first get the relevant ids
        $CLOs =  \App\Models\LearningOutcome::where('course_id', '=', $crsID)->get();        
        $setOfCLO = [];
        foreach($CLOs as $clo)array_push($setOfCLO,$clo->l_outcome_id);        
        //deleting records
        $r = DB::table('outcome_maps')->whereIn('l_outcome_id', $setOfCLO)->delete();
        $r = DB::table('outcome_assessments')->whereIn('l_outcome_id',  $setOfCLO)->delete();
        $r = DB::table('outcome_activities')->whereIn('l_outcome_id',  $setOfCLO)->delete();
        $r = DB::table('standards_outcome_maps')->whereIn('l_outcome_id', $setOfCLO)->delete();  
        $r = DB::table('course_optional_priorities')->where('course_id', $crsID)->delete();  
        $r = DB::table('assessment_methods')->where('course_id', '=', $crsID)->delete();
        $r = DB::table('learning_activities')->where('course_id', '=', $crsID)->delete();
        $r = DB::table('course_programs')->where('course_id', '=', $crsID)->delete();
        $r = DB::table('course_users')->where('course_id', '=', $crsID)->delete();
        //this deletes the course record itself.
        return $this->crud->delete($id);
    }
}