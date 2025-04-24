<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesProfession extends Model
{
    use HasFactory;
    protected $table='sales_lead_professions';
    protected $fillable = ['title','status','created_by','updated_by','company_id','parent_id','is_parent'];
}
