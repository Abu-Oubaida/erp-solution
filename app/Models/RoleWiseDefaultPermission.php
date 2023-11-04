<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoleWiseDefaultPermission extends Model
{
    use HasFactory;
    protected $fillable = ['status','role_id','role_name','permission_id','permission_name','created_by','updated_by'];
    public function GetRole()
    {
        return $this->belongsTo(Role::class,'role_id');
    }
    public function GetPermission()
    {
        return $this->belongsTo(Permission::class,'permission_id');
    }
}
