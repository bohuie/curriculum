<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Program extends Model
{
    use \Backpack\CRUD\app\Models\Traits\CrudTrait;
    use HasFactory;

    protected $primaryKey = 'program_id';

    protected $table = 'programs';   
    

    protected $fillable = ['program', 'faculty', 'department',  'level', 'status'];

    protected $guarded = ['program_id'];
    
    

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_programs', 'program_id', 'course_id')->withPivot('course_required', 'instructor_assigned', 'map_status', 'note')->withTimestamps();
    }

    /* public function mappingScaleLevels()
    {
        return $this->hasManyThrough(MappingScale::Class, MappingScaleProgram::Class);
    }*/

    public function mappingScaleLevels()
    {
        return $this->belongsToMany(MappingScale::class, 'mapping_scale_programs', 'program_id', 'map_scale_id')->withTimestamps();
    }
    
    public function mappingScalePrograms()
    {
        return $this->hasMany(MappingScaleProgram::class, 'program_id', 'program_id');
    }
    
    /* public function newPivot(Model $parent, array $attributes, $table, $exists, $using = NULL) {
        if ($parent instanceof Program) {
            return new MappingScaleProgram($parent, $attributes, $table, $exists, $using = NULL);
        }
        return parent::newPivot($parent, $attributes, $table, $exists, $using = NULL);
    }*/
    
    public function users(){
        return $this->belongsToMany(User::class, 'program_users', 'program_id', 'user_id')->withPivot('permission');
    }

    // Eloquent automatically determines the FK column for the ProgramLearningOutcome model by taking the parent model (program) and suffix it with _id (program_id)
    public function programLearningOutcomes() {
        return $this->hasMany(ProgramLearningOutcome::class, 'program_id', 'program_id');
    }

    public function ploCategories() {
        return $this->hasMany(PLOCategory::class, 'program_id', 'program_id');
    }
}
