<?php

namespace App\Models\syllabus;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SyllabusResourceVancouver extends Model
{
    use HasFactory;

    protected $table = 'syllabi_resources_vancouver';

    public $timestamps = false;

    protected $fillable = ['syllabus_id', 'v_syllabus_resource_id',];


}
