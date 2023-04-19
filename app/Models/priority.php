<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class priority extends Model
{
    use HasFactory;
    protected $fillable = ['status', 'title', 'priority_number', 'remarks', 'created_by', 'updated_by'];
}
