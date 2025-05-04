<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\company_info;
use App\Models\User;

class SalesEmployeeEntry extends Model
{
    use HasFactory;
    protected $fillable = ['employee_id','created_by','updated_by','company_id'];
    public function company(){
        return $this->belongsTo(company_info::class,'company_id','id');
    }
    public function user(){
        return $this->belongsTo(User::class,'employee_id','id');
    }
}
