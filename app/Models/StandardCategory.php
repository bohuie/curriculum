<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class StandardCategory extends Model
{
    use \Backpack\CRUD\app\Models\Traits\CrudTrait;
    use HasFactory;

    protected $table = "standard_categories";

    protected $primaryKey = 'standard_category_id';

    protected $fillable = ['sc_name','Standardtable'];
    
    public function courses()
    {
        return $this->hasMany(Course::class, 'standard_category_id', 'standard_category_id');
    }

    public function standards() {
        return $this->hasMany(Standard::class, 'standard_category_id', 'standard_category_id');
    }
    
    public function getStandardtableAttribute(){
        $catID = filter_input(INPUT_SERVER,'PATH_INFO');        
        $catID = explode("/",$catID)[3];
        $test = DB::table('standards')->where('standard_category_id', $catID)->get();
        return json_encode($test);
    }
    
     public function setStandardtableAttribute($value){
        $catID = filter_input(INPUT_SERVER,'PATH_INFO'); 
        $catID = explode("/",$catID)[3];
        $jdata = json_decode($value);
        if(!is_array($jdata))$jdata = [];
        $existingScales = Standard::where('standard_category_id', $catID)->get();
        $setScales = [];
        foreach($existingScales as $sc){array_push($setScales,$sc->standard_id);}
        $nSc = [];        
        foreach($jdata as $row)
            if(property_exists($row, "standard_id"))
                array_push($nSc,$row->standard_id);
        
        $setDel = array_filter($setScales, function($element) use($nSc){
            return !(in_array($element, $nSc));
        });
        foreach($jdata as $row){
            $id = -1;
            if(property_exists($row, "standard_id") && $row->standard_id != ""){
                $id = $row->standard_id;
            }
            if(in_array($id, $setScales))
                    Standard::where('standard_id', $id)->update(['s_shortphrase' => $row->s_shortphrase, 's_outcome' => $row->s_outcome]);            
            else{
                Standard::create(['standard_category_id' => $catID, 's_shortphrase' => $row->s_shortphrase, 's_outcome' => $row->s_outcome]);
            }
        }
        
        Standard::whereIn('standard_id', $setDel)->delete();
    }
}
