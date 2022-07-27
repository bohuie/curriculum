<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ProgramContent extends Model
{
    use HasFactory;

    protected $primaryKey = 'program_id';
    protected $table = 'program_content';   
    protected $fillable = ['PLOs', 'mapping_scales', 'freq_dist_tables',  'CLOs_bar', 'assessment_methods_bar', 'learning_activities_bar'];
    protected $guarded = ['program_id'];
    
    public $incrementing = false;

    

}
