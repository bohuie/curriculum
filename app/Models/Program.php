<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Program extends Model
{
    use \Backpack\CRUD\app\Models\Traits\CrudTrait;
    use HasFactory;

    protected $primaryKey = 'program_id';

    protected $table = 'programs';   
    

    protected $fillable = ['program', 'faculty', 'department',  'level', 'status', 'ProgramOC'];

    protected $guarded = ['program_id'];
    
    

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_programs', 'program_id', 'course_id')->withPivot('course_required', 'instructor_assigned', 'map_status')->withTimestamps();
    }

    /* public function mappingScaleLevels()
    {
        return $this->hasManyThrough(MappingScale::Class, MappingScaleProgram::Class);
    }*/

    public function mappingScaleLevels()
    {
        return $this->belongsToMany(MappingScale::class, 'mapping_scale_programs', 'program_id', 'map_scale_id')->withTimestamps();
    }
    
    public function mappingScalePrograms()
    {
        return $this->hasMany(MappingScaleProgram::class, 'program_id', 'program_id');
    }
    
    /* public function newPivot(Model $parent, array $attributes, $table, $exists, $using = NULL) {
        if ($parent instanceof Program) {
            return new MappingScaleProgram($parent, $attributes, $table, $exists, $using = NULL);
        }
        return parent::newPivot($parent, $attributes, $table, $exists, $using = NULL);
    }*/
    
    public function users(){
        return $this->belongsToMany(User::class, 'program_users', 'program_id', 'user_id');
    }

    // Eloquent automatically determines the FK column for the ProgramLearningOutcome model by taking the parent model (program) and suffix it with _id (program_id)
    public function programLearningOutcomes() {
        return $this->hasMany(ProgramLearningOutcome::class, 'program_id', 'program_id');
    }
    public function getProgramOCAttribute(){
        $prgID = filter_input(INPUT_SERVER,'PATH_INFO');
        $prgID = explode("/",$prgID)[3];
        $ploCats =  \App\Models\PLOCategory::where('program_id', '=', $prgID)->get()->toArray();
        for($i = 0; $i < count($ploCats); $i++){
            $ploCats[$i]['programOutcome'] = json_encode(\App\Models\ProgramLearningOutcome::
                    where('plo_category_id', '=',$ploCats[$i]['plo_category_id'])
                    ->get()->toArray());
        }
        //this gets the uncategorized records
        $ploCats[count($ploCats)]['programOutcome'] = json_encode(\App\Models\ProgramLearningOutcome::
                    where('plo_category_id', '=',NULL)->where('program_id', '=', $prgID)
                    ->get()->toArray());
        $ploCats[count($ploCats)-1]['plo_category'] = "Uncategorized";
        return json_encode($ploCats);
    }
    
    public function setProgramOCAttribute($value){      
        $prgID = filter_input(INPUT_SERVER,'PATH_INFO');        
        $prgID = explode("/",$prgID)[3];
        $jdata = json_decode($value);    
        if(!is_array($jdata))$jdata = [];  
        //**********
        //crud for categories
        //**********
        $existingCats =  \App\Models\PLOCategory::where('program_id', '=', $prgID)->get();      //all cats in the db for this program
        $setCats = [];  //this is the set of ids for easy db access
        $setDel = [];
        foreach($existingCats as $cat){array_push($setCats,$cat->plo_category_id);}
        $nSc = [];      //rows already in the DB (they have an ID)   
        foreach($jdata as $row){
            if(property_exists($row, "plo_category_id"))
                array_push($nSc,$row->plo_category_id);
        }
        $setDel = array_filter($setCats, function($element) use($nSc){  //filters from the db records those not present on the page. these are deleted
            return !(in_array($element, $nSc));
        });
        $aData = [];
        foreach($jdata as $key => $row){
            $item = json_decode(json_encode($row),true);
            if($item['plo_category'] == "Uncategorized"){$aData[$key] = $item;continue;} //do not insert uncategorized as a category
            if(property_exists($row, "plo_category_id") && $row->plo_category_id != ""){
                $id = $row->plo_category_id;
                if(in_array($id, $setCats))
                    PLOCategory::where('plo_category_id', $id)->update(['plo_category' => $row->plo_category]);
            }
            else{
                $res = DB::table('p_l_o_categories')->insertGetId(['program_id' => $prgID, 'plo_category' => $row->plo_category]); 
                $item['plo_category_id'] = $res;
            }
            $aData[$key] = $item;
        }
        DB::table('p_l_o_categories')->whereIn('plo_category_id', $setDel)->delete();
        $sPD = DB::table('program_learning_outcomes')->whereIn('plo_category_id', $setDel)->get(); 
        $setPendingDel = [];
        foreach($sPD as $obj){array_push($setPendingDel,$obj->pl_outcome_id);}
        //these no longer exist due to their category being destroyed
        DB::table('program_learning_outcomes')->whereIn('pl_outcome_id', $setPendingDel)->delete();
        DB::table('outcome_maps')->whereIn('pl_outcome_id', $setPendingDel)->delete();
        //*************
        //for each category:: crud for PLOs  //
        //**********
        $ploObjs = [];
        $existingPLOs =  \App\Models\ProgramLearningOutcome::where('program_id', $prgID)->get(); 
        $setPLOs = [];  //this is the set of ids for easy db access
        foreach($existingPLOs as $plo){array_push($setPLOs,$plo->pl_outcome_id);}
        $nSc = []; 

        foreach($aData as $cat){//
            if($cat['plo_category'] == "Uncategorized")$cat['plo_category'] = NULL;  
            $value = json_decode($cat['programOutcome']);
            if(is_array($value) && count($value) > 0){
                foreach($value as $row){
                    if(property_exists($row, "pl_outcome_id"))
                        array_push($nSc,$row->pl_outcome_id);
                    $arRow = json_decode(json_encode($row),true);//turns the obj to an array
                    $arRow['plo_category_id'] = $cat['plo_category_id']; //this will be used later
                    array_push($ploObjs,$arRow);
                }
            }  
        }

        $setDel = array_filter($setPLOs, function($element) use($nSc){  //filters from the db records those still present on the page. others are deleted
            return !(in_array($element, $nSc));
        });
                       //rather than updating it, so it should be fixed. doesnt work because the list contains the ids of moved records despite their having been deleted.
        foreach($ploObjs as $row){
            if(isset($row['pl_outcome_id'])){
                $id = $row['pl_outcome_id'];
                if(in_array($id, $setPLOs)){
                    if(isset($row['plo_category_id']) && $row['plo_category_id'])
                        ProgramLearningOutcome::where('pl_outcome_id', $id)
                            ->update(['plo_shortphrase' => $row['plo_shortphrase'], 'pl_outcome' => $row['pl_outcome'], 'plo_category_id' => $row['plo_category_id']]);
                    else
                        ProgramLearningOutcome::where('pl_outcome_id', $id)
                            ->update(['plo_shortphrase' => $row['plo_shortphrase'], 'pl_outcome' => $row['pl_outcome'], 'plo_category_id' => NULL]);
                }
            }
            else{
                if(isset($row['plo_category_id']) && $row['plo_category_id'])
                    ProgramLearningOutcome::create(['program_id' => $prgID, 'plo_shortphrase' => $row['plo_shortphrase'],
                        'pl_outcome' => $row['pl_outcome'], 'plo_category_id' => $row['plo_category_id']]);
                else
                    ProgramLearningOutcome::create(['program_id' => $prgID, 'plo_shortphrase' => $row['plo_shortphrase'],
                        'pl_outcome' => $row['pl_outcome'], 'plo_category_id' => NULL]);
            }

        }
        DB::table('program_learning_outcomes')->whereIn('pl_outcome_id', $setDel)->delete(); //by deleting here, I am avoiding the refactoring for now. Tis will delete and recreate a record
        DB::table('outcome_maps')->whereIn('pl_outcome_id', $setDel)->delete();  
              
    }
  
}
