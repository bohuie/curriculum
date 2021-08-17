<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LearningActivity extends Model
{
    use HasFactory;

    protected $primaryKey ='l_activity_id';
    protected $fillable = ['l_activity','course_id'];

    public function learningOutcomes(){
        return $this->belongsToMany('App\Models\LearningOutcome')->using('App\Models\OutcomeActivity');
    }

    public function course(){
        return $this->belongsTo('App\Models\Course');
    }
}
