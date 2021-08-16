<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StandardScale extends Model
{
    use HasFactory;

    protected $table = 'standard_scales';

    protected $primaryKey = 'standard_scale_id';

    protected $fillable = ['standard_scale_id','scale_category_id','title', 'abbreviation', 'description', 'colour'];
    
    public function learningOutcomes(){
        return $this->belongsToMany(LearningOutcome::class)->using(StandardsOutcomeMap::class); 
    }

    public function standardsScaleCategory() {
        return $this->belongsTo(StandardsScaleCategory::class, 'scale_category_id', 'scale_category_id');
    }
}
