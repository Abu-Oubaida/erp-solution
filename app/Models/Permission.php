<?php

namespace App\Models;

use Laratrust\Models\LaratrustPermission;

class Permission extends LaratrustPermission
{
    public $guarded = [];

    protected $fillable = ['parent_id', 'name', 'display_name', 'description'];

    public function PermissionUser()
    {
        return $this->hasMany(PermissionUser::class,'parent_id');
    }

    public function childPermission()
    {
        return $this->hasMany(Permission::class,'parent_id')->orderBy('name','asc');
    }



}
