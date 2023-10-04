<?php

namespace App\Models;

use Laratrust\Models\LaratrustPermission;

class Permission extends LaratrustPermission
{
    public $guarded = [];

    protected $fillable = ['parent_id', 'name', 'display_name', 'description'];

}
