<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OptionalPriorityCategories extends Model
{   
    use \Backpack\CRUD\app\Models\Traits\HasIdentifiableAttribute;
    use \Backpack\CRUD\app\Models\Traits\CrudTrait;
    use HasFactory;

    protected $primaryKey ='cat_id';
    
    protected $table = 'optional_priority_categories';    
    protected $fillable = [  
        'cat_id',    
        'cat_name'   
    ];
    public function OptionalPrioritySubcategories(){
        return $this->hasMany(OptionalPrioritySubcategories::class, 'cat_id', 'cat_id');
    }
}
