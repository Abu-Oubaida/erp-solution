<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    use HasFactory;
    protected $table = 'sales_leads';
    protected $fillable = [ 'full_name', 'spouse', 'primary_mobile', 'primary_email', 'lead_main_profession_id', 'lead_sub_profession_id', 'lead_company', 'lead_designation', 'notes','status','created_by','updated_by','lead_status_id','sell_status','company_id','associate_id'];
    public function extraMobiles(){
        return $this->hasMany(ExtraMobile::class,'lead_id');
    }
    public function extraEmails(){
        return $this->hasMany(ExtraEmail::class,'lead_id');
    }
    public function source(){
        return $this->hasOne(Source::class,'lead_id');
    }
    public function preference(){
        return $this->hasOne(SalePreference::class,'lead_id');
    }
    public function leadMainProfession(){
        return $this->belongsTo(SalesProfession::class,'lead_main_profession_id');
    }
    public function leadSubProfession(){
        return $this->belongsTo(SalesProfession::class,'lead_sub_profession_id');
    }
}
