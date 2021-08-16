<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class MappingScaleCategory extends Model
{
    use \Backpack\CRUD\app\Models\Traits\CrudTrait;
    use HasFactory;

    protected $table = 'mapping_scale_categories';

    protected $primaryKey = 'mapping_scale_categories_id';


    protected $fillable = ['title', 'description', 'msc_title', 'Mappingtable'];


    public function mappingScales() {
        return $this->hasMany(MappingScale::class, 'mapping_scale_categories_id', 'mapping_scale_categories_id');
    }
    
     public function getMappingtableAttribute(){
        $catID = filter_input(INPUT_SERVER,'PATH_INFO');        
        $catID = explode("/",$catID)[3];
        $test = DB::table('mapping_scales')->where('mapping_scale_categories_id', $catID)->get();
        return json_encode($test);
    }
    
     public function setMappingtableAttribute($value){
        $catID = filter_input(INPUT_SERVER,'PATH_INFO'); 
        $catID = explode("/",$catID)[3];
        $jdata = json_decode($value);
        if(!is_array($jdata))$jdata = [];
        $existingScales = MappingScale::where('mapping_scale_categories_id', $catID)->get();
        $setScales = [];
        foreach($existingScales as $sc){array_push($setScales,$sc->map_scale_id);}
        $nSc = [];        
        foreach($jdata as $row)
            if(property_exists($row, "map_scale_id"))
                array_push($nSc,$row->map_scale_id);
        
        $setDel = array_filter($setScales, function($element) use($nSc){
            return !(in_array($element, $nSc));
        });
        foreach($jdata as $row){
            $id=-1;
            if(property_exists($row, "map_scale_id") && $row->map_scale_id != ""){
                $id = $row->map_scale_id;
            }
            if(in_array($id, $setScales))
                    MappingScale::where('map_scale_id', $id)->update(['title' => $row->title, 'abbreviation' => $row->abbreviation, 'description' => $row->description, 'colour' => $row->colour]);
            else{
                MappingScale::create(['mapping_scale_categories_id' => $catID, 'title' => $row->title, 'abbreviation' => $row->abbreviation, 'description' => $row->description, 'colour' => $row->colour]);
            }
        }
        
        MappingScale::whereIn('map_scale_id', $setDel)->delete();
    }
}
