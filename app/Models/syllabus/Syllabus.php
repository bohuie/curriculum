<?php

namespace App\Models\syllabus;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Syllabus extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'course_id'];

    protected $table = 'syllabi';

    public function users(){
        return $this->belongsToMany('App\Models\User', 'syllabi_users', 'syllabus_id', 'user_id')->withPivot('permission');
    }

}
