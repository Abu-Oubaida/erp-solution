<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deleted_history extends Model
{
    use HasFactory;
    protected $fillable = ['id', 'disk_name', 'path', 'type', 'created_by', 'created_at', 'updated_at'];
}
