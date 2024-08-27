<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermissionUserHistory extends Model
{
    use HasFactory;

    protected $fillable = ['company_id','admin_id','user_id','permission_id','operation_name'];
}
