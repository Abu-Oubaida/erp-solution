<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher_type_permission_user_delete_history extends Model
{
    use HasFactory;
    protected $fillable = ['old_id','voucher_type_id','user_id','old_created_at','old_updated_at','old_created_by','old_updated_by','created_by','updated_by'];
}
