<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCompanyPermission extends Model
{
    use HasFactory;
    protected $fillable = ['role_id','user_id','company_id','created_by','updated_by'];

    public function users()
    {
        return $this->hasMany(User::class,'id','user_id');
    }
    public function userRole()
    {
        return $this->belongsTo(Role::class,'role_id','id');
    }
    public function companies()
    {
        return $this->belongsTo(company_info::class,'company_id','id');
    }
    public function createdBy()
    {
        return $this->belongsTo(User::class,'created_by');
    }
    public function updatedBy()
    {
        return $this->belongsTo(User::class,'updated_by');
    }
}
