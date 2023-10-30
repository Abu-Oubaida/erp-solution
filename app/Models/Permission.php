<?php

namespace App\Models;

use Laratrust\Models\LaratrustPermission;

class Permission extends LaratrustPermission
{
    public $guarded = [];

    protected $fillable = ['parent_id', 'name','is_parent', 'display_name', 'description'];

    public function PermissionUser()
    {
        return $this->hasMany(PermissionUser::class,'parent_id');
    }

    public function childPermission()
    {
        return $this->hasMany(Permission::class,'parent_id')->where('is_parent',null)->orderBy('name','asc');
    }
    public function parentName()
    {
        return $this->belongsTo(Permission::class,'parent_id');
    }



}
