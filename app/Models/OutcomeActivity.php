<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\Pivot;

class OutcomeActivity extends Pivot
{
    use HasFactory;

    protected $table = 'outcome_activities';

    protected $primaryKey = ['l_outcome_id', 'l_activity_id'];

    public $incrementing = false;
}
