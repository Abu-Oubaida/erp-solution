<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermissionUser extends Model
{
    use HasFactory;
    protected $fillable = ['permission_name','parent_id'];

    public function permissionParent()
    {
        return $this->belongsTo(Permission::class,'parent_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
