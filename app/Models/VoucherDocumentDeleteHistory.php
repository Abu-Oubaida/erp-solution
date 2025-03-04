<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VoucherDocumentDeleteHistory extends Model
{
    use HasFactory;
    protected $fillable = ['old_id','company_id','voucher_type_id','voucher_date','voucher_number','file_count','remarks','old_created_by','old_updated_by','old_created_at','old_updated_at','created_by','updated_by'];
}
