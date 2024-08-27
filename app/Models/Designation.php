<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Designation extends Model
{
    use HasFactory;

    protected $fillable = ['company_id','title','status','priority','remarks','created_by','updated_by'];

    public function getUser()
    {
        $this->hasMany(User::class,'designation');
    }
}
