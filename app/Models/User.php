<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use \Backpack\CRUD\app\Models\Traits\CrudTrait;
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function roles(){
        return $this->belongsToMany('App\Models\Role');
    }

    public function courses(){
        return $this->belongsToMany('App\Models\Course', 'course_users', 'user_id', 'course_id');
    }

    public function programs(){
        return $this->belongsToMany('App\Models\Program', 'program_users', 'user_id', 'program_id',);
    }

    public function syllabi(){
        return $this->belongsToMany('App\Models\syllabus\Syllabus', 'syllabi_users', 'user_id', 'syllabus_id',);
    }

    public function hasAnyRoles($roles){
        if($this->roles()->whereIn('role', $roles)->first()){
            return true;
        }
        return false;
    }

    public function hasRole($role){
        if($this->roles()->where('role', $role)->first()){
            return true;
        }
        return false;
    }


}
