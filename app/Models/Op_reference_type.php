<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Op_reference_type extends Model
{
    use HasFactory;
    protected $fillable = [ 'name', 'code', 'description', 'status', 'created_by', 'updated_by'];

    public function createdBy()
    {
        $this->belongsTo(User::class, 'created_by', 'id');
    }
    public function updatedBy()
    {
        $this->belongsTo(User::class, 'updated_by', 'id');
    }
}
