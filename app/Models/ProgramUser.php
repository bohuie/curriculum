<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramUser extends Model
{
    use HasFactory;

    protected $table = 'program_users';

    protected $primary = 'id';

    protected $guarded = ['permission'];

    protected $fillable = ['user_id', 'program_id'];

    public $incrementing = false;
}
