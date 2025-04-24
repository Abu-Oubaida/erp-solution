<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesLeadPreferencesLocation extends Model
{
    use HasFactory;
    protected $fillable = [ 'lead_id', 'location_id','status','created_by','updated_by','company_id'];
    
}
