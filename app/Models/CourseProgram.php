<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseProgram extends Model
{
    use HasFactory;

    protected $fillable = ['course_id', 'program_id','course_required', 'instructor_assigned', 'map_status'];
}
