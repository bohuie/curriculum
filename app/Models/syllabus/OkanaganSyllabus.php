<?php

namespace App\Models\syllabus;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OkanaganSyllabus extends Model
{
    use HasFactory;

    protected $table = 'okanagan_syllabi';

    public $timestamps = false;
}
