<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExtraMobile extends Model
{
    use HasFactory;
    protected $table='sales_lead_extra_mobiles';
    protected $fillable = [ 'lead_id', 'mobile','status','created_by','updated_by','company_id'];
}
