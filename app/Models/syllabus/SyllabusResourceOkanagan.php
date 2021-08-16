<?php

namespace App\Models\syllabus;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SyllabusResourceOkanagan extends Model
{
    use HasFactory;

    protected $table = 'syllabi_resources_okanagan';

    public $timestamps = false;

    protected $fillable = ['syllabus_id', 'o_syllabus_resource_id',];


}
