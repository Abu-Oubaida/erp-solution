<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher_type_permission_user extends Model
{
    use HasFactory;
    protected $fillable = ['voucher_type_id','user_id','company_id','created_by','updated_by'];
    protected $table = 'voucher_type_permission_user';
    public function users(){
       return $this->hasMany(User::class,'id');
    }

    public function user(){
        return $this->belongsTo(User::class,'user_id');
     }
}
