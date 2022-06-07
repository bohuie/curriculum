<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OptionalPrioritiesSubdescription extends Model
{
    use HasFactory;

    protected $table = 'optional_priorities_subdescriptions';

    protected $primaryKey = 'op_subdesc';

    protected $fillable = ['op_subdesc'];
}
