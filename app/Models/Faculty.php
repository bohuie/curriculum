<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faculty extends Model
{
    use HasFactory;

    protected $table = 'faculties';

    protected $primaryKey = 'faculty_id';

    protected $guarded = 'faculty_id';

    protected $fillable = ['faculty', 'campus_id'];

    public function campus()
    {
        return $this->belongsTo(Campus::class, 'campus_id', 'campus_id');
    }

    public function departments()
    {
        return $this->hasMany(Department::class, 'department_id', 'department_id');
    }
}
