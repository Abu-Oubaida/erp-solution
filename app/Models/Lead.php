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
}
