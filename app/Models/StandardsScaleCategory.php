<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class StandardsScaleCategory extends Model
{
    use \Backpack\CRUD\app\Models\Traits\CrudTrait;
    use HasFactory;

    protected $table = 'standards_scale_categories';

    protected $primaryKey = 'scale_category_id';

    protected $fillable = ['name', 'description', 'Scaletable'];


    public function courses()
    {
        return $this->hasMany(Course::class, 'scale_category_id', 'scale_category_id');
    }

    public function ministryStandardScales()
    {        
        return $this->hasMany(StandardScale::class, 'scale_category_id', 'scale_category_id');
    }
    
    public function getScaletableAttribute(){
        $catID = filter_input(INPUT_SERVER,'PATH_INFO');        
        $catID = explode("/",$catID)[3];
        $test = DB::table('standard_scales')->where('scale_category_id', $catID)->get();
        return json_encode($test);
    }
    
     public function setScaletableAttribute($value){
        $catID = filter_input(INPUT_SERVER,'PATH_INFO'); 
        $catID = explode("/",$catID)[3];
        $jdata = json_decode($value);
        if(!is_array($jdata))$jdata = [];
        $existingScales = StandardScale::where('scale_category_id', $catID)->get();
        $setScales = [];
        foreach($existingScales as $sc){array_push($setScales,$sc->standard_scale_id);}
        $nSc = [];        
        foreach($jdata as $row)
            if(property_exists($row, "standard_scale_id"))
                array_push($nSc,$row->standard_scale_id);
        
        $setDel = array_filter($setScales, function($element) use($nSc){
            return !(in_array($element, $nSc));
        });
        foreach($jdata as $row){
            $id=-1;
            if(property_exists($row, "standard_scale_id")){
                $id = $row->standard_scale_id;
            }
            if(in_array($id, $setScales))
                    StandardScale::where('standard_scale_id', $id)->update(['title' => $row->title, 'abbreviation' => $row->abbreviation, 'description' => $row->description, 'colour' => $row->colour]);
            else{
                StandardScale::create(['scale_category_id' => $catID, 'title' => $row->title, 'abbreviation' => $row->abbreviation, 'description' => $row->description, 'colour' => $row->colour]);
            }
        }
        
        StandardScale::whereIn('standard_scale_id', $setDel)->delete();
    }
}
