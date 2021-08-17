<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
//use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\Pivot;

class CourseOptionalPriorities extends Pivot
{
    use HasFactory;

    protected $primaryKey = ['op_id','course_id'];
    
    protected $table = 'course_optional_priorities';

    public $incrementing = false;
}
