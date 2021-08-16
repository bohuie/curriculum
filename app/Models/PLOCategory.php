<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PLOCategory extends Model
{
    use HasFactory;

    protected $primaryKey = 'plo_category_id';
    
     protected $fillable = ['program_id','plo_category','program_id'];

    public function plos()
    {
        return $this->hasMany(ProgramLearningOutcome::class, 'plo_category_id', 'plo_category_id');
    }
}
