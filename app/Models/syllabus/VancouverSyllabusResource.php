<?php

namespace App\Models\syllabus;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VancouverSyllabusResource extends Model
{
    use HasFactory;

    protected $table = 'vancouver_syllabus_resources';

    public $timestamps = false;

}
