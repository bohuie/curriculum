<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class MappingScale extends Model
{
    use HasFactory;

    protected $primaryKey = 'map_scale_id';    

    public function programs()
    {
        return $this->belongsToMany(Program::Class, 'mapping_scale_programs', 'map_scale_id', 'program_id')->withTimestamps();
    }
    
    /*public function newPivot(Model $parent, array $attributes, $table, $exists, $using=NULL) {
        if ($parent instanceof MappingScale) {
            return new MappingScaleProgram($parent, $attributes, $table, $exists, $using=NULL);
        }
        return parent::newPivot($parent, $attributes, $table, $exists, $using=NULL);
    }*/
    public function mappingScalePrograms(){
        return $this->hasMany(MappingScaleProgram::Class);
    }
}
