<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
//use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class StandardsOutcomeMap extends Pivot
{
    use HasFactory;

    protected $table = 'standards_outcome_maps';

    protected $primaryKey = ['standard_id', 'course_id'];

    public $incrementing = false;
}
