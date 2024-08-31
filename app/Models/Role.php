<?php

namespace App\Models;

use Laratrust\Models\LaratrustRole;

class Role extends LaratrustRole
{
    public $guarded = [];
    protected $fillable = ['company_id','name','display_name','description','description','created_by','updated_by'];

    public function getUsers()
    {
        return $this->belongsToMany('App\Models\User', 'role_user', 'role_id', 'user_id');
    }
    public function createdBy()
    {
        return $this->belongsTo(User::class,'created_by');
    }
    public function updatedBy()
    {
        return $this->belongsTo(User::class,'updated_by');
    }
    public function company()
    {
        return $this->belongsTo(company_info::class,'company_id');
    }
}
