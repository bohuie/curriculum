<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseUser extends Model
{
    use HasFactory;

    protected $table = 'course_users';

    protected $primary = 'id';

    protected $guarded = ['permission'];

    protected $fillable = ['user_id', 'course_id'];

    public $incrementing = false;
}
