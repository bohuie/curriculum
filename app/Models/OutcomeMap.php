<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\Pivot;

class OutcomeMap extends Pivot
{
    use HasFactory;

    protected $primaryKey = ['l_outcome_id','pl_outcome_id'];
    
    protected $table = 'outcome_maps';

    public $incrementing = false;
}
