<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssessmentMethod extends Model
{
    use HasFactory;

    protected $primaryKey ='a_method_id';
    protected $fillable = ['a_method','weight','course_id'];

    public function learningOutcomes() {
        return $this->belongsToMany('App\Models\LearningOutcome')->using('App\Models\OutcomeAssessment');
    }

    public function course() {
        return $this->belongsTo('App\Models\Course');
    }
}
