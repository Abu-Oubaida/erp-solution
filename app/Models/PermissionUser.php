<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermissionUser extends Model
{
    use HasFactory;
    protected $fillable = ['company_id','user_id','permission_name','parent_id'];
    protected $table = 'permission_users';
    public function permissionParent()
    {
        return $this->belongsTo(Permission::class,'parent_id');
    }
    public function users()
    {
        return $this->hasMany(User::class,'id','user_id');
    }
}
