<?php

namespace App\Models\syllabus;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VancouverSyllabus extends Model
{
    use HasFactory;

    protected $table = 'vancouver_syllabi';

    public $timestamps = false;
}
