<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rename_history extends Model
{
    use HasFactory;
    protected $fillable = ['status','message','disk_name','old_name','new_name','created_by'];
}
