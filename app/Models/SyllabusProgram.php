<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SyllabusProgram extends Model
{
    use HasFactory;

    protected $table = 'syllabi_programs';

    protected $primaryKey = 'id';

    protected $guarded = 'id';

    protected $fillable = ['syllabus_id', 'program_id'];
}
