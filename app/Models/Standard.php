<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Standard extends Model
{
    use HasFactory;

    protected $primaryKey = 'standard_id';

    protected $fillable = ['standard_id', 'sc_id', 's_shortphrase', 's_outcome'];

    public function learningOutcomes(){
        return $this->belongsToMany(LearningOutcome::class)->using(StandardsOutcomeMap::class); 
    }
}
