<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class department extends Model
{
    use HasFactory;
    protected $fillable = ['company_id','dept_code', 'dept_name', 'status', 'remarks','created_by','updated_by',];

    public function getUsers()
    {
        return $this->hasMany(User::class,'dept_id');
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
