<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalePreference extends Model
{
    use HasFactory;
    protected $table='sales_lead_preferences';
    protected $fillable = ['lead_id','created_by','updated_by','company_id','preference_note','apartment_type_id','apartment_size_id','floor_id','facing_id','view_id','budget_id'];	
}
