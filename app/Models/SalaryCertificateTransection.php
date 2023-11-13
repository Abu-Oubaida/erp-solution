<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryCertificateTransection extends Model
{
    use HasFactory;
    protected $fillable = ['user_salary_certificate_data_id','dated','amount','challan_no','type','bank_name','created_by','updated_by'];
}
