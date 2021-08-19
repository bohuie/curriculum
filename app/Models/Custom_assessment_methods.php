<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Custom_assessment_methods extends Model
{
    use HasFactory;

    protected $primaryKey = 'custom_method_id';
    
    protected $table = 'custom_assessment_methods';

    protected $fillable = ['custom_methods'];
}
