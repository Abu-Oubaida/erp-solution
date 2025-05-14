<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Source extends Model
{
    use HasFactory;
    protected $table='sales_lead_sources';
    protected $fillable = [ 'lead_id', 'main_source_id', 'sub_source_id','created_by','updated_by','company_id','reference_name'];
    public function leadMainSource(){
        return $this->belongsTo(SalesLeadSourceInfo::class,'main_source_id');
    }
    public function leadSubSource(){
        return $this->belongsTo(SalesLeadSourceInfo::class,'sub_source_id');
    }
}
