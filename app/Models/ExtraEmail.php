<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExtraEmail extends Model
{
    use HasFactory;
    protected $table='sales_lead_extra_emails';
    protected $fillable = [ 'lead_id', 'email','status','created_by','updated_by','company_id'];
}
