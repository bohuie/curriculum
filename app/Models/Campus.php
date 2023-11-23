<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campus extends Model
{
    use HasFactory;

    protected $table = 'campuses';

    protected $primaryKey = 'campus_id';

    protected $guarded = 'campus_id';

    protected $fillable = ['campus'];

    public function faculties()
    {
        return $this->hasMany(faculty::class, 'campus_id', 'campus_id');
    }
}
