<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// model for CourseLearningOutcomes
class LearningOutcome extends Model
{
    use \Backpack\CRUD\app\Models\Traits\CrudTrait;
    use HasFactory;
    
    protected $table = 'learning_outcomes';

    protected $primaryKey = 'l_outcome_id';

    protected $fillable = ["l_outcome", "clo_shortphrase", "course_id"];

    public function course(){
        return $this->belongsTo('App\Models\Course', 'course_id');
    }

    public function assessmentMethods(){
        return $this->belongsToMany('App\Models\AssessmentMethod', 'outcome_assessments','l_outcome_id', 'a_method_id')->using('App\Models\OutcomeAssessment')->withTimeStamps();
    }

    public function learningActivities(){
        return $this->belongsToMany('App\Models\LearningActivity', 'outcome_activities','l_outcome_id', 'l_activity_id')->using('App\Models\OutcomeActivity')->withTimeStamps();
    }

    public function programLearningOutcomes(){
        return $this->belongsToMany('App\Models\ProgramLearningOutcome', 'outcome_maps','l_outcome_id', 'pl_outcome_id')->using('App\Models\OutcomeMap')->withPivot('map_scale_id')->withTimeStamps();
    }

    public function standardOutcomeMap() {
        return $this->belongsToMany(Standard::class, 'standards_outcome_maps','l_outcome_id', 'standard_id')->using(StandardsOutcomeMap::class)->withPivot('standard_scale_id')->withTimeStamps();
    }
}
