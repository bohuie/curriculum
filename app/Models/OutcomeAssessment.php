<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
//use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\Pivot;

class OutcomeAssessment extends Pivot
{
    use HasFactory;

    protected $table = 'outcome_assessments';

    protected $primaryKey = ['l_outcome_id', 'a_method_id'];

    public $incrementing = false;
}
