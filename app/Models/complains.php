<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class complains extends Model
{
    use HasFactory;
    protected $fillable = ['id', 'user_id', 'title', 'priority', 'details', 'to_dept', 'status', 'progress', 'forward_to', 'forward_by', 'updated_by', 'document_img', 'created_at', 'updated_at'];
}
