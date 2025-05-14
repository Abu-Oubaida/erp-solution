<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalePreference extends Model
{
    use HasFactory;
    protected $table='sales_lead_preferences';
    protected $fillable = ['lead_id','created_by','updated_by','company_id','preference_note','apartment_type_id','apartment_size_id','floor_id','facing_id','view_id','budget_id'];
    public function apartmentType(){
         return $this->belongsTo(SalesLeadApartmentType::class,'apartment_type_id');
    }
    public function apartmentSize(){
         return $this->belongsTo(SalesLeadApartmentSize::class,'apartment_size_id');
    }
    public function floor(){
         return $this->belongsTo(SalesLeadFloor::class,'floor_id');
    }
    public function facing(){
         return $this->belongsTo(SalesLeadFacing::class,'facing_id');
    }
    public function view(){
         return $this->belongsTo(SalesLeadView::class,'view_id');
    }
    public function budget(){
         return $this->belongsTo(SalesLeadBudget::class,'budget_id');
    }	
}
