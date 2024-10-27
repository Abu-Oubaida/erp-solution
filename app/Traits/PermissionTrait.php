<?php

namespace App\Traits;

use App\Models\Permission;

trait PermissionTrait
{
    private function permissions()
    {
        return (object) Permission::all()->pluck('name','name')->toArray();
    }
}
