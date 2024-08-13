<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class userProjectPermission extends Model
{
    use HasFactory;
    protected $fillable = ['date','user_id','project_id','company_id','created_by','updated_by'];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }
    public function projects()
    {
        return $this->belongsTo(branch::class,'project_id','id');
    }
}
