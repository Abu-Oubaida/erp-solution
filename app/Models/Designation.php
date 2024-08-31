<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Designation extends Model
{
    use HasFactory;

    protected $fillable = ['company_id','title','status','priority','remarks','created_by','updated_by'];

    public function getUsers()
    {
        $this->hasMany(User::class,'designation');
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
