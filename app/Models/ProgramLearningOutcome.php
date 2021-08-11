<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramLearningOutcome extends Model
{
    use HasFactory;

    protected $primaryKey = 'pl_outcome_id';
    
    protected $fillable = ['program_id', 'pl_outcome', 'plo_shortphrase','pl_outcome_id','plo_category_id'];

    public function learningOutcomes(){    
        return $this->belongsToMany(LearningOutcome::class, 'outcome_maps', 'pl_outcome_id', 'l_outcome_id')->using('App\Models\OutcomeMap')->withPivot('map_scale_value')->withTimestamps(); 
    }

    public function category()
    {
        return $this->belongsTo('App\Models\PLOCategory','plo_category_id', 'plo_category_id');
    }

    // get the program that owns the program learning outcome
    // Eloquent will attempt to find a Program model with an id that matches the program_id column in the ProgramLearningOutcome model
    public function program() {
        /*
            @param Parent model
            @param foreign key in ProgramLearningOutcome model
            @param id/PK in parent model
        */
        return $this->belongsTo(Program::class, 'program_id', 'program_id');
    }


    
}
