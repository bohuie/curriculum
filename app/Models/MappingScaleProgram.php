<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MappingScaleProgram extends Model
{
    use HasFactory;

    protected $primaryKey = ['map_scale_id','program_id'];

    public $incrementing = false;
    
    public function mappingScales()
    {
        return $this->belongsTo(MappingScale::Class);
    }
    
    public function programs()
    {
        return $this->belongsTo(Program::Class);
    }
}
