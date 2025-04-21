<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Data_archive_storage_package_delete_history extends Model
{
    use HasFactory;
    protected $fillable = ['old_id','status','package_name','package_size','package_type','old_created_by','old_created_at','old_updated_by','old_updated_at','created_by','updated_by'];
}
