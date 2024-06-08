<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class company_type extends Model
{
    use HasFactory;
    protected $fillable = ['company_type_title','status','remarks','created_by','updated_by'];

    public function createdBY()
    {
        return $this->belongsTo(User::class,'created_by');
    }
    public function updatedBY()
    {
        return $this->belongsTo(User::class,'updated_by');
    }
    public function companies()
    {
        return $this->hasMany(company_info::class,'company_type_id');
    }
}
